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
                 ->setCellValue("B".$i, "고객고유번호")
                 ->setCellValue("C".$i, "단지명")
                 ->setCellValue("D".$i, "회원여부")
                 ->setCellValue("E".$i, "등기접수일")
                 ->setCellValue("F".$i, "전매/증여")
                 ->setCellValue("G".$i, "공유여부")
                 ->setCellValue("H".$i, "타입")
                 ->setCellValue("I".$i, "동")
                 ->setCellValue("J".$i, "호")
                 ->setCellValue("K".$i, "정산비고")
                 ->setCellValue("L".$i, "취득자1이름")
                 ->setCellValue("M".$i, "취득자1주민번호")
                 ->setCellValue("N".$i, "취득자1주소")
                 ->setCellValue("O".$i, "취득자2이름")
                 ->setCellValue("P".$i, "취득자2주민번호")
                 ->setCellValue("Q".$i, "취득자2주소")
                 ->setCellValue("R".$i, "배우자")
                 ->setCellValue("S".$i, "전화번호")
                 ->setCellValue("T".$i, "CS비고")
                 ->setCellValue("U".$i, "취득과세표")
                 ->setCellValue("V".$i, "시가표준액")
                 ->setCellValue("W".$i, "취득세입금일(년월일시분)")
                 ->setCellValue("X".$i, "입금금액")
                 ->setCellValue("Y".$i, "채권최고액")

//                 ->setCellValue("Z".$i, "채권최고액2")
//                 ->setCellValue("AA".$i, "채권최고액3")
//                 ->setCellValue("AB".$i, "채권최고액4")

                 ->setCellValue("Z".$i, "취득세납부")
                 ->setCellValue("AA".$i, "취득세합계")
                 ->setCellValue("AB".$i, "신탁말소등록세")
                 ->setCellValue("AC".$i, "인지갯수")
                 ->setCellValue("AD".$i, "인지세")
                 ->setCellValue("AE".$i, "증지세")
                 ->setCellValue("AF".$i, "제증명")
                 ->setCellValue("AG".$i, "이전채권번호1")
                 ->setCellValue("AH".$i, "이전채권번호2")
                 ->setCellValue("AI".$i, "이전채권본인부담액합계")
                 ->setCellValue("AJ".$i, "설정채권번호")

//                 ->setCellValue("AN".$i, "설정채권번호2")
//                 ->setCellValue("AO".$i, "설정채권번호3")
//                 ->setCellValue("AP".$i, "설정채권번호4")

                 ->setCellValue("AK".$i, "설정채권본인부담액합계")
                 ->setCellValue("AL".$i, "소유권이전보수료")
                 ->setCellValue("AM".$i, "소유권이전부가세")
                 ->setCellValue("AN".$i, "신탁말소등기보수료")
                 ->setCellValue("AO".$i, "신탁말소등기부가세")
                 ->setCellValue("AP".$i, "보수료합계")
                 ->setCellValue("AQ".$i, "공과금합계")
                 ->setCellValue("AR".$i, "비용합계-보수료+공과금")
                 ->setCellValue("AS".$i, "환불금-입금-비용합계")
                 ->setCellValue("AT".$i, "1회청구대상필드")
                 ->setCellValue("AU".$i, "환불금")
                 ->setCellValue("AV".$i, "추가입금대상여부")
                 ->setCellValue("AW".$i, "지급금합계")
                 ->setCellValue("AX".$i, "전입의뢰일")
                 ->setCellValue("AY".$i, "전입수령일")
                 ->setCellValue("AZ".$i, "담당자_외주_id")
                 ->setCellValue("BA".$i, "소유주와의관계")
                 ->setCellValue("BB".$i, "재열람의뢰일")
                 ->setCellValue("BC".$i, "재열람수령일")
                 ->setCellValue("BD".$i, "재열람확인사항")
                 ->setCellValue("BE".$i, "문자발송일")
                 ->setCellValue("BF".$i, "비고")
                 ->setCellValue("BG".$i, "재무돌이비고")
                 ->setCellValue("BH".$i, "이전필증수령일")
                 ->setCellValue("BI".$i, "이전필증전달일")
                 ->setCellValue("BJ".$i, "이전정산완료일")
                 ->setCellValue("BK".$i, "이전필증배포일")
                 ->setCellValue("BL".$i, "이전영수증출력일")
                 ->setCellValue("BM".$i, "현금영수증발행일")
