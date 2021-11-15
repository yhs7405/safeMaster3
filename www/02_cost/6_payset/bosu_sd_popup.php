<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib a left outer join tbl_bosu_cost_manual b on a.a1 = b.a1";


	$a1				=	trim($_REQUEST[a1]);
	$h_idx				=	trim($_REQUEST[h_idx]);


	$h1					=	trim($_REQUEST[h1]);
	$i1					=	trim($_REQUEST[i1]);
	$j1					=	trim($_REQUEST[j1]);
	$job_id		=	trim($_REQUEST[job_id]);
	$job_date		=	trim($_REQUEST[job_date]);

	if($target_date=="") $target_date="doc_receive_date";
	if($s_date=="")		$s_date=date("Ymd");
	if($e_date=="")		$e_date=date("Ymd");

	$view_num		=	trim($_REQUEST[view_num]);	//한라인에 몇개를 출력할건지//
	if($_REQUEST[page]==""){$page=1;}else{$page=$_REQUEST[page];}
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	100;					//리스트 갯수


	$wherequery = " where 1=1  ";

	if($h_idx!=""){
		$wherequery.= " and a.h_idx = ".$h_idx." ";
	}	else {
		//$wherequery.= " and a.h_idx = ' '";
	}
	if($a1!=""){
		$wherequery.= " and a.a1 = '".$a1."' ";
	}	else {
		$board_dbname	=	"tbl_junib a , tbl_bosu_cost_manual b ";
		$wherequery.= " and a.a1 = b.a1 ";
		//$wherequery.= " and a.h_idx = ' '";
	}

	if($h1!="")				$wherequery.= " and a.h1 = '".$h1."' ";
	if($i1!="")				$wherequery.= " and a.i1 = '".$i1."' ";
	if($j1!="")				$wherequery.= " and (a.j1 like '%{$j1}%' or a.m1 like '%{$j1}%')";
	if($job_id!="")				$wherequery.= " and b.job_id = '".$job_id."' ";
	if($job_date!="")				$wherequery.= " and b.job_date = '".$job_date."' ";
	
	

	//echo $wherequery;
	$rows_total = db_count_all($board_dbname,$wherequery);

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


<body style="overflow:auto; width:1400px;">



