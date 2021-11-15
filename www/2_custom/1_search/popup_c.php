<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);


	$h_idx				=	trim($_REQUEST[h_idx]);
	$target_date		=	trim($_REQUEST[target_date]);
	$s_date				=	trim($_REQUEST[s_date]);
	$e_date				=	trim($_REQUEST[e_date]);

	$target_date2		=	trim($_REQUEST[target_date2]);
	$s_date2			=	trim($_REQUEST[s_date2]);
	$e_date2			=	trim($_REQUEST[e_date2]);
	$bank_code			=	trim($_REQUEST[bank_code]);
	$jijum_code			=	trim($_REQUEST[jijum_code]);

	$bank_null_ch	=	trim($_REQUEST[bank_null_ch]);
	$kikan2_null_ch		=	trim($_REQUEST[kikan2_null_ch]);
	$h1					=	trim($_REQUEST[h1]);
	$i1					=	trim($_REQUEST[i1]);
	$j1					=	trim($_REQUEST[j1]);
	$memo				=	trim($_REQUEST[memo]);

	if($target_date=="") $target_date="1";
	if($s_date=="")		$s_date=date("Ymd");
	if($e_date=="")		$e_date=date("Ymd");

	$view_num		=	trim($_REQUEST[view_num]);	//한라인에 몇개를 출력할건지//
	if($_REQUEST[page]==""){$page=1;}else{$page=$_REQUEST[page];}
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	20;					//리스트 갯수


	$wherequery = " where 1=1  ";

	if($h_idx!="")			$wherequery.= " and i.h_idx = ".$h_idx." ";
	if($bank_null_ch=="Y")	$wherequery.= " and i.d1 <> '' ";
	if($bank_code!="")		$wherequery.= " and i.d1 = '".$bank_code."' ";
	if($jijum_code!="")		$wherequery.= " and i.e1 = '".$jijum_code."' ";
	if($h1!="")				$wherequery.= " and i.h1 = '".$h1."' ";
	if($i1!="")				$wherequery.= " and i.i1 = '".$i1."' ";
	if($j1!="")				$wherequery.= " and (i.j1 like '%{$j1}%' or i.m1 like '%{$j1}%')";
	
	if($target_date!=""){
		if(($s_date!="")&&($e_date!="")){
			$imsi = "";
			if(($target_date=="sjp_s_date")||($target_date=="sjp_j_date")||($target_date=="sjj_w_date")||($target_date=="sjp_b_date")){  //설정일때
				$imsi = " and s.{$target_date} between {$s_date} and {$e_date} and i.a1 in (select a1 from tbl_suljung where {$target_date} between {$s_date} and {$e_date} )";
			}else{
				if($target_date!="") {$imsi = " and {$target_date} between ";}
				if($s_date==$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
				if($s_date!=$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
			}
			$wherequery.=$imsi;
		}
	}

if($kikan2_null_ch=="Y"){
			if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")){ //설정일때
				$imsi = " and i.a1 in (select a1 from tbl_suljung where  ({$target_date2}='' or {$target_date2} is null ))";
			}else{
				if($target_date2!="") {$imsi = " and ({$target_date2}='' or {$target_date2} is null )";}
			}
			//echo "<br><br>".$imsi;
			$wherequery.=$imsi;
}else{
	if($target_date2!=""){
		if(($s_date2!="")&&($e_date2!="")){
			$imsi = "";
			if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")){ //설정일때
				$imsi = " and s.{$target_date2} between {$s_date2} and {$e_date2} and i.a1 in (select a1 from tbl_suljung where {$target_date2} between {$s_date2} and {$e_date2} )";
			}else{
				if($target_date2!="") {$imsi = " and {$target_date2} between ";}
				if($s_date2==$e_date2) {$imsi.= " {$s_date2} and {$e_date2} ";}
				if($s_date2!=$e_date2) {$imsi.= " {$s_date2} and {$e_date2} ";}
			}
			$wherequery.=$imsi;
		}
	}
}

	if($memo!=""){
		$wherequery.= " and (i.a1 in ( (select a1 from tbl_junib where (memo like '%{$memo}%') or (ijp_s_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_s_memo  like '%{$memo}%') or (ijp_j_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_j_memo  like '%{$memo}%') or (ijj_w_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjj_w_memo  like '%{$memo}%') or (ijp_b_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_b_memo  like '%{$memo}%') or (refund_memo  like '%{$memo}%') ";
		$wherequery.= "      or (refund_end_memo like '%{$memo}%') or (refund_memo  like '%{$memo}%')))  or ";
		$wherequery.= "      i.a1 in (select a1 from tbl_sugum where sugum_memo like '%{$memo}%') )";
	}

