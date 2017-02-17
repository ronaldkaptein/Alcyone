# Alcyone

## A minimalistic Markdown-based tool for creating websites

Alcyone is a very basic content managing system for generating websites, based
on markdown. Static pages and (blog) posts can be written in Markdown, uploaded, 
and they are then automatically added to the site. Very minimal but functional. 

For a simple demo of alcyone in action, see
[here](http://ronaldkaptein.nl/alcyone_demo).

Two steps to generate a website:

## 1

Add basic info such as author and website title to the top op `index.php`.

## 2

Add content to the directory `/content/`. All content must be written in
[markdown](http://daringfireball.net/projects/markdown/) (extension .md). A
small header with custom fields determines how the content is shown. Syntax of
the header is key: value.

- `type`: "post" or "static".
- `title`: title of the post/static page
- `date`: date of a post, in format yyyy-mm-dd, e.g. 2013-11-23. Is ignored for
  static pages
- `order`: allows for manual sorting of posts/static pages in a list  

Some examples files are already present in `/content/`

By default, the newest post is shown on the home page. This can be changed in
`index.php`. 

## Styling

The style of the website can be changed using css.

## Advanced

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

See the top of `phpfunctions.php` for more info.

## To do

See [todo.md](todo.md) for a list of things I may implement in the future
