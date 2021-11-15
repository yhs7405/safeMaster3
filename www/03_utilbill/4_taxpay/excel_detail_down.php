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

	$sql = "select *, if(m1='',j1,CONCAT(j1, ',', m1)) as jm1, DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) as ft_date , DATEDIFF(r1,tax_end_date) as ft1_date from $board_dbname $wherequery  order by  cast(a1 as unsigned) asc ";
	//echo $sql;


	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

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
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "동")
                 ->setCellValue("B".$i, "호")
                 ->setCellValue("C".$i, "취득자1")
                 ->setCellValue("D".$i, "주민번호1")
                 ->setCellValue("E".$i, "취득자2")
                 ->setCellValue("F".$i, "주민번호2")
                 ->setCellValue("G".$i, "전화번호1")
                 ->setCellValue("H".$i, "잔금일")
                 ->setCellValue("I".$i, "납부기한(60일)")
                 ->setCellValue("J".$i, "취신고")
                 ->setCellValue("K".$i, "취전달")
                 ->setCellValue("L".$i, "취득세고지서 전자납부번호")
                 ->setCellValue("M".$i, "취납부")
                 ->setCellValue("N".$i, "취본희망")
                 ->setCellValue("O".$i, "취본납부")
                 ->setCellValue("P".$i, "수납일")
                 ->setCellValue("Q".$i, "수납금액")
                 ->setCellValue("R".$i, "취득세소계")
                 ->setCellValue("S".$i, "취득세실납부액")
                 ->setCellValue("T".$i, "수납결과")
                 ;
	$i++;

	while($row = $stmt->fetch()){
		
		if($row[al1_hope_yn]=="y"||$row[al1_imp_yn]=="y"){
			if($row[al1_hope_yn]=="y"&&$row[al1_imp_yn]=="y"){
				$s_nabbu = "취본 희망/납부";
			} else if($row[al1_hope_yn]=="y"){
				$s_nabbu = "취본희망";
			} else if($row[al1_imp_yn]=="y"){
				$s_nabbu = "취본납부";
			} 
			if(($row[total_sum]-$row[al1]-$row[ai1])!=0){
				$s_result = $row[total_sum]-$row[al1]-$row[ai1];
			} else {
				$s_result = "ok";
			}
		} else {
			if($row[al1]>$row[ai1]){
				$s_nabbu = "납부불가";
			} else {
				$s_nabbu = $row[al1_silapp_cost];
			}
			if(($row[total_sum]-$row[ai1])!=0){
				$s_result = $row[total_sum]-$row[ai1];
			} else {
				$s_result = "ok";
			}
		}

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $row[h1])
                 ->setCellValue("B".$i, $row[i1])
                 ->setCellValue("C".$i, $row[j1])
                 ->setCellValue("D".$i, $row[k1])
                 ->setCellValue("E".$i, $row[m1])
                 ->setCellValue("F".$i, $row[n1])
                 ->setCellValue("G".$i, $row[p1])
                 ->setCellValue("H".$i, $row[balance_date])
								 ->setCellValue("I".$i, $row[tax_end_date])
								 ->setCellValue("J".$i, $row[al1_acp_date])
								 ->setCellValue("K".$i, $row[al1_reg_date])
								 ->setCellValue("L".$i, $row[elc_no])
								 ->setCellValue("M".$i, $row[r1])
								 ->setCellValue("N".$i, f_yn_value($row[al1_hope_yn]))
								 ->setCellValue("O".$i, f_yn_value($row[al1_imp_yn]))
								 ->setCellValue("P".$i, f_date_cut($row[ah1]))
								 ->setCellValue("Q".$i, $row[ai1])
								 ->setCellValue("R".$i, $row[al1])
								 ->setCellValue("S".$i, $s_nabbu)
								 ->setCellValue("T".$i, $s_result)
                 ;
	$i++;
	}

 
	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "취득세납부_상세엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
