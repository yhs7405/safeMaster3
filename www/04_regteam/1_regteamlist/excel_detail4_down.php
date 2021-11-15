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
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "동")
                 ->setCellValue("B".$i, "층")
                 ->setCellValue("C".$i, "호")
                 ->setCellValue("D".$i, "취득자1")
                 ->setCellValue("E".$i, "주민등록번호1")
                 ->setCellValue("F".$i, "주소1")
                 ->setCellValue("G".$i, "취득자2")
                 ->setCellValue("H".$i, "주민등록번호2")
                 ->setCellValue("I".$i, "주소2")
                 ->setCellValue("J".$i, "건물면적")
                 ->setCellValue("K".$i, "토지면적")
                 ->setCellValue("L".$i, "건물면적(등)")
                 ->setCellValue("M".$i, "토지면적(등)")
                 ->setCellValue("N".$i, "계약일")
                 ->setCellValue("O".$i, "건물신탁접수일자")
                 ->setCellValue("P".$i, "건물신탁접수번호")
                 ->setCellValue("Q".$i, "건물신탁원부날짜")
                 ->setCellValue("R".$i, "건물신탁원부번호")
                 ;
	$i++;

	while($row = $stmt->fetch()){
		
		$reg_land_area = $row[reg_land_area1]+$row[reg_land_area2]+$row[reg_land_area3]+$row[reg_land_area4]+$row[reg_land_area5]+$row[reg_land_area6]+$row[reg_land_area7]+$row[reg_land_area8]+$row[reg_land_area9]+$row[reg_land_area10];
	
		if(strlen($row[i1])==3){
			$fi = substr($row[i1],0,1);
		} else if(strlen($row[i1])==4){
			$fi = substr($row[i1],0,2);
		}
     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $row[h1])
                 ->setCellValue("B".$i, $fi)
                 ->setCellValue("C".$i, $row[i1])
                 ->setCellValue("D".$i, $row[j1])
                 ->setCellValue("E".$i, $row[k1])
                 ->setCellValue("F".$i, $row[l1])
                 ->setCellValue("G".$i, $row[m1])
                 ->setCellValue("H".$i, $row[n1])
								 ->setCellValue("I".$i, $row[o1])
								 ->setCellValue("J".$i, $row[con_building_area])
								 ->setCellValue("K".$i, $row[con_land_area])
								 ->setCellValue("L".$i, $row[reg_building_area])
								 ->setCellValue("M".$i, $reg_land_area)
								 ->setCellValue("N".$i, $row[contract_date])
								 ->setCellValue("O".$i, $row[building_trust_date])
								 ->setCellValue("P".$i, $row[building_trust_no])
								 ->setCellValue("Q".$i, $row[building_trust_org_date])
								 ->setCellValue("R".$i, $row[building_trust_org_no])
                 ;
	$i++;
	}

	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "등기관리팀리스트_위임장작성용엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
