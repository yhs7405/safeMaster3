<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");
	include ("../../include/excel.inc");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$h_idx				=	trim($_REQUEST[h_idx]);
	$target_date		=	trim($_REQUEST[target_date]);
	$s_date				=	trim($_REQUEST[s_date]);
	$e_date				=	trim($_REQUEST[e_date]);

	$target_date2		=	trim($_REQUEST[target_date2]);
	$s_date2			=	trim($_REQUEST[s_date2]);
	$e_date2			=	trim($_REQUEST[e_date2]);
	$bank_code			=	trim($_REQUEST[bank_code]);
	$jijum_code			=	trim($_REQUEST[jijum_code]);

	$bank_null_ch	=	trim($_REQUEST[bank_null_ch]);
	$kikan2_null_ch		=	trim($_REQUEST[kikan2_null_ch]);
	$h1					=	trim($_REQUEST[h1]);
	$i1					=	trim($_REQUEST[i1]);
	$j1					=	trim($_REQUEST[j1]);
	$memo				=	trim($_REQUEST[memo]);

	if($target_date=="") $target_date="1";
	if($s_date=="")		$s_date=date("Ymd");
	if($e_date=="")		$e_date=date("Ymd");

	$list_num		=	trim($_REQUEST[list_num]);
	$page			=	trim($_REQUEST[page]);
	$view_num		=	trim($_REQUEST[list_num]);	//한라인에 몇개를 출력할건지//
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	20;					//리스트 갯수

	$wherequery = " where 1=1  ";

	if($h_idx!="")			$wherequery.= " and i.h_idx = ".$h_idx." ";
	if($bank_null_ch=="Y")	$wherequery.= " and i.d1 <> '' ";
	if($bank_code!="")		$wherequery.= " and i.d1 = '".$bank_code."' ";
	if($jijum_code!="")		$wherequery.= " and i.e1 = '".$jijum_code."' ";
	if($h1!="")				$wherequery.= " and i.h1 = '".$h1."' ";
	if($i1!="")				$wherequery.= " and i.i1 = '".$i1."' ";
	if($j1!="")				$wherequery.= " and (i.j1 like '%{$j1}%' or i.m1 like '%{$j1}%')";
	
	if($target_date!=""){
		if(($s_date!="")&&($e_date!="")){
			$imsi = "";
			if(($target_date=="sjp_s_date")||($target_date=="sjp_j_date")||($target_date=="sjj_w_date")||($target_date=="sjp_b_date")){  //설정일때
				$imsi = " and s.{$target_date} between {$s_date} and {$e_date} and i.a1 in (select a1 from tbl_suljung where {$target_date} between {$s_date} and {$e_date} )";
			}else{
				if($target_date!="") {$imsi = " and {$target_date} between ";}
				if($s_date==$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
				if($s_date!=$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
			}
			$wherequery.=$imsi;
		}
	}

if($kikan2_null_ch=="Y"){
			if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")){ //설정일때
				$imsi = " and i.a1 in (select a1 from tbl_suljung where  ({$target_date2}='' or {$target_date2} is null ))";
			}else{
				if($target_date2!="") {$imsi = " and ({$target_date2}='' or {$target_date2} is null )";}
			}
			//echo "<br><br>".$imsi;
			$wherequery.=$imsi;
}else{
	if($target_date2!=""){
		if(($s_date2!="")&&($e_date2!="")){
			$imsi = "";
			if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")){ //설정일때
				$imsi = " and s.{$target_date2} between {$s_date2} and {$e_date2} and i.a1 in (select a1 from tbl_suljung where {$target_date2} between {$s_date2} and {$e_date2} )";
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
		$wherequery.= " and (i.a1 in ( (select a1 from tbl_junib where (memo like '%{$memo}%') or (ijp_s_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_s_memo  like '%{$memo}%') or (ijp_j_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_j_memo  like '%{$memo}%') or (ijj_w_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjj_w_memo  like '%{$memo}%') or (ijp_b_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_b_memo  like '%{$memo}%') or (refund_memo  like '%{$memo}%') ";
		$wherequery.= "      or (refund_end_memo like '%{$memo}%') or (refund_memo  like '%{$memo}%')))  or ";
		$wherequery.= "      i.a1 in (select a1 from tbl_sugum where sugum_memo like '%{$memo}%') )";
	}

	$objPHPExcel = new PHPExcel();

	$sql = "select i.g1,i.g1,i.e1,i.h1,i.i1,i.j1,i.k1,i.m1,i.aw1,i.aw1_jumin,i.aw2,i.aw2_jumin,i.aw3,i.aw3_jumin,i.aw4,i.aw4_jumin,s.suljung_no,s.sjp_s_date,s.chaekwon_max,s.bank_code,s.jijum_code from tbl_junib i cross join tbl_suljung s on i.a1=s.a1  $wherequery order by   cast(i.h1 as unsigned) asc,cast(i.i1 as unsigned) asc ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();


	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "은행명")
                 ->setCellValue("B".$i, "지점명")
                 ->setCellValue("C".$i, "동")
                 ->setCellValue("D".$i, "호")
                 ->setCellValue("E".$i, "채무자")
                 ->setCellValue("F".$i, "주민번호")
                 ->setCellValue("G".$i, "채권최고액")
                 ->setCellValue("H".$i, "등기접수일");
	$i++;

	while($row = $stmt->fetch()){

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, f_bank_name($row[bank_code]))
                 ->setCellValue("B".$i, f_jijum_name($row[jijum_code]))
                 ->setCellValue("C".$i, $row[h1])
                 ->setCellValue("D".$i, $row[i1])
                 ->setCellValue("E".$i, $row["aw".$row[suljung_no]])
                 ->setCellValue("F".$i, f_jumin_valid($row["aw".$row[suljung_no]."_jumin"]))
                 ->setCellValue("G".$i, f_money($row[chaekwon_max]))
                 ->setCellValue("H".$i, f_date($row[g1]));
	$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "필증목록");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
