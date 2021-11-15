<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";

	$list_num	=	trim($_REQUEST[list_num]);
	$page		=	trim($_REQUEST[page]);

	if($list_num=="")	$list_num=20;
	if($page=="")		$page=1;

	//print_r($_REQUEST);

	$datex = date("YmdHis");
	for($i=1;$i<=$list_num;$i++){
//		echo $i;
		$KEY1 = array();
		$KEY1["a1"] = $_REQUEST["a1_".$i];
		$KEY1["ad1"] = $_REQUEST["ad1_".$i];
		$KEY1["doc_memo"] = $_REQUEST["doc_memo_".$i];
		$KEY1["au1"] = $_REQUEST["au1_".$i];
		$KEY1["rec_memo"] = $_REQUEST["rec_memo_".$i];
		$KEY1["ae1"] = $_REQUEST["ae1_".$i];
		$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
		db_replace($KEY1,$board_dbname,$updatewhere,"a1");

	}

	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<html>";
	echo "<body>";
	echo "<center>";
	echo "<br>";
	echo "<input type=button value=' 저장 완료! ' onclick='javascript:opener.document.ffx.submit();window.close();'>";
	echo "</center>";
	echo "</body>";
	echo "</html>";
	exit;
?>