<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");
	include ("../include/excel.inc");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	" tbl_junib j cross join tbl_suljung b on j.a1=b.a1 ";

	$h_idx				=	trim($_REQUEST[h_idx]);
	$target_date			=	trim($_REQUEST[target_date]);
	$s_date				=	trim($_REQUEST[s_date]);
	$e_date				=	trim($_REQUEST[e_date]);

	$target_date2			=	trim($_REQUEST[target_date2]);
	$s_date2			=	trim($_REQUEST[s_date2]);
	$e_date2			=	trim($_REQUEST[e_date2]);
	$bank_code			=	trim($_REQUEST[bank_code]);
	$jijum_code			=	trim($_REQUEST[jijum_code]);

	$cg_daesang			=	trim($_REQUEST[cg_daesang]);
	$kikan2_null_ch			=	trim($_REQUEST[kikan2_null_ch]);

	$h1				=	trim($_REQUEST[h1]);
	$i1				=	trim($_REQUEST[i1]);
	$j1				=	trim($_REQUEST[j1]);
	$memo				=	trim($_REQUEST[memo]);

	$list_num			=	trim($_REQUEST[list_num]);
	$page				=	trim($_REQUEST[page]);
	$view_num			=	trim($_REQUEST[list_num]);	//한라인에 몇개를 출력할건지//
	$Page_List			=	10;				//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num	=	20;				//리스트 갯수

	$wherequery = " where 1=1  ";

	if($h_idx!="")			$wherequery.= " and j.h_idx = ".$h_idx." ";
	if($bank_code!="")		$wherequery.= " and j.d1 = '".$bank_code."' ";
	if($jijum_code!="")		$wherequery.= " and j.e1 = '".$jijum_code."' ";
	if($h1!="")			$wherequery.= " and j.h1 = '".$h1."' ";
	if($i1!="")			$wherequery.= " and j.i1 = '".$i1."' ";
	if($j1!="")			$wherequery.= " and (j.j1 like '%{$j1}%' or j.m1 like '%{$j1}%')";

	//if($cg_daesang=="Y")	$wherequery.= " and b.cg_daesang='Y' ";
	
	
	if($target_date!=""){
		if(($s_date!="")&&($e_date!="")){
			$imsi = "";
			if(($target_date=="sjp_s_date")||($target_date=="sjp_j_date")||($target_date=="sjj_w_date")||($target_date=="sjp_b_date")){  //설정일때
				$imsi = " and j.a1 in (select a1 from tbl_suljung where {$target_date} between {$s_date} and {$e_date} )";
			}else if($target_date=="biyong_c_date"){
				$imsi = " and j.a1 in (select a1 from tbl_sugum where {$target_date} between {$s_date} and {$e_date} )";
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
				$imsi = " and j.a1 in (select a1 from tbl_suljung where  ({$target_date2}='' or {$target_date2} is null ))";
			}else{
				if($target_date2!="") {$imsi = " and ({$target_date2}='' or {$target_date2} is null )";}
			}
			$wherequery.=$imsi;
}else{
	if($target_date2!=""){
		if(($s_date2!="")&&($e_date2!="")){
			$imsi = "";
			if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")){ //설정일때
				$imsi = " and j.a1 in (select a1 from tbl_suljung where {$target_date2} between {$s_date2} and {$e_date2} )";
			}else if($target_date2=="biyong_c_date"){
				$imsi = " and j.a1 in (select a1 from tbl_sugum where {$target_date} between {$s_date2} and {$e_date2} )";
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
		$wherequery.= " and (j.a1 in ( (select a1 from tbl_junib where (memo like '%{$memo}%') or (ijp_s_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_s_memo  like '%{$memo}%') or (ijp_j_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_j_memo  like '%{$memo}%') or (ijj_w_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjj_w_memo  like '%{$memo}%') or (ijp_b_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_b_memo  like '%{$memo}%') or (refund_memo  like '%{$memo}%') ";
		$wherequery.= "      or (refund_end_memo like '%{$memo}%') or (refund_memo  like '%{$memo}%')))  or ";
		$wherequery.= "      j.a1 in (select a1 from tbl_sugum where sugum_memo like '%{$memo}%') )";
	}

if($ibgum_y=="y"){
		$wherequery.= " and b.idx in (select b.idx  from  tbl_suljung b left join tbl_sugum s on b.a1=s.a1 where b.suljung_no=s.suljung_no and (b.bosu_price + b.gongga_price)>(ifnull(s.ibgum_money1,0)+ifnull(s.ibgum_money2,0)) ) ";
}


	$objPHPExcel = new PHPExcel();

	$sql = "select j.idx as idxs from  tbl_junib j cross join tbl_suljung b on j.a1=b.a1   $wherequery  group by j.idx order by  cast(j.h1 as unsigned) asc,cast(j.i1 as unsigned) asc  ";
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
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
                 ->setCellValue("U".$i, "환불일")
                 ->setCellValue("V".$i, "처리자")
                 ->setCellValue("W".$i, "추가대상여부")
                 ->setCellValue("X".$i, "환불비고")
                 ->setCellValue("Y".$i, "비용안내")

                 ->setCellValue("Z".$i, "취득세납부")
                 ->setCellValue("AA".$i, "말소세금납부")
                 ->setCellValue("AB".$i, "인지갯수(수식)")
                 ->setCellValue("AC".$i, "전매/증여")
                 ->setCellValue("AD".$i, "공유여부")
                 ->setCellValue("AE".$i, "타입-1")

                 ->setCellValue("AF".$i, "이전채권번호1")
                 ->setCellValue("AG".$i, "이전채권번호2")
                 ->setCellValue("AH".$i, "설정채권번호1")
                 ->setCellValue("AU".$i, "설정채권번호2")
                 ->setCellValue("AJ".$i, "설정채권번호3")
                 ->setCellValue("AK".$i, "설정채권번호4")

                 ->setCellValue("AL".$i, "비고(외부)")
                 ->setCellValue("AM".$i, "정산비고")
                 ->setCellValue("AN".$i, "취득세과표")
                 ->setCellValue("AO".$i, "시가표준액")
                 ->setCellValue("AP".$i, "취득세입금일")
                 ->setCellValue("AQ".$i, "입금금액")
                 ->setCellValue("AR".$i, "이전채권본인부담합계")
                 ->setCellValue("AS".$i, "설정채권본인부담합계")
                 ->setCellValue("AT".$i, "취득세합계")

                 ->setCellValue("AU".$i, "인지세")
                 ->setCellValue("AV".$i, "증지세")

                 ->setCellValue("AW".$i, "신탁말소등록세")
                 ->setCellValue("AZ".$i, "제증명")
                 ->setCellValue("AY".$i, "소유권이전보수료")
                 ->setCellValue("AZ".$i, "소유권이전부가세")
                 ->setCellValue("BA".$i, "신탁말소등기보수료")
                 ->setCellValue("BB".$i, "신탁말소부가세")
                 ->setCellValue("BC".$i, "보수액합계")
                 ->setCellValue("BD".$i, "은행비고")

                 ->setCellValue("BE".$i, "채권최고액1")
                 ->setCellValue("BF".$i, "채무자1")
                 ->setCellValue("BG".$i, "채권최고액2")
                 ->setCellValue("BH".$i, "채권최고액3")
                 ->setCellValue("BI".$i, "채권최고액4")

                 ->setCellValue("BJ".$i, "설정채권매입액1(자동계산)")
                 ->setCellValue("BK".$i, "설정채권매입액2(자동계산)")
                 ->setCellValue("BL".$i, "설정채권매입액3(자동계산)")
                 ->setCellValue("BM".$i, "설정채권매입액4(자동계산)")

                 ->setCellValue("BN".$i, "등록면허세1")
                 ->setCellValue("BO".$i, "지방교육세1")

                 ->setCellValue("BP".$i, "등록면허세2")
                 ->setCellValue("BQ".$i, "지방교육세2")

                 ->setCellValue("BR".$i, "등록면허세3")
                 ->setCellValue("BS".$i, "지방교육세3")

                 ->setCellValue("BT".$i, "등록면허세4")
                 ->setCellValue("BU".$i, "지방교육세4");

	$i++;

	while($row = $stmt->fetch()){

		$sql = "select * from tbl_junib where idx = {$row[idxs]} limit 1";
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
                 ->setCellValue("R".$i, $ij[refund_money]) //"환불금")
                 ->setCellValue("S".$i, $ij[refund_bank]) //"환불은행")
                 ->setCellValue("T".$i, $ij[refund_account]) //"환불계좌번호")
                 ->setCellValue("U".$i, $ij[refund_date]) //"환불일")
                 ->setCellValue("V".$i, f_id_value($ij[refund_id])) //"처리자")
                 ->setCellValue("W".$i, $ij[chuga_ibgum_daesang]) //"추가입금대상여부")
                 ->setCellValue("X".$i, $ij[refund_memo]) //"환불비고")
                 ->setCellValue("Y".$i, $ij[q1]) // "비용안내")

                 ->setCellValue("Z".$i, $ij[r1]) // "취득세납부")
                 ->setCellValue("AA".$i, $ij[s1]) // "말소세납부")
                 ->setCellValue("AB".$i, $ij[t1]) // "인지갯수(수식)")
                 ->setCellValue("AC".$i, $ij[u1]) //"전매/증여")
                 ->setCellValue("AD".$i, $ij[v1]) // "공유여부")
                 ->setCellValue("AE".$i, $ij[w1]) // "타입-1")

                 ->setCellValue("AF".$i, $ij[x1]) // "이전채권번호1")
                 ->setCellValue("AG".$i, $ij[y1]) // "이전채권번호2")
                 ->setCellValue("AH".$i, $ij[z1]) // "설정채권번호1")
                 ->setCellValue("AU".$i, $ij[aa1]) // "설정채권번호2")
                 ->setCellValue("AJ".$i, $ij[ab1]) // "설정채권번호3")
                 ->setCellValue("AK".$i, $ij[ac1]) // "설정채권번호4")

                 ->setCellValue("AL".$i, $ij[ad1]) // "비고(외부)")
                 ->setCellValue("AM".$i, $ij[ae1]) // "정산비고")
                 ->setCellValue("AN".$i, $ij[af1]) // "취득세과표")
                 ->setCellValue("AO".$i, $ij[ag1]) // "시가표준액")
                 ->setCellValue("AP".$i, $ij[ah1]) // "취득세입금일")
                 ->setCellValue("AQ".$i, $ij[ai1]) // "입금금액")
                 ->setCellValue("AR".$i, $ij[aj1]) // "이전채권본인부담합계")
                 ->setCellValue("AS".$i, $ij[ak1]) // "설정채권본인부담합계")
                 ->setCellValue("AT".$i, $ij[al1]) // "취득세합계")

                 ->setCellValue("AU".$i, $ij[am1]) // "인지세")
                 ->setCellValue("AV".$i, $ij[an1]) // "증지세")

                 ->setCellValue("AW".$i, $ij[ao1]) // "신탁말소등록세")
                 ->setCellValue("AZ".$i, $ij[ap1]) // "제증명")
                 ->setCellValue("AY".$i, $ij[aq1]) // "소유권이전보수료")
                 ->setCellValue("AZ".$i, $ij[ar1]) // "소유권이전부가세")
                 ->setCellValue("BA".$i, $ij[as1]) // "신탁말소등기보수료")
                 ->setCellValue("BB".$i, $ij[at1]) // "신탁말소부가세")
                 ->setCellValue("BC".$i, ($ij[aq1]+$ij[ar1]+$ij[as1]+$ij[at1])) // "보수액합계")
                 ->setCellValue("BD".$i, $ij[au1]) // "은행비고")

                 ->setCellValue("BE".$i, $s1[chaekwon_max])
                 ->setCellValue("BF".$i, $ij[j1]) //"채무자1")
                 ->setCellValue("BG".$i, $s2[chaekwon_max])
                 ->setCellValue("BH".$i, $s3[chaekwon_max])
                 ->setCellValue("BI".$i, $s4[chaekwon_max])

                 ->setCellValue("BJ".$i, $s1[suljung_maeib]) // "설정채권매입액1(자동계산)")
                 ->setCellValue("BK".$i, $s2[suljung_maeib]) // "설정채권매입액2(자동계산)")
                 ->setCellValue("BL".$i, $s3[suljung_maeib]) // "설정채권매입액3(자동계산)")
                 ->setCellValue("BM".$i, $s4[suljung_maeib]) // "설정채권매입액4(자동계산)")

                 ->setCellValue("BN".$i, $s1[reg_lic]) // "등록면허세1")
                 ->setCellValue("BO".$i, $s1[local_edu]) // "지방교육세1")

                 ->setCellValue("BP".$i, $s2[reg_lic]) // "등록면허세2")
                 ->setCellValue("BQ".$i, $s2[local_edu]) // "지방교육세2")

                 ->setCellValue("BR".$i, $s3[reg_lic]) // "등록면허세3")
                 ->setCellValue("BS".$i, $s3[local_edu]) // "지방교육세3")

                 ->setCellValue("BT".$i, $s4[reg_lic]) // "등록면허세4")
                 ->setCellValue("BU".$i, $s4[local_edu]); // "지방교육세4");
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
