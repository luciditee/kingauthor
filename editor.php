<?php

/*
 * KingAuthor -- a shitty static template-based blog
 * system, written for no reason other than "why not."
 * 
 * This system is licensed under the MIT license.
 * 
 * Copyright 2019-2020 William Preston / Turnip Collective
 * 
 */

    define('LOCKOUT', 1);
    require('./core/global.php');
    $preloadedArticle = null;

    if (isset($_GET['article'])) {
        $articleIndex = intval($_GET['article']);
        if ($articleIndex == 0) {
            header('Location: index.php?error=badArticleIndex&found='.urlencode($_GET['article']));
            exit;
        }

        // handle deletion first
        if (isset($_GET['delete'])) {
            if ($_GET['delete'] == 'true') {
                $r = $db->deleteSpecificPost($articleIndex);
                log_append("Result: " . print_r($r, true));
                header('Location: index.php?msg=deleted&which=' . $articleIndex . '&result=' . $r);
                exit;
            }
        }

        // deletion is irrelevant if we're here, attempt to preload
        $preloadedArticle = $db->getSpecificPost($articleIndex);
        if ($preloadedArticle == null) {
            header('Location: index.php?error=failedLoad');
            exit;
        }
    }

    function getPreloadedValue($key) {
        global $preloadedArticle; // AAAAAAAAAAAAAAAAHHHHHHHHHHHHHH
        if ($preloadedArticle == null) return "";
        else return $preloadedArticle[$key];
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Post Editor</title>
        <?php include('./templates/head.template.php'); ?>
    </head>
    <body>
        <h1>Static Content Editor</h1>
        
        <div id="main-content">
            <h2><?php print $preloadedArticle == null ? "Create New" : "Edit Existing"; ?> Post</h2>
            <div id="content-inner">
                <form id="new-post-form" name="new-post-form" method="post" action="submitpost.php<?php if ($preloadedArticle != null) print("?update=".$preloadedArticle['id']); ?>">
                    <h3 class="mouseover">Step 1: Post Category</h3>
                    <div id="category-select" class="accordion">
                        <p>While not required, it is highly recommended to pick at least one category to stick this post inside, for SEO and findability purposes. If you select nothing, the first category in <code>config.php</code>, currently set to <strong><?php print $_KA_CATEGORIES[0]; ?></strong>, will be used instead.</p>
                        <select multiple="multiple" id="cat-select" name="cat-select[]">
                            <?php
                                $i = 0;
                                foreach ($_KA_CATEGORIES as $cat) {
                                    // First category is the 'default' and is always skipped
                                    // This category gets used internally if you don't specify one
                                    if ($i == 0) {
                                        $i++;
                                        continue;
                                    }

                                    ?>
                                    <option value='<?php echo $i; ?>'><?php echo $cat; ?></option>
                                    <?php
                                    $i++;
                                }
                            ?>
                        </select>
                        <p>When you are satisfied with your category selections, press <strong>Next</strong>.

                        <button type="button" class="next" id="step-1-next" onclick="openNextAccordionPanel();">Next</button>
                    </div>
                    
                    <h3 class="mouseover">Step 2: Write the Post</h3>
                    <div class="accordion">
                        <input type="text" name="post-title" placeholder="Post Title" value="<?php print getPreloadedValue("title"); ?>" autofocus>
                        <textarea id="md-editor" name="post-content"><?php print getPreloadedValue("content"); ?></textarea>
                        <button type="button" class="next" id="step-1-next" onclick="openNextAccordionPanel();">Next</button>
                    </div>

                    <h3 class="mouseover">Step 3: Sign Your Name</h3>
                    <div class="accordion">
                        <input type="text" name="post-author" value="<?php print $preloadedArticle == null ? KA_DEFAULT_AUTHOR : getPreloadedValue('author'); ?>" placeholder="Name Required, Smartass">
                        <button type="button" class="next" id="step-1-next" onclick="openNextAccordionPanel();">Next</button>
                    </div>

                    <h3 class="mouseover">Step 4: Save the Post</h3>
                    <div class="accordion">
                        
                        <div class="switch-wrapper">
                            <div class="onoffswitch">
                                <input type="checkbox" name="public-mode" class="onoffswitch-checkbox" id="publicmodeswitch">
                                <label class="onoffswitch-label" for="publicmodeswitch"></label>
                            </div>
                            <p><strong>Public Mode</strong> - <span class="description">Check this box to mark the post public; otherwise, the post will be placed into the database, but will be exempt from the content generation routine and will also be hidden from the "Link to Post" listing when editing or creating posts.</span></p>
                        </div>
                        <?php 
                        
                            if ($preloadedArticle != null) {
                                if (!$preloadedArticle['hidden']) {
                                    ?>
                        <!-- generated by PHP because we detected a public article preloaded -->
                        <!-- and for some reason doing this by just injecting 'checked' into the tag -->
                        <!-- isn't sufficient and doesn't work in firefox? need further testing -->
                        <script type="text/javascript">
                            var publicBox = $('#publicmodeswitch');
                            publicBox.prop("checked", true);
                        </script>
                                    <?php
                                }
                            }
                        
                        ?>
                        <button class="submit" type="submit" form="new-post-form">Save Post</button>
                    </div>

                    
                    <div id="left-fixed">
                        <div id="link-other-article">
                            <h3>Link to Post</h3>
                            <ul>
                                <?php

                                $posts = $db->getAllPosts();
                                foreach ($posts as $post) {
                                     if ($post['hidden'] == true) continue;
                                ?>
                                <li onclick="insertToMarkdown('{<?php print KA_TOKEN_PREFIX . KA_TOKEN_DELIM . KA_TOKEN_INTLINK . KA_TOKEN_DELIM . $post['id'] . KA_TOKEN_DELIM . 'Link text here'; ?>}')">
                                    <?php print $post['title']; ?>
                                </li>
                                <?php
                                }

                                ?>
                            </ul>
                        </div>
                    </div>
                
                </form>
            </div>
        </div>
        
        <?php include('./templates/copyright.template.php'); ?>

        <script src="./core/deps/js/jquery.multi-select.js" type="text/javascript"></script>
        <script>
            // Set up markdown editor, accordion, and multiselection
            var simplemde = new SimpleMDE({ element: document.getElementById("md-editor") });
            var acc = $('#new-post-form');
            var leftPanel = $('#left-fixed');
            const postPanel = 1; // which accordion panel is the 'write post' area in?
            $('#cat-select').multiSelect();
            acc.accordion({
                heightStyle: "content",
                activate: function( event, ui ) {
                    if (acc.accordion("option","active") == postPanel) {
                        leftPanel.fadeIn();
                    } else {
                        leftPanel.fadeOut();
                    }
                }
            });

            <?php
                if ($preloadedArticle != null) {
                    foreach ($preloadedArticle['tags'] as $cat) {
                        if ($cat == 0) continue;
                        ?>
            // generated by PHP -- preselect categories for the article
            // we preloaded earlier
            $('#cat-select').multiSelect('select', '<?php print $cat; ?>');
                        <?php
                    }
                }
            ?>

            // "add link to post" feature
            function insertToMarkdown(text) {
                var pos = simplemde.codemirror.getCursor();
                simplemde.codemirror.setSelection(pos, pos);
                simplemde.codemirror.replaceSelection(text);
            }

            // suppress default behavior for 'add link' feature
            $('#link-other-article').click( function (e) {
                e.stopPropagation();
            });

            // suppress default behavior for return key
            $(document).keypress(
                function(event){
                    if (event.which == '13') {
                    event.preventDefault();
                    }
                }
            );

            // easy function for 'next' button
            function openNextAccordionPanel() {
                var current = acc.accordion("option","active"),
                    maximum = acc.find("h3").length,
                    next = current+1 === maximum ? 0 : current+1;
                acc.accordion("option","active",next);
            }

            
        </script>
    </body>
</html>