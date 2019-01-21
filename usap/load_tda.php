<?php
include('lib-common.php');
include($_CONF['path'] . 'lib-database.php');
include($_CONF['path'] . 'smarty/Smarty.class.php');

$smarty = new Smarty;
$smarty->template_dir = $_CONF['path'] . 'smarty/templates';
$smarty->compile_dir = $_CONF['path'] . 'smarty/templates_c';

$info = array();
$info['current_page'] = $_SERVER['SCRIPT_NAME'];

if(isset($_POST['submit']) && $_FILES['file']['size'])
{
    if(is_uploaded_file($_FILES['file']['tmp_name']))
    {
        $fp = fopen($_FILES['file']['tmp_name'],'r');

        $data = '';

        $input['year'] = (int)$_REQUEST['year'];

        while($line = fgetcsv($fp,1024))
        {
            if(!empty($line[6]) && $line[6] == 'W0')
            { $input['section'] = $line[4]; }
            else
            if(strlen($line[0]) != 0 && is_numeric($line[0]))
            {
                $list = '';

                $input['battalion_id'] = (int)$line[0];
                $input['company_id'] = (int)$line[1];
                $input['para'] = htmlentities($line[2],ENT_QUOTES);
                $input['ln'] = htmlentities($line[3],ENT_QUOTES);
                $input['position'] = htmlentities($line[4],ENT_QUOTES);
                $input['gr'] = htmlentities($line[5],ENT_QUOTES);
                $input['posco'] = htmlentities($line[6],ENT_QUOTES);
                $input['br'] = htmlentities($line[7],ENT_QUOTES);
                $input['id'] = htmlentities($line[8],ENT_QUOTES);
                $input['amsco'] = htmlentities($line[9],ENT_QUOTES);
                $input['swc'] = htmlentities($line[10],ENT_QUOTES);
                $input['mdep'] = htmlentities($line[11],ENT_QUOTES);
                $input['req'] = (int)$line[12];
                $input['auth'] = (int)$line[13];
                $input['r1r2r3'] = htmlentities($line[14],ENT_QUOTES);

                foreach($input as $column => $value)
                {
                    if(is_numeric($value))
                    { $list .= "$value,"; }
                    else
                    { $list .= "'$value',"; }
                }

                $data .= '(' . substr($list,0,-1) . '),';
            }
        }

        $info['query'] = 'INSERT INTO tda (' . implode(',',array_keys($input)) . ') VALUES ' . substr($data,0,-1);

        if(mysql_query($info['query']))
        { $info['message'] = "{$input['year']} Data Loaded!"; }
        else
        { $info['message'] = 'Error loading data: ' . mysql_error(); }
    }
    else
    { $info['message'] = "Invalid file"; }
}

echo com_siteheader('TDA Loader');

$smarty->assign_by_ref('info',$info);
echo $smarty->fetch('load_tda.tpl');

echo com_sitefooter();

?>