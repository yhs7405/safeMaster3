<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);


	$h_idx		=	urldecode(trim($_REQUEST[h_idx]));

	//설정상세조회
	$sql= "select * from tbl_hyunjang_info where h_idx='{$h_idx}' ";
	//Echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$rows = $stmt->fetch();
//	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '1' and member_gubun = '1' and dachul_gubun = 'y' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row1 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '1' and member_gubun = '1' and dachul_gubun = 'n' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row2 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '1' and member_gubun = '2' and dachul_gubun = 'y' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row3 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '1' and member_gubun = '2' and dachul_gubun = 'n' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row4 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '1' and member_gubun = '3' and dachul_gubun = 'y' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row5 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '1' and member_gubun = '3' and dachul_gubun = 'n' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row6 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '1' and member_gubun = '4' and dachul_gubun = 'y' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row7 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '1' and member_gubun = '4' and dachul_gubun = 'n' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row8 = $stmt1->fetch();
	
	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '2' and member_gubun = '1' and dachul_gubun = 'y' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row9 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '2' and member_gubun = '1' and dachul_gubun = 'n' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row10 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '2' and member_gubun = '2' and dachul_gubun = 'y' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row11 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '2' and member_gubun = '2' and dachul_gubun = 'n' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row12 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '2' and member_gubun = '3' and dachul_gubun = 'y' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row13 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '2' and member_gubun = '3' and dachul_gubun = 'n' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row14 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '2' and member_gubun = '4' and dachul_gubun = 'y' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row15 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '2' and member_gubun = '4' and dachul_gubun = 'n' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row16 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '3' and member_gubun = '1' and dachul_gubun = 'y' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row17 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '3' and member_gubun = '1' and dachul_gubun = 'n' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row18 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '3' and member_gubun = '2' and dachul_gubun = 'y' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row19 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '3' and member_gubun = '2' and dachul_gubun = 'n' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row20 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '3' and member_gubun = '3' and dachul_gubun = 'y' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row21 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '3' and member_gubun = '3' and dachul_gubun = 'n' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row22 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '3' and member_gubun = '4' and dachul_gubun = 'y' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row23 = $stmt1->fetch();

	$sql= "select * from tbl_bosu_cost_set where h_idx='{$h_idx}' and gigan_gubun = '3' and member_gubun = '4' and dachul_gubun = 'n' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row24 = $stmt1->fetch();

?>

  
<!DOCTYPE html>
<html lang="kr">

<head>
<title>재무돌이</title>
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

<body style="background:white;">

<!--header 시작-->
	<?include ("../include/header_none.php");?>
<!--header 종료-->


<style>
	.top_box th{ text-align:left; }
</style>
<div id="content">

  <div id="content-header">
    <div id="breadcrumb" style="text-align:center;"><h3>보수료등 등록</h3></div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
			<form name=ffx method="post">
				<input type=hidden name="h_idx" value="<?=$rows[h_idx]?>">
			</form>

			<form name=ffm method="post">

			<div class="widget-content nopadding">


			<div class="control-group">

