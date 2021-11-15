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

	$sql = "select *, if(m1='',j1,CONCAT(j1, ',', m1)) as jm1, DATEDIFF(DATE_FORMAT(now(),'%Y%m%d'),tax_end_date) as tax_im_date,  DATEDIFF(al1_acp_date,tax_end_date) as tax2_im_date from $board_dbname  $wherequery order by  cast(a1 as unsigned) asc ";
	//echo $sql;


	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "■ ".f_hyunjang_name($h_idx)."(".f_money0($rows_total).")건");
	$i++;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(50);
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

	$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setWrapText(true);
//	$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('M')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('N')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('O')->getAlignment()->setWrapText(true);

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "최대4자리\n동명칭")
                 ->setCellValue("C".$i, "최대4자리\n호명칭")
                 ->setCellValue("D".$i, "0개인\n1법인\n납세자구분")
                 ->setCellValue("E".$i, "숫자13자리\n납세자번호")
                 ->setCellValue("F".$i, "납세자명")
                 ->setCellValue("G".$i, "납세자주소")
                 ->setCellValue("H".$i, "1 배우자 혹은 직계존비속\n9 기타\n매도자와의관계")
                 ->setCellValue("I".$i, "소숫점2자리\n토지면적")
                 ->setCellValue("J".$i, "소숫점2자리\n건물면적")
                 ->setCellValue("K".$i, "잔금납부일")
                 ->setCellValue("L".$i, "취득과표(신고가액)")
                 ->setCellValue("M".$i, "주택시가(산출과표)")
                 ->setCellValue("N".$i, "과세구분(비과감면)")
                 ->setCellValue("O".$i, "0 과세\n1 전체비과세\n2 감면분비과세\n농특세과세구분")
                 ->setCellValue("P".$i, "(공유자주민번호)")
                 ->setCellValue("Q".$i, "(공유자이름)")
                 ->setCellValue("R".$i, "(공유자주소)")
                 ->setCellValue("S".$i, "전화번호1")
                 ->setCellValue("T".$i, "취득세소계")
                 ->setCellValue("U".$i, "물건지지번")
                 ->setCellValue("V".$i, "물건지도로명")
                 ->setCellValue("W".$i, "취득자1지분")
                 ->setCellValue("X".$i, "취득자2지분")
                 ->setCellValue("Y".$i, "분양가")
                 ->setCellValue("Z".$i, "발코니")
                 ->setCellValue("AA".$i, "옵션1")
                 ->setCellValue("AB".$i, "옵션2")
                 ->setCellValue("AC".$i, "옵션3")
                 ->setCellValue("AD".$i, "옵션4")
                 ->setCellValue("AE".$i, "부가세")
                 ->setCellValue("AF".$i, "할인액")
                 ->setCellValue("AG".$i, "프리미엄")
                 ;
	$i++;

	while($row = $stmt->fetch()){

		//준공일 , FAQ URL
		$sql = "select * from tbl_hyunjang_danji_info where h_idx='{$row[h_idx]}' and danji_name='{$row[b1]}' ";
		$stmts = $pdo->prepare($sql);
		$stmts->execute();
		$stmts->setFetchMode(PDO::FETCH_ASSOC);
		$r1 = $stmts->fetch();

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $i-2)
                 ->setCellValue("B".$i, $row[h1])
                 ->setCellValue("C".$i, $row[i1])
                 ->setCellValue("D".$i, "0")
                 ->setCellValue("E".$i, f_de_date($row[k1]))
                 ->setCellValue("F".$i, $row[j1])
                 ->setCellValue("G".$i, $row[l1])
                 ->setCellValue("H".$i, "9")
								 ->setCellValue("I".$i, $row[con_land_area])
								 ->setCellValue("J".$i, $row[con_building_area])
								 ->setCellValue("K".$i, $row[balance_date])
								 ->setCellValue("L".$i, $row[af1])
								 ->setCellValue("M".$i, $row[af1])
								 ->setCellValue("N".$i, "0000000000")
								 ->setCellValue("O".$i, "0")
								 ->setCellValue("P".$i, f_de_date($row[n1]))
								 ->setCellValue("Q".$i, $row[m1])
								 ->setCellValue("R".$i, $row[o1])
								 ->setCellValue("S".$i, $row[p1])
								 ->setCellValue("T".$i, $row[al1])
								 ->setCellValue("U".$i, $r1[jibun_addr])
								 ->setCellValue("V".$i, $r1[doro_addr])
								 ->setCellValue("W".$i, $row[j1_stake])
								 ->setCellValue("X".$i, $row[m1_stake])
								 ->setCellValue("Y".$i, $row[bunyang_cost])
								 ->setCellValue("Z".$i, $row[balkoni_cost])
								 ->setCellValue("AA".$i,$row[option1_cost])
								 ->setCellValue("AB".$i,$row[option2_cost])
								 ->setCellValue("AC".$i,$row[option3_cost])
								 ->setCellValue("AD".$i,$row[option4_cost])
								 ->setCellValue("AE".$i,$row[vat])
								 ->setCellValue("AF".$i,$row[discount_cost])
								 ->setCellValue("AG".$i,$row[pre_cost])
                 ;
		$objPHPExcel->getActiveSheet()->setCellValueExplicit("E".$i, f_de_date($row[k1]),PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit("P".$i, f_de_date($row[n1]),PHPExcel_Cell_DataType::TYPE_STRING);

	$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "취득세신고_취신고엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
