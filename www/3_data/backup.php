<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

?>

<!DOCTYPE html>
<html lang="kr">

<head>
<title>재무돌이</title>
<?include ("../include/common.php");?>
</head>

<body>

<!--header 시작-->
	<?include ("../include/header.php");?>
<!--header 종료-->


<!--top-메뉴시작-->
	<?include ("../include/header_menu.php");?>
<!--top-메뉴종료-->



<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">데이터관리</a> <a href="#" class="current">엑셀백업</a> </div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">


     <form name="ff" action="backup_excel.php" method="post" class="form-horizontal" onsubmit="return f_submit();" enctype="multipart/form-data">

      <div class="span6" style="width:98%;"  style="text-align:center;">
        <div class="widget-box"  style="text-align:center;">
          <div class="widget-content nopadding"  style="text-align:center;">

            <div class="control-group" style="text-align:center;height:500px;">

			<center>
			<br><br><br><br><br><br><br><br>
			<table  style="margin:10px;vertical-align: middle;">
			<tr>
				<td>현장명</td>
				<td><select name="h_idx" id="h_idx">
						<?
						if($h_idx==""){
							$sql = "select * from tbl_hyunjang_info";
						}else{
							$sql = "select * from tbl_hyunjang_info where h_idx='{$h_idx}'";
						}

						$sosok_r = $pdo->prepare($sql);
						$sosok_r->execute();
						while($rr = $sosok_r->fetch()){?>
							<option value="<?=$rr[h_idx]?>" <?if($rr[h_idx]==$row[h_idx]){?>selected<?}?>><?=$rr[h_name]?></option>
						<?}?>
					</select>
				</td>
			</tr>
			</table>
			<br>

<?if($_SESSION["admin_permission"][ch_322]=="y"){?>
			<div style="text-align:center;" >
				<span id="filex"><button type="submit" class="btn btn-success">엑셀 백업하기</button></span>
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
	<?include ("../include/bottom.php");?>
<!--bottom-종료-->

<script src="/js/jquery.min.js"></script> 
<script src="/js/jquery.ui.custom.js"></script> 
<script src="/js/bootstrap.min.js"></script> 
<script src="/js/jquery.dataTables.min.js"></script> 
<script src="/js/maruti.js"></script> 

<script>
function f_submit(){
	var v = document.ff;
		if(confirm("선택된 현장을 엑셀 백업하시겠습니까?")){
//			$("#filex").html("<b style='color:blue;'>엑셀 백업중...</b>");
			return true;
		}
}


</script>

</body>
</html>
