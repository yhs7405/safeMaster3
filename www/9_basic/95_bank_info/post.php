<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	//print_r($_REQUEST);
	//$KEY[id_ch] = trim($_REQUEST[id_ch]);

	$tablename = "tbl_bank_info";

	$idx = trim($_REQUEST[idx]);
	$mode = trim($_REQUEST[mode]);

//	$KEY[bank_code] = trim($_REQUEST[bank_code]);
	$KEY[bank_name] = trim($_REQUEST[bank_name]);
	$KEY[bank_alias] = trim($_REQUEST[bank_alias]);
	$KEY[bubin_no] = trim($_REQUEST[bubin_no]);
	$KEY[ver_name] = preg_replace("/'/","''",trim($_REQUEST[ver_name]));


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
		$maxx = "B01";
	}else{
		$imsi  = $row[aa] + 1;
		if(strlen($imsi)==1){
			$maxx = "B0".$imsi;
		}else{
			$maxx = "B".$imsi;
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
	//echo $updatewhere;
	//print_r($KEY);
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