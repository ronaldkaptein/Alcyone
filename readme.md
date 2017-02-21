# Alcyone

## A minimalistic Markdown-based tool for creating websites

Alcyone is a very basic content managing system for generating websites, based on markdown. Static pages and (blog) posts can be written in Markdown, uploaded, and they are then automatically added to the site. Very minimal but functional. 

For a simple demo of alcyone in action, see [here](http://ronaldkaptein.nl/alcyone_demo).

## Setup

To generate a website:

Add basic info for the website to the file `content/siteconfig.md`. Currently supported data is:

- `author`: Used in footer
- `sitetitle`: Title for website. Also used for header

Note that this file should have `type: hidden` on top, otherwise it may appear on the site.

Add content to the directory `/content/`. All content must be written in
[markdown](http://daringfireball.net/projects/markdown/) (extension .md). A
small header with custom fields determines how the content is shown. Syntax of
the header is key: value.

- `type`: "post" for a blog post, "static" for a static page, "hidden" for a hidden page.
- `title`: title of the post/static page
- `date`: date of a post, in format yyyy-mm-dd, e.g. 2013-11-23. Is ignored for
  static pages
- `order`: allows for manual sorting of posts/static pages in a list  

Some examples files are already present in `/content/`

By default, the newest post is shown on the home page. This can be changed in
`index.php` if wanted. 

## Internal links

Internal links can be used in the markdown file using the syntax "&#91;&#91;link text:filename.md&#93;&#93;". Where
`filename.md` is an existing file in the `content` directory. The extension `.md` can be omitted if wanted, it is then
added automatically. 

## Styling

If wanted, the style of the website can be changed by editing the file `user.css` in the `content` directory. There is also a file
`alcyone.css` in the main directory, but it is advised not to edit this file, because it may be changed during an
update, in which case user changes are lost. The user can override the settings in `alcyone.css` in `user.css`. 

## Advanced


### Including page or post list
Post lists and static page menus are generated using the php-function
`includepagelist`, which lives in `/functions/phpfunctions.php`. This function
needs one obligatory argument, namely the type of page to show (e.g. `posts` or
`static`, but custom types are possible). All other arguments are optional, and
should be given in format "key",value:

- `sortkey`: key to sort on (e.g. date, order), default is date
- `cssclass`: css class to style list (default staticlist)
- `yearheaders`: use year subheaders in post list (default 0)
- `print`: print output (default 1)
- `includehomelink`: include link to home in static page menu (default 0)
- `homelink`: link to home (default `\`)
- `homelinktext`: text for home link (default "home")

`includepagelist` also returns the name of the page that is sorted first, e.g.
the newest posts when sorted by date. 

The example file `blog.md` contains an example of how to generate a list of all blog posts.

See the top of `phpfunctions.php` for more info.

### Including extra header

Extra content can be added to the header of `index.php`. This can be useful for e.g. including Google fonts, or for adding tracking code for web analytics. This can be done by creating a file `user_header.html` with the extra content in the `content` directory. This file should not contain `<header>` tags, only the text to be added to the default header. 

## To do

See [todo.md](todo.md) for a list of things I may implement in the future
