<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");
	include ("../include/excel.inc");
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	" tbl_junib j cross join (select s.idx, s.sugum_update, s.a1, t.suljung_no, t.ibgum_date1, t.ibgum_date2, s.gongga_price, s.bosu_price, s.bosu_price_vat, s.sjj_w_date, s.cg_daesang from tbl_suljung s, tbl_sugum t where s.a1 = t.a1 and s.suljung_no = t.suljung_no) b on j.a1=b.a1  ";

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

	$h1					=	trim($_REQUEST[h1]);
	$i1					=	trim($_REQUEST[i1]);
	$j1					=	trim($_REQUEST[j1]);
	$memo					=	trim($_REQUEST[memo]);

	$ibgum_y			=	trim($_REQUEST[ibgum_y]);

	if($target_date=="") $target_date="g1";
	if($s_date=="")		$s_date=date("Ymd");
	if($e_date=="")		$e_date=date("Ymd");

	$list_num			=	trim($_REQUEST[list_num]);
	$page				=	trim($_REQUEST[page]);
	$view_num			=	trim($_REQUEST[list_num]);	//한라인에 몇개를 출력할건지//
	$Page_List			=	10;				//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num	=	20;				//리스트 갯수

	$wherequery = " where 1=1  ";
  $imss = " ";
	if($h_idx!="")			$wherequery.= " and j.h_idx = ".$h_idx." ";
	if($bank_code!="")		$wherequery.= " and j.d1 = '".$bank_code."' ";
	if($jijum_code!="")		$wherequery.= " and j.e1 = '".$jijum_code."' ";
	if($h1!="")			$wherequery.= " and j.h1 = '".$h1."' ";
	if($i1!="")			$wherequery.= " and j.i1 = '".$i1."' ";
	if($j1!="")			$wherequery.= " and (j.j1 like '%{$j1}%' or j.m1 like '%{$j1}%')";

	//if($cg_daesang=="Y")	$wherequery.= " and b.cg_daesang='Y' ";
		
	if($target_date!=""){

		if($target_date=="100") {
			$imsi = " and b.sugum_update in (SELECT max(sugum_update) FROM tbl_suljung where sugum_update<>'' GROUP BY sugum_update ORDER BY sugum_update DESC)";
		}else if($target_date=="ibgum_date") {
			$imsia = " and ((b.ibgum_date1 between {$s_date} and {$e_date}) or (b.ibgum_date2 between {$s_date} and {$e_date}) )";
			$imss.= $imsia;
		}else if(($s_date!="")&&($e_date!="")){
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
		}
			$wherequery.=$imsi;
	}

if($kikan2_null_ch=="Y"){
			if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")){ //설정일때
				$imsi = " and j.a1 in (select a1 from tbl_suljung where  ({$target_date2}='' or {$target_date2} is null ))";
			}else if(($target_date2=="biyong_c_date")){ //수금일때
				$imsi = " and j.a1 in (select a1 from tbl_sugum where ({$target_date2}='' or {$target_date2} is null ))";
			}else if(($target_date2=="ibgum_date")){ //입금일일때
				$imsia = " and ((b.ibgum_date1 is null) or (b.ibgum_date2 is null) )";
			$imss.= $imsia;
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
				$imsi = " and j.a1 in (select a1 from tbl_sugum where {$target_date2} between {$s_date2} and {$e_date2} )";
			}else if($target_date2=="ibgum_date") {
				$imsia = " and ((b.ibgum_date1 between {$s_date2} and {$e_date2}) or (b.ibgum_date2 between {$s_date2} and {$e_date2}) )";
			$imss.= $imsia;
			//$wherequery.=$imsi;
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
		$wherequery.= " and b.idx in (select b.idx  from  tbl_suljung b left join tbl_sugum s on b.a1=s.a1 and b.suljung_no = s.suljung_no where b.suljung_no=s.suljung_no and (b.bosu_price + b.gongga_price + b.bosu_price_vat)>(ifnull(s.ibgum_money1,0)+ifnull(s.ibgum_money2,0)) ) ";
}


	$wherequery.= $imss;

	$objPHPExcel = new PHPExcel();

	$sql = "select j.h_idx,j.a1, b.suljung_no, b.jijum_code, b.cg_daesang, b.idx AS s_idx, j.idx, j.d1, j.e1, j.h1, j.i1, j.aw1, j.aw1_jumin, j.aw2, j.aw2_jumin, j.aw3, j.aw3_jumin, j.aw4, j.aw4_jumin, b.gukto, b.regi_lic, b.local_edu, b.suljung_bosu, b.gongga_price, b.bosu_price, b.bosu_price_vat, b.suljung_maeib, b.chaekwon_max, b.sjj_w_date, j.ijp_s_memo, b.ibgum_date1, b.ibgum_date2 from  tbl_junib j cross join (select s.idx, s.sugum_update, t.a1, t.suljung_no, t.ibgum_date1, t.ibgum_date2, s.gukto, s.regi_lic, s.local_edu, s.suljung_bosu, s.gongga_price, s.bosu_price, s.bosu_price_vat, s.suljung_maeib, s.chaekwon_max, s.jijum_code, s.sjj_w_date, s.cg_daesang from tbl_suljung s left join tbl_sugum t on s.a1 = t.a1 and s.suljung_no = t.suljung_no ) b on j.a1=b.a1  $wherequery  order by  cast(j.h1 as unsigned) asc,cast(j.i1 as unsigned) asc ";
	//echo $sql;
	$i_sql = $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(15);

