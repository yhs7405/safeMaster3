<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

	//print_r($_REQUEST);
	//$KEY[id_ch] = trim($_REQUEST[id_ch]);

	$tablename = "tbl_bank_jijum";

	$idx = trim($_REQUEST[idx]);
	$mode = trim($_REQUEST[mode]);

	$KEY[bank_code] = trim($_REQUEST[bank_code]);
	$KEY[jijum_code] = trim($_REQUEST[jijum_code]);
	$KEY[jijum_name] = trim($_REQUEST[jijum_name]);

	$KEY[saup_no] = trim($_REQUEST[saup_no]);
	$KEY[ceo] = trim($_REQUEST[ceo]);
	$KEY[trade_code] = trim($_REQUEST[trade_code]);
	$KEY[trade_name] = trim($_REQUEST[trade_name]);
	$KEY[addr] = trim($_REQUEST[addr]);
	$KEY[upjong] = trim($_REQUEST[upjong]);
	$KEY[uptae] = trim($_REQUEST[uptae]);
	$KEY[etc] = trim($_REQUEST[etc]);


if($mode=="i"){ //코드값 증가
	//echo "-i";

//	$sql= "select cast(substr(bank_code,2,2) as unsigned) aa from $tablename ";
	$sql= "select max(cast(substr(jijum_code,2,5) as unsigned)) aa from $tablename ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$row = $stmt->fetch();

	//최대값
	if($row[aa]==""){
		$maxx = "J00001";
	}else{
		$imsi  = intval($row[aa]) + 1;
		if(strlen($imsi)==1){
			$maxx = "J0000".$imsi;
		}else if(strlen($imsi)==2){
			$maxx = "J000".$imsi;
		}else if(strlen($imsi)==3){
			$maxx = "J00".$imsi;
		}else if(strlen($imsi)==4){
			$maxx = "J".$imsi;
		}
	}

	$KEY[jijum_code] = $maxx;
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