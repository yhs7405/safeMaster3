<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

	$board_dbname	=	"tbl_bosu_cost_manual";

	$list_num	=	trim($_REQUEST[list_num]);
	$page		=	trim($_REQUEST[page]);

	$user_ip = $_SERVER['REMOTE_ADDR'];
	$h_idx	=	trim($_REQUEST[h_idx]);
	$job_id      = $_SESSION["admin_id"];
	$job_date = date("YmdHis");

	if($list_num=="")	$list_num=20;
	if($page=="")		$page=1;

	//print_r($_REQUEST);

	$datex = date("YmdHis");
	for($i=1;$i<=$list_num;$i++){
			//print_r($KEY10);
		//echo $KEY10;
 		if(trim($_REQUEST["a1_".$i])!=""){

			$a1 = $_REQUEST["a1_".$i];
			$sql = "delete from tbl_bosu_cost_manual where a1='{$a1}' ";
			$r1 = db_query($sql);
			
			$sql="insert into tbl_login_user values('{$job_date}','{$job_id}','Y','{$user_ip}','삭제','보수료수동입력','/02_cost/6_payset/bosu_sd_popup.php','{$a1}') ";  //로그정보
			//echo $sql;
			db_query($sql);
		}
	}

	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<html>";
	echo "<body>";
	echo "<center>";
	echo "<br>";
	echo "<input type=button value=' 삭제 되었습니다. ' onclick='javascript:opener.document.ffx.submit();window.close();'>";
	echo "</center>";
	echo "</body>";
	echo "</html>";
	exit;
?>
