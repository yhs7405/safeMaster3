<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	$h_idx = trim($_REQUEST[h_idx]);

	$sql = "select b.bank_code,b.bank_name from tbl_bank_info b left join tbl_junib j on b.bank_code=j.d1 where j.h_idx={$h_idx}  group by b.bank_code order by b.bank_code,b.bank_name asc  ";

	//echo $sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	//echo $rows;

	if($rows==0){
			$json_arr[0] = Array("bank_code"=>"", "bank_name"=>"--은행--");
	}else{
			$json_arr[0] = Array("bank_code"=>"", "bank_name"=>"--은행--");
		$count=1;
		while($row = $stmt->fetch()){
			$json_arr[$count] = Array("bank_code"=>$row[bank_code], "bank_name"=>urlencode($row[bank_name]));
			$count++;
		}
	}

	echo urldecode(json_encode($json_arr));

?>
