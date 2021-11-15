<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";

	$list_num	=	trim($_REQUEST[view_num]);
	$page		=	trim($_REQUEST[page]);


	//print_r($_REQUEST);

	$datex = date("YmdHis");
	for($i=1;$i<=$list_num;$i++){
		
		if($_REQUEST["idx_".$i]!=""){
			$sql = "update  tbl_junib set ijp_s_memo='".$_REQUEST["ijp_s_memo_".$i]."' where idx=".$_REQUEST["idx_".$i]." ";
			//echo $sql."<br>";
			db_query($sql);

			$sql = "update tbl_junib set sps_update='{$datex}' where idx=".$_REQUEST["idx_".$i]." ";
				//echo $sql;
			db_query($sql);  //최신날짜 갱신
		}
	}

	for($i=1;$i<=$list_num;$i++){
		for($x=1;$x<5;$x++){
			if(trim($_REQUEST["x_sjp_s_date".$x."_".$i])=="y"){
				$sql = "update  tbl_suljung set sjp_s_date='".$_REQUEST["sjp_s_date".$x."_".$i]."',sjp_s_id='".$_SESSION["admin_id"]."' where suljung_no={$x} and a1='".$_REQUEST["a1_".$i]."'";
				//echo $sql."<br>";
				db_query($sql);
			}
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