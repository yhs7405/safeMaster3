<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");
	include ("../include/excel.inc");
//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

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
	$kikan2_null_ch			=	trim($_REQUEST[kikan2_null_ch]);
	$bank_null_ch		=	trim($_REQUEST[bank_null_ch]);

	$h1					=	trim($_REQUEST[h1]);
	$i1					=	trim($_REQUEST[i1]);
	$j1					=	trim($_REQUEST[j1]);
	$memo				=	trim($_REQUEST[memo]);
	$bosu_ch			=	trim($_REQUEST[bosu_ch]);

	if($target_date=="")	$target_date="g1";

	$sql = "SELECT max( cast( ijy_c_date AS unsigned ) ) AS mm FROM tbl_junib WHERE (ijy_c_date IS NOT NULL AND ijy_c_date <> '') LIMIT 1";
	$ss = db_query_fetch($sql);

	if(($s_date=="")&&($e_date=="")){
		if($ss[mm]==""){
			$s_date=date("Ymd");
			$e_date=date("Ymd");
		}else{
			$s_date=$ss[mm];
			$e_date=$ss[mm];
		}
	}


	$view_num		=	trim($_REQUEST[view_num]);	//한라인에 몇개를 출력할건지//
	if($_REQUEST[page]==""){$page=1;}else{$page=$_REQUEST[page];}
	$Page_List		=	10;				//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	100;				//리스트 갯수

	$wherequery = " where 1=1  ";

	if($h_idx!="")			$wherequery.= " and h_idx = ".$h_idx." ";
	if($bank_null_ch=="Y"){
		$wherequery.= " and d1 <> '' ";
	} else {
		if($bank_code!="")		$wherequery.= " and d1 = '".$bank_code."' ";
		if($jijum_code!="")		$wherequery.= " and e1 = '".$jijum_code."' ";
	}
	if($h1!="")			$wherequery.= " and h1 = '".$h1."' ";
	if($i1!="")			$wherequery.= " and i1 = '".$i1."' ";
	if($j1!="")			$wherequery.= " and (j1 like '%{$j1}%' or m1 like '%{$j1}%')";

	if($target_date!=""){
		
		if($target_date=="100") {
			$imsi = " and ijun_update in (SELECT max(ijun_update) FROM tbl_junib where ijun_update<>'' GROUP BY ijun_update ORDER BY ijun_update DESC)";
			$wherequery.=$imsi;
		}else if(($s_date!="")&&($e_date!="")){
			$imsi = "";
			if(($target_date=="sjp_s_date")||($target_date=="sjp_j_date")||($target_date=="sjj_w_date")||($target_date=="sjp_b_date")||($target_date=="sg_b_date")){  //설정일때
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
			if($target_date2!="") {$imsi = " and ({$target_date2}='' or {$target_date2} is null )";}
			$wherequery.=$imsi;
	}else{
		if($target_date2!=""){
			if(($s_date2!="")&&($e_date2!="")){
				$imsi = "";
				if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")){ //설정일때
					$imsi = " and a1 in (select a1 from tbl_suljung where {$target_date2} between {$s_date2} and {$e_date2} )";
				}else if($target_date2=="sg_b_date"){
					$imsi = " and idx in (select idx from tbl_suljung where {$target_date2} between {$s_date2} and {$e_date2} )";
				}else{
					if($target_date2!="") {$imsi = " and {$target_date2} between ";}
					if($s_date2==$e_date2) {$imsi.= " {$s_date2} and {$e_date2} ";}
					if($s_date2!=$e_date2) {$imsi.= " {$s_date2} and {$e_date2} ";}
				}
				$wherequery.=$imsi;
			}
		}
	}

	if($bosu_ch=="Y"){
		$wherequery.= " and (aq1 > 0 or ar1 > 0 or as1 > 0 or at1 > 0)";
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

	//echo $wherequery;
	$rows_total = db_count_all($board_dbname,$wherequery);

	//영수증출력    ijy_c_date
	//현금영수증발행  hy_b_date


	$sql = "select * from $board_dbname  $wherequery order by  cast(h1 as unsigned) asc,cast(i1 as unsigned) asc ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();


	if($rows > 0){

		$i=1;

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);


		$objPHPExcel->setActiveSheetIndex(0)
					 ->setCellValue("A".$i, "접수일")
					 ->setCellValue("B".$i, "동")
					 ->setCellValue("C".$i, "호")
					 ->setCellValue("D".$i, "취득자1")
					 ->setCellValue("E".$i, "취득자2")
					 ->setCellValue("F".$i, "보수액")
					 ->setCellValue("G".$i, "공과금")
					 ->setCellValue("H".$i, "입금")
					 ->setCellValue("I".$i, "정산(환불금)")
					 ->setCellValue("J".$i, "이전영수증출력일")
					 ->setCellValue("K".$i, "현금영수증발행일")
					 ->setCellValue("L".$i, "비고");

		$i++;
		while($row = $stmt->fetch()){

		  $bosu   = f_jung($row[aq1])+f_jung($row[ar1])+f_jung($row[as1])+f_jung($row[at1]);
		  $gongga = f_jung($row[aj1])+f_jung($row[ak1])+f_jung($row[al1])+f_jung($row[am1])+f_jung($row[an1])+f_jung($row[ao1])+f_jung($row[ap1]);

			 $objPHPExcel->setActiveSheetIndex(0)
						 ->setCellValue("A".$i, f_date($row[g1]))
						 ->setCellValue("B".$i, $row[h1])
						 ->setCellValue("C".$i, $row[i1])
						 ->setCellValue("D".$i, $row[j1])
						 ->setCellValue("E".$i, $row[m1])
						 ->setCellValue("F".$i, f_money($bosu))
						 ->setCellValue("G".$i, f_money($gongga))
						 ->setCellValue("H".$i, f_money($bosu+$gongga))
						 ->setCellValue("I".$i, f_money($row[ai1]))
						 ->setCellValue("J".$i, f_money($row[refund_fee]))
						 ->setCellValue("K".$i, f_date($row[hy_b_date]))
						 ->setCellValue("L".$i, $row[ijp_s_memo]);
				 ;
			$i++;

		}
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "이전양식_엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

	?>