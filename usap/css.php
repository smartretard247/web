<?
include("config.php");
session_start();

if($_SESSION['battalion_id'] == 1)
{
    $_CONF['up']['main_color'] = '#DC143C';
    $_CONF['up']['row_highlight_color'] = '#F9BFCB';
}

?>
/*main body*/
body { font-family: arial; font-size: x-small; font-style: normal; color: <?=$_CONF['up']['font_color']?>;
       font-weight: normal; background-color: <?=$_CONF['up']['background_color']?>}
.body { font-family: arial; font-size: small; font-style: normal; color: <?=$_CONF['up']['font_color']?>;
       font-weight: normal; background-color: <?=$_CONF['up']['background_color']?>}

/*link effects*/
a:visited{ color: <?=$_CONF['up']['font_color']?>;}
a:link   { color: <?=$_CONF['up']['font_color']?>;}
a:hover  { color: <?=$_CONF['up']['main_color']?>;}

a.headerlink:visited{ color: <?=$_CONF['up']['font_color']?>; text-decoration: none; }
a.headerlink:link   { color: <?=$_CONF['up']['font_color']?>; text-decoration: none; }
a.headerlink:hover  { color: <?=$_CONF['up']['font_color']?>; text-decoration: none; }

/*table effects*/
.table_heading{ font-size: medium; font-weight: bolder; background:<?=$_CONF['up']['main_color']?>; text-align: left;}
.table_bgcolor_heading{ font-size: small; font-weight: bold; background:<?=$_CONF['up']['background_color']?>; text-align:left;}
.table_bgcolor_cheading{ font-size: small; font-weight: bold; background:<?=$_CONF['up']['background_color']?>; text-align:center;}
.table_cheading{ font-size: medium; font-weight: bolder; background:<?=$_CONF['up']['main_color']?>; text-align: center;}
.table_csheading{ font-weight: bolder; background:<?=$_CONF['up']['main_color']?>; text_align: center;}
.table_blackcell{ background:black;}
.table_bgcolor_error{ font-size: small; font-weight: normal; background:<?=$_CONF['up']['background_color']?>; text-align:left; color:<?=$_CONF['up']['error_color']?>;}
.table_bgcolor_cerror{ font-size: small; font-weight: normal; background:<?=$_CONF['up']['background_color']?>; text-align:center; color:<?=$_CONF['up']['error_color']?>;}


.table_bgcolor_data{ font-size: small; font-weight: normal; background:<?=$_CONF['up']['background_color']?>; text-align:left;}
.table_bgcolor_cdata{ font-size: small; font-weight: normal; background:<?=$_CONF['up']['background_color']?>; text-align:center;}

/*general text effects*/
.heading{ font-size: medium; font-weight: bolder; background:<?=$_CONF['up']['main_color']?>;}
.example{ font-size: x-small; font-style: italic; font-weight: lighter}
.data   { font-weight: normal;}
.column_name { font-family: arial, helvetica, sans-serif; font-weight: bold;}
.error { font-weight: font-size: x-small; bolder; color: <?=$_CONF['up']['error_color']?>; text-align: center; }
.notice { font-weight: bold; font-size: medium; color: <?=$_CONF['up']['notice_color']?>; text-align: center; }

/*form effects*/
input{background-color: <?=$_CONF['up']['background_color']?>;
      color: <?=$_CONF['up']['font_color']?>;}
select{background-color: <?=$_CONF['up']['background_color']?>;
      color: <?=$_CONF['up']['font_color']?>;}
textarea{background-color: <?=$_CONF['up']['background_color']?>;
      color: <?=$_CONF['up']['font_color']?>;}
.text_box{ background-color: <?=$_CONF['up']['background_color']?>;
           color: <?=$_CONF['up']['font_color']?>;}
.button  { font-weight: bold; background-color: <?=$_CONF['up']['background_color']?>;
           border: thin <?=$_CONF['up']['main_color']?> solid; color: <?=$_CONF['up']['font_color']?>;}

/*miscellaneous*/
acronym { border-bottom: 1px dashed <?=$_CONF['up']['font_color']?>; cursor: help; }

.verticaltext {writing-mode: tb-rl; filter: flipv fliph; }