<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";
	$board_dbname2	=	"tbl_suljung";

	$list_num	=	trim($_REQUEST[list_num]);
	$page		=	trim($_REQUEST[page]);
	if($list_num=="")	$list_num=20;
	if($page=="")		$page=1;

	//print_r($_REQUEST);

	$datex = date("YmdHis");
	for($i=1;$i<=$list_num;$i++){
//		echo $i;
		$f1 = trim($_REQUEST["f1_".$i]);

		if(trim($_REQUEST["building_trust_date1_".$i])=="y"){
			$KEY1 = array();
			$KEY1["a1"] = $_REQUEST["a1_".$i];
			$KEY1["building_trust_date"] = $_REQUEST["building_trust_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
//			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
//			print_r($KEY1);
//			print_r($updatewhere);
//			echo $KEY["doc_receive_date"];
			db_replace($KEY1,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["building_trust_org_date1_".$i])=="y"){
			$KEY2 = array();
			$KEY2["a1"] = $_REQUEST["a1_".$i];
			$KEY2["building_trust_org_date"] = $_REQUEST["building_trust_org_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY2,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["man_fin_date1_".$i])=="y"){
			$KEY3 = array();
			$KEY3["a1"] = $_REQUEST["a1_".$i];
			$KEY3["man_fin_date"] = $_REQUEST["man_fin_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
			db_replace($KEY3,$board_dbname,$updatewhere,"a1");
		}

		if(trim($_REQUEST["reg_cause_date11_".$i])=="y"){
			$KEY4 = array();
			if($f1>"0"){
				$KEY4["a1"] = $_REQUEST["a1_".$i];
				$KEY4["suljung_no"] = "1";
				$KEY4["reg_cause_date"] = $_REQUEST["reg_cause_date1_".$i];
				$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
				$updatewhere.= "   AND suljung_no = '1' ";
				//			print_r($board_dbname2);
				//			print_r($updatewhere);

				db_replace($KEY4,$board_dbname2,$updatewhere,"a1");
			}
		}

		if(trim($_REQUEST["reg_cause_date21_".$i])=="y"){
			$KEY5 = array();
			if($f1>"1"){
				$KEY5["a1"] = $_REQUEST["a1_".$i];
				$KEY5["suljung_no"] = "2";
				$KEY5["reg_cause_date"] = $_REQUEST["reg_cause_date2_".$i];
				$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
				$updatewhere.= "   AND suljung_no = '2' ";
				db_replace($KEY5,$board_dbname2,$updatewhere,"a1");
			}
		}

		if(trim($_REQUEST["reg_cause_date31_".$i])=="y"){
			$KEY6 = array();
			if($f1>"2"){
				$KEY6["a1"] = $_REQUEST["a1_".$i];
				$KEY6["suljung_no"] = "3";
				$KEY6["reg_cause_date"] = $_REQUEST["reg_cause_date3_".$i];
				$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
				$updatewhere.= "   AND suljung_no = '3' ";
				db_replace($KEY6,$board_dbname2,$updatewhere,"a1");
			}
		}

		if(trim($_REQUEST["reg_cause_date41_".$i])=="y"){
			$KEY7 = array();
			if($f1>"3"){
				$KEY7["a1"] = $_REQUEST["a1_".$i];
				$KEY7["suljung_no"] = "4";
				$KEY7["reg_cause_date"] = $_REQUEST["reg_cause_date4_".$i];
				$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
				$updatewhere.= "   AND suljung_no = '4' ";
				db_replace($KEY7,$board_dbname2,$updatewhere,"a1");
			}
		}


			$KEY10 = array();
			$KEY10["a1"] = $_REQUEST["a1_".$i];
			$KEY10["building_trust_no"] = $_REQUEST["building_trust_no_".$i];
			$KEY10["building_trust_org_no"] = $_REQUEST["building_trust_org_no_".$i];
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