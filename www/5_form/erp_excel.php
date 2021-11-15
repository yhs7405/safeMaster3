<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");
	include ("../include/excel.inc");

//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";

	$ch200	=	trim($_REQUEST[ch200]);
	$chx	=	$_POST[ch];

	$wherequery = " where 1=1 ";

	$mm = array();
	for($i=0;$i<count($chx);$i++){
		$mm[$i] = "'$chx[$i]'";
	}

	$chxm = implode( ',', $mm);

	$wherequery.= " and idx in ({$chxm}) and hy_b_date is not null "; 

	//print_r($chx);
	//echo "<br>{$wherequery}";

	$sql = "select * from $board_dbname  $wherequery order by idx desc ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	

	$objPHPExcel = new PHPExcel();


	$i=1;
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);


    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "전표일자")
                 ->setCellValue("B".$i, "부가세유형")
                 ->setCellValue("C".$i, "거래처코드")
                 ->setCellValue("D".$i, "거래처명")
                 ->setCellValue("E".$i, "공급가액")
                 ->setCellValue("F".$i, "부가세")
                 ->setCellValue("G".$i, "돈들어온계좌번호")
                 ->setCellValue("H".$i, "매출계정코드")
                 ->setCellValue("I".$i, "적요코드")
                 ->setCellValue("J".$i, "적요")
                 ->setCellValue("K".$i, "프로젝트")
                 ->setCellValue("L".$i, "부서");

	$i++;
	while($row = $stmt->fetch()){

			//Project code
			$sql = "select * from tbl_hyunjang_info where h_idx='{$row[h_idx]}'  limit 1";
			//echo $sql;
			$jj = db_query_fetch($sql);

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $row[hy_b_date])
                 ->setCellValue("B".$i, "1D")
                 ->setCellValue("C".$i, "00451")
                 ->setCellValue("D".$i, "")
                 ->setCellValue("E".$i, intval($row[aq1])+intval($row[as1]))
                 ->setCellValue("F".$i, intval($row[ar1])+intval($row[at1]))
                 ->setCellValue("G".$i, "")
                 ->setCellValue("H".$i, "4209")
                 ->setCellValue("I".$i, "")
                 ->setCellValue("J".$i, f_hyunjang_name($row[h_idx])." ".$row[h1]." ".$row[i1]." ".$row[j1]." ".$row[m1])
                 ->setCellValue("K".$i, $jj[project_code])
                 ->setCellValue("L".$i, "00005");
	$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "ERP연동");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;
?>