<h4>현장명 : <?=$rows[h_name]?></h4> <br>


		가. 입주기간내
        <div class="widget-box">
		
          <div class="widget-content nopadding">
				<input type=hidden name="h_idx" value="<?=$rows[h_idx]?>">
				<input type=hidden name="reg_mem_yn" value="<?=$rows[reg_mem_yn]?>">
				<input type=hidden name="non_mem_yn" value="<?=$rows[non_mem_yn]?>">
				<input type=hidden name="gen_mem_yn" value="<?=$rows[gen_mem_yn]?>">
				<input type=hidden name="web_mem_yn" value="<?=$rows[web_mem_yn]?>">

            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>회원종류</th>
                  <th>대출여부</th>
                  <th>이전보수료</th>
                  <th>이전보수료 부가세</th>
                  <th>제증명</th>
                  <th>신탁보수료</th>
                  <th>신탁보수료 부가세</th>
                </tr>
              </thead>
              <tbody>

              	<?	if($rows[reg_mem_yn] == "y"){  ?>
	              <tr class="odd gradeX">
	                <td style="text-align:center;" rowspan=2>정회원</td>
	                <td style="text-align:center;">대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_1" value="<?=f_money0($row1[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_2)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_1" value="<?=f_money0($row1[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_2)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_1" value="<?=f_money0($row1[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_2)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_1" value="<?=f_money0($row1[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_2)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_1" value="<?=f_money0($row1[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_2)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
	              <tr class="odd gradeX">
	                <td style="text-align:center;">무대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_2" value="<?=f_money0($row2[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_3)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_2" value="<?=f_money0($row2[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_3)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_2" value="<?=f_money0($row2[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_3)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_2" value="<?=f_money0($row2[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_3)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_2" value="<?=f_money0($row2[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_3)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
              	<?	}  ?>
              	<?	if($rows[non_mem_yn] == "y"){  ?>
	              <tr class="odd gradeX">
	                <td style="text-align:center;" rowspan=2>(비)회원</td>
	                <td style="text-align:center;">대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_3" value="<?=f_money0($row3[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_4)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_3" value="<?=f_money0($row3[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_4)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_3" value="<?=f_money0($row3[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_4)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_3" value="<?=f_money0($row3[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_4)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_3" value="<?=f_money0($row3[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_4)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
	              <tr class="odd gradeX">
	                <td style="text-align:center;">무대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_4" value="<?=f_money0($row4[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_5)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_4" value="<?=f_money0($row4[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_5)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_4" value="<?=f_money0($row4[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_5)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_4" value="<?=f_money0($row4[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_5)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_4" value="<?=f_money0($row4[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_5)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
              	<?	}  ?>
              	<?	if($rows[gen_mem_yn] == "y"){  ?>
	              <tr class="odd gradeX">
	                <td style="text-align:center;" rowspan=2>일반회원</td>
	                <td style="text-align:center;">대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_5" value="<?=f_money0($row5[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_6)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_5" value="<?=f_money0($row5[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_6)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_5" value="<?=f_money0($row5[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_6)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_5" value="<?=f_money0($row5[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_6)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_5" value="<?=f_money0($row5[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_6)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
	              <tr class="odd gradeX">
	                <td style="text-align:center;">무대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_6" value="<?=f_money0($row6[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_7)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_6" value="<?=f_money0($row6[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_7)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_6" value="<?=f_money0($row6[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_7)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_6" value="<?=f_money0($row6[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_7)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_6" value="<?=f_money0($row6[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_7)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
              	<?	}  ?>
              	<?	if($rows[web_mem_yn] == "y"){  ?>
	              <tr class="odd gradeX">
	                <td style="text-align:center;" rowspan=2>웹회원</td>
	                <td style="text-align:center;">대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_7" value="<?=f_money0($row7[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_8)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_7" value="<?=f_money0($row7[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_8)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_7" value="<?=f_money0($row7[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_8)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_7" value="<?=f_money0($row7[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_8)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_7" value="<?=f_money0($row7[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_8)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
	              <tr class="odd gradeX">
	                <td style="text-align:center;">무대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_8" value="<?=f_money0($row8[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_9)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_8" value="<?=f_money0($row8[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_9)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_8" value="<?=f_money0($row8[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_9)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_8" value="<?=f_money0($row8[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_9)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_8" value="<?=f_money0($row8[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_9)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
              	<?	}  ?>

              </tbody>
            </table>
          </div>
        </div>

		나. 입주지정기간 종료 후 ~ 보수기준일 까지
        <div class="widget-box">
		
          <div class="widget-content nopadding">

            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>회원종류</th>
                  <th>대출여부</th>
                  <th>이전보수료</th>
                  <th>이전보수료 부가세</th>
                  <th>제증명</th>
                  <th>신탁보수료</th>
                  <th>신탁보수료 부가세</th>
                </tr>
              </thead>
              <tbody>

              	<?	if($rows[reg_mem_yn] == "y"){  ?>
	              <tr class="odd gradeX">
	                <td style="text-align:center;" rowspan=2>정회원</td>
	                <td style="text-align:center;">대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_9" value="<?=f_money0($row9[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_10)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_9" value="<?=f_money0($row9[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_10)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_9" value="<?=f_money0($row9[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_10)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_9" value="<?=f_money0($row9[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_10)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_9" value="<?=f_money0($row9[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_10)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
	              <tr class="odd gradeX">
	                <td style="text-align:center;">무대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_10" value="<?=f_money0($row10[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_11)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_10" value="<?=f_money0($row10[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_11)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_10" value="<?=f_money0($row10[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_3)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_10" value="<?=f_money0($row10[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_11)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_10" value="<?=f_money0($row10[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_11)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
              	<?	}  ?>
              	<?	if($rows[non_mem_yn] == "y"){  ?>
	              <tr class="odd gradeX">
	                <td style="text-align:center;" rowspan=2>(비)회원</td>
	                <td style="text-align:center;">대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_11" value="<?=f_money0($row11[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_12)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_11" value="<?=f_money0($row11[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_12)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_11" value="<?=f_money0($row11[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_12)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_11" value="<?=f_money0($row11[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_12)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_11" value="<?=f_money0($row11[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_12)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
	              <tr class="odd gradeX">
	                <td style="text-align:center;">무대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_12" value="<?=f_money0($row12[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_13)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_12" value="<?=f_money0($row12[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_13)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_12" value="<?=f_money0($row12[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_13)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_12" value="<?=f_money0($row12[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_13)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_12" value="<?=f_money0($row12[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_13)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
              	<?	}  ?>
              	<?	if($rows[gen_mem_yn] == "y"){  ?>
	              <tr class="odd gradeX">
	                <td style="text-align:center;" rowspan=2>일반회원</td>
	                <td style="text-align:center;">대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_13" value="<?=f_money0($row13[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_14)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_13" value="<?=f_money0($row13[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_14)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_13" value="<?=f_money0($row13[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_14)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_13" value="<?=f_money0($row13[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_14)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_13" value="<?=f_money0($row13[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_14)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
	              <tr class="odd gradeX">
	                <td style="text-align:center;">무대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_14" value="<?=f_money0($row14[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_15)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_14" value="<?=f_money0($row14[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_15)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_14" value="<?=f_money0($row14[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_15)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_14" value="<?=f_money0($row14[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_15)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_14" value="<?=f_money0($row14[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_15)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
              	<?	}  ?>
              	<?	if($rows[web_mem_yn] == "y"){  ?>
	              <tr class="odd gradeX">
	                <td style="text-align:center;" rowspan=2>웹회원</td>
	                <td style="text-align:center;">대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_15" value="<?=f_money0($row15[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_16)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_15" value="<?=f_money0($row15[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_16)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_15" value="<?=f_money0($row15[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_16)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_15" value="<?=f_money0($row15[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_16)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_15" value="<?=f_money0($row15[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_16)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
	              <tr class="odd gradeX">
	                <td style="text-align:center;">무대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_16" value="<?=f_money0($row16[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_17)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_16" value="<?=f_money0($row16[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_17)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_16" value="<?=f_money0($row16[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_17)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_16" value="<?=f_money0($row16[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_17)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_16" value="<?=f_money0($row16[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_17)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
              	<?	}  ?>

              </tbody>
            </table>
          </div>
        </div>

		다. 보수기준일 다음날부터
        <div class="widget-box">
		
          <div class="widget-content nopadding">

            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>회원종류</th>
                  <th>대출여부</th>
                  <th>이전보수료</th>
                  <th>이전보수료 부가세</th>
                  <th>제증명</th>
                  <th>신탁보수료</th>
                  <th>신탁보수료 부가세</th>
                </tr>
              </thead>
              <tbody>

              	<?	if($rows[reg_mem_yn] == "y"){  ?>
	              <tr class="odd gradeX">
	                <td style="text-align:center;" rowspan=2>정회원</td>
	                <td style="text-align:center;">대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_17" value="<?=f_money0($row17[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_18)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_17" value="<?=f_money0($row17[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_18)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_17" value="<?=f_money0($row17[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_18)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_17" value="<?=f_money0($row17[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_18)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_17" value="<?=f_money0($row17[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_18)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
	              <tr class="odd gradeX">
	                <td style="text-align:center;">무대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_18" value="<?=f_money0($row18[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_19)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_18" value="<?=f_money0($row18[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_19)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_18" value="<?=f_money0($row18[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_19)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_18" value="<?=f_money0($row18[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_19)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_18" value="<?=f_money0($row18[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_19)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
              	<?	}  ?>
              	<?	if($rows[non_mem_yn] == "y"){  ?>
	              <tr class="odd gradeX">
	                <td style="text-align:center;" rowspan=2>(비)회원</td>
	                <td style="text-align:center;">대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_19" value="<?=f_money0($row19[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_20)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_19" value="<?=f_money0($row19[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_20)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_19" value="<?=f_money0($row19[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_20)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_19" value="<?=f_money0($row19[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_20)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_19" value="<?=f_money0($row19[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_20)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
	              <tr class="odd gradeX">
	                <td style="text-align:center;">무대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_20" value="<?=f_money0($row20[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_21)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_20" value="<?=f_money0($row20[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_21)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_20" value="<?=f_money0($row20[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_21)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_20" value="<?=f_money0($row20[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_21)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_20" value="<?=f_money0($row20[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_21)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
              	<?	}  ?>
              	<?	if($rows[gen_mem_yn] == "y"){  ?>
	              <tr class="odd gradeX">
	                <td style="text-align:center;" rowspan=2>일반회원</td>
	                <td style="text-align:center;">대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_21" value="<?=f_money0($row21[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_22)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_21" value="<?=f_money0($row21[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_22)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_21" value="<?=f_money0($row21[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_22)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_21" value="<?=f_money0($row21[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_22)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_21" value="<?=f_money0($row21[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_22)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
	              <tr class="odd gradeX">
	                <td style="text-align:center;">무대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_22" value="<?=f_money0($row22[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_23)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_22" value="<?=f_money0($row22[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_23)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_22" value="<?=f_money0($row22[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_23)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_22" value="<?=f_money0($row22[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_23)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_22" value="<?=f_money0($row22[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_23)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
              	<?	}  ?>
              	<?	if($rows[web_mem_yn] == "y"){  ?>
	              <tr class="odd gradeX">
	                <td style="text-align:center;" rowspan=2>웹회원</td>
	                <td style="text-align:center;">대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_23" value="<?=f_money0($row23[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_24)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_23" value="<?=f_money0($row23[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_24)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_23" value="<?=f_money0($row23[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_24)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_23" value="<?=f_money0($row23[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_24)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_23" value="<?=f_money0($row23[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_24)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
	              <tr class="odd gradeX">
	                <td style="text-align:center;">무대출</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_24" value="<?=f_money0($row24[ijeon_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_25)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_24" value="<?=f_money0($row24[ijeon_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_25)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="proof_cost_xx"  name="proof_cost_24" value="<?=f_money0($row24[proof_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(proof_cost_25)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_24" value="<?=f_money0($row24[sintak_bosu_cost])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_25)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_24" value="<?=f_money0($row24[sintak_bosu_vat])?>" style="width:120px;height:15px;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_25)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
	              </tr>
              	<?	}  ?>

              </tbody>
            </table>
          </div>
        </div>



			</div>

<br>
<?if($_SESSION["admin_permission"][ch_db2]=="y"){?>
		<?	if($rows > 0){  ?>
				<div style="float:right;margin-top:-25px;margin-bottom:10px;margin-right:10px;">
					<button type="button" class="btn btn-success" onclick="javascript:f_submit();">저장</button>
				</div>
		<?	} else {  ?>
				<div style="float:right;margin-top:0px;margin-bottom:10px;margin-right:10px;">
					<button type="button" class="btn btn-success" onclick="javascript:f_submit();">저장</button>
				</div>
		<?	}  ?>
<?}?>
<br>

      </div>
			</form>
      
    	</div>

  	</div>
	</div>
</div>


<script src="/js/common.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script>
function f_chaemu(idx,a1,suljung_no){
	var frm    = document.ff;
	var url    ="popup_s_chaemu.php?idx="+idx+"&a1="+encodeURI(a1)+"&suljung_no="+suljung_no;
	var title  = "listpop111";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=700, height=300, top=100,left=100"; 
	var a12 = window.open(url, title,status);
	a12.focus();
}

function f_bank(p1,a1){
	var frm    = document.ff;
	var url    ="popup_s_bank.php?idx="+p1+"&a1="+encodeURI(a1);
	var title  = "listpop";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=700, height=300, top=0,left=20"; 
	var a = window.open(url, title,status);
	a.focus();
}

function f_cc11(p1,a1){
	var frm    = document.ff;
	var url    ="popup_s_cc.php?idx="+p1+"&a1="+encodeURI(a1);
	var title  = "listpop11";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=700, height=300, top=100,left=100"; 
	var a1 = window.open(url, title,status);
	a1.focus();
}

$(document).ready(function(){
	$("#chx").click(function(e){ 
		if($(this).is(":checked")){
			//alert(1);
			$(".ch").prop("checked", true);
		}else{
			//alert(0);
			$(".ch").prop("checked", false);
		}
	});
});

function f_submitx(){
	document.ffx.page.value=1;
	document.ffx.target="_self";
	document.ffx.action = "bosu_popup.php";
	document.ffx.submit();
}

function f_submit(){
	var v = document.ffm;
	if(confirm("저장하시겠습니까?")){
		var frm    = document.ffm;
		var url    = "bosu_post.php";
		var title  = "listpop22";
		var status = "toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no,width=100, height=100, top=400,left=800"; 
		var aa2 = window.open("", title,status);
		frm.target = title;
		frm.action = url;
		frm.method = "post";
		frm.submit();
		aa2.focus();
	}
}
</script>

</body>
</html>
