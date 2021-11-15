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

	$target_gubun			=	trim($_REQUEST[target_gubun]);
	$target_gubun2			=	trim($_REQUEST[target_gubun2]);
	$target_gubun3			=	trim($_REQUEST[target_gubun3]);


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

	if($bank_null_ch=="Y"){
		$wherequery.= " and d1 = '' "; // 은행 앞 체크 - 설정갯수가 0인 대상조회
	} else {
		if($bank_code!="")		$wherequery.= " and d1 = '".$bank_code."' ";
		if($jijum_code!="")		$wherequery.= " and e1 = '".$jijum_code."' ";
	}
	
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
  // ae1(정산비고) au1(은행비고)
	if($memo!=""){
		$wherequery.= " and  ( ae1 like '%{$memo}%' or rec_memo like '%{$memo}%' )";
	}

  // 서류진행
	if($target_gubun=="1"){
		$wherequery.= " and mibi_doc <> ''";
	} else if($target_gubun=="2"){
		$wherequery.= " and mibi_doc = ''";
	}

// 서류유효기간
	if($target_gubun2=="1"){
		$wherequery.= " and (((j1_ingam_limit_date <> '') or (j1_chobon_limit_date <> '') or (j1_etc_limit_date <> '') or (m1_ingam_limit_date <> '') or (m1_chobon_limit_date <> '') or (m1_etc_limit_date <> '') ) ";
		$wherequery.= "       and ((DATEDIFF(pred_g1,j1_ingam_limit_date) > 0) or (DATEDIFF(pred_g1,j1_chobon_limit_date) > 0) or (DATEDIFF(pred_g1,j1_etc_limit_date) > 0) or ";
		$wherequery.= "            (DATEDIFF(pred_g1,m1_ingam_limit_date) > 0) or (DATEDIFF(pred_g1,m1_chobon_limit_date) > 0) or (DATEDIFF(pred_g1,m1_etc_limit_date) > 0) ))";
	} else if($target_gubun2=="2"){
		//$wherequery.= " and (((j1_ingam_limit_date <> '') or (j1_chobon_limit_date <> '') or (j1_etc_limit_date <> '') or (m1_ingam_limit_date <> '') or (m1_chobon_limit_date <> '') or (m1_etc_limit_date <> '') ) ";
		$wherequery.= "       and (((j1_ingam_limit_date  <> '') and (DATEDIFF(pred_g1,j1_ingam_limit_date)  <= 0)) and ((j1_chobon_limit_date <> '') and (DATEDIFF(pred_g1,j1_chobon_limit_date) <= 0) )  ";
		$wherequery.= "         and ((j1_etc_limit_date    <> '') and (DATEDIFF(pred_g1,j1_etc_limit_date)    <= 0)) and ((m1_ingam_limit_date  <> '') and (DATEDIFF(pred_g1,m1_ingam_limit_date)  <= 0))   ";
		$wherequery.= "         and ((m1_chobon_limit_date <> '') and (DATEDIFF(pred_g1,m1_chobon_limit_date) <= 0)) and ((m1_etc_limit_date    <> '') and (DATEDIFF(pred_g1,m1_etc_limit_date)    <= 0) ))  ";
	}


  // 등기접수임박
	if($target_gubun3=="taget1"){
		$wherequery.= " and ((DATEDIFF(date_format(NOW(),'%Y%m%d'),pred_g1_temp) >= -1 and DATEDIFF(date_format(NOW(),'%Y%m%d'),pred_g1_temp) <= 0 and pred_g1 = '' ) or ( DATEDIFF(pred_g1,pred_g1_temp) >= -1 and DATEDIFF(pred_g1,pred_g1_temp) <= 0 and pred_g1 <> '' )) "; // 취득세신고만료일 - 취득세신고일
	} else if($target_gubun3=="taget2"){
		$wherequery.= " and ((DATEDIFF(date_format(NOW(),'%Y%m%d'),pred_g1_temp) >= -3 and DATEDIFF(date_format(NOW(),'%Y%m%d'),pred_g1_temp) <= 0 and pred_g1 = '' ) or ( DATEDIFF(pred_g1,pred_g1_temp) >= -3 and DATEDIFF(pred_g1,pred_g1_temp) <= 0 and pred_g1 <> '' )) ";
	} else if($target_gubun3=="taget3"){
		$wherequery.= " and ((DATEDIFF(date_format(NOW(),'%Y%m%d'),pred_g1_temp) >= -7 and DATEDIFF(date_format(NOW(),'%Y%m%d'),pred_g1_temp) <= 0 and pred_g1 = '' ) or ( DATEDIFF(pred_g1,pred_g1_temp) >= -7 and DATEDIFF(pred_g1,pred_g1_temp) <= 0 and pred_g1 <> '' )) ";
	} else if($target_gubun3=="taget4"){
		$wherequery.= " and ((DATEDIFF(date_format(NOW(),'%Y%m%d'),pred_g1_temp) >= -15 and DATEDIFF(date_format(NOW(),'%Y%m%d'),pred_g1_temp) <= 0 and pred_g1 = '' ) or ( DATEDIFF(pred_g1,pred_g1_temp) >= -15 and DATEDIFF(pred_g1,pred_g1_temp) <= 0 and pred_g1 <> '' )) ";
	} else if($target_gubun3=="taget5"){
		$wherequery.= " and ((DATEDIFF(date_format(NOW(),'%Y%m%d'),pred_g1_temp) >= -30 and DATEDIFF(date_format(NOW(),'%Y%m%d'),pred_g1_temp) <= 0 and pred_g1 = '' ) or ( DATEDIFF(pred_g1,pred_g1_temp) >= -30 and DATEDIFF(pred_g1,pred_g1_temp) <= 0 and pred_g1 <> '' )) ";
	} else if($target_gubun3=="taget6"){
		$wherequery.= " and ((DATEDIFF(date_format(NOW(),'%Y%m%d'),pred_g1_temp) > 0 and pred_g1 = '' ) or (DATEDIFF(pred_g1,pred_g1_temp) > 0 and pred_g1 <> '' )) ";
	}

	$wherequery.= $imss;

	//echo $wherequery;
	$rows_total = db_count_all($board_dbname,$wherequery);

	//현장정보 반환
	$sql2 = "select * from tbl_hyunjang_info where h_idx='{$h_idx}' limit 1 ";
	$kk = db_query_value($sql2);

	$objPHPExcel = new PHPExcel();

	$sql = "select *, if(m1='',j1,CONCAT(j1, ',', m1)) as jm1, DATEDIFF(pred_g1,pred_g1_temp) as g1_temp, DATEDIFF(date_format(NOW(),'%Y%m%d'),pred_g1_temp) as g1_temp2 , DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) as ft_date, if(point_data='1','이전ONLY',if(point_data='2', '설정ONLY', if(point_data='3', point_data_name, ''))) as point_dm, if(m1='',j1,CONCAT(j1, ',', m1)) as jm1 , DATEDIFF(pred_g1,j1_ingam_limit_date) as j1_ingam_limit_cnt, DATEDIFF(pred_g1,m1_ingam_limit_date) as m1_ingam_limit_cnt, DATEDIFF(pred_g1,j1_chobon_limit_date) as j1_chobon_limit_cnt, DATEDIFF(pred_g1,m1_chobon_limit_date) as m1_chobon_limit_cnt, DATEDIFF(pred_g1,j1_etc_limit_date) as j1_etc_limit_cnt, DATEDIFF(pred_g1,m1_etc_limit_date) as m1_etc_limit_cnt from $board_dbname  $wherequery order by  cast(a1 as unsigned) asc ";
	//echo $sql;


	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(50);
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AT')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AU')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AV')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AW')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AX')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AY')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AZ')->setWidth(0);

	$objPHPExcel->getActiveSheet()->getColumnDimension('BA')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BB')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BC')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BD')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BE')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BF')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BG')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BH')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BI')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BJ')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BK')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BL')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BM')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BN')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BO')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BP')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BQ')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BR')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BS')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BT')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BU')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BV')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BW')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BX')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BY')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BZ')->setWidth(20);

	$objPHPExcel->getActiveSheet()->getColumnDimension('CA')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CB')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CC')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CD')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CE')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CF')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CG')->setWidth(0);

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "")
                 ->setCellValue("B".$i, "")
                 ->setCellValue("C".$i, "동")
                 ->setCellValue("D".$i, "호")
                 ->setCellValue("E".$i, "계약일")
                 ->setCellValue("F".$i, "잔금일")
                 ->setCellValue("G".$i, "취득자1")
                 ->setCellValue("H".$i, "주민번호1")
                 ->setCellValue("I".$i, "주소1")
                 ->setCellValue("J".$i, "취득자2")
                 ->setCellValue("K".$i, "주민번호2")
                 ->setCellValue("L".$i, "주소2")
                 ->setCellValue("M".$i, "거래신고일련번호")
                 ->setCellValue("N".$i, "거래신고금액")
                 ->setCellValue("O".$i, "취득세과표")
                 ->setCellValue("P".$i, "시가표준(건물)")
                 ->setCellValue("Q".$i, "취1매입")
                 ->setCellValue("R".$i, "공유여부")
                 ->setCellValue("S".$i, "취1채권번호")
                 ->setCellValue("T".$i, "취2채권번호")
                 ->setCellValue("U".$i, "시가표준(건물)")
                 ->setCellValue("V".$i, "시가표준(토지)")
                 ->setCellValue("W".$i, "취득세")
                 ->setCellValue("X".$i, "교육세")
                 ->setCellValue("Y".$i, "농어촌특별세")
                 ->setCellValue("Z".$i, "취1매입")

                 ->setCellValue("AA".$i, "취2매입")
                 ->setCellValue("AB".$i, "건물총매입액")
                 ->setCellValue("AC".$i, "토지총매입액")
                 ->setCellValue("AD".$i, "고유번호")
                 ->setCellValue("AE".$i, "")
                 ->setCellValue("AF".$i, "등기원인일1")
                 ->setCellValue("AG".$i, "채권최고액1")
                 ->setCellValue("AH".$i, "")
                 ->setCellValue("AI".$i, "")
                 ->setCellValue("AJ".$i, "취득자1")
                 ->setCellValue("AK".$i, "주민번호1")
                 ->setCellValue("AL".$i, "주소1")
                 ->setCellValue("AM".$i, "취득자2")
                 ->setCellValue("AN".$i, "주민번호2")
                 ->setCellValue("AO".$i, "주소2")
                 ->setCellValue("AP".$i, "은행상호")
                 ->setCellValue("AQ".$i, "은행법인번호")
                 ->setCellValue("AR".$i, "은행주소")
                 ->setCellValue("AS".$i, "대표자구분")
                 ->setCellValue("AT".$i, "지점대표자이름")
                 ->setCellValue("AU".$i, "대출여부(지점)")
                 ->setCellValue("AV".$i, "채무자1")
                 ->setCellValue("AW".$i, "설1매입")
                 ->setCellValue("AX".$i, "설1번호")
                 ->setCellValue("AY".$i, "")
                 ->setCellValue("AZ".$i, "")

                 ->setCellValue("BA".$i, "등록면허세1")
                 ->setCellValue("BB".$i, "")
                 ->setCellValue("BC".$i, "")
                 ->setCellValue("BD".$i, "")
                 ->setCellValue("BE".$i, "등기원인일2")
                 ->setCellValue("BF".$i, "채권최고액2")
                 ->setCellValue("BG".$i, "설2매입")
                 ->setCellValue("BH".$i, "설2번호")
                 ->setCellValue("BI".$i, "")
                 ->setCellValue("BJ".$i, "")
                 ->setCellValue("BK".$i, "등록면허세2")
                 ->setCellValue("BL".$i, "")
                 ->setCellValue("BM".$i, "")
                 ->setCellValue("BN".$i, "")
                 ->setCellValue("BO".$i, "등기원인일3")
                 ->setCellValue("BP".$i, "채권최고액3")
                 ->setCellValue("BQ".$i, "설3매입")
                 ->setCellValue("BR".$i, "설3번호")
                 ->setCellValue("BS".$i, "")
                 ->setCellValue("BT".$i, "")
                 ->setCellValue("BU".$i, "등록면허세3")
                 ->setCellValue("BV".$i, "")
                 ->setCellValue("BW".$i, "")
                 ->setCellValue("BX".$i, "")
                 ->setCellValue("BY".$i, "등기원인일4")
                 ->setCellValue("BZ".$i, "채권최고액4")

                 ->setCellValue("CA".$i, "설4매입")
                 ->setCellValue("CB".$i, "설4번호")
                 ->setCellValue("CC".$i, "")
                 ->setCellValue("CD".$i, "")
                 ->setCellValue("CE".$i, "등록면허세4")
                 ->setCellValue("CF".$i, "")
                 ->setCellValue("CG".$i, "")
                 ;
	$i++;

	while($row = $stmt->fetch()){
		
//		if($row[f1]==)
		//설정1 정보 반환
		$sql2 = "select * from tbl_suljung where a1='{$row[a1]}' and suljung_no='1' limit 1 ";
		$sk1 = db_query_value($sql2);

		//설정2 정보 반환
		$sql2 = "select * from tbl_suljung where a1='{$row[a1]}' and suljung_no='2' limit 1 ";
		$sk2 = db_query_value($sql2);

		//설정3 정보 반환
		$sql2 = "select * from tbl_suljung where a1='{$row[a1]}' and suljung_no='3' limit 1 ";
		$sk3 = db_query_value($sql2);

		//설정4 정보 반환
		$sql2 = "select * from tbl_suljung where a1='{$row[a1]}' and suljung_no='4' limit 1 ";
		$sk4 = db_query_value($sql2);

		//은행정보 반환
		$sql2 = "select * from tbl_bank_info where bank_code='{$row[d1]}' limit 1 ";
		$kk1 = db_query_value($sql2);

		//은행지점정보 반환
		$sql2 = "select * from tbl_bank_jijum where bank_code='{$row[d1]}' and jijum_code='{$row[e1]}' limit 1 ";
		$kk2 = db_query_value($sql2);

		if($row[mibi_doc]!=""){
			$a_temp = $row[ae1];
		}else{
			$a_temp = "OK";
		}
		
		$av1 = floor($row[av1] * 0.002 /10) *10 ; //등록면허세 ROUNDDOWN(채권최고액*0.002,-1)
		$ax1 = floor($row[ax1] * 0.002 /10) *10 ;
		$ay1 = floor($row[ay1] * 0.002 /10) *10 ;
		$az1 = floor($row[az1] * 0.002 /10) *10 ;

		if($row[u1_gubun]=="y"){
			$singo_last_no = $row[singo_last_no];
			$singo_last_cost = $row[singo_last_cost];
		}else if($row[singo_gubun]=="실거래"){
			$singo_last_no = $row[singo_org_no];
			$singo_last_cost = $row[singo_org_cost];
		}


		if($row[m1]!=""){
			$m1 = "공유";
		}else{
			$m1 = "0";
		}
		

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "")
                 ->setCellValue("B".$i, "")
                 ->setCellValue("C".$i, $row[h1])
                 ->setCellValue("D".$i, $row[i1])
                 ->setCellValue("E".$i, $row[contract_date])
                 ->setCellValue("F".$i, $row[balance_date])
                 ->setCellValue("G".$i, $row[j1])
                 ->setCellValue("H".$i, $row[k1])
								 ->setCellValue("I".$i, $row[l1])
								 ->setCellValue("J".$i, $row[m1])
								 ->setCellValue("K".$i, $row[n1])
								 ->setCellValue("L".$i, $row[o1])
								 ->setCellValue("M".$i, $singo_last_no)  // 실거래정보입력 .html에서 마지막전매가 있는 경우, 거래신고일련번호(마지막전매), 거래신고금액(마지막전매)을 입력하고, 마지막에 전매가 없는 경우 중 '실거래대상인 경우' 거래신고일련번호(원, 거래신고금액(원)을 입력한다.
								 ->setCellValue("N".$i, $singo_last_cost)
								 ->setCellValue("O".$i, $row[af1])
								 ->setCellValue("P".$i, $row[ag1])
								 ->setCellValue("Q".$i, $row[gchi1_rec_cost])
								 ->setCellValue("R".$i, $m1)  // 취득자1만 있는 경우 0, 취득자2가 있는 경우 '공유'라고 현출
								 ->setCellValue("S".$i, $row[x1])
								 ->setCellValue("T".$i, $row[y1])
								 ->setCellValue("U".$i, $row[ag1])
								 ->setCellValue("V".$i, $row[ag2])
								 ->setCellValue("W".$i, $row[al1_tax])
								 ->setCellValue("X".$i, $row[al1_edu])
								 ->setCellValue("Y".$i, $row[al1_farm])
								 ->setCellValue("Z".$i, $row[gchi1_rec_cost])

                 ->setCellValue("AA".$i, $row[gchi2_rec_cost])
                 ->setCellValue("AB".$i, $row[bd_rec_cost])
                 ->setCellValue("AC".$i, $row[toji_rec_cost])
                 ->setCellValue("AD".$i, $row[a1])
                 ->setCellValue("AE".$i, "") 
                 ->setCellValue("AF".$i, $sk1[reg_cause_date]) // 설정1
                 ->setCellValue("AG".$i, $row[av1])
                 ->setCellValue("AH".$i, "") 
								 ->setCellValue("AI".$i, "") 
								 ->setCellValue("AJ".$i, $row[j1])
								 ->setCellValue("AK".$i, $row[k1])
								 ->setCellValue("AL".$i, $row[l1])
								 ->setCellValue("AM".$i, $row[m1])
								 ->setCellValue("AN".$i, $row[n1])
								 ->setCellValue("AO".$i, $row[o1])
								 ->setCellValue("AP".$i, $kk1[bank_name])
								 ->setCellValue("AQ".$i, $kk1[bubin_no])
								 ->setCellValue("AR".$i, $kk2[addr])
								 ->setCellValue("AS".$i, "") // 대표자구분이 없음 필요시 은행지점에 항목 추가하여 만들어야함
								 ->setCellValue("AT".$i, $kk2[ceo])
								 ->setCellValue("AU".$i, $kk2[jijum_name])
								 ->setCellValue("AV".$i, $row[aw1])
								 ->setCellValue("AW".$i, $row[ba1])
								 ->setCellValue("AX".$i, $row[z1])
								 ->setCellValue("AY".$i, "")
								 ->setCellValue("AZ".$i, "")

                 ->setCellValue("BA".$i, $av1) // 등록세(채권최고액1*0.2%)로 계산해서 뿌려주세요. ROUNDDOWN(채권최고액*0.002,-1)
                 ->setCellValue("BB".$i, "")
                 ->setCellValue("BC".$i, "")
                 ->setCellValue("BD".$i, "")
                 ->setCellValue("BE".$i, $sk2[reg_cause_date]) // 설정1
                 ->setCellValue("BF".$i, $row[ax1])
                 ->setCellValue("BG".$i, $row[bb1])
                 ->setCellValue("BH".$i, $row[aa1])
								 ->setCellValue("BI".$i, "")
								 ->setCellValue("BJ".$i, "")
								 ->setCellValue("BK".$i, $ax1)  // 등록세(채권최고액2*0.2%)로 계산해서 뿌려주세요. ROUNDDOWN(채권최고액*0.002,-1)
								 ->setCellValue("BL".$i, "")
								 ->setCellValue("BM".$i, "")
								 ->setCellValue("BN".$i, "")
								 ->setCellValue("BO".$i, $sk3[reg_cause_date]) // 설정1
								 ->setCellValue("BP".$i, $row[ay1])
								 ->setCellValue("BQ".$i, $row[bc1])
								 ->setCellValue("BR".$i, $row[ab1])
								 ->setCellValue("BS".$i, "")
								 ->setCellValue("BT".$i, "")
								 ->setCellValue("BU".$i, $ay1) // 등록세(채권최고액3*0.2%)로 계산해서 뿌려주세요. ROUNDDOWN(채권최고액*0.002,-1)
								 ->setCellValue("BV".$i, "")
								 ->setCellValue("BW".$i, "")
								 ->setCellValue("BX".$i, "")
								 ->setCellValue("BY".$i, $sk4[reg_cause_date]) // 설정1
								 ->setCellValue("BZ".$i, $row[az1])

                 ->setCellValue("CA".$i, $row[bd1])
                 ->setCellValue("CB".$i, $row[ac1])
                 ->setCellValue("CC".$i, "")
                 ->setCellValue("CD".$i, "")
                 ->setCellValue("CE".$i, $az1) // 등록세(채권최고액4*0.2%)로 계산해서 뿌려주세요. ROUNDDOWN(채권최고액*0.002,-1)
                 ->setCellValue("CF".$i, "")
                 ->setCellValue("CG".$i, "")
                 ;
	$i++;
	}

	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "등기관리팀리스트_신청서용엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
