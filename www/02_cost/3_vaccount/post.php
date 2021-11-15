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


		if(trim($_REQUEST["woori_sand_date1_".$i])=="y"){
			$KEY1 = array();
			$KEY1["a1"] = $_REQUEST["a1_".$i];
			$KEY1["woori_sand_date"] = $_REQUEST["woori_sand_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
//			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			print_r($KEY1);
//			print_r($updatewhere);
			print_r( $KEY2["vir_acc_no"]);
			db_replace($KEY1,$board_dbname,$updatewhere,"a1");
		}

			$KEY2 = array();
			$KEY2["a1"] = $_REQUEST["a1_".$i];
			$KEY2["ai1"] = f_de_comma($_REQUEST["ai1_".$i]);
			$KEY2["vir_acc_no"] = $_REQUEST["vir_acc_no_".$i];
			if($KEY2["ai1"]=="" || $KEY2["ai1"]=="0"){
				$KEY2["ah1"] = "";
			}
			if($KEY2["vir_acc_no"]==""){
				$KEY2["woori_sand_date"] = "";
			}
			$KEY2["dup_check"] = $_REQUEST["dup_check_".$i];
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