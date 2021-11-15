<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	//현금-이전/세금-설정/카드-<strong></strong> 발행일

	$h_idx				=	trim($_REQUEST[h_idx]);
	$s_date				=	trim($_REQUEST[s_date]);
	$e_date				=	trim($_REQUEST[e_date]);

	if($s_date=="") $s_date = date("Ymd");
	if($e_date=="") $e_date = date("Ymd");


	$view_num		=	trim($_REQUEST[view_num]);	//한라인에 몇개를 출력할건지//
	if($_REQUEST[page]==""){$page=1;}else{$page=$_REQUEST[page];}
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	100;					//리스트 갯수



	//현금영수증 발행일
	//세금계산서 발행일
	//카드승인일?????
	if($h_idx=="") {
		$sql1 = "select hy_b_date as suim,a1,h_idx,'ijun' as gubun,idx from  tbl_junib where hy_b_date between {$s_date} and {$e_date}";
		$sql2 = "select sg_b_date as suim,a1,h_idx,'suljung' as gubun,idx from tbl_suljung where sg_b_date between {$s_date} and {$e_date}";
		$sql3 = "select b.sg_b_date as suim,b.a1,b.h_idx,'suljung' as gubun,b.idx from tbl_junib j cross join tbl_suljung b on j.a1=b.a1 where 1 = 1 and b.idx in (SELECT s.idx FROM `tbl_suljung` s cross join tbl_sugum t on s.a1=t.a1 WHERE s.suljung_no=t.suljung_no and (length(t.confirm_date)>0) and (confirm_date between {$s_date} and {$e_date}) group by s.idx )";
		//$sql3 = "select sg_b_date as suim,a1,h_idx,'card' as gubun,idx from tbl_suljung where sg_b_date between {$s_date} and {$e_date}";
	} else {
		$sql1 = "select hy_b_date as suim,a1,h_idx,'ijun' as gubun,idx from  tbl_junib where h_idx = '{$h_idx}' and hy_b_date between {$s_date} and {$e_date}";
		$sql2 = "select sg_b_date as suim,a1,h_idx,'suljung' as gubun,idx from tbl_suljung where h_idx = '{$h_idx}' and sg_b_date between {$s_date} and {$e_date}";
		$sql3 = "select b.sg_b_date as suim,b.a1,b.h_idx,'suljung' as gubun,b.idx from tbl_junib j cross join tbl_suljung b on j.a1=b.a1 where 1 = 1 and j.h_idx = '{$h_idx}' and b.idx in (SELECT s.idx FROM `tbl_suljung` s cross join tbl_sugum t on s.a1=t.a1 WHERE s.suljung_no=t.suljung_no and (length(t.confirm_date)>0) and (confirm_date between {$s_date} and {$e_date}) group by s.idx )";
	}

	$sql = "select kk.suim,kk.a1,kk.h_idx,kk.gubun,kk.idx from (";
	$sql.= "{$sql1} union all {$sql2} union all {$sql3}  )  kk order by kk.suim desc";

	//echo "<br><br><br>".$sql."<br><br><br>";
	$stmt1 = $pdo->prepare($sql);
	$stmt1->execute();

	$rows_total = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

?>

<!DOCTYPE html>
<html lang="kr">

<head>
<title>재무돌이</title>
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



<body style="overflow:auto; width:1880px;">

<!--header 시작-->
	<?include ("../include/header.php");?>
<!--header 종료-->


<!--top-메뉴시작-->
	<?include ("../include/header_menu.php");?>
<!--top-메뉴종료-->


