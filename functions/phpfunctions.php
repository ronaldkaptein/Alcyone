<?php

include_once 'markdown.php';

function includepagelist($typetoinclude)
{
   #Check for optional arguments in format "key",value
   $sortkey="date";
   $cssclass="staticlist";
   $yearheaders=0;
   $print=1;
   $includehomelink=0;
   $homelink="";
   $homelinktext="home";
   $includedate=0;

   if ($typetoinclude === "posts"){
      $typetoinclude = "post";}

   $numargs = func_num_args();
   $theargs=func_get_args();

   #Defaults:
   $OFFSET=0;
   $N=0;
   if ( $numargs > 1) {
      for ($i=1; $i<$numargs; $i=$i+2) {
         switch ($theargs[$i]) {
         case 'offset':
            case 'OFFSET';
            $OFFSET=$theargs[$i+1];
            break;
         case 'n':
         case 'N':
            $N=$theargs[$i+1];
            break;
         case 'sortkey':
            $sortkey=$theargs[$i+1];
            break;
         case 'cssclass':
            $cssclass=$theargs[$i+1];
            break;
         case 'yearheaders':
            $yearheaders=$theargs[$i+1];
            break;
         case 'print':
            $print=$theargs[$i+1];
            break;
         case 'includehomelink':
            $includehomelink=$theargs[$i+1];
            break;
	     case 'includedate':
			$includedate=$theargs[$i+1];
			break;
         case 'homelink':
            $homelink=$theargs[$i+1];
            break;
         case 'homelinktext':
            $homelinktext=$theargs[$i+1];
            break;
         }
      }
   }

   $i=0;
   if ($dirhandle = opendir('content')) 
   {
      while (false !== ($entry = readdir($dirhandle))) 
      {
         $lastchars = substr($entry, -3, 3);
         #Only include markdown files:
         if ( (strcmp($lastchars,".md") == 0))
         {
            $thefile = $entry;
            $thecontent = file_get_contents("content/".$thefile);

            $thetitle=get_metadata($thefile,"title");
            $thetype=get_metadata($thefile,"type");
            $theorder=get_metadata($thefile,"order");
            $thedate=get_metadata($thefile,"date");

            if (strcmp($thetype,$typetoinclude) == 0 ) {
               $file[$i]=$thefile;
               $content[$i]=$thecontent;
               $order[$i]=$theorder;
               $title[$i]=$thetitle;
               $date[$i]=$thedate;
               $year[$i]=substr($thedate,0,4);
               $i++;
            }
         }
      }
      closedir($dirhandle);
   }
   $noffiles=$i;

   if ( $includehomelink == 1 ){
      $file[$i+1]=$homelink;
      $order[$i+1]=0;
      $date[$i+1]="9999-99-99";
      $title[$i+1]=$homelinktext;
      $year[$i+1]="9999";
      $content[$i+1]="";
      $noffiles++;
   }

   if (strcmp($sortkey,"date") == 0) {
      array_multisort($date,SORT_DESC,$content,$title,$file,$year);
   }
   elseif (strcmp($sortkey,"order") == 0) {
      array_multisort($order,$content,$title,$file,$year);
   }

   if ($N == 0 | $N > $noffiles | $N+$OFFSET>$noffiles) 
   {
      $end=$noffiles;
   }
   else
   {
      $end=$N+$OFFSET;
   }

   $datepostfix="";
   if ($includedate == 1)
   {
     $datepostfix=" (".$date[$i].") ";
   }

   if ( $print == 1)
   {
      echo "<ul class=\"$cssclass\">\n";
      for ($i=$OFFSET; $i<$end; $i++)
      {
		if ($includedate == 1)
        {
           $datepostfix=" (".$date[$i].") ";
        }
         if ($yearheaders == 1 && ($i==$OFFSET || $year[$i] != $year[$i-1]))
         {
            echo '<div class="listsubheader">'.$year[$i].'</div>';
         }
         echo "<li><a href=\"index.php?q=".$file[$i]."\">".$title[$i]."</a><span //class=\"datepostfix\">".$datepostfix."</span></li>";
      }
      echo "</ul>\n";
   }
   //return the file sorted on top (e.g. the newest file). 
   return $file[0];
}