//	echo "<br><br><br>".$wherequery."<br><br><br>";
	$rows_total = db_count_all(" tbl_junib i cross join tbl_suljung s on i.a1=s.a1  ",$wherequery);

	//print_r($_REQUEST);

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
    <div id="breadcrumb" style="text-align:center;"><h3>필증목록</h3></div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">

		<div class="widget-content nopadding">
		<table class="table table-bordered table-striped top_box" style="border-right:1px solid #e7e7e7;">
		  <thead>
			<tr>
			  <th style="text-align:left;border-right:0px;"><b style="color:black;font-weight:bold;">
			  <?if($h_idx!=""){?>현장명: <?=f_hyunjang_name($h_idx)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
			  <?if($bank_code!=""){?><?=f_bank_name($bank_code)?>&nbsp;<?}?>
			  <?if($jijum_code!=""){?><?=f_jijum_name($jijum_code)?>&nbsp;<?}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  총 <?=f_money($rows_total)?>건</th>
			  <th style="text-align:right;border-left:0px;">
				<button type="button" class="btn btn-success" onclick="javascript:f_excel();"  style="background-color:#F29661;">엑셀다운로드</button>&nbsp;&nbsp;&nbsp;&nbsp;
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
                  <th>은행명</th>
                  <th>지점명</th>
                  <th>동</th>
                  <th>호</th>
                  <th>채무자</th>
                  <th>주민번호</th>
                  <th>채권최고액</th>
                  <th style="background-color:#70AD47;color:white;">등기접수일</th>
                </tr>
              </thead>
              <tbody>

	<?
	$Link_Value = "?list_num={$view_num}&s_gubun=$s_gubun&s_search=$s_search";
	$Page_link = _Make_Link($rows_total,$view_num,$Page_List,$page,$Link_Value,$img_pp,$img_p,$img_nn,$img_n);


	$sql = "select i.g1,i.g1,i.e1,i.h1,i.i1,i.j1,i.k1,i.m1,i.aw1,i.aw1_jumin,i.aw2,i.aw2_jumin,i.aw3,i.aw3_jumin,i.aw4,i.aw4_jumin,s.suljung_no,s.sjp_s_date,s.chaekwon_max,s.bank_code,s.jijum_code from tbl_junib i cross join tbl_suljung s on i.a1=s.a1  $wherequery order by   cast(i.h1 as unsigned) asc,cast(i.i1 as unsigned) asc limit $Page_link[start],$view_num";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	if($rows > 0){
		if($page) $page=1;
		$T = 1;
			while($row = $stmt->fetch()){?>

                <tr class="odd gradeX">
                  <td style="text-align:center;"><?=($page-1)*$view_num+$T?></td>
                  <td style="text-align:center;"><?=f_bank_name($row[bank_code])?></td>
                  <td style="text-align:center;"><?=f_jijum_name($row[jijum_code])?></td>

                  <td style="text-align:center;"><?=$row[h1]?></td>
                  <td style="text-align:center;"><?=$row[i1]?></td>

                  <td style="text-align:center;"><?=$row["aw".$row[suljung_no]]?></td>
                  <td style="text-align:center;"><?=f_jumin_valid($row["aw".$row[suljung_no]."_jumin"])?></td>

                  <td style="text-align:center;"><?=f_money($row[chaekwon_max])?></td>
                  <td style="text-align:center;"><?=f_date($row[g1])?></td>

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
	<input type=hidden name="h_idx" value="<?=$h_idx?>">
	<input type=hidden name="target_date" value="<?=$target_date?>">
	<input type=hidden name="s_date" value="<?=$s_date?>">
	<input type=hidden name="e_date" value="<?=$e_date?>">
	<input type=hidden name="target_date2" value="<?=$target_date2?>">
	<input type=hidden name="s_date2" value="<?=$s_date2?>">
	<input type=hidden name="e_date2" value="<?=$e_date2?>">
	<input type=hidden name="bank_code" value="<?=$bank_code?>">
	<input type=hidden name="jijum_code" value="<?=$jijum_code?>">
	<input type=hidden name="h1" value="<?=$h1?>">
	<input type=hidden name="i1" value="<?=$i1?>">
	<input type=hidden name="j1" value="<?=$j1?>">
	<input type=hidden name="memo" value="<?=$memo?>">
</form>

<script src="/js/common.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script>
function f_excel(){
	var frm    = document.ffx;
	frm.action = "excel_down_c.php";
	frm.method = "post";
	frm.submit();
}

function f_form_print(){
	var frm    = document.ffx;
	if($("#s_date").val()==""){
		alert("확인일자를 입력해주세요.");
		$("#s_date").focus();
	}else{
		frm.confirm_date.value = $("#s_date").val();
		var url    ="/report_form/popup_form_c/index.html";
		var title  = "listpop11";
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
	document.ff.submit();
}
</script>

</body>
</html>
