<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");
	include ("../include/excel.inc");
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	" tbl_junib j left join tbl_suljung b on j.a1=b.a1 ";

	$h_idx				=	trim($_REQUEST[h_idx]);


	$objPHPExcel = new PHPExcel();

	$sql = "select j.a1 as j_a1, j.*,b.*, b.idx as s_idx from  tbl_junib j left join tbl_suljung b on j.a1=b.a1 where j.h_idx={$h_idx} order by  cast(j.h1 as unsigned) asc,cast(j.i1 as unsigned) asc ,cast(b.suljung_no as unsigned) asc ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;



	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
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
	//$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);

	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setWidth(20);
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('BD')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BE')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BF')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BG')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BH')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BI')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BJ')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BK')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BL')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BM')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BN')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BO')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BP')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BQ')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BR')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BS')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BT')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BU')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BV')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BW')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BX')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BY')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('BZ')->setWidth(15);

	$objPHPExcel->getActiveSheet()->getColumnDimension('CA')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CB')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CC')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CD')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CE')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CF')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CG')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CH')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CI')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CJ')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CK')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CL')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CM')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CN')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CO')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CP')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CQ')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CR')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CS')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CT')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CU')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CV')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CW')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CX')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CY')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('CZ')->setWidth(15);

	$objPHPExcel->getActiveSheet()->getColumnDimension('DA')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('DB')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('DC')->setWidth(30);

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "??????????????????")
                 ->setCellValue("C".$i, "?????????")
                 ->setCellValue("D".$i, "????????????")
                 ->setCellValue("E".$i, "???????????????")
                 ->setCellValue("F".$i, "??????/??????")
                 ->setCellValue("G".$i, "????????????")
                 ->setCellValue("H".$i, "??????")
                 ->setCellValue("I".$i, "???")
                 ->setCellValue("J".$i, "???")
                 ->setCellValue("K".$i, "????????????")
                 ->setCellValue("L".$i, "?????????1??????")
                 ->setCellValue("M".$i, "?????????1????????????")
                 ->setCellValue("N".$i, "?????????1??????")
                 ->setCellValue("O".$i, "?????????2??????")
                 ->setCellValue("P".$i, "?????????2????????????")
                 ->setCellValue("Q".$i, "?????????2??????")
                 ->setCellValue("R".$i, "?????????")
                 ->setCellValue("S".$i, "????????????")
                 ->setCellValue("T".$i, "CS??????")
                 ->setCellValue("U".$i, "???????????????")
                 ->setCellValue("V".$i, "???????????????")
                 ->setCellValue("W".$i, "??????????????????(???????????????)")
                 ->setCellValue("X".$i, "????????????")
                 ->setCellValue("Y".$i, "???????????????")

//                 ->setCellValue("Z".$i, "???????????????2")
//                 ->setCellValue("AA".$i, "???????????????3")
//                 ->setCellValue("AB".$i, "???????????????4")

                 ->setCellValue("Z".$i, "???????????????")
                 ->setCellValue("AA".$i, "???????????????")
                 ->setCellValue("AB".$i, "?????????????????????")
                 ->setCellValue("AC".$i, "????????????")
                 ->setCellValue("AD".$i, "?????????")
                 ->setCellValue("AE".$i, "?????????")
                 ->setCellValue("AF".$i, "?????????")
                 ->setCellValue("AG".$i, "??????????????????1")
                 ->setCellValue("AH".$i, "??????????????????2")
                 ->setCellValue("AI".$i, "?????????????????????????????????")
                 ->setCellValue("AJ".$i, "??????????????????")

