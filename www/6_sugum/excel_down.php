<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");
	include ("../include/excel.inc");
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"  tbl_junib j cross join (select s.idx, s.sugum_update, s.a1, t.suljung_no, t.ibgum_date1, t.ibgum_date2, s.gongga_price, s.bosu_price, s.bosu_price_vat, s.sjj_w_date from tbl_suljung s, tbl_sugum t where s.a1 = t.a1 and s.suljung_no = t.suljung_no) b on j.a1=b.a1 ";

	$h_idx				=	trim($_REQUEST[h_idx]);
	$target_date			=	trim($_REQUEST[target_date]);
	$s_date				=	trim($_REQUEST[s_date]);
	$e_date				=	trim($_REQUEST[e_date]);

	$target_date2			=	trim($_REQUEST[target_date2]);
	$s_date2			=	trim($_REQUEST[s_date2]);
	$e_date2			=	trim($_REQUEST[e_date2]);
	$bank_code			=	trim($_REQUEST[bank_code]);
	$jijum_code			=	trim($_REQUEST[jijum_code]);

	$cg_daesang			=	trim($_REQUEST[cg_daesang]);
	$kikan2_null_ch			=	trim($_REQUEST[kikan2_null_ch]);

	$h1				=	trim($_REQUEST[h1]);
	$i1				=	trim($_REQUEST[i1]);
	$j1				=	trim($_REQUEST[j1]);
	$memo				=	trim($_REQUEST[memo]);

	$ibgum_y			=	trim($_REQUEST[ibgum_y]);

	if($target_date=="") $target_date="g1";
	if($s_date=="")		$s_date=date("Ymd");
	if($e_date=="")		$e_date=date("Ymd");

	$list_num		=	trim($_REQUEST[list_num]);
	$page			=	trim($_REQUEST[page]);
	$view_num		=	trim($_REQUEST[list_num]);	//한라인에 몇개를 출력할건지//
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
//	if(!$view_num)	$view_num=	20;					//리스트 갯수

	$wherequery = " where 1=1  ";
  $imss = " ";
	if($h_idx!="")			$wherequery.= " and j.h_idx = ".$h_idx." ";
	if($bank_code!="")		$wherequery.= " and j.d1 = '".$bank_code."' ";
	if($jijum_code!="")		$wherequery.= " and j.e1 = '".$jijum_code."' ";
	if($h1!="")			$wherequery.= " and j.h1 = '".$h1."' ";
	if($i1!="")			$wherequery.= " and j.i1 = '".$i1."' ";
	if($j1!="")			$wherequery.= " and (j.j1 like '%{$j1}%' or j.m1 like '%{$j1}%')";

	//if($cg_daesang=="Y")	$wherequery.= " and b.cg_daesang='Y' ";
	
	if($target_date!=""){

		if($target_date=="100") {
			$imsi = " and b.sugum_update in (SELECT max(sugum_update) FROM tbl_suljung where sugum_update<>'' GROUP BY sugum_update ORDER BY sugum_update DESC)";
		}else if($target_date=="ibgum_date") {
			$imsia = " and ((b.ibgum_date1 between {$s_date} and {$e_date}) or (b.ibgum_date2 between {$s_date} and {$e_date}) )";
			$imss.= $imsia;
		}else if(($s_date!="")&&($e_date!="")){
			$imsi = "";
			if(($target_date=="sjp_s_date")||($target_date=="sjp_j_date")||($target_date=="sjj_w_date")||($target_date=="sjp_b_date")){  //설정일때
				$imsi = " and j.a1 in (select a1 from tbl_suljung where {$target_date} between {$s_date} and {$e_date} )";
			}else if($target_date=="biyong_c_date"){
				$imsi = " and j.a1 in (select a1 from tbl_sugum where {$target_date} between {$s_date} and {$e_date} )";
			}else{
				if($target_date!="") {$imsi = " and {$target_date} between ";}
				if($s_date==$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
				if($s_date!=$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
			}
		}
		$wherequery.=$imsi;
	}

if($kikan2_null_ch=="Y"){
			if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")){ //설정일때
				$imsi = " and j.a1 in (select a1 from tbl_suljung where  ({$target_date2}='' or {$target_date2} is null ))";
			}else if(($target_date2=="biyong_c_date")){ //수금일때
				$imsi = " and j.a1 in (select a1 from tbl_sugum where ({$target_date2}='' or {$target_date2} is null ))";
			}else if(($target_date2=="ibgum_date")){ //입금일일때
				$imsia = " and ((b.ibgum_date1 is null) or (b.ibgum_date2 is null) )";
			$imss.= $imsia;
			}else{
				if($target_date2!="") {$imsi = " and ({$target_date2}='' or {$target_date2} is null )";}
			}
			$wherequery.=$imsi;
}else{
	if($target_date2!=""){
		if(($s_date2!="")&&($e_date2!="")){
			$imsi = "";
			if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")){ //설정일때
				$imsi = " and j.a1 in (select a1 from tbl_suljung where {$target_date2} between {$s_date2} and {$e_date2} )";
			}else if($target_date2=="biyong_c_date"){
				$imsi = " and j.a1 in (select a1 from tbl_sugum where {$target_date2} between {$s_date2} and {$e_date2} )";
			}else if($target_date2=="ibgum_date") {
				$imsia = " and ((b.ibgum_date1 between {$s_date2} and {$e_date2}) or (b.ibgum_date2 between {$s_date2} and {$e_date2}) )";
			$imss.= $imsia;
			$wherequery.=$imsi;
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
		$wherequery.= " and (j.a1 in ( (select a1 from tbl_junib where (memo like '%{$memo}%') or (ijp_s_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_s_memo  like '%{$memo}%') or (ijp_j_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_j_memo  like '%{$memo}%') or (ijj_w_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjj_w_memo  like '%{$memo}%') or (ijp_b_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_b_memo  like '%{$memo}%') or (refund_memo  like '%{$memo}%') ";
		$wherequery.= "      or (refund_end_memo like '%{$memo}%') or (refund_memo  like '%{$memo}%')))  or ";
		$wherequery.= "      j.a1 in (select a1 from tbl_sugum where sugum_memo like '%{$memo}%') )";
	}

if($ibgum_y=="y"){
		$wherequery.= " and b.idx in (select b.idx  from  tbl_suljung b left join tbl_sugum s on b.a1=s.a1 where b.suljung_no=s.suljung_no and (b.bosu_price + b.gongga_price + b.bosu_price_vat)>(ifnull(s.ibgum_money1,0)+ifnull(s.ibgum_money2,0)) ) ";
}

	$wherequery.= $imss;

	$objPHPExcel = new PHPExcel();

	$sql = "select j.h_idx,j.a1, b.suljung_no, b.idx AS s_idx, j.idx, j.d1, j.e1, j.h1, j.i1, j.aw1, j.aw2, j.aw3, j.aw4, b.gongga_price, b.bosu_price, b.bosu_price_vat, b.sjj_w_date, j.ijp_s_memo, b.ibgum_date1, b.ibgum_date2 from  tbl_junib j cross join (select s.idx, s.sugum_update, t.a1, t.suljung_no, t.ibgum_date1, t.ibgum_date2, s.gongga_price, s.bosu_price, s.bosu_price_vat, s.sjj_w_date from tbl_suljung s left join tbl_sugum t on s.a1 = t.a1 and s.suljung_no = t.suljung_no ) b on j.a1=b.a1  $wherequery  order by  cast(j.h1 as unsigned) asc,cast(j.i1 as unsigned) asc ";
//	echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(30);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "NO")
                 ->setCellValue("B".$i, "은행명")
                 ->setCellValue("C".$i, "지점명")
                 ->setCellValue("D".$i, "동")
                 ->setCellValue("E".$i, "호")
                 ->setCellValue("F".$i, "채무자")
                 ->setCellValue("G".$i, "공과금")
                 ->setCellValue("H".$i, "보수액")
                 ->setCellValue("I".$i, "총비용")
                 ->setCellValue("J".$i, "정산일")
                 ->setCellValue("K".$i, "비용청구일")
                 ->setCellValue("L".$i, "검증")
                 ->setCellValue("M".$i, "1차입금일")
                 ->setCellValue("N".$i, "1차입금액")
                 ->setCellValue("O".$i, "1차카드/현금")
                 ->setCellValue("P".$i, "2차입금일")
                 ->setCellValue("Q".$i, "2차입금액")
                 ->setCellValue("R".$i, "2차카드/현금")
                 ->setCellValue("S".$i, "총입금액")
                 ->setCellValue("T".$i, "승인일")
                 ->setCellValue("U".$i, "수금비고")
                 ->setCellValue("V".$i, "재무돌이비고");
	$i++;

	while($row = $stmt->fetch()){


	$sql1 = "select * from tbl_sugum where a1='{$row[a1]}' and suljung_no='$row[suljung_no]'  limit 1 ";
	//echo $sql1;
	$row1 = db_query_value($sql1);


	if($row[bosu_price]+$row[bosu_price_vat]+$row[gongga_price]-$row1[ibgum_money1]-$row1[ibgum_money2] == 0){
		$row1[gumjung1]="OK";
	}else{
		$row1[gumjung1]=($row[bosu_price]+$row[bosu_price_vat]+$row[gongga_price]-$row1[ibgum_money1]-$row1[ibgum_money2]);
	}

     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, $i-1)
                 ->setCellValue("B".$i, f_bank_name($row[d1]))
                 ->setCellValue("C".$i, f_jijum_name($row[e1]))
                 ->setCellValue("D".$i, $row[h1])
                 ->setCellValue("E".$i, $row[i1])
                 ->setCellValue("F".$i, $row["aw".$row[suljung_no]])
                 ->setCellValue("G".$i, $row[gongga_price])
                 ->setCellValue("H".$i, $row[bosu_price]+$row[bosu_price_vat])
                 ->setCellValue("I".$i, $row[bosu_price]+$row[bosu_price_vat]+$row[gongga_price])
                 ->setCellValue("J".$i, f_date($row[sjj_w_date]))
                 ->setCellValue("K".$i, $row1[biyong_c_date])
                 ->setCellValue("L".$i, $row1[gumjung1])
                 ->setCellValue("M".$i, $row1[ibgum_date1])
                 ->setCellValue("N".$i, $row1[ibgum_money1])
                 ->setCellValue("O".$i, $row1[card_gubun1])
                 ->setCellValue("P".$i, $row1[ibgum_date2])
                 ->setCellValue("Q".$i, $row1[ibgum_money2])
                 ->setCellValue("R".$i, $row1[card_gubun2])
                 ->setCellValue("S".$i, $row1[ibgum_money1]+$row1[ibgum_money2])
                 ->setCellValue("T".$i, f_date($row1[confirm_date]))
                 ->setCellValue("U".$i, $row1[sugum_memo])
                 ->setCellValue("V".$i, $row[ijp_s_memo]);
	$i++;
	}


	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "수금_엑셀");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
