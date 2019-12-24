<?php

    /*
    * KingAuthor -- a shitty static template-based blog
    * system, written for no reason other than "why not."
    * 
    * This system is proprietary and not really meant for
    * external use. If you've gained access to this code,
    * congratulations, let me know how so I can unfuck my
    * security schema.
    * 
    * Copyright 2019-2020 William Preston / Turnip Collective
    * 
    * Please don't redistribute this code.
    */

    define('LOCKOUT', 1);
    require('./core/global.php');
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
            <h2>Create New Post</h2>
            <div id="content-inner">
                <form id="new-post-form" name="new-post-form" method="post" action="submitpost.php">
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
                        <input type="text" name="post-title" placeholder="Post Title" autofocus>
                        <textarea id="md-editor" name="post-content"> </textarea>
                        <button type="button" class="next" id="step-1-next" onclick="openNextAccordionPanel();">Next</button>
                    </div>

                    <h3 class="mouseover">Step 3: Sign Your Name</h3>
                    <div class="accordion">
                        <input type="text" name="post-author" value="<?php print KA_DEFAULT_AUTHOR; ?>" placeholder="Name Required, Smartass">
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
                // $accordion.accordion("activate",next); // pre jQuery UI 1.10
                acc.accordion("option","active",next);
            }

            
        </script>
    </body>
</html>