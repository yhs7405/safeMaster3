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

		if(trim($_REQUEST["balance_date1_".$i])=="y"){
			$KEY1 = array();
			$KEY1["a1"] = $_REQUEST["a1_".$i];
			$KEY1["balance_date"] = $_REQUEST["balance_date_".$i];
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
//			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
//			print_r($KEY1);
//			print_r($updatewhere);
//			echo $KEY["doc_receive_date"];
			db_replace($KEY1,$board_dbname,$updatewhere,"a1");
		}

			$KEY2 = array();
			$KEY2["a1"] = $_REQUEST["a1_".$i];
			$KEY2["bunyang_cost"] = f_de_comma($_REQUEST["bunyang_cost_".$i]);
			$KEY2["balkoni_cost"] = f_de_comma($_REQUEST["balkoni_cost_".$i]);
			$KEY2["option1_cost"] = f_de_comma($_REQUEST["option1_cost_".$i]);
			$KEY2["option2_cost"] = f_de_comma($_REQUEST["option2_cost_".$i]);
			$KEY2["option3_cost"] = f_de_comma($_REQUEST["option3_cost_".$i]);
			$KEY2["option4_cost"] = f_de_comma($_REQUEST["option4_cost_".$i]);
			$KEY2["discount_cost"] = f_de_comma($_REQUEST["discount_cost_".$i]);
			$KEY2["vat"] = f_de_comma($_REQUEST["vat_".$i]);
			$KEY2["pre_cost"] = f_de_comma($_REQUEST["pre_cost_".$i]);
//			$KEY2["af1"] = f_de_comma($_REQUEST["af1_".$i]);
			$KEY2["af1"] = $KEY2["bunyang_cost"] + $KEY2["balkoni_cost"] + $KEY2["option1_cost"] + $KEY2["option2_cost"] + $KEY2["option3_cost"] + $KEY2["option4_cost"] - ($KEY2["discount_cost"] + $KEY2["vat"]) + $KEY2["pre_cost"];
	// 인지산정과표 계산
			$KEY2["am1_table"] = $KEY2["bunyang_cost"] + $KEY2["balkoni_cost"] + $KEY2["option1_cost"] + $KEY2["option2_cost"] + $KEY2["option3_cost"] + $KEY2["option4_cost"] - ($KEY2["discount_cost"] + $KEY2["vat"]) ;

		if($KEY2[u1_gubun]=="y"){  //전매가 1회라도 있는경우
			if($KEY2[am1_table]<"1000000000"){
				$KEY2[am1_pur_cost] = "150000";
			} else if($KEY2[am1_table]>="1000000000"){
				if($af1<"1000000000"){
					$KEY2[am1_pur_cost] = "500000"; //35만원 + 15만원
				} else if($af1>="1000000000"){
					$KEY2[am1_pur_cost] = "700000"; //35만원 + 35만원
				}
			}
		}else if($KEY2[u1_gubun]=="n"){
			if($af1<"1000000000"){
				$KEY2[am1_pur_cost] = "150000"; //15만원
			} else if($af1>="1000000000"){
				$KEY2[am1_pur_cost] = "350000"; //35만원
			}
		}
		
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