<?
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	$idx		= $_REQUEST["idx"];
	$a1		= $_REQUEST["a1"];
	$suljung_no	= $_REQUEST["suljung_no"];

	$name		= trim($_REQUEST["name"]);
	$jumin		= trim($_REQUEST["jumin"]);

	//echo $jumin;

	$KEY1 = array();

	$KEY1["aw".$suljung_no]  = $name;
	$KEY1["aw".$suljung_no."_jumin"]  = $jumin;

	$updatewhere = " WHERE a1 = '{$a1}'";
	db_replace($KEY1,"tbl_junib",$updatewhere,"idx");

	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<script>alert('처리되었습니다.');opener.location.reload();window.close();</script>";

?>