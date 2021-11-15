<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//print_r($_REQUEST);

	$idx = trim($_REQUEST[idx]);

	$mode = trim($_REQUEST[mode]);

	$KEY[sangho] = trim($_REQUEST[sangho]);
	$KEY[saup_no] = trim($_REQUEST[saup_no]);
	$KEY[ceo] = trim($_REQUEST[ceo]);
	$KEY[bubmusa] = trim($_REQUEST[bubmusa]);
	$KEY[addr] = trim($_REQUEST[addr]);
	$KEY[upjong] = trim($_REQUEST[upjong]);
	$KEY[uptae] = trim($_REQUEST[uptae]);
	$KEY[tel] = trim($_REQUEST[tel]);
	$KEY[fax] = trim($_REQUEST[fax]);
	$KEY[email] = trim($_REQUEST[email]);

	$tablename = "tbl_info";

if($mode=="i"){
	//echo "-i";
	###########################################################################

	$updatewhere = " WHERE idx='werwerfsdfsdfwerwre' ";
	$idx = db_replace($KEY,$tablename,$updatewhere,"idx");

	$tablename = "tbl_user_permission";
	$updatewhere = " WHERE u_idx='{$idx}' ";
	$KEY2[u_idx] = $idx;
	$idx2 = db_replace($KEY2,$tablename,$updatewhere,"idx");

	###########################################################################
	if($idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('등록 되었습니다.');location.href='info.php';</script>";	
		exit;
	}
}else if($mode=="e"){
	//echo "-e";
	###########################################################################

	$updatewhere = " WHERE idx='{$idx}' ";
	db_replace($KEY,$tablename,$updatewhere,"idx");

	$updatewhere = " WHERE idx='{$idx}' ";
	$idx = db_replace($KEY,$tablename,$updatewhere,"idx");

	###########################################################################
	if($idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('저장 되었습니다.');location.href='info.php';</script>";
		exit;
	}
}

?>