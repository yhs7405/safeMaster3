<?
	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");
	include ("../../include/excel.inc");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$tablename_suljung = "tbl_suljung";  //근저당설정

	$idx		= $_REQUEST["idx"];
	$a1		= $_REQUEST["a1"];
	$bank_code	= $_REQUEST["bank_code"];
	$jijum_code	= $_REQUEST["jijum_code"];


	$sql= "select * from tbl_suljung where idx={$idx} limit 1";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	$suljung_no	= $row[suljung_no];    //순서
	$chaekwon_max	= $row[chaekwon_max];  //채권최고액
	$suljung_maeib	= $row[suljung_maeib];  //설정채권매입액


	$KEY1 = array();

	$KEY1["bank_code"] = $bank_code; //은행코드
	$KEY1["jijum_code"] = $jijum_code; //지점코드
	$KEY1["regi_lic"]  = intval($chaekwon_max) * 0.002;//등록면허세1
	$KEY1["local_edu"]  = intval($chaekwon_max) * 0.002 * 0.2;//지방교육세1

	$KEY1["chaekwon_max"]  = intval($chaekwon_max);//채권최고액1
	$KEY1["suljung_maeib"]  = intval($suljung_maeib);//설정채권매입액1

	$KEY1["suljung_bosu"]  = f_nujin_value($bank_code,$jijum_code,$chaekwon_max,$row[a1],$suljung_no); //은행누진보수료 참조 (은행코드,지점코드)
	$updatewhere = " WHERE idx = '{$idx}'";
	db_replace($KEY1,$tablename_suljung,$updatewhere,"idx");


	//설정 보수액/공과금 갱신
	$KEY1 = array();
	$KEY1[bosu_price] = f_suljung_bosu($idx,"");
	$KEY1[bosu_price_vat] = f_suljung_bosu_vat($idx,"");
	$KEY1[gongga_price] =  f_suljung_gongga($idx,"");
	$updatewhere = " WHERE idx = '{$idx}'";
	db_replace($KEY1,$tablename_suljung,$updatewhere,"idx");

	//이전데이타의 은행지점을 설정의1번과 동일하게 수정한다.
	if($suljung_no==1){
		$query = "update tbl_junib set d1='{$bank_code}',e1='{$jijum_code}' where a1='{$a1}'";
		db_query($query);
	}

	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<script>alert('처리되었습니다.');opener.location.reload();window.close();</script>";


?>