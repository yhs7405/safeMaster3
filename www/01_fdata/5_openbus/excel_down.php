<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");
	include ("../../include/excel.inc");
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";

	$h_idx				=	trim($_REQUEST[h_idx]);

	$bank_code			=	trim($_REQUEST[bank_code]);
	$jijum_code			=	trim($_REQUEST[jijum_code]);

	$h1					=	trim($_REQUEST[h1]);
	$i1					=	trim($_REQUEST[i1]);
	$j1					=	trim($_REQUEST[j1]);
	$memo				=	trim($_REQUEST[memo]);
	$bank_null_ch	=	trim($_REQUEST[bank_null_ch]);


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
	
  // ad1(cs비고) , doc_memo(서류비고), au1(은행비고), rec_memo(접수비고), ae1(정산비고)
 	if($memo!=""){
		$wherequery.= " and (ad1 like '%{$memo}%' or doc_memo like '%{$memo}%' or au1 like '%{$memo}%' or rec_memo like '%{$memo}%' or ae1 like '%{$memo}%') ";
	}

	$wherequery.= $imss;

	$objPHPExcel = new PHPExcel();

	$sql = "select *, if(m1='',j1,CONCAT(j1, ',', m1)) as jm1, case when u1='1' then CONCAT('(2차)',u1_1) when u1='2' then CONCAT('(2차)',u1_1, '-', u1_2) when u1='3' then CONCAT('(3차)',u1_1, '-', u1_2, '-', u1_3) when u1='4' then CONCAT('(4차)',u1_1, '-', u1_2, '-', u1_3, '-', u1_4) when u1='5' then CONCAT('(5차)',u1_1, '-', u1_2, '-', u1_3, '-', u1_4, '-', u1_5) when u1='6' then CONCAT('(6차)',u1_1, '-', u1_2, '-', u1_3, '-', u1_4, '-', u1_5, '-', u1_6) else '' end as u1_list from $board_dbname $wherequery  order by  cast(a1 as unsigned) asc ";
	//echo $sql;
	$i_sql = $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=3;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(35);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(25);
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AT')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AU')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AV')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AW')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AX')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AY')->setWidth(0);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AZ')->setWidth(50);

//	$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(15);
//	$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(15);
//	$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(15);
//	$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(15);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "고객고유번호")
                 ->setCellValue("C".$i, "동")
                 ->setCellValue("D".$i, "호")
                 ->setCellValue("E".$i, "타입")
                 ->setCellValue("F".$i, "회원여부")
                 ->setCellValue("G".$i, "취득자1")
                 ->setCellValue("H".$i, "주민번호1")
                 ->setCellValue("I".$i, "취득자2")
                 ->setCellValue("J".$i, "주민번호2")
                 ->setCellValue("K".$i, "")
                 ->setCellValue("L".$i, "핸드폰")
                 ->setCellValue("M".$i, "잔금일")
                 ->setCellValue("N".$i, "은행명")
                 ->setCellValue("O".$i, "지점")
                 ->setCellValue("P".$i, "승계")
                 ->setCellValue("Q".$i, "CS비고")
                 ->setCellValue("R".$i, "정산비고")
                 ->setCellValue("S".$i, "서류비고")
                 ->setCellValue("T".$i, "은행비고")
                 ->setCellValue("U".$i, "완납증명서")
                 ->setCellValue("V".$i, "취득세신고")
                 ->setCellValue("W".$i, "취득세납부")
                 ->setCellValue("X".$i, "서류미비")
                 ->setCellValue("Y".$i, "미비서류")
                 ->setCellValue("Z".$i, "등기접수일")
                 ->setCellValue("AA".$i, "필증수령일")
                 ->setCellValue("AB".$i, "필증전달일")
                 ->setCellValue("AC".$i, "환불완료")
                 ->setCellValue("AD".$i, "비용안내")
                 ->setCellValue("AE".$i, "취득세입금일")
                 ->setCellValue("AF".$i, "입금금액")
                 ->setCellValue("AG".$i, "")
                 ->setCellValue("AH".$i, "가상계좌")
                 ->setCellValue("AI".$i, "")
                 ->setCellValue("AJ".$i, "")
                 ->setCellValue("AK".$i, "")
                 ->setCellValue("AL".$i, "")
                 ->setCellValue("AM".$i, "")
                 ->setCellValue("AN".$i, "")
                 ->setCellValue("AO".$i, "")
                 ->setCellValue("AP".$i, "")
                 ->setCellValue("AQ".$i, "")
                 ->setCellValue("AR".$i, "")
                 ->setCellValue("AS".$i, "")
                 ->setCellValue("AT".$i, "")
                 ->setCellValue("AU".$i, "")
                 ->setCellValue("AV".$i, "")
                 ->setCellValue("AW".$i, "")
                 ->setCellValue("AX".$i, "")
                 ->setCellValue("AY".$i, "")
                 ->setCellValue("AZ".$i, "등기비용")