function postnavigation($reffile){
   $i=0;
   $refnum=9999;
   if ($dirhandle = opendir('content')) 
   {
      while (false !== ($entry = readdir($dirhandle))) 
      {
         $lastchars = substr($entry, -3, 3);
         if ( (strcmp($lastchars,".md") == 0) ) 
         {
            if ( strcmp(substr($reffile,-3,3),".md") != 0 )
            {
               $reffile=$reffile.".md";
            }
            $thefile = $entry;
            $content = file_get_contents("content/".$thefile);

            $thetitle=get_metadata($thefile,"title");
            $thedate=get_metadata($thefile,"date");
            $thetype=get_metadata($thefile,"type");

            # Only include file if thedate has the right format (i.e. 5th and 8th characters are "-")
            $pos1 = strpos($thedate,"-");
            $thedate2 = substr($thedate,$pos1+1);
            $pos2 = strpos($thedate2,"-");

            if ( ($pos1 == 4) && ($pos2 == 2) && strcmp($thetype,"post") == 0 ) {
               $file[$i]=$thefile;
               $cont[$i]=$content;
               $dat[$i]=$thedate;
               $year[$i]=substr($thedate,0,4);
               $tit[$i]=$thetitle;
               $i++;
            }
         }
      }
      closedir($dirhandle);
   }
   $noffiles=$i;

   array_multisort($dat,SORT_DESC,$cont,$tit,$file,$year);

   #Find number of reffile in list: $refnum
   $refnum=array_keys($file,$reffile);

   $end=$noffiles;

   echo "<div class=\"postnav\">\n";
   if ($refnum[0] < $end-1)
   {
      echo "<a style=\"float:left\" href=\"?q=".$file[$refnum[0]+1]."\">&laquo; ".$tit[$refnum[0]+1]."</a>";
   }
   if ($refnum[0] > 0)
   {
      echo "<a style=\"float:right\" href=\"?q=".$file[$refnum[0]-1]."\">".$tit[$refnum[0]-1]." &raquo;</a>";
   }
   echo "</div>";
}

function includemarkdown($file){
   if (preg_match("/.md$/",$file))
   {
      $filename=$file;
   }
   else
   {
      $filename="$file.md";
   }
   $content=file("content/".$filename);
   $i=0;
   foreach ($content as $line) {
      #Remove metadata before first line break
      if ( preg_match('/^$/',$line))
      {
         break;
      }
      {
         if ( preg_match('/^[a-z]*: */',$line))
         {
            unset($content[$i]);
         }
      }
      $i++;
   }
   $contenthtml = Markdown(implode($content));

   foreach (explode("\n",$contenthtml) as $line) {
      if ( preg_match('/<?php (.*)\?>/',$line,$result))
      {
         eval($result[1].";");
      }
      else
      {
         echo $line;
      }
   }
}


function get_metadata($file,$tag){
   if (preg_match("/.md$/",$file))
   {
      $filename=$file;
   }
   else
   {
      $filename="$file.md";
   }
   $content=file("content/".$filename);
   $contentb=array();
   $i=0;
   foreach ($content as $line) {
      #Look for metadata before first line break
      if ( preg_match('/^$/',$line))
      {
         break;
      }
      {
         $expression="/^".$tag.":.*/";
         if ( preg_match($expression,$line))
         {
            $expression="/^".$tag.":*(.*)/";
            preg_match($expression,$line,$themetadata);
			$themetadata[1]=trim($themetadata[1]);
            return $themetadata[1];
            break;
         }
      }
      $i++;
   }
}