//	$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(15);
//	$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(15);
//	$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(15);
//	$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(15);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "비용청구일")
                 ->setCellValue("C".$i, "고객고유번호")
                 ->setCellValue("D".$i, "은행")
                 ->setCellValue("E".$i, "지점")
                 ->setCellValue("F".$i, "카드/현금")
                 ->setCellValue("G".$i, "발행일/승인일")
                 ->setCellValue("H".$i, "국토교통부")
                 ->setCellValue("I".$i, "입금일")
                 ->setCellValue("J".$i, "입금액")
                 ->setCellValue("K".$i, "카드수수료")
                 ->setCellValue("L".$i, "실제입금액")
                 ->setCellValue("M".$i, "채권채고액")
                 ->setCellValue("N".$i, "채무자")
                 ->setCellValue("O".$i, "채무자주민번호")
                 ->setCellValue("P".$i, "설정채권매입액")
                 ->setCellValue("Q".$i, "등록면허세")
                 ->setCellValue("R".$i, "지방교육세")
                 ->setCellValue("S".$i, "등록세합계")
                 ->setCellValue("T".$i, "증지대")
                 ->setCellValue("U".$i, "등본발급대")
                 ->setCellValue("V".$i, "열람증지대(우리공)")
                 ->setCellValue("W".$i, "지배인초본발급(하나공)")
                 ->setCellValue("X".$i, "등록세신고납부대행")
                 ->setCellValue("Y".$i, "교통비")
                 ->setCellValue("Z".$i, "공과금합계")
                 ->setCellValue("AA".$i, "기본보수료")
                 ->setCellValue("AB".$i, "원인증서작성료")
                 ->setCellValue("AC".$i, "누진보수료")
                 ->setCellValue("AD".$i, "등록세대행")
                 ->setCellValue("AE".$i, "교통비")
                 ->setCellValue("AF".$i, "등본(제증명)")
                 ->setCellValue("AG".$i, "보수료소계")
                 ->setCellValue("AH".$i, "부가세")
                 ->setCellValue("AI".$i, "보수료합계")
                 ->setCellValue("AJ".$i, "은행비용합계")

