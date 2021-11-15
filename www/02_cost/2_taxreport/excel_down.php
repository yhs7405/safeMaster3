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

	$target_gubun			=	trim($_REQUEST[target_gubun]);
	$target_gubun2			=	trim($_REQUEST[target_gubun2]);

	$h1					=	trim($_REQUEST[h1]);
	$i1					=	trim($_REQUEST[i1]);
	$j1					=	trim($_REQUEST[j1]);
	$memo				=	trim($_REQUEST[memo]);
	$kikan1_null_ch		=	trim($_REQUEST[kikan1_null_ch]);
	$kikan2_null_ch		=	trim($_REQUEST[kikan2_null_ch]);

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
  // ad1(cs비고) , au1(은행비고)
	if($memo!=""){
		$wherequery.= " and ad1 like '%{$memo}%' ";
	}

  // 취득세신고임박
	if($target_gubun=="taget1"){
		$wherequery.= " and (DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) >= -1 and DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) <= 0 and al1_acp_date = '' ) "; // 취득세신고만료일 - 취득세신고일
	} else if($target_gubun=="taget2"){
		$wherequery.= " and (DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) >= -3 and DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) <= 0 and al1_acp_date = '' ) ";
	} else if($target_gubun=="taget3"){
		$wherequery.= " and (DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) >= -7 and DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) <= 0 and al1_acp_date = '' ) ";
	} else if($target_gubun=="taget4"){
		$wherequery.= " and (DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) >= -15 and DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) <= 0 and al1_acp_date = '' ) ";
	} else if($target_gubun=="taget5"){
		$wherequery.= " and (DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) >= -30 and DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) <= 0 and al1_acp_date = '' ) ";
	} else if($target_gubun=="taget6"){
		$wherequery.= " and ((DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) > 0 and al1_acp_date = '' ) or (DATEDIFF(al1_acp_date,tax_end_date) > 0 and al1_acp_date <> '' )) ";
	}

  // 취본
	if($target_gubun2=="al1_hope_yn"){
		$wherequery.= " and al1_hope_yn = 'y'";
	} else if($target_gubun2=="al1_imp_yn"){
		$wherequery.= " and al1_imp_yn = 'y'";
	}


	$wherequery.= $imss;

	//echo $wherequery;
	$rows_total = db_count_all($board_dbname,$wherequery);


	$objPHPExcel = new PHPExcel();

	$sql = "select *, if(m1='',j1,CONCAT(j1, ',', m1)) as jm1, DATEDIFF(DATE_FORMAT(now(),'%Y%m%d'),tax_end_date) as tax_im_date,  DATEDIFF(al1_acp_date,tax_end_date) as tax2_im_date  from $board_dbname  $wherequery order by  cast(a1 as unsigned) asc";
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(50);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "동")
                 ->setCellValue("C".$i, "호")
                 ->setCellValue("D".$i, "취득자")
                 ->setCellValue("E".$i, "완증수령일")
                 ->setCellValue("F".$i, "검인신청일")
                 ->setCellValue("G".$i, "취본희망")
                 ->setCellValue("H".$i, "취본납부")
                 ->setCellValue("I".$i, "취신고임박")
                 ->setCellValue("J".$i, "취득세신고일")
                 ->setCellValue("K".$i, "취득세수령일")
                 ->setCellValue("L".$i, "전자납부번호")
                 ->setCellValue("M".$i, "취득세전달일")
                 ->setCellValue("N".$i, "잔금일")
                 ->setCellValue("O".$i, "취득물건")
                 ->setCellValue("P".$i, "취득세과표")
                 ->setCellValue("Q".$i, "취감면사유")
                 ->setCellValue("R".$i, "취득세")
                 ->setCellValue("S".$i, "지방교육세")
                 ->setCellValue("T".$i, "농어촌특별세")
                 ->setCellValue("U".$i, "취소계")
                 ->setCellValue("V".$i, "CS비고")
                 ;
	$i++;


		if($row[al1_acp_date]==""){
			$tax_im_date = $row[tax_im_date];
		} else if($row[tax2_im_date]>0){
			$tax_im_date = "도과";
		} else {
			$tax_im_date = "OK";
		}
     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $i-2)
                 ->setCellValue("B".$i, $row[h1])
                 ->setCellValue("C".$i, $row[i1])
                 ->setCellValue("D".$i, $row[jm1])
                 ->setCellValue("E".$i, $row[comp_rec_date])
                 ->setCellValue("F".$i, $row[prob_apply_date])
                 ->setCellValue("G".$i, $row[al1_hope_yn])
                 ->setCellValue("H".$i, $row[al1_imp_yn])
								 ->setCellValue("I".$i, $tax_im_date)
								 ->setCellValue("J".$i, $row[al1_acp_date])
								 ->setCellValue("K".$i, $row[al1_rec_date])
								 ->setCellValue("L".$i, $row[elc_no])
								 ->setCellValue("M".$i, $row[al1_reg_date])
								 ->setCellValue("N".$i, $row[balance_date])
								 ->setCellValue("O".$i, f_apply_type_value($row[apply_type]))
								 ->setCellValue("P".$i, $row[af1])
								 ->setCellValue("Q".$i, f_tax_cut_cause_value($row[tax_cut_cause]))
								 ->setCellValue("R".$i, $row[al1_tax])
								 ->setCellValue("S".$i, $row[al1_edu])
								 ->setCellValue("T".$i, $row[al1_farm])
								 ->setCellValue("U".$i, $row[al1])
								 ->setCellValue("V".$i, $row[ad1])
                 ;
	$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "취득세신고_엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
