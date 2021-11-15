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

	$h1					=	trim($_REQUEST[h1]);
	$i1					=	trim($_REQUEST[i1]);
	$j1					=	trim($_REQUEST[j1]);
	$memo				=	trim($_REQUEST[memo]);
	$kikan1_null_ch		=	trim($_REQUEST[kikan1_null_ch]);
	$kikan2_null_ch		=	trim($_REQUEST[kikan2_null_ch]);
	$bank_null_ch	=	trim($_REQUEST[bank_null_ch]);

	if($target_date=="") $target_date="doc_receive_date";
	if($s_date=="")		$s_date=date("Ymd");
	if($e_date=="")		$e_date=date("Ymd");


	$view_num		=	trim($_REQUEST[view_num]);	//한라인에 몇개를 출력할건지//
	if($_REQUEST[page]==""){$page=1;}else{$page=$_REQUEST[page];}
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	100;					//리스트 갯수

	$wherequery = " where 1=1  ";

	if($h_idx!=""){
		$wherequery.= " and h_idx = ".$h_idx." ";
	}	else {
		$wherequery.= " and h_idx = ' '";
	}
	if($h1!="")				$wherequery.= " and h1 = '".$h1."' ";
	if($i1!="")				$wherequery.= " and i1 = '".$i1."' ";
	if($j1!="")				$wherequery.= " and (j1 like '%{$j1}%' or m1 like '%{$j1}%')";
	
	if($kikan1_null_ch=="Y"){
		if($target_date!="") {$imsi = " and ({$target_date}='' or {$target_date} is null )";}
		$wherequery.=$imsi;
	}else{
		if($target_date!=""){
			if(($s_date!="")&&($e_date!="")){
				$imsi = "";
				if($target_date!="") {$imsi = " and {$target_date} between ";}
				if($s_date==$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
				if($s_date!=$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
				$wherequery.=$imsi;
			}
		}
	}
	
	if($kikan2_null_ch=="Y"){
		if($target_date2!="") {$imsi = " and ({$target_date2}='' or {$target_date2} is null )";}
		$wherequery.=$imsi;
	}else{
		if($target_date2!=""){
			if(($s_date2!="")&&($e_date2!="")){
				$imsi = "";
				if($target_date2!="") {$imsi = " and {$target_date2} between ";}
				if($s_date2==$e_date2) {$imsi.= " {$s_date2} and {$e_date2} ";}
				if($s_date2!=$e_date2) {$imsi.= " {$s_date2} and {$e_date2} ";}
				$wherequery.=$imsi;
			}
		}
	}

  // 서류진행
	if($target_gubun=="al1_hope_yn"){
		$wherequery.= " and al1_hope_yn = 'y'";
	} else if($target_gubun=="al1_imp_yn"){
		$wherequery.= " and al1_imp_yn = 'y'";
	}

	$wherequery.= $imss;

	//echo $wherequery;
	$rows_total = db_count_all($board_dbname,$wherequery);


	$objPHPExcel = new PHPExcel();

	$sql = "select *, if(m1='',j1,CONCAT(j1, ',', m1)) as jm1 from $board_dbname $wherequery  order by  cast(a1 as unsigned) asc ";
	//echo $sql;


	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "■ ".f_hyunjang_name($h_idx)."(".f_money0($rows_total).")건");
	$i++;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(25);

	//$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setWrapText(true);
	//$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setWrapText(true);

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "현장명")
                 ->setCellValue("C".$i, "동")
                 ->setCellValue("D".$i, "호")
                 ->setCellValue("E".$i, "취득자")
                 ->setCellValue("F".$i, "전화번호1")
                 ->setCellValue("G".$i, "납부기한")
                 ->setCellValue("H".$i, "1차비용안내금액")
                 ->setCellValue("I".$i, "2차비용안내금액")
                 ->setCellValue("J".$i, "가상계좌")
                 ->setCellValue("K".$i, "비용상세내역\n(취득세포함)")
                 ->setCellValue("L".$i, "비용상세내역\n(취득세제외)")
                 ->setCellValue("M".$i, "취득세")
                 ->setCellValue("N".$i, "그외비용소계")
                 ->setCellValue("O".$i, "총비용")
                 ->setCellValue("P".$i, "인지")
                 ->setCellValue("Q".$i, "채권할인율")
                 ->setCellValue("R".$i, "이전채권본인부담금")
                 ->setCellValue("S".$i, "설정채권본인부담금")
                 ->setCellValue("T".$i, "증지")
                 ->setCellValue("U".$i, "신탁말소보수료")
                 ->setCellValue("V".$i, "신착말소등록세")
                 ->setCellValue("W".$i, "제증명")
                 ->setCellValue("X".$i, "소이등보수")
                 ->setCellValue("Y".$i, "취본희망")
                 ->setCellValue("Z".$i, "취본납부")
                 ->setCellValue("AA".$i, "1차비용안내일")
                 ->setCellValue("AB".$i, "2차비용안내일")
                 ->setCellValue("AC".$i, "수납금액")
                 ->setCellValue("AD".$i, "전화번호2")
                 ->setCellValue("AE".$i, "전자납부번호")
                 ;
	$i++;

	while($row = $stmt->fetch()){


		$cost_doc = "취득세 ".f_money0($row[al1])." 원, ";
		$cost_sum_doc = "인지 ".f_money0($row[am1])." 원, 이전채권본인부담금 ".f_money0($row[aj1_tmp])." 원, 설정채권본인부담금 ".f_money0($row[ak1_tmp])." 원, 증지 ".f_money0($row[an1])." 원, ";
		$cost_sum_doc.= "신탁말소보수(해당시) ".f_money0($row[as1]+$row[at1])." 원, ";
		$cost_sum_doc.= "신탁말소등록세(해당시) ".f_money0($row[ao1])." 원, ";
		$cost_sum_doc.= "제증명 ".f_money0($row[ap1])." 원, 소이등보수료 ".f_money0($row[aq1]+$row[ar1])." 원) ";


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $i-2)
                 ->setCellValue("B".$i, f_hyunjang_name($row[h_idx]))
                 ->setCellValue("C".$i, $row[h1])
                 ->setCellValue("D".$i, $row[i1])
                 ->setCellValue("E".$i, $row[jm1])
                 ->setCellValue("F".$i, $row[p1])
                 ->setCellValue("G".$i, $row[pay_dead_date])
                 ->setCellValue("H".$i, $row[gch1_cost])
								 ->setCellValue("I".$i, $row[gch2_cost])
								 ->setCellValue("J".$i, $row[vir_acc_no])
								 ->setCellValue("K".$i, $cost_doc . $cost_sum_doc)
								 ->setCellValue("L".$i, $cost_sum_doc)
								 ->setCellValue("M".$i, $row[al1])
								 ->setCellValue("N".$i, $row[etc_cost_sum])
								 ->setCellValue("O".$i, $row[total_sum])
								 ->setCellValue("P".$i, $row[am1_pur_cost])
								 ->setCellValue("Q".$i, $row[bond_sale_rate])
								 ->setCellValue("R".$i, $row[aj1_tmp])
								 ->setCellValue("S".$i, $row[ak1_tmp])
								 ->setCellValue("T".$i, $row[an1_by])
								 ->setCellValue("U".$i, $row[as1_by])
								 ->setCellValue("V".$i, $row[ao1_by])
								 ->setCellValue("W".$i, $row[ap1_by])
								 ->setCellValue("X".$i, $row[aq1_by])
								 ->setCellValue("Y".$i, f_yn_value($row[al1_hope_yn]))
								 ->setCellValue("Z".$i, f_yn_value($row[al1_imp_yn]))
								 ->setCellValue("AA".$i,$row[gch1_cost_date])
								 ->setCellValue("AB".$i,$row[gch2_cost_date])
								 ->setCellValue("AC".$i,$row[ai1])
								 ->setCellValue("AD".$i,$row[p2])
								 ->setCellValue("AE".$i,$row[elc_no])
                 ;
	$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "비용안내_비용안내용데이터엑셀_".date("Ymd"));
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
