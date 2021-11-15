<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");
	include ("../include/excel.inc");

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
	$bank_null_ch	=	trim($_REQUEST[bank_null_ch]);

	$h1					=	trim($_REQUEST[h1]);
	$i1					=	trim($_REQUEST[i1]);
	$j1					=	trim($_REQUEST[j1]);
	$memo				=	trim($_REQUEST[memo]);
	$refund_y			=	trim($_REQUEST[refund_y]);
	$kikan2_null_ch		=	trim($_REQUEST[kikan2_null_ch]);

	if($target_date=="") $target_date="g1";
	if($s_date=="")		$s_date=date("Ymd");
	if($e_date=="")		$e_date=date("Ymd");

	$list_num		=	trim($_REQUEST[list_num]);
	$page			=	trim($_REQUEST[page]);
	$view_num		=	trim($_REQUEST[list_num]);	//한라인에 몇개를 출력할건지//
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	20;					//리스트 갯수

	$wherequery = " where 1=1  ";

	if($h_idx!="")			$wherequery.= " and h_idx = ".$h_idx." ";
	if($bank_null_ch=="Y")	$wherequery.= " and d1 <> '' ";
	if($bank_code!="")		$wherequery.= " and d1 = '".$bank_code."' ";
	if($jijum_code!="")		$wherequery.= " and e1 = '".$jijum_code."' ";
	if($h1!="")				$wherequery.= " and h1 = '".$h1."' ";
	if($i1!="")				$wherequery.= " and i1 = '".$i1."' ";
	if($j1!="")				$wherequery.= " and (j1 like '%{$j1}%' or m1 like '%{$j1}%')";
	
	if($target_date!=""){
		if(($s_date!="")&&($e_date!="")){
			$imsi = "";
			if(($target_date=="sjp_s_date")||($target_date=="sjp_j_date")||($target_date=="sjj_w_date")||($target_date=="sjp_b_date")){  //설정일때
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
			if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")){ //설정일때
				$imsi = " and a1 in (select a1 from tbl_suljung where  ({$target_date2}='' or {$target_date2} is null ))";
			}else{
				if($target_date2!="") {$imsi = " and ({$target_date2}='' or {$target_date2} is null )";}
			}
			$wherequery.=$imsi;
}else{
	if($target_date2!=""){
		if(($s_date2!="")&&($e_date2!="")){
			$imsi = "";
			if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")){ //설정일때
				$imsi = " and a1 in (select a1 from tbl_suljung where {$target_date2} between {$s_date2} and {$e_date2} )";
			}else{
				if($target_date2!="") {$imsi = " and {$target_date2} between ";}
				if($s_date2==$e_date2) {$imsi.= " {$s_date2} and {$e_date2} ";}
				if($s_date2!=$e_date2) {$imsi.= " {$s_date2} and {$e_date2} ";}
			}
			$wherequery.=$imsi;
		}
	}
}

	if($refund_y=="1"){ //환불대상자
			$wherequery.= " and refund_fee>0 ";
	}
	if($refund_y=="2"){ //환불대상자(계좌미입력)
			$wherequery.= " and refund_account is null ";
	}
	if($refund_y=="3"){ //환불없슴
			$wherequery.= " and refund_fee=0 ";
	}
	if($refund_y=="4"){  //추가입금대상자
			$wherequery.= " and refund_fee<0 ";
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


	$objPHPExcel = new PHPExcel();
	
	$sql = "select * from $board_dbname  $wherequery order by  cast(h1 as unsigned) asc,cast(i1 as unsigned) asc";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "고객고유번호")
                 ->setCellValue("C".$i, "배포일")
                 ->setCellValue("D".$i, "취득자1")
                 ->setCellValue("E".$i, "취득자2")
                 ->setCellValue("F".$i, "전화번호")
                 ->setCellValue("G".$i, "환불금")
                 ->setCellValue("H".$i, "환불은행")
                 ->setCellValue("I".$i, "환불계좌번호")
                 ->setCellValue("J".$i, "환불예금주")
                 ->setCellValue("K".$i, "환불금액")
                 ->setCellValue("L".$i, "환불일")
                 ->setCellValue("M".$i, "처리자")
                 ->setCellValue("N".$i, "추가대상여부")
                 ->setCellValue("O".$i, "비고")
		 ;
	$i++;

	while($row = $stmt->fetch()){

	$objPHPExcel->setActiveSheetIndex(0)
			 ->setCellValue("A".$i, $i-1)
			 ->setCellValue("B".$i, $row[a1])
			 ->setCellValue("C".$i, f_date($row[ijp_b_date]))
			 ->setCellValue("D".$i, $row[j1])
			 ->setCellValue("E".$i, $row[m1])
			 ->setCellValue("F".$i, $row[p1])
			 ->setCellValue("G".$i, $row[refund_fee])
			 ->setCellValue("H".$i, $row[refund_bank])
			 ->setCellValue("I".$i, $row[refund_account])
			 ->setCellValue("J".$i, $row[refund_name])
			 ->setCellValue("K".$i, $row[refund_money])
			 ->setCellValue("L".$i, f_date($row[refund_date]))
			 ->setCellValue("M".$i, f_id_value($row[refund_id]))
			 ->setCellValue("N".$i, "")
			 ->setCellValue("O".$i, $row[refund_end_memo]);
	$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "환불금내역");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
