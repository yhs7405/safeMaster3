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

		if(trim($_REQUEST["pre_spur_date1_".$i])=="y"){
			$KEY1 = array();
			$KEY1["a1"] = $_REQUEST["a1_".$i];
			$KEY1["pre_spur_date"] = $_REQUEST["pre_spur_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
//			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
//			print_r($KEY1);
//			print_r($updatewhere);
//			echo $KEY["doc_receive_date"];
			db_replace($KEY1,$board_dbname,$updatewhere,"a1");
		}

			$KEY2 = array();
			$KEY2["a1"] = $_REQUEST["a1_".$i];
			$KEY2["ba1"] = f_de_comma($_REQUEST["ba1_".$i]);
			$KEY2["bb1"] = f_de_comma($_REQUEST["bb1_".$i]);
			$KEY2["bc1"] = f_de_comma($_REQUEST["bc1_".$i]);
			$KEY2["bd1"] = f_de_comma($_REQUEST["bd1_".$i]);
			$KEY2["me_spur_yn"] = $_REQUEST["me_spur_yn_".$i];
			$KEY2["me_sprepur_cost1"] = f_de_comma($_REQUEST["me_sprepur_cost1_".$i]);
			$KEY2["me_sprepur_cost2"] = f_de_comma($_REQUEST["me_sprepur_cost2_".$i]);
			$KEY2["au1"] = $_REQUEST["au1_".$i];
			$KEY2["ae1"] = $_REQUEST["ae1_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY2,$board_dbname,$updatewhere,"a1");

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