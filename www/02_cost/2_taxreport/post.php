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


		if(trim($_REQUEST["al1_acp_date1_".$i])=="y"){
			$KEY1 = array();
			$KEY1["a1"] = $_REQUEST["a1_".$i];
			$KEY1["al1_acp_date"] = $_REQUEST["al1_acp_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY1,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["al1_rec_date1_".$i])=="y"){
			$KEY2 = array();
			$KEY2["a1"] = $_REQUEST["a1_".$i];
			$KEY2["al1_rec_date"] = $_REQUEST["al1_rec_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY2,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["al1_reg_date1_".$i])=="y"){
			$KEY3 = array();
			$KEY3["a1"] = $_REQUEST["a1_".$i];
			$KEY3["al1_reg_date"] = $_REQUEST["al1_reg_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY3,$board_dbname,$updatewhere,"a1");
		}

			$KEY4 = array();
			$KEY4["a1"] = $_REQUEST["a1_".$i];
			$KEY4["al1_hope_yn"] = $_REQUEST["al1_hope_yn_".$i];
			$KEY4["al1_imp_yn"] = $_REQUEST["al1_imp_yn_".$i];
			$KEY4["elc_no"] = $_REQUEST["elc_no_".$i];
			$KEY4["al1_tax"] = f_de_comma($_REQUEST["al1_tax_".$i]);
			$KEY4["al1_edu"] = f_de_comma($_REQUEST["al1_edu_".$i]);
			$KEY4["al1_farm"] = f_de_comma($_REQUEST["al1_farm_".$i]);
			$KEY4["al1"] = f_de_comma($_REQUEST["al1_".$i]);
			$KEY4["ad1"] = $_REQUEST["ad1_".$i];
			//$KEY4["al1"] = $KEY4["al1_tax"] + $KEY4["al1_edu"] + $KEY4["al1_farm"];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY4,$board_dbname,$updatewhere,"a1");

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