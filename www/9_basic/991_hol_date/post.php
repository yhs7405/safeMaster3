<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

	$board_dbname	=	"tbl_holiday_set";

	$i_year	=	trim($_REQUEST[i_year]);

	//print_r($_REQUEST);
//			echo $i_year;
//			echo $board_dbname;

	$job_id      = $_SESSION["admin_id"];
	$job_date = date("YmdHis");
			//echo $job_date;
	$sql = "delete from tbl_holiday_set where h_year='{$i_year}' ";
	$r1 = db_query($sql);

	for($i=1;$i<=24;$i++){
//		echo $i;
//			$updatewhere = " WHERE a1 = '".$_REQUEST["a1_".$i]."'  ";
//			print_r($KEY);
//			print_r($updatewhere);
//			echo $KEY["doc_receive_date"];

			$KEY = array();
			$KEY["h_year"] = $i_year;
			$KEY["h_month"] = $_REQUEST["h_month_".$i];
			$KEY["h_day"] = $_REQUEST["h_day_".$i];
			if($KEY["h_month"]!=""&&$KEY["h_day"]!=""){
				$KEY["h_date"] = $i_year.$_REQUEST["h_month_".$i].$_REQUEST["h_day_".$i];
			} else {
				$KEY["h_date"] = "";
				$KEY["h_month"] = "";
				$KEY["h_day"] = "";
			}
			if ($KEY["h_date"]!="") {
				$KEY["h_name"] = $_REQUEST["h_name_".$i];
				$KEY["job_date"] = $job_id;
				$KEY["job_id"] = $job_date;
				//echo $KEY["h_date"]."h_date/";
				//echo $KEY["h_year"]."h_year/";
				//echo $KEY["h_month"]."h_month/";
				//echo $KEY["h_day"]."h_day/";
				//echo $KEY["h_name"]."h_name/<br>";
				$updatewhere = " WHERE h_name = '".$KEY["h_name"]."' and h_year = '".$KEY["h_year"]."' ";
	//			echo $updatewhere."/";
				db_replace($KEY,$board_dbname,$updatewhere,"h_date");
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