<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);
	

?>

<!DOCTYPE html>
<html lang="kr">

<head>
<title>CS돌이</title>
<?include ("../../include/common.php");?>
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




<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">비용관련</a> <a href="#" class="current">가상계좌/수납</a> <a href="#" class="current">가상계좌업로드</a> </div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">


     <form name="ff" action="vaupload_ok.php" method="post" class="form-horizontal" onsubmit="return f_submit();" enctype="multipart/form-data">

      <div class="span6" style="width:98%;"  style="text-align:center;">
        <div class="widget-box"  style="text-align:center;">
          <div class="widget-content nopadding"  style="text-align:center;">

            <div class="control-group" style="text-align:center;height:500px;">

			<center>
			<br><br><br><br><br><br><br><br>
			<table  style="margin:10px;vertical-align: middle;">
			<tr>
				<td>파일</td>
				<td><input type=file name=excel_file></td>
			</tr>
			<tr>
				<td style="color:red;" colspan=2><br>1. .xls 형식의 파일만 업로드 가능합니다. </td>
			</tr>
			<tr>
				<td style="color:red;" colspan=2><br>2. 우리모아에서 다운로드 받은 파일을 첨부해주세요.</td>
			</tr>
			</table>
			<br>

<?if($_SESSION["admin_permission"][ch_d42]=="y"){?>
			<div style="text-align:center;">
				<span id="filex"><button type="submit" class="btn btn-success">엑셀 업로드하기</button></span>
			</div>
<?}?>
			<br>
			</center>

          </div>
        </div>
      </div>
	 </form>


    </div>

  </div>
</div>

<!--bottom-시작-->
	<?include ("../../include/bottom.php");?>
<!--bottom-종료-->

<script src="/js/jquery.min.js"></script> 
<script src="/js/jquery.ui.custom.js"></script> 
<script src="/js/bootstrap.min.js"></script> 
<script src="/js/jquery.dataTables.min.js"></script> 
<script src="/js/maruti.js"></script> 

<script>
function f_submit($h_idx){
	var v = document.ff;
//		alert(${v.excel_file.value});
	if(v.excel_file.value==""){
		alert("파일을 선택해 주세요.");
		return false;
	}else{
		if(confirm("선택된 파일을 업로드 하시겠습니까?")){
			$("#filex").html("<b style='color:blue;'>엑셀 업로드중...</b>");
			return true;
		}else {
			return false;
		}
	}
}


</script>

</body>
</html>
