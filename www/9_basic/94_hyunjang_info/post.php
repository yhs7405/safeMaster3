<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	$id      = $_SESSION["admin_id"];
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$regist_date = date("YmdHis");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	//print_r($_REQUEST);
	//$KEY[id_ch] = trim($_REQUEST[id_ch]);

	$tablename = "tbl_hyunjang_info";

	$idx = trim($_REQUEST[idx]);
	$h_idx = trim($_REQUEST[h_idx]);
	$mode = trim($_REQUEST[mode]);

//	if($h_idx==""){
//						//
//		$sql2 = "select max(h_idx)+1 as h_idx from tbl_hyunjang_info ";/
//		$h_idx = db_query_value($sql2);
//		$$KEY[h_idx] = $h_idx;

//	}
	$KEY[h_name]		= trim($_REQUEST[h_name]);
	$KEY[sum_sedae]		= trim($_REQUEST[sum_sedae]);
	$KEY[registery_office]	= trim($_REQUEST[registery_office]);
	$KEY[trade_code]	= trim($_REQUEST[trade_code]);
	$KEY[trade_name]	= trim($_REQUEST[trade_name]);
	$KEY[project_code]	= trim($_REQUEST[project_code]);
	$KEY[etc]		= trim($_REQUEST[etc]);
	$KEY[sosok]		= trim($_REQUEST[sosok]);
	$KEY[no_text]		= trim($_REQUEST[no_text]);
	$KEY[regist_date]	= date("YmdHis");
	$KEY[addr]		= trim($_REQUEST[addr]);

	$KEY[faq_link]		= trim($_REQUEST[faq_link]);
	$KEY[jungong_date]		= trim($_REQUEST[jungong_date]);
	$KEY[bojon_date]		= trim($_REQUEST[bojon_date]);
	$KEY[land_com_yn]		= trim($_REQUEST[land_com_yn]);
	$KEY[trust_gubun]		= trim($_REQUEST[trust_gubun]);
	$KEY[ipju_app_s]		= trim($_REQUEST[ipju_app_s]);
	$KEY[ipju_app_e]		= trim($_REQUEST[ipju_app_e]);
	$KEY[doc_rec_s]		= trim($_REQUEST[doc_rec_s]);
	$KEY[doc_rec_e]		= trim($_REQUEST[doc_rec_e]);
	$KEY[type_info]		= trim($_REQUEST[type_info]);
	$KEY[area_gubun]		= trim($_REQUEST[area_gubun]);
	$KEY[jojeong_yn]		= trim($_REQUEST[jojeong_yn]);
	$KEY[reg_mem_yn]		= trim($_REQUEST[reg_mem_yn]);
	$KEY[non_mem_yn]		= trim($_REQUEST[non_mem_yn]);
	$KEY[gen_mem_yn]		= trim($_REQUEST[gen_mem_yn]);
	$KEY[web_mem_yn]		= trim($_REQUEST[web_mem_yn]);
	$KEY[bosu_stn_date]		= trim($_REQUEST[bosu_stn_date]);
	$KEY[deunggi_cause]		= trim($_REQUEST[deunggi_cause]);
	$KEY[deunggi_pur]		= trim($_REQUEST[deunggi_pur]);

	// 예상등기접수일temp
	//취득세신고만료일



if($mode=="i"){ //코드값 증가
	//echo "-i";

//echo "<script>alert('테스트되었습니다.');</script>";	
	###########################################################################

	$updatewhere = " WHERE h_idx='werwerfsdfsdfwerwre' ";
	$h_idx = db_replace($KEY,$tablename,$updatewhere,"h_idx");

	###########################################################################
	if($h_idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('등록 완료되었습니다.');location.href='index.html';</script>";	
		exit;
	}
}else if($mode=="e"){

		$sql = "select * from tbl_junib where h_idx=$h_idx order by a1";
		//echo $sql;
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();
		//echo $rows;
	//echo "<script>alert('테스트되었습니다.');</script>";	

		if($rows > 0){

			$T = 1;
			while($row = $stmt->fetch()){
				$KEY2[a1] = $row[a1];
				$KEY2[pred_g1_temp] = f_day_comp($row[balance_date],$KEY[bojon_date]);  // 잔금일// 보존등기일 (예정등기일_temp)
				$KEY2[tax_end_date] = f_day_comp($row[balance_date],$KEY[jungong_date]);  // 잔금일// 준공일 (취득세신고만료일)

				$updatewhere = " WHERE a1='{$KEY2[a1]}' ";
				db_replace($KEY2,"tbl_junib",$updatewhere,"a1");
			}
		}


	//echo "-e";
	###########################################################################
	$updatewhere = " WHERE h_idx='{$h_idx}' ";
	db_replace($KEY,$tablename,$updatewhere,"h_idx");
	###########################################################################
	if($h_idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('수정 완료되었습니다.');location.href='index.html';</script>";
		exit;
	}
	
	
}else if($mode=="d"){
	//echo "-e";
	###########################################################################
	$where = " WHERE h_idx='{$h_idx}' ";

	$sql = "delete from $tablename  $where";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$sql="insert into tbl_login_user values('{$regist_date}','{$id}','N','{$user_ip}','삭제','현장상세정보','9_basic/94_hyunjang_info/regist.php','{$_REQUEST[h_idx]}') ";  //로그정보
	//echo $sql;
	db_query($sql);

	###########################################################################
	if($h_idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('삭제 완료되었습니다.');location.href='index.html';</script>";
		exit;
	}
}

?>