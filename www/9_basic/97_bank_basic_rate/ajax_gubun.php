<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_bank_basic_rate";

	$bank_code		=	trim($_REQUEST[bank_code]);
	$gubun_code		=	trim($_REQUEST[gubun_code]);
	$basic_gukto	=	trim($_REQUEST[basic_gukto]);

	$wherequery = " WHERE bank_code='{$bank_code}' and  gubun_code='{$gubun_code}' and basic_gukto='{$basic_gukto}' ";

	$sql= "select * from $board_dbname ".$wherequery;
	//echo $sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();
	
	if($row){
		$KEY[result]			=	"y";
		$KEY[b_basic_bosu]		=	f_money($row[b_basic_bosu]);
		$KEY[b_julsak_position]	=	f_money($row[b_julsak_position]);
		$KEY[b_nujin_bosu]		=	f_money($row[b_nujin_bosu]);
		$KEY[b_halin]			=	f_money($row[b_halin]);
		$KEY[b_singonabbu]		=	f_money($row[b_singonabbu]);
		$KEY[b_kyotong]			=	f_money($row[b_kyotong]);
		$KEY[b_woninjungseo]	=	f_money($row[b_woninjungseo]);
		
		$KEY[g_singonabbu]		=	f_money($row[g_singonabbu]);
		$KEY[g_jungjidae]		=	f_money($row[g_jungjidae]);
		$KEY[g_kyotong]			=	f_money($row[g_kyotong]);
		$KEY[g_jejungmyung]		=	f_money($row[g_jejungmyung]);
		$KEY[g_yeolamjunggi]	=	f_money($row[g_yeolamjunggi]);
		$KEY[g_deungchobon]		=	f_money($row[g_deungchobon]);
		$KEY[g_jibaeinchobon]	=	f_money($row[g_jibaeinchobon]);
	}else{
		$KEY[result]			=	"n";
		$KEY[b_basic_bosu]		=	"";
		$KEY[b_julsak_position]	=	"";
		$KEY[b_nujin_bosu]		=	"";
		$KEY[b_halin]			=	"";
		$KEY[b_singonabbu]		=	"";
		$KEY[b_kyotong]			=	"";
		$KEY[b_woninjungseo]	=	"";

		$KEY[g_singonabbu]		=	"";
		$KEY[g_jungjidae]		=	"";
		$KEY[g_kyotong]			=	"";
		$KEY[g_jejungmyung]		=	"";
		$KEY[g_yeolamjunggi]	=	"";
		$KEY[g_deungchobon]		=	"";
		$KEY[g_jibaeinchobon]	=	"";
	}
	echo urldecode(json_encode($KEY));
?>