<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");
	include ("../include/excel.inc");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";

	$h_idx				=	trim($_REQUEST[h_idx]);
	$target_date		=	trim($_REQUEST[target_date]);
	$s_date				=	trim($_REQUEST[s_date]);
	$e_date				=	trim($_REQUEST[e_date]);
	$damdang_id			=	trim($_REQUEST[damdang_id]);
	$sou_relation		=	trim($_REQUEST[sou_relation]);
	$bank_code			=	trim($_REQUEST[bank_code]);
	$jijum_code			=	trim($_REQUEST[jijum_code]);
	$review_confirm		=	trim($_REQUEST[review_confirm]);
	$daesang			=	trim($_REQUEST[daesang]);
	$j1					=	trim($_REQUEST[j1]);
	$memo				=	trim($_REQUEST[memo]);

	$list_num		=	trim($_REQUEST[list_num]);
	$page			=	trim($_REQUEST[page]);
	$view_num		=	trim($_REQUEST[list_num]);	//한라인에 몇개를 출력할건지//
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	20;					//리스트 갯수

	$wherequery = " where 1=1 ";

	if($h_idx!="")			$wherequery.= " and h_idx = ".$h_idx." ";
	if($damdang_id1!="")	$wherequery.= " and damdang_id = '".$damdang_id."' ";
	if($sou_relation!="")	$wherequery.= " and sou_relation = '".$sou_relation."' ";
	if($bank_code!="")		$wherequery.= " and d1 = '".$bank_code."' ";
	if($jijum_code!="")		$wherequery.= " and e1 = '".$jijum_code."' ";
	if($review_confirm!="")	$wherequery.= " and review_confirm = '".$review_confirm."' ";
	if($j1!="")				$wherequery.= " and (j1 like '%{$j1}%' or m1 like '%{$j1}%')";
	//$memo				=	trim($_REQUEST[memo]);

	if($daesang=="1") $wherequery.= " and junib_request_date='' ";  //전입의뢰일자가 없음
	if($daesang=="2") $wherequery.= " and junib_s_date='' ";        //전입수령일자가 없음
	if($daesang=="3") $wherequery.= " and review_request_date='' "; //재열람의뢰일자가 없음
	if($daesang=="4") $wherequery.= " and review_s_date=''";        //전입수령일가 없음

	if($target_date!=""){
		if(($s_date!="")&&($e_date!="")){
			$imsi = "";
			if($target_date=="1") {$imsi = " and g1 between ";$tt = "등기접수일";}
			if($target_date=="2") {$imsi = " and junib_request_date between ";$tt = "전입의뢰일";}
			if($target_date=="3") {$imsi = " and junib_s_date between ";$tt = "전입수령일";}
			if($target_date=="4") {$imsi = " and review_request_date between ";$tt = "재열람의뢰일";}
			if($target_date=="5") {$imsi = " and review_s_date between ";$tt = "전입수령일";}
			if($s_date==$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
			if($s_date!=$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
			$wherequery.=$imsi;
		}
	}

	$objPHPExcel = new PHPExcel();

	$sql = "select * from $board_dbname  $wherequery order by idx desc ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "고객고유번호")
                 ->setCellValue("B".$i, "은행")
                 ->setCellValue("C".$i, "지점")
                 ->setCellValue("D".$i, "등기접수일")
                 ->setCellValue("E".$i, "취득자1")
                 ->setCellValue("F".$i, "취득자2")
                 ->setCellValue("G".$i, "전화번호")
                 ->setCellValue("H".$i, "전입의뢰일")
                 ->setCellValue("I".$i, "소유주와의관계")
                 ->setCellValue("J".$i, "담당자(외주)")
                 ->setCellValue("K".$i, "재열람의뢰일")
                 ->setCellValue("L".$i, "재열람수령일")
                 ->setCellValue("M".$i, "재열람확인사항")
                 ->setCellValue("N".$i, "문자발송일");
	$i++;

	while($row = $stmt->fetch()){

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $row[a1])
                 ->setCellValue("B".$i, f_bank_name($row[d1]))
                 ->setCellValue("C".$i, f_jijum_name($row[e1]))
                 ->setCellValue("D".$i, f_date($row[g1]))
                 ->setCellValue("E".$i, $row[j1])
                 ->setCellValue("F".$i, $row[j1])
                 ->setCellValue("G".$i, $row[p1])
                 ->setCellValue("H".$i, f_date($row[junib_request_date]))
                 ->setCellValue("I".$i, f_sou_value($row[sou_relation]))
                 ->setCellValue("J".$i, f_damdang_oiju_value($row[damdang_id]))
                 ->setCellValue("K".$i, f_date($row[review_request_date]))
                 ->setCellValue("L".$i, f_date($row[review_s_date]))
                 ->setCellValue("M".$i, f_sou_value($row[review_confirm]))
                 ->setCellValue("N".$i, f_date($row[sms_date]));
	$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "전입세대열람조회");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
