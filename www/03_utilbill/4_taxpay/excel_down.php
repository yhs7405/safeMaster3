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
	$target_gubun3			=	trim($_REQUEST[target_gubun3]);

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
  // ae1 정산비고
	if($memo!=""){
		$wherequery.= " and ae1 like '%{$memo}%'";
	}

  // 취득세납부임박
	if($target_gubun=="taget1"){
		$wherequery.= " and (DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) >= -1 and DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) <= 0 and r1 = '' )"; // 취득세신고만료일 - 취득세신고일
	} else if($target_gubun=="taget2"){
		$wherequery.= " and (DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) >= -3 and DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) <= 0 and r1 = '' )";
	} else if($target_gubun=="taget3"){
		$wherequery.= " and (DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) >= -7 and DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) <= 0 and r1 = '' )";
	} else if($target_gubun=="taget4"){
		$wherequery.= " and (DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) >= -15 and DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) <= 0 and r1 = '' )";
	} else if($target_gubun=="taget5"){
		$wherequery.= " and (DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) >= -30 and DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) <= 0 and r1 = '' )";
	} else if($target_gubun=="taget6"){
		$wherequery.= " and ((DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) > 0 and r1 = '' ) or (DATEDIFF(r1,tax_end_date) > 0 and r1 <> '' )) ";
	}

  // 취본
	if($target_gubun2=="al1_hope_yn"){
		$wherequery.= " and al1_hope_yn = 'y'";
	} else if($target_gubun2=="al1_imp_yn"){
		$wherequery.= " and al1_imp_yn = 'y'";
	}

  // 조회조건
	if($target_gubun3=="taget1"){
		$wherequery.= " and (al1_hope_yn = 'y' or al1_imp_yn = 'y') ";
		$wherequery.= " and al1 < ai1 ";
	} else if($target_gubun3=="taget2"){
		$wherequery.= " and (al1_hope_yn <> 'y' and al1_imp_yn <> 'y') ";
		$wherequery.= " and al1 < ai1 ";
	} else if($target_gubun3=="taget3"){
		$wherequery.= " and (al1_hope_yn = 'y' or al1_imp_yn = 'y' or al1 > ai1 ) ";
	}


	$wherequery.= $imss;

	//echo $wherequery;
	$rows_total = db_count_all($board_dbname,$wherequery);


	$objPHPExcel = new PHPExcel();

	$sql = "select *, if(m1='',j1,CONCAT(j1, ',', m1)) as jm1, DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) as ft_date, DATEDIFF(r1,tax_end_date) as ft1_date from $board_dbname $wherequery  order by  cast(a1 as unsigned) asc ";
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(15);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "동")
                 ->setCellValue("C".$i, "호")
                 ->setCellValue("D".$i, "취득자")
                 ->setCellValue("E".$i, "취신고")
                 ->setCellValue("F".$i, "취전달")
                 ->setCellValue("G".$i, "전자납부번호")
                 ->setCellValue("H".$i, "취득세납부일")
                 ->setCellValue("I".$i, "취득세납부안내문자일")
                 ->setCellValue("J".$i, "취임박")
                 ->setCellValue("K".$i, "취소계")
                 ->setCellValue("L".$i, "취득세실납부액")
                 ->setCellValue("M".$i, "총비용")
                 ->setCellValue("N".$i, "취본희망")
                 ->setCellValue("O".$i, "취본납부")
                 ->setCellValue("P".$i, "수납NUST")
                 ->setCellValue("Q".$i, "수납액")
                 ->setCellValue("R".$i, "수납결과")
                 ->setCellValue("S".$i, "1차비용안내금액")
                 ->setCellValue("T".$i, "2차비용안내금액")
                 ->setCellValue("U".$i, "정산비고")
                 ->setCellValue("V".$i, "잔금일")
                 ->setCellValue("W".$i, "비용입금일")
                 ->setCellValue("X".$i, "(예상)등기접수일")
                 ->setCellValue("Y".$i, "등기접수일")
                 ;
	$i++;

	while($row = $stmt->fetch()){
		
		if($row[al1_hope_yn]=="y"||$row[al1_imp_yn]=="y"){
			$s_must = $row[total_sum]-$row[al1];
			if(($row[total_sum]-$row[al1]-$row[ai1])!=0){
				$s_result = $row[total_sum]-$row[al1]-$row[ai1];
			} else {
				$s_result = "ok";
			}
		} else {
			$s_must = $row[total_sum];
			if(($row[total_sum]-$row[ai1])!=0){
				$s_result = $row[total_sum]-$row[ai1];
			} else {
				$s_result = "ok";
			}
		}

		if($row[r1]==""){
			$s_limt = $row[ft_date];
		} else if($row[ft1_date]>0){
			$s_limt = "도과";
		} else {
			$s_limt = "OK";
		}

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $i-2)
                 ->setCellValue("B".$i, $row[h1])
                 ->setCellValue("C".$i, $row[i1])
                 ->setCellValue("D".$i, $row[jm1])
                 ->setCellValue("E".$i, $row[al1_acp_date])
                 ->setCellValue("F".$i, $row[al1_reg_date])
                 ->setCellValue("G".$i, $row[elc_no])
                 ->setCellValue("H".$i, $row[r1])
								 ->setCellValue("I".$i, $row[tax_text_date])
								 ->setCellValue("J".$i, $s_limt)
								 ->setCellValue("K".$i, $row[al1])
								 ->setCellValue("L".$i, $row[al1_silapp_cost])
								 ->setCellValue("M".$i, $row[total_sum])
								 ->setCellValue("N".$i, $row[al1_hope_yn])
								 ->setCellValue("O".$i, $row[al1_imp_yn])
								 ->setCellValue("P".$i, $s_must)
								 ->setCellValue("Q".$i, $row[ai1])
								 ->setCellValue("R".$i, $s_result)
								 ->setCellValue("S".$i, $row[gch1_cost])
								 ->setCellValue("T".$i, $row[gch2_cost])
								 ->setCellValue("U".$i, $row[ae1])
								 ->setCellValue("V".$i, $row[balance_date])
								 ->setCellValue("W".$i, $row[al1_reg_date])
								 ->setCellValue("X".$i, $row[pred_g1])
								 ->setCellValue("Y".$i, $row[g1])
                 ;
	$i++;
	}

 
	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "취득세납부_엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
