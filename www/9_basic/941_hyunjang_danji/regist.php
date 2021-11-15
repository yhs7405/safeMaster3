<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$h_idx = trim($_REQUEST[h_idx]);
	$idx = trim($_REQUEST[idx]);

	$sql= "select * from tbl_hyunjang_info where h_idx='{$h_idx}' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row1 = $stmt1->fetch();


	$board_dbname	= "tbl_hyunjang_danji_info";
	if($idx==""){
		$mode="i";  //insert 신규
		$wherequery = " where 1=1  ";
	}else{
		$mode="e";  //edit 수정

		$sql= "select * from $board_dbname where idx='{$idx}' ";
		//echo $sql;
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$row = $stmt->fetch();
	}
?>

<!DOCTYPE html>
<html lang="kr">

<head>
<title>재무돌이</title>
<?include ("../../include/common.php");?>
</head>

<body style="background-color:white;">


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

// 설정갯수 초기화
	if($("#mode").val()=="e"){
		if($("#lot_amount").val()=="1"){
			$('#la_cnt2').css('display', 'none');
			$('#la_cnt3').css('display', 'none');
			$('#la_cnt4').css('display', 'none');
			$('#la_cnt5').css('display', 'none');
			$('#la_cnt6').css('display', 'none');
			$('#la_cnt7').css('display', 'none');
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
			$('#lot_cnt2').css('display', 'none');
			$('#lot_cnt3').css('display', 'none');
			$('#lot_cnt4').css('display', 'none');
			$('#lot_cnt5').css('display', 'none');
			$('#lot_cnt6').css('display', 'none');
			$('#lot_cnt7').css('display', 'none');
			$('#lot_cnt8').css('display', 'none');
			$('#lot_cnt9').css('display', 'none');
			$('#lot_cnt10').css('display', 'none');
		} else if($("#lot_amount").val()=="2"){
			$('#la_cnt3').css('display', 'none');
			$('#la_cnt4').css('display', 'none');
			$('#la_cnt5').css('display', 'none');
			$('#la_cnt6').css('display', 'none');
			$('#la_cnt7').css('display', 'none');
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
			$('#lot_cnt3').css('display', 'none');
			$('#lot_cnt4').css('display', 'none');
			$('#lot_cnt5').css('display', 'none');
			$('#lot_cnt6').css('display', 'none');
			$('#lot_cnt7').css('display', 'none');
			$('#lot_cnt8').css('display', 'none');
			$('#lot_cnt9').css('display', 'none');
			$('#lot_cnt10').css('display', 'none');
		} else if($("#lot_amount").val()=="3"){
			$('#la_cnt4').css('display', 'none');
			$('#la_cnt5').css('display', 'none');
			$('#la_cnt6').css('display', 'none');
			$('#la_cnt7').css('display', 'none');
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
			$('#lot_cnt4').css('display', 'none');
			$('#lot_cnt5').css('display', 'none');
			$('#lot_cnt6').css('display', 'none');
			$('#lot_cnt7').css('display', 'none');
			$('#lot_cnt8').css('display', 'none');
			$('#lot_cnt9').css('display', 'none');
			$('#lot_cnt10').css('display', 'none');
		} else if($("#lot_amount").val()=="4"){
			$('#la_cnt5').css('display', 'none');
			$('#la_cnt6').css('display', 'none');
			$('#la_cnt7').css('display', 'none');
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
			$('#lot_cnt5').css('display', 'none');
			$('#lot_cnt6').css('display', 'none');
			$('#lot_cnt7').css('display', 'none');
			$('#lot_cnt8').css('display', 'none');
			$('#lot_cnt9').css('display', 'none');
			$('#lot_cnt10').css('display', 'none');
		} else if($("#lot_amount").val()=="5"){
			$('#la_cnt6').css('display', 'none');
			$('#la_cnt7').css('display', 'none');
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
			$('#lot_cnt6').css('display', 'none');
			$('#lot_cnt7').css('display', 'none');
			$('#lot_cnt8').css('display', 'none');
			$('#lot_cnt9').css('display', 'none');
			$('#lot_cnt10').css('display', 'none');
		} else if($("#lot_amount").val()=="6"){
			$('#la_cnt7').css('display', 'none');
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
			$('#lot_cnt7').css('display', 'none');
			$('#lot_cnt8').css('display', 'none');
			$('#lot_cnt9').css('display', 'none');
			$('#lot_cnt10').css('display', 'none');
		} else if($("#lot_amount").val()=="7"){
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
			$('#lot_cnt8').css('display', 'none');
			$('#lot_cnt9').css('display', 'none');
			$('#lot_cnt10').css('display', 'none');
		} else if($("#lot_amount").val()=="8"){
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
			$('#lot_cnt9').css('display', 'none');
			$('#lot_cnt10').css('display', 'none');
		} else if($("#lot_amount").val()=="9"){
			$('#la_cnt10').css('display', 'none');
			$('#lot_cnt10').css('display', 'none');
		} else {
			
		}
	} else {
		$('#la_cnt2').css('display', 'none');
		$('#la_cnt3').css('display', 'none');
		$('#la_cnt4').css('display', 'none');
		$('#la_cnt5').css('display', 'none');
		$('#la_cnt6').css('display', 'none');
		$('#la_cnt7').css('display', 'none');
		$('#la_cnt8').css('display', 'none');
		$('#la_cnt9').css('display', 'none');
		$('#la_cnt10').css('display', 'none');
		$('#lot_cnt2').css('display', 'none');
		$('#lot_cnt3').css('display', 'none');
		$('#lot_cnt4').css('display', 'none');
		$('#lot_cnt5').css('display', 'none');
		$('#lot_cnt6').css('display', 'none');
		$('#lot_cnt7').css('display', 'none');
		$('#lot_cnt8').css('display', 'none');
		$('#lot_cnt9').css('display', 'none');
		$('#lot_cnt10').css('display', 'none');
	}



});
$( ".datepickx" ).datepicker( "option", "dayNamesShort",  [ "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam" ] );
// 필지갯수에 따른 제어
function f_lot_cnt(frm) {
	var v = document.ff;
	if(frm=="1"){
		$('#la_cnt2').css('display', 'none');
		$('#la_cnt3').css('display', 'none');
		$('#la_cnt4').css('display', 'none');
		$('#la_cnt5').css('display', 'none');
		$('#la_cnt6').css('display', 'none');
		$('#la_cnt7').css('display', 'none');
		$('#la_cnt8').css('display', 'none');
		$('#la_cnt9').css('display', 'none');
		$('#la_cnt10').css('display', 'none');
		$('#lot_cnt2').css('display', 'none');
		$('#lot_cnt3').css('display', 'none');
		$('#lot_cnt4').css('display', 'none');
		$('#lot_cnt5').css('display', 'none');
		$('#lot_cnt6').css('display', 'none');
		$('#lot_cnt7').css('display', 'none');
		$('#lot_cnt8').css('display', 'none');
		$('#lot_cnt9').css('display', 'none');
		$('#lot_cnt10').css('display', 'none');

		$('#lot_no_2').val('');
		$('#lot_no_3').val('');
		$('#lot_no_4').val('');
		$('#lot_no_5').val('');
		$('#lot_no_6').val('');
		$('#lot_no_7').val('');
		$('#lot_no_8').val('');
		$('#lot_no_9').val('');
		$('#lot_no_10').val('');
		$('#lot_area_2').val('');
		$('#lot_area_3').val('');
		$('#lot_area_4').val('');
		$('#lot_area_5').val('');
		$('#lot_area_6').val('');
		$('#lot_area_7').val('');
		$('#lot_area_8').val('');
		$('#lot_area_9').val('');
		$('#lot_area_10').val('');
	}else if(frm=="2"){
		$('#la_cnt2').css('display', '');
		$('#la_cnt3').css('display', 'none');
		$('#la_cnt4').css('display', 'none');
		$('#la_cnt5').css('display', 'none');
		$('#la_cnt6').css('display', 'none');
		$('#la_cnt7').css('display', 'none');
		$('#la_cnt8').css('display', 'none');
		$('#la_cnt9').css('display', 'none');
		$('#la_cnt10').css('display', 'none');
		$('#lot_cnt2').css('display', '');
		$('#lot_cnt3').css('display', 'none');
		$('#lot_cnt4').css('display', 'none');
		$('#lot_cnt5').css('display', 'none');
		$('#lot_cnt6').css('display', 'none');
		$('#lot_cnt7').css('display', 'none');
		$('#lot_cnt8').css('display', 'none');
		$('#lot_cnt9').css('display', 'none');
		$('#lot_cnt10').css('display', 'none');

		$('#lot_no_3').val('');
		$('#lot_no_4').val('');
		$('#lot_no_5').val('');
		$('#lot_no_6').val('');
		$('#lot_no_7').val('');
		$('#lot_no_8').val('');
		$('#lot_no_9').val('');
		$('#lot_no_10').val('');
		$('#lot_area_3').val('');
		$('#lot_area_4').val('');
		$('#lot_area_5').val('');
		$('#lot_area_6').val('');
		$('#lot_area_7').val('');
		$('#lot_area_8').val('');
		$('#lot_area_9').val('');
		$('#lot_area_10').val('');
	}else if(frm=="3"){
		$('#la_cnt2').css('display', '');
		$('#la_cnt3').css('display', '');
		$('#la_cnt4').css('display', 'none');
		$('#la_cnt5').css('display', 'none');
		$('#la_cnt6').css('display', 'none');
		$('#la_cnt7').css('display', 'none');
		$('#la_cnt8').css('display', 'none');
		$('#la_cnt9').css('display', 'none');
		$('#la_cnt10').css('display', 'none');
		$('#lot_cnt2').css('display', '');
		$('#lot_cnt3').css('display', '');
		$('#lot_cnt4').css('display', 'none');
		$('#lot_cnt5').css('display', 'none');
		$('#lot_cnt6').css('display', 'none');
		$('#lot_cnt7').css('display', 'none');
		$('#lot_cnt8').css('display', 'none');
		$('#lot_cnt9').css('display', 'none');
		$('#lot_cnt10').css('display', 'none');

		$('#lot_no_4').val('');
		$('#lot_no_5').val('');
		$('#lot_no_6').val('');
		$('#lot_no_7').val('');
		$('#lot_no_8').val('');
		$('#lot_no_9').val('');
		$('#lot_no_10').val('');
		$('#lot_area_4').val('');
		$('#lot_area_5').val('');
		$('#lot_area_6').val('');
		$('#lot_area_7').val('');
		$('#lot_area_8').val('');
		$('#lot_area_9').val('');
		$('#lot_area_10').val('');
	}else if(frm=="4"){
		$('#la_cnt2').css('display', '');
		$('#la_cnt3').css('display', '');
		$('#la_cnt4').css('display', '');
		$('#la_cnt5').css('display', 'none');
		$('#la_cnt6').css('display', 'none');
		$('#la_cnt7').css('display', 'none');
		$('#la_cnt8').css('display', 'none');
		$('#la_cnt9').css('display', 'none');
		$('#la_cnt10').css('display', 'none');
		$('#lot_cnt2').css('display', '');
		$('#lot_cnt3').css('display', '');
		$('#lot_cnt4').css('display', '');
		$('#lot_cnt5').css('display', 'none');
		$('#lot_cnt6').css('display', 'none');
		$('#lot_cnt7').css('display', 'none');
		$('#lot_cnt8').css('display', 'none');
		$('#lot_cnt9').css('display', 'none');
		$('#lot_cnt10').css('display', 'none');

		$('#lot_no_5').val('');
		$('#lot_no_6').val('');
		$('#lot_no_7').val('');
		$('#lot_no_8').val('');
		$('#lot_no_9').val('');
		$('#lot_no_10').val('');
		$('#lot_area_5').val('');
		$('#lot_area_6').val('');
		$('#lot_area_7').val('');
		$('#lot_area_8').val('');
		$('#lot_area_9').val('');
		$('#lot_area_10').val('');
	}else if(frm=="5"){
		$('#la_cnt2').css('display', '');
		$('#la_cnt3').css('display', '');
		$('#la_cnt4').css('display', '');
		$('#la_cnt5').css('display', '');
		$('#la_cnt6').css('display', 'none');
		$('#la_cnt7').css('display', 'none');
		$('#la_cnt8').css('display', 'none');
		$('#la_cnt9').css('display', 'none');
		$('#la_cnt10').css('display', 'none');
		$('#lot_cnt2').css('display', '');
		$('#lot_cnt3').css('display', '');
		$('#lot_cnt4').css('display', '');
		$('#lot_cnt5').css('display', '');
		$('#lot_cnt6').css('display', 'none');
		$('#lot_cnt7').css('display', 'none');
		$('#lot_cnt8').css('display', 'none');
		$('#lot_cnt9').css('display', 'none');
		$('#lot_cnt10').css('display', 'none');

		$('#lot_no_6').val('');
		$('#lot_no_7').val('');
		$('#lot_no_8').val('');
		$('#lot_no_9').val('');
		$('#lot_no_10').val('');
		$('#lot_area_6').val('');
		$('#lot_area_7').val('');
		$('#lot_area_8').val('');
		$('#lot_area_9').val('');
		$('#lot_area_10').val('');
	}else if(frm=="6"){
		$('#la_cnt2').css('display', '');
		$('#la_cnt3').css('display', '');
		$('#la_cnt4').css('display', '');
		$('#la_cnt5').css('display', '');
		$('#la_cnt6').css('display', '');
		$('#la_cnt7').css('display', 'none');
		$('#la_cnt8').css('display', 'none');
		$('#la_cnt9').css('display', 'none');
		$('#la_cnt10').css('display', 'none');
		$('#lot_cnt2').css('display', '');
		$('#lot_cnt3').css('display', '');
		$('#lot_cnt4').css('display', '');
		$('#lot_cnt5').css('display', '');
		$('#lot_cnt6').css('display', '');
		$('#lot_cnt7').css('display', 'none');
		$('#lot_cnt8').css('display', 'none');
		$('#lot_cnt9').css('display', 'none');
		$('#lot_cnt10').css('display', 'none');

		$('#lot_no_7').val('');
		$('#lot_no_8').val('');
		$('#lot_no_9').val('');
		$('#lot_no_10').val('');
		$('#lot_area_7').val('');
		$('#lot_area_8').val('');
		$('#lot_area_9').val('');
		$('#lot_area_10').val('');
	}else if(frm=="7"){
		$('#la_cnt2').css('display', '');
		$('#la_cnt3').css('display', '');
		$('#la_cnt4').css('display', '');
		$('#la_cnt5').css('display', '');
		$('#la_cnt6').css('display', '');
		$('#la_cnt7').css('display', '');
		$('#la_cnt8').css('display', 'none');
		$('#la_cnt9').css('display', 'none');
		$('#la_cnt10').css('display', 'none');
		$('#lot_cnt2').css('display', '');
		$('#lot_cnt3').css('display', '');
		$('#lot_cnt4').css('display', '');
		$('#lot_cnt5').css('display', '');
		$('#lot_cnt6').css('display', '');
		$('#lot_cnt7').css('display', '');
		$('#lot_cnt8').css('display', 'none');
		$('#lot_cnt9').css('display', 'none');
		$('#lot_cnt10').css('display', 'none');

		$('#lot_no_8').val('');
		$('#lot_no_9').val('');
		$('#lot_no_10').val('');
		$('#lot_area_8').val('');
		$('#lot_area_9').val('');
		$('#lot_area_10').val('');
	}else if(frm=="8"){
		$('#la_cnt2').css('display', '');
		$('#la_cnt3').css('display', '');
		$('#la_cnt4').css('display', '');
		$('#la_cnt5').css('display', '');
		$('#la_cnt6').css('display', '');
		$('#la_cnt7').css('display', '');
		$('#la_cnt8').css('display', '');
		$('#la_cnt9').css('display', 'none');
		$('#la_cnt10').css('display', 'none');
		$('#lot_cnt2').css('display', '');
		$('#lot_cnt3').css('display', '');
		$('#lot_cnt4').css('display', '');
		$('#lot_cnt5').css('display', '');
		$('#lot_cnt6').css('display', '');
		$('#lot_cnt7').css('display', '');
		$('#lot_cnt8').css('display', '');
		$('#lot_cnt9').css('display', 'none');
		$('#lot_cnt10').css('display', 'none');

		$('#lot_no_9').val('');
		$('#lot_no_10').val('');
		$('#lot_area_9').val('');
		$('#lot_area_10').val('');
	}else if(frm=="9"){
		$('#la_cnt2').css('display', '');
		$('#la_cnt3').css('display', '');
		$('#la_cnt4').css('display', '');
		$('#la_cnt5').css('display', '');
		$('#la_cnt6').css('display', '');
		$('#la_cnt7').css('display', '');
		$('#la_cnt8').css('display', '');
		$('#la_cnt9').css('display', '');
		$('#la_cnt10').css('display', 'none');
		$('#lot_cnt2').css('display', '');
		$('#lot_cnt3').css('display', '');
		$('#lot_cnt4').css('display', '');
		$('#lot_cnt5').css('display', '');
		$('#lot_cnt6').css('display', '');
		$('#lot_cnt7').css('display', '');
		$('#lot_cnt8').css('display', '');
		$('#lot_cnt9').css('display', '');
		$('#lot_cnt10').css('display', 'none');

		$('#lot_no_10').val('');
		$('#lot_area_10').val('');
	} else {
		$('#la_cnt2').css('display', '');
		$('#la_cnt3').css('display', '');
		$('#la_cnt4').css('display', '');
		$('#la_cnt5').css('display', '');
		$('#la_cnt6').css('display', '');
		$('#la_cnt7').css('display', '');
		$('#la_cnt8').css('display', '');
		$('#la_cnt9').css('display', '');
		$('#la_cnt10').css('display', '');
		$('#lot_cnt2').css('display', '');
		$('#lot_cnt3').css('display', '');
		$('#lot_cnt4').css('display', '');
		$('#lot_cnt5').css('display', '');
		$('#lot_cnt6').css('display', '');
		$('#lot_cnt7').css('display', '');
		$('#lot_cnt8').css('display', '');
		$('#lot_cnt9').css('display', '');
		$('#lot_cnt10').css('display', '');
	}

}

