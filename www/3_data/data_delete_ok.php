<?
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");
	include ("../include/excel.inc");

	//전입자료 삭제
	$sql = "delete from tbl_junib where h_idx=".$_REQUEST["h_idx"]." ";
	//echo $sql."<br>";
	db_query($sql);

	//수금자료 삭제
	$sql = "delete from tbl_sugum where h_idx=".$_REQUEST["h_idx"]." ";
	//echo $sql."<br>";
	db_query($sql);

	//설정자료 삭제
	$sql = "delete from tbl_suljung where h_idx=".$_REQUEST["h_idx"]." ";
	db_query($sql);

	//정산리포트 삭제
	$sql = "delete from tbl_jungsan_report where h_idx=".$_REQUEST["h_idx"]." ";
	db_query($sql);
	//echo $sql."<br>";
	
	//지점정보 삭제
	$sql = "delete from tbl_bank_jijum_rate where h_idx=".$_REQUEST["h_idx"]." ";
	db_query($sql);
	//echo $sql."<br>";
	
	//현장정보  삭제
	$sql = "delete from tbl_hyunjang_info where h_idx=".$_REQUEST["h_idx"]." ";
	db_query($sql);
	//echo $sql."<br>";

	//현장단지정보  삭제
	$sql = "delete from tbl_hyunjang_danji_info where h_idx=".$_REQUEST["h_idx"]." ";
	db_query($sql);
	//echo $sql."<br>";



	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<script>alert('처리되었습니다.');location.href='data_delete.php';</script>";

?>