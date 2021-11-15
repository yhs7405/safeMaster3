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

	$sql = "select *, if(m1='',j1,CONCAT(j1, ',', m1)) as jm1, if(point_data='1','이전ONLY',if(point_data='2', '설정ONLY', if(point_data='3', point_data_name, ''))) as point_dm, case when u1='1' then CONCAT('(1차)',u1_1) when u1='2' then CONCAT('(2차)',u1_1, '-', u1_2) when u1='3' then CONCAT('(3차)',u1_1, '-', u1_2, '-', u1_3) when u1='4' then CONCAT('(4차)',u1_1, '-', u1_2, '-', u1_3, '-', u1_4) when u1='5' then CONCAT('(5차)',u1_1, '-', u1_2, '-', u1_3, '-', u1_4, '-', u1_5) when u1='6' then CONCAT('(6차)',u1_1, '-', u1_2, '-', u1_3, '-', u1_4, '-', u1_5, '-', u1_6) else '' end as u1_list, DATEDIFF(pred_g1,pred_g1_temp) as g1_temp, DATEDIFF(date_format(NOW(),'%Y%m%d'),pred_g1_temp) as g1_temp2 , DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) as ft_date, if(point_data='1','이전ONLY',if(point_data='2', '설정ONLY', if(point_data='3', point_data_name, ''))) as point_dm, if(m1='',j1,CONCAT(j1, ',', m1)) as jm1 , DATEDIFF(pred_g1,j1_ingam_limit_date) as j1_ingam_limit_cnt, DATEDIFF(pred_g1,m1_ingam_limit_date) as m1_ingam_limit_cnt, DATEDIFF(pred_g1,j1_chobon_limit_date) as j1_chobon_limit_cnt, DATEDIFF(pred_g1,m1_chobon_limit_date) as m1_chobon_limit_cnt, DATEDIFF(pred_g1,j1_etc_limit_date) as j1_etc_limit_cnt, DATEDIFF(pred_g1,m1_etc_limit_date) as m1_etc_limit_cnt from $board_dbname  $wherequery order by  cast(a1 as unsigned) asc ";
	//echo $sql;


	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "■ ".f_hyunjang_name($h_idx)."(".f_money0($rows_total).")건");
	$i++;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(35);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(35);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(35);

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "승계여부")
                 ->setCellValue("C".$i, "동")
                 ->setCellValue("D".$i, "호")
                 ->setCellValue("E".$i, "취득자1")
                 ->setCellValue("F".$i, "취득자2")
                 ->setCellValue("G".$i, "은행1")
                 ->setCellValue("H".$i, "지점1")
                 ->setCellValue("I".$i, "채권최고액1")
                 ->setCellValue("J".$i, "채권최고액2")
                 ->setCellValue("K".$i, "채권최고액3")
                 ->setCellValue("L".$i, "채권최고액4")
                 ->setCellValue("M".$i, "특이사항")
                 ->setCellValue("N".$i, "수납결과")
                 ->setCellValue("O".$i, "은행비고")
                 ->setCellValue("P".$i, "접수비고")
                 ->setCellValue("Q".$i, "정산비고")
                 ->setCellValue("R".$i, "은행2")
                 ->setCellValue("S".$i, "지점2")
                 ->setCellValue("T".$i, "은행3")
                 ->setCellValue("U".$i, "지점3")
                 ->setCellValue("V".$i, "은행4")
                 ->setCellValue("W".$i, "지점4")
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


		
    if($row[al1_hope_yn]=="y"||$row[al1_imp_yn]=="y"){
    	if(($row[total_sum]-$row[al1]-$row[ai1])!=0){
    	
    		$s_result = f_money($row[total_sum]-$row[al1]-$row[ai1]);
    	}else{
        $s_result = "ok";
    	}	
  	} else{	
  		if(($row[total_sum]-$row[ai1])!=0){
    		$s_result = f_money($row[total_sum]-$row[ai1]);
    	}else{
        $s_result = "ok";
   	}	
  	}


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $i-2)
                 ->setCellValue("B".$i, $row[u1_list])
                 ->setCellValue("C".$i, $row[h1])
                 ->setCellValue("D".$i, $row[i1])
                 ->setCellValue("E".$i, $row[j1])
                 ->setCellValue("F".$i, $row[m1])
                 ->setCellValue("G".$i, f_bank_name($sk1[bank_code]))
                 ->setCellValue("H".$i, f_jijum_name($sk1[jijum_code]))
								 ->setCellValue("I".$i, $row[av1])
								 ->setCellValue("J".$i, $row[ax1])
								 ->setCellValue("K".$i, $row[ay1])
								 ->setCellValue("L".$i, $row[az1])
								 ->setCellValue("M".$i, $row[point_dm])
								 ->setCellValue("N".$i, $s_result)
								 ->setCellValue("O".$i, $row[au1])
								 ->setCellValue("P".$i, $row[rec_memo])
								 ->setCellValue("Q".$i, $row[ae1])
								 ->setCellValue("R".$i, f_bank_name($sk2[bank_code]))
								 ->setCellValue("S".$i, f_jijum_name($sk2[jijum_code]))
								 ->setCellValue("T".$i, f_bank_name($sk3[bank_code]))
								 ->setCellValue("U".$i, f_jijum_name($sk3[jijum_code]))
								 ->setCellValue("V".$i, f_bank_name($sk4[bank_code]))
								 ->setCellValue("W".$i, f_jijum_name($sk4[jijum_code]))
                 ;
	$i++;
	}

	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "등기관리팀리스트_접수용엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
