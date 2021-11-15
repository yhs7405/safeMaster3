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
		$wherequery.= " and (au1 like '%{$memo}%' or ae1 like '%{$memo}%') ";
	}

  // 설정갯수
	if($target_gubun!=""){
		$wherequery.= " and f1 = '{$target_gubun}'";
	}


	$wherequery.= $imss;

	//echo $wherequery;
	$rows_total = db_count_all($board_dbname,$wherequery);


	$objPHPExcel = new PHPExcel();

	$sql = "select *, if(m1='',j1,CONCAT(j1, ',', m1)) as jm1 from $board_dbname $wherequery  order by  cast(a1 as unsigned) asc ";
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(20);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "동")
                 ->setCellValue("C".$i, "호")
                 ->setCellValue("D".$i, "취득자")
                 ->setCellValue("E".$i, "(예상)등기접수일")
                 ->setCellValue("F".$i, "등기접수일")
                 ->setCellValue("G".$i, "은행1")
                 ->setCellValue("H".$i, "지점1")
                 ->setCellValue("I".$i, "설채요청")
                 ->setCellValue("J".$i, "채최확인1")
                 ->setCellValue("K".$i, "채최확인2")
                 ->setCellValue("L".$i, "채최1")
                 ->setCellValue("M".$i, "채최2")
                 ->setCellValue("N".$i, "채최3")
                 ->setCellValue("O".$i, "채최4")
                 ->setCellValue("P".$i, "설정감면")
                 ->setCellValue("Q".$i, "설1매입")
                 ->setCellValue("R".$i, "설2매입")
                 ->setCellValue("S".$i, "설3매입")
                 ->setCellValue("T".$i, "설4매입")
                 ->setCellValue("U".$i, "본인채권")
                 ->setCellValue("V".$i, "(설)본인채권매입금액1")
                 ->setCellValue("W".$i, "(설)본인채권매입금액2")
                 ->setCellValue("X".$i, "은행비고")
                 ->setCellValue("Y".$i, "정산비고")
                 ->setCellValue("Z".$i, "취득자1")
                 ->setCellValue("AA".$i, "주민번호")
                 ->setCellValue("AB".$i, "취득자2")
                 ->setCellValue("AC".$i, "주민번호")
                 ;
	$i++;

	while($row = $stmt->fetch()){

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $i-2)
                 ->setCellValue("B".$i, $row[h1])
                 ->setCellValue("C".$i, $row[i1])
                 ->setCellValue("D".$i, $row[jm1])
                 ->setCellValue("E".$i, $row[pred_g1])
                 ->setCellValue("F".$i, $row[g1])
                 ->setCellValue("G".$i, f_bank_name($row[d1]))
                 ->setCellValue("H".$i, f_jijum_name($row[e1]))
								 ->setCellValue("I".$i, $row[pre_spur_date])
								 ->setCellValue("J".$i, $row[bond_1conf_date])
								 ->setCellValue("K".$i, $row[bond_2conf_date])
								 ->setCellValue("L".$i, $row[av1])
								 ->setCellValue("M".$i, $row[ax1])
								 ->setCellValue("N".$i, $row[ay1])
								 ->setCellValue("O".$i, $row[az1])
								 ->setCellValue("P".$i, f_tax_cut_cause_value($row[tax_cut_cause]))
								 ->setCellValue("Q".$i, $row[ba1])
								 ->setCellValue("R".$i, $row[bb1])
								 ->setCellValue("S".$i, $row[bc1])
								 ->setCellValue("T".$i, $row[bd1])
								 ->setCellValue("U".$i, $row[me_spur_yn])
								 ->setCellValue("V".$i, $row[me_sprepur_cost1])
								 ->setCellValue("W".$i, $row[me_sprepur_cost2])
								 ->setCellValue("X".$i, $row[au1])
								 ->setCellValue("Y".$i, $row[ae1])
								 ->setCellValue("Z".$i, $row[j1])
								 ->setCellValue("AA".$i,$row[k1])
								 ->setCellValue("AB".$i,$row[m1])
								 ->setCellValue("AC".$i,$row[n1])
                 ;
	$i++;
	}

	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "채권산정(설정채권)_엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
