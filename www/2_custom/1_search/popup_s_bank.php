<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";

	$idx		=	trim($_REQUEST[idx]);
	$a1		=	urldecode(trim($_REQUEST[a1]));

	$sql= "select * from tbl_suljung where idx='{$idx}' limit 1 ";
	//echo "<br>".$sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	$bank_code = $row[bank_code];
	$jijum_code = $row[jijum_code];

	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();
?>


<!DOCTYPE html>
<html lang="kr">

<head>
<title>CS돌이</title>
<?include ("../../include/common.php");?>
</head>

<body>



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


<div>

<br><br><br>

  <div id="content-header">
    <div id="breadcrumb" style="text-align:center;background-color:#cccccc;"><h3>설정은행 변경</h3></div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">

			<div  style="width:100%;background-color:#EFEFEF;border-top:1px solid #e7e7e7;border-left:1px solid #e7e7e7;border-right:1px solid #e7e7e7;">
				<form name=ff method=post>
					<input type=hidden name="idx" value="<?=$idx?>">
					<input type=hidden name="a1" value="<?=$a1?>">
				<center>
				<table style="background-color:#EFEFEF;">
				  <thead>
					<tr>
					  <th style="text-align:center;margin-top:10px;">
						  <Br>
						  &nbsp;은행&nbsp;<select name="bank_code" id="bank_code"  onchange="javascript:select_detail('bank_code','jijum_code');" style='width:120px;'>
									<option value="">--은행--</option>
									<?
									$sql = "select * from tbl_bank_info ";
									$sosok_r = $pdo->prepare($sql);
									$sosok_r->execute();
									while($rr = $sosok_r->fetch()){?>
										<option value="<?=$rr[bank_code]?>" <?if($rr[bank_code]==$bank_code){?>selected<?}?>><?=$rr[bank_name]?></option>
									<?}?>
								</select>
								<select name="jijum_code" id="jijum_code" style='width:160px;'>
											<option value="" >--지점--</option>
									<?
									if($bank_code!=""){
										$sql = "select * from tbl_bank_jijum where bank_code='{$bank_code}' ";
										$sosok_r = $pdo->prepare($sql);
										$sosok_r->execute();
										while($rr1 = $sosok_r->fetch()){?>
											<option value="<?=$rr1[jijum_code]?>" <?if($rr1[jijum_code]==$jijum_code){?>selected<?}?>><?=$rr1[jijum_name]?></option>
										<?}?>
									<?}else{?>
											<option value="">----</option>
									<?}?>
								</select>
								<br> 선택된 은행정보를 수정 하시겠습니까?
					  </th>
					</tr>
					<tr>
					  <th>
						<button type="button" class="btn btn-success" onclick="javascript:f_submit();" style="background-color:#4374D9;height:30px;width:60px;margin-top:10px;">수정</button>
						&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-success" onclick="javascript:window.close();" style="background-color:#4374D9;height:30px;width:60px;margin-top:10px;">취소</button>
						<br><br>
					  </th>
					</tr>
				  </thead>
				</table>
				</center>
				</form>
			</div>
		</div>


      </div>
    </div>
  </div>
</div>


<script src="/js/common.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script>
function f_submit(){
	if(confirm("은행정보수정시 은행별 누진보수료가 재설정됩니다.\n\n은행정보를 수정하시겠니까?")){
		document.ff.action="popup_s_bank_proc.php";
		document.ff.submit();
	}
}

</script>

</body>
</html>
