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

		if(trim($_REQUEST["gch1_cost_date1_".$i])=="y"){
			$KEY1 = array();
			$KEY1["a1"] = $_REQUEST["a1_".$i];
			$KEY1["gch1_cost_date"] = $_REQUEST["gch1_cost_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
//			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
//			print_r($KEY1);
//			print_r($updatewhere);
//			echo $KEY["doc_receive_date"];
			db_replace($KEY1,$board_dbname,$updatewhere,"a1");
		}


		if(trim($_REQUEST["gch2_cost_date1_".$i])=="y"){
			$KEY2 = array();
			$KEY2["a1"] = $_REQUEST["a1_".$i];
			$KEY2["gch2_cost_date"] = $_REQUEST["gch2_cost_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY2,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["dun_cost_date1_".$i])=="y"){
			$KEY3 = array();
			$KEY3["a1"] = $_REQUEST["a1_".$i];
			$KEY3["dun_cost_date"] = $_REQUEST["dun_cost_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY3,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["pay_dead_date1_".$i])=="y"){
			$KEY4 = array();
			$KEY4["a1"] = $_REQUEST["a1_".$i];
			$KEY4["pay_dead_date"] = $_REQUEST["pay_dead_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY4,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["bond_sale_rate1_".$i])=="y"){
			$KEY5 = array();
			$KEY5["a1"] = $_REQUEST["a1_".$i];
			$KEY5["bond_sale_rate"] = f_de_comma($_REQUEST["bond_sale_rate_".$i]);
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY5,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["an1_by1_".$i])=="y"){
			$KEY6 = array();
			$KEY6["a1"] = $_REQUEST["a1_".$i];
			$KEY6["an1_by"] = f_de_comma($_REQUEST["an1_by_".$i]);
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY6,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["as1_by1_".$i])=="y"){
			$KEY7 = array();
			$KEY7["a1"] = $_REQUEST["a1_".$i];
			$KEY7["as1_by"] = f_de_comma($_REQUEST["as1_by_".$i]);
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY7,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["ao1_by1_".$i])=="y"){
			$KEY8 = array();
			$KEY8["a1"] = $_REQUEST["a1_".$i];
			$KEY8["ao1_by"] = f_de_comma($_REQUEST["ao1_by_".$i]);
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY8,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["ap1_by1_".$i])=="y"){
			$KEY9 = array();
			$KEY9["a1"] = $_REQUEST["a1_".$i];
			$KEY9["ap1_by"] = f_de_comma($_REQUEST["ap1_by_".$i]);
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY9,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["aq1_by1_".$i])=="y"){
			$KEY10 = array();
			$KEY10["a1"] = $_REQUEST["a1_".$i];
			$KEY10["aq1_by"] = f_de_comma($_REQUEST["aq1_by_".$i]);
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY10,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["gch1_cost1_".$i])=="y"){
			$KEY11 = array();
			$KEY11["a1"] = $_REQUEST["a1_".$i];
			$KEY11["gch1_cost"] = f_de_comma($_REQUEST["gch1_cost_".$i]);
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY11,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["gch2_cost1_".$i])=="y"){
			$KEY12 = array();
			$KEY12["a1"] = $_REQUEST["a1_".$i];
			$KEY12["gch2_cost"] = f_de_comma($_REQUEST["gch2_cost_".$i]);
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY12,$board_dbname,$updatewhere,"a1");
		}

			$KEY13 = array();
			$KEY13["a1"] = $_REQUEST["a1_".$i];
			$KEY13["al1_hope_yn"] = $_REQUEST["al1_hope_yn_".$i];
			$KEY13["al1_imp_yn"] = $_REQUEST["al1_imp_yn_".$i];
			$KEY13["al1"] = f_de_comma($_REQUEST["al1_".$i]);
			$KEY13["am1_pur_cost"] = f_de_comma($_REQUEST["am1_pur_cost_".$i]);
			$KEY13["aj1_tmp"] = f_de_comma($_REQUEST["aj1_tmp_".$i]);
			$KEY13["ak1_tmp"] = f_de_comma($_REQUEST["ak1_tmp_".$i]);
			$KEY13["etc_cost_sum"] = f_de_comma($_REQUEST["etc_cost_sum_".$i]);
			$KEY13["total_sum"] = f_de_comma($_REQUEST["total_sum_".$i]);
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