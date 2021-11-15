<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	//print_r($_REQUEST);
	//$KEY[id_ch] = trim($_REQUEST[id_ch]);

	$idx = trim($_REQUEST[idx]);

	$mode = trim($_REQUEST[mode]);

	$KEY[id] = trim($_REQUEST[id]);

	$KEY[name] = trim($_REQUEST[name]);
	//$KEY[pwd] = trim($_REQUEST[pwd1]);
	$KEY[tel] = trim($_REQUEST[tel]);
	$KEY[email] = trim($_REQUEST[email]);
	$KEY[grade] = trim($_REQUEST[grade]);
	$KEY[jumin] = trim($_REQUEST[jumin1])."-".trim($_REQUEST[jumin2]);
	$KEY[sosok] = trim($_REQUEST[sosok]);
	$KEY[addr] = trim($_REQUEST[addr]);

	$KEY2[ch_100] = trim($_REQUEST[ch_100]);
	$KEY2[ch_111] = trim($_REQUEST[ch_111]);
	$KEY2[ch_112] = trim($_REQUEST[ch_112]);
	$KEY2[ch_121] = trim($_REQUEST[ch_121]);
	$KEY2[ch_122] = trim($_REQUEST[ch_122]);
	$KEY2[ch_200] = trim($_REQUEST[ch_200]);
	$KEY2[ch_211] = trim($_REQUEST[ch_211]);
	$KEY2[ch_212] = trim($_REQUEST[ch_212]);
	$KEY2[ch_221] = trim($_REQUEST[ch_221]);
	$KEY2[ch_222] = trim($_REQUEST[ch_222]);
	$KEY2[ch_231] = trim($_REQUEST[ch_231]);
	$KEY2[ch_232] = trim($_REQUEST[ch_232]);
	$KEY2[ch_241] = trim($_REQUEST[ch_241]);
	$KEY2[ch_242] = trim($_REQUEST[ch_242]);

	$KEY2[ch_251] = trim($_REQUEST[ch_251]);
	$KEY2[ch_252] = trim($_REQUEST[ch_252]);

	$KEY2[ch_261] = trim($_REQUEST[ch_261]);
	$KEY2[ch_262] = trim($_REQUEST[ch_262]);

	$KEY2[ch_271] = trim($_REQUEST[ch_271]);
	$KEY2[ch_272] = trim($_REQUEST[ch_272]);

	$KEY2[ch_281] = trim($_REQUEST[ch_281]);
	$KEY2[ch_282] = trim($_REQUEST[ch_282]);

	$KEY2[ch_291] = trim($_REQUEST[ch_291]);
	$KEY2[ch_292] = trim($_REQUEST[ch_292]);

	$KEY2[ch_2a1] = trim($_REQUEST[ch_2a1]);
	$KEY2[ch_2a2] = trim($_REQUEST[ch_2a2]);

	$KEY2[ch_300] = trim($_REQUEST[ch_300]);
	$KEY2[ch_311] = trim($_REQUEST[ch_311]);
	$KEY2[ch_312] = trim($_REQUEST[ch_312]);

	$KEY2[ch_321] = trim($_REQUEST[ch_321]);
	$KEY2[ch_322] = trim($_REQUEST[ch_322]);

	$KEY2[ch_331] = trim($_REQUEST[ch_331]);
	$KEY2[ch_332] = trim($_REQUEST[ch_332]);

	$KEY2[ch_400] = trim($_REQUEST[ch_400]);
	$KEY2[ch_411] = trim($_REQUEST[ch_411]);
	$KEY2[ch_412] = trim($_REQUEST[ch_412]);

	$KEY2[ch_500] = trim($_REQUEST[ch_500]);
	$KEY2[ch_511] = trim($_REQUEST[ch_511]);
	$KEY2[ch_512] = trim($_REQUEST[ch_512]);

	$KEY2[ch_521] = trim($_REQUEST[ch_521]);
	$KEY2[ch_522] = trim($_REQUEST[ch_522]);

	$KEY2[ch_600] = trim($_REQUEST[ch_600]);
	$KEY2[ch_611] = trim($_REQUEST[ch_611]);
	$KEY2[ch_612] = trim($_REQUEST[ch_612]);

	$KEY2[ch_700] = trim($_REQUEST[ch_700]);
	$KEY2[ch_711] = trim($_REQUEST[ch_711]);
	$KEY2[ch_712] = trim($_REQUEST[ch_712]);

	$KEY2[ch_721] = trim($_REQUEST[ch_721]);
	$KEY2[ch_722] = trim($_REQUEST[ch_722]);

	$KEY2[ch_731] = trim($_REQUEST[ch_731]);
	$KEY2[ch_732] = trim($_REQUEST[ch_732]);

	$KEY2[ch_800] = trim($_REQUEST[ch_800]);
	$KEY2[ch_811] = trim($_REQUEST[ch_811]);
	$KEY2[ch_812] = trim($_REQUEST[ch_812]);

	$KEY2[ch_900] = trim($_REQUEST[ch_900]);
	$KEY2[ch_911] = trim($_REQUEST[ch_911]);
	$KEY2[ch_912] = trim($_REQUEST[ch_912]);

	$KEY2[ch_921] = trim($_REQUEST[ch_921]);
	$KEY2[ch_922] = trim($_REQUEST[ch_922]);

	$KEY2[ch_931] = trim($_REQUEST[ch_931]);
	$KEY2[ch_932] = trim($_REQUEST[ch_932]);

	$KEY2[ch_941] = trim($_REQUEST[ch_941]);
	$KEY2[ch_942] = trim($_REQUEST[ch_942]);

	$KEY2[ch_951] = trim($_REQUEST[ch_951]);
	$KEY2[ch_952] = trim($_REQUEST[ch_952]);

	$KEY2[ch_961] = trim($_REQUEST[ch_961]);
	$KEY2[ch_962] = trim($_REQUEST[ch_962]);

	$KEY2[ch_971] = trim($_REQUEST[ch_971]);
	$KEY2[ch_972] = trim($_REQUEST[ch_972]);

	$KEY2[ch_981] = trim($_REQUEST[ch_981]);
	$KEY2[ch_982] = trim($_REQUEST[ch_982]);

	$KEY2[ch_991] = trim($_REQUEST[ch_991]);
	$KEY2[ch_992] = trim($_REQUEST[ch_992]);

	$KEY2[ch_a00] = trim($_REQUEST[ch_a00]);
	$KEY2[ch_a11] = trim($_REQUEST[ch_a11]);
	$KEY2[ch_a12] = trim($_REQUEST[ch_a12]);

	$KEY2[ch_a21] = trim($_REQUEST[ch_a21]);
	$KEY2[ch_a22] = trim($_REQUEST[ch_a22]);

	$KEY2[ch_b00] = trim($_REQUEST[ch_b00]);
	$KEY2[ch_b11] = trim($_REQUEST[ch_b11]);
	$KEY2[ch_b12] = trim($_REQUEST[ch_b12]);

	$KEY2[ch_b21] = trim($_REQUEST[ch_b21]);
	$KEY2[ch_b22] = trim($_REQUEST[ch_b22]);

