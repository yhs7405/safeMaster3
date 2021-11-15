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

		if(trim($_REQUEST["doc_receive_date1_".$i])=="y"){
			$KEY1 = array();
			$KEY1["a1"] = $_REQUEST["a1_".$i];
			$KEY1["doc_receive_date"] = $_REQUEST["doc_receive_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
//			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
//			print_r($KEY1);
//			print_r($updatewhere);
//			echo $KEY["doc_receive_date"];
			db_replace($KEY1,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["comp_req_date1_".$i])=="y"){
			$KEY2 = array();
			$KEY2["a1"] = $_REQUEST["a1_".$i];
			$KEY2["comp_req_date"] = $_REQUEST["comp_req_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY2,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["comp_rec_date1_".$i])=="y"){
			$KEY3 = array();
			$KEY3["a1"] = $_REQUEST["a1_".$i];
			$KEY3["comp_rec_date"] = $_REQUEST["comp_rec_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY3,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["prob_apply_date1_".$i])=="y"){
			$KEY4 = array();
			$KEY4["a1"] = $_REQUEST["a1_".$i];
			$KEY4["prob_apply_date"] = $_REQUEST["prob_apply_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY4,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["bond_req_date1_".$i])=="y"){
			$KEY5 = array();
			$KEY5["a1"] = $_REQUEST["a1_".$i];
			$KEY5["bond_req_date"] = $_REQUEST["bond_req_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY5,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["bond_1conf_date1_".$i])=="y"){
			$KEY6 = array();
			$KEY6["a1"] = $_REQUEST["a1_".$i];
			$KEY6["bond_1conf_date"] = $_REQUEST["bond_1conf_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY6,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["bond_2conf_date1_".$i])=="y"){
			$KEY7 = array();
			$KEY7["a1"] = $_REQUEST["a1_".$i];
			$KEY7["bond_2conf_date"] = $_REQUEST["bond_2conf_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY7,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["ch1_junib_date1_".$i])=="y"){
			$KEY8 = array();
			$KEY8["a1"] = $_REQUEST["a1_".$i];
			$KEY8["ch1_junib_date"] = $_REQUEST["ch1_junib_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY8,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["pred_g11_".$i])=="y"){
			$KEY9 = array();
			$KEY9["a1"] = $_REQUEST["a1_".$i];
			$KEY9["pred_g1"] = $_REQUEST["pred_g1_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY9,$board_dbname,$updatewhere,"a1");
		}

			$KEY10 = array();
			$KEY10["a1"] = $_REQUEST["a1_".$i];
			$KEY10["av1"] = f_de_comma($_REQUEST["av1_".$i]);
			$KEY10["ax1"] = f_de_comma($_REQUEST["ax1_".$i]);
			$KEY10["ay1"] = f_de_comma($_REQUEST["ay1_".$i]);
			$KEY10["az1"] = f_de_comma($_REQUEST["az1_".$i]);
			$KEY10["au1"] = $_REQUEST["au1_".$i];
			$KEY10["ad1"] = $_REQUEST["ad1_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY10,$board_dbname,$updatewhere,"a1");

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