<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">데이타관리</a> <a href="#" class="current">사건부</a> </div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">

			<div  style="width:100%;background-color:#EFEFEF;border-top:1px solid #e7e7e7;border-left:1px solid #e7e7e7;border-right:1px solid #e7e7e7;">
				<form name=ffx method=post>
				<input type=hidden name="view_num"  id="view_num"  value="<?=$view_num?>">
				<input type=hidden name="page"  id="page"  value="<?=$page?>">
				<table style="background-color:#EFEFEF;">
				  <thead>
					<tr>
					  <th style="text-align:left;margin-top:10px;">
						<div style="margin-left:520px;margin-bottom:5px;margin-top:5px;">
						<button type="button" class="btn btn-success" onclick="javascript:f_date0();"  style="background-color:#F29661;font-size:8pt;height:26px;">당일</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date1();"  style="background-color:#F29661;font-size:8pt;height:26px;">3일</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date2();"  style="background-color:#F29661;font-size:8pt;height:26px;">1주일</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date3();"  style="background-color:#F29661;font-size:8pt;height:26px;">2주일</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date4();"  style="background-color:#F29661;font-size:8pt;height:26px;">1개월</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date5();"  style="background-color:#F29661;font-size:8pt;height:26px;">3개월</button>
						</div>
						  &nbsp;&nbsp;&nbsp;현 장 명&nbsp;&nbsp;&nbsp;<?=f_hyunjang_select("h_idx",$h_idx," style='width:281px;'")?>&nbsp;&nbsp;

						  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(현금/세금/카드) 발행일&nbsp;<input type=text name="s_date" id="s_date" value="<?=$s_date?>"  class="datepickx" size=8 maxlength=8 style="width:80px;height:20px;">&nbsp;~&nbsp;<input type=text name="e_date" id="e_date" value="<?=$e_date?>"  class="datepickx" size=8 maxlength=8 style="width:80px;height:20px;">&nbsp;&nbsp;
					  </th>
					  <th>
						&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-success" onclick="javascript:f_submitx();" style="background-color:#4374D9;height:60px;width:60px;margin-top:25px;">조회</button>
					  </th>
					</tr>
				  </thead>
				</table>
			</div>
			<table class="table table-bordered table-striped top_box" style="border-right:1px solid #e7e7e7;">
			  <thead>
				<tr>
				  <th style="text-align:left;border-right:0px;"><b style="color:black;font-weight:bold;"><?if($h_idx!=""){?>현장명: <?=f_hyunjang_name($h_idx)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
				  <?if(($target_date!="")&&($s_date!="")&&($e_date!="")){?>세금계산서발행일 : <?=f_date($s_date)?>~ <?=f_date($e_date)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
				  <?if(($target_date2!="")&&($s_date2!="")&&($e_date2!="")){?><?=f_cate($target_date2)?>: <?=f_date($s_date2)?>~ <?=f_date($e_date2)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
				  총 <?=f_money0($rows_total)?>건</b>
				  </th>
				  <th style="text-align:right;border-left:0px;">
					<button type="button" class="btn btn-success" onclick="javascript:f_excel();"  style="background-color:#F29661;">엑셀다운로드</button>&nbsp;&nbsp;&nbsp;&nbsp;
				  </th>
				</tr>
			  </thead>
			</table>
			</form>
		</div>

	<form name=ffm method="post">

        <div class="widget-box">
          <div class="widget-content nopadding">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>수임년월일</th>

                  <th>건명</th>
                  <th>보수액</th>

                  <th>위임인의 성명/주소</th>
                  <th>위임인의 확인</th>

                  <th>종결일자</th>
                  <th>비고</th>

                  <th>발행일</th>
                  <th>현장명</th>
                </tr>

              </thead>
              <tbody>

	<?
	$Link_Value = "?list_num={$view_num}&h_idx=$h_idx&s_date=$s_date&e_date=$e_date";
	$Page_link = _Make_Link($rows_total,$view_num,$Page_List,$page,$Link_Value,$img_pp,$img_p,$img_nn,$img_n);

	$sql = "select kk.suim,kk.a1,kk.h_idx,kk.gubun,kk.idx from (";
	$sql.= "{$sql1} union all {$sql2} )  kk order by kk.suim desc limit $Page_link[start],$view_num";
	//echo "<br><br><br>".$sql."<br><br><br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	if($rows > 0){
		if($_REQUEST[page]==""){$page=1;}else{$page=$_REQUEST[page];}
		$T=1;
		while($row = $stmt->fetch()){
			if($row[gubun]=="ijun"){
				$row[gubun]="소유권이전";
			}else{
				$row[gubun]="근저당설정";
			}
			?>


				<input type=hidden name="idx_<?=$i?>" value="<?=$row[idx]?>">


                <tr class="odd gradeX">
                  <td style="text-align:center;"><?=($page-1)*$view_num+$T?></td>
                  <td style="text-align:center;"><?=f_date($row[suim])?></td>


                  <td style="text-align:center;"><?=$row[gubun]?></td>
<?
	//이전정보
	$sql = "select * from tbl_junib where a1='{$row[a1]}' and h_idx={$row{h_idx}} limit 1";
	//echo $sql;
	$ii = db_query_fetch($sql);
?>

<?if($row[gubun]=="근저당설정"){?>	
		  <?$suljung_bosu=f_suljung_bosu($row[idx],"");?>
		<?$sql = "select * from tbl_suljung where idx={$row[idx]} limit 1";
		$sj = db_query_fetch($sql);

		$sql = "select * from tbl_bank_jijum where jijum_code='{$sj[jijum_code]}' limit 1";
		$jj = db_query_fetch($sql);

		$sql = "select * from tbl_bank_info where bank_code='{$jj[bank_code]}' limit 1";
		$jk = db_query_fetch($sql);
		
		?>
                  <td style="text-align:center;"><?=f_money($suljung_bosu)?></td>

                  <td style="text-align:center;"><?=$ii[j1]?>/<?=$ii[l1]?>
				  <br>
				  <?=$jk[bank_name]?>/<?=$jj[jijum_name]?>/<?=$jj[ceo]?>/<?=$jj[addr]?>
				  
				  </td>

                  <td style="text-align:center;"><?=f_jumin_valid($ii[k1])?><br><?=$jj[trade_code]?></td>

                  <td style="text-align:center;"><?=f_date($row[suim])?></td>
                  <td style="text-align:center;"><?=$row[memo]?></td>
                  <td style="text-align:center;"><?=f_date($row[suim])?></td>
                   <td style="text-align:center;"><?=f_hyunjang_name($row[h_idx])?></td>

<?}else{//이전?>
		<?$ijun_bosu=f_jung($ii[aq1])+f_jung($ii[ar1])+f_jung($ii[as1])+f_jung($ii[at1]);?>
		<?$sql = "select * from tbl_junib where idx={$row[idx]} limit 1";
		$ij = db_query_fetch($sql);

		$sql = "select * from tbl_hyunjang_danji_info where h_idx={$ij[h_idx]} and danji_name='{$ij[b1]}'";
		$dj = db_query_fetch($sql);
		?>

                  <td style="text-align:center;"><?=f_money($ijun_bosu)?></td>

                  <td style="text-align:center;">
				  <?=$dj[d_com_name]?> / <?=$dj[d_name]?> / <?=$dj[d_addr]?><br>
				  <?=$ii[j1]?>/<?=$ii[l1]?>

				  </td>
                  <td style="text-align:center;"><?=$dj[d_bubin_no]?><br><?=f_jumin_valid($ij[k1])?></td>
                  <td style="text-align:center;"><?=f_date($row[suim])?></td>

                  <td style="text-align:center;"><?=$row[memo]?></td>
                  <td style="text-align:center;"><?=f_date($row[suim])?></td>
                   <td style="text-align:center;"><?=f_hyunjang_name($row[h_idx])?></td>

<?}?>


                </tr>


	<?$T++;$i++;}
}else{?>
              <tr class="title">
                <td colspan=4 align=center>내용이 없습니다.</td>
              </tr>
<?}?>


              </tbody>
            </table>
          </div>
        </div>


		<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
			<div class="dataTables_filter" id="DataTables_Table_0_filter"><label  style="float:left;margin-left:20px;">페이지당 </label>&nbsp;<select name="per_page" style="width:80px;">
					<option selected="selected" value="10" <?if($list_num==10){?>selected<?}?>>10</option>
					<option value="15" <?if($list_num==15){?>selected<?}?>>15</option>
					<option value="30" <?if($list_num==30){?>selected<?}?>>30</option>
					<option value="50" <?if(($list_num==50)||($list_num=="")){?>selected<?}?>>50</option>
					<option value="100" <?if($list_num==100){?>selected<?}?>>100</option>
					<option value="200" <?if($list_num==200){?>selected<?}?>>200</option>
					<option value="300" <?if($list_num==300){?>selected<?}?>>300</option>
				</select>
			</div>
			<?include $_SERVER["DOCUMENT_ROOT"]."/include/paging.php";?>
			<!--
			<div style="float:right;margin-top:-25px;margin-bottom:10px;margin-right:10px;">
				<button type="button" class="btn btn-success" onclick="javascript:f_submit();">저장</button>
			</div>
			//-->
		</div>

		</form>

      </div>
    </div>
  </div>
</div>

<!--bottom-시작-->
	<?include ("../include/bottom.php");?>
<!--bottom-종료-->


<script src="/js/common.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script>

function f_excel(){
	var frm    = document.ffx;
	frm.action = "accident_excel.php";
	frm.method = "post";
	frm.submit();
	frm.action = "accident.php";
}

function f_submitx(){
	document.ffx.submit();
}
function f_submit(){
	var v = document.ff;
	if(confirm("저장하시겠습니까?")){
		v.action = "post.php";
		v.submit();
	}
}

$(document).ready(function(){

	$("#ijp_s_date_ch").click(function(e){ 
		if($(this).is(":checked")){
			//alert(1);
			$(".ijp_s_date1").prop("checked", true);
		}else{
			//alert(0);
			$(".ijp_s_date1").prop("checked", false);
		}
	});
	$("#ijp_s_memo_ch").click(function(e){ 
		if($(this).is(":checked")){
			$(".ijp_s_memo1").prop("checked", true);
		}else{
			$(".ijp_s_memo1").prop("checked", false);
		}
	});
});

</script>

</body>
</html>