<div id="content">

  <div id="content-header">
    <div id="breadcrumb" style="text-align:center;"><h3>보수료 수동입력</h3></div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">

			<form name=ffx  method=post>
			<div  style="width:100%;background-color:#EFEFEF;border-top:1px solid #e7e7e7;border-left:1px solid #e7e7e7;border-right:1px solid #e7e7e7;">
				<input type=hidden name="view_num"  id="view_num"  value="<?=$view_num?>">
				<input type=hidden name="page"  id="page"  value="<?=$page?>">
				<?if($a1==""){?>
				<table class="table table-bordered table-striped top_box" style="background-color:#EFEFEF;">
				  <thead>
					<tr style="vertical-align: middle;">
					  <th style="text-align:left;border-right:0px;">
						  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;동&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=text name="h1" value="<?=$h1?>" style="width:80px;">
						  &nbsp;&nbsp;&nbsp;호&nbsp;&nbsp;&nbsp;<input type=text name="i1" value="<?=$i1?>" style="width:80px;">
						  &nbsp;&nbsp;&nbsp;취득자&nbsp;&nbsp;&nbsp;<input type=text name="j1" value="<?=$j1?>" style="width:147px;">
					  </th>
				  <th style="text-align:right;border-left:0px;">
						&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-success" onclick="javascript:f_submitx();" style="background-color:#4374D9;height:30px;width:60px;margin-top:5px;">조회</button>
					  </th>
					</tr>
				  </thead>
				</table>
				<?}?>
			</div>
			<table class="table table-bordered table-striped top_box" style="border-right:1px solid #e7e7e7;">
			  <thead>
				<tr style="height:40px;vertical-align: middle;">
				  <th style="text-align:left;border-right:0px;"><b style="color:black;font-weight:bold;"><?if($h_idx!=""){?>현장명: <?=f_hyunjang_name($h_idx)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
				  총 <?=f_money0($rows_total)?>건</b>
				  </th>
				</tr>
			  </thead>
			</table>
			</form>
		</div>


	<form name=ffm method="post">
		<input type=hidden name="list_num"  value="<?=$view_num?>">
		<input type=hidden name="h_idx" value="<?=$h_idx?>">

        <div class="widget-box">
          <div class="widget-content nopadding">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
         					<?if($a1==""){?>
								  <th><input type="checkbox" id="chx"></th>
								  <?}?>
                  <th style="font-size:8pt;">No</th>
                  <th style="font-size:8pt;">동</th>
                  <th style="font-size:8pt;">호</th>
                  <th style="font-size:8pt;">취득자</th>
                  <th style="font-size:8pt;">전화번호1</th>
                  <th style="font-size:8pt;">회원종류</th>

                  <th style="font-size:8pt;">이전보수료(수동)</th>
                  <th style="font-size:8pt;">이전보수료 부가세(수동)</th>
                  <th style="font-size:8pt;">제증명(수동)</th>
                  <th style="font-size:8pt;">신탁보수료(수동)</th>
                  <th style="font-size:8pt;">신탁보수료 부가세(수동)</th>
                  <th style="font-size:8pt;">입력일</th>
                  <th style="font-size:8pt;">입력자</th>
                </tr>

              </thead>
              <tbody>

	<?
	$Link_Value = "?list_num={$view_num}&s_gubun=$s_gubun&s_search=$s_search";
	$Page_link = _Make_Link($rows_total,$view_num,$Page_List,$page,$Link_Value,$img_pp,$img_p,$img_nn,$img_n);

	$sql = "select *, if(a.m1='',a.j1,CONCAT(a.j1, ',', a.m1)) as jm1, a.a1 as a2 from $board_dbname  $wherequery order by  cast(a.a1 as unsigned) asc limit $Page_link[start],$view_num";
//	$sql = "select * from $board_dbname  $wherequery order by  cast(h1 as unsigned) asc,cast(i1 as unsigned) asc limit $Page_link[start],$view_num";
//	echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	if($rows > 0){
		$T=1;$i=1;
			while($row = $stmt->fetch()){?>


				<input type=hidden name="idx_<?=$i?>" value="<?=$row[idx]?>">
				<input type=hidden name="a1_<?=$i?>" value="<?=$row[a2]?>">


                <tr class="odd gradeX">
         					<?if($a1==""){?>
                  <td style="text-align:center;font-size:8pt;"><input type="checkbox" name="ch[]" class="ch" value="<?=$row[idx]?>"></td>
                  <?}?>
                	<td style="text-align:center;font-size:8pt;"><?=($page-1)*$view_num+$T?></td>
                	<td style="text-align:center;font-size:8pt;"><?=$row[h1]?></td>
                	<td style="text-align:center;font-size:8pt;"><?=$row[i1]?></td>
                	<td style="text-align:center;font-size:8pt;"><a href="javascript:f_popup_s('<?=$row[a2]?>');"  style="text-decoration:underline;color:red;"><?=$row[jm1]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$row[p1]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=f_member_value($row[a2])?></td>

                	<td style="text-align:center;font-size:8pt;">금 <input type=text class="ijeon_bosu_cost_xx"  name="ijeon_bosu_cost_<?=$i?>" value="<?=f_money($row[ijeon_bosu_cost])?>" style="width:70px;height:15px;font-size:8pt;text-align:right;" onkeydown="nextFocus(ijeon_bosu_cost_<?=$i+1?>)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;font-size:8pt;">금 <input type=text class="ijeon_bosu_vat_xx"  name="ijeon_bosu_vat_<?=$i?>" value="<?=f_money($row[ijeon_bosu_vat])?>" style="width:70px;height:15px;font-size:8pt;text-align:right;" onkeydown="nextFocus(ijeon_bosu_vat_<?=$i+1?>)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;font-size:8pt;">금 <input type=text class="proof_cost_xx"  name="proof_cost_<?=$i?>" value="<?=f_money($row[proof_cost])?>" style="width:70px;height:15px;font-size:8pt;text-align:right;" onkeydown="nextFocus(proof_cost_<?=$i+1?>)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;font-size:8pt;">금 <input type=text class="sintak_bosu_cost_xx"  name="sintak_bosu_cost_<?=$i?>" value="<?=f_money($row[sintak_bosu_cost])?>" style="width:70px;height:15px;font-size:8pt;text-align:right;" onkeydown="nextFocus(sintak_bosu_cost_<?=$i+1?>)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                	<td style="text-align:center;font-size:8pt;">금 <input type=text class="sintak_bosu_vat_xx"  name="sintak_bosu_vat_<?=$i?>" value="<?=f_money($row[sintak_bosu_vat])?>" style="width:70px;height:15px;font-size:8pt;text-align:right;" onkeydown="nextFocus(sintak_bosu_vat_<?=$i+1?>)" onkeyup="onlyNum(this);this.value=this.value.comma();"> 원</td>
                  <td style="text-align:center;font-size:8pt;"><?=f_date2($row[job_date])?></td>
	               	<td style="text-align:center;font-size:8pt;"><?=f_id_value($row[job_id])?></td>
                </tr>

	<?$T++;$i++;}
}else{?>
              <tr class="title">
                <td colspan=14 align=center>내용이 없습니다.</td>
              </tr>
<?}?>


              </tbody>
            </table>
          </div>
        </div>


		<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix" style="height:30px;">
			<div class="dataTables_filter" id="DataTables_Table_0_filter">
			<?if($a1==""){?>
				<label  style="float:left;">페이지당 </label>&nbsp;<select name="view_num" style="width:80px;" onchange="javascript:f_movex(this);">
					<option selected="selected" value="10" <?if($view_num==10){?>selected<?}?>>10</option>
					<option value="15" <?if($view_num==15){?>selected<?}?>>15</option>
					<option value="20" <?if($view_num==20){?>selected<?}?>>20</option>
					<option value="30" <?if($view_num==30){?>selected<?}?>>30</option>
					<option value="30" <?if($view_num==50){?>selected<?}?>>50</option>
					<option value="100" <?if($view_num==100){?>selected<?}?>>100</option>
					<option value="200" <?if($view_num==200){?>selected<?}?>>200</option>
					<option value="300" <?if($view_num==300){?>selected<?}?>>300</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?}?>
				<?if($a1==""){?>
					<?if($_SESSION["admin_permission"][ch_da2]=="y"){?>
							<?	if($rows > 0){  ?>
									<div style="float:right;margin-top:0px;margin-bottom:10px;margin-right:10px;">
										<button type="button" class="btn btn-success" onclick="javascript:f_delete();">삭제</button>
									</div>
							<?	} else {  ?>
									<div style="float:right;margin-top:0px;margin-bottom:10px;margin-right:10px;">
										<button type="button" class="btn btn-success" onclick="javascript:f_delete();">삭제</button>
									</div>
							<?	}  ?>
					<?}?>
				<?}?>

			</div>
			<?if($a1==""){?>
			<?include $_SERVER["DOCUMENT_ROOT"]."/include/paging.php";?>
			<?}?>
			