if($mode=="i"){
	//echo "-i";
	###########################################################################
	$tablename = "tbl_user";
	$updatewhere = " WHERE idx='werwerfsdfsdfwerwre' ";
	$idx = db_replace($KEY,$tablename,$updatewhere,"idx");

	$sql="update tbl_user set pwd=password('".trim($_REQUEST[pwd1])."') where idx='{$idx}'";  //암호갱신
	//echo $sql;
	db_query($sql);

	$tablename = "tbl_user_permission";
	$updatewhere = " WHERE u_idx='{$idx}' ";
	$KEY2[u_idx] = $idx;
	$idx2 = db_replace($KEY2,$tablename,$updatewhere,"idx");

	###########################################################################
	if($idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('등록 완료되었습니다.');location.href='index.html';</script>";	
		exit;
	}
}else if($mode=="e"){
	//echo "-e";
	###########################################################################
	$tablename = "tbl_user";
	$updatewhere = " WHERE idx='{$idx}' ";
	db_replace($KEY,$tablename,$updatewhere,"idx");

	if(trim($_REQUEST[pwd1])!=""){
	$sql="update tbl_user set pwd=password('".trim($_REQUEST[pwd1])."') where idx='{$idx}'";  //암호갱신
	//echo $sql;
	db_query($sql);
	}

	$tablename = "tbl_user_permission";
	$updatewhere = " WHERE u_idx='{$idx}' ";
	$KEY2[u_idx] = $idx;
	$idx2 = db_replace($KEY2,$tablename,$updatewhere,"idx");

	###########################################################################
	if($idx2 > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('수정 완료되었습니다.');location.href='index.html';</script>";
		exit;
	}
}

?>