<?php
	include ("./include/dbconn.php");
	include ("./include/function.php");

	$regist_date = date("YmdHis");
	$sql="update tbl_user set logout_date='$regist_date' where id='".$_SESSION["admin_id"]."'";
	db_query($sql);

	$_SESSION["admin_id"]		=	"";
	$_SESSION["admin_idx"]		=	"";
	$_SESSION["admin_name"]		=	"";
	$_SESSION["admin_grade"]	=	"";
	$_SESSION["admin_sosok"]	=	"";
	$_SESSION["admin_permission"]	=	"";

	unset($_SESSION["admin_id"]);
	unset($_SESSION["admin_idx"]);
	unset($_SESSION["admin_name"]);
	unset($_SESSION["admin_grade"]);
	unset($_SESSION["admin_sosok"]);
?>

<script>
	alert('로그아웃 되셨습니다. ');
	top.document.location.href="/";
</script>