//                 ->setCellValue("BN".$i, "현금영수증출력일")
                 ->setCellValue("BN".$i, "환불은행")
                 ->setCellValue("BO".$i, "환불계좌번호")
                 ->setCellValue("BP".$i, "예금주")
                 ->setCellValue("BQ".$i, "환불금-정산금")
                 ->setCellValue("BR".$i, "환불비고")
                 ->setCellValue("BS".$i, "환불완료일")
                 ->setCellValue("BT".$i, "환불작업자")
                 ->setCellValue("BU".$i, "비용청구일")
                 ->setCellValue("BV".$i, "은행")
                 ->setCellValue("BW".$i, "지점")
                 ->setCellValue("BX".$i, "카드/현금")
                 ->setCellValue("BY".$i, "발행일/승인일")
                 ->setCellValue("BZ".$i, "국토교통부")
                 ->setCellValue("CA".$i, "입금일")
                 ->setCellValue("CB".$i, "입금액")
                 ->setCellValue("CD".$i, "카드수수료")
                 ->setCellValue("CD".$i, "실제입금액")
                 ->setCellValue("CE".$i, "채권채고액")
                 ->setCellValue("CF".$i, "채무자")
                 ->setCellValue("CG".$i, "채무자주민번호")
                 ->setCellValue("CH".$i, "설정채권매입액")
                 ->setCellValue("CI".$i, "등록면허세")
                 ->setCellValue("CJ".$i, "지방교육세")
                 ->setCellValue("CK".$i, "등록세합계")
                 ->setCellValue("CL".$i, "증지대")
                 ->setCellValue("CM".$i, "등본발급대")
                 ->setCellValue("CN".$i, "열람증지대(우리공)")
                 ->setCellValue("CO".$i, "지배인초본발급(하나공)")
                 ->setCellValue("CP".$i, "등록세신고납부대행")
                 ->setCellValue("CQ".$i, "교통비")
                 ->setCellValue("CR".$i, "공과금합계")
                 ->setCellValue("CS".$i, "기본보수료")
                 ->setCellValue("CT".$i, "원인증서작성료")
                 ->setCellValue("CU".$i, "누진보수료")
                 ->setCellValue("CV".$i, "등록세대행")
                 ->setCellValue("CW".$i, "교통비")
                 ->setCellValue("CX".$i, "등본(제증명)")
                 ->setCellValue("CY".$i, "보수료소계")
                 ->setCellValue("CZ".$i, "부가세")
                 ->setCellValue("DA".$i, "보수료합계")
                 ->setCellValue("DB".$i, "은행비용합계")
                 ->setCellValue("DC".$i, "은행비고")
