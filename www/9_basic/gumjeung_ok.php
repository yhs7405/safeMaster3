<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

	$board_dbname	=	"tbl_suljung";

	$h_idx			=	trim($_REQUEST[h_idx]);

	$wherequery = " where 1=1 ";
	$wherequery.= " and h_idx = '".$h_idx."' ";

	$sql = "select * from $board_dbname  $wherequery order by idx asc";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	if($rows > 0){
		while($row = $stmt->fetch()){
			$bosu = f_nujin_value($row[bank_code],$row[jijum_code],$row[chaekwon_max],$row[a1]);
			$sql = "update  tbl_suljung set suljung_bosu='".$bosu."' where idx='".$row[idx]."' and suljung_no='".$row[suljung_no]."' " ;
			//echo $sql."<br>";
			db_query($sql);

			$bosu_p = f_suljung_bosu($row[a1],$row[suljung_no],"");
			$bosu_v = f_suljung_bosu_vat($row[a1],$row[suljung_no],"");
			$sql = "update  tbl_suljung set bosu_price='".$bosu_p."' ,  bosu_price_vat='".$bosu_v."' where idx='".$row[idx]."' and suljung_no='".$row[suljung_no]."' " ;
			//echo $sql."<br>";
			db_query($sql);

		}
	}

	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<html>";
	echo "<body>";
	echo "<center>";
	echo "<br>";
	echo "<input type=button value=' 저장 완료 ' onclick='javascript:opener.document.ff.submit();;window.close();'>";
	echo "</center>";
	echo "</body>";
	echo "</html>";
	exit;
?>