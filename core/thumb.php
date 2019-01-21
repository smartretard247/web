<?php
  if(!isset($_SESSION['photoCount'])) {
    $_SESSION['photoCount'] = 2;
  } else {
    $_SESSION['photoCount'] = ($_SESSION['photoCount'] >= 21) ? 2 : $_SESSION['photoCount'] + 1;
  }
  
  $photoDir = "images/Slide";
  $files = scandir($photoDir);
  $tot_images = sizeof($files) - 1;
  
  if($files[$_SESSION['photoCount']] == "@eaDir") { ++$_SESSION['photoCount']; }
  
  $image = $files[$_SESSION['photoCount']];
  
  $size = getimagesize($photoDir . "/" . $image);
  $width = $size[0]; $height = $size[1]; 
  if($width != 0 && $height != 0) {
    if($width > $height) {
      $height = $height * 160 / $width;
      $size[1] = $size[1] * 800 / $size[0];

      $width = 160;
      $size[0] = 960;
    } else { 
      $width = $width * 210 / $height;
      $size[0] = $size[0] * 1050 / $size[1];

      $height = 210;
      $size[1] = 1050;
    }
  }


