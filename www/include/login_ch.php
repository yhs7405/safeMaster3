<?
	if($_SESSION["admin_id"]==""){
		echo "<script>alert('로그인하셔야합니다.');location.href='/';</script>";
		exit;
	}

	$rq_uri = $_SERVER['REQUEST_URI'];
	$rq_urix = explode("/",$rq_uri);
	$rq_urim = $rq_urix[1];

	//echo "<br><br>".$rq_urim;

	if(($_SESSION["admin_permission"][ch_100]!="y")&&($rq_urim=="1_junib")){
		echo "<script>alert('해당페이지 접근권한이 없습니다.');location.href='/';</script>";
		exit;
	}
	if(($_SESSION["admin_permission"][ch_200]!="y")&&($rq_urim=="2_custom")){
		echo "<script>alert('해당페이지 접근권한이 없습니다.');location.href='/';</script>";
		exit;
	}
	if(($_SESSION["admin_permission"][ch_300]!="y")&&($rq_urim=="3_data")){
		echo "<script>alert('해당페이지 접근권한이 없습니다.');location.href='/';</script>";
		exit;
	}
	if(($_SESSION["admin_permission"][ch_500]!="y")&&($rq_urim=="5_form")){
		echo "<script>alert('해당페이지 접근권한이 없습니다.');location.href='/';</script>";
		exit;
	}
	if(($_SESSION["admin_permission"][ch_600]!="y")&&($rq_urim=="6_sugum")){
		echo "<script>alert('해당페이지 접근권한이 없습니다.');location.href='/';</script>";
		exit;
	}
	if(($_SESSION["admin_permission"][ch_700]!="y")&&($rq_urim=="7_refund")){
		echo "<script>alert('해당페이지 접근권한이 없습니다.');location.href='/';</script>";
		exit;
	}
	if(($_SESSION["admin_permission"][ch_800]!="y")&&($rq_urim=="8_erp")){
		echo "<script>alert('해당페이지 접근권한이 없습니다.');location.href='/';</script>";
		exit;
	}
	if(($_SESSION["admin_permission"][ch_900]!="y")&&($rq_urim=="9_basic")){
		echo "<script>alert('해당페이지 접근권한이 없습니다.');location.href='/';</script>";
		exit;
	}
	if(($_SESSION["admin_permission"][ch_a00]!="y")&&($rq_urim=="a_account")){
		echo "<script>alert('해당페이지 접근권한이 없습니다.');location.href='/';</script>";
		exit;
	}
	if(($_SESSION["admin_permission"][ch_b00]!="y")&&($rq_urim=="b_bbs")){
		echo "<script>alert('해당페이지 접근권한이 없습니다.');location.href='/';</script>";
		exit;
	}

?>