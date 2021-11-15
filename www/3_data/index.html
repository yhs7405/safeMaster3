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
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">데이터관리</a> <a href="#" class="current">엑셀업로드</a> </div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">


     <form name="ff" action="upload_ok.php" method="post" class="form-horizontal" onsubmit="return f_submit();" enctype="multipart/form-data">

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
			<tr>
				<td>파일</td>
				<td><input type=file name=excel_file></td>
			</tr>
			<tr>
				<td style="color:red;" colspan=2><br>1. .xls 형식의 파일만 업로드 가능합니다. </td>
			</tr>
			<tr>
				<td style="color:red;" colspan=2><br>2. 채권최고액과 은행명이 기존 재무돌이 데이터와 상이한 경우 전체행이 업로드되지 않습니다.<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;마스터문의하세요(업로드진행안됨)</td>
			</tr>
			<tr>
				<td style="color:red;" colspan=2><br>3. 채무자정보가 엑셀값으로 업로드되니, 수동으로 채무자정보를 수정하세요</td>
			</tr>
			</table>
			<br>

<?if($_SESSION["admin_permission"][ch_312]=="y"){?>
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
	if(v.excel_file.value==""){
		alert("파일을 선택해 주세요.");
		return false;
	}else{
		if(confirm("선택된 파일을 업로드 하시겠습니까?")){
			$("#filex").html("<b style='color:blue;'>엑셀 업로드중...</b>");
			return true;
		}
	}
}

function f_duplicate(){
	var v1=$("#id").val();
	if(v1!=""){
		$.ajax({
			type:"GET",
			url:'./ajax_id.php',
			dataType:"text",
			data:{id:v1},
			timeout : 30000,
			success:function (req) {
				//alert(req);
				if($.trim(req)=="y"){ //중복 아닐때
					alert("사용 가능한 아이디입니다.");
					$("#id_ch").val("y");
				}else{ //중복 일때
					alert("이미 사용중인 아이디입니다.");
					$("#id_ch").val("n");
				}
			},
			error : function(request, status, error) {
				//통신 에러 발생시 처리
				//alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
			}
		});
	}else{
		alert("아이디를 입력하세요.");
		$("#id").focus();
	}
}

</script>

</body>
</html>
