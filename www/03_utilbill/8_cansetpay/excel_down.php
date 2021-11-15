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
	
  // 정산비고
	if($memo!=""){
		$wherequery.= " and ae1 like '%{$memo}%' ";
	}


	$wherequery.= $imss;

	//echo $wherequery;
	$rows_total = db_count_all($board_dbname,$wherequery);


	$objPHPExcel = new PHPExcel();

	$sql = "select *, if(m1='',j1,CONCAT(j1, ',', m1)) as jm1, DATEDIFF(date_format(NOW(),'%Y%m%d'),tax_end_date) as ft_date from $board_dbname $wherequery  order by  cast(a1 as unsigned) asc ";
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(50);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "동")
                 ->setCellValue("C".$i, "호")
                 ->setCellValue("D".$i, "취득자")
                 ->setCellValue("E".$i, "(예상)등기접수일")
                 ->setCellValue("F".$i, "등기접수일")
                 ->setCellValue("G".$i, "설갯수")
                 ->setCellValue("H".$i, "신탁여부")
                 ->setCellValue("I".$i, "말소등록세")
                 ->setCellValue("J".$i, "변경등록세")
                 ->setCellValue("K".$i, "말등신고")
                 ->setCellValue("L".$i, "말등수령")
                 ->setCellValue("M".$i, "말등납부")
                 ->setCellValue("N".$i, "변등신고")
                 ->setCellValue("O".$i, "변등수령")
                 ->setCellValue("P".$i, "변등납부")
                 ->setCellValue("Q".$i, "정산비고")
                 ;
	$i++;

	while($row = $stmt->fetch()){
		
		if($row[al1_hope_yn]=="y"||$row[al1_imp_yn]=="y"){
			$s_must = $row[total_sum]-$row[al1];
			if(($row[total_sum]-$row[al1]-$row[ai1])!=0){
				$s_result = $row[total_sum]-$row[al1]-$row[ai1];
			} else {
				$s_result = "ok";
			}
		} else {
			$s_must = $row[total_sum];
			if(($row[total_sum]-$row[ai1])!=0){
				$s_result = $row[total_sum]-$row[ai1];
			} else {
				$s_result = "ok";
			}
		}

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $i-2)
                 ->setCellValue("B".$i, $row[h1])
                 ->setCellValue("C".$i, $row[i1])
                 ->setCellValue("D".$i, $row[jm1])
                 ->setCellValue("E".$i, $row[pred_g1])
                 ->setCellValue("F".$i, $row[g1])
                 ->setCellValue("G".$i, $row[f1])
                 ->setCellValue("H".$i, f_turst_gubun_value($row[h_idx]))
								 ->setCellValue("I".$i, $row[ao1])
								 ->setCellValue("J".$i, $row[ao1_toji])
								 ->setCellValue("K".$i, $row[ao1_dec_date])
								 ->setCellValue("L".$i, $row[ao1_rec_date])
								 ->setCellValue("M".$i, $row[ao1_pay_date])
								 ->setCellValue("N".$i, $row[ao1_tdec_date])
								 ->setCellValue("O".$i, $row[ao1_trec_date])
								 ->setCellValue("P".$i, $row[ao1_tpay_date])
								 ->setCellValue("Q".$i, $row[ae1])
                 ;
	$i++;
	}

	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "말소-변경등록세신고납부_엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
