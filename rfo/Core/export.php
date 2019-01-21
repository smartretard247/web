<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2010 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2010 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.5, 2010-12-10
 */

/** Error reporting */
error_reporting(0);

date_default_timezone_set('Europe/London');

/** PHPExcel */
require_once 'Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("SSG Young, Jesse")
							 ->setLastModifiedBy("SSG Young, Jesse")
							 ->setTitle("RA")
							 ->setSubject("RFO")
							 ->setDescription("RFO")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("RFO");


// Add some data
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'COPY TO RFO')
			->setCellValue('A2', 'Class Info:  (25B CLS-NM / GRAD DATE DDMMMYY)')
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
			->setCellValue('M4', 'MED/PROF')
			->setCellValue('M4', 'DENTAL')
			->setCellValue('M4', 'PHA')
			->setCellValue('M4', 'Travel');

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('RA');
			
//create another sheet
$objWorksheet1 = $objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A1', 'Name')
            ->setCellValue('A2', 'SSG Natali, José A')
			->setCellValue('B1', 'SSN')
			->setCellValue('B2', '583-61-0945');
		
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->setTitle('NGER');




// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//$objWriter->save('php://output');
$objWriter->save('exported.xls');


//exit;
