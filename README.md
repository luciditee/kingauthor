# KingAuthor
KingAuthor is a static CMS that no one asked for. It was made for situations where the web site in question would benefit from having a CMS, like a dev blog, but the intended target is for static hosting, such as an Amazon AWS S3 bucket.

It is intended to be ran in a local LAMP-like environment, though the only real requirement is the ability to execute PHP scripts as web pages, and a PHP installation that includes sqlite as a PDO driver--which is to say, nearly all PHP5/PHP7 setups.

## Why?

I wanted to write a static content generator for fun without the crutches of Lumen/Laravel/Composer/everything nice about PHP these days, as a fun throwback. There's ~~probably~~ almost certainly some bad decisions in this code, which is why--while the outputted static content is fine to use anywhere--I don't recommend running this code on a live web server somewhere. *Use it locally.*

Emphasis mine: there is literally no reason to use this in production for anything other than 'for fun' if your intent is to hunt down a CMS you can use for your website. I just wanted to write my own for the hell of it. If you want a serious static content system, take a look at [Jekyll](https://jekyllrb.com/) instead.

If you still want to give this the time of day. I'm open to pull requests and the setup section below explains how to get things going.

## Setup

Clone it, then point your `sites-available` or whatever applicable settings to the directory the git repo exists so you can browse to it at `http://localhost/index.php` or similar. Then, `cd` to the repo directory, and `cd` into the `conf` director, and type the following:

```
$ cp ./blog.db.example ./blog.db && cp ./config.example.php ./config.php
```

Finally, edit `config.php`'s values to your liking. They should be self explanatory. When setting up your templates, refer to the Usage section below for info on how that works.

There are no user accounts, nor sessions. I cannot emphasize enough that **this is meant to be run locally**. If you host it on a public-facing web server, put it behind an HTTP AUTH or something, but for the love of God, make sure the output directory isn't pointing to your production server, because that opens your site up to defacement.

## Usage

Drop your template files into the `page-templates` directory. Edit `config.php` as needed to point to which template file belongs to which template--blog post, page, and so on. Edit them to use the following template macros to ensure it drops the correct values in the correct place:

* {KA:PostTitle} - The title of a blogpost.
* {KA:PageTitle} - The title of a page (as opposed to a blogpost).
* {KA:PostContent} - The content of a post.
* {KA:PageContent} - The content of a page.
* {KA:PostAuthor} - The author of a blog post.
* {KA:PostTimestamp} - The timestamp of when a blog post was last edited.
* {KA:PostCategory} - The category a post can be found within.
* {KA:InternalLink:N:LinkTextGoesHere} - A link to another blog post (NOT page). Change N to the ID of the post and LinkTextGoesHere to whatever text you want.

Once all this is done, you can start creating posts and pages within the application itself by pointing your browser to it @ `localhost` or, god forbid, if you've hosted it somewhere public. 

**JavaScript is required to be enabled for this web application to be useful.** Again, this is meant to be run locally, so trust is inherent. Sorry about that.

When you've made the posts you want, you can go to the Dashboard and press the `Generate Content` button, and it'll generate all of your pages for you.

Congrats! Now you have a bunch of .html files you can dump on your hosting service and reap the benefits of cheaper hosting or faster loading times, provided your templates aren't massively heavyweight.

## License

KingAuthor is licensed under the MIT license. See `LICENSE` at the root of the repo for more info.