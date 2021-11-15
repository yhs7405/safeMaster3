<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";

	$h_idx			=	trim($_REQUEST[h_idx]);
	$target_date		=	trim($_REQUEST[target_date]);
	$s_date			=	trim($_REQUEST[s_date]);
	$e_date			=	trim($_REQUEST[e_date]);
	$damdang_id		=	trim($_REQUEST[damdang_id]);
	$sou_relation		=	trim($_REQUEST[sou_relation]);
	$bank_code		=	trim($_REQUEST[bank_code]);
	$jijum_code		=	trim($_REQUEST[jijum_code]);
	$bank_null_ch		=	trim($_REQUEST[bank_null_ch]);
	$review_confirm		=	trim($_REQUEST[review_confirm]);
	$daesang		=	trim($_REQUEST[daesang]);
	$j1			=	trim($_REQUEST[j1]);
	$memo			=	trim($_REQUEST[memo]);

	$view_num		=	trim($_REQUEST[view_num]);	//한라인에 몇개를 출력할건지//
	if($_REQUEST[page]==""){$page=1;}else{$page=$_REQUEST[page];}
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	20;					//리스트 갯수

	$wherequery = " where 1=1 ";

	if($h_idx!="")			$wherequery.= " and h_idx = ".$h_idx." ";
	if($damdang_id!="")		$wherequery.= " and damdang_id = '".$damdang_id."' ";
	if($sou_relation!=""){
		if($sou_relation!="000"){ 
			$wherequery.= " and sou_relation = '".$sou_relation."' ";
		} else { //ok전체
			$wherequery.= " and sou_relation in ('100','200','300','400') ";
		}
	}
	if($bank_null_ch=="Y")	$wherequery.= " and d1 <> '' ";
	if($bank_code!="")		$wherequery.= " and d1 = '".$bank_code."' ";
	if($jijum_code!="")		$wherequery.= " and e1 = '".$jijum_code."' ";
	if($review_confirm!=""){
		if($review_confirm!="000"){
			$wherequery.= " and review_confirm = '".$review_confirm."' ";
		} else {  //ok전체
			$wherequery.= " and review_confirm in ('100','200','300','400') ";
		}
	}	
	if($j1!="")				$wherequery.= " and (j1 like '%{$j1}%' or m1 like '%{$j1}%')";
	//$memo				=	trim($_REQUEST[memo]);

	if($daesang=="1") $wherequery.= " and (junib_request_date='' or junib_request_date is null) ";  //전입의뢰일자가 없음
	if($daesang=="2") $wherequery.= " and (junib_s_date='' or junib_s_date is null)";        //전입수령일자가 없음
	if($daesang=="3") $wherequery.= " and (review_request_date='' or review_request_date is null)"; //재열람의뢰일자가 없음
	if($daesang=="4") $wherequery.= " and (review_s_date='' or review_s_date is null)";        //전입수령일자가 없음

	if($target_date!=""){
		if(($s_date!="")&&($e_date!="")){
			$imsi = "";
			if($target_date=="1") {$imsi = " and g1 between ";$tt = "등기접수일";}
			if($target_date=="2") {$imsi = " and junib_request_date between ";$tt = "전입의뢰일";}
			if($target_date=="3") {$imsi = " and junib_s_date between ";$tt = "전입수령일";}
			if($target_date=="4") {$imsi = " and review_request_date between ";$tt = "재열람의뢰일";}
			if($target_date=="5") {$imsi = " and review_s_date between ";$tt = "전입수령일";}
			if($target_date=="6") {$imsi = " and (a1 in ( select a1 from tbl_suljung where sjj_w_date between";$tt = "필증정산일(설정)";}
			if($s_date==$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
			if($s_date!=$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
			if($target_date=="6") {$imsi.= "))";}
			$wherequery.=$imsi;
		}
	}

	if($memo!=""){
		$wherequery.= " and (a1 in ( (select a1 from tbl_junib where (memo like '%{$memo}%') or (ijp_s_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_s_memo  like '%{$memo}%') or (ijp_j_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_j_memo  like '%{$memo}%') or (ijj_w_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjj_w_memo  like '%{$memo}%') or (ijp_b_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_b_memo  like '%{$memo}%') or (refund_memo  like '%{$memo}%') ";
		$wherequery.= "      or (refund_end_memo like '%{$memo}%') or (refund_memo  like '%{$memo}%')))  or ";
		$wherequery.= "      a1 in (select a1 from tbl_sugum where sugum_memo like '%{$memo}%') )";
	}

	//echo "<br>";
	//print_r($_REQUEST);
	//echo "<br>{$wherequery}";

	$rows_total = db_count_all($board_dbname,$wherequery);
?>

<!DOCTYPE html>
<html lang="kr">

<head>
<title>CS돌이</title>
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

<body style="background:white;">

<!--header 시작-->
	<?include ("../include/header_none.php");?>
<!--header 종료-->


<style>
	.top_box th{ text-align:left; }
</style>
<div id="content">

  <div id="content-header">
    <div id="breadcrumb" style="text-align:center;"><h3>전입세대 의뢰 및 수령리스트</h3></div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">

		<div class="widget-content nopadding">
		<table class="table table-bordered table-striped top_box" style="border-right:1px solid #e7e7e7;">
		  <thead>
			<tr>
			  <th style="text-align:left;border-right:0px;"><b style="color:black;font-weight:bold;"><?if($h_idx!=""){?>현장명: <?=f_hyunjang_name($h_idx)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?><?if(($target_date!="")&&($s_date!="")&&($e_date!="")){?><?=$tt?>: <?=f_date($s_date)?>~ <?=f_date($e_date)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?>총 <?=f_money($rows_total)?>건</b></th>
			  <th style="text-align:right;border-left:0px;">
			    확인일자&nbsp;<input type=text name="s_date" id="s_date" value="<?=date("Ymd")?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"><button type="button" class="btn btn-success" onclick="javascript:f_form_print();"  style="background-color:#F29661;">출력</button>&nbsp;&nbsp;&nbsp;&nbsp;
			  작업자 : <?=$_SESSION["admin_name"]?>&nbsp;&nbsp;
			  </th>
			</tr>
		  </thead>
		</table>
		</div>



        <div class="widget-box">
          <div class="widget-content nopadding">

            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>고객고유번호</th>
                  <th>전입의뢰일</th>
                  <th>전입수령일</th>
                  <th>소유주와관계</th>
                  <th style="background-color:#70AD47;color:white;">재열람의뢰일</th>
                  <th style="background-color:#70AD47;color:white;">재열람수령일</th>
                  <th style="background-color:#70AD47;color:white;">재열람확인사항</th>
                  <th>비고</th>
                  <th>담당자(외주)</th>
                </tr>
              </thead>
              <tbody>

	<?
	$Link_Value = "?list_num={$view_num}&s_gubun=$s_gubun&s_search=$s_search";
	$Page_link = _Make_Link($rows_total,$view_num,$Page_List,$page,$Link_Value,$img_pp,$img_p,$img_nn,$img_n);

	$sql = "select * from $board_dbname  $wherequery order by  cast(h1 as unsigned) asc,cast(i1 as unsigned) asc  limit $Page_link[start],$view_num";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	if($rows > 0){
		$T = 1;
			while($row = $stmt->fetch()){?>

                <tr class="odd gradeX">
                  <td style="text-align:center;"><?=($page-1)*$view_num+$T?></td>
                  <td style="text-align:center;"><?=$row[a1]?></td>
                  <td style="text-align:center;"><?=f_date($row[junib_request_date])?></td>
                  <td style="text-align:center;"><?=f_date($row[junib_s_date])?></td>
                  <td style="text-align:center;"><?=f_sou_value($row[sou_relation])?></td>
                  <td style="text-align:center;"><?=f_date($row[review_request_date])?></td>
                  <td style="text-align:center;"><?=f_date($row[review_s_date])?></td>
                  <td style="text-align:center;"><?=f_sou_value($row[review_confirm])?></td>
                  <td style="text-align:center;"><?=$row[memo]?></td>
                  <td style="text-align:center;"><?=f_id_value($row[damdang_id])?></td>
                </tr>

	<?$T++;}
}else{?>
              <tr class="title">
                <td colspan=4 align=center>내용이 없습니다.</td>
              </tr>
<?}?>

              </tbody>
            </table>
          </div>
        </div>

		<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix" style="height:30px;">
			<div class="dataTables_filter" id="DataTables_Table_0_filter"><label  style="float:left;">페이지당 </label>&nbsp;<select name="view_num" style="width:80px;" onchange="javascript:f_movex(this);">
					<option selected="selected" value="10" <?if($view_num==10){?>selected<?}?>>10</option>
					<option value="15" <?if($view_num==15){?>selected<?}?>>15</option>
					<option value="20" <?if($view_num==20){?>selected<?}?>>20</option>
					<option value="30" <?if($view_num==30){?>selected<?}?>>30</option>
					<option value="50" <?if($view_num==50){?>selected<?}?>>50</option>
					<option value="100" <?if($view_num==100){?>selected<?}?>>100</option>
					<option value="200" <?if($view_num==200){?>selected<?}?>>200</option>
					<option value="300" <?if($view_num==300){?>selected<?}?>>300</option>
				</select>
			</div>

			<?include $_SERVER["DOCUMENT_ROOT"]."/include/paging.php";?>
		</div>

      </div>
    </div>
  </div>
</div>

<form name=ffx method=post>
	<input type=hidden name="view_num"  id="view_num"  value="<?=$view_num?>">
	<input type=hidden name="page"  id="page"  value="<?=$page?>">
	<input type=hidden name="confirm_date">	
	<input type=hidden name="bank_null_ch" value="<?=$bank_null_ch?>">
	<input type=hidden name="h_idx" value="<?=$h_idx?>">
	<input type=hidden name="target_date" value="<?=$target_date?>">
	<input type=hidden name="s_date" value="<?=$s_date?>">
	<input type=hidden name="e_date" value="<?=$e_date?>">
	<input type=hidden name="damdang_id" value="<?=$damdang_id?>">
	<input type=hidden name="sou_relation" value="<?=$sou_relation?>">
	<input type=hidden name="bank_code" value="<?=$bank_code?>">
	<input type=hidden name="jijum_code" value="<?=$jijum_code?>">
	<input type=hidden name="review_confirm" value="<?=$review_confirm?>">
	<input type=hidden name="daesang" value="<?=$daesang?>">
	<input type=hidden name="j1" value="<?=$j1?>">
	<input type=hidden name="memo" value="<?=$memo?>">
</form>

<script src="/js/common.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script>

function f_form_print(){
	var frm    = document.ffx;
	if($("#s_date").val()==""){
		alert("확인일자를 입력해주세요.");
		$("#s_date").focus();
	}else{
		frm.confirm_date.value = $("#s_date").val();
		var url    ="/report_form/popup_form_junib_print/index.html";
		var title  = "listpop1";
		var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=800, height=800, top=0,left=20"; 
		var aa = window.open("", title,status);
		frm.target = title;
		frm.action = url;
		frm.method = "post";
		frm.submit();
		aa.focus();
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

	select_detail('bank_code','jijum_code');


function f_submit(){
	document.ffx.page.value=1;
	document.ffx.action = "popup_list.php";
	document.ffx.submit();
}
</script>

</body>
</html>
