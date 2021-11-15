<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);


	$a1		=	urldecode(trim($_REQUEST[a1]));

	//설정상세조회
	$sql= "select * from tbl_suljung where a1='{$a1}' order by suljung_no asc ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();


	$sql= "select * from tbl_junib where a1='{$a1}' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$row1 = $stmt1->fetch();

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

<body style="background:white;">

<!--header 시작-->
	<?include ("../include/header_none.php");?>
<!--header 종료-->


<style>
	.top_box th{ text-align:left; }
</style>
<div id="content">

  <div id="content-header">
    <div id="breadcrumb" style="text-align:center;"><h3>설정상세조회</h3></div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">

		<div class="widget-content nopadding">


		<div class="control-group">


		설정개수 : 총 <?=$rows?> 건
        <div class="widget-box">
		
          <div class="widget-content nopadding">

            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>구분</th>
                  <th>은행</th>
                  <th>지점</th>
                  <th>채권최고액</th>
                  <th>채무자</th>
                  <th>채무자주민등록번호</th>
                  <th>설정채권매입액</th>
                  <th>등록면허세</th>
                  <th>지방교육세</th>
                </tr>
              </thead>
              <tbody>

	<?
		$T=1;
			while($row = $stmt->fetch()){?>
                <tr class="odd gradeX">
                  <td style="text-align:center;">설정<?=$row[suljung_no]?></td>
                  <td style="text-align:center;"><A href="javascript:f_bank(<?=$row[idx]?>,'<?=$a1?>');" style="text-decoration:underline;"><?=f_bank_name($row[bank_code])?></a></td>
                  <td style="text-align:center;"><?=f_jijum_name($row[jijum_code])?></td>
                  <td style="text-align:center;"><a href="javascript:f_cc11(<?=$row[idx]?>,'<?=$a1?>');"  style="text-decoration:underline;"><?=f_money($row[chaekwon_max])?></a></td>

		<?if($row1["aw".$row[suljung_no]]!=""){?>
                  <td style="text-align:center;"><a href="javascript:f_chaemu(<?=$row[idx]?>,'<?=$a1?>','<?=$row[suljung_no]?>');"  style="text-decoration:underline;"><?=$row1["aw".$row[suljung_no]]?></a></td>
		<?}else{?>
                  <td style="text-align:center;"><a href="javascript:f_chaemu(<?=$row[idx]?>,'<?=$a1?>','<?=$row[suljung_no]?>');"  style="text-decoration:underline;color:red;">입력하세요</a></td>
		<?}?>

                  <td style="text-align:center;"><?=f_jumin_valid($row1["aw".$row[suljung_no]."_jumin"])?></td>
                  <td style="text-align:center;"><?=f_money($row[suljung_maeib])?></td>
                  <td style="text-align:center;"><?=f_money($row[regi_lic])?></td>
                  <td style="text-align:center;"><?=f_money($row[local_edu])?></td>
                </tr>

	<?$T++;}?>


              </tbody>
            </table>
          </div>
        </div>



		</div>

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

	select_detail('bank_code','jijum_code');

function f_submit(){
	document.ff.submit();
}
</script>

</body>
</html>
