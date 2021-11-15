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

		if(trim($_REQUEST["ba1_date1_".$i])=="y"){
			$KEY1 = array();
			$KEY1["a1"] = $_REQUEST["a1_".$i];
			$KEY1["ba1_date"] = $_REQUEST["ba1_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
//			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
//			print_r($KEY1);
//			print_r($updatewhere);
//			echo $KEY["doc_receive_date"];
			db_replace($KEY1,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["bb1_date1_".$i])=="y"){
			$KEY2 = array();
			$KEY2["a1"] = $_REQUEST["a1_".$i];
			$KEY2["bb1_date"] = $_REQUEST["bb1_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY2,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["bc1_date1_".$i])=="y"){
			$KEY3 = array();
			$KEY3["a1"] = $_REQUEST["a1_".$i];
			$KEY3["bc1_date"] = $_REQUEST["bc1_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY3,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["bd1_date1_".$i])=="y"){
			$KEY4 = array();
			$KEY4["a1"] = $_REQUEST["a1_".$i];
			$KEY4["bd1_date"] = $_REQUEST["bd1_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY4,$board_dbname,$updatewhere,"a1");
		}

			$KEY5 = array();
			$KEY5["a1"] = $_REQUEST["a1_".$i];
			$KEY5["z1"] = $_REQUEST["z1_".$i];
			$KEY5["ba1_cost"] = f_de_comma($_REQUEST["ba1_cost_".$i]);
			$KEY5["aa1"] = $_REQUEST["aa1_".$i];
			$KEY5["bb1_cost"] = f_de_comma($_REQUEST["bb1_cost_".$i]);
			$KEY5["ab1"] = $_REQUEST["ab1_".$i];
			$KEY5["bc1_cost"] = f_de_comma($_REQUEST["bc1_cost_".$i]);
			$KEY5["ac1"] = $_REQUEST["ac1_".$i];
			$KEY5["bd1_cost"] = f_de_comma($_REQUEST["bd1_cost_".$i]);
			$KEY5["ae1"] = $_REQUEST["ae1_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY5,$board_dbname,$updatewhere,"a1");

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