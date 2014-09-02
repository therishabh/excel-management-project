<?php

	/** Error reporting */
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('asia/calcutta');

	if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');

	/** Include PHPExcel */
	require_once 'php-excel/PHPExcel.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
								 ->setLastModifiedBy("Maarten Balliauw")
								 ->setTitle("Office 2007 XLSX Test Document")
								 ->setSubject("Office 2007 XLSX Test Document")
								 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("Test result file");


	// Add some data
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Aa1', 'Hello');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('a2', 'world!')
	            ->setCellValue('c1', 'Hello')
	            ->setCellValue('D2', 'Rishabh')
	            ->setCellValue('e1', 'Hello')
	            ->setCellValue('f2', 'world!')
	            ->setCellValue('g1', 'Hello')
	            ->setCellValue('h2', 'world!');


	// Miscellaneous glyphs, UTF-8
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A4', '')
	            ->setCellValue('A5', '');


	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Rishabh');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="rishabh_agrawal.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>