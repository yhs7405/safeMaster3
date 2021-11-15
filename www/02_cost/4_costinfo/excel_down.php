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
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(15);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "동")
                 ->setCellValue("C".$i, "호")
                 ->setCellValue("D".$i, "취득자")
                 ->setCellValue("E".$i, "전화번호1")
                 ->setCellValue("F".$i, "완증수령일")
                 ->setCellValue("G".$i, "취득세신고일")
                 ->setCellValue("H".$i, "가상계좌생성일")
                 ->setCellValue("I".$i, "수납일")
                 ->setCellValue("J".$i, "1차비용안내일")
                 ->setCellValue("K".$i, "2차비용안내일")
                 ->setCellValue("L".$i, "비용독촉안내일")
                 ->setCellValue("M".$i, "수납액")
                 ->setCellValue("N".$i, "전자")
                 ->setCellValue("O".$i, "가상")
                 ->setCellValue("P".$i, "다주택여부")
                 ->setCellValue("Q".$i, "수납결과")
                 ->setCellValue("R".$i, "납부기한")
                 ->setCellValue("S".$i, "취본희망")
                 ->setCellValue("T".$i, "취본납부")
                 ->setCellValue("U".$i, "취득세")
                 ->setCellValue("V".$i, "인지세")
                 ->setCellValue("W".$i, "채권할인율(%)")
                 ->setCellValue("X".$i, "이전채권본인부담금")
                 ->setCellValue("Y".$i, "설정채권본인부담금")
                 ->setCellValue("Z".$i, "증지")
                 ->setCellValue("AA".$i, "신탁말소보수료")
                 ->setCellValue("AB".$i, "신탁말소등록세")
                 ->setCellValue("AC".$i, "제증명")
                 ->setCellValue("AD".$i, "소이등보수료")
                 ->setCellValue("AE".$i, "그외비용소계")
                 ->setCellValue("AF".$i, "총비용")
                 ->setCellValue("AG".$i, "1차비용안내금액")
                 ->setCellValue("AH".$i, "2차비용안내금액")
                 ;
	$i++;

	while($row = $stmt->fetch()){

    if($row[al1_hope_yn]=="y"||$row[al1_imp_yn]=="y"){
      if(($row[total_sum]-$row[al1]-$row[ai1])!=0){
      	$ok_cost = $row[total_sum]-$row[al1]-$row[ai1];
      }else{
      	$ok_cost = "ok";
      }
    }else{	
    	if(($row[total_sum]-$row[ai1])!=0){
      	$ok_cost = $row[total_sum]-$row[ai1];
      }else{
      	$ok_cost = "ok";
      }
    }

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $i-2)
                 ->setCellValue("B".$i, $row[h1])
                 ->setCellValue("C".$i, $row[i1])
                 ->setCellValue("D".$i, $row[jm1])
                 ->setCellValue("E".$i, $row[p1])
                 ->setCellValue("F".$i, $row[comp_rec_date])
                 ->setCellValue("G".$i, $row[al1_acp_date])
                 ->setCellValue("H".$i, $row[vir_acc_date])
								 ->setCellValue("I".$i, f_date_cut($row[ah1]))
								 ->setCellValue("J".$i, $row[gch1_cost_date])
								 ->setCellValue("K".$i, $row[gch2_cost_date])
								 ->setCellValue("L".$i, $row[dun_cost_date])
								 ->setCellValue("M".$i, $row[ai1])
								 ->setCellValue("N".$i, $row[elc_no])
								 ->setCellValue("O".$i, $row[vir_acc_no])
								 ->setCellValue("P".$i, f_multihouse_type_value($row[multi_housing_type]))
								 ->setCellValue("Q".$i, $ok_cost)
								 ->setCellValue("R".$i, $row[pay_dead_date])
								 ->setCellValue("S".$i, $row[al1_hope_yn])
								 ->setCellValue("T".$i, $row[al1_imp_yn])
								 ->setCellValue("U".$i, $row[al1])
								 ->setCellValue("V".$i, $row[am1_pur_cost])
								 ->setCellValue("W".$i, $row[bond_sale_rate])
								 ->setCellValue("X".$i, $row[aj1_tmp])
								 ->setCellValue("Y".$i, $row[ak1_tmp])
								 ->setCellValue("Z".$i, $row[an1_by])
								 ->setCellValue("AA".$i,$row[as1_by])
								 ->setCellValue("AB".$i,$row[ao1_by])
								 ->setCellValue("AC".$i,$row[ap1_by])
								 ->setCellValue("AD".$i,$row[aq1_by])
								 ->setCellValue("AE".$i,$row[etc_cost_sum])
								 ->setCellValue("AF".$i,$row[total_sum])
								 ->setCellValue("AG".$i,$row[gch1_cost])
								 ->setCellValue("AH".$i,$row[gch2_cost])
                 ;
	$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "비용안내_엑셀_".date("Ymd"));
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