<?if($_SESSION["admin_permission"][ch_da2]=="y"){?>
		<?	if($rows > 0){  ?>
		<?if($a1==""){?>
				<div style="float:right;margin-top:-25px;margin-bottom:10px;margin-right:10px;">
		<?}else{?>
				<div style="float:right;margin-top:0px;margin-bottom:10px;margin-right:10px;">
		<?}?>
					<button type="button" class="btn btn-success" onclick="javascript:f_submit();">저장</button>
				</div>
		<?	} else {  ?>
				<div style="float:right;margin-top:0px;margin-bottom:10px;margin-right:10px;">
					<button type="button" class="btn btn-success" onclick="javascript:f_submit();">저장</button>
				</div>
		<?	}  ?>
<?}?>
		</div>

		</form>

    </div>
  </div>
</div>

<!--bottom-시작-->
	<?include ("../../include/bottom.php");?>
<!--bottom-종료-->

<script src="/js/common.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script>

function f_popup_s(a1){//설정상세조회
	var url    ="/01_fdata/1_finput/regist.php?a1="+encodeURI(a1);
	var title  = "listpops";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1890, height=950, top=0,left=20"; 
	var a = window.open(url, title,status);
	a.focus();
}

function f_popup_g(a1){//기본상세조회
	var url    ="/2_custom/1_search/popup_g.php?a1="+encodeURI(a1);
	var title  = "listpops";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1400, height=840, top=0,left=20"; 
	var a = window.open(url, title,status);
	a.focus();
}

function f_chogi(){  //초기화
	if(confirm("초기화 하시겠습니까?")){

			$("#woori_sand_date_ch").prop("checked", false);

			$(".woori_sand_date").prop("checked", false);

			$(".woori_sand_date_xx").val("");
			
	}
}


function apply(){  //적용
			//alert($("#apply_date").val());
	for(i=1;i<=<?=$view_num?>;i++){
		if($("#apply_date").val()!=""){  //날짜일때
			//alert($("#apply_date").val());
			if($("input:checkbox[name='woori_sand_date1_"+i+"']").is(":checked")==true){
				$("input:text[name='woori_sand_date_"+i+"']").val($("#apply_date").val());
			}

		}

	}

}


$(document).ready(function(){
	$("#woori_sand_date_ch").click(function(e){ 
		if($(this).is(":checked")){
			//alert(1);
			$(".woori_sand_date").prop("checked", true);
		}else{
			//alert(0);
			$(".woori_sand_date").prop("checked", false);
		}
	});


});

function f_submitx(){
	if(document.ffx.h_idx.value==""){
		alert("현장은 필수항목입니다.");
		$('#h_idx').focus();
	}	else{
		document.ffx.page.value=1;
		document.ffx.target="_self";
		document.ffx.action = "index.html";
		document.ffx.submit();
	}
}

function f_submit(){
	var v = document.ffm;
	if(confirm("저장하시겠습니까?")){
		var frm    = document.ffm;
		var url    = "bosu_sd_post.php";
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


function f_delete(){
	var v = document.ffm;
	if(confirm("삭제하시겠습니까?")){
		var frm    = document.ffm;
		var url    = "delete_ok.php";
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
