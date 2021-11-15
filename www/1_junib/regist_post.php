<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";

	$list_num	=	trim($_REQUEST[list_num]);
	$page		=	trim($_REQUEST[page]);

	//print_r($_REQUEST);

	$datex = date("YmdHis");

	for($i=1;$i<=$list_num;$i++){

		if($_REQUEST["idx_".$i]!=""){

			if(trim($_REQUEST["junib_request_date1_".$i])=="y"){
				$sql = "update  tbl_junib set junib_request_date='".$_REQUEST["junib_request_date_".$i]."' where idx=".$_REQUEST["idx_".$i];
				//echo $sql."<br>";
				db_query($sql);

				db_query("update tbl_junib set junib_update='{$datex}'  where idx=".$_REQUEST["idx_".$i]);  //최신날짜 갱신
			}

			if(trim($_REQUEST["junib_s_date1_".$i])=="y"){
				$sql = "update  tbl_junib set junib_s_date='".$_REQUEST["junib_s_date_".$i]."' where idx=".$_REQUEST["idx_".$i]." ";
				db_query($sql);

				db_query("update tbl_junib set junib_update='{$datex}'  where idx=".$_REQUEST["idx_".$i]);  //최신날짜 갱신
			}
			if(trim($_REQUEST["damdang_id1_".$i])=="y"){
				$sql = "update  tbl_junib set damdang_id='".$_REQUEST["damdang_id_".$i]."' where idx=".$_REQUEST["idx_".$i]." ";
				db_query($sql);

				db_query("update tbl_junib set junib_update='{$datex}'  where idx=".$_REQUEST["idx_".$i]);  //최신날짜 갱신
			}

			//소유주완의 관계
			if(trim($_REQUEST["sou_relation1_".$i])=="y"){
				$sql = "update  tbl_junib set sou_relation='".$_REQUEST["sou_relation_".$i]."' where idx=".$_REQUEST["idx_".$i]." ";
				db_query($sql);

				db_query("update tbl_junib set junib_update='{$datex}'  where idx=".$_REQUEST["idx_".$i]);  //최신날짜 갱신
			}
			if(trim($_REQUEST["review_request_date1_".$i])=="y"){
				$sql = "update  tbl_junib set review_request_date='".$_REQUEST["review_request_date_".$i]."' where idx=".$_REQUEST["idx_".$i]." ";
				db_query($sql);

				db_query("update tbl_junib set junib_update='{$datex}'  where idx=".$_REQUEST["idx_".$i]);  //최신날짜 갱신
			}
			if(trim($_REQUEST["review_s_date1_".$i])=="y"){
				$sql = "update  tbl_junib set review_s_date='".$_REQUEST["review_s_date_".$i]."' where idx=".$_REQUEST["idx_".$i]." ";
				db_query($sql);

				db_query("update tbl_junib set junib_update='{$datex}'  where idx=".$_REQUEST["idx_".$i]);  //최신날짜 갱신
			}

			//재열람 확인사항
			if(trim($_REQUEST["review_confirm1_".$i])=="y"){
				$sql = "update  tbl_junib set review_confirm='".$_REQUEST["review_confirm_".$i]."' where idx=".$_REQUEST["idx_".$i]." ";
				db_query($sql);

				db_query("update tbl_junib set junib_update='{$datex}'  where idx=".$_REQUEST["idx_".$i]);  //최신날짜 갱신
			}
			if(trim($_REQUEST["sms_date1_".$i])=="y"){
				$sql = "update  tbl_junib set sms_date='".$_REQUEST["sms_date_".$i]."' where idx=".$_REQUEST["idx_".$i]." ";
				db_query($sql);

				db_query("update tbl_junib set junib_update='{$datex}'  where idx=".$_REQUEST["idx_".$i]);  //최신날짜 갱신
			}
			if(trim($_REQUEST["memo1_".$i])=="y"){
				$sql = "update  tbl_junib set memo='".$_REQUEST["memo_".$i]."' where idx=".$_REQUEST["idx_".$i]." ";
				db_query($sql);

				db_query("update tbl_junib set junib_update='{$datex}'  where idx=".$_REQUEST["idx_".$i]);  //최신날짜 갱신
			}
		}
	}

		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<html>";
		echo "<body>";
		echo "<center>";
		echo "<br>";
		echo "<input type=button value=' 처리 완료! ' onclick='javascript:opener.document.ffx.submit();;window.close();'>";
		echo "</center>";
		echo "</body>";
		echo "</html>";
		exit;
?>