<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");
	include ("../include/excel.inc");
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname		=	" tbl_junib j cross join tbl_suljung b on j.a1=b.a1 ";

	$h_idx			=	trim($_REQUEST[h_idx]);
	$target_date	=	trim($_REQUEST[target_date]);
	$s_date			=	trim($_REQUEST[s_date]);
	$e_date			=	trim($_REQUEST[e_date]);

	$target_date2	=	trim($_REQUEST[target_date2]);
	$s_date2		=	trim($_REQUEST[s_date2]);
	$e_date2		=	trim($_REQUEST[e_date2]);
	$bank_code		=	trim($_REQUEST[bank_code]);
	$jijum_code		=	trim($_REQUEST[jijum_code]);

	$cg_daesang		=	trim($_REQUEST[cg_daesang]);
	$kikan2_null_ch	=	trim($_REQUEST[kikan2_null_ch]);

	$h1				=	trim($_REQUEST[h1]);
	$i1				=	trim($_REQUEST[i1]);
	$j1				=	trim($_REQUEST[j1]);
	$memo			=	trim($_REQUEST[memo]);

	if($target_date=="")	$target_date="g1";

	$sql = "SELECT max( cast( sjb_c_date AS unsigned ) ) AS mm FROM tbl_suljung WHERE (sjb_c_date IS NOT NULL AND sjb_c_date <> '') LIMIT 1";
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
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	100;					//리스트 갯수

	$wherequery = " where 1=1  ";

	if($h_idx!="")			$wherequery.= " and j.h_idx = ".$h_idx." ";
	if($bank_code!="")		$wherequery.= " and b.bank_code = '".$bank_code."' ";
	if($jijum_code!="")		$wherequery.= " and b.jijum_code = '".$jijum_code."' ";
	if($h1!="")			$wherequery.= " and j.h1 = '".$h1."' ";
	if($i1!="")			$wherequery.= " and j.i1 = '".$i1."' ";
	if($j1!="")			$wherequery.= " and (j.j1 like '%{$j1}%' or j.m1 like '%{$j1}%')";

	if($cg_daesang=="Y")	$wherequery.= " and b.cg_daesang='Y' ";
	
	if($target_date!=""){

		if($target_date=="100") {
			$imsi = " and suljung_update in (SELECT max(suljung_update) FROM tbl_suljung where suljung_update<>'' GROUP BY suljung_update ORDER BY suljung_update DESC)";
			$wherequery.=$imsi;
		}else if(($s_date!="")&&($e_date!="")){
			$imsi = "";
			if(($target_date=="sjp_s_date")||($target_date=="sjp_j_date")||($target_date=="sjj_w_date")||($target_date=="sjp_b_date")||($target_date=="sg_b_date")||($target_date=="sjb_c_date")){  //설정일때
				$imsi = " and b.{$target_date} between {$s_date} and {$e_date} ";
			}else{
				$imsi = " and j.{$target_date} between {$s_date} and {$e_date} ";
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
				if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")||($target_date2=="sg_b_date")||($target_date=="sjb_c_date")){ //설정일때
					$imsi = " and b.{$target_date2} between {$s_date2} and {$e_date2} ";
				}else if($target_date2=="hy_b_date"){
					$imsi = " and j.{$target_date2} between {$s_date2} and {$e_date2} ";
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

	//echo "<br><br>".$wherequery;
	$rows_total = db_count_all($board_dbname,$wherequery);

	$sql = "select j.*,b.* from  tbl_junib j cross join tbl_suljung b on j.a1=b.a1   $wherequery order by  cast(j.h1 as unsigned) asc,cast(j.i1 as unsigned) asc ";
	//echo "<br><br><br>----------".$sql."-------------";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();


	if($rows > 0){

		$i=1;

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('r')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(50);


		$objPHPExcel->setActiveSheetIndex(0)
					 ->setCellValue("A".$i, "접수일")
					 ->setCellValue("B".$i, "은행")
					 ->setCellValue("C".$i, "지점")
					 ->setCellValue("D".$i, "동")
					 ->setCellValue("E".$i, "호")
					 ->setCellValue("F".$i, "채무자")
					 ->setCellValue("G".$i, "채권최고액")
					 ->setCellValue("H".$i, "보수액")
					 ->setCellValue("I".$i, "부가세")
					 ->setCellValue("J".$i, "보수액합계")
					 ->setCellValue("K".$i, "공과금")
					 ->setCellValue("L".$i, "총비용")
					 ->setCellValue("M".$i, "1회청구여부")
					 ->setCellValue("N".$i, "설정비용내역서출력일")
					 ->setCellValue("O".$i, "세금계산서발행일")
					 ->setCellValue("P".$i, "국토교통부")
					 ->setCellValue("Q".$i, "비고")
					 ->setCellValue("R".$i, "은행비고");

		$i++;
		while($row = $stmt->fetch()){

			 $objPHPExcel->setActiveSheetIndex(0)
						 ->setCellValue("A".$i, f_date($row[g1]))
						 ->setCellValue("B".$i, f_bank_name($row[bank_code]))
						 ->setCellValue("C".$i, f_jijum_name($row[jijum_code]))
						 ->setCellValue("D".$i, $row[h1])
						 ->setCellValue("E".$i, $row[i1])
						 ->setCellValue("F".$i, $row["aw".$row[suljung_no]])
						 ->setCellValue("G".$i, $row[chaekwon_max])
						 ->setCellValue("H".$i, $row[bosu_price])
						 ->setCellValue("I".$i, $row[bosu_price_vat])
						 ->setCellValue("J".$i, $row[bosu_price]+$row[bosu_price_vat])
						 ->setCellValue("K".$i, $row[gongga_price])
						 ->setCellValue("L".$i, $row[bosu_price]+$row[bosu_price_vat]+$row[gongga_price])
						 ->setCellValue("M".$i, f_Y_value($row[cg_daesang]))
						 ->setCellValue("N".$i, f_date($row[sjb_c_date]))
						 ->setCellValue("O".$i, f_date($row[sg_b_date]))
						 ->setCellValue("P".$i, f_gukto($row[gukto]))
						 ->setCellValue("Q".$i, $row[ijp_s_memo])
						 ->setCellValue("R".$i, $row[au1])
				 ;
			$i++;

		}
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "설정양식_엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

	?>