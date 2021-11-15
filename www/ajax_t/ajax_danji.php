<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	$h_idx = trim($_REQUEST[h_idx]);  //현장코드

	if($h_idx==""){
		$sql = "select danji_name as b1, danji_name from tbl_hyunjang_danji_info where 1=1 and h_idx = '' order by danji_name asc  ";
	}else{
		$sql = "select danji_name as b1, danji_name from tbl_hyunjang_danji_info where 1=1 and h_idx = {$h_idx} order by danji_name asc  ";
	}


	//echo $sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	//echo $rows;

	if($rows==0){
//			$json_arr[0] = Array("b1"=>"", "danji_name"=>"--선택--");
	}else{
//			$json_arr[0] = Array("b1"=>"", "danji_name"=>"--선택--");
		$count=1;
		while($row = $stmt->fetch()){
			$json_arr[$count] = Array("b1"=>$row[b1], "danji_name"=>urlencode($row[danji_name]));
			$count++;
		}
	}

	echo urldecode(json_encode($json_arr));

?>