//                 ->setCellValue("AK".$i, "1")
//                 ->setCellValue("AL".$i, "2")
//                 ->setCellValue("AM".$i, "3")
//                 ->setCellValue("AN".$i, "4")
                 ;
	$i++;

	while($row = $stmt->fetch()){


		$sql1 = "select * from tbl_sugum where a1='{$row[a1]}' and suljung_no='$row[suljung_no]'  ";
		//echo $sql1;
		$j_sql = $sql1;
		$row1 = db_query_value($sql1);

		//설정비용
			if($row[gukto]=="gukto"){
				$basic_gukto = "gukto";
			}else{
				$basic_gukto = "basic";
			}
		$sql = "select * from tbl_bank_jijum_rate where h_idx='{$row[h_idx]}' and jijum_code='{$row[jijum_code]}' and basic_gukto='{$basic_gukto}' ";
		//$sql = "select * from tbl_bank_jijum_rate where h_idx='{$row[h_idx]}' and jijum_code='{$row[jijum_code]}' and basic_gukto='{$basic_gukto}' limit 1";
		//echo $sql;
		$k_sql = $sql;
		$sj = db_query_fetch($sql);
		
		$imsi_daesang = "";  //하나라도 y이면 1회대상임
		if($sj[b_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($sj[b_kyotong_ch]=="y")$imsi_daesang = "y";
		if($sj[g_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($sj[g_kyotong_ch]=="y")$imsi_daesang = "y";

		$i_g_singonabbu = 0;
		$i_g_kyotong = 0;
		$i_b_singonabbu = 0;
		$i_b_kyotong = 0;

		if($imsi_daesang=="y"){  //1회 대상인 경우
			if($row[cg_daesang]=="Y"){  //1회청구라면 그사람만 나옴.
				if($sj[g_singonabbu_ch]=="y") $i_g_singonabbu = $sj[g_singonabbu];
				if($sj[g_kyotong_ch]=="y") $i_g_kyotong = $sj[g_kyotong];
				if($sj[b_singonabbu_ch]=="y") $i_b_singonabbu = $sj[b_singonabbu];
				if($sj[b_kyotong_ch]=="y") $i_b_kyotong = $sj[b_kyotong];
			}else{
				if($sj[g_singonabbu_ch]=="") {
					$i_g_singonabbu = $sj[g_singonabbu];
				} else if($sj[g_singonabbu_ch]==NULL) {
					$i_g_singonabbu = $sj[g_singonabbu];
				}
				if($sj[g_kyotong_ch]==""){
					$i_g_kyotong = $sj[g_kyotong];
				}else if($sj[g_kyotong_ch]==NULL){
					$i_g_kyotong = $sj[g_kyotong];
				}
				if($sj[b_singonabbu_ch]=="") {
					$i_b_singonabbu = $sj[b_singonabbu];
				} else if($sj[b_singonabbu_ch]==NULL) {
					$i_b_singonabbu = $sj[b_singonabbu];
				}
				if($sj[b_kyotong_ch]==""){
					$i_b_kyotong = $sj[b_kyotong];
				}else if($sj[b_kyotong_ch]==NULL){
					$i_b_kyotong = $sj[b_kyotong];
				}
				//$imsi = $imsi + $sj[b_kyotong];
			}
		}else{//1회 대상이 아닌경우
				$i_g_singonabbu = $sj[g_singonabbu];
				$i_g_kyotong = $sj[g_kyotong];
				$i_b_singonabbu = $sj[b_singonabbu];
				$i_b_kyotong = $sj[b_kyotong];
		}

		 $objPHPExcel->setActiveSheetIndex(0)
					 ->setCellValue("A".$i, $i-1)
					 ->setCellValue("B".$i, $row1[biyong_c_date])
					 ->setCellValue("C".$i, $row[a1])
					 ->setCellValue("D".$i, f_bank_name($row[d1]))
					 ->setCellValue("E".$i, f_jijum_name($row[e1]))
					 ->setCellValue("F".$i, $row1[card_gubun1])
					 ->setCellValue("G".$i, f_date($row1[ibgum_date1]))
					 ->setCellValue("H".$i, f_gukto($row[gukto]))
					 ->setCellValue("I".$i, $row1[ibgum_date1]."/".$row1[ibgum_date2])
					 ->setCellValue("J".$i, $row1[ibgum_money1]+$row1[ibgum_money2])
					 ->setCellValue("K".$i, "")
					 ->setCellValue("L".$i, "")
					 ->setCellValue("M".$i, $row[chaekwon_max])
					 ->setCellValue("N".$i, $row["aw".$row[suljung_no]])
					 ->setCellValue("O".$i, f_jumin_valid($row["aw".$row[suljung_no]."_jumin"]))
					 ->setCellValue("P".$i, $row[suljung_maeib])
					 ->setCellValue("Q".$i, $row[regi_lic])
					 ->setCellValue("R".$i, $row[local_edu])
					 ->setCellValue("S".$i, $row[regi_lic]+$row[local_edu])
					 ->setCellValue("T".$i, $sj[g_jungjidae])
					 ->setCellValue("U".$i, $sj[g_deungchobon])  
					 ->setCellValue("V".$i, $sj[g_yeolamjunggi])
					 ->setCellValue("W".$i, $sj[g_jibaeinchobon])
					 ->setCellValue("X".$i, $i_g_singonabbu)
					 ->setCellValue("Y".$i, $i_g_kyotong)
					 ->setCellValue("Z".$i, $row[gongga_price])
					 ->setCellValue("AA".$i, $sj[b_basic_bosu])
					 ->setCellValue("AB".$i, $sj[b_woninjungseo])
					 ->setCellValue("AC".$i,$row[suljung_bosu])
					 ->setCellValue("AD".$i,$i_b_singonabbu)
					 ->setCellValue("AE".$i,$i_b_kyotong)
					 ->setCellValue("AF".$i,$sj[g_jejungmyung])
					 ->setCellValue("AG".$i,$row[bosu_price])
					 ->setCellValue("AH".$i,$row[bosu_price_vat])
					 ->setCellValue("AI".$i,$row[bosu_price]+$row[bosu_price_vat])
					 ->setCellValue("AJ".$i,$row[bosu_price]+$row[bosu_price_vat]+$row[gongga_price])
					 
//					 ->setCellValue("AK".$i,$imsi_daesang)
//					 ->setCellValue("AL".$i,$i_sql)
//					 ->setCellValue("AM".$i,$j_sql)
//					 ->setCellValue("AN".$i,$k_sql)
		 ;
		$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "수금_상세엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
