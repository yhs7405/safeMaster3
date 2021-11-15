<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	//print_r($_REQUEST);
	//$KEY[id_ch] = trim($_REQUEST[id_ch]);

	$tablename = "tbl_hyunjang_danji_info";

	$h_idx = trim($_REQUEST[h_idx]);

	$idx = trim($_REQUEST[idx]);

	$mode = trim($_REQUEST[mode]);

	$KEY[h_idx]		= trim($_REQUEST[h_idx]);
	$KEY[danji_name]	= trim($_REQUEST[danji_name]);
	$KEY[jibun_addr]	= trim($_REQUEST[jibun_addr]);
	$KEY[doro_addr]		= trim($_REQUEST[doro_addr]);
	$KEY[d_com_name]	= trim($_REQUEST[d_com_name]);
	$KEY[d_saup_no]		= trim($_REQUEST[d_saup_no]);
	$KEY[d_bubin_no]	= trim($_REQUEST[d_bubin_no]);
	$KEY[d_addr]		= trim($_REQUEST[d_addr]);
	$KEY[d_position]	= trim($_REQUEST[d_position]);
	$KEY[d_name]		= trim($_REQUEST[d_name]);

	$KEY[building_jibun]		= trim($_REQUEST[building_jibun]);
	$KEY[building_name]		= trim($_REQUEST[building_name]);
	$KEY[building_road_name]		= trim($_REQUEST[building_road_name]);
	$KEY[building_dis_rescue]		= trim($_REQUEST[building_dis_rescue]);
	$KEY[lot_amount]		= trim($_REQUEST[lot_amount]);
	$KEY[lot_no_1]		= trim($_REQUEST[lot_no_1]);
	$KEY[lot_no_2]		= trim($_REQUEST[lot_no_2]);
	$KEY[lot_no_3]		= trim($_REQUEST[lot_no_3]);
	$KEY[lot_no_4]		= trim($_REQUEST[lot_no_4]);
	$KEY[lot_no_5]		= trim($_REQUEST[lot_no_5]);
	$KEY[lot_no_6]		= trim($_REQUEST[lot_no_6]);
	$KEY[lot_no_7]		= trim($_REQUEST[lot_no_7]);
	$KEY[lot_no_8]		= trim($_REQUEST[lot_no_8]);
	$KEY[lot_no_9]		= trim($_REQUEST[lot_no_9]);
	$KEY[lot_no_10]		= trim($_REQUEST[lot_no_10]);
	$KEY[lot_area_1]		= f_de_comma(trim($_REQUEST[lot_area_1]));
	$KEY[lot_area_2]		= f_de_comma(trim($_REQUEST[lot_area_2]));
	$KEY[lot_area_3]		= f_de_comma(trim($_REQUEST[lot_area_3]));
	$KEY[lot_area_4]		= f_de_comma(trim($_REQUEST[lot_area_4]));
	$KEY[lot_area_5]		= f_de_comma(trim($_REQUEST[lot_area_5]));
	$KEY[lot_area_6]		= f_de_comma(trim($_REQUEST[lot_area_6]));
	$KEY[lot_area_7]		= f_de_comma(trim($_REQUEST[lot_area_7]));
	$KEY[lot_area_8]		= f_de_comma(trim($_REQUEST[lot_area_8]));
	$KEY[lot_area_9]		= f_de_comma(trim($_REQUEST[lot_area_9]));
	$KEY[lot_area_10]		= f_de_comma(trim($_REQUEST[lot_area_10]));
	$KEY[area_kiinds]		= trim($_REQUEST[area_kiinds]);
	$KEY[area_ratio]		= f_de_comma(trim($_REQUEST[area_ratio]));
	$KEY[road_dong]		= trim($_REQUEST[road_dong]);
	$KEY[road_building_name]		= trim($_REQUEST[road_building_name]);
	$KEY[trust_date]		= trim($_REQUEST[trust_date]);
	$KEY[trust_no]		= trim($_REQUEST[trust_no]);
	$KEY[trust_org]		= trim($_REQUEST[trust_org]);



if($mode=="i"){ //신규
	//echo "-i";

	###########################################################################

	$updatewhere = " WHERE idx='werwerfsdfsdfwerwre' ";
	$idx = db_replace($KEY,$tablename,$updatewhere,"idx");

	###########################################################################
	if($idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('등록 완료되었습니다.');location.href='index.html?h_idx=".$h_idx."';opener.parent.location.reload();</script>";	
		exit;
	}
}else if($mode=="e"){
	//echo "-e";


	###########################################################################
	$updatewhere = " WHERE idx='{$idx}' ";
	db_replace($KEY,$tablename,$updatewhere,"idx");
	###########################################################################
	if($idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('수정 완료되었습니다.');location.href='regist.php?h_idx=".$h_idx."&idx=".$idx."';opener.parent.location.reload();</script>";
		exit;
	}
}else if($mode=="d"){
	//echo "-e";

	###########################################################################
	$where = " WHERE idx='{$idx}' ";

	$sql = "delete from $tablename  $where";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	###########################################################################
	if($idx > 0){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('삭제 완료되었습니다.');location.href='index.html?h_idx=".$h_idx."';opener.parent.location.reload();</script>";
		exit;
	}
}

?>