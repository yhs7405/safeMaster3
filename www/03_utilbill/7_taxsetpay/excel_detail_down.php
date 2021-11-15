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
		$wherequery.= " and  ( ae1 like '%{$memo}%' or au1 like '%{$memo}%' )";
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(70);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(70);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "종류")
                 ->setCellValue("B".$i, "건물동")
                 ->setCellValue("C".$i, "건물호")
                 ->setCellValue("D".$i, "구분")
                 ->setCellValue("E".$i, "주민(법인)번호")
                 ->setCellValue("F".$i, "성명")
                 ->setCellValue("G".$i, "과세표준")
                 ->setCellValue("H".$i, "물건지")
                 ->setCellValue("I".$i, "은행주소")
                 ->setCellValue("J".$i, "등록세")
                 ;
	$i++;

	while($row = $stmt->fetch()){
		
			$j = $row[f1];
			if($j==0){
				
		     $objPHPExcel->setActiveSheetIndex(0)
		                 ->setCellValue("A".$i, "0")
		                 ->setCellValue("B".$i, $row[h1])
		                 ->setCellValue("C".$i, $row[i1])
		                 ->setCellValue("D".$i, "집합건물")
		                 ->setCellValue("E".$i, "")
		                 ->setCellValue("F".$i, "")
		                 ->setCellValue("G".$i, "")
		                 ->setCellValue("H".$i, "")
		                 ->setCellValue("I".$i, "")
		                 ->setCellValue("J".$i, "")
		                 ;
				$i++;
			} else {
			
				if($j>=1){
					
					//설정정보 반환
					$sql2 = "select * from tbl_suljung where a1='{$row[a1]}' and suljung_no='1' limit 1 ";
					$kk1 = db_query_value($sql2);
					if($kk1[a1]!=""){
						//현장단지정보 반환
						$sql2 = "select * from tbl_hyunjang_danji_info where h_idx='{$h_idx}' and danji_name='{$row[b1]}' limit 1 ";
						$kk2 = db_query_value($sql2);

						//은행정보 반환
						$sql2 = "select * from tbl_bank_info where bank_code='{$kk1[bank_code]}' limit 1 ";
						$kk3 = db_query_value($sql2);

						//은행지점정보 반환
						$sql2 = "select * from tbl_bank_jijum where bank_code='{$kk1[bank_code]}' and jijum_code='{$kk1[jijum_code]}' limit 1 ";
						$kk4 = db_query_value($sql2);
						if(($kk1[jijum_code]==$jijum_code&&$jijum_code!="")||($kk1[bank_code]==$bank_code&&$bank_code!="")||($jijum_code==""&&$bank_code=="")){

				     $objPHPExcel->setActiveSheetIndex(0)
				                 ->setCellValue("A".$i, "1")
				                 ->setCellValue("B".$i, $row[h1])
				                 ->setCellValue("C".$i, $row[i1])
				                 ->setCellValue("D".$i, "집합건물")
				                 ->setCellValue("E".$i, $kk3[bubin_no])
				                 ->setCellValue("F".$i, $kk3[bank_name])
				                 ->setCellValue("G".$i, $row[av1])
				                 ->setCellValue("H".$i, $kk2[doro_addr]." ".$row[h1]."동 ".$row[i1]."호 (".$row[road_dong].", ".$row[road_building_name].")")
				                 ->setCellValue("I".$i, $kk4[addr])
				                 ->setCellValue("J".$i, $row[av1_tax])
				                 ;
						$i++;
						}
					}
				} 
				if($j>=2){
					//설정정보 반환
					$sql2 = "select * from tbl_suljung where a1='{$row[a1]}' and suljung_no='2' limit 1 ";
					$kk1 = db_query_value($sql2);
					if($kk1[a1]!=""){
						//현장단지정보 반환
						$sql2 = "select * from tbl_hyunjang_danji_info where h_idx='{$h_idx}' and danji_name='{$row[b1]}' limit 1 ";
						$kk2 = db_query_value($sql2);

						//은행정보 반환
						$sql2 = "select * from tbl_bank_info where bank_code='{$kk1[bank_code]}' limit 1 ";
						$kk3 = db_query_value($sql2);

						//은행지점정보 반환
						$sql2 = "select * from tbl_bank_jijum where bank_code='{$kk1[bank_code]}' and jijum_code='{$kk1[jijum_code]}' limit 1 ";
						$kk4 = db_query_value($sql2);
						if(($kk1[jijum_code]==$jijum_code&&$jijum_code!="")||($kk1[bank_code]==$bank_code&&$bank_code!="")||($jijum_code==""&&$bank_code=="")){

				     $objPHPExcel->setActiveSheetIndex(0)
				                 ->setCellValue("A".$i, "2")
				                 ->setCellValue("B".$i, $row[h1])
				                 ->setCellValue("C".$i, $row[i1])
				                 ->setCellValue("D".$i, "집합건물")
				                 ->setCellValue("E".$i, $kk3[bubin_no])
				                 ->setCellValue("F".$i, $kk3[bank_name])
				                 ->setCellValue("G".$i, $row[ax1])
				                 ->setCellValue("H".$i, $kk2[doro_addr]." ".$row[h1]."동 ".$row[i1]."호 (".$row[road_dong].", ".$row[road_building_name].")")
				                 ->setCellValue("I".$i, $kk4[addr])
				                 ->setCellValue("J".$i, $row[ax1_tax])
				                 ;
						$i++;
						}
					}
				} 
				if($j>=3){
					//설정정보 반환
					$sql2 = "select * from tbl_suljung where a1='{$row[a1]}' and suljung_no='2' limit 1 ";
					$kk1 = db_query_value($sql2);
					if($kk1[a1]!=""){
						//현장단지정보 반환
						$sql2 = "select * from tbl_hyunjang_danji_info where h_idx='{$h_idx}' and danji_name='{$row[b1]}' limit 1 ";
						$kk2 = db_query_value($sql2);

						//은행정보 반환
						$sql2 = "select * from tbl_bank_info where bank_code='{$kk1[bank_code]}' limit 1 ";
						$kk3 = db_query_value($sql2);

						//은행지점정보 반환
						$sql2 = "select * from tbl_bank_jijum where bank_code='{$kk1[bank_code]}' and jijum_code='{$kk1[jijum_code]}' limit 1 ";
						$kk4 = db_query_value($sql2);
						if(($kk1[jijum_code]==$jijum_code&&$jijum_code!="")||($kk1[bank_code]==$bank_code&&$bank_code!="")||($jijum_code==""&&$bank_code=="")){

				     $objPHPExcel->setActiveSheetIndex(0)
				                 ->setCellValue("A".$i, "3")
				                 ->setCellValue("B".$i, $row[h1])
				                 ->setCellValue("C".$i, $row[i1])
				                 ->setCellValue("D".$i, "집합건물")
				                 ->setCellValue("E".$i, $kk3[bubin_no])
				                 ->setCellValue("F".$i, $kk3[bank_name])
				                 ->setCellValue("G".$i, $row[ay1])
				                 ->setCellValue("H".$i, $kk2[doro_addr]." ".$row[h1]."동 ".$row[i1]."호 (".$row[road_dong].", ".$row[road_building_name].")")
				                 ->setCellValue("I".$i, $kk4[addr])
				                 ->setCellValue("J".$i, $row[ay1_tax])
				                 ;
						$i++;
						}
					}
				} 
				if($j==4){
					//설정정보 반환
					$sql2 = "select * from tbl_suljung where a1='{$row[a1]}' and suljung_no='2' limit 1 ";
					$kk1 = db_query_value($sql2);
					if($kk1[a1]!=""){
						//현장단지정보 반환
						$sql2 = "select * from tbl_hyunjang_danji_info where h_idx='{$h_idx}' and danji_name='{$row[b1]}' limit 1 ";
						$kk2 = db_query_value($sql2);

						//은행정보 반환
						$sql2 = "select * from tbl_bank_info where bank_code='{$kk1[bank_code]}' limit 1 ";
						$kk3 = db_query_value($sql2);

						//은행지점정보 반환
						$sql2 = "select * from tbl_bank_jijum where bank_code='{$kk1[bank_code]}' and jijum_code='{$kk1[jijum_code]}' limit 1 ";
						$kk4 = db_query_value($sql2);
						if(($kk1[jijum_code]==$jijum_code&&$jijum_code!="")||($kk1[bank_code]==$bank_code&&$bank_code!="")||($jijum_code==""&&$bank_code=="")){

				     $objPHPExcel->setActiveSheetIndex(0)
				                 ->setCellValue("A".$i, "4")
				                 ->setCellValue("B".$i, $row[h1])
				                 ->setCellValue("C".$i, $row[i1])
				                 ->setCellValue("D".$i, "집합건물")
				                 ->setCellValue("E".$i, $kk3[bubin_no])
				                 ->setCellValue("F".$i, $kk3[bank_name])
				                 ->setCellValue("G".$i, $row[az1])
				                 ->setCellValue("H".$i, $kk2[doro_addr]." ".$row[h1]."동 ".$row[i1]."호 (".$row[road_dong].", ".$row[road_building_name].")")
				                 ->setCellValue("I".$i, $kk4[addr])
				                 ->setCellValue("J".$i, $row[az1_tax])
				                 ;
						$i++;
						}
					}
				}
				
			}
/*

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $i-2)
                 ->setCellValue("B".$i, $row[h1])
                 ->setCellValue("C".$i, $row[i1])
                 ->setCellValue("D".$i, $row[jm1])
                 ->setCellValue("E".$i, f_bank_name($row[d1]))
                 ->setCellValue("F".$i, f_jijum_name($row[e1]))
                 ->setCellValue("G".$i, $row[av1])
                 ->setCellValue("H".$i, $row[ax1])
								 ->setCellValue("I".$i, $row[ay1])
								 ->setCellValue("J".$i, $row[az1])
								 ->setCellValue("K".$i, $row[av1_tax])
								 ->setCellValue("L".$i, $row[ax1_tax])
								 ->setCellValue("M".$i, $row[ay1_tax])
								 ->setCellValue("N".$i, $row[az1_tax])
								 ->setCellValue("O".$i, $row[av1_dec_date])
								 ->setCellValue("P".$i, $row[ax1_dec_date])
								 ->setCellValue("Q".$i, $row[ay1_dec_date])
								 ->setCellValue("R".$i, $row[ay1_dec_date])
								 ->setCellValue("S".$i, $row[av1_rec_date])
								 ->setCellValue("T".$i, $row[ax1_rec_date])
								 ->setCellValue("U".$i, $row[ay1_rec_date])
								 ->setCellValue("V".$i, $row[az1_rec_date])
								 ->setCellValue("W".$i, $row[av1_pay_date])
								 ->setCellValue("X".$i, $row[ax1_pay_date])
								 ->setCellValue("Y".$i, $row[ay1_pay_date])
								 ->setCellValue("Z".$i, $row[az1_pay_date])
								 ->setCellValue("AA".$i,$row[au1])
								 ->setCellValue("AB".$i,$row[ae1])
								 ->setCellValue("AC".$i,$row[bond_1conf_date])
								 ->setCellValue("AD".$i,$row[bond_2conf_date])
								 ->setCellValue("AE".$i,$row[pred_g1])
								 ->setCellValue("AF".$i,$row[g1])
                 ;
	$i++; */
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "설정등록세신고납부_등록신고용엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
