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

		if(trim($_REQUEST["av1_dec_date1_".$i])=="y"){
			$KEY2 = array();
			$KEY2["a1"] = $_REQUEST["a1_".$i];
			$KEY2["av1_dec_date"] = $_REQUEST["av1_dec_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY2,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["ax1_dec_date1_".$i])=="y"){
			$KEY3 = array();
			$KEY3["a1"] = $_REQUEST["a1_".$i];
			$KEY3["ax1_dec_date"] = $_REQUEST["ax1_dec_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY3,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["ay1_dec_date1_".$i])=="y"){
			$KEY4 = array();
			$KEY4["a1"] = $_REQUEST["a1_".$i];
			$KEY4["ay1_dec_date"] = $_REQUEST["ay1_dec_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY4,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["az1_dec_date1_".$i])=="y"){
			$KEY5 = array();
			$KEY5["a1"] = $_REQUEST["a1_".$i];
			$KEY5["az1_dec_date"] = $_REQUEST["az1_dec_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY5,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["av1_rec_date1_".$i])=="y"){
			$KEY6 = array();
			$KEY6["a1"] = $_REQUEST["a1_".$i];
			$KEY6["av1_rec_date"] = $_REQUEST["av1_rec_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY6,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["ax1_rec_date1_".$i])=="y"){
			$KEY7 = array();
			$KEY7["a1"] = $_REQUEST["a1_".$i];
			$KEY7["ax1_rec_date"] = $_REQUEST["ax1_rec_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY7,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["ay1_rec_date1_".$i])=="y"){
			$KEY8 = array();
			$KEY8["a1"] = $_REQUEST["a1_".$i];
			$KEY8["ay1_rec_date"] = $_REQUEST["ay1_rec_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY8,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["az1_rec_date1_".$i])=="y"){
			$KEY9 = array();
			$KEY9["a1"] = $_REQUEST["a1_".$i];
			$KEY9["az1_rec_date"] = $_REQUEST["az1_rec_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY9,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["av1_pay_date1_".$i])=="y"){
			$KEY10 = array();
			$KEY10["a1"] = $_REQUEST["a1_".$i];
			$KEY10["av1_pay_date"] = $_REQUEST["av1_pay_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY10,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["ax1_pay_date1_".$i])=="y"){
			$KEY11 = array();
			$KEY11["a1"] = $_REQUEST["a1_".$i];
			$KEY11["ax1_pay_date"] = $_REQUEST["ax1_pay_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY11,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["ay1_pay_date1_".$i])=="y"){
			$KEY12 = array();
			$KEY12["a1"] = $_REQUEST["a1_".$i];
			$KEY12["ay1_pay_date"] = $_REQUEST["ay1_pay_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY12,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["az1_pay_date1_".$i])=="y"){
			$KEY1 = array();
			$KEY1["a1"] = $_REQUEST["a1_".$i];
			$KEY1["az1_pay_date"] = $_REQUEST["az1_pay_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
//			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
//			print_r($KEY1);
//			print_r($updatewhere);
//			echo $KEY["doc_receive_date"];
			db_replace($KEY1,$board_dbname,$updatewhere,"a1");
		}


			$KEY13 = array();
			$KEY13["a1"] = $_REQUEST["a1_".$i];
			$KEY13["au1"] = $_REQUEST["au1_".$i];
			$KEY13["ae1"] = $_REQUEST["ae1_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY13,$board_dbname,$updatewhere,"a1");

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