//                 ->setCellValue("AN".$i, "??????????????????2")
//                 ->setCellValue("AO".$i, "??????????????????3")
//                 ->setCellValue("AP".$i, "??????????????????4")

                 ->setCellValue("AK".$i, "?????????????????????????????????")
                 ->setCellValue("AL".$i, "????????????????????????")
                 ->setCellValue("AM".$i, "????????????????????????")
                 ->setCellValue("AN".$i, "???????????????????????????")
                 ->setCellValue("AO".$i, "???????????????????????????")
                 ->setCellValue("AP".$i, "???????????????")
                 ->setCellValue("AQ".$i, "???????????????")
                 ->setCellValue("AR".$i, "????????????-?????????+?????????")
                 ->setCellValue("AS".$i, "?????????-??????-????????????")
                 ->setCellValue("AT".$i, "1?????????????????????")
                 ->setCellValue("AU".$i, "?????????")
                 ->setCellValue("AV".$i, "????????????????????????")
                 ->setCellValue("AW".$i, "???????????????")
                 ->setCellValue("AX".$i, "???????????????")
                 ->setCellValue("AY".$i, "???????????????")
                 ->setCellValue("AZ".$i, "?????????_??????_id")
                 ->setCellValue("BA".$i, "?????????????????????")
                 ->setCellValue("BB".$i, "??????????????????")
                 ->setCellValue("BC".$i, "??????????????????")
                 ->setCellValue("BD".$i, "?????????????????????")
                 ->setCellValue("BE".$i, "???????????????")
                 ->setCellValue("BF".$i, "??????")
                 ->setCellValue("BG".$i, "??????????????????")
                 ->setCellValue("BH".$i, "?????????????????????")
                 ->setCellValue("BI".$i, "?????????????????????")
                 ->setCellValue("BJ".$i, "?????????????????????")
                 ->setCellValue("BK".$i, "?????????????????????")
                 ->setCellValue("BL".$i, "????????????????????????")
                 ->setCellValue("BM".$i, "????????????????????????")
//                 ->setCellValue("BN".$i, "????????????????????????")
                 ->setCellValue("BN".$i, "????????????")
                 ->setCellValue("BO".$i, "??????????????????")
                 ->setCellValue("BP".$i, "?????????")
                 ->setCellValue("BQ".$i, "?????????-?????????")
                 ->setCellValue("BR".$i, "????????????")
                 ->setCellValue("BS".$i, "???????????????")
                 ->setCellValue("BT".$i, "???????????????")
                 ->setCellValue("BU".$i, "???????????????")
                 ->setCellValue("BV".$i, "??????")
                 ->setCellValue("BW".$i, "??????")
                 ->setCellValue("BX".$i, "??????/??????")
                 ->setCellValue("BY".$i, "?????????/?????????")
                 ->setCellValue("BZ".$i, "???????????????")
                 ->setCellValue("CA".$i, "?????????")
                 ->setCellValue("CB".$i, "?????????")
                 ->setCellValue("CD".$i, "???????????????")
                 ->setCellValue("CD".$i, "???????????????")
                 ->setCellValue("CE".$i, "???????????????")
                 ->setCellValue("CF".$i, "?????????")
                 ->setCellValue("CG".$i, "?????????????????????")
                 ->setCellValue("CH".$i, "?????????????????????")
                 ->setCellValue("CI".$i, "???????????????")
                 ->setCellValue("CJ".$i, "???????????????")
                 ->setCellValue("CK".$i, "???????????????")
                 ->setCellValue("CL".$i, "?????????")
                 ->setCellValue("CM".$i, "???????????????")
                 ->setCellValue("CN".$i, "???????????????(?????????)")
                 ->setCellValue("CO".$i, "?????????????????????(?????????)")
                 ->setCellValue("CP".$i, "???????????????????????????")
                 ->setCellValue("CQ".$i, "?????????")
                 ->setCellValue("CR".$i, "???????????????")
                 ->setCellValue("CS".$i, "???????????????")
                 ->setCellValue("CT".$i, "?????????????????????")
                 ->setCellValue("CU".$i, "???????????????")
                 ->setCellValue("CV".$i, "???????????????")
                 ->setCellValue("CW".$i, "?????????")
                 ->setCellValue("CX".$i, "??????(?????????)")
                 ->setCellValue("CY".$i, "???????????????")
                 ->setCellValue("CZ".$i, "?????????")
                 ->setCellValue("DA".$i, "???????????????")
                 ->setCellValue("DB".$i, "??????????????????")
                 ->setCellValue("DC".$i, "????????????")
