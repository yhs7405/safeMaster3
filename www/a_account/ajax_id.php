<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$id = trim($_REQUEST[id]);
	$sql= "select * from tbl_user where id='{$id}' ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	if($row[idx]==""){  //중복아님
		echo "y";
	}else{
		echo "n";
	}
?>
