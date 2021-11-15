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

//	print_r($_REQUEST);

	$datex = date("YmdHis");
	for($i=1;$i<=$list_num;$i++){

		if($_REQUEST["idx_".$i]!=""){
			if(trim($_REQUEST["ijp_b_date1_".$i])=="y"){
				$sql = "update  tbl_junib set ijp_b_date='".$_REQUEST["ijp_b_date_".$i]."',ijp_b_id='".$_SESSION["admin_id"]."' where idx=".$_REQUEST["idx_".$i];
				//echo $sql."<br>";
				db_query($sql);
			}

				$sql = "update  tbl_junib set ijp_s_memo='".$_REQUEST["ijp_s_memo_".$i]."' where idx=".$_REQUEST["idx_".$i]." ";
				//echo $sql."<br>";
				db_query($sql);

				$sql = "update tbl_junib set ijp_b_update='{$datex}' where idx=".$_REQUEST["idx_".$i]." ";
					//echo $sql;
				db_query($sql);  //최신날짜 갱신
		}
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