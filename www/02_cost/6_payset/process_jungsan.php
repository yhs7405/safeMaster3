<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

	$h_idx	=	$_REQUEST[h_idx];

	// 정산테이블 해당 현장데이터 삭제
	$sql = "delete from tbl_jungsan_report where h_idx={$h_idx}";
	db_query($sql);

	//d1,e1  은행/지점
	$sql = "select h_idx, d1,e1 from tbl_junib  where h_idx={$h_idx} group by h_idx, d1,e1";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();


	while($row = $stmt->fetch()){

		//이전
		$sql = "select ifnull(count(h_idx),0) as ss,sum(ap1) ap1_s,sum(aq1) aq1_s,sum(ar1) ar1_s, sum(as1) as1_s,sum(at1) at1_s from tbl_junib where h_idx={$h_idx} and e1='{$row[e1]}' and d1='{$row[d1]}' ";
		//echo $sql;
		$ij = db_query_fetch($sql);

		if($ij[ss]==0){ 
			$ij[ss]=0;
			$ij_bosu   = 0;  //소유권이전보수료
			$ij_malso  = 0;  //신탁말소보수료
			$ij_jejung = 0;             //제증명
		}else{
			$ij[ss]=$ij[ss];
			$ij_bosu   = $ij[aq1_s]+$ij[ar1_s];  //소유권이전보수료
			$ij_malso  = $ij[as1_s]+$ij[at1_s];  //신탁말소보수료
			$ij_jejung = $ij[ap1_s];             //제증명
		}
		//설정
		$sql = "select ifnull(count(h_idx),0) as ss from tbl_suljung where h_idx={$h_idx}  and jijum_code='{$row[e1]}' and bank_code='{$row[d1]}' ";
		//echo $sql;
		$sj_cc = db_query_fetch($sql);
		//if($sj_cc[ss]==0) $sj_cc[ss]=0;

		//중복처리
//		$sql = "select * from tbl_jungsan_report where bank_code='{$row[d1]}' and jijum_code='{$row[e1]}' ";
		//echo $sql;
//		$xx = db_query_fetch($sql);

//		if($xx[h_idx]==""){//없으면 insert
			$datex = date("Ymd");
			$sql = "insert into tbl_jungsan_report (h_idx,regist_date,bank_code,jijum_code,ijun_count,ijun_bosu,ijun_malso,ijun_je,suljung_count) values({$h_idx},'{$datex}','{$row[d1]}','{$row[e1]}',{$ij[ss]},{$ij_bosu},{$ij_malso},{$ij_jejung},{$sj_cc[ss]})";
			db_query($sql);
//		}else{
//			$datex = date("Ymd");
//			$sql = "update tbl_jungsan_report set regist_date='{$datex}',ijun_count={$ij[ss]},ijun_bosu={$ij_bosu},ijun_malso={$ij_malso},ijun_je={$ij_jejung},suljung_count={$sj_cc[ss]} where bank_code='{$row[d1]}' and jijum_code='{$row[e1]}' ";
//			db_query($sql);
//		}
		
	}


	//d1,e1  은행/지점별로 
	$sql = "select d1,e1 from tbl_junib  where h_idx={$h_idx} group by d1,e1";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	while($row = $stmt->fetch()){
		$suljung_total = 0;
		$bosu = 0;	
		$chunggu = 0;
		$suljung_ibgum = 0;
		//은행/지점에 해당하는 설정정보 돌리기 
		$sql = "select * from tbl_suljung where h_idx={$h_idx}  and jijum_code='{$row[e1]}' and bank_code='{$row[d1]}' ";
		//echo $sql;
		$stmt1 = $pdo->prepare($sql);
		$stmt1->execute();
		while($row1 = $stmt1->fetch()){
		
			if($row1[gukto]=="gukto"){
				$basic_gukto = "gukto";
			}else{
				$basic_gukto = "basic";
			}

//			//지점비율
//			$sql = "select * from tbl_bank_jijum_rate where jijum_code='{$row1[jijum_code]}'  and basic_gukto='{$basic_gukto}' limit 1";
//			if($row1[jijum_code]=="J00001"){
//			//echo $sql."----".$row1[cg_daesang]."<br>";
//			}
//			$jj = db_query_fetch($sql);
//
//			$sql = "select bank_alias from tbl_bank_info where bank_code='{$jj[bank_code]}' limit 1";
//			//echo $sql;
//			$bb = db_query_fetch($sql);
//
//			$b_imsi = 0;
//			//기본보수료(고정값) + 누진보수료(엑셀업로드시) + 등록세신고납부대행(1회청구비교) + 교통비(1회청구비교) + 원인증서작성료(고정) + 부가세
//			if($row1[cg_daesang]=="Y"){  //1회청구라면 그사람만 나옴.
//				$b_imsi = $row1[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
//				$b_imsi = $b_imsi + $jj[b_singonabbu];
//				$b_imsi = $b_imsi + $jj[b_kyotong];
//			}else{
//				$b_imsi = $row1[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
//				if($jj[b_singonabbu_ch]!="y") $imsi = $imsi + $jj[b_singonabbu];
//				if($jj[b_kyotong_ch]!="y") $imsi = $imsi + $jj[b_kyotong];
//			}
//
//			//부가세 (신한은행 원단위까지 나와.타은행은 원단위 버림)
//			if($bb[bank_alias]=="신한"){  //원단위까지 나와야함.
//				$vat = floor(($b_imsi*0.1)/1)*1;
//			}else{  //원단위 버림
//				$vat = floor(($b_imsi*0.1)/10)*10;
//			}
//
//			$bosu = $bosu + ($b_imsi+$vat);
//
//			$g_imsi = 0;
//			//증지대(공) + 등록세신고납부대행(공-Y) + 교통비(공-Y) + 제증명(공) + 열람증지대(우리공) + 등초본발급(공) + 지배인초본발급(하나공)
//			//$imsi = $jj[g_jungjidae] + $jj[g_singgonabbu] + $jj[g_kyotong] + $jj[g_jejungmyung] + $jj[g_yeolamjunggi] + $jj[g_deungchobon] + $jj[g_jibaeinchobon];
//			if($row1[cg_daesang]=="Y"){  //1회청구라면 그사람만 나옴.
//				$g_imsi = $jj[g_jungjidae] + $jj[g_jejungmyung] + $jj[g_yeolamjunggi] + $jj[g_deungchobon] + $jj[g_jibaeinchobon];
//				$g_imsi = $g_imsi + $jj[g_singonabbu];
//				$g_imsi = $g_imsi + $jj[g_kyotong];
//			}else{
//				$g_imsi = $jj[g_jungjidae] + $jj[g_jejungmyung] + $jj[g_yeolamjunggi] + $jj[g_deungchobon] + $jj[g_jibaeinchobon];
//				if($jj[g_singonabbu_ch]!="y") $imsi = $imsi + $jj[g_singonabbu];
//				if($jj[g_kyotong_ch]!="y") $imsi = $imsi + $jj[g_kyotong];
//			}
//
//			$chunggu =  $chunggu  + $g_imsi;

		$bosu = $bosu + $row1[bosu_price] + $row1[bosu_price_vat];
		$chunggu = $chunggu + $row1[gongga_price];
			//if($row1[jijum_code]=="J00001"){
			//	echo $b_imsi."/".$g_imsi."<br>";
			//}
			$sql = "select ifnull(ibgum_money1,0) as a1,ifnull(ibgum_money2,0) as a2 from tbl_sugum where a1='{$row1[a1]}' and suljung_no='{$row1[suljung_no]}'  ";
			//echo $sql."<br>";
			$ib = db_query_fetch($sql);

			$suljung_ibgum = $suljung_ibgum + f_jung($ib[a1])+ f_jung($ib[a2]);
		}

		$suljung_total = $bosu  + $chunggu; 

	
		$sql = "update tbl_jungsan_report set suljung_total={$suljung_total},suljung_bosu={$bosu},suljung_gongga={$chunggu},suljung_ibgum={$suljung_ibgum} where h_idx={$h_idx}  and jijum_code='{$row[e1]}' and bank_code='{$row[d1]}'  ";
		//echo "<br>".$sql."<br>";
		db_query($sql);

	}
echo "OK";
?>