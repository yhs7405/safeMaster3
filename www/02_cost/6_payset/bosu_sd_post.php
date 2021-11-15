<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

	$board_dbname	=	"tbl_bosu_cost_manual";

	$list_num	=	trim($_REQUEST[list_num]);
	$page		=	trim($_REQUEST[page]);

	$h_idx	=	trim($_REQUEST[h_idx]);
	$job_id      = $_SESSION["admin_id"];
	$job_date = date("YmdHis");

	if($list_num=="")	$list_num=20;
	if($page=="")		$page=1;

	//print_r($_REQUEST);

	$datex = date("YmdHis");
	for($i=1;$i<=$list_num;$i++){
			//print_r($KEY10);
		//echo $KEY10;
 		if(trim($_REQUEST["a1_".$i])!=""){

			$KEY10 = array();
			$KEY10["a1"] = $_REQUEST["a1_".$i];
			$KEY10["h_idx"] = $h_idx;
			$KEY10["ijeon_bosu_cost"] = f_de_comma($_REQUEST["ijeon_bosu_cost_".$i]);
			$KEY10["ijeon_bosu_vat"] = f_de_comma($_REQUEST["ijeon_bosu_vat_".$i]);
			$KEY10["proof_cost"] = f_de_comma($_REQUEST["proof_cost_".$i]);
			$KEY10["sintak_bosu_cost"] = f_de_comma($_REQUEST["sintak_bosu_cost_".$i]);
			$KEY10["sintak_bosu_vat"] = f_de_comma($_REQUEST["sintak_bosu_vat_".$i]);
			$KEY10["job_id"] = $job_id;
			$KEY10["job_date"] = $job_date;
			//print_r($KEY10);
			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."' ";
			db_replace($KEY10,$board_dbname,$updatewhere,"a1");
		}
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