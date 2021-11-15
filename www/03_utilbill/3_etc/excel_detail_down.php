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


	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "설정채권요청일")
                 ->setCellValue("B".$i, "설정채권발행일")
                 ->setCellValue("C".$i, "고객고유번호")
                 ->setCellValue("D".$i, "종류")
                 ->setCellValue("E".$i, "이름")
                 ->setCellValue("F".$i, "주민번호")
                 ->setCellValue("G".$i, "매입액")
                 ;
	$i++;

	while($row = $stmt->fetch()){
//		$j=1;
			$j = $row[f1];
			if($j==0){
				$j1 = "";
				$j2 = "";
				$j3 = "";
				$j4 = "";
				
		     $objPHPExcel->setActiveSheetIndex(0)
		                 ->setCellValue("A".$i, $row[pre_spur_date])
		                 ->setCellValue("B".$i, "")
		                 ->setCellValue("C".$i, $row[a1])
		                 ->setCellValue("D".$i, "0")
		                 ->setCellValue("E".$i, "")
		                 ->setCellValue("F".$i, "")
		                 ->setCellValue("G".$i, "")
		                 ;
				$i++;
			} else {
			
				if($j>=1){
					$j1 = $row[aj1_rec1_date];
					$j2 = $row[aw1];
					$j3 = $row[aw1jumin];
					$j4 = $row[ba1];
					if(($row[aw1_jumin]==$row[aw2_jumin])||($row[aw1_jumin]==$row[aw3_jumin])||($row[aw1_jumin]==$row[aw4_jumin])){
						$objPHPExcel->getActiveSheet()->getStyle("F".$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("FFFF0000");
					}

			     $objPHPExcel->setActiveSheetIndex(0)
			                 ->setCellValue("A".$i, $row[pre_spur_date])
			                 ->setCellValue("B".$i, "")
			                 ->setCellValue("C".$i, $row[a1])
			                 ->setCellValue("D".$i, "1")
			                 ->setCellValue("E".$i, $row[aw1])
			                 ->setCellValue("F".$i, $row[aw1_jumin])
			                 ->setCellValue("G".$i, $row[ba1])
			                 ;
					$i++;
				} 
				if($j>=2){
					$j1 = $row[aj1_rec2_date];
					$j2 = $row[aw2];
					$j3 = $row[aw2jumin];
					$j4 = $row[bb1];
					if(($row[aw2_jumin]==$row[aw1_jumin])||($row[aw2_jumin]==$row[aw3_jumin])||($row[aw2_jumin]==$row[aw4_jumin])){
						$objPHPExcel->getActiveSheet()->getStyle("F".$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("FFFF0000");
					}

			     $objPHPExcel->setActiveSheetIndex(0)
			                 ->setCellValue("A".$i, $row[pre_spur_date])
			                 ->setCellValue("B".$i, "")
			                 ->setCellValue("C".$i, $row[a1])
			                 ->setCellValue("D".$i, "2")
			                 ->setCellValue("E".$i, $row[aw2])
			                 ->setCellValue("F".$i, $row[aw2_jumin])
			                 ->setCellValue("G".$i, $row[bb1])
			                 ;
					$i++;
				} 
				if($j>=3){
					$j1 = $row[aj1_rec3_date];
					$j2 = $row[aw3];
					$j3 = $row[aw3jumin];
					$j4 = $row[bc1];
					if(($row[aw3_jumin]==$row[aw1_jumin])||($row[aw3_jumin]==$row[aw2_jumin])||($row[aw3_jumin]==$row[aw4_jumin])){
						$objPHPExcel->getActiveSheet()->getStyle("F".$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("FFFF0000");
					}

			     $objPHPExcel->setActiveSheetIndex(0)
			                 ->setCellValue("A".$i, $row[pre_spur_date])
			                 ->setCellValue("B".$i, "")
			                 ->setCellValue("C".$i, $row[a1])
			                 ->setCellValue("D".$i, "3")
			                 ->setCellValue("E".$i, $row[aw3])
			                 ->setCellValue("F".$i, $row[aw3_jumin])
			                 ->setCellValue("G".$i, $row[bc1])
			                 ;
					$i++;
				} 
				if($j==4){
					$j1 = $row[aj1_rec4_date];
					$j2 = $row[aw4];
					$j3 = $row[aw4jumin];
					$j4 = $row[bd1];
					if(($row[aw4_jumin]==$row[aw1_jumin])||($row[aw4_jumin]==$row[aw2_jumin])||($row[aw4_jumin]==$row[aw3_jumin])){
						$objPHPExcel->getActiveSheet()->getStyle("F".$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("FFFF0000");
					}

			     $objPHPExcel->setActiveSheetIndex(0)
			                 ->setCellValue("A".$i, $row[pre_spur_date])
			                 ->setCellValue("B".$i, "")
			                 ->setCellValue("C".$i, $row[a1])
			                 ->setCellValue("D".$i, "4")
			                 ->setCellValue("E".$i, $row[aw4])
			                 ->setCellValue("F".$i, $row[aw4_jumin])
			                 ->setCellValue("G".$i, $row[bd1])
			                 ;
					$i++;
				}
				
			}

//			if(($row[aw1jumin]==$row[aw2jumin])||($row[aw1jumin]==$row[aw3jumin])||($row[aw1jumin]==$row[aw4jumin])){
//				$objPHPExcel->getActiveSheet()->getStyle("F".$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("FF0000FF");
//			}
	}

	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "채권산정(설정채권)_설정채권매입용엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
