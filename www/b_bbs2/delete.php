<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"board_data";

	$board_uid	=	urldecode(trim($_REQUEST[board_uid]));

	$sql = "delete from {$board_dbname} where board_uid='{$board_uid}'";
	db_query($sql);

	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<script>alert('삭제되었습니다.');location.href='index.html';</script>";
?>