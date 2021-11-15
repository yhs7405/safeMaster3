<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	$p1	= $_REQUEST[p1];  //은행코드
	$h_idx	= $_REQUEST[h_idx];  //현장코드

if($h_idx==""){
	$sql = "select jijum_code,jijum_name from tbl_bank_jijum where bank_code = '$p1' order by bank_code,jijum_code asc ";
}else{
	$sql = "select b.jijum_code,b.jijum_name from tbl_bank_jijum b left join tbl_junib j on b.jijum_code=j.e1 where j.h_idx={$h_idx} and j.d1='{$p1}'  group by b.jijum_code order by b.jijum_code,b.jijum_name asc  ";
}

	//echo $sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	//echo $rows;

	if($rows==0){
			$json_arr[0] = Array("jijum_code"=>"", "jijum_name"=>"--지점--");
	}else{
			$json_arr[0] = Array("jijum_code"=>"", "jijum_name"=>"--지점--");
		$count=1;
		while($row = $stmt->fetch()){
			$json_arr[$count] = Array("jijum_code"=>$row[jijum_code], "jijum_name"=>urlencode($row[jijum_name]));
			$count++;
		}
	}

	echo urldecode(json_encode($json_arr));

?>