<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";

	$h_idx			=	trim($_REQUEST[h_idx]);
	$target_date	=	trim($_REQUEST[target_date]);
	$s_date			=	trim($_REQUEST[s_date]);
	$e_date			=	trim($_REQUEST[e_date]);
	$damdang_id		=	trim($_REQUEST[damdang_id]);
	$sou_relation	=	trim($_REQUEST[sou_relation]);
	$bank_code		=	trim($_REQUEST[bank_code]);
	$jijum_code		=	trim($_REQUEST[jijum_code]);
	
	$bank_null_ch	=	trim($_REQUEST[bank_null_ch]);
	$review_confirm	=	trim($_REQUEST[review_confirm]);
	$daesang		=	trim($_REQUEST[daesang]);
	$j1				=	trim($_REQUEST[j1]);
	$memo			=	trim($_REQUEST[memo]);

	if($target_date=="") $target_date=1;
	if($s_date=="")		$s_date=date("Ymd");
	if($e_date=="")		$e_date=date("Ymd");


	$view_num		=	trim($_REQUEST[view_num]);	//한라인에 몇개를 출력할건지//
	if($_REQUEST[page]==""){$page=1;}else{$page=$_REQUEST[page];}
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	100;				//리스트 갯수

	$wherequery = " where 1=1 ";

	if($h_idx!="")			$wherequery.= " and h_idx = ".$h_idx." ";
	if($damdang_id!="")		$wherequery.= " and damdang_id = '".$damdang_id."' ";
	if($sou_relation!=""){
		if($sou_relation!="000"){
			$wherequery.= " and sou_relation = '".$sou_relation."' ";
		} else {
			$wherequery.= " and sou_relation in ('100','200','300','400') ";
		}
	}	
	if($bank_null_ch=="Y"){
		$wherequery.= " and d1 <> '' ";
	} else {
		if($bank_code!="")		$wherequery.= " and d1 = '".$bank_code."' ";
		if($jijum_code!="")		$wherequery.= " and e1 = '".$jijum_code."' ";
	}
	if($review_confirm!=""){
		if($review_confirm!="000"){
			$wherequery.= " and review_confirm = '".$review_confirm."' ";
		} else {
			$wherequery.= " and review_confirm in ('100','200','300','400') ";
		}
	}	
	if($j1!="")			$wherequery.= " and (j1 like '%{$j1}%' or m1 like '%{$j1}%')";
	//$memo				=	trim($_REQUEST[memo]);

	if($daesang=="1") $wherequery.= " and (junib_request_date='' or junib_request_date is null) ";  //전입의뢰일자가 없음
	if($daesang=="2") $wherequery.= " and (junib_s_date='' or junib_s_date is null)";                //전입수령일자가 없음
	if($daesang=="3") $wherequery.= " and (review_request_date='' or review_request_date is null)"; //재열람의뢰일자가 없음
	if($daesang=="4") $wherequery.= " and (review_s_date='' or review_s_date is null)";             //전입수령일자가 없음

	if($target_date!=""){
		if($target_date=="100") {
			$imsi = " and junib_update in (SELECT max(junib_update) FROM tbl_junib where junib_update<>'' GROUP BY junib_update ORDER BY junib_update DESC)";
			$wherequery.=$imsi;
		}else if(($s_date!="")&&($e_date!="")){
			$imsi = "";
			if($target_date=="1") {$imsi = " and g1 between ";$tt = "접수일";}
			if($target_date=="2") {$imsi = " and junib_request_date between ";$tt = "전입의뢰일";}
			if($target_date=="3") {$imsi = " and junib_s_date between ";$tt = "전입수령일";}
			if($target_date=="4") {$imsi = " and review_request_date between ";$tt = "재열람의뢰일";}
			if($target_date=="5") {$imsi = " and review_s_date between ";$tt = "전입수령일";}
			if($s_date==$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
			if($s_date!=$e_date) {$imsi.= " {$s_date} and {$e_date} ";}
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


<body style="overflow:auto; width:1900px;">

<!--header 시작-->
	<?include ("../include/header.php");?>
<!--header 종료-->


<!--top-메뉴시작-->
	<?include ("../include/header_menu.php");?>
<!--top-메뉴종료-->

<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">전입세대열람</a> <a href="#" class="current">등록</a> </div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">

	
				<form name=ffx method=post>
					<div  style="width:100%;background-color:#EFEFEF;border-top:1px solid #e7e7e7;border-left:1px solid #e7e7e7;border-right:1px solid #e7e7e7;">
						<input type=hidden name="view_num"  id="view_num"  value="<?=$view_num?>">
						<input type=hidden name="page"  id="page"  value="<?=$page?>">
						<table style="background-color:#EFEFEF;">
						  <thead>
							<tr>
							  <th style="text-align:left;margin-top:10px;">
									<div style="margin-left:622px;margin-bottom:5px;margin-top:5px;">
										<button type="button" class="btn btn-success" onclick="javascript:f_date0();"  style="background-color:#F29661;font-size:8pt;height:26px;">당일</button>
										<button type="button" class="btn btn-success" onclick="javascript:f_date1();"  style="background-color:#F29661;font-size:8pt;height:26px;">3일</button>
										<button type="button" class="btn btn-success" onclick="javascript:f_date2();"  style="background-color:#F29661;font-size:8pt;height:26px;">1주일</button>
										<button type="button" class="btn btn-success" onclick="javascript:f_date3();"  style="background-color:#F29661;font-size:8pt;height:26px;">2주일</button>
										<button type="button" class="btn btn-success" onclick="javascript:f_date4();"  style="background-color:#F29661;font-size:8pt;height:26px;">1개월</button>
										<button type="button" class="btn btn-success" onclick="javascript:f_date5();"  style="background-color:#F29661;font-size:8pt;height:26px;">3개월</button>
									</div>
								  &nbsp;&nbsp;&nbsp;&nbsp;현 장 명&nbsp;&nbsp;&nbsp;<?=f_hyunjang_select("h_idx",$h_idx," style='width:315px;'")?>&nbsp;&nbsp;
								  &nbsp;&nbsp;&nbsp;&nbsp;대 상 기 간&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name=target_date style="width:200px;">
								  <option value="1" <?if($target_date=="1"){?>selected<?}?>>접수일</option>
								  <option value="2" <?if($target_date=="2"){?>selected<?}?>>전입의뢰일</option>
								  <option value="3" <?if($target_date=="3"){?>selected<?}?>>전입수령일</option>
								  <option value="4" <?if($target_date=="4"){?>selected<?}?>>재열람의뢰일</option>
								  <option value="5" <?if($target_date=="5"){?>selected<?}?>>재열람수령일</option>
								  <option value="100" <?if($target_date=="100"){?>selected<?}?>>최근작업데이타</option>
								  </select>&nbsp;&nbsp;<input type=text name="s_date" id="s_date" value="<?=$s_date?>"  class="datepickx" size=8 maxlength=8 style="width:85px;height:20px;">&nbsp;~&nbsp;<input type=text name="e_date" id="e_date" value="<?=$e_date?>"  class="datepickx" size=8 maxlength=8 style="width:85px;height:20px;">
								  &nbsp;&nbsp;&nbsp;담 당 자&nbsp;&nbsp;<?=f_damdang_oiju("damdang_id",$damdang_id," style='width:150px;' ")?>
								  &nbsp;&nbsp;소유주와의관계&nbsp;<?=f_sou_s("sou_relation",$sou_relation," style='width:200px;' ")?>
								  <Br>
								  &nbsp;&nbsp;&nbsp;&nbsp;은&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;행&nbsp;&nbsp;&nbsp;
									<input type="checkbox" id="bank_null_ch" name="bank_null_ch" <?if($bank_null_ch=="Y"){?>checked<?}?> value="Y">&nbsp;&nbsp;&nbsp;

							<select name="bank_code" id="bank_code"  onchange="javascript:select_detail('bank_code','jijum_code');" style='width:120px;'>
								<option value="">--은행--</option>
								<?
								if($h_idx==""){
									$sql = "select * from tbl_bank_info ";
								}else{
									$sql = "select b.bank_code,b.bank_name from tbl_bank_info b left join tbl_junib j on b.bank_code=j.d1 where j.h_idx={$h_idx} group by b.bank_code ";
								}
								$sosok_r = $pdo->prepare($sql);
								$sosok_r->execute();
								while($rr = $sosok_r->fetch()){?>
									<option value="<?=$rr[bank_code]?>" <?if($rr[bank_code]==$bank_code){?>selected<?}?>><?=$rr[bank_name]?></option>
								<?}?>
							</select>
							<select name="jijum_code" id="jijum_code" style='width:160px;'>
								<?
								if(($bank_code!="")&&($h_idx!="")){
									$sql = "select b.jijum_code,b.jijum_name from tbl_bank_jijum b left join tbl_junib j on b.jijum_code=j.e1 where j.h_idx={$h_idx} and j.d1='{$bank_code}'  group by b.jijum_code order by b.jijum_code,b.jijum_name asc  ";
									$mm = 1;
								}else if(($bank_code!="")&&($h_idx=="")){
									$sql = "select * from tbl_bank_jijum where bank_code='{$bank_code}' ";
									$mm = 1;
								}
								if($mm==1){
									$sosok_r = $pdo->prepare($sql);
									$sosok_r->execute();?>
										<option value="">--지점--</option>
									<?while($rr = $sosok_r->fetch()){?>
										<option value="<?=$rr[jijum_code]?>" <?if($rr[jijum_code]==$jijum_code){?>selected<?}?>><?=$rr[jijum_name]?></option>
									<?}?>
								<?}else{?>
										<option value="">--지점--</option>
								<?}?>
							</select>

								  &nbsp;&nbsp;&nbsp;재열람 확인사항&nbsp;&nbsp;<?=f_sou_s("review_confirm",$review_confirm," style='width:200px;' ")?>
								  &nbsp;&nbsp;&nbsp;&nbsp;전입세대상세&nbsp;&nbsp;<select name=daesang  style="width:175px;">
									<option value="">--선택--</option>
									<option value="1" <?if($daesang==1){?>selected<?}?>>미의뢰목록</option>
									<option value="2" <?if($daesang==2){?>selected<?}?>>미수령목록</option>
									<option value="3" <?if($daesang==3){?>selected<?}?>>재열람(미의뢰)목록</option>
									<option value="4" <?if($daesang==4){?>selected<?}?>>재열람(미수령)목록</option>
								  </select>
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;취 득 자&nbsp;&nbsp;<input type=text name="j1" value="<?=$j1?>" style="width:136px;">
								  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;비&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;고&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=text name="memo" value="<?=$memo?>" style="width:185px;">
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
						<tr style="height:40px;vertical-align: middle;">
						  <th style="text-align:left;border-right:0px;width:620px;"><b style="color:black;font-weight:bold;"><?if($h_idx!=""){?>현장명: <?=f_hyunjang_name($h_idx)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?><?if(($target_date!="")&&($s_date!="")&&($e_date!="")){?><?=$tt?>: <?=f_date($s_date)?>~ <?=f_date($e_date)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?>총 <?=f_money0($rows_total)?>건</b></th>
						  <th style="text-align:right;border-left:0px;">
							<button type="button" class="btn btn-success" onclick="javascript:f_chogi();"  style="background-color:#F29661;">초기화</button>&nbsp;
							<input type=text name="search_date" id="search_date"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;" Value="<?=Date("Ymd")?>">
							&nbsp;담당자&nbsp;<?=f_damdang_oiju("search_damdang_id",$damdang_id," style='width:130px;height:27px;' ")?>
							&nbsp;&nbsp;소유주와의관계&nbsp;<?=f_sou("search_sou_relation",$sou_relation," style='width:180px;height:27px;' ")?>
							&nbsp;&nbsp;비고&nbsp;<input type=text name="search_memo" id="search_memo" style="width:120px;height:15px;">&nbsp;&nbsp;&nbsp;&nbsp;
							<button type="button" class="btn btn-success" onclick="javascript:f_apply();"  style="background-color:#F29661;">적용</button>&nbsp;&nbsp;&nbsp;&nbsp;
						  </th>
						</tr>
					  </thead>
					</table>
				</form>
			</div>


<form name=ffm method="post">
	<input type=hidden name="list_num"  id="list_num"  value="<?=$view_num?>">
      <div class="widget-box">
          <div class="widget-content nopadding">
					  <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="font-size:8pt;">No</th>
                  <th style="font-size:8pt;">고객고유번호</th>
                  <th style="font-size:8pt;">은행</th>
                  <th style="font-size:8pt;">지점</th>
                  <th style="font-size:8pt;">등기접수일</th>
                  <th style="font-size:8pt;">취득자1</th>
                  <th style="font-size:8pt;">취득자2</th>
                  <th style="font-size:8pt;">배우자</th>
                  <th style="font-size:8pt;">전화번호</th>

                  <th style="text-align:left;width:108px;font-size:8pt;"><input type="checkbox" id="junib_request_date_ch">&nbsp;전입의뢰일</th>
                  <th style="text-align:left;width:108px;font-size:8pt;"><input type="checkbox" id="junib_s_date_ch">&nbsp;전입수령일</th>
                  <th style="text-align:left;width:107px;font-size:8pt;"><input type="checkbox" id="damdang_id_ch">&nbsp;담당자(외주)</th>

                  <th style="background-color:#70AD47;color:white;text-align:left;width:127px;font-size:8pt;"><input type="checkbox" id="sou_relation_ch">&nbsp;소유주와의관계</th>
                  <th style="background-color:#70AD47;color:white;text-align:left;width:108px;font-size:8pt;"><input type="checkbox" id="review_request_date_ch">&nbsp;재열람의뢰일</th>
                  <th style="background-color:#70AD47;color:white;text-align:left;width:108px;font-size:8pt;"><input type="checkbox" id="review_s_date_ch">&nbsp;재열람수령일</th>
                  <th style="background-color:#70AD47;color:white;text-align:left;width:127px;font-size:8pt;"><input type="checkbox" id="review_confirm_ch">&nbsp;재열람확인사항</th>
                  <th style="background-color:#70AD47;color:white;text-align:left;width:108px;font-size:8pt;"><input type="checkbox" id="sms_date_ch">&nbsp;문자발송일</th>
                  <th style="background-color:#70AD47;color:white;text-align:left;width:131px;font-size:8pt;"><input type="checkbox" id="memo_ch">&nbsp;비고</th>
                </tr>
              </thead>
              <tbody>

	<?
	$Link_Value = "?list_num={$view_num}&s_gubun=$s_gubun&s_search=$s_search";
	$Page_link = _Make_Link($rows_total,$view_num,$Page_List,$page,$Link_Value,$img_pp,$img_p,$img_nn,$img_n);

	$sql = "select * from $board_dbname  $wherequery order by  cast(h1 as unsigned) asc,cast(i1 as unsigned) asc limit $Page_link[start],$view_num";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	if($rows > 0){
		$T=1;$i=1;
		while($row = $stmt->fetch()){?>


		<input type=hidden name="idx_<?=$i?>" value="<?=$row[idx]?>">

                <tr class="odd gradeX">
                  <td style="text-align:center;font-size:8pt;"><?=($page-1)*$view_num+$T?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$row[a1]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=f_bank_name($row[d1])?></td>
                  <td style="text-align:center;font-size:8pt;"><?=f_jijum_name($row[e1])?></td>
                  <td style="text-align:center;font-size:8pt;"><?=f_date($row[g1])?></td>
                  <td style="text-align:center;font-size:8pt;"><a href="javascript:f_popup_g('<?=$row[a1]?>');"  style="text-decoration:underline;color:red;"><?=$row[j1]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$row[m1]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$row[s1]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$row[p1]?></td>

                  <td style="text-align:left;"><input type="checkbox" name="junib_request_date1_<?=$i?>" class="junib_request_date1" value="y">&nbsp;<input type=text name="junib_request_date_<?=$i?>" value="<?=$row[junib_request_date]?>"  class="datepickx junib_request_date_xx" size=8 maxlength=8 style="width:53px;height:15px;font-size:8pt;" onfocus="javascript:focusFunction(this);"></td>

                  <td style="text-align:left;"><input type="checkbox" name="junib_s_date1_<?=$i?>" class="junib_s_date1" value="y">&nbsp;<input type=text name="junib_s_date_<?=$i?>" value="<?=$row[junib_s_date]?>"  class="datepickx junib_s_date_xx" size=8 maxlength=8 style="width:53px;height:15px;font-size:8pt;"  onfocus="javascript:focusFunction(this);"></td>

                  <td style="text-align:left;"><input type="checkbox" name="damdang_id1_<?=$i?>" class="damdang_id1" value="y">&nbsp;<?=f_damdang_oiju("damdang_id_{$i}",$row[damdang_id]," style='width:90px;height:25px;font-size:8pt;' class='damdang_id_xx'  onfocus='javascript:focusFunction(this);' ")?></td>

				  <!--소유주와의관계/-->
                  <td style="text-align:left;"><input type="checkbox" name="sou_relation1_<?=$i?>" class="sou_relation1" value="y">&nbsp;<?=f_sou("sou_relation_{$i}",$row[sou_relation]," style='width:110px;height:25px;font-size:8pt;'  class='sou_relation_xx'   onfocus='javascript:focusFunction(this);' ")?></td>

                  <td style="text-align:left;"><input type="checkbox" name="review_request_date1_<?=$i?>" class="review_request_date1"  value="y">&nbsp;<input type=text name="review_request_date_<?=$i?>" value="<?=$row[review_request_date]?>"  class="datepickx review_request_date_xx" size=8 maxlength=8 style="width:53px;height:15px;font-size:8pt;"  onfocus='javascript:focusFunction(this);'></td>

                  <td style="text-align:left;"><input type="checkbox" name="review_s_date1_<?=$i?>" class="review_s_date1" value="y">&nbsp;<input type=text name="review_s_date_<?=$i?>" value="<?=$row[review_s_date]?>"  class="datepickx review_s_date_xx" size=8 maxlength=8 style="width:53px;height:15px;font-size:8pt;"  onfocus="javascript:focusFunction(this);"></td>

				  <!--재열람확인사항/-->
                  <td style="text-align:left;"><input type="checkbox" name="review_confirm1_<?=$i?>" class="review_confirm1" value="y">&nbsp;<?=f_sou("review_confirm_{$i}",$row[review_confirm]," style='width:110px;height:25px;font-size:8pt;'   class='review_confirm_xx'   onfocus='javascript:focusFunction(this);'  ")?></td>

                  <td style="text-align:left;"><input type="checkbox" name="sms_date1_<?=$i?>" class="sms_date1" value="y">&nbsp;<input type=text name="sms_date_<?=$i?>" value="<?=$row[sms_date]?>"  class="datepickx sms_date_xx" size=8 maxlength=8 style="width:53px;height:15px;font-size:8pt;"  onfocus="javascript:focusFunction(this);"></td>

                  <td style="text-align:left;"><input type="checkbox" name="memo1_<?=$i?>" class="memo1" value="y">&nbsp;<input type=text name="memo_<?=$i?>" value="<?=$row[memo]?>" style="width:100px;height:15px;font-size:8pt;" class="memo_xx"  onfocus="javascript:focusFunction(this);"></td>
                </tr>

	<?$T++;$i++;}
}else{?>
              <tr class="title">
                <td colspan=17 align=center>내용이 없습니다.</td>
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

<?if($_SESSION["admin_permission"][ch_122]=="y"){?>
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

		</div>
      

		</form>
    </div>
  </div>
</div>

<!--bottom-시작-->
	<?include ("../include/bottom.php");?>
<!--bottom-종료-->



<script src="/js/common.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script>
function f_submitx(){  //검색
	document.ffx.submit();
}

function f_popup_g(a1){//기본상세조회
	var url    ="/2_custom/1_search/popup_g.php?a1="+encodeURI(a1);
	var title  = "listpops";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1400, height=840, top=0,left=20"; 
	var a = window.open(url, title,status);
	a.focus();
}

function f_submit(){
	var v = document.ffm;
	if(confirm("저장하시겠습니까?")){
		var frm    = document.ffm;
		var url    = "regist_post.php";
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

$(document).ready(function(){

	$("#junib_request_date_ch").click(function(e){ 
		if($(this).is(":checked")){
			//alert(1);
			$(".junib_request_date1").prop("checked", true);
		}else{
			//alert(0);
			$(".junib_request_date1").prop("checked", false);
		}
	});
	$("#junib_s_date_ch").click(function(e){ 
		if($(this).is(":checked")){
			$(".junib_s_date1").prop("checked", true);
		}else{
			$(".junib_s_date1").prop("checked", false);
		}
	});
	$("#damdang_id_ch").click(function(e){ 
		if($(this).is(":checked")){
			$(".damdang_id1").prop("checked", true);
		}else{
			$(".damdang_id1").prop("checked", false);
		}
	});
	$("#sou_relation_ch").click(function(e){
		if($(this).is(":checked")){
			$(".sou_relation1").prop("checked", true);
		}else{
			$(".sou_relation1").prop("checked", false);
		}
	});
	$("#review_request_date_ch").click(function(e){
		if($(this).is(":checked")){
			$(".review_request_date1").prop("checked", true);
		}else{
			$(".review_request_date1").prop("checked", false);
		}
	});
	$("#review_s_date_ch").click(function(e){
		if($(this).is(":checked")){
			$(".review_s_date1").prop("checked", true);
		}else{
			$(".review_s_date1").prop("checked", false);
		}
	});
	$("#review_confirm_ch").click(function(e){
		if($(this).is(":checked")){
			$(".review_confirm1").prop("checked", true);
		}else{
			$(".review_confirm1").prop("checked", false);
		}
	});
	$("#sms_date_ch").click(function(e){
		if($(this).is(":checked")){
			$(".sms_date1").prop("checked", true);
		}else{
			$(".sms_date1").prop("checked", false);
		}
	});
	$("#memo_ch").click(function(e){
		if($(this).is(":checked")){
			$(".memo1").prop("checked", true);
		}else{
			$(".memo1").prop("checked", false);
		}
	});
});

</script>


<script>
//	select_detail('bank_code','jijum_code');

function f_chogi(){  //초기화
	if(confirm("초기화 하시겠습니까?")){
			$("#junib_request_date_ch").prop("checked", false);
			$("#junib_s_date_ch").prop("checked", false);
			$("#damdang_id_ch").prop("checked", false);
			$("#sou_relation_ch").prop("checked", false);
			$("3review_request_date_ch").prop("checked", false);
			$("#review_s_date_ch").prop("checked", false);
			$("#review_confirm_ch").prop("checked", false);
			$("#sms_date_ch").prop("checked", false);
			$("#memo_ch").prop("checked", false);

			$(".junib_request_date1").prop("checked", false);
			$(".junib_s_date1").prop("checked", false);
			$(".damdang_id1").prop("checked", false);
			$(".sou_relation1").prop("checked", false);
			$(".review_request_date1").prop("checked", false);
			$(".review_s_date1").prop("checked", false);
			$(".review_confirm1").prop("checked", false);
			$(".sms_date1").prop("checked", false);
			$(".memo1").prop("checked", false);

			$(".damdang_id_xx").val("");
			$(".sou_relation_xx").val("");
			$(".review_confirm_xx").val("");

			$(".junib_request_date_xx").val("");
			$(".junib_s_date_xx").val("");
			$(".review_request_date_xx").val("");
			$(".review_s_date_xx").val("");
			$(".sms_date_xx").val("");
			$(".memo_xx").val("");

	}
}

function f_apply(){  //적용

	//alert($("#search_memo").val());
	//search_date search_damdang_id search_sou_relation search_memo
	for(i=1;i<=<?=$view_num?>;i++){

		if($("#search_date").val()!=""){  //날짜일때

			if($("input:checkbox[name='junib_request_date1_"+i+"']").is(":checked")==true){
				$("input:text[name='junib_request_date_"+i+"']").val($("#search_date").val());
			}
			if($("input:checkbox[name='junib_s_date1_"+i+"']").is(":checked")==true){
				$("input:text[name='junib_s_date_"+i+"']").val($("#search_date").val());
			}
			if($("input:checkbox[name='review_request_date1_"+i+"']").is(":checked")==true){
				$("input:text[name='review_request_date_"+i+"']").val($("#search_date").val());
			}
			if($("input:checkbox[name='review_s_date1_"+i+"']").is(":checked")==true){
				$("input:text[name='review_s_date_"+i+"']").val($("#search_date").val());
			}
			if($("input:checkbox[name='sms_date1_"+i+"']").is(":checked")==true){
				$("input:text[name='sms_date_"+i+"']").val($("#search_date").val());
			}

		}
		if($("#search_damdang_id").val()!=""){//담당자외주일때
			if($("input:checkbox[name='damdang_id1_"+i+"']").is(":checked")==true){
				$("select[name='damdang_id_"+i+"']").val($("#search_damdang_id").val());
			}
		}
		if($("#search_sou_relation").val()!=""){//소유주와의 관계 / 재열람확인사항
			if($("input:checkbox[name='sou_relation1_"+i+"']").is(":checked")==true){
				$("select[name='sou_relation_"+i+"']").val($("#search_sou_relation").val());
			}
			if($("input:checkbox[name='review_confirm1_"+i+"']").is(":checked")==true){
				$("select[name='review_confirm_"+i+"']").val($("#search_sou_relation").val());
			}
		}
		if($("#search_memo").val()!=""){ //비고
			if($("input:checkbox[name='memo1_"+i+"']").is(":checked")==true){
				$("input:text[name='memo_"+i+"']").val($("#search_memo").val());
			}
		}
	}

}
</script>

</body>
</html>

</body>
</html>
