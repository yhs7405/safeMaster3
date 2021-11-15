<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");
	include ("../include/excel.inc");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";

	$h_idx				=	trim($_REQUEST[h_idx]);
	$target_date			=	trim($_REQUEST[target_date]);
	$s_date				=	trim($_REQUEST[s_date]);
	$e_date				=	trim($_REQUEST[e_date]);

	$target_date2			=	trim($_REQUEST[target_date2]);
	$s_date2			=	trim($_REQUEST[s_date2]);
	$e_date2			=	trim($_REQUEST[e_date2]);
	$bank_code			=	trim($_REQUEST[bank_code]);
	$jijum_code			=	trim($_REQUEST[jijum_code]);
	$bank_null_ch	=	trim($_REQUEST[bank_null_ch]);

	$h1				=	trim($_REQUEST[h1]);
	$i1				=	trim($_REQUEST[i1]);
	$j1				=	trim($_REQUEST[j1]);
	$memo				=	trim($_REQUEST[memo]);
	$refund_y			=	trim($_REQUEST[refund_y]);
	$kikan2_null_ch			=	trim($_REQUEST[kikan2_null_ch]);

	if($target_date=="") $target_date="g1";
	if($s_date=="")		$s_date=date("Ymd");
	if($e_date=="")		$e_date=date("Ymd");

	$list_num			=	trim($_REQUEST[list_num]);
	$page				=	trim($_REQUEST[page]);
	$view_num			=	trim($_REQUEST[list_num]);	//한라인에 몇개를 출력할건지//
	$Page_List			=	10;				//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num	=	20;				//리스트 갯수

	$wherequery = " where 1=1  ";

	if($h_idx!="")			$wherequery.= " and h_idx = ".$h_idx." ";
	if($bank_null_ch=="Y")	$wherequery.= " and d1 <> '' ";
	if($bank_code!="")		$wherequery.= " and d1 = '".$bank_code."' ";
	if($jijum_code!="")		$wherequery.= " and e1 = '".$jijum_code."' ";
	if($h1!="")			$wherequery.= " and h1 = '".$h1."' ";
	if($i1!="")			$wherequery.= " and i1 = '".$i1."' ";
	if($j1!="")			$wherequery.= " and (j1 like '%{$j1}%' or m1 like '%{$j1}%')";
	
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

	if($refund_y=="1"){ //환불대상자
			$wherequery.= " and refund_fee>0 ";
	}
	if($refund_y=="2"){ //환불대상자(계좌미입력)
			$wherequery.= " and refund_account is null ";
	}
	if($refund_y=="3"){ //환불없슴
			$wherequery.= " and refund_fee=0 ";
	}
	if($refund_y=="4"){  //추가입금대상자
			$wherequery.= " and refund_fee<0 ";
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

	$sql = "select * from $board_dbname  $wherequery order by  cast(h1 as unsigned) asc,cast(i1 as unsigned) asc";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);

	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AT')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AU')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AV')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AW')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AX')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AY')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AZ')->setWidth(15);

	$objPHPExcel->getActiveSheet()->getColumnDimension('BA')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BB')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BC')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BD')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BE')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BF')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BG')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BH')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BI')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BJ')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BK')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BL')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BM')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BN')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BO')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BP')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BQ')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BR')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BS')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BT')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BU')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BV')->setWidth(15);

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "고객고유번호")
                 ->setCellValue("B".$i, "단지명")
                 ->setCellValue("C".$i, "회원여부")
                 ->setCellValue("D".$i, "은행명")
                 ->setCellValue("E".$i, "지점명")
                 ->setCellValue("F".$i, "설정갯수")
                 ->setCellValue("G".$i, "등기접수일")
                 ->setCellValue("H".$i, "동")
                 ->setCellValue("I".$i, "호")
                 ->setCellValue("J".$i, "취득자1")
                 ->setCellValue("K".$i, "주민번호1")
                 ->setCellValue("L".$i, "주소1")
                 ->setCellValue("M".$i, "취득자2")
                 ->setCellValue("N".$i, "주민번호2")
                 ->setCellValue("O".$i, "주소2")
                 ->setCellValue("P".$i, "전화번호")

                 ->setCellValue("Q".$i, "배포일")
                 ->setCellValue("R".$i, "환불금")
                 ->setCellValue("S".$i, "환불은행")
                 ->setCellValue("T".$i, "환불계좌번호")
                 ->setCellValue("U".$i, "환불금액")
                 ->setCellValue("V".$i, "환불일")
                 ->setCellValue("W".$i, "처리자")
                 ->setCellValue("X".$i, "추가대상여부")
                 ->setCellValue("Y".$i, "환불비고")
                 ->setCellValue("Z".$i, "비용안내")

                 ->setCellValue("AA".$i, "취득세납부")
                 ->setCellValue("AB".$i, "말소세금납부")
                 ->setCellValue("AC".$i, "인지갯수(수식)")
                 ->setCellValue("AD".$i, "전매/증여")
                 ->setCellValue("AE".$i, "공유여부")
                 ->setCellValue("AF".$i, "타입-1")
                 ->setCellValue("AG".$i, "이전채권번호1")
                 ->setCellValue("AH".$i, "이전채권번호2")
                 ->setCellValue("AI".$i, "설정채권번호1")
                 ->setCellValue("AJ".$i, "설정채권번호2")
                 ->setCellValue("AK".$i, "설정채권번호3")
                 ->setCellValue("AL".$i, "설정채권번호4")
                 ->setCellValue("AM".$i, "비고(외부)")
                 ->setCellValue("AN".$i, "정산비고")
                 ->setCellValue("AO".$i, "취득세과표")
                 ->setCellValue("AP".$i, "시가표준액")
                 ->setCellValue("AQ".$i, "취득세입금일")
                 ->setCellValue("AR".$i, "입금금액")
                 ->setCellValue("AS".$i, "이전채권본인부담합계")
                 ->setCellValue("AT".$i, "설정채권본인부담합계")
                 ->setCellValue("AU".$i, "취득세합계")
                 ->setCellValue("AV".$i, "인지세")
                 ->setCellValue("AW".$i, "증지세")
                 ->setCellValue("AX".$i, "신탁말소등록세")
                 ->setCellValue("AY".$i, "제증명")
                 ->setCellValue("AZ".$i, "소유권이전보수료")
                 ->setCellValue("BA".$i, "소유권이전부가세")
                 ->setCellValue("BB".$i, "신탁말소등기보수료")
                 ->setCellValue("BC".$i, "신탁말소부가세")
                 ->setCellValue("BD".$i, "보수액합계")
                 ->setCellValue("BE".$i, "은행비고")
                 ->setCellValue("BF".$i, "채권최고액1")
                 ->setCellValue("BG".$i, "채무자1")
                 ->setCellValue("BH".$i, "채권최고액2")
                 ->setCellValue("BI".$i, "채권최고액3")
                 ->setCellValue("BJ".$i, "채권최고액4")
                 ->setCellValue("BK".$i, "설정채권매입액1(자동계산)")
                 ->setCellValue("BL".$i, "설정채권매입액2(자동계산)")
                 ->setCellValue("BM".$i, "설정채권매입액3(자동계산)")
                 ->setCellValue("BN".$i, "설정채권매입액4(자동계산)")
                 ->setCellValue("BO".$i, "등록면허세1")
                 ->setCellValue("BP".$i, "지방교육세1")
                 ->setCellValue("BQ".$i, "등록면허세2")
                 ->setCellValue("BR".$i, "지방교육세2")
                 ->setCellValue("BS".$i, "등록면허세3")
                 ->setCellValue("BT".$i, "지방교육세3")
                 ->setCellValue("BU".$i, "등록면허세4")
                 ->setCellValue("BV".$i, "지방교육세4");

	$i++;

	while($row = $stmt->fetch()){

		$sql = "select * from tbl_junib where idx = {$row[idx]} limit 1";
		//echo $sql;
		$ij = db_query_fetch($sql);

		$sql = "select * from tbl_suljung where a1 = '{$ij[a1]}' and suljung_no=1 limit 1";
		//echo $sql;
		$s1 = db_query_fetch($sql);

		$sql = "select * from tbl_suljung where a1 = '{$ij[a1]}' and suljung_no=2 limit 1";
		//echo $sql;
		$s2 = db_query_fetch($sql);

		$sql = "select * from tbl_suljung where a1 = '{$ij[a1]}' and suljung_no=3 limit 1";
		//echo $sql;
		$s3 = db_query_fetch($sql);

		$sql = "select * from tbl_suljung where a1 = '{$ij[a1]}' and suljung_no=4 limit 1";
		//echo $sql;
		$s4 = db_query_fetch($sql);


		 $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $ij[a1]) //고객고유번호
                 ->setCellValue("B".$i, $ij[b1]) //"단지명")
                 ->setCellValue("C".$i, $ij[c1]) //"회원여부")
                 ->setCellValue("D".$i, f_bank_name($ij[d1])) //"은행명")
                 ->setCellValue("E".$i, f_jijum_name($ij[e1])) //"지점명")
                 ->setCellValue("F".$i, $ij[f1]) //"설정갯수")
                 ->setCellValue("G".$i, $ij[g1]) //"등기접수일")
                 ->setCellValue("H".$i, $ij[h1]) //"동")
                 ->setCellValue("I".$i, $ij[i1]) //"호")
                 ->setCellValue("J".$i, $ij[j1]) //"취득자1")
                 ->setCellValue("K".$i, $ij[k1]) //"주민번호1")
                 ->setCellValue("L".$i, $ij[l1]) //"주소1")
                 ->setCellValue("M".$i, $ij[m1]) //"취득자2")
                 ->setCellValue("N".$i, $ij[n1]) //"주민번호2")
                 ->setCellValue("O".$i, $ij[o1]) //"주소2")
                 ->setCellValue("P".$i, $ij[p1]) //"전화번호")

                 ->setCellValue("Q".$i, $ij[ijp_b_date]) //"이전필증배포일")
                 ->setCellValue("R".$i, $ij[refund_fee]) //"환불금")refund_fee
                 ->setCellValue("S".$i, $ij[refund_bank]) //"환불은행")
                 ->setCellValue("T".$i, $ij[refund_account]) //"환불계좌번호")
                 ->setCellValue("U".$i, $ij[refund_money]) //"환불금액")
                 ->setCellValue("V".$i, $ij[refund_date]) //"환불일")
                 ->setCellValue("W".$i, f_id_value($ij[refund_id])) //"처리자")
                 ->setCellValue("X".$i, $ij[chuga_ibgum_daesang]) //"추가입금대상여부")
                 ->setCellValue("Y".$i, $ij[refund_memo]) //"환불비고")
                 ->setCellValue("Z".$i, $ij[q1]) // "비용안내")

                 ->setCellValue("AA".$i, $ij[r1]) // "취득세납부")
                 ->setCellValue("AB".$i, $ij[s1]) // "말소세납부")
                 ->setCellValue("AC".$i, $ij[t1]) // "인지갯수(수식)")
                 ->setCellValue("AD".$i, $ij[u1]) //"전매/증여")
                 ->setCellValue("AE".$i, $ij[v1]) // "공유여부")
                 ->setCellValue("AF".$i, $ij[w1]) // "타입-1")
                 ->setCellValue("AG".$i, $ij[x1]) // "이전채권번호1")
                 ->setCellValue("AH".$i, $ij[y1]) // "이전채권번호2")
                 ->setCellValue("AI".$i, $ij[z1]) // "설정채권번호1")
                 ->setCellValue("AJ".$i, $ij[aa1]) // "설정채권번호2")
                 ->setCellValue("AK".$i, $ij[ab1]) // "설정채권번호3")
                 ->setCellValue("AL".$i, $ij[ac1]) // "설정채권번호4")
                 ->setCellValue("AM".$i, $ij[ad1]) // "비고(외부)")
                 ->setCellValue("AN".$i, $ij[ae1]) // "정산비고")
                 ->setCellValue("AO".$i, $ij[af1]) // "취득세과표")
                 ->setCellValue("AP".$i, $ij[ag1]) // "시가표준액")
                 ->setCellValue("AQ".$i, $ij[ah1]) // "취득세입금일")
                 ->setCellValue("AR".$i, $ij[ai1]) // "입금금액")
                 ->setCellValue("AS".$i, $ij[aj1]) // "이전채권본인부담합계")
                 ->setCellValue("AT".$i, $ij[ak1]) // "설정채권본인부담합계")
                 ->setCellValue("AU".$i, $ij[al1]) // "취득세합계")
                 ->setCellValue("AV".$i, $ij[am1]) // "인지세")
                 ->setCellValue("AW".$i, $ij[an1]) // "증지세")
                 ->setCellValue("AX".$i, $ij[ao1]) // "신탁말소등록세")
                 ->setCellValue("AY".$i, $ij[ap1]) // "제증명")
                 ->setCellValue("AZ".$i, $ij[aq1]) // "소유권이전보수료")
                 ->setCellValue("BA".$i, $ij[ar1]) // "소유권이전부가세")
                 ->setCellValue("BB".$i, $ij[as1]) // "신탁말소등기보수료")
                 ->setCellValue("BC".$i, $ij[at1]) // "신탁말소부가세")
                 ->setCellValue("BD".$i, ($ij[aq1]+$ij[ar1]+$ij[as1]+$ij[at1])) // "보수액합계")
                 ->setCellValue("BE".$i, $ij[au1]) // "은행비고")
                 ->setCellValue("BF".$i, $s1[chaekwon_max])
                 ->setCellValue("BG".$i, $ij[j1]) //"채무자1")
                 ->setCellValue("BH".$i, $s2[chaekwon_max])
                 ->setCellValue("BI".$i, $s3[chaekwon_max])
                 ->setCellValue("BJ".$i, $s4[chaekwon_max])
                 ->setCellValue("BK".$i, $s1[suljung_maeib]) // "설정채권매입액1(자동계산)")
                 ->setCellValue("BL".$i, $s2[suljung_maeib]) // "설정채권매입액2(자동계산)")
                 ->setCellValue("BM".$i, $s3[suljung_maeib]) // "설정채권매입액3(자동계산)")
                 ->setCellValue("BN".$i, $s4[suljung_maeib]) // "설정채권매입액4(자동계산)")
                 ->setCellValue("BO".$i, $s1[reg_lic]) // "등록면허세1")
                 ->setCellValue("BP".$i, $s1[local_edu]) // "지방교육세1")
                 ->setCellValue("BQ".$i, $s2[reg_lic]) // "등록면허세2")
                 ->setCellValue("BR".$i, $s2[local_edu]) // "지방교육세2")
                 ->setCellValue("BS".$i, $s3[reg_lic]) // "등록면허세3")
                 ->setCellValue("BT".$i, $s3[local_edu]) // "지방교육세3")
                 ->setCellValue("BU".$i, $s4[reg_lic]) // "등록면허세4")
                 ->setCellValue("BV".$i, $s4[local_edu]); // "지방교육세4");
		$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "환불금_상세엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