;

	$i++;

	while($row = $stmt->fetch()){


		$sql1 = "select * from tbl_sugum where a1='{$row[j_a1]}' and suljung_no='$row[suljung_no]'  limit 1 ";
		//echo $sql1;
		$row1 = db_query_value($sql1);

		//????????????
			if($row[gukto]=="gukto"){
				$basic_gukto = "gukto";
			}else{
				$basic_gukto = "basic";
			}
		$sql = "select * from tbl_bank_jijum_rate where jijum_code='{$row[jijum_code]}' and basic_gukto='{$basic_gukto}' limit 1";
		//echo $sql;
		$sj = db_query_fetch($sql);

		$imsi_daesang = "";  //???????????? y?????? 1????????????
		if($sj[b_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($sj[b_kyotong_ch]=="y")$imsi_daesang = "y";
		if($sj[g_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($sj[g_kyotong_ch]=="y")$imsi_daesang = "y";

		$i_g_singonabbu = 0;
		$i_g_kyotong = 0;
		$i_b_singonabbu = 0;
		$i_b_kyotong = 0;

		if($imsi_daesang=="y"){  //1??? ????????? ??????
			if($row[cg_daesang]=="Y"){  //1??????????????? ???????????? ??????.
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
		}else{//1??? ????????? ????????????
				$i_g_singonabbu = $sj[g_singonabbu];
				$i_g_kyotong = $sj[g_kyotong];
				$i_b_singonabbu = $sj[b_singonabbu];
				$i_b_kyotong = $sj[b_kyotong];
		}

		 $objPHPExcel->setActiveSheetIndex(0)
					 ->setCellValue("A".$i, $i-1)
					 ->setCellValue("B".$i, $row[j_a1])  // ??????????????????
					 ->setCellValue("C".$i, $row[b1])  // ?????????
					 ->setCellValue("D".$i, $row[c1])  // ????????????
					 ->setCellValue("E".$i, $row[g1])  // ???????????????
					 ->setCellValue("F".$i, $row[u1])  // ??????/??????
					 ->setCellValue("G".$i, $row[v1])  // ????????????
					 ->setCellValue("H".$i, $row[w1])  // ??????
					 ->setCellValue("I".$i, $row[h1])  // ???
					 ->setCellValue("J".$i, $row[i1])  // ???
					 ->setCellValue("K".$i, $row[ae1]) // ????????????

					 ->setCellValue("L".$i, $row[j1])  // ?????????1??????
					 ->setCellValue("M".$i, $row[k1])  // ?????????1????????????
					 ->setCellValue("N".$i, $row[l1])  // ?????????1??????
					 ->setCellValue("O".$i, $row[m1])  // ?????????2??????
					 ->setCellValue("P".$i, $row[n1])  // ?????????2????????????
					 ->setCellValue("Q".$i, $row[o1])  // ?????????2??????
					 ->setCellValue("R".$i, $row[s1])  // ?????????
					 ->setCellValue("S".$i, $row[p1])  // ????????????
					 ->setCellValue("T".$i, $row[ad1]) // CS??????

					 ->setCellValue("U".$i,  $row[af1])  // ???????????????
					 ->setCellValue("V".$i,  $row[ag1])  // ???????????????
					 ->setCellValue("W".$i,  $row[ah1])  // ??????????????????(???????????????)
					 ->setCellValue("X".$i,  $row[ai1])  // ????????????
					 ->setCellValue("Y".$i,  $row[chaekwon_max])  // ???????????????
//					 ->setCellValue("Y".$i,  $row[ax1])  // ???????????????2
//					 ->setCellValue("AA".$i, $row[ay1])  // ???????????????3
//					 ->setCellValue("AB".$i, $row[az1])  // ???????????????4

					 ->setCellValue("Z".$i, $row[r1])   // ???????????????
					 ->setCellValue("AA".$i, $row[al1])  // ???????????????
					 ->setCellValue("AB".$i, $row[ao1])  // ?????????????????????
					 ->setCellValue("AC".$i, $row[t1])   // ????????????
					 ->setCellValue("AD".$i, $row[am1])  // ?????????
					 ->setCellValue("AE".$i, $row[an1])  // ?????????
					 ->setCellValue("AF".$i, $row[ap1])  // ?????????

					 ->setCellValue("AG".$i, $row[x1])   // ??????????????????1
					 ->setCellValue("AH".$i, $row[y1 ])  // ??????????????????2
					 ->setCellValue("AI".$i, $row[aj1])  // ?????????????????????????????????
					 ->setCellValue("AJ".$i, $row[chaekwon_no])   // ??????????????????
//					 ->setCellValue("AN".$i, $row[aa1])  // ??????????????????2
//					 ->setCellValue("AO".$i, $row[ab1])  // ??????????????????3
//					 ->setCellValue("AP".$i, $row[ac1])  // ??????????????????4
					 ->setCellValue("AK".$i, $row[ak1])  // ?????????????????????????????????

					 ->setCellValue("AL".$i, $row[aq1])  // ????????????????????????
					 ->setCellValue("AM".$i, $row[ar1])  // ????????????????????????
					 ->setCellValue("AN".$i, $row[as1])  // ???????????????????????????
					 ->setCellValue("AO".$i, $row[at1])  // ???????????????????????????
					 ->setCellValue("AP".$i, $row[aq1]+$row[ar1]+$row[as1]+$row[at1]) // ???????????????
					 ->setCellValue("AQ".$i, $row[al1]+$row[ao1]+$row[am1]+$row[an1]+$row[ap1]+$row[aj1]+$row[ak1])  // ???????????????
					 ->setCellValue("AR".$i, $row[aq1]+$row[ar1]+$row[as1]+$row[at1]+$row[al1]+$row[ao1]+$row[am1]+$row[an1]+$row[ap1]+$row[aj1]+$row[ak1])  // ????????????(?????????+?????????)
					 ->setCellValue("AS".$i, $row[ai1]-($row[aq1]+$row[ar1]+$row[as1]+$row[at1]+$row[al1]+$row[ao1]+$row[am1]+$row[an1]+$row[ap1]+$row[aj1]+$row[ak1]))  // ?????????(??????-????????????)

					 ->setCellValue("AT".$i, $row[cg_daesang])  // 1?????????????????????
					 ->setCellValue("AU".$i, $row[refund_fee])  // ?????????
					 ->setCellValue("AV".$i, $row[chuga_ibgum_daesang])  // ????????????????????????
					 ->setCellValue("AW".$i, $row[total_pay])  // ???????????????
					 ->setCellValue("AX".$i, $row[junib_request_date])  // ???????????????
					 ->setCellValue("AY".$i, $row[junib_s_date])  // ???????????????
					 ->setCellValue("AZ".$i, $row[damdang_id])  // ?????????(??????)id
					 ->setCellValue("BA".$i, f_sou1_value($row[sou_relation]))  // ?????????????????????
					 ->setCellValue("BB".$i, $row[review_request_date])  // ??????????????????
					 ->setCellValue("BC".$i, $row[review_s_date])  // ??????????????????
					 ->setCellValue("BD".$i, $row[review_confirm])  // ?????????????????????
					 ->setCellValue("BE".$i, $row[sms_date])  // ???????????????
					 ->setCellValue("BF".$i, $row[memo])  // ??????

					 ->setCellValue("BG".$i, $row[ijp_s_memo])  // ??????????????????
					 ->setCellValue("BH".$i, $row[ijp_s_date])  // ?????????????????????
					 ->setCellValue("BI".$i, $row[ijp_j_date])  // ?????????????????????
					 ->setCellValue("BJ".$i, $row[ijj_w_date])  // ?????????????????????
					 ->setCellValue("BK".$i, $row[ijp_b_date])  // ?????????????????????
					 ->setCellValue("BL".$i, $row[ijy_c_date])  // ????????????????????????
					 ->setCellValue("BM".$i, $row[hy_b_date])  // ????????????????????????
//					 ->setCellValue("BN".$i, $row[hy_d_date])   // ????????????????????????

					 ->setCellValue("BN".$i, $row[refund_bank])  // ????????????
					 ->setCellValue("BO".$i, $row[refund_account])  // ??????????????????
					 ->setCellValue("BP".$i, $row[refund_name])  // ?????????
					 ->setCellValue("BQ".$i, $row[refund_money])  // ?????????(?????????)
					 ->setCellValue("BR".$i, $row[refund_memo])  // ????????????
					 ->setCellValue("BS".$i, $row[refund_date])  // ???????????????
					 ->setCellValue("BT".$i, $row[refund_id])  // ???????????????

					 ->setCellValue("BU".$i, $row1[biyong_c_date])
					 ->setCellValue("BV".$i, f_bank_name($row[d1]))
					 ->setCellValue("BW".$i, f_jijum_name($row[e1]))
					 ->setCellValue("BX".$i, $row1[card_gubun1])
					 ->setCellValue("BY".$i, f_date($row1[ibgum_date1]))
					 ->setCellValue("BZ".$i, f_gukto($row[gukto]))
					 ->setCellValue("CA".$i, $row1[ibgum_date1]."/".$row1[ibgum_date2])
					 ->setCellValue("CB".$i, $row1[ibgum_money1]+$row1[ibgum_money2])
					 ->setCellValue("CD".$i, "")
					 ->setCellValue("CD".$i, "")
					 ->setCellValue("CE".$i, $row[chaekwon_max])
					 ->setCellValue("CF".$i, $row["aw".$row[suljung_no]])
					 ->setCellValue("CG".$i, f_jumin_valid($row["aw".$row[suljung_no]."_jumin"]))
					 ->setCellValue("CH".$i, $row[suljung_maeib])
					 ->setCellValue("CI".$i, $row[regi_lic])
					 ->setCellValue("CJ".$i, $row[local_edu])
					 ->setCellValue("CK".$i, $row[regi_lic]+$row[local_edu])
					 ->setCellValue("CL".$i, $sj[g_jungjidae])
					 ->setCellValue("CM".$i, $sj[g_deungchobon])
					 ->setCellValue("CN".$i, $sj[g_yeolamjunggi])
					 ->setCellValue("CO".$i, $sj[g_jibaeinchobon])
					 ->setCellValue("CP".$i, $i_g_singonabbu)
					 ->setCellValue("CQ".$i, $i_g_kyotong)
					 ->setCellValue("CR".$i, $row[gongga_price])
					 ->setCellValue("CS".$i, $sj[b_basic_bosu])
					 ->setCellValue("CT".$i, $sj[b_woninjungseo])
					 ->setCellValue("CU".$i,$row[suljung_bosu])
					 ->setCellValue("CV".$i,$i_b_singonabbu)
					 ->setCellValue("CW".$i,$i_b_kyotong)
					 ->setCellValue("CX".$i,$sj[g_jejungmyung])
					 ->setCellValue("CY".$i,$row[bosu_price])
					 ->setCellValue("CZ".$i,$row[bosu_price_vat])
					 ->setCellValue("DA".$i,$row[bosu_price]+$row[bosu_price_vat])
					 ->setCellValue("DB".$i,$row[bosu_price]+$row[bosu_price_vat]+$row[gongga_price])

					 ->setCellValue("DC".$i,$row[au1])

		 ;
		$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "??????_??????");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
