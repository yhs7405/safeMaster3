<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

?>


<!DOCTYPE html>
<html lang="kr">

<head>
<title>재무돌이</title>
<?include ("../include/common.php");?>
</head>


<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

<script>

$(function() {
		$( ".datepickx" ).datepicker({
			autosize: true,
			showOn: "button",
			buttonImage: "/images/calendar.gif",
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: "yymmdd",
			dayNamesShort:["일","월","화","수","목","금","토"],
			monthNamesShort:["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"]
		});
	 $("img.ui-datepicker-trigger").attr("style", "margin-bottom:4px; vertical-align:middle; cursor: Pointer;");
});
$( ".datepickx" ).datepicker( "option", "dayNamesShort",  [ "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam" ] );
</script>


<body>

<br><br><br><br>
<center>
팝빌 아이디  :&nbsp;&nbsp;&nbsp;taeyul7777  <Br>
팝빌 비밀번호:&nbsp;&nbsp;&nbsp;@9563349!<br>
<Br><Br>
<input type=button value=" 창닫기 " onclick="javascript:window.close();">
</center>

</body>
</html>
