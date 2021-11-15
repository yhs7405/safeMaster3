<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");
	include ("../../include/excel.inc");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_jungsan_report";

	$h_idx			=	trim($_REQUEST[h_idx]);
	$objPHPExcel = new PHPExcel();

	$sql = "select * from $board_dbname where h_idx={$h_idx} order by bank_code desc,jijum_code asc ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "은행명")
                 ->setCellValue("B".$i, "지점명")
                 ->setCellValue("C".$i, "현장명")
                 ->setCellValue("D".$i, "이전-건수")
                 ->setCellValue("E".$i, "이전-소유권이전보수료")
                 ->setCellValue("F".$i, "이전-신탁말소보수료")
                 ->setCellValue("G".$i, "이전-제증명")
                 ->setCellValue("H".$i, "설정-건수")
                 ->setCellValue("I".$i, "설정-청구액(총)")
                 ->setCellValue("J".$i, "설정-보수료")
                 ->setCellValue("K".$i, "설정-공과금")
                 ->setCellValue("L".$i, "설정-입금")
                 ->setCellValue("M".$i, "미수");
	$i++;

	while($row = $stmt->fetch()){

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, f_bank_name($row[bank_code]))
                 ->setCellValue("B".$i, f_jijum_name($row[jijum_code]))
                 ->setCellValue("C".$i, f_hyunjang_name($row[h_idx]))
                 ->setCellValue("D".$i, $row[ijun_count])
                 ->setCellValue("E".$i, $row[ijun_bosu])
                 ->setCellValue("F".$i, $row[ijun_malso])
                 ->setCellValue("G".$i, $row[ijun_je])
                 ->setCellValue("H".$i, $row[suljung_count])
                 ->setCellValue("I".$i, $row[suljung_total])
                 ->setCellValue("J".$i, $row[suljung_bosu])
                 ->setCellValue("K".$i, $row[suljung_gongga])
                 ->setCellValue("L".$i, $row[suljung_ibgum])
                 ->setCellValue("M".$i, $row[suljung_total]-$row[suljung_ibgum]);
	$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "정산보고서조회");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
