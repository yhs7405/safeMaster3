<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";

	$list_num	=	trim($_REQUEST[list_num]);
	$page		=	trim($_REQUEST[page]);


	//print_r($_REQUEST);


	$datex = date("YmdHis");
	for($i=1;$i<=$list_num;$i++){
		//echo $i;

		$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  and suljung_no='".$_REQUEST["suljung_no_".$i]."'   ";
		db_query("update tbl_suljung set sugum_update='{$datex}'  $updatewhere ");  //최신날짜 갱신

		if(trim($_REQUEST["biyong_c_date1_".$i])=="y"){
			$KEY1 = array();
			$KEY1["a1"] = $_REQUEST["a1_".$i];
			$KEY1["h_idx"] = $_REQUEST["h_idx_".$i];
			$KEY1["suljung_no"] = $_REQUEST["suljung_no_".$i];
			$KEY1["biyong_c_date"] = $_REQUEST["biyong_c_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  and suljung_no='".$_REQUEST["suljung_no_".$i]."'   ";
			//print_r($KEY1);
			//echo $KEY["biyong_c_date"];
			db_replace($KEY1,"tbl_sugum",$updatewhere,"a1");
		}

		if(trim($_REQUEST["ibgum_date11_".$i])=="y"){
			$KEY2 = array();
			$KEY2["a1"] = $_REQUEST["a1_".$i];
			$KEY2["h_idx"] = $_REQUEST["h_idx_".$i];
			$KEY2["suljung_no"] = $_REQUEST["suljung_no_".$i];
			$KEY2["ibgum_date1"] = $_REQUEST["ibgum_date1_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' and suljung_no='".$_REQUEST["suljung_no_".$i]."' ";
			db_replace($KEY2,"tbl_sugum",$updatewhere,"a1");
		}

		if(trim($_REQUEST["ibgum_date21_".$i])=="y"){
			$KEY3 = array();
			$KEY3["a1"] = $_REQUEST["a1_".$i];
			$KEY3["h_idx"] = $_REQUEST["h_idx_".$i];
			$KEY3["suljung_no"] = $_REQUEST["suljung_no_".$i];
			$KEY3["ibgum_date2"] = $_REQUEST["ibgum_date2_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' and suljung_no='".$_REQUEST["suljung_no_".$i]."' ";
			db_replace($KEY3,"tbl_sugum",$updatewhere,"a1");
		}

			$KEY4 = array();
			$KEY4["a1"] = $_REQUEST["a1_".$i];
			$KEY4["h_idx"] = $_REQUEST["h_idx_".$i];
			$KEY4["h_idx"] = $_REQUEST["h_idx_".$i];
			$KEY4["suljung_no"] = $_REQUEST["suljung_no_".$i];
			$KEY4["ibgum_money1"] = f_de_comma($_REQUEST["ibgum_money1_".$i]);
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' and suljung_no='".$_REQUEST["suljung_no_".$i]."' ";
			db_replace($KEY4,"tbl_sugum",$updatewhere,"a1");

			$KEY5 = array();
			$KEY5["a1"] = $_REQUEST["a1_".$i];
			$KEY5["h_idx"] = $_REQUEST["h_idx_".$i];
			$KEY5["suljung_no"] = $_REQUEST["suljung_no_".$i];
			$KEY5["ibgum_money2"] = f_de_comma($_REQUEST["ibgum_money2_".$i]);
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' and suljung_no='".$_REQUEST["suljung_no_".$i]."' ";
			db_replace($KEY5,"tbl_sugum",$updatewhere,"a1");

		if(trim($_REQUEST["card_gubun11_".$i])=="y"){
			$KEY6 = array();
			$KEY6["a1"] = $_REQUEST["a1_".$i];
			$KEY6["h_idx"] = $_REQUEST["h_idx_".$i];
			$KEY6["suljung_no"] = $_REQUEST["suljung_no_".$i];
			$KEY6["card_gubun1"] = $_REQUEST["card_gubun1_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  and suljung_no='".$_REQUEST["suljung_no_".$i]."' ";
			db_replace($KEY6,"tbl_sugum",$updatewhere,"a1");
		}

		if(trim($_REQUEST["card_gubun21_".$i])=="y"){
			$KEY7 = array();
			$KEY7["a1"] = $_REQUEST["a1_".$i];
			$KEY7["h_idx"] = $_REQUEST["h_idx_".$i];
			$KEY7["suljung_no"] = $_REQUEST["suljung_no_".$i];
			$KEY7["card_gubun2"] = $_REQUEST["card_gubun2_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' and suljung_no='".$_REQUEST["suljung_no_".$i]."' ";
			db_replace($KEY7,"tbl_sugum",$updatewhere,"a1");
		}

		if(trim($_REQUEST["confirm_date1_".$i])=="y"){
			$KEY8 = array();
			$KEY8["a1"] = $_REQUEST["a1_".$i];
			$KEY8["h_idx"] = $_REQUEST["h_idx_".$i];
			$KEY8["suljung_no"] = $_REQUEST["suljung_no_".$i];
			$KEY8["confirm_date"] = $_REQUEST["confirm_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' and suljung_no='".$_REQUEST["suljung_no_".$i]."' ";
			db_replace($KEY8,"tbl_sugum",$updatewhere,"a1");
		}

			$KEY9 = array();
			$KEY9["a1"] = $_REQUEST["a1_".$i];
			$KEY9["h_idx"] = $_REQUEST["h_idx_".$i];
			$KEY9["suljung_no"] = $_REQUEST["suljung_no_".$i];
			$KEY9["sugum_memo"] = $_REQUEST["sugum_memo_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' and suljung_no='".$_REQUEST["suljung_no_".$i]."' ";
			db_replace($KEY9,"tbl_sugum",$updatewhere,"a1");

			$KEY10 = array();
			$KEY10["a1"] = $_REQUEST["a1_".$i];
			$KEY10["h_idx"] = $_REQUEST["h_idx_".$i];
			$KEY10["ijp_s_memo"] = $_REQUEST["ijp_s_memo_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY10,"tbl_junib",$updatewhere,"a1");

	}

		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<html>";
		echo "<body>";
		echo "<center>";
		echo "<br>";
		echo "<input type=button value=' 처리 완료! ' onclick='javascript:opener.document.ffx.submit();window.close();'>";
		echo "</center>";
		echo "</body>";
		echo "</html>";
		exit;

?>
