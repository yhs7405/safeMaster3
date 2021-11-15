<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");
	include ("../include/excel.inc");

//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

	//현금-이전/세금-설정/카드-<strong></strong> 발행일

	$h_idx				=	trim($_REQUEST[h_idx]);
	$s_date				=	trim($_REQUEST[s_date]);
	$e_date				=	trim($_REQUEST[e_date]);

	if($s_date=="") $s_date = date("Ymd");
	if($e_date=="") $e_date = date("Ymd");


	$view_num		=	trim($_REQUEST[view_num]);	//한라인에 몇개를 출력할건지//
	if($_REQUEST[page]==""){$page=1;}else{$page=$_REQUEST[page];}
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	100;					//리스트 갯수


	//현금영수증 발행일
	//세금계산서 발행일
	//카드승인일?????
	if($h_idx=="") {
		$sql1 = "select hy_b_date as suim,a1,h_idx,'ijun' as gubun,idx from  tbl_junib where hy_b_date between {$s_date} and {$e_date}";
		$sql2 = "select sg_b_date as suim,a1,h_idx,'suljung' as gubun,idx from tbl_suljung where sg_b_date between {$s_date} and {$e_date}";
		$sql3 = "select (SELECT confirm_date FROM tbl_sugum WHERE a1 = b.a1 AND suljung_no = b.suljung_no) as suim,b.a1,b.h_idx,'suljung' as gubun,b.idx from tbl_junib j cross join tbl_suljung b on j.a1=b.a1 where 1 = 1 and b.idx in (SELECT s.idx FROM `tbl_suljung` s cross join tbl_sugum t on s.a1=t.a1 WHERE s.suljung_no=t.suljung_no and (length(t.confirm_date)>0) and (confirm_date between {$s_date} and {$e_date}) group by s.idx )";
	} else {
		$sql1 = "select hy_b_date as suim,a1,h_idx,'ijun' as gubun,idx from  tbl_junib where h_idx = '{$h_idx}' and hy_b_date between {$s_date} and {$e_date}";
		$sql2 = "select sg_b_date as suim,a1,h_idx,'suljung' as gubun,idx from tbl_suljung where h_idx = '{$h_idx}' and sg_b_date between {$s_date} and {$e_date}";
		$sql3 = "select (SELECT confirm_date FROM tbl_sugum WHERE a1 = b.a1 AND suljung_no = b.suljung_no) as suim,b.a1,b.h_idx,'suljung' as gubun,b.idx from tbl_junib j cross join tbl_suljung b on j.a1=b.a1 where 1 = 1 and j.h_idx = '{$h_idx}' and b.idx in (SELECT s.idx FROM `tbl_suljung` s cross join tbl_sugum t on s.a1=t.a1 WHERE s.suljung_no=t.suljung_no and (length(t.confirm_date)>0) and (confirm_date between {$s_date} and {$e_date}) group by s.idx )";
	}

	//echo $sql;

	$objPHPExcel = new PHPExcel();

	$i=1;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(70);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);


     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$i, "No")
                 ->setCellValue("B".$i, "수임년월일")
                 ->setCellValue("C".$i, "건명")
                 ->setCellValue("D".$i, "보수액")
                 ->setCellValue("E".$i, "위임인의성명/주소")
                 ->setCellValue("F".$i, "위임인의확인")
                 ->setCellValue("G".$i, "종결일자")
                 ->setCellValue("H".$i, "비고")
                 ->setCellValue("I".$i, "발행일")
                 ->setCellValue("J".$i, "현장명");
	$i++;


	$sql = "select kk.suim,kk.a1,kk.h_idx,kk.gubun,kk.idx from (";
	$sql.= "{$sql1} union all {$sql2}  union all {$sql3} )  kk order by kk.suim desc ";
	//echo "<br><br><br>".$sql."<br><br><br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();


	while($row = $stmt->fetch()){
		if($row[gubun]=="ijun"){
			$row[gubun]="소유권이전";
		}else{
			$row[gubun]="근저당설정";
		}
		//이전정보
		$sql = "select * from tbl_junib where a1='{$row[a1]}' and h_idx={$row{h_idx}} limit 1";
		//echo $sql;
		$ii = db_query_fetch($sql);

		if($row[gubun]=="근저당설정"){
			$bosu=f_suljung_bosu_imsi($row[idx],"");

			$sql = "select * from tbl_suljung where idx={$row[idx]} limit 1";
			$sj = db_query_fetch($sql);

			$sql = "select * from tbl_bank_jijum where jijum_code='{$sj[jijum_code]}' limit 1";
			$jj = db_query_fetch($sql);

			$sql = "select * from tbl_bank_info where bank_code='{$jj[bank_code]}' limit 1";
			$jk = db_query_fetch($sql);

			$addrx = $ii[j1]."/".$ii[l1]."\r\n/".$jk[bank_name]."/".$jj[jijum_name]."/".$jj[ceo]."/".$jj[addr];
			$valid = f_jumin_valid($ii[k1])."\r\n/".$jj[trade_code];
		}else{
			$bosu=f_jung($ii[aq1])+f_jung($ii[ar1])+f_jung($ii[as1])+f_jung($ii[at1]);

			$sql = "select * from tbl_junib where idx={$row[idx]} limit 1";
			$ij = db_query_fetch($sql);

			$sql = "select * from tbl_hyunjang_danji_info where h_idx={$ij[h_idx]} and danji_name='{$ij[b1]}'";
			$dj = db_query_fetch($sql);
			
			$addrx = $dj[d_com_name]."/".$dj[d_name]."/".$dj[d_addr]."\r\n/".$ii[j1]."/".$ii[l1];
			$valid = $dj[d_bubin_no]."\r\n/".f_jumin_valid($ij[k1]);
		}


		$objPHPExcel->setActiveSheetIndex(0)
			 ->setCellValue("A".$i, $i)
			 ->setCellValue("B".$i, f_date($row[suim]))
			 ->setCellValue("C".$i, $row[gubun]) 
			 ->setCellValue("D".$i, $bosu)
			 ->setCellValue("E".$i, $addrx)
			 ->setCellValue("F".$i, $valid)
			 ->setCellValue("G".$i, f_date($row[suim]))
			 ->setCellValue("H".$i, $row[memo])
			 ->setCellValue("I".$i, f_date($row[suim]))
			 ->setCellValue("J".$i, f_hyunjang_name($row[h_idx]));
		$i++;
	}

	 $objPHPExcel->getActiveSheet()->setTitle('Seet name');
	 $objPHPExcel->setActiveSheetIndex(0); 
	 $filename = iconv("UTF-8", "EUC-KR", "사건부");
	 header('Content-Type: application/vnd.ms-excel');
	 header("Content-Disposition: attachment;filename=".$filename.".xls");
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

?>
