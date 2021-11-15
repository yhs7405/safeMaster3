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
	$row1 = $stmt1->fetch();


	$sql= "select * from tbl_hyunjang_info where h_idx='{$row1[h_idx]}' ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	//현장단지정보 목록 4개
	$sql = "select * from tbl_hyunjang_danji_info where h_idx={$row1[h_idx]} ";
	//echo $sql;
	$stmth = $pdo->prepare($sql);
	$stmth->execute();
	$rowsh = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	//현장은행지점정보 목록 4개
	$sql = "select * from tbl_bank_jijum_rate where h_idx={$row1[h_idx]}";
	//echo $sql;
	$stmtb = $pdo->prepare($sql);
	$stmtb->execute();
	$rowsb = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();
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
    <div id="breadcrumb" style="text-align:center;"><h3>현장상세조회</h3></div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">

		<div class="widget-content nopadding">


		<div class="control-group">

			<table  style="width:98%;margin:10px;">
			<tr>
				<td width="10%">* 현장명</td>
				<td width="40%"><input type="text" class="span11" name="h_name" id="h_name" value="<?=$row[h_name]?>"></td>
				<td width="10%">세대수</td>
				<td width="40%"><input type="text" class="span11" name="sum_sedae" value="<?=$row[sum_sedae]?>"></td>
			</tr>
			<tr>
				<td width="10%">* 고유번호용문구</td>
				<td width="40%"><input type="text" class="span11" name="no_text" id="no_text"   value="<?=$row[no_text]?>" style="width:300px;"/><?if($mode=="i"){?>&nbsp;<button type="button" class="btn btn-success" onclick="javascript:f_duplicate();">현장명중복</button><?}?></td></td>
				<td width="10%">관할등기소</td>
				<td width="40%"><input type="text" class="span11" name="registery_office"  value="<?=$row[registery_office]?>"></td>
			</tr>
			<tr>
				<td>거래처코드</td>
				<td><input type="text" class="span11" name="trade_code" id="trade_code"  maxlength=20  value="<?=$row[trade_code]?>"/></td>
				<td>거래처명</td>
				<td><input type="text" class="span11" name="trade_name" id="trade_name"   value="<?=$row[trade_name]?>"/></td>
			</tr>

			<tr>
				<td>거래처특이사항</td>
				<td colspan=3><input type="text" name="etc" style="width:98%;height:30px;"  value="<?=$row[etc]?>"></td>
			</tr>

			<tr>
				<td>작업소속</td>
				<td><select name="sosok" id="sosok">
						<option value="">--소속선택--</option>
						<?$sql = "select * from tbl_sosok";
						$sosok_r = $pdo->prepare($sql);
						$sosok_r->execute();
						while($rr = $sosok_r->fetch()){?>
							<option value="<?=$rr[sosok_code]?>" <?if($rr[sosok_code]==$row[sosok]){?>selected<?}?>><?=$rr[sosok_name]?></option>
						<?}?>
					</select>
				</td>
				<td>* 주소</td>
				<td><input type="text" class="span11" name="addr" id="addr"  placeholder="주소"  maxlength=150   value="<?=$row[addr]?>"/></td>
			</tr>

			</table>
			<br>

		> 단지 : 총 <?=$rowsh?> 건
        <div class="widget-box">
		
          <div class="widget-content nopadding">

            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="font-size:8pt;" rowspan=2>No</th>
                  <th style="font-size:8pt;" rowspan=2>단지명</th>
                  <th style="font-size:8pt;" colspan=2>물건지주소</th>
                  <th style="font-size:8pt;" rowspan=2>시행사명</th>
                  <th style="font-size:8pt;" rowspan=2>사업자번호</th>
                  <th style="font-size:8pt;" rowspan=2>법인번호</th>
                  <th style="font-size:8pt;" rowspan=2>시행사주소</th>
                  <th style="font-size:8pt;" rowspan=2>대표자직책</th>
                  <th style="font-size:8pt;" rowspan=2>대표자명</th>
                </tr>
                <tr>
                  <th>도로명</th>
                  <th>지번</th>
                </tr>
              </thead>
              <tbody>

	<?
		$T=1;
			while($rowh = $stmth->fetch()){?>
                <tr class="odd gradeX">
                  <td style="text-align:center;font-size:8pt;"><?=$T?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$rowh[danji_name]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$rowh[jibun_addr]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$rowh[doro_addr]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$rowh[d_com_name]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$rowh[d_saup_no]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$rowh[d_bubin_no]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$rowh[d_addr]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$rowh[d_position]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$rowh[d_name]?></td>
                </tr>

	<?$T++;}?>



              </tbody>
            </table>
          </div>
        </div>



		> 은행 : 총 <?=$rowsb?> 건
        <div class="widget-box">
		
          <div class="widget-content nopadding">

            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="font-size:8pt;" rowspan=2>No</th>
                  <th style="font-size:8pt;" rowspan=2>은행</th>
                  <th style="font-size:8pt;" rowspan=2>지점</th>
                  <th style="font-size:8pt;" rowspan=2>구분</th>
                  <th style="font-size:8pt;" colspan=5>법무사보수료</th>
                  <th style="font-size:8pt;" colspan=7>공과금</th>
                </tr>
                <tr>
                  <th style="font-size:8pt;">기본보수료</th>
                  <th style="font-size:8pt;">할인율</th>
                  <th style="font-size:8pt;">등록세<br>신고납<br>부대행</th>
                  <th style="font-size:8pt;">교통비</th>
                  <th style="font-size:8pt;">원인증서작성료</th>

                  <th style="font-size:8pt;">증지대<br>(공)</th>
                  <th style="font-size:8pt;">등록세신고<br>납부대행(공)</th>
                  <th style="font-size:8pt;">교통비<br>(공)</th>
                  <th style="font-size:8pt;">제증명<br>(공)</th>
                  <th style="font-size:8pt;">열람증지대<br>(우리공)</th>
                  <th style="font-size:8pt;">등초본발급<br>(공)</th>
                  <th style="font-size:8pt;">지배연초본<br>발급(하나공)</th>

                </tr>
              </thead>
              <tbody>

	<?
		$T=1;
			while($rowb = $stmtb->fetch()){
				if($rowb[gubun_code]=="basic"){
					$rowb[gubun_code] = "기본";
				}else{
					$rowb[gubun_code] = "본점";
				}
				?>
                <tr class="odd gradeX">
                  <td style="text-align:center;font-size:8pt;"><?=$T?></td>
                  <td style="text-align:center;font-size:8pt;"><?=f_bank_name($rowb[bank_code])?></td>
                  <td style="text-align:center;font-size:8pt;"><?=f_jijum_name($rowb[jijum_code])?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$rowb[gubun_code]?></td>
                  <td style="text-align:center;font-size:8pt;"><?if($rowb[b_basic_bosu]!=0){?><?=f_money($rowb[b_basic_bosu])?>원<?}?></td>
                  <td style="text-align:center;font-size:8pt;"><?if($rowb[b_halin]!=0){?><?=f_money($rowb[b_halin])?>%<?}?></td>
                  <td style="text-align:center;font-size:8pt;"><?if($rowb[b_singonabbu]!=0){?><?=f_money($rowb[b_singonabbu])?>원<?}?></td>
                  <td style="text-align:center;font-size:8pt;"><?if($rowb[b_kyotong]!=0){?><?=f_money($rowb[b_kyotong])?>원<?}?></td>
                  <td style="text-align:center;font-size:8pt;"><?if($rowb[b_woninjungseo]!=0){?><?=f_money($rowb[b_woninjungseo])?>원<?}?></td>

                  <td style="text-align:center;font-size:8pt;"><?if($rowb[g_jungjidae]!=0){?><?=f_money($rowb[g_jungjidae])?>원<?}?></td>
                  <td style="text-align:center;font-size:8pt;"><?if($rowb[g_singonabbu]!=0){?><?=f_money($rowb[g_singonabbu])?>원<?}?></td>
                  <td style="text-align:center;font-size:8pt;"><?if($rowb[g_kyotong]!=0){?><?=f_money($rowb[g_kyotong])?>원<?}?></td>
                  <td style="text-align:center;font-size:8pt;"><?if($rowb[g_jejungmyung]!=0){?><?=f_money($rowb[g_jejungmyung])?>원<?}?></td>
                  <td style="text-align:center;font-size:8pt;"><?if($rowb[g_yeolamjunggi]!=0){?><?=f_money($rowb[g_yeolamjunggi])?>원<?}?></td>
                  <td style="text-align:center;font-size:8pt;"><?if($rowb[g_deungchobon]!=0){?><?=f_money($rowb[g_deungchobon])?>원<?}?></td>
                  <td style="text-align:center;font-size:8pt;"><?if($rowb[g_jibaeinchobon]!=0){?><?=f_money($rowb[g_jibaeinchobon])?>원<?}?></td>
                </tr>

	<?$T++;}?>



              </tbody>
            </table>
          </div>
        </div>

				<div style="text-align:center;" >
					<button type="button" class="btn btn-success" onclick="javascript:window.close();">닫기</button>
				</div>


		</div>

      </div>
    </div>
  </div>
</div>



<script src="/js/common.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script>
function f_list_print(){
	var frm    = document.ff;
	var url    ="popup_list.php";
	var title  = "listpop";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=2500, height=800, top=0,left=20"; 
	window.open("", title,status);
	frm.target = title;
	frm.action = url;
	frm.method = "post";
	frm.submit();
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