;

	$i++;

	while($row = $stmt->fetch()){


		$sql1 = "select * from tbl_sugum where a1='{$row[j_a1]}' and suljung_no='$row[suljung_no]'  limit 1 ";
		//echo $sql1;
		$row1 = db_query_value($sql1);

		//설정비용
			if($row[gukto]=="gukto"){
				$basic_gukto = "gukto";
			}else{
				$basic_gukto = "basic";
			}
		$sql = "select * from tbl_bank_jijum_rate where jijum_code='{$row[jijum_code]}' and basic_gukto='{$basic_gukto}' limit 1";
		//echo $sql;
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
					 ->setCellValue("B".$i, $row[j_a1])  // 고객고유번호
					 ->setCellValue("C".$i, $row[b1])  // 단지명
					 ->setCellValue("D".$i, $row[c1])  // 회원여부
					 ->setCellValue("E".$i, $row[g1])  // 등기접수일
					 ->setCellValue("F".$i, $row[u1])  // 전매/증여
					 ->setCellValue("G".$i, $row[v1])  // 공유여부
					 ->setCellValue("H".$i, $row[w1])  // 타입
					 ->setCellValue("I".$i, $row[h1])  // 동
					 ->setCellValue("J".$i, $row[i1])  // 호
					 ->setCellValue("K".$i, $row[ae1]) // 정산비고

					 ->setCellValue("L".$i, $row[j1])  // 취득자1이름
					 ->setCellValue("M".$i, $row[k1])  // 취득자1주민번호
					 ->setCellValue("N".$i, $row[l1])  // 취득자1주소
					 ->setCellValue("O".$i, $row[m1])  // 취득자2이름
					 ->setCellValue("P".$i, $row[n1])  // 취득자2주민번호
					 ->setCellValue("Q".$i, $row[o1])  // 취득자2주소
					 ->setCellValue("R".$i, $row[s1])  // 배우자
					 ->setCellValue("S".$i, $row[p1])  // 전화번호
					 ->setCellValue("T".$i, $row[ad1]) // CS비고

					 ->setCellValue("U".$i,  $row[af1])  // 취득과세표
					 ->setCellValue("V".$i,  $row[ag1])  // 시가표준액
					 ->setCellValue("W".$i,  $row[ah1])  // 취득세입금일(년월일시분)
					 ->setCellValue("X".$i,  $row[ai1])  // 입금금액
					 ->setCellValue("Y".$i,  $row[chaekwon_max])  // 채권최고액
//					 ->setCellValue("Y".$i,  $row[ax1])  // 채권최고액2
//					 ->setCellValue("AA".$i, $row[ay1])  // 채권최고액3
//					 ->setCellValue("AB".$i, $row[az1])  // 채권최고액4

					 ->setCellValue("Z".$i, $row[r1])   // 취득세납부
					 ->setCellValue("AA".$i, $row[al1])  // 취득세합계
					 ->setCellValue("AB".$i, $row[ao1])  // 신탁말소등록세
					 ->setCellValue("AC".$i, $row[t1])   // 인지갯수
					 ->setCellValue("AD".$i, $row[am1])  // 인지세
					 ->setCellValue("AE".$i, $row[an1])  // 증지세
					 ->setCellValue("AF".$i, $row[ap1])  // 제증명

					 ->setCellValue("AG".$i, $row[x1])   // 이전채권번호1
					 ->setCellValue("AH".$i, $row[y1 ])  // 이전채권번호2
					 ->setCellValue("AI".$i, $row[aj1])  // 이전채권본인부담액합계
					 ->setCellValue("AJ".$i, $row[chaekwon_no])   // 설정채권번호
//					 ->setCellValue("AN".$i, $row[aa1])  // 설정채권번호2
//					 ->setCellValue("AO".$i, $row[ab1])  // 설정채권번호3
//					 ->setCellValue("AP".$i, $row[ac1])  // 설정채권번호4
					 ->setCellValue("AK".$i, $row[ak1])  // 설정채권본인부담액합계

					 ->setCellValue("AL".$i, $row[aq1])  // 소유권이전보수료
					 ->setCellValue("AM".$i, $row[ar1])  // 소유권이전부가세
					 ->setCellValue("AN".$i, $row[as1])  // 신탁말소등기보수료
					 ->setCellValue("AO".$i, $row[at1])  // 신탁말소등기부가세
					 ->setCellValue("AP".$i, $row[aq1]+$row[ar1]+$row[as1]+$row[at1]) // 보수료합계
					 ->setCellValue("AQ".$i, $row[al1]+$row[ao1]+$row[am1]+$row[an1]+$row[ap1]+$row[aj1]+$row[ak1])  // 공과금합계
					 ->setCellValue("AR".$i, $row[aq1]+$row[ar1]+$row[as1]+$row[at1]+$row[al1]+$row[ao1]+$row[am1]+$row[an1]+$row[ap1]+$row[aj1]+$row[ak1])  // 비용합계(보수료+공과금)
					 ->setCellValue("AS".$i, $row[ai1]-($row[aq1]+$row[ar1]+$row[as1]+$row[at1]+$row[al1]+$row[ao1]+$row[am1]+$row[an1]+$row[ap1]+$row[aj1]+$row[ak1]))  // 환불금(입금-비용합계)

					 ->setCellValue("AT".$i, $row[cg_daesang])  // 1회청구대상필드
					 ->setCellValue("AU".$i, $row[refund_fee])  // 환불금
					 ->setCellValue("AV".$i, $row[chuga_ibgum_daesang])  // 추가입금대상여부
					 ->setCellValue("AW".$i, $row[total_pay])  // 지급금합계
					 ->setCellValue("AX".$i, $row[junib_request_date])  // 전입의뢰일
					 ->setCellValue("AY".$i, $row[junib_s_date])  // 전입수령일
					 ->setCellValue("AZ".$i, $row[damdang_id])  // 담당자(외주)id
					 ->setCellValue("BA".$i, f_sou1_value($row[sou_relation]))  // 소유주와의관계
					 ->setCellValue("BB".$i, $row[review_request_date])  // 재열람의뢰일
					 ->setCellValue("BC".$i, $row[review_s_date])  // 재열람수령일
					 ->setCellValue("BD".$i, $row[review_confirm])  // 재열람확인사항
					 ->setCellValue("BE".$i, $row[sms_date])  // 문자발송일
					 ->setCellValue("BF".$i, $row[memo])  // 비고

					 ->setCellValue("BG".$i, $row[ijp_s_memo])  // 재무돌이비고
					 ->setCellValue("BH".$i, $row[ijp_s_date])  // 이전필증수령일
					 ->setCellValue("BI".$i, $row[ijp_j_date])  // 이전필증전달일
					 ->setCellValue("BJ".$i, $row[ijj_w_date])  // 이전정산완료일
					 ->setCellValue("BK".$i, $row[ijp_b_date])  // 이전필증배포일
					 ->setCellValue("BL".$i, $row[ijy_c_date])  // 이전영수증출력일
					 ->setCellValue("BM".$i, $row[hy_b_date])  // 현금영수증발행일
//					 ->setCellValue("BN".$i, $row[hy_d_date])   // 현금영수증출력일

					 ->setCellValue("BN".$i, $row[refund_bank])  // 환불은행
					 ->setCellValue("BO".$i, $row[refund_account])  // 환불계좌번호
					 ->setCellValue("BP".$i, $row[refund_name])  // 예금주
					 ->setCellValue("BQ".$i, $row[refund_money])  // 환불금(정산금)
					 ->setCellValue("BR".$i, $row[refund_memo])  // 환불비고
					 ->setCellValue("BS".$i, $row[refund_date])  // 환불완료일
					 ->setCellValue("BT".$i, $row[refund_id])  // 환불작업자

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
	 $filename = iconv("UTF-8", "EUC-KR", "현장_백업");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
