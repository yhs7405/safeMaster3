<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$tablename = "tbl_bank_jijum_rate";

	//print_r($_REQUEST);
	
	$mode = trim($_REQUEST[mode]);
	$aaa = 'aaa';
	$h_idx				=	trim($_REQUEST[h_idx]);		
	$bank_code			=	trim($_REQUEST[bank_code]);	//은행
	$jijum_code			=	trim($_REQUEST[jijum_code]);	//지점
	$gubun_code			=	trim($_REQUEST[gubun_code]);	//구분-기본/본점제휴 - basic/bonjum
	$bosu_gubun			=	trim($_REQUEST[bosu_gubun]);	//보수표구분

	$KEY[gubun_code]		=	trim($_REQUEST[gubun_code]);	//구분-기본/본점제휴 - basic/bonjum
	$KEY2[gubun_code]		=	trim($_REQUEST[gubun_code]);	//구분-기본/본점제휴 - basic/bonjum

	$KEY[bosu_gubun]		=	trim($_REQUEST[bosu_gubun]);	//
	$KEY2[bosu_gubun]		=	trim($_REQUEST[bosu_gubun]);	//

if($mode=="i"){

	$sql = "select * from tbl_bank_jijum_rate where bank_code='{$bank_code}' and jijum_code='{$jijum_code}' and gubun_code='{$gubun_code}' and h_idx={$h_idx} ";
	$bxx = db_query_fetch($sql);
	if($bxx[idx]!=""){ //중복된다면 오류
		echo "<script>alert('이미 등록되어있는 지점기본비용 정보 입니다.');location.href='regist.php';</script>";
	}

	$KEY[h_idx]			=	trim($_REQUEST[h_idx]);	
	$KEY[bank_code]			=	trim($_REQUEST[bank_code]);		//은행
	$KEY[jijum_code]		=	trim($_REQUEST[jijum_code]);	//지점

	$KEY2[h_idx]			=	trim($_REQUEST[h_idx]);	
	$KEY2[bank_code]		=	trim($_REQUEST[bank_code]);		//은행
	$KEY2[jijum_code]		=	trim($_REQUEST[jijum_code]);	//지점

}

//기본
	$KEY[account_code]		=	trim($_REQUEST[account_code]);
	$KEY[b_basic_bosu]		=	f_de_comma(trim($_REQUEST[b_basic_bosu]));
	$KEY[b_julsak_position]		=	f_de_comma(trim($_REQUEST[b_julsak_position]));
	$KEY[b_nujin_bosu]		=	trim($_REQUEST[b_nujin_bosu]);
	$KEY[b_halin]			=	f_de_comma(trim($_REQUEST[b_halin]));
	$KEY[b_singonabbu]		=	f_de_comma(trim($_REQUEST[b_singonabbu]));
	$KEY[b_kyotong]			=	f_de_comma(trim($_REQUEST[b_kyotong]));
	$KEY[b_woninjungseo]		=	f_de_comma(trim($_REQUEST[b_woninjungseo]));

	$KEY[g_singonabbu]		=	f_de_comma(trim($_REQUEST[g_singonabbu]));
	$KEY[g_jungjidae]		=	f_de_comma(trim($_REQUEST[g_jungjidae]));
	$KEY[g_kyotong]			=	f_de_comma(trim($_REQUEST[g_kyotong]));
	$KEY[g_jejungmyung]		=	f_de_comma(trim($_REQUEST[g_jejungmyung]));
	$KEY[g_yeolamjunggi]		=	f_de_comma(trim($_REQUEST[g_yeolamjunggi]));
	$KEY[g_deungchobon]		=	f_de_comma(trim($_REQUEST[g_deungchobon]));
	$KEY[g_jibaeinchobon]		=	f_de_comma(trim($_REQUEST[g_jibaeinchobon]));

	$KEY[b_singonabbu_ch]		=	trim($_REQUEST[b_singonabbu_ch]);
	$KEY[b_kyotong_ch]		=	trim($_REQUEST[b_kyotong_ch]);
	$KEY[g_singonabbu_ch]		=	trim($_REQUEST[g_singonabbu_ch]);
	$KEY[g_kyotong_ch]		=	trim($_REQUEST[g_kyotong_ch]);


