<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");
	include ("../../include/excel.inc");
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);


	$board_dbname	=	"tbl_member_manual";

	$h_idx				=	trim($_REQUEST[h_idx]);

	$wherequery.= $imss;

	//echo $wherequery;
	$rows_total = db_count_all($board_dbname,$wherequery);


	$objPHPExcel = new PHPExcel();

	$sql = "select * from $board_dbname $wherequery  order by  cast(a1 as unsigned) asc ";
	//echo $sql;


	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "■ ".f_hyunjang_name($h_idx)."(".f_money0($rows_total).")건");
	$i++;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "동")
                 ->setCellValue("B".$i, "호")
                 ->setCellValue("C".$i, "취득자1")
                 ->setCellValue("D".$i, "취득자2")
                 ->setCellValue("E".$i, "전화번호1")
                 ->setCellValue("F".$i, "전화번호2")
                 ->setCellValue("G".$i, "회원여부")
                 ;
	$i++;

	while($row = $stmt->fetch()){

			$sql = "select * from tbl_junib where a1='{$row[a1]}' ";
			$stmts = $pdo->prepare($sql);
			$stmts->execute();
			$stmts->setFetchMode(PDO::FETCH_ASSOC);
			$rows = $stmts->fetch();

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $rows[h1]."동")
                 ->setCellValue("B".$i, $rows[i1]."호")
                 ->setCellValue("C".$i, $rows[j1])
                 ->setCellValue("D".$i, $rows[m1])
                 ->setCellValue("E".$i, $rows[p1])
                 ->setCellValue("F".$i, $rows[p2])
                 ->setCellValue("G".$i, f_membercode_value($row[member_gubun]))
                 ;
	$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "회원데이터다운로드_엑셀_".date("Ymd"));
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
