<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);
	$id      = $_SESSION["admin_id"];
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$regist_date = date("YmdHis");


	$a1		=	urldecode(trim($_REQUEST[a1]));

	$sql = "delete from tbl_junib where a1='{$a1}' ";
	$r1 = db_query($sql);

	$sql = "delete from tbl_suljung where a1='{$a1}' ";
	$r1 = db_query($sql);

	$sql = "delete from tbl_sugum where a1='{$a1}' ";
	$r1 = db_query($sql);

	$sql="insert into tbl_login_user values('{$regist_date}','{$id}','Y','{$user_ip}','삭제','기본상세조회','2_custom/1_search/delete_ok.php','{$_REQUEST[a1]}') ";  //로그정보
	//echo $sql;
	db_query($sql);

	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<script>alert('삭제 되었습니다.');opener.document.ffx.submit();window.close();</script>";
?>
