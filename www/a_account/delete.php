<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	//print_r($_REQUEST);
	//$KEY[id_ch] = trim($_REQUEST[id_ch]);

	$idx = trim($_REQUEST[idx]);

	$sql = "delete from tbl_user where idx = '{$idx}'";
	db_query($sql);

	$sql = "delete from tbl_user_permission where u_idx = '{$idx}'";
	db_query($sql);

	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<script>alert('계정 삭제 완료되었습니다.');location.href='index.html';</script>";
	exit;

?>