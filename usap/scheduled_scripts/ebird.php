<?php

include('../config.php');

$date = date('Ymd');

$ebird_file = "http://ebird.afis.osd.mil/ebfiles/e{$date}aaindex.html";
$local_file = $_CONF['path'] . "templates/ebird.html";

$file = @file_get_contents($ebird_file);

if(strlen($file) > 0)
{
    //Remove initial HTML before and including
    //the first <H3> tag
    $pos = strpos($file,'<H3>');
    $file = substr($file,$pos+4);

    //Remove ending BODY and HTML tags
    $file = str_replace('</BODY>','',$file);
    $file = str_replace('</HTML>','</div>',$file);

    //Add </div> tags before each opening <H3> tag
    $file = str_replace('<H3>','</div><H3>',$file);

    //Javascript function for controlling
    //opening and closing of topics
    $js = "
    <SCRIPT language=javascript>
    var state = 'hidden';
    var dis = 'none';

    function showhide(layer_ref) {

      /////////////////////////
      //determine current state
      /////////////////////////
      if (document.all)
      {
        //IS IE 4 or 5 (or 6 beta)
        eval( 'state = document.all.' + layer_ref + '.style.visibility;');
      }

      if (document.layers)
      {
        //IS NETSCAPE 4 or below
        state = document.layers[layer_ref].visibility;
      }

      if (document.getElementById && !document.all)
      {
        maxwell_smart = document.getElementById(layer_ref);
        state = maxwell_smart.style.visibility;
      }

      /////////////
      //swap states
      /////////////
      if (state == 'visible')
      {
        state = 'hidden';
        dis = 'none';
      }
      else
      {
        state = 'visible';
        dis = 'block';
      }

      ///////////////
      //set new state
      ///////////////
      if (document.all)
      {
        //IS IE 4 or 5 (or 6 beta)
        eval( 'document.all.' + layer_ref + '.style.visibility = state');
        eval( 'document.all.' + layer_ref + '.style.display = dis');
      }

      if (document.layers)
      {
        //IS NETSCAPE 4 or below
        document.layers[layer_ref].visibility = state;
        document.layers[layer_ref].display = dis;
      }

      if (document.getElementById && !document.all)
      {
        maxwell_smart = document.getElementById(layer_ref);
        maxwell_smart.style.visibility = state;
        maxwell_smart.style.display = dis;
      }
    }
    </SCRIPT>";

    //Add js and first <H3> tag
    //to beginning of file.
    $file = $js . '<H3>' . $file;

    //Add targets to existing anchor tags
    //so stories open up in new window
    //and full URL to relative links
    $file = str_replace('HREF="','target="_blank" HREF="http://ebird.afis.osd.mil/ebfiles/',$file);

    //Callback function to add links and div tag
    function callback($match)
    {
        $id = strtolower(preg_replace('/[^a-zA-Z]/','',$match[1]));
        $retval = "<div>&bull; <A onclick=\"showhide('$id');\" href=\"javascript:void(0);\">{$match[1]}</A></div>
                   <DIV id=\"$id\" style=\"DISPLAY: none; VISIBILITY: hidden\">";

        return $retval;
    }

    $file = preg_replace_callback('/<H3>([^<]+)<\/H3>/','callback',$file);

    //Write resulting HTML to file
    $fp = fopen($local_file,'w');
    fwrite($fp,$file);
    fclose($fp);
}

?>