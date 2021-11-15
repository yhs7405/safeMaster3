<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");
	include ("../../include/excel.inc");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";

	$h_idx				=	trim($_REQUEST[h_idx]);
	$target_date		=	trim($_REQUEST[target_date]);
	$s_date				=	trim($_REQUEST[s_date]);
	$e_date				=	trim($_REQUEST[e_date]);

	$target_date2		=	trim($_REQUEST[target_date2]);
	$s_date2			=	trim($_REQUEST[s_date2]);
	$e_date2			=	trim($_REQUEST[e_date2]);
	$bank_code			=	trim($_REQUEST[bank_code]);
	$jijum_code			=	trim($_REQUEST[jijum_code]);

	$bank_null_ch	=	trim($_REQUEST[bank_null_ch]);
	$kikan2_null_ch		=	trim($_REQUEST[kikan2_null_ch]);
	$h1					=	trim($_REQUEST[h1]);
	$i1					=	trim($_REQUEST[i1]);
	$j1					=	trim($_REQUEST[j1]);
	$memo				=	trim($_REQUEST[memo]);

	if($target_date=="") $target_date="1";
	if($s_date=="")		$s_date=date("Ymd");
	if($e_date=="")		$e_date=date("Ymd");

	$list_num		=	trim($_REQUEST[list_num]);
	$page			=	trim($_REQUEST[page]);
	$view_num		=	trim($_REQUEST[view_num]);	//한라인에 몇개를 출력할건지//
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	20;					//리스트 갯수

	$wherequery = " where 1=1  ";

	if($h_idx!="")			$wherequery.= " and h_idx = ".$h_idx." ";
	if($bank_null_ch=="Y")	$wherequery.= " and d1 <> '' ";
	if($bank_code!="")		$wherequery.= " and d1 = '".$bank_code."' ";
	if($jijum_code!="")		$wherequery.= " and e1 = '".$jijum_code."' ";
	if($h1!="")				$wherequery.= " and h1 = '".$h1."' ";
	if($i1!="")				$wherequery.= " and i1 = '".$i1."' ";
	if($j1!="")				$wherequery.= " and (j1 like '%{$j1}%' or m1 like '%{$j1}%')";
	
	if($target_date!=""){
		if(($s_date!="")&&($e_date!="")){
			$imsi = "";
			if(($target_date=="sjp_s_date")||($target_date=="sjp_j_date")||($target_date=="sjj_w_date")||($target_date=="sjp_b_date")){  //설정일때
				$imsi = " and a1 in (select a1 from tbl_suljung where {$target_date} between {$s_date} and {$e_date} )";
			}else{
				if($target_date!="") {$imsi = " and {$target_date} between ";}
				if($s_date==$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
				if($s_date!=$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
			}
			$wherequery.=$imsi;
		}
	}

if($kikan2_null_ch=="Y"){
			if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")){ //설정일때
				$imsi = " and a1 in (select a1 from tbl_suljung where  ({$target_date2}='' or {$target_date2} is null ))";
			}else{
				if($target_date2!="") {$imsi = " and ({$target_date2}='' or {$target_date2} is null )";}
			}
			//echo "<br><br>".$imsi;
			$wherequery.=$imsi;
}else{
	if($target_date2!=""){
		if(($s_date2!="")&&($e_date2!="")){
			$imsi = "";
			if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")){ //설정일때
				$imsi = " and a1 in (select a1 from tbl_suljung where {$target_date2} between {$s_date2} and {$e_date2} )";
			}else{
				if($target_date2!="") {$imsi = " and {$target_date2} between ";}
				if($s_date2==$e_date2) {$imsi.= " {$s_date2} and {$e_date2} ";}
				if($s_date2!=$e_date2) {$imsi.= " {$s_date2} and {$e_date2} ";}
			}
			$wherequery.=$imsi;
		}
	}
}

	if($memo!=""){
		$wherequery.= " and (a1 in ( (select a1 from tbl_junib where (memo like '%{$memo}%') or (ijp_s_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_s_memo  like '%{$memo}%') or (ijp_j_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_j_memo  like '%{$memo}%') or (ijj_w_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjj_w_memo  like '%{$memo}%') or (ijp_b_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_b_memo  like '%{$memo}%') or (refund_memo  like '%{$memo}%') ";
		$wherequery.= "      or (refund_end_memo like '%{$memo}%') or (refund_memo  like '%{$memo}%')))  or ";
		$wherequery.= "      a1 in (select a1 from tbl_sugum where sugum_memo like '%{$memo}%') )";
	}


	$objPHPExcel = new PHPExcel();

	$sql = "select * from $board_dbname  $wherequery order by  cast(h1 as unsigned) asc,cast(i1 as unsigned) asc ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(20);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "현장명")
                 ->setCellValue("B".$i, "접수일")
                 ->setCellValue("C".$i, "은행명1")
                 ->setCellValue("D".$i, "지점명1")
                 ->setCellValue("E".$i, "동")
                 ->setCellValue("F".$i, "호")
                 ->setCellValue("G".$i, "취득자1")
                 ->setCellValue("H".$i, "취득자2")
                 ->setCellValue("I".$i, "전화번호")
                 ->setCellValue("J".$i, "이전필증수령일")
                 ->setCellValue("K".$i, "이전필증전달일")
                 ->setCellValue("L".$i, "이전필증정산일")
                 ->setCellValue("M".$i, "이전필증배포일")
                 ->setCellValue("N".$i, "환불일")
                 ->setCellValue("O".$i, "설정필증수령일1")
                 ->setCellValue("P".$i, "설정필증전달일1")
                 ->setCellValue("Q".$i, "설정필증정산일1")
                 ->setCellValue("R".$i, "설정필증배포일1")
                 ->setCellValue("S".$i, "설정필증수령일2")
                 ->setCellValue("T".$i, "설정필증전달일2")
                 ->setCellValue("U".$i, "설정필증정산일2")
                 ->setCellValue("V".$i, "설정필증배포일2")
                 ->setCellValue("W".$i, "설정필증수령일3")
                 ->setCellValue("X".$i, "설정필증전달일3")
                 ->setCellValue("Y".$i, "설정필증정산일3")
                 ->setCellValue("Z".$i, "설정필증배포일3")
                 ->setCellValue("AA".$i, "설정필증수령일4")
                 ->setCellValue("AB".$i, "설정필증전달일4")
                 ->setCellValue("AC".$i, "설정필증정산일4")
                 ->setCellValue("AD".$i, "설정필증배포일4");

	$i++;



	while($row = $stmt->fetch()){

	$sql = "select * from tbl_junib where a1='{$row[a1]}' limit 1 ";
	$row2 = db_query_value($sql);

	$sql1 = "select * from tbl_suljung where a1='{$row[a1]}' and suljung_no=1 ";
	$row1 = db_query_value($sql1);

	$sql1 = "select * from tbl_suljung where a1='{$row[a1]}' and suljung_no=2 ";
	$row2 = db_query_value($sql1);

	$sql1 = "select * from tbl_suljung where a1='{$row[a1]}' and suljung_no=3 ";
	$row3 = db_query_value($sql1);

	$sql1 = "select * from tbl_suljung where a1='{$row[a1]}' and suljung_no=4 ";
	$row4 = db_query_value($sql1);

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, f_hyunjang_name($row[h_idx]))
                 ->setCellValue("B".$i, f_date($row[g1]))
                 ->setCellValue("C".$i, f_bank_name($row[d1]))
                 ->setCellValue("D".$i, f_jijum_name($row[e1]))
                 ->setCellValue("E".$i, $row[h1])
                 ->setCellValue("F".$i, $row[i1])
                 ->setCellValue("G".$i, $row[j1])
                 ->setCellValue("H".$i, $row[m1])
                 ->setCellValue("I".$i, $row[p1])
                 ->setCellValue("J".$i, f_date2($row[ijp_s_date]))
                 ->setCellValue("K".$i, f_date2($row[ijp_j_date]))
                 ->setCellValue("L".$i, f_date2($row[ijj_w_date]))
                 ->setCellValue("M".$i, f_date2($row[ijp_b_date]))
                 ->setCellValue("N".$i, f_date2($row[refund_date]))

                 ->setCellValue("O".$i, f_date2($row1[sjp_s_date]))
                 ->setCellValue("P".$i, f_date2($row1[sjp_j_date]))
                 ->setCellValue("Q".$i, f_date2($row1[sjj_w_date]))
                 ->setCellValue("R".$i, f_date2($row1[sjp_b_date]))

                 ->setCellValue("O".$i, f_date2($row2[sjp_s_date]))
                 ->setCellValue("P".$i, f_date2($row2[sjp_j_date]))
                 ->setCellValue("Q".$i, f_date2($row2[sjj_w_date]))
                 ->setCellValue("R".$i, f_date2($row2[sjp_b_date]))

                 ->setCellValue("O".$i, f_date2($row3[sjp_s_date]))
                 ->setCellValue("P".$i, f_date2($row3[sjp_j_date]))
                 ->setCellValue("Q".$i, f_date2($row3[sjj_w_date]))
                 ->setCellValue("R".$i, f_date2($row3[sjp_b_date]))

                 ->setCellValue("O".$i, f_date2($row4[sjp_s_date]))
                 ->setCellValue("P".$i, f_date2($row4[sjp_j_date]))
                 ->setCellValue("Q".$i, f_date2($row4[sjj_w_date]))
                 ->setCellValue("R".$i, f_date2($row4[sjp_b_date]));
	$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "고객지원팀조회");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
