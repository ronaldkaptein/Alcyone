<!--
alcyone - A simple php and Markdown based tool for creating websites

Copyright 2013  Ronald Kaptein
<https://bitbucket.org/ronaldk/alcyone>

alcyone is released under the terms of the GNU General Public License, version
3. See the file license.txt included %  with UPMOVE, or
http://www.gnu.org/licenses/gpl.html
-->
<head>

<?php

include_once 'functions/phpfunctions.php';

//Get user settings
$author=get_metadata("siteconfig.md","author");
$sitemaintitle=get_metadata("siteconfig.md","sitetitle");
$footertext="All &copy; ".$author;

//Get page from argument ?q=pagename
if (!isset($_GET['q'])) //argument q is not passed
   $file="";
else
{
   $file = $_GET['q']; 
   $file = "$file";
}
if (is_file("content/".$file)) { 
   $thefile = $file; 
   $pagetitle=$sitemaintitle." - ".get_metadata($thefile,"title");
}
else
{ 
   $thefile=""; 
   $pagetitle=$sitemaintitle;
}  
?> 

<title><?php echo $pagetitle ?></title>
<meta name="description" content="<?php echo $sitemaintitle ?>" >
<meta name="keywords" content="<?php echo $sitemaintitle ?>" >
<meta name="author" content="<?php echo $author ?>" >
<meta http-equiv="content-type" content="text/html;charset=UTF-8" >
<link rel="stylesheet" type="text/css" href="style.css" title="style" >
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<div id="container">
<div id="header">
   <a href="index.php"><?php echo $sitemaintitle ?></a>
</div>
   <div id="menu">
      <!--Make menu of static pages-->
      <?php includepagelist("static","offset",0,"cssclass","staticlist","sortkey","order","includehomelink",1); ?>
   </div>

   <div id="content">
      <?php
      //If no file specified, show newest post:
      if (empty($file)){
         $file=includepagelist("post","print",0);
      }

      //Include the content:
      includemarkdown($file);

      //show navigation to next/previous post if type is post
      $type=get_metadata($file,"type");

      if (strcmp($type,"post") == 0)
      {
      postnavigation($file);
      }
      ?>
   </div>
<div id="footer">
   <?php echo $footertext ?>
</div>
</div>

</body>
