<?
//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");
	include ("../../include/excel.inc");

	//print_r($_REQUEST);

	foreach ($_REQUEST as $k => $v) { 
		//echo "$k => $v <Br>"; 
		if(($k!="ccc1")&&($k!="ccc2")&&($k!="ccc3")&&($k!="ccc4")&&($k!="att1")){
			if(($k!="af1")||($k!="ag1")||($k!="ai1")||($k!="al1")||($k!="ao1")||($k!="t1")||($k!="am1")||($k!="an1")||($k!="ap1")||($k!="aj1")||($k!="aq1")||($k!="ar1")||($k!="as1")||($k!="at1")) {
				$KEY[$k] = f_de_comma($v) ;
			}else{
				$KEY[$k] = $v ;
			}
		}
	}
	$KEY[at1] = f_de_comma($_REQUEST[att1]) ;  //파라메터 안먹히게 예외처리

	//print_r($KEY);

	$KEY["total_pay"]  = intval($KEY["aj1"])+intval($KEY["ak1"])+intval($KEY["al1"])+intval($KEY["am1"])+intval($KEY["an1"])+intval($KEY["ao1"])+intval($KEY["ap1"])+intval($KEY["aq1"])+intval($KEY["ar1"])+intval($KEY["as1"])+intval($KEY["at1"]);  //지급금합계

	
	$imsi_refund  = intval($KEY["ai1"]) -$KEY["total_pay"] ; //환불금 = 입금액 - 지급금합계
	$imsi_refund = floor( $imsi_refund / 10 ) * 10;  //1단위 절삭
	$KEY["refund_fee"] = $imsi_refund;  //1단위 절삭


	if($KEY["refund_fee"]<0){  //0보다 크면 환불해줘야함
		$KEY["chuga_ibgum_daesang"]  = "Y"; //추가입금대상여부 - 대상  Y로
	}else{//0보다 작으면 돈을 받아야함.
		$KEY["chuga_ibgum_daesang"]  = ""; //추가입금대상여부 -X
	}

	$updatewhere = " WHERE a1 = '{$KEY[a1]}'";
	db_replace($KEY,"tbl_junib",$updatewhere,"idx");

	if($_REQUEST["ccc1"]!=""){
		$KEY1 = array();
		$KEY1["chaekwon_no"] = $_REQUEST["ccc1"];
		$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=1 ";
		db_replace($KEY1,"tbl_suljung",$updatewhere,"idx");
	}

	if($_REQUEST["ccc2"]!=""){
		$KEY2 = array();
		$KEY2["chaekwon_no"] = $_REQUEST["ccc2"];
		$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=2 ";
		db_replace($KEY2,"tbl_suljung",$updatewhere,"idx");
	}

	if($_REQUEST["ccc3"]!=""){
		$KEY3 = array();
		$KEY3["chaekwon_no"] = $_REQUEST["ccc3"];
		$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=3 ";
		db_replace($KEY3,"tbl_suljung",$updatewhere,"idx");
	}

	if($_REQUEST["ccc4"]!=""){
		$KEY4 = array();
		$KEY4["chaekwon_no"] = $_REQUEST["ccc4"];
		$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=4 ";
		db_replace($KEY4,"tbl_suljung",$updatewhere,"idx");
	}

	$a1 = urlencode($KEY[a1]);
	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<script>alert('저장 되었습니다.');location.href='popup_g.php?a1=".$a1."';</script>";


?>