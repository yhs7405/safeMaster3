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
	if($bank_null_ch=="Y"){
		$wherequery.= " and d1 = '' "; // 은행 앞 체크 - 설정갯수가 0인 대상조회
	} else {
		if($bank_code!="")		$wherequery.= " and d1 = '".$bank_code."' ";
		if($jijum_code!="")		$wherequery.= " and e1 = '".$jijum_code."' ";
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
  // as1(cs비고) , au1(은행비고)
	if($memo!=""){
		$wherequery.= " and (ad1 like '%{$memo}%' or au1 like '%{$memo}%') ";
	}

	$wherequery.= $imss;

	$objPHPExcel = new PHPExcel();

	$sql = "select *, if(m1='',j1,CONCAT(j1, ',', m1)) as jm1 from $board_dbname $wherequery  order by  cast(a1 as unsigned) asc ";
	//echo $sql;
	$i_sql = $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->mergeCells('A1:B1');
	$objPHPExcel->getActiveSheet()->mergeCells('C1:F1');
	$objPHPExcel->getActiveSheet()->mergeCells('G1:K1');
	$objPHPExcel->getActiveSheet()->mergeCells('L1:V1');
	$objPHPExcel->getActiveSheet()->mergeCells('W1:AE1');
	$objPHPExcel->getActiveSheet()->mergeCells('AF1:AG1');

//	$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(15);
//	$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(15);
//	$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(15);
//	$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(15);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "동,호 정보")
                 ->setCellValue("C".$i, "건물정보")
                 ->setCellValue("G".$i, "토지정보")
                 ->setCellValue("L".$i, "금액 및 날짜")
                 ->setCellValue("W".$i, "매수자정보")
                 ->setCellValue("AF".$i, "매수자별 자금조달계획서(대상인 경우에만 입력하면 됩니다.)")
                 ->setCellValue("AG".$i, "")

//                 ->setCellValue("AK".$i, "1")
//                 ->setCellValue("AL".$i, "2")
//                 ->setCellValue("AM".$i, "3")
//                 ->setCellValue("AN".$i, "4")
                 ;
	$i++;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AT')->setWidth(20);

//	$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(15);
//	$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(15);
//	$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(15);
//	$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(15);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "동명")
                 ->setCellValue("B".$i, "호명")
                 ->setCellValue("C".$i, "전용면적")
                 ->setCellValue("D".$i, "건물지분(분모)")
                 ->setCellValue("E".$i, "건물지분(분자)")
                 ->setCellValue("F".$i, "계약면적(건물)")
                 ->setCellValue("G".$i, "대지권비율(분모)")
                 ->setCellValue("H".$i, "대지권비율(분자)")
                 ->setCellValue("I".$i, "토지지분(분모)")
                 ->setCellValue("J".$i, "토지지분(분자)")
                 ->setCellValue("K".$i, "계약면적(토지)")
                 ->setCellValue("L".$i, "공급가액")
                 ->setCellValue("M".$i, "옵션액")
                 ->setCellValue("N".$i, "추가지불액")
                 ->setCellValue("O".$i, "거래금액")
                 ->setCellValue("P".$i, "계약금")
                 ->setCellValue("Q".$i, "계약일자")
                 ->setCellValue("R".$i, "중도금")
                 ->setCellValue("S".$i, "중도금일자")
                 ->setCellValue("T".$i, "잔금")
                 ->setCellValue("U".$i, "잔금지급일자")
                 ->setCellValue("V".$i, "참고사항")
                 ->setCellValue("W".$i, "매수자명")
                 ->setCellValue("X".$i, "유형")
                 ->setCellValue("Y".$i, "주민(법인)등록번호")
                 ->setCellValue("Z".$i, "사업자번호")
                 ->setCellValue("AA".$i, "전화번호")
                 ->setCellValue("AB".$i, "휴대번호")
                 ->setCellValue("AC".$i, "매수자지분(분모)")
                 ->setCellValue("AD".$i, "매수자지분(분자)")
                 ->setCellValue("AE".$i, "도로명주소")
                 ->setCellValue("AF".$i, "자금조달계획 제출구분")
                 ->setCellValue("AG".$i, "금융기관예금액")
                 ->setCellValue("AH".$i, "부동산매도액")
                 ->setCellValue("AI".$i, "주식채권 매각대금")
                 ->setCellValue("AJ".$i, "보증금 등 승계")
                 ->setCellValue("AK".$i, "현금 등 기타")
                 ->setCellValue("AL".$i, "금융기관 대출액")
                 ->setCellValue("AM".$i, "사채")
                 ->setCellValue("AN".$i, "기타")
                 ->setCellValue("AO".$i, "입주계획(본인입주)")
                 ->setCellValue("AP".$i, "입주계획(가족입주)")
                 ->setCellValue("AQ".$i, "입주계획(임대)")
                 ->setCellValue("AR".$i, "입주예정시기")
                 ->setCellValue("AS".$i, "취득자2")
                 ->setCellValue("AT".$i, "주민번호2")

//                 ->setCellValue("AK".$i, "1")
//                 ->setCellValue("AL".$i, "2")
//                 ->setCellValue("AM".$i, "3")
//                 ->setCellValue("AN".$i, "4")
                 ;
	$i++;

	while($row = $stmt->fetch()){


		 $objPHPExcel->setActiveSheetIndex(0)
								 ->setCellValue("A".$i, $row[h1])
								 ->setCellValue("B".$i, $row[i1])
								 ->setCellValue("C".$i, $row[con_building_area])
								 ->setCellValue("D".$i, "")
								 ->setCellValue("E".$i, "")
								 ->setCellValue("F".$i, $row[con_building_area])
								 ->setCellValue("G".$i, "")
								 ->setCellValue("H".$i, "")
								 ->setCellValue("I".$i, "")
								 ->setCellValue("J".$i, "")
								 ->setCellValue("K".$i, $row[con_land_area])
								 ->setCellValue("L".$i, "")
								 ->setCellValue("M".$i, "")
								 ->setCellValue("N".$i, "")
								 ->setCellValue("O".$i, "")
								 ->setCellValue("P".$i, "")
								 ->setCellValue("Q".$i, $row[contract_date])
								 ->setCellValue("R".$i, "")
								 ->setCellValue("S".$i, "")
								 ->setCellValue("T".$i, "")
								 ->setCellValue("U".$i, $row[balance_date])
								 ->setCellValue("V".$i, "")
								 ->setCellValue("W".$i, $row[j1])
								 ->setCellValue("X".$i, "")
								 ->setCellValue("Y".$i, $row[k1])
								 ->setCellValue("Z".$i, "")
								 ->setCellValue("AA".$i,"")
								 ->setCellValue("AB".$i, $row[p1])
								 ->setCellValue("AC".$i,"")
								 ->setCellValue("AD".$i,"")
								 ->setCellValue("AE".$i,"")
								 ->setCellValue("AF".$i,"")
								 ->setCellValue("AG".$i,"")
								 ->setCellValue("AH".$i,"")
								 ->setCellValue("AI".$i,"")
								 ->setCellValue("AJ".$i,"")
								 ->setCellValue("AK".$i,"")
								 ->setCellValue("AL".$i,"")
								 ->setCellValue("AM".$i,"")
								 ->setCellValue("AN".$i,"")
								 ->setCellValue("AO".$i,"")
								 ->setCellValue("AP".$i,"")
								 ->setCellValue("AQ".$i,"")
								 ->setCellValue("AR".$i,"")
								 ->setCellValue("AS".$i,$row[m1])
								 ->setCellValue("AT".$i,$row[n1])
					 
//					 ->setCellValue("AK".$i,$imsi_daesang)
//					 ->setCellValue("AL".$i,$i_sql)
//					 ->setCellValue("AM".$i,$j_sql)
//					 ->setCellValue("AN".$i,$k_sql)
		 ;
		$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "검인신고용_엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
