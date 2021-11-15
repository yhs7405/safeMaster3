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

	$target_gubun			=	trim($_REQUEST[target_gubun]);


	$h1					=	trim($_REQUEST[h1]);
	$i1					=	trim($_REQUEST[i1]);
	$j1					=	trim($_REQUEST[j1]);
	$memo				=	trim($_REQUEST[memo]);
	$kikan1_null_ch		=	trim($_REQUEST[kikan1_null_ch]);
	$kikan2_null_ch		=	trim($_REQUEST[kikan2_null_ch]);
	$not_data_ch			=	trim($_REQUEST[not_data_ch]);

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
  // ae1(정산비고)
	if($memo!=""){
		$wherequery.= " and ae1 like '%{$memo}%' ";
	}
  // 미입력데이터만 보기
	if($not_data_ch=="Y"){
		$wherequery.= " and ( ao1 = '' or ao1_toji = '' or an1 = '' or am1_rpur_cost = '') ";
	}

	$wherequery.= $imss;

	//echo $wherequery;
	$rows_total = db_count_all($board_dbname,$wherequery);

	//현장정보 반환
	$sql2 = "select * from tbl_hyunjang_info where h_idx='{$h_idx}' limit 1 ";
	$kk = db_query_value($sql2);

	$objPHPExcel = new PHPExcel();

	$sql = "select *, if(m1='',j1,CONCAT(j1, ',', m1)) as jm1 , case when u1='1' then CONCAT('(2차)',u1_1) when u1='2' then CONCAT('(2차)',u1_1, '-', u1_2) when u1='3' then CONCAT('(3차)',u1_1, '-', u1_2, '-', u1_3) when u1='4' then CONCAT('(4차)',u1_1, '-', u1_2, '-', u1_3, '-', u1_4) when u1='5' then CONCAT('(5차)',u1_1, '-', u1_2, '-', u1_3, '-', u1_4, '-', u1_5) when u1='6' then CONCAT('(6차)',u1_1, '-', u1_2, '-', u1_3, '-', u1_4, '-', u1_5, '-', u1_6) else '' end as u1_list from $board_dbname  $wherequery order by  cast(a1 as unsigned) asc ";
	//echo $sql;


	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "■ ".f_hyunjang_name($h_idx)."(".f_money0($rows_total).")건");
	$i++;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(50);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "동")
                 ->setCellValue("C".$i, "호")
                 ->setCellValue("D".$i, "취득자")
                 ->setCellValue("E".$i, "취득세신고일")
                 ->setCellValue("F".$i, "(예상)등기접수일")
                 ->setCellValue("G".$i, "등기접수일")
                 ->setCellValue("H".$i, "전매여부")
                 ->setCellValue("I".$i, "인지산정과표")
                 ->setCellValue("J".$i, "취득과세표")
                 ->setCellValue("K".$i, "인지매입액")
                 ->setCellValue("L".$i, "신탁여부")
                 ->setCellValue("M".$i, "신탁말소(건물)등록세")
                 ->setCellValue("N".$i, "신탁말소(토지)등록세")
                 ->setCellValue("O".$i, "증지")
                 ->setCellValue("P".$i, "본인인지")
                 ->setCellValue("Q".$i, "본인인지금액")
                 ->setCellValue("R".$i, "(실)인지매입")
                 ->setCellValue("S".$i, "인지구매")
                 ->setCellValue("T".$i, "인지전달")
                 ->setCellValue("U".$i, "정산비고")
                 ;
	$i++;

	while($row = $stmt->fetch()){

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $i-2)
                 ->setCellValue("B".$i, $row[h1])
                 ->setCellValue("C".$i, $row[i1])
                 ->setCellValue("D".$i, $row[jm1])
                 ->setCellValue("E".$i, $row[al1_acp_dateal1_acp_date])
                 ->setCellValue("F".$i, $row[pred_g1])
                 ->setCellValue("G".$i, $row[g1])
                 ->setCellValue("H".$i, $row[u1_list])
								 ->setCellValue("I".$i, $row[am1_table])
								 ->setCellValue("J".$i, $row[af1])
								 ->setCellValue("K".$i, $row[am1_pur_cost])
								 ->setCellValue("L".$i, "")
								 ->setCellValue("M".$i, $row[ao1])
								 ->setCellValue("N".$i, $row[ao1_toji])
								 ->setCellValue("O".$i, $row[discount_can1ost])
								 ->setCellValue("P".$i, $row[am1_me_yn])
								 ->setCellValue("Q".$i, $row[am1_me_cost])
								 ->setCellValue("R".$i, $row[am1_rpur_cost])
								 ->setCellValue("S".$i, $row[am1_pur_date])
								 ->setCellValue("T".$i, $row[am1_relay_date])
								 ->setCellValue("U".$i, $row[ae1])
                 ;
	$i++;
	}

	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "기타공과금산정_엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
