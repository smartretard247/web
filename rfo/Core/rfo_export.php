<?php function ExportRFO($soldier, $curr_comp = 'RA') {

if($curr_comp == 'RA') { $eq = '='; } else { $eq = '!='; }
    
    
/** Error reporting */
error_reporting(0);

date_default_timezone_set('Europe/London');

/** PHPExcel */
require_once 'Classes/PHPExcel.php';

/** PHPExcel_IOFactory */
require_once 'Classes/PHPExcel/IOFactory.php';

// Create new PHPExcel object
//$objPHPExcel = new PHPExcel();

//echo date('H:i:s') . " Load from Excel5 template\n";
$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load("Templates/RFO_" . $curr_comp . ".xls");


// Set properties
$objPHPExcel->getProperties()->setCreator("SSG Young, Jesse")
							 ->setLastModifiedBy("SSG Young, Jesse")
							 ->setTitle("RFO")
							 ->setSubject("RFO")
							 ->setDescription("RFO")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("RFO");

$alpha = GetTableWhere('alpha', 'Component', $eq, 'RA', 'ClassNumber, Component, LastName');
if($alpha) { 
	$i = 5; $s = -1; $current_class = '000-00';
	
	foreach ($alpha as $talpha) {
		$soldier->SetFromDB($talpha['SSN']);
		$soldier->GetRFO()->SetFromDB($talpha['SSN']);
		
		if ($current_class != $soldier->GetClassNumber()){
			$current_class = $soldier->GetClassNumber();
			$i = 5; ++$s;
			
			//create another sheet
			if($s >= 0) {
				//$objWorksheet1 = $objPHPExcel->createSheet();
				$objWorksheet1 = $objPHPExcel->addSheet($objPHPExcel->getSheet($s)->copy(), $s+1);
			}
			
			$grad_date = Classes::FindGradDate($soldier->GetClassNumber());
			$objPHPExcel->setActiveSheetIndex($s)
						->setCellValue('A2', 'Class Info:  (25B ' . $soldier->GetClassNumber() . ' / GRAD DATE ' . $grad_date . ')') //add grad date here...
						->setCellValue('A4', 'NAME (Last, First, MI)')
						->setCellValue('B4', 'COMP')
						->setCellValue('C4', 'SSN')
						->setCellValue('D4', 'RANK')
						->setCellValue('E4', 'ABN Y/N')
						->setCellValue('F4', 'HRAP Y/N')
						->setCellValue('G4', 'APFT Y/N')
						->setCellValue('H4', 'SEC Y/N')
						->setCellValue('I4', 'UCMJ Y/N')
						->setCellValue('J4', 'LV Y/N')
						->setCellValue('K4', 'POV Y/N')
						->setCellValue('L4', 'FMLY Y/N')
						->setCellValue('M4', 'POR Y/N')
						->setCellValue('N4', 'MED/PROF')
						->setCellValue('O4', 'DENTAL')
						->setCellValue('P4', 'PHA')
						->setCellValue('Q4', 'Travel');
			$objPHPExcel->getActiveSheet()->setTitle($soldier->GetClassNumber());
		}
		
		if($soldier->GetRFO()->GetCompletion()) {  
			
			$objPHPExcel->setActiveSheetIndex($s)
				->setCellValue('A' . $i, $soldier->GetName())
				->setCellValue('B' . $i, $soldier->GetComponent())
				->setCellValue('C' . $i, $soldier->GetSSN())
				->setCellValue('D' . $i, $soldier->GetRank())
				->setCellValue('E' . $i, $soldier->GetRFO()->GetAirborne())
				->setCellValue('F' . $i, $soldier->GetRFO()->GetHRAP())
				->setCellValue('G' . $i, $soldier->GetRFO()->GetAPFT())
				->setCellValue('H' . $i, $soldier->GetRFO()->GetSecurityClearance())
				->setCellValue('I' . $i, $soldier->GetRFO()->GetUCMJ());
				
			if($soldier->GetComponent() == 'RA') {
				$objPHPExcel->setActiveSheetIndex($s)
					->setCellValue('J' . $i, $soldier->GetRFO()->GetLeave())
					->setCellValue('K' . $i, $soldier->GetRFO()->GetPOV())
					->setCellValue('L' . $i, $soldier->GetRFO()->GetFamily())
					->setCellValue('M' . $i, $soldier->GetRFO()->GetPOR());
			} else {
				$objPHPExcel->setActiveSheetIndex($s)
					->setCellValue('J' . $i, 'N/A')
					->setCellValue('K' . $i, 'N/A')
					->setCellValue('L' . $i, 'N/A')
					->setCellValue('M' . $i, 'N/A');
			}	
			
			$objPHPExcel->setActiveSheetIndex($s)
				->setCellValue('N' . $i, $soldier->GetRFO()->GetProfile())
				->setCellValue('O' . $i, $soldier->GetRFO()->GetDentalCategory())
				->setCellValue('P' . $i, $soldier->GetRFO()->GetPHA());
				
			if($soldier->GetComponent() == 'NG' || $soldier->GetComponent() == 'ER') {
				$objPHPExcel->setActiveSheetIndex($s)
					->setCellValue('Q' . $i, $soldier->GetRFO()->GetTravel());
			} else {
				$objPHPExcel->setActiveSheetIndex($s)
					->setCellValue('Q' . $i, 'N/A');
			}
			
			//$objPHPExcel->setActiveSheetIndex(0)
			//	
			
			++$i;
		} 
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	}
	
} 

NoDataRow($talpha, 17);
	
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client�s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('Exported\D447-RFO-' . $curr_comp . '.xls');  //change to GetUnit()
}
?>