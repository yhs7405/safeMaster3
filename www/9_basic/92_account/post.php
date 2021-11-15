<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

	//print_r($_REQUEST);
	//$KEY[id_ch] = trim($_REQUEST[id_ch]);

	$tablename = "tbl_account";

	$idx = trim($_REQUEST[idx]);

	$mode = trim($_REQUEST[mode]);

//	$KEY[bank_code] = trim($_REQUEST[bank_code]);
	$KEY[bank_name] = trim($_REQUEST[bank_name]);
	$KEY[bank_nickname] = trim($_REQUEST[bank_nickname]);
	$KEY[bank_owner] = trim($_REQUEST[bank_owner]);
	$KEY[bank_account] = trim($_REQUEST[bank_account]);


if($mode=="i"){ //코드값 증가
	//echo "-i";

//	$sql= "select cast(substr(bank_code,2,2) as unsigned) aa from $tablename ";
	$sql= "select max(cast(substr(bank_code,2,2) as unsigned)) aa from $tablename ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$row = $stmt->fetch();

	//최대값
	if($row[aa]==""){
		$maxx = "A01";
	}else{
		$imsi  = $row[aa] + 1;
		if(strlen($imsi)==1){
			$maxx = "A0".$imsi;
		}else{
			$maxx = "A".$imsi;
		}
	}

	$KEY[bank_code] = $maxx;
	###########################################################################

	$updatewhere = " WHERE idx='werwerfsdfsdfwerwre' ";
	$idx = db_replace($KEY,$tablename,$updatewhere,"idx");

	###########################################################################
	if($idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('등록 완료되었습니다.');location.href='index.html';</script>";	
		exit;
	}
}else if($mode=="e"){
	//echo "-e";
	###########################################################################
	$updatewhere = " WHERE idx='{$idx}' ";
	db_replace($KEY,$tablename,$updatewhere,"idx");
	###########################################################################
	if($idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('수정 완료되었습니다.');location.href='index.html';</script>";
		exit;
	}
}else if($mode=="d"){
	//echo "-e";
	###########################################################################
	$where = " WHERE idx='{$idx}' ";

	$sql = "delete from $tablename  $where";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	###########################################################################
	if($idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('삭제 완료되었습니다.');location.href='index.html';</script>";
		exit;
	}
}

?>