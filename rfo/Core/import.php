<?php function ImportClassRoster($filename, $unit) {   
    
/** Error reporting */
error_reporting(0);

date_default_timezone_set('Europe/London');

/** PHPExcel */
require_once '../Classes/PHPExcel.php';

/** PHPExcel_IOFactory */
require_once '../Classes/PHPExcel/IOFactory.php';

// Create new PHPExcel object
//$objImport = new PHPExcel();

//echo date('H:i:s') . " Load from Excel5 template\n";
$objReader = PHPExcel_IOFactory::createReader('Excel5');

$objImport = $objReader->load($filename);

	$output = array();
	$store = array();
	
	$I = 1;
	while ($objImport->getActiveSheet()->getCell('A' . $I)->getValue() != "") {
		if($objImport->getActiveSheet()->getCell('D' . $I)->getValue() == $unit) {
			$store['Rank'] = $objImport->getActiveSheet()->getCell('A' . $I)->getValue();
			switch($store['Rank']) {
			  case 'E-1': $store['Rank'] = 'PVT'; break;
			  case 'E-2': $store['Rank'] = 'PV2'; break;
			  case 'E-3': $store['Rank'] = 'PFC'; break;
			  case 'E-4': $store['Rank'] = 'SPC'; break;
			  case 'E-5': $store['Rank'] = 'SGT'; break;
			  case 'E-6': $store['Rank'] = 'SSG'; break;
			  case 'E-7': $store['Rank'] = 'SFC'; break;
			  case 'PVT': $store['Rank'] = 'PVT'; break;
			  case 'PV1': $store['Rank'] = 'PVT'; break;
			  case 'PV2': $store['Rank'] = 'PV2'; break;
			  case 'PFC': $store['Rank'] = 'PFC'; break;
			  case 'SPC': $store['Rank'] = 'SPC'; break;
			  case 'SGT': $store['Rank'] = 'SGT'; break;
			  case 'SSG': $store['Rank'] = 'SSG'; break;
			  case 'SFC': $store['Rank'] = 'SFC'; break;
			}
			
			
			$store['LastName'] = $objImport->getActiveSheet()->getCell('B' . $I)->getValue();
			//$store['FirstName'] = $objImport->getActiveSheet()->getCell('C' . $I)->getValue();
			$store['Component'] = $objImport->getActiveSheet()->getCell('C' . $I)->getValue();
			if($store['Component'] == "AR") {
				$store['Component'] = "ER";
			}
			$store['SSN'] = $objImport->getActiveSheet()->getCell('E' . $I)->getValue();
			
			$output[] = $store;
		}
		++$I;
	}

	return $output;
}
?>