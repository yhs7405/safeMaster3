<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");
	include ("../include/excel.inc");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);


	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);


	$board_dbname	=	" tbl_junib j cross join tbl_suljung b on j.a1=b.a1 ";

	$h_idx			=	trim($_REQUEST[h_idx]);
	$target_date		=	trim($_REQUEST[target_date]);
	$s_date			=	trim($_REQUEST[s_date]);
	$e_date			=	trim($_REQUEST[e_date]);

	$target_date2		=	trim($_REQUEST[target_date2]);
	$s_date2		=	trim($_REQUEST[s_date2]);
	$e_date2		=	trim($_REQUEST[e_date2]);
	$bank_code		=	trim($_REQUEST[bank_code]);
	$jijum_code		=	trim($_REQUEST[jijum_code]);

	$cg_daesang		=	trim($_REQUEST[cg_daesang]);
	$kikan2_null_ch		=	trim($_REQUEST[kikan2_null_ch]);

	$h1			=	trim($_REQUEST[h1]);
	$i1			=	trim($_REQUEST[i1]);
	$j1			=	trim($_REQUEST[j1]);
	$memo			=	trim($_REQUEST[memo]);

	if($s_date=="")		$s_date=date("Ymd");
	if($e_date=="")		$e_date=date("Ymd");
	if($s_date2=="")	$s_date2=date("Ymd");
	if($e_date2=="")	$e_date2=date("Ymd");

	$list_num		=	trim($_REQUEST[list_num]);
	$page			=	trim($_REQUEST[page]);
	$view_num		=	trim($_REQUEST[list_num]);	//한라인에 몇개를 출력할건지//
	$Page_List		=	10;				//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	20;				//리스트 갯수

	$wherequery = " where 1=1  ";

	if($h_idx!="")			$wherequery.= " and j.h_idx = ".$h_idx." ";
	if($bank_code!="")		$wherequery.= " and j.d1 = '".$bank_code."' ";
	if($jijum_code!="")		$wherequery.= " and j.e1 = '".$jijum_code."' ";
	if($h1!="")			$wherequery.= " and j.h1 = '".$h1."' ";
	if($i1!="")			$wherequery.= " and j.i1 = '".$i1."' ";
	if($j1!="")			$wherequery.= " and (j.j1 like '%{$j1}%' or j.m1 like '%{$j1}%')";

	//if($cg_daesang=="Y")	$wherequery.= " and b.cg_daesang='Y' ";
	
	if(($s_date!="")&&($e_date!="")){
		$imsi = "";
		$imsi = " and b.idx in (SELECT s.idx FROM `tbl_suljung` s cross join tbl_sugum t on s.a1=t.a1 WHERE s.suljung_no=t.suljung_no and (length(t.confirm_date)>0) and (confirm_date between {$s_date} and {$e_date}) group by s.idx )";
		$wherequery.=$imsi;
		//echo "<br>".$imsi;
	}

	if($memo!=""){
		$wherequery.= " and (j.a1 in ( (select a1 from tbl_junib where (memo like '%{$memo}%') or (ijp_s_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_s_memo  like '%{$memo}%') or (ijp_j_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_j_memo  like '%{$memo}%') or (ijj_w_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjj_w_memo  like '%{$memo}%') or (ijp_b_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_b_memo  like '%{$memo}%') or (refund_memo  like '%{$memo}%') ";
		$wherequery.= "      or (refund_end_memo like '%{$memo}%') or (refund_memo  like '%{$memo}%')))  or ";
		$wherequery.= "      j.a1 in (select a1 from tbl_sugum where sugum_memo like '%{$memo}%') )";
	}


	$objPHPExcel = new PHPExcel();


	$sql = "select j.*,b.* from  tbl_junib j cross join tbl_suljung b on j.a1=b.a1   $wherequery ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "정산일")
                 ->setCellValue("C".$i, "은행명")
                 ->setCellValue("D".$i, "지점명")
                 ->setCellValue("E".$i, "동")
                 ->setCellValue("F".$i, "호")
                 ->setCellValue("G".$i, "채무자")
                 ->setCellValue("H".$i, "채권최고액")
                 ->setCellValue("I".$i, "보수액")
                 ->setCellValue("J".$i, "공과금")
                 ->setCellValue("K".$i, "총비용")
                 ->setCellValue("L".$i, "설정비용내역서출력일")
                 ->setCellValue("M".$i, "카드승인일");
	$i++;

	while($row = $stmt->fetch()){


	$sql1 = "select * from tbl_sugum where a1='{$row[a1]}' and suljung_no='$row[suljung_no]'  limit 1 ";
	//echo $sql1;
	$row1 = db_query_value($sql1);

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $i)
                 ->setCellValue("B".$i, f_date($row[ijj_w_date]))
                 ->setCellValue("C".$i, f_bank_name($row[d1]))
                 ->setCellValue("D".$i, f_jijum_name($row[e1]))
                 ->setCellValue("E".$i, $row[h1])
                 ->setCellValue("F".$i, $row[i1])
                 ->setCellValue("G".$i, $row[j1])
                 ->setCellValue("H".$i, $row[chaekwon_max])
                 ->setCellValue("I".$i, $row[bosu_price]+$row[bosu_price_vat])
                 ->setCellValue("J".$i, $row[gongga_price])
                 ->setCellValue("K".$i, $row[bosu_price]+$row[bosu_price_vat]+$row[gongga_price])
                 ->setCellValue("L".$i, f_date($row[sjb_c_date]))
                 ->setCellValue("M".$i, f_date($row1[ibgum_date1])."/".f_date($row1[ibgum_date2]));
	$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "erp_카드승인일");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
