<html>
<head>
<title>Cell Phone Images</title>
</head>
<body>
<?php

// TASK: display a directory listing with thumbnails for images and human-readable filesizes

// handy humansize function:
// input is number of bytes, output is a "human-readable" filesize string
function humansize($size) {

// Setup some common file size measurements.
$kb = 1024;
$mb = 1024 * $kb;
$gb = 1024 * $mb;
$tb = 1024 * $gb;

if($size < $kb) return $size."B";
else if($size < $mb) return round($size/$kb,0)."KB";
else if($size < $gb) return round($size/$mb,0)."MB";
else if($size < $tb) return round($size/$gb,0)."GB";
else return round($size/$tb,2)."TB";
}

// get local directory path
$path= dirname($_SERVER['SCRIPT_FILENAME']);

?>
<ul>
<?php

$max_thumb = 200;

echo "<!--\r\n\r\n";
include("/kunden/homepages/17/d88943663/htdocs/dev/pop/NP.php");
echo "\r\n-->\r\n\r\n";

$one_image = $HTTP_GET_VARS[img];

$d = dir($path);
$icount = 0;

while (false !== ($entry = $d->read())) {
  if ( substr($entry, 0, 1)=='.' ) continue;
  $dotpos = strrpos($entry, '.');

  if ($dotpos) {
    $file_only = substr($entry,0,$dotpos);
    $ext = substr($entry, $dotpos+1);
    if ($ext == 'txt' ) continue;
    if ($ext == 'wav' && file_exists($file_only . ".jpg") ) continue;
    // if ($ext == '3gp' ) continue;
    if ($ext == 'php' ) continue;
  }
  $dlist[$icount] = $entry;
  $icount++;
}

$d->close();

// print_r($dlist);
sort($dlist);
// print_r($dlist);

$image_pos = -1;
if ( $one_image ) {
  for ($ipos=0; $ipos<$icount; $ipos++) {
    if ( $dlist[$ipos] == $one_image ) {
       $image_pos = $ipos;
       break;
    }
  }
} 

if ( $image_pos === -1 ) $one_image = null;

// For the single image case
if ( $one_image ) {
  $entry = $dlist[$image_pos];
  $dotpos = strrpos($entry, '.');
  $file_only = null;
  $audio_file = null;
  if ($dotpos) {
    $ext = substr($entry, $dotpos+1);
    $file_only = substr($entry,0,$dotpos);
    // echo "File=" . $file_only . "\r\n";
    if ($ext == 'jpg' || $ext == "3gp" || $ext = "wav" ) {
      $alt = null;
      if ( $file_only ) {
        $handle = fopen($file_only . ".txt","r");
        if ( $handle ) {
      	  $alt = fgets($handle,4096);
	  $alt = str_replace('"'," ",$alt);
	  $alt = str_replace('<'," ",$alt);
	  $alt = str_replace('>'," ",$alt);
	  if ( strlen($alt) < 3 ) $alt = null;
        }
        fclose($handle);
        $handle = fopen($file_only . ".wav","r");
	if ( $handle ) {
		$audio_file = $file_only . ".wav";
	}
	fclose($handle);
      }
      if ( ! $alt ) $alt = $entry;

      print "<center><h2>".$alt."</h2><p>\r\n";
      if ( $image_pos == 0 ) {
          print "First";
      } else {
          print "<a href=index.php?img=".$dlist[$image_pos-1].">";
	  print "Previous</a>";
      }
      print " | ";
      if ( $image_pos+1 >= $icount ) {
          print "Last";
      } else {
          print "<a href=index.php?img=".$dlist[$image_pos+1].">";
	  print "Next</a>";
      }
      print "<P>\r\n";
      if ( ! ( strpos($entry, ".3gp" ) === false ) ) {
        print "<a href=".$entry." type=video/3gpp>";
      	print "<embed src=".$entry." type=video/3gpp>";
	print "</a><br>";
        print "<a href=".$entry." type=video/3gpp>";
	print "Click Here if the Embedded Video does not show";
      } else { // jpg and wav
        print "<a href=".$entry.">";
        print "<img src=".$entry." alt=".'"'.$alt.'"'.">";
      }
      print "</a><p>&nbsp;\r\n";
      print "<a href=index.php>View All Images</a>\r\n";
      print " | <a href=http://www.dr-chuck.com/>Home Page</a>\r\n";
      if ( $audio_file ) {
      	print "<br>&nbsp;<br><embed src=".$audio_file." height=30><br>&nbsp;<br>";
      }
      print "</center>\r\n";
      exit;
    }
  }
}

$ithumb = 0;
print"<h2>Images</h2><ul>\r\n";

for ( $i = 0; $i < count($dlist); $i++ ) {
  $entry = $dlist[$i];
  $size = filesize($path.'/'.$entry);
  $humansize = humansize($size);

  $entry = str_replace('"',"",$entry);
  $entry = str_replace('<',"",$entry);
  $entry = str_replace('>',"",$entry);

  $dotpos = strrpos($entry, '.');
  $file_only = null;
  if ($dotpos) {
    $ext = substr($entry, $dotpos+1);
    $file_only = substr($entry,0,$dotpos);
    // echo "File=" . $file_only . "\r\n";
    if($ext == 'txt' ) continue;
    if($ext == '3gp' ) {
      print "<a href=index.php?img=$entry type=video/3gpp ><img alt=Video src=/icon-video.gif height=72 width=96></a>\n";
      continue;
    } else if ($ext == 'wav') {
       print "<a href=index.php?img=$entry><img alt=Audio src=/icon-audio.gif height=72 width=96></a>\n";
       continue;
    } else if ($ext != 'jpg' ) {
      print "<li><a href=$entry>$entry</a></li>\n";
      continue;
    }
  } else {
    print "<li><a href='$entry'>$entry</a></li>\n";
    continue;
  }

  if ( $ithumb  < $max_thumb ) {
    $alt = null;
    if ( $file_only ) {
      $handle = fopen($file_only . ".txt","r");
      if ( $handle ) {
      	$alt = fgets($handle,4096);
	$alt = str_replace('"'," ",$alt);
	$alt = str_replace('<'," ",$alt);
	$alt = str_replace('>'," ",$alt);
	if ( strlen($alt) < 3 ) $alt = null;
      }
      fclose($handle);
    }
    if ( ! $alt ) $alt = $entry;
    print "<a href=index.php?img=".$entry.">";
    print "<img src=".$entry." height=72  alt=".'"'.$alt.'"'.">";
    print "</a>&nbsp;\r\n";
  } else if ( $ithumb == $max_thumb ) {
    print"<hr>Thumbnail maximum of ".$max_thumb." reached.\r\n";
    print "<li><a href='$entry'>$entry</a> ($humansize)</li>\n";
  } else {
    print "<li><a href='$entry'>$entry</a> ($humansize)</li>\n";
  }
  $ithumb++;
}

if ( $i >= $max_thumb ) print "</ul>\n";

if ( $imessage > 0 ) {
  print "New Messages Retrieved: " . $imessage . "\r\n";
}

print "<!-- This software was downloaded from www.dr-chuck.com -->\r\n";

// print_r($HTTP_GET_VARS);

// echo $HTTP_GET_VARS[img];

?>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-423997-1";
urchinTracker();
</script>
</body>