</script>


<div id="content">

  <div id="content-header">
    <?if($mode=="e"){?>
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">기본정보</a> <a href="#" class="current">현장상세정보 수정</a>>  <?=$row1[h_name]?> </div>
    <?}else{?>
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">기본정보</a> <a href="#" class="current">현장상세정보 등록</a>>  <?=$row1[h_name]?> </div>
    <?}?>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">

      <div class="span6" style="width:98%;">
        <div class="widget-box">
          <div class="widget-content nopadding">

              <form name="ff" action="post.php" method="post" class="form-horizontal" onsubmit="return f_submit();">
			<input type="hidden" name="mode" id="mode" value="<?=$mode?>">
			<input type="hidden" name="h_idx" id="h_idx" value="<?=$h_idx?>">
			<input type="hidden" name="idx" id="idx" value="<?=$idx?>">

		<div class="control-group">

	 		<h4>&nbsp;▷ 기본정보</h4>
			<table  style="width:98%;margin:10px;">
			<tr>
				<td width="5%">* 단지명</td>
				<td width="40%" colspan=3><input type="text" class="span11" name="danji_name" value="<?=$row[danji_name]?>" style="width:200px;"></td>
			</tr>
			<tr>
				<td width="5%">* 물건지-지번</td>
				<td><input type="text" class="span11" name="jibun_addr" value="<?=$row[jibun_addr]?>"></td>
				<td width="5%">* 물건지 도로명주소</td>
				<td><input type="text" class="span11" name="doro_addr" value="<?=$row[doro_addr]?>"></td>
			</tr>
			<tr>
				<td>회사명</td>
				<td><input type="text" class="span11" name="d_com_name"  maxlength=20  value="<?=$row[d_com_name]?>"/></td>
				<td>주소</td>
				<td><input type="text" class="span11" name="d_addr" id="d_addr"   value="<?=$row[d_addr]?>"/></td>
			</tr>
			<tr>
				<td>사업자등록번호</td>
				<td><input type="text" class="span11" name="d_saup_no" id="d_saup_no"   value="<?=$row[d_saup_no]?>"/></td>
				<td>법인등록번호</td>
				<td><input type="text" class="span11" name="d_bubin_no" id="d_bubin_no"   value="<?=$row[d_bubin_no]?>"/></td>
			</tr>
			<tr>
				<td>대표자직책</td>
				<td><input type="text" class="span11" name="d_position" id="d_position"   value="<?=$row[d_position]?>"/></td>
				<td>대표자성명</td>
				<td><input type="text" class="span11" name="d_name" id="d_name"   value="<?=$row[d_name]?>"/></td>
			</tr>

			</table>
		</div>
		
		<div class="control-group">
	 		<h4>&nbsp;▷ 1동의 건물의 표시</h4>
			<table  style="width:98%;margin:10px;">
			<tr>
				<td width="10%">지번주소</td>
				<td width="60%"><input type="text" class="span11" name="building_jibun" id="building_jibun" value="<?=$row[building_jibun]?>"  style="width:700px;"></td>
				<td width="5%">건물명</td>
				<td width="25%"><input type="text" class="span11" name="building_name" id="building_name" value="<?=$row[building_name]?>"></td>
			</tr>
			<tr>
				<td>[도로명주소]</td>
				<td colspan=3><input type="text" class="span11" name="building_road_name" id="building_road_name" value="<?=$row[building_road_name]?>" style="width:700px;"></td>
			</tr>
			</table>
		</div>
		
		<div class="control-group">

	 		<h4>&nbsp;▷ 전유부분의 건물표시</h4>
			<table  style="width:98%;margin:10px;">
			<tr>
				<td width="10%">구조</td>
				<td width="90%"><input type="text" class="span11" name="building_dis_rescue" id="building_dis_rescue" value="<?=$row[building_dis_rescue]?>" ></td>
			</tr>
			</table>
		</div>

		<div class="control-group">

	 		<h4>&nbsp;▷ 대지권의 표시</h4>
			<table  style="width:98%;margin:10px;">
			<tr>
				<td width="12%">대지권인 토지의 필지갯수</td>
				<td colspan=3 >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<select name=lot_amount id="lot_amount" onchange="f_lot_cnt(this.value);" style="width:150px;">
				  <option value="1" <?if($row[lot_amount]=="1"){?>selected<?}?> <?if($mode=="i"){ ?> selected <?}?>>1</option> 
				  <option value="2" <?if($row[lot_amount]=="2"){?>selected<?}?>>2</option>
				  <option value="3" <?if($row[lot_amount]=="3"){?>selected<?}?>>3</option> 
				  <option value="4" <?if($row[lot_amount]=="4"){?>selected<?}?>>4</option>
				  <option value="5" <?if($row[lot_amount]=="5"){?>selected<?}?>>5</option> 
				  <option value="6" <?if($row[lot_amount]=="6"){?>selected<?}?>>6</option>
				  <option value="7" <?if($row[lot_amount]=="7"){?>selected<?}?>>7</option> 
				  <option value="8" <?if($row[lot_amount]=="8"){?>selected<?}?>>8</option>
				  <option value="9" <?if($row[lot_amount]=="9"){?>selected<?}?>>9</option> 
				  <option value="10" <?if($row[lot_amount]=="10"){?>selected<?}?>>10</option>
				  </select>
				</td>
			</tr>
			<tr>
				<td width="12%">토지의 표시(지번)</td>
				<td width="48%">
					<table>
						<tr name="la_cnt1" id="la_cnt1" width="100%">
							<td  width="100%">
							  &nbsp;&nbsp;1. <input type="text" class="span11" name="lot_no_1" id="lot_no_1" value="<?=$row[lot_no_1]?>" style="width:500px;">
							</td>
						</tr>
						<tr name="la_cnt2" id="la_cnt2">
							<td>
							  &nbsp;&nbsp;2. <input type="text" class="span11" name="lot_no_2" id="lot_no_2" value="<?=$row[lot_no_2]?>" style="width:500px;" >
							</td>
						</tr>
						<tr name="la_cnt3" id="la_cnt3">
							<td>
							  &nbsp;&nbsp;3. <input type="text" class="span11" name="lot_no_3" id="lot_no_3" value="<?=$row[lot_no_3]?>" style="width:500px;" >
							</td>
						</tr>
						<tr name="la_cnt4" id="la_cnt4">
							<td>
							  &nbsp;&nbsp;4. <input type="text" class="span11" name="lot_no_4" id="lot_no_4" value="<?=$row[lot_no_4]?>" style="width:500px;" >
							</td>
						</tr>
						<tr name="la_cnt5" id="la_cnt5">
							<td>
							  &nbsp;&nbsp;5. <input type="text" class="span11" name="lot_no_5" id="lot_no_5" value="<?=$row[lot_no_5]?>" style="width:500px;" >
							</td>
						</tr>
						<tr name="la_cnt6" id="la_cnt6">
							<td>
							  &nbsp;&nbsp;6. <input type="text" class="span11" name="lot_no_6" id="lot_no_6" value="<?=$row[lot_no_6]?>" style="width:500px;" >
							</td>
						</tr>
						<tr name="la_cnt7" id="la_cnt7">
							<td>
							  &nbsp;&nbsp;7. <input type="text" class="span11" name="lot_no_7" id="lot_no_7" value="<?=$row[lot_no_7]?>" style="width:500px;" >
							</td>
						</tr>
						<tr name="la_cnt8" id="la_cnt8">
							<td>
							  &nbsp;&nbsp;8. <input type="text" class="span11" name="lot_no_8" id="lot_no_8" value="<?=$row[lot_no_8]?>" style="width:500px;" >
							</td>
						</tr>
						<tr name="la_cnt9" id="la_cnt9">
							<td>
							  &nbsp;&nbsp;9. <input type="text" class="span11" name="lot_no_9" id="lot_no_9" value="<?=$row[lot_no_9]?>" style="width:500px;" >
							</td>
						</tr>
						<tr name="la_cnt10" id="la_cnt10">
							<td>
							  10. <input type="text" class="span11" name="lot_no_10" id="lot_no_10" value="<?=$row[lot_no_10]?>" style="width:500px;" >
							</td>
						</tr>
					</table>
					
				</td>
				<td width="10%">대(필지별)</td>
				<td width="30%">
					<table>
						<tr name="lot_cnt1" id="lot_cnt1">
							<td>
							  &nbsp;&nbsp;1. <input type="text" class="span11" name="lot_area_1" id="lot_area_1" maxlength=20 style="text-align:right;width:200px;" value="<?=$row[lot_area_1]?>" onkeyup="onlyNum(this);"/>
							</td>
						</tr>
						<tr name="lot_cnt2" id="lot_cnt2">
							<td>
							  &nbsp;&nbsp;2. <input type="text" class="span11" name="lot_area_2" id="lot_area_2" maxlength=20 style="text-align:right;width:200px;" value="<?=$row[lot_area_2]?>" onkeyup="onlyNum(this);"/>
							</td>
						</tr>
						<tr name="lot_cnt3" id="lot_cnt3">
							<td>
							  &nbsp;&nbsp;3. <input type="text" class="span11" name="lot_area_3" id="lot_area_3" maxlength=20 style="text-align:right;width:200px;" value="<?=$row[lot_area_3]?>" onkeyup="onlyNum(this);"/>
							</td>
						</tr>
						<tr name="lot_cnt4" id="lot_cnt4">
							<td>
							  &nbsp;&nbsp;4. <input type="text" class="span11" name="lot_area_4" id="lot_area_4" maxlength=20 style="text-align:right;width:200px;" value="<?=$row[lot_area_4]?>" onkeyup="onlyNum(this);"/>
							</td>
						</tr>
						<tr name="lot_cnt5" id="lot_cnt5">
							<td>
							  &nbsp;&nbsp;5. <input type="text" class="span11" name="lot_area_5" id="lot_area_5" maxlength=20 style="text-align:right;width:200px;" value="<?=$row[lot_area_5]?>" onkeyup="onlyNum(this);"/>
							</td>
						</tr>
						<tr name="lot_cnt6" id="lot_cnt6">
							<td>
							  &nbsp;&nbsp;6. <input type="text" class="span11" name="lot_area_6" id="lot_area_6" maxlength=20 style="text-align:right;width:200px;" value="<?=$row[lot_area_6]?>" onkeyup="onlyNum(this);"/>
							</td>
						</tr>
						<tr name="lot_cnt7" id="lot_cnt7">
							<td>
							  &nbsp;&nbsp;7. <input type="text" class="span11" name="lot_area_7" id="lot_area_7" maxlength=20 style="text-align:right;width:200px;" value="<?=$row[lot_area_7]?>" onkeyup="onlyNum(this);"/>
							</td>
						</tr>
						<tr name="lot_cnt8" id="lot_cnt8">
							<td>
							  &nbsp;&nbsp;8. <input type="text" class="span11" name="lot_area_8" id="lot_area_8" maxlength=20 style="text-align:right;width:200px;" value="<?=$row[lot_area_8]?>" onkeyup="onlyNum(this);"/>
							</td>
						</tr>
						<tr name="lot_cnt9" id="lot_cnt9">
							<td>
							  &nbsp;&nbsp;9. <input type="text" class="span11" name="lot_area_9" id="lot_area_9" maxlength=20 style="text-align:right;width:200px;" value="<?=$row[lot_area_9]?>" onkeyup="onlyNum(this);"/>
							</td>
						</tr>
						<tr name="lot_cnt10" id="lot_cnt10">
							<td>
							  10. <input type="text" class="span11" name="lot_area_10" id="lot_area_10" maxlength=20 style="text-align:right;width:200px;" value="<?=$row[lot_area_10]?>" onkeyup="onlyNum(this);"/>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
			<tr>
				<td width="10%">대지권의 종류</td>
				<td width="40%">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="area_kiinds" id="area_kiinds" value="<?=$row[area_kiinds]?>" style="width:500px;" >
				</td>
				<td width="10%">대지권의 비율(분모)</td>
				<td width="40%">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="area_ratio" id="area_ratio" maxlength=20 style="text-align:right;width:200px;" value="<?=$row[area_ratio]?>" onkeyup="onlyNum(this);"/>
				</td>
			</tr>
			</tr>
			</table>
		</div>

		<div class="control-group">
	 		<h4>&nbsp;▷ 도로명물건지표현</h4>
			<table  style="width:98%;margin:10px;">
			<tr>
				<td width="10%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;동</td>
				<td width="40%">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="road_dong" id="road_dong" value="<?=$row[road_dong]?>" style="width:300px;" >
				</td>
				<td width="10%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;건물명</td>
				<td width="40%">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="road_building_name" id="road_building_name" value="<?=$row[road_building_name]?>" style="width:500px;" >
				</td>
			</tr>
			</table>
		</div>

		<div class="control-group">
	 		<h4>&nbsp;▷ 단지별 신청서/위임장 정보</h4>
			<table  style="width:98%;margin:10px;">
			<tr>
				<td width="15%" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;변경할사항<br>(토지신탁접수일)</td>
				<td width="10%"><input type=text name="trust_date" id="trust_date" value="<?=$row[trust_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"></td>
				<td width="10%">토지신탁접수번호</td>
				<td width="20%"><input type="text" class="span11" name="trust_no" id="trust_no" value="<?=$row[trust_no]?>" style="width:150px;"></td>
				<td width="10%">토지신탁원부</td>
				<td width="20%"><input type="text" class="span11" name="trust_org" id="trust_org" value="<?=$row[trust_org]?>" style="width:150px;"> 호</td>
			</tr>
			</table>
		</div>
				
		<div class="control-group">
			<br>
			<div style="text-align:center;" >
				<button type="submit" class="btn btn-success">저장</button>&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="button" class="btn btn-success"  onclick="javascript:f_delete(<?=$idx?>);">삭제</button>&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="button" class="btn btn-success" onclick="javascript:window.close();">닫기</button>
			</div>
			<br>

		</div>

            </form>

        </div>
      </div>
    </div>

  </div>