//국토
	$KEY2[account_code]		=	trim($_REQUEST[account_code]);
	$KEY2[b_basic_bosu]		=	f_de_comma(trim($_REQUEST[b_basic_bosu_1]));
	$KEY2[b_julsak_position]	=	f_de_comma(trim($_REQUEST[b_julsak_position_1]));
	$KEY2[b_nujin_bosu]		=	trim($_REQUEST[b_nujin_bosu_1]);
	$KEY2[b_halin]			=	f_de_comma(trim($_REQUEST[b_halin_1]));
	$KEY2[b_singonabbu]		=	f_de_comma(trim($_REQUEST[b_singonabbu_1]));
	$KEY2[b_kyotong]		=	f_de_comma(trim($_REQUEST[b_kyotong_1]));
	$KEY2[b_woninjungseo]		=	f_de_comma(trim($_REQUEST[b_woninjungseo_1]));

	$KEY2[g_singonabbu]		=	f_de_comma(trim($_REQUEST[g_singonabbu_1]));
	$KEY2[g_jungjidae]		=	f_de_comma(trim($_REQUEST[g_jungjidae_1]));
	$KEY2[g_kyotong]		=	f_de_comma(trim($_REQUEST[g_kyotong_1]));
	$KEY2[g_jejungmyung]		=	f_de_comma(trim($_REQUEST[g_jejungmyung_1]));
	$KEY2[g_yeolamjunggi]		=	f_de_comma(trim($_REQUEST[g_yeolamjunggi_1]));
	$KEY2[g_deungchobon]		=	f_de_comma(trim($_REQUEST[g_deungchobon_1]));
	$KEY2[g_jibaeinchobon]		=	f_de_comma(trim($_REQUEST[g_jibaeinchobon_1]));

	$KEY2[b_singonabbu_ch]		=	trim($_REQUEST[b_singonabbu_ch_1]);
	$KEY2[b_kyotong_ch]		=	trim($_REQUEST[b_kyotong_ch_1]);
	$KEY2[g_singonabbu_ch]		=	trim($_REQUEST[g_singonabbu_ch_1]);
	$KEY2[g_kyotong_ch]		=	trim($_REQUEST[g_kyotong_ch_1]);

if($mode=="i"){  //신규자료 입력
	//echo "-i";
	###########################################################################

	$KEY[basic_gukto]	=	"basic";
	$updatewhere = " WHERE jijum_code='{$jijum_code}' and basic_gukto='basic' and h_idx={$h_idx} ";
	$idx = db_replace($KEY,$tablename,$updatewhere,"idx");

	$KEY2[basic_gukto]	=	"gukto";
	$updatewhere = " WHERE jijum_code='{$jijum_code}' and basic_gukto='gukto' and h_idx={$h_idx} ";
	$idx = db_replace($KEY2,$tablename,$updatewhere,"idx");

	###########################################################################
	if($idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('등록 완료되었습니다.');location.href='./regist.php?bank_code={$bank_code}&jijum_code={$jijum_code}&gubun_code={$gubun_code}&h_idx={$h_idx}';</script>";	
		exit;
	}

}else if($mode=="e"){  //저장 갱신
	//echo "-e";
	###########################################################################
	$KEY[basic_gukto]	=	"basic";
	$updatewhere = " where jijum_code='{$jijum_code}' and basic_gukto='basic' and h_idx={$h_idx} ";
	$idx = db_replace($KEY,$tablename,$updatewhere,"idx");

	$KEY2[basic_gukto]	=	"gukto";
	$updatewhere = " where jijum_code='{$jijum_code}' and basic_gukto='gukto' and h_idx={$h_idx} ";
	$idx = db_replace($KEY2,$tablename,$updatewhere,"idx");

	
	//해당 물건지의 설정-누진보수액/보수액/공과금 갱신 --------------------------------------------------------------------------
	$sql = "select idx,a1,bank_code,jijum_code,suljung_no,chaekwon_max from tbl_suljung where h_idx={$h_idx} and jijum_code='{$jijum_code}' ";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
//$aaa = $sql;
	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	if($rows > 0){
		while($row = $stmt->fetch()){

			//설정-누진보수액 재계산
			$KEY1 = array();
			$KEY1["suljung_bosu"]  = f_nujin_value($row[bank_code],$row[jijum_code],$row[chaekwon_max],$row[a1],$row[suljung_no]); //은행누진보수료 참조 (은행코드,지점코드)
			$updatewhere = " WHERE idx = '{$row[idx]}'";

//			$aaa=$KEY1["suljung_bosu"];
//			$aaa=$row[bank_code];
//			$aaa=$row[jijum_code];
//			$aaa=$row[chaekwon_max];
//			$aaa=$row[a1];
//			$aaa=$row[suljung_no];

			db_replace($KEY1,"tbl_suljung",$updatewhere,"idx");
		//echo "<script>alert($KEY1[suljung_bosu]);</script>";	
			//print_r($KEY1)."<br>";
			//print_r($row[bank_code])."&nbsp;";
			//print_r($row[jijum_code])."&nbsp;";
			//print_r($row[chaekwon_max])."&nbsp;";
			//print_r($row[a1])."&nbsp;";
			//print_r($row[suljung_no])."&nbsp;";
			//print_r($KEY1["suljung_bosu"])."<br>";

			//보수액/공과금 갱신
			$KEY1 = array();

			$ff = f_suljung_bosu2($row["a1"],$row["suljung_no"],"");
			$KEY1[bosu_price] = $ff[fee];
			$KEY1[bosu_price_vat] = $ff[vat];
//			$aaa=$KEY1[bosu_price_vat];

			$KEY1[gongga_price] =  f_suljung_gongga2($row["a1"],$row["suljung_no"],"");

//			$KEY1[bosu_price] = f_suljung_bosu($row[idx],"");
//			$KEY1[gongga_price] =  f_suljung_gongga($row[idx],"");
			$updatewhere = " WHERE idx = '{$row[idx]}'";
			db_replace($KEY1,"tbl_suljung",$updatewhere,"idx");
			//print_r($KEY1)."<br>";
		}
	}

	###########################################################################
	if($idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('수정 완료되었습니다.');location.href='./regist.php?bank_code={$bank_code}&jijum_code={$jijum_code}&gubun_code={$gubun_code}&h_idx={$h_idx}&aaa={$aaa}';</script>";	
		exit;
	}
}

?>