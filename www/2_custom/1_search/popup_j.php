<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";

	$a1		=	urldecode(trim($_REQUEST[a1]));

	//현장정보
	$sql= "select * from tbl_junib where a1='{$a1}' ";
	//echo $sql;
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();
	$stmt1->setFetchMode(PDO::FETCH_ASSOC);
	$r = $stmt1->fetch();

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
    <div id="breadcrumb" style="text-align:center;"><h3>진행사항 상세조회</h3></div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">

			<form name=ff action="popup_g_proc.php" method=post>
			<input type=hidden name=a1 value="<?=$a1?>">

			<div class="widget-content nopadding">

				<div class="control-group">
				<div class="widget-box">
				  <div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
					  <thead>
						<tr>
						  <th>고객고유정보</th>
						  <th>등기접수일</th>
						  <th>동</th>
						  <th>호</th>
						  <th>취득자1</th>
						  <th>취득자2</th>
						  <th>전화번호</th>
						</tr>
					  </thead>
					  <tbody>
						<tr class="odd gradeX">
						  <td style="text-align:center;"><?=$r[a1]?></td>
						  <td style="text-align:center;"><?=f_date($r[g1])?></td>
						  <td style="text-align:center;"><?=$r[h1]?></td>
						  <td style="text-align:center;"><?=$r[i1]?></td>
						  <td style="text-align:center;"><?=$r[j1]?></td>
						  <td style="text-align:center;"><?=$r[m1]?></td>
						  <td style="text-align:center;"><?=$r[p1]?></td>
						</tr>
					  </tbody>
					</table>
				  </div>
				</div>

				<div class="control-group">
				> 이전 진행사항
				<div class="widget-box">
				  <div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
					  <thead>
						<tr>
						  <th>구분</th>
						  <th>필수수령일</th>
						  <th>필증수령자</th>
						  <th>필증전달일</th>
						  <th>필증전달자</th>
						  <th>정산완료일</th>
						  <th>정산완료자</th>
						  <th>필증배포일</th>
						  <th>필증배포자</th>
						</tr>
					  </thead>
					  <tbody>
						<tr class="odd gradeX">
						  <td style="text-align:center;">이전</td>
						  <td style="text-align:center;"><?=f_date($r[ijp_s_date])?></td>
						  <td style="text-align:center;"><?=f_id_value($r[ijp_s_id])?></td>
						  <td style="text-align:center;"><?=f_date($r[ijp_j_date])?></td>
						  <td style="text-align:center;"><?=f_id_value($r[ijp_j_id])?></td>
						  <td style="text-align:center;"><?=f_date($r[ijj_w_date])?></td>
						  <td style="text-align:center;"><?=f_id_value($r[ijj_w_id])?></td>
						  <td style="text-align:center;"><?=f_date($r[ijp_b_date])?></td>
						  <td style="text-align:center;"><?=f_id_value($r[ijp_b_id])?></td>
						</tr>
					  </tbody>
					</table>
				  </div>
				</div>


				<div class="control-group">
				> 환불(정산) 진행사항
				<div class="widget-box">
				  <div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
					  <thead>
						<tr>
						  <th>구분</th>
						  <th>추가입금대상여부</th>
						  <th>환불은행</th>
						  <th>환불계좌</th>
						  <th>환불예금주</th>
						  <th>환불(정산)일</th>
						  <th>환불(정산)처리자</th>
						  <th>환불(정산금)</th>
						</tr>
					  </thead>
					  <tbody>
						<tr class="odd gradeX">
						  <td style="text-align:center;">이전</td>
						  <td style="text-align:center;"><?=$r[chuga_ibgum_daesang]?></td>
						  <td style="text-align:center;"><?=$r[refund_bank]?></td>
						  <td style="text-align:center;"><?=$r[refund_account]?></td>
						  <td style="text-align:center;"><?=$r[refund_name]?></td>
						  <td style="text-align:center;"><?=f_date($r[refund_date])?></td>
						  <td style="text-align:center;"><?=f_id_value($r[refund_id])?></td>
						  <td style="text-align:center;"><?=f_money($r[refund_money])?></td>
						</tr>
					  </tbody>
					</table>
				  </div>
				</div>



				<div class="control-group">
				> 설정 진행사항
				<div class="widget-box">
				  <div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
					  <thead>
						<tr>
						  <th>구분</th>
						  <th>필증수령일</th>
						  <th>필증수령자</th>
						  <th>필증전달일</th>
						  <th>필증전달자</th>
						  <th>정산완료일</th>
						  <th>정산완료자</th>
						  <th>필증배포일</th>
						  <th>필증배포자</th>
						</tr>
					  </thead>
					  <tbody>
<?
	$sql = "select * from tbl_suljung where a1 = '{$a1}' order by suljung_no asc";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	if($rows > 0){
			while($row = $stmt->fetch()){?>

						<tr class="odd gradeX">
						  <td style="text-align:center;">설정<?=$row[suljung_no]?></td>
						  <td style="text-align:center;"><?=$row[sjp_s_date]?></td>
						  <td style="text-align:center;"><?=f_id_value($r[sjp_s_id])?></td>
						  <td style="text-align:center;"><?=$row[sjp_j_datte]?></td>
						  <td style="text-align:center;"><?=f_id_value($r[sjp_j_id])?></td>
						  <td style="text-align:center;"><?=$row[sjj_w_date]?></td>
						  <td style="text-align:center;"><?=f_id_value($r[sjj_w_id])?></td>
						  <td style="text-align:center;"><?=$row[sjp_b_date]?></td>
						  <td style="text-align:center;"><?=f_id_value($r[sjp_b_id])?></td>
						</tr>
			<?}?>
<?}?>

					  </tbody>
					</table>
				  </div>
				</div>


			</div>
			</form>

      </div>
    </div>
  </div>
</div>



<script src="/js/common.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script>
function f_submit(){
	if(confirm("저장하시겠습니까?")){
		document.ff.submit();
	}
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

</script>

</body>
</html>