</div>


<script src="/js/common.js"></script> 
<script src="/js/bootstrap.min.js"></script> 

<script>
function f_danji(p1){
	var aa = window.open("/9_basic/941_hyunjang_danji/index.html?h_idx="+p1,"width=500,width=500","scrollbars=yes");
	aa.focus();
}

function f_delete(p1){
	if(confirm("삭제하시겠습니까?")){
		location.href = "post.php?h_idx=<?=$h_idx?>&idx=<?=$idx?>&mode=d";
	}
}

function f_submit(){
	var v = document.ff;
	<?if($mode=="i"){?>
		if(v.danji_name.value==""){
			alert("단지명을 입력하세요.");
			v.danji_name.focus();
			return false;
		}else if(v.jibun_addr.value=="n"){
			alert("물건지-지번을 입력하세요.");
			v.jibun_addr.focus();
			return false;
		}else if(v.doro_addr.value=="n"){
			alert("물건지-도로명주소를 입력하세요.");
			v.jibun_addr.focus();
			return false;
		}else{
			return true;
		}
	<?}else{?>
		if(v.danji_name.value=="n"){
			alert("단지명을 입력하세요.");
			v.danji_name.focus();
			return false;
		}else{
			return true;
		}
	<?}?>
}


</script>

</body>
</html>