//                 ->setCellValue("AK".$i, "1")
//                 ->setCellValue("AL".$i, "2")
//                 ->setCellValue("AM".$i, "3")
//                 ->setCellValue("AN".$i, "4")
                 ;
	$i++;$i++;$i++;

	while($row = $stmt->fetch()){

			// 미비서류 여부 확인
			if($row[mibi_doc]!=""){
				$mibi_yn ="0";
			}else{
				$mibi_yn ="1";
			}
			
			//비용안내일 큰값 적용
			if(intval($row[gch1_cost_date])>intval($row[gch2_cost_date])){
				$gch_cost_date =$row[gch1_cost_date];
			}else{
				$gch_cost_date =$row[gch2_cost_date];
			}

			$cost_sum = $row[al1] + ($row[am1 ] + $row[aj1] + $row[ak1] + $row[an1] + $row[as1] + $row[at1] + $row[ao1] + $row[ap1] + $row[aq1] + $row[ar1]);
			$etc_sum = $row[am1 ] + $row[aj1] + $row[ak1] + $row[an1] + $row[as1] + $row[at1] + $row[ao1] + $row[ap1] + $row[aq1] + $row[ar1];
			$cost_sum_doc = "총비용 ".f_money0($cost_sum)." 원 = 취득세 ".f_money0($row[al1])." 원 + 그외비용소계 ".f_money0($etc_sum)." 원 ";
			$cost_sum_doc.= "(인지 ".f_money0($row[am1])." 원, 채권할인율(".f_money0($row[bond_sale_rate])."%), 이전채권비".f_money0($row[aj1])." 원, 설정채권비 ".f_money0($row[ak1])." 원, 증지 ".f_money0($row[an1])." 원, ";
			$cost_sum_doc.= "신탁말소보수(해당시) ".f_money0($row[as1]+$row[at1])." 원, ";
			$cost_sum_doc.= "신탁말소등록세(해당시) ".f_money0($row[ao1])." 원, ";
			$cost_sum_doc.= "제증명 ".f_money0($row[ap1])." 원, 이전등기보수료 ".f_money0($row[aq1]+$row[ar1])." 원) ";
		 $objPHPExcel->setActiveSheetIndex(0)
					 ->setCellValue("A".$i, $i-5)
					 ->setCellValue("B".$i, $row[a1])
					 ->setCellValue("C".$i, $row[h1])
					 ->setCellValue("D".$i, $row[i1])
					 ->setCellValue("E".$i, $row[con_building_area])
					 ->setCellValue("F".$i, f_member_value($row[a1]))
					 ->setCellValue("G".$i, $row[j1])
					 ->setCellValue("H".$i, $row[k1])
					 ->setCellValue("I".$i, $row[m1])
					 ->setCellValue("J".$i, $row[n1])
					 ->setCellValue("K".$i, "")
					 ->setCellValue("L".$i, $row[p1])
					 ->setCellValue("M".$i, $row[balance_date])
					 ->setCellValue("N".$i, f_bank_name($row[d1]))
					 ->setCellValue("O".$i, f_jijum_name($row[e1]))
					 ->setCellValue("P".$i, $row[u1_list])
					 ->setCellValue("Q".$i, $row[ad1])
					 ->setCellValue("R".$i, $row[ae1])
					 ->setCellValue("S".$i, $row[doc_memo])
					 ->setCellValue("T".$i, $row[au1])
					 ->setCellValue("U".$i, $row[comp_rec_date])
					 ->setCellValue("V".$i, $row[al1_acp_date])
					 ->setCellValue("W".$i, $row[r1])
					 ->setCellValue("X".$i, $mibi_yn) //if($row[mibi_doc]!=""){"1"}else{"0"})
					 ->setCellValue("Y".$i, $row[mibi_doc])
					 ->setCellValue("Z".$i, $row[g1])
					 ->setCellValue("AA".$i,$row[ijp_s_date])
					 ->setCellValue("AB".$i,$row[ijp_j_date])
					 ->setCellValue("AC".$i,$row[refund_date])
					 ->setCellValue("AD".$i,$gch_cost_date)
					 ->setCellValue("AE".$i,f_date_cut($row[ah1]))
					 ->setCellValue("AF".$i,$row[ai1])
					 ->setCellValue("AG".$i,"")
					 ->setCellValue("AH".$i,$row[vir_acc_no])
					 ->setCellValue("AI".$i, "")
					 ->setCellValue("AJ".$i, "")
					 ->setCellValue("AK".$i, "")
					 ->setCellValue("AL".$i, "")
					 ->setCellValue("AM".$i, "")
					 ->setCellValue("AN".$i, "")
					 ->setCellValue("AO".$i, "")
					 ->setCellValue("AP".$i, "")
					 ->setCellValue("AQ".$i, "")
					 ->setCellValue("AR".$i, "")
					 ->setCellValue("AS".$i, "")
					 ->setCellValue("AT".$i, "")
					 ->setCellValue("AU".$i, "")
					 ->setCellValue("AV".$i, "")
					 ->setCellValue("AW".$i, "")
					 ->setCellValue("AX".$i, "")
					 ->setCellValue("AY".$i, "")
					 ->setCellValue("AZ".$i, $cost_sum_doc)
					 
//					 ->setCellValue("AK".$i,$imsi_daesang)
//					 ->setCellValue("AL".$i,$i_sql)
//					 ->setCellValue("AM".$i,$j_sql)
//					 ->setCellValue("AN".$i,$k_sql)
		 ;
		$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "오픈버스용_엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
