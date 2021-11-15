<?
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");
	include ("../../include/excel.inc");

	$tablename_suljung = "tbl_suljung";  //근저당설정

	$idx		= $_REQUEST["idx"];
	$a1		= $_REQUEST["a1"];
	$chaekwon_max	= str_replace(",","",$_REQUEST[chaekwon_max]);  //채권최고액

	$sql= "select * from tbl_suljung where idx={$idx} limit 1";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	$bank_code		= $row[bank_code];
	$jijum_code		= $row[jijum_code];
	$suljung_no		= $row[suljung_no];    //순서
	$suljung_maeib		= $row[suljung_maeib];  //설정채권매입액


	$KEY1 = array();

	$KEY1["regi_lic"]  = intval($chaekwon_max) * 0.002;//등록면허세1
	$KEY1["local_edu"]  = intval($chaekwon_max) * 0.002 * 0.2;//지방교육세1

	$KEY1["chaekwon_max"]  = intval($chaekwon_max);//채권최고액1
	$KEY1["suljung_maeib"]  = intval($suljung_maeib);//설정채권매입액1
 
	$KEY1["suljung_bosu"]  = f_nujin_value($bank_code,$jijum_code,$chaekwon_max,$a1,$suljung_no); //은행누진보수료 참조 (은행코드,지점코드)
	$updatewhere = " WHERE idx = '{$idx}'";
	db_replace($KEY1,$tablename_suljung,$updatewhere,"idx");

	//설정 보수액/공과금 갱신
	$KEY1 = array();
	$KEY1[bosu_price] = f_suljung_bosu($idx,"");
	$KEY1[bosu_price_vat] = f_suljung_bosu_vat($idx,"");
	$KEY1[gongga_price] =  f_suljung_gongga($idx,"");
	$updatewhere = " WHERE idx = '{$idx}'";
	db_replace($KEY1,$tablename_suljung,$updatewhere,"idx");


	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<script>alert('처리되었습니다.');opener.location.reload();window.close();</script>";


?>