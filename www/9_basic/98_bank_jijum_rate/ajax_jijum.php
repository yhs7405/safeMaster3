<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_bank_jijum_rate";

	//$bank_code		=	trim($_REQUEST[bank_code]);

	$h_idx				=	trim($_REQUEST[h_idx]);
	$bank_code			=	trim($_REQUEST[bank_code]);
	$jijum_code			=	trim($_REQUEST[jijum_code]);
	$gubun_code			=	trim($_REQUEST[gubun_code]);

	$wherequery = " WHERE bank_code='{$bank_code}' and jijum_code='{$jijum_code}' and gubun_code='{$gubun_code}' and basic_gukto='basic' ";
	$sql= "select * from $board_dbname ".$wherequery;
	//echo $sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();
	
	if($row){
		$KEY[result]			=	"y";

		$KEY[b_basic_bosu]		=	f_money($row[b_basic_bosu]);
		$KEY[b_julsak_position]		=	f_money($row[b_julsak_position]);
		$KEY[b_nujin_bosu]		=	f_null($row[b_nujin_bosu]);
		$KEY[b_halin]			=	f_money($row[b_halin]);
		$KEY[b_singonabbu]		=	f_money($row[b_singonabbu]);
		$KEY[b_kyotong]			=	f_money($row[b_kyotong]);
		$KEY[b_woninjungseo]		=	f_money($row[b_woninjungseo]);

		$KEY[g_singonabbu]		=	f_money($row[g_singonabbu]);
		$KEY[g_jungjidae]		=	f_money($row[g_jungjidae]);
		$KEY[g_kyotong]			=	f_money($row[g_kyotong]);
		$KEY[g_jejungmyung]		=	f_money($row[g_jejungmyung]);
		$KEY[g_yeolamjunggi]		=	f_money($row[g_yeolamjunggi]);
		$KEY[g_deungchobon]		=	f_money($row[g_deungchobon]);
		$KEY[g_jibaeinchobon]		=	f_money($row[g_jibaeinchobon]);

		$KEY[b_singonabbu_ch]		=	f_null($row[b_singonabbu_ch]);
		$KEY[b_kyotong_ch]		=	f_null($row[b_kyotong_ch]);
		$KEY[g_singonabbu_ch]		=	f_null($row[g_singonabbu_ch]);
		$KEY[g_kyotong_ch]		=	f_null($row[g_kyotong_ch]);

	}else{
		$KEY[result]			=	"n";
		$KEY[b_basic_bosu]		=	"";
		$KEY[b_julsak_position]		=	"";
		$KEY[b_nujin_bosu]		=	"";
		$KEY[b_halin]			=	"";
		$KEY[b_singonabbu]		=	"";
		$KEY[b_kyotong]			=	"";
		$KEY[b_woninjungseo]		=	"";

		$KEY[g_singonabbu]		=	"";
		$KEY[g_jungjidae]		=	"";
		$KEY[g_kyotong]			=	"";
		$KEY[g_jejungmyung]		=	"";
		$KEY[g_yeolamjunggi]		=	"";
		$KEY[g_deungchobon]		=	"";
		$KEY[g_jibaeinchobon]		=	"";

		$KEY[b_singonabbu_ch]		=	"";
		$KEY[b_kyotong_ch]		=	"";
		$KEY[g_singonabbu_ch]		=	"";
		$KEY[g_kyotong_ch]		=	"";
	}


	$wherequery = " WHERE bank_code='{$bank_code}' and jijum_code='{$jijum_code}' and gubun_code='{$gubun_code}' and basic_gukto='basic' ";
	$sql= "select * from $board_dbname ".$wherequery;
	//echo $sql;

	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row1 = $stmt1->fetch();
	
	if($row1){
		$KEY[result_1]			=	"y";

		$KEY[b_basic_bosu_1]		=	f_money($row1[b_basic_bosu]);
		$KEY[b_julsak_position_1]	=	f_money($row1[b_julsak_position]);
		$KEY[b_nujin_bosu_1]		=	f_null($row1[b_nujin_bosu]);
		$KEY[b_halin_1]				=	f_money($row1[b_halin]);
		$KEY[b_singonabbu_1]		=	f_money($row1[b_singonabbu]);
		$KEY[b_kyotong_1]			=	f_money($row1[b_kyotong]);
		$KEY[b_woninjungseo_1]		=	f_money($row1[b_woninjungseo]);

		$KEY[g_singonabbu_1]		=	f_money($row1[g_singonabbu]);
		$KEY[g_jungjidae_1]			=	f_money($row1[g_jungjidae]);
		$KEY[g_kyotong_1]			=	f_money($row1[g_kyotong]);
		$KEY[g_jejungmyung_1]		=	f_money($row1[g_jejungmyung]);
		$KEY[g_yeolamjunggi_1]		=	f_money($row1[g_yeolamjunggi]);
		$KEY[g_deungchobon_1]		=	f_money($row1[g_deungchobon]);
		$KEY[g_jibaeinchobon_1]		=	f_money($row1[g_jibaeinchobon]);

		$KEY[b_singonabbu_ch_1]		=	f_null($row1[b_singonabbu_ch]);
		$KEY[b_kyotong_ch_1]		=	f_null($row1[b_kyotong_ch]);
		$KEY[g_singonabbu_ch_1]		=	f_null($row1[g_singonabbu_ch]);
		$KEY[g_kyotong_ch_1]		=	f_null($row1[g_kyotong_ch]);

	}else{
		$KEY[result_1]				=	"n";
		$KEY[b_basic_bosu_1]		=	"";
		$KEY[b_julsak_position_1]	=	"";
		$KEY[b_nujin_bosu_1]		=	"";
		$KEY[b_halin_1]				=	"";
		$KEY[b_singonabbu_1]		=	"";
		$KEY[b_kyotong_1]			=	"";
		$KEY[b_woninjungseo_1]		=	"";

		$KEY[g_singonabbu_1]		=	"";
		$KEY[g_jungjidae_1]			=	"";
		$KEY[g_kyotong_1]			=	"";
		$KEY[g_jejungmyung_1]		=	"";
		$KEY[g_yeolamjunggi_1]		=	"";
		$KEY[g_deungchobon_1]		=	"";
		$KEY[g_jibaeinchobon_1]		=	"";

		$KEY[b_singonabbu_ch_1]		=	"";
		$KEY[b_kyotong_ch_1]		=	"";
		$KEY[g_singonabbu_ch_1]		=	"";
		$KEY[g_kyotong_ch_1]		=	"";
	}
	echo urldecode(json_encode($KEY));
?>