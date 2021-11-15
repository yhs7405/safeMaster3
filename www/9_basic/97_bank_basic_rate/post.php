<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$tablename = "tbl_bank_basic_rate";

	//print_r($_REQUEST);
	
	$mode = trim($_REQUEST[mode]);

	$bank_code			=	trim($_REQUEST[bank_code]);	//은행
	$gubun_code			=	trim($_REQUEST[gubun_code]);	//구분-기본/본점제휴 - basic/bonjum


 if($mode=="i"){ //신규이면

	$sql = "select * from tbl_bank_basic_rate where bank_code='{$bank_code}' and gubun_code='{$gubun_code}' ";
	$bxx = db_query_fetch($sql);
	if($bxx[idx]!=""){ //중복된다면 오류
		echo "<script>alert('이미 등록되어있는 은행기본비용 정보 입니다.');location.href='regist.php';</script>";
	}

	$KEY[bank_code]			=	trim($_REQUEST[bank_code]);		//은행
	$KEY[gubun_code]		=	trim($_REQUEST[gubun_code]);	//구분-기본/본점제휴 - basic/bonjum


	$KEY2[bank_code]		=	trim($_REQUEST[bank_code]);		//은행
	$KEY2[gubun_code]		=	trim($_REQUEST[gubun_code]);	//구분-기본/본점제휴 - basic/bonjum

 }

//기본비율


	$KEY[b_basic_bosu]		=	f_de_comma(trim($_REQUEST[b_basic_bosu]));
	$KEY[b_julsak_position]	=	f_de_comma(trim($_REQUEST[b_julsak_position]));
	$KEY[b_nujin_bosu]		=	trim($_REQUEST[b_nujin_bosu]);
	$KEY[b_halin]			=	f_de_comma(trim($_REQUEST[b_halin]));
	$KEY[b_singonabbu]		=	f_de_comma(trim($_REQUEST[b_singonabbu]));
	$KEY[b_kyotong]			=	f_de_comma(trim($_REQUEST[b_kyotong]));
	$KEY[b_woninjungseo]	=	f_de_comma(trim($_REQUEST[b_woninjungseo]));

	$KEY[g_singonabbu]		=	f_de_comma(trim($_REQUEST[g_singonabbu]));
	$KEY[g_jungjidae]		=	f_de_comma(trim($_REQUEST[g_jungjidae]));
	$KEY[g_kyotong]			=	f_de_comma(trim($_REQUEST[g_kyotong]));
	$KEY[g_jejungmyung]		=	f_de_comma(trim($_REQUEST[g_jejungmyung]));
	$KEY[g_yeolamjunggi]		=	f_de_comma(trim($_REQUEST[g_yeolamjunggi]));
	$KEY[g_deungchobon]		=	f_de_comma(trim($_REQUEST[g_deungchobon]));
	$KEY[g_jibaeinchobon]	=	f_de_comma(trim($_REQUEST[g_jibaeinchobon]));

//국토비율

	$KEY2[b_basic_bosu]		=	f_de_comma(trim($_REQUEST[b_basic_bosu_1]));
	$KEY2[b_julsak_position]=	f_de_comma(trim($_REQUEST[b_julsak_position_1]));
	$KEY2[b_nujin_bosu]		=	trim($_REQUEST[b_nujin_bosu_1]);
	$KEY2[b_halin]			=	f_de_comma(trim($_REQUEST[b_halin_1]));
	$KEY2[b_singonabbu]		=	f_de_comma(trim($_REQUEST[b_singonabbu_1]));
	$KEY2[b_kyotong]		=	f_de_comma(trim($_REQUEST[b_kyotong_1]));
	$KEY2[b_woninjungseo]	=	f_de_comma(trim($_REQUEST[b_woninjungseo_1]));

	$KEY2[g_singonabbu]		=	f_de_comma(trim($_REQUEST[g_singonabbu_1]));
	$KEY2[g_jungjidae]		=	f_de_comma(trim($_REQUEST[g_jungjidae_1]));
	$KEY2[g_kyotong]		=	f_de_comma(trim($_REQUEST[g_kyotong_1]));
	$KEY2[g_jejungmyung]	=	f_de_comma(trim($_REQUEST[g_jejungmyung_1]));
	$KEY2[g_yeolamjunggi]	=	f_de_comma(trim($_REQUEST[g_yeolamjunggi_1]));
	$KEY2[g_deungchobon]	=	f_de_comma(trim($_REQUEST[g_deungchobon_1]));
	$KEY2[g_jibaeinchobon]	=	f_de_comma(trim($_REQUEST[g_jibaeinchobon_1]));

//echo "-----".$KEY2[g_singonabbu];

if($mode=="i"){
	//echo "-i";
	###########################################################################
	//기본
	$KEY[basic_gukto]	=	"basic";
	$updatewhere = " WHERE bank_code='{$bank_code}' and  gubun_code='{$gubun_code}' and basic_gukto='basic' ";
	$idx = db_replace($KEY,$tablename,$updatewhere,"idx");

	//국토부
	$KEY2[basic_gukto]	=	"gukto";
	$updatewhere = " WHERE bank_code='{$bank_code}' and  gubun_code='{$gubun_code}' and basic_gukto='gukto' ";
	$idx = db_replace($KEY2,$tablename,$updatewhere,"idx");

	###########################################################################
	if($idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('등록 완료되었습니다.');location.href='./regist.php?bank_code={$bank_code}&gubun_code={$gubun_code}';</script>";	
		exit;
	}
}else if($mode=="e"){
	//echo "-e";
	###########################################################################	
	//기본
	$KEY[basic_gukto]	=	"basic";
	$updatewhere = " WHERE bank_code='{$bank_code}' and  gubun_code='{$gubun_code}' and basic_gukto='basic' ";
	$idx = db_replace($KEY,$tablename,$updatewhere,"idx");

	//국토부
	$KEY2[basic_gukto]	=	"gukto";
	$updatewhere = " WHERE bank_code='{$bank_code}' and  gubun_code='{$gubun_code}' and basic_gukto='gukto' ";
	$idx = db_replace($KEY2,$tablename,$updatewhere,"idx");

	###########################################################################
	if($idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('수정 완료되었습니다.');location.href='./regist.php?bank_code={$bank_code}&gubun_code={$gubun_code}';</script>";	
		exit;
	}
}

?>