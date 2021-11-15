<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname		=	" tbl_junib j cross join tbl_suljung b on j.a1=b.a1 ";

	$h_idx			=	trim($_REQUEST[h_idx]);
	$target_date		=	trim($_REQUEST[target_date]);
	$s_date			=	trim($_REQUEST[s_date]);
	$e_date			=	trim($_REQUEST[e_date]);

	$target_date2		=	trim($_REQUEST[target_date2]);
	$s_date2		=	trim($_REQUEST[s_date2]);
	$e_date2		=	trim($_REQUEST[e_date2]);
	$bank_code		=	trim($_REQUEST[bank_code]);
	$jijum_code		=	trim($_REQUEST[jijum_code]);

	$cg_daesang		=	trim($_REQUEST[cg_daesang]);
	$kikan2_null_ch		=	trim($_REQUEST[kikan2_null_ch]);

	$h1			=	trim($_REQUEST[h1]);
	$i1			=	trim($_REQUEST[i1]);
	$j1			=	trim($_REQUEST[j1]);
	$memo			=	trim($_REQUEST[memo]);

	if($target_date=="")	$target_date="g1";

	$sql = "SELECT max( cast( sjb_c_date AS unsigned ) ) AS mm FROM tbl_suljung WHERE (sjb_c_date IS NOT NULL AND sjb_c_date <> '') LIMIT 1";
	$ss = db_query_fetch($sql);

	if(($s_date=="")&&($e_date=="")){
		if($ss[mm]==""){
			$s_date=date("Ymd");
			$e_date=date("Ymd");
		}else{
			$s_date=$ss[mm];
			$e_date=$ss[mm];
		}
	}


	$view_num		=	trim($_REQUEST[view_num]);	//한라인에 몇개를 출력할건지//
	if($_REQUEST[page]==""){$page=1;}else{$page=$_REQUEST[page];}
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	100;					//리스트 갯수

	$wherequery = " where 1=1  ";

	if($h_idx!="")			$wherequery.= " and j.h_idx = ".$h_idx." ";
	if($bank_code!="")		$wherequery.= " and b.bank_code = '".$bank_code."' ";
	if($jijum_code!="")		$wherequery.= " and b.jijum_code = '".$jijum_code."' ";
	if($h1!="")			$wherequery.= " and j.h1 = '".$h1."' ";
	if($i1!="")			$wherequery.= " and j.i1 = '".$i1."' ";
	if($j1!="")			$wherequery.= " and (j.j1 like '%{$j1}%' or j.m1 like '%{$j1}%')";

	if($cg_daesang=="Y")	$wherequery.= " and b.cg_daesang='Y' ";
	
	if($target_date!=""){

		if($target_date=="100") {
			$imsi = " and suljung_update in (SELECT max(suljung_update) FROM tbl_suljung where suljung_update<>'' GROUP BY suljung_update ORDER BY suljung_update DESC)";
			$wherequery.=$imsi;
		}else if(($s_date!="")&&($e_date!="")){
			$imsi = "";
			if(($target_date=="sjp_s_date")||($target_date=="sjp_j_date")||($target_date=="sjj_w_date")||($target_date=="sjp_b_date")||($target_date=="sg_b_date")||($target_date=="sjb_c_date")){  //설정일때
				$imsi = " and b.{$target_date} between {$s_date} and {$e_date} ";
			}else{
				$imsi = " and j.{$target_date} between {$s_date} and {$e_date} ";
			}
			$wherequery.=$imsi;
		}
	}

	if($kikan2_null_ch=="Y"){
			if($target_date2!="") {$imsi = " and ({$target_date2}='' or {$target_date2} is null )";}
			$wherequery.=$imsi;
	}else{
		if($target_date2!=""){
			if(($s_date2!="")&&($e_date2!="")){
				$imsi = "";
				if(($target_date2=="sjp_s_date")||($target_date2=="sjp_j_date")||($target_date2=="sjj_w_date")||($target_date2=="sjp_b_date")||($target_date2=="sg_b_date")||($target_date=="sjb_c_date")){ //설정일때
					$imsi = " and b.{$target_date2} between {$s_date2} and {$e_date2} ";
				}else if($target_date2=="hy_b_date"){
					$imsi = " and j.{$target_date2} between {$s_date2} and {$e_date2} ";
				}

				$wherequery.=$imsi;
			}
		}
	}

	if($memo!=""){
		$wherequery.= " and (j.a1 in ( (select a1 from tbl_junib where (memo like '%{$memo}%') or (ijp_s_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_s_memo  like '%{$memo}%') or (ijp_j_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_j_memo  like '%{$memo}%') or (ijj_w_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjj_w_memo  like '%{$memo}%') or (ijp_b_memo  like '%{$memo}%') ";
		$wherequery.= "      or (sjp_b_memo  like '%{$memo}%') or (refund_memo  like '%{$memo}%') ";
		$wherequery.= "      or (refund_end_memo like '%{$memo}%') or (refund_memo  like '%{$memo}%')))  or ";
		$wherequery.= "      j.a1 in (select a1 from tbl_sugum where sugum_memo like '%{$memo}%') )";
	}

	//echo "<br><br>".$wherequery;
	$rows_total = db_count_all($board_dbname,$wherequery);


	function f_total($where){
		$sql = "select sum(bosu_price) as bp, sum(bosu_price_vat) as bpv, sum(gongga_price) as gp from tbl_junib j cross join tbl_suljung b on j.a1=b.a1 {$where}";
		//echo $sql;
		$ss = db_query_value($sql);
		$mm[bp] = $ss[bp];
		$mm[bpv] = $ss[bpv];
		$mm[gp] = $ss[gp];
		return $mm;
	}
	$ssm = f_total($wherequery);

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
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">양식관리</a> <a href="#" class="current">설정양식출력</a> </div>
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
						<div style="margin-left:550px;margin-bottom:5px;margin-top:5px;">
						<button type="button" class="btn btn-success" onclick="javascript:f_date0();"  style="background-color:#F29661;font-size:8pt;height:26px;">당일</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date1();"  style="background-color:#F29661;font-size:8pt;height:26px;">3일</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date2();"  style="background-color:#F29661;font-size:8pt;height:26px;">1주일</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date3();"  style="background-color:#F29661;font-size:8pt;height:26px;">2주일</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date4();"  style="background-color:#F29661;font-size:8pt;height:26px;">1개월</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date5();"  style="background-color:#F29661;font-size:8pt;height:26px;">3개월</button>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<button type="button" class="btn btn-success" onclick="javascript:f_date20();"  style="background-color:#F29661;font-size:8pt;height:26px;">당일</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date21();"  style="background-color:#F29661;font-size:8pt;height:26px;">3일</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date22();"  style="background-color:#F29661;font-size:8pt;height:26px;">1주일</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date23();"  style="background-color:#F29661;font-size:8pt;height:26px;">2주일</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date24();"  style="background-color:#F29661;font-size:8pt;height:26px;">1개월</button>
						<button type="button" class="btn btn-success" onclick="javascript:f_date25();"  style="background-color:#F29661;font-size:8pt;height:26px;">3개월</button>
						</div>
						  &nbsp;&nbsp;&nbsp;&nbsp;현 장 명&nbsp;&nbsp;&nbsp;<?=f_hyunjang_select("h_idx",$h_idx," style='width:281px;'")?>&nbsp;&nbsp;

						  &nbsp;&nbsp;&nbsp;기 간&nbsp;&nbsp;&nbsp;<select name=target_date style="width:200px;">
						  <option value="g1" <?if($target_date=="g1"){?>selected<?}?>>접수일</option>
						  <option value="ijp_s_date" <?if($target_date=="ijp_s_date"){?>selected<?}?>>필증수령일(이전)</option>
						  <option value="ijp_j_date" <?if($target_date=="ijp_j_date"){?>selected<?}?>>필증전달일(이전)</option>
						  <option value="ijj_w_date" <?if($target_date=="ijj_w_date"){?>selected<?}?>>필증정산일(이전)</option>
						  <option value="ijp_b_date" <?if($target_date=="ijp_b_date"){?>selected<?}?>>고객배포일(이전)</option>
						  <option value="refund_date" <?if($target_date=="refund_date"){?>selected<?}?>>환불일</option>
						  <option value="sjp_s_date" <?if($target_date=="sjp_s_date"){?>selected<?}?>>필증수령일(설정)</option>
						  <option value="sjp_j_date" <?if($target_date=="sjp_j_date"){?>selected<?}?>>필증전달일(설정)</option>
						  <option value="sjj_w_date" <?if($target_date=="sjj_w_date"){?>selected<?}?>>필증정산일(설정)</option>
						  <option value="sjp_b_date" <?if($target_date=="sjp_b_date"){?>selected<?}?>>고객배포일(설정)</option>
						  <option value="hy_b_date" <?if($target_date=="hy_b_date"){?>selected<?}?>>현금영수증발행일</option>
						  <option value="sg_b_date" <?if($target_date=="sg_b_date"){?>selected<?}?>>세금계산서발행일</option>
						  <option value="sjb_c_date" <?if($target_date=="sjb_c_date"){?>selected<?}?>>설정비용내역서출력일</option>
						  <option value="100" <?if($target_date=="100"){?>selected<?}?>>최근작업데이타</option>
						  </select>&nbsp;<input type=text name="s_date" id="s_date" value="<?=$s_date?>"  class="datepickx" size=8 maxlength=8 style="width:80px;height:20px;">~<input type=text name="e_date" id="e_date" value="<?=$e_date?>"  class="datepickx" size=8 maxlength=8 style="width:80px;height:20px;">

						  &nbsp;&nbsp;&nbsp;기 간 2&nbsp;&nbsp;&nbsp;<select name=target_date2 style="width:200px;">
						  <option value="">-선택-</option>
						  <option value="g1" <?if($target_date2=="g1"){?>selected<?}?>>접수일</option>
						  <option value="ijp_s_date" <?if($target_date2=="ijp_s_date"){?>selected<?}?>>필증수령일(이전)</option>
						  <option value="ijp_j_date" <?if($target_date2=="ijp_j_date"){?>selected<?}?>>필증전달일(이전)</option>
						  <option value="ijj_w_date" <?if($target_date2=="ijj_w_date"){?>selected<?}?>>필증정산일(이전)</option>
						  <option value="ijp_b_date" <?if($target_date2=="ijp_b_date"){?>selected<?}?>>고객배포일(이전)</option>
						  <option value="refund_date" <?if($target_date2=="refund_date"){?>selected<?}?>>환불일</option>
						  <option value="sjp_s_date" <?if($target_date2=="sjp_s_date"){?>selected<?}?>>필증수령일(설정)</option>
						  <option value="sjp_j_date" <?if($target_date2=="sjp_j_date"){?>selected<?}?>>필증전달일(설정)</option>
						  <option value="sjj_w_date" <?if($target_date2=="sjj_w_date"){?>selected<?}?>>필증정산일(설정)</option>
						  <option value="sjp_b_date" <?if($target_date2=="sjp_b_date"){?>selected<?}?>>고객배포일(설정)</option>
						  <option value="hy_b_date" <?if($target_date2=="hy_b_date"){?>selected<?}?>>현금영수증발행일</option>
						  <option value="sg_b_date" <?if($target_date2=="sg_b_date"){?>selected<?}?>>세금계산서발행일</option>
						  <option value="sjb_c_date" <?if($target_date2=="sjb_c_date"){?>selected<?}?>>설정비용내역서출력일</option>
						  </select>&nbsp;
						    <input type="checkbox" id="kikan2_null_ch" name="kikan2_null_ch" <?if($kikan2_null_ch=="Y"){?>checked<?}?> value="Y">
						  <input type=text name="s_date2" id="s_date2" value="<?=$s_date2?>"  class="datepickx" size=8 maxlength=8 style="width:80px;height:20px;">~<input type=text name="e_date2" id="e_date2" value="<?=$e_date2?>"  class="datepickx" size=8 maxlength=8 style="width:80px;height:20px;">

						  <Br>
						  &nbsp;&nbsp;&nbsp;&nbsp;은&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;행&nbsp;&nbsp;&nbsp;

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
						  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;동&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=text name="h1" value="<?=$h1?>" style="width:80px;">
						  &nbsp;&nbsp;&nbsp;호&nbsp;&nbsp;&nbsp;<input type=text name="i1" value="<?=$i1?>" style="width:80px;">
						  &nbsp;&nbsp;&nbsp;이 름&nbsp;&nbsp;&nbsp;<input type=text name="j1" value="<?=$j1?>" style="width:147px;">
						  &nbsp;&nbsp;&nbsp;&nbsp;비&nbsp;&nbsp;고&nbsp;&nbsp;&nbsp;&nbsp;<input type=text name="memo" value="<?=$memo?>" style="width:343px;">
						  &nbsp;&nbsp;&nbsp;1회청구여부&nbsp;&nbsp;<input type=checkbox name="cg_daesang" value="Y" <?if($cg_daesang=="Y"){?>checked<?}?>>
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
				  <th style="text-align:left;border-right:0px;"><b style="color:black;font-weight:bold;"><?if($h_idx!=""){?>현장명: <?=f_hyunjang_name($h_idx)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
				  <?if(($target_date!="")&&($s_date!="")&&($e_date!="")){?><?=f_cate($target_date)?>: <?=f_date($s_date)?>~ <?=f_date($e_date)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
				  <?if(($target_date2!="")&&($s_date2!="")&&($e_date2!="")){?><?=f_cate($target_date2)?>: <?=f_date($s_date2)?>~ <?=f_date($e_date2)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
				  총 <?=f_money0($rows_total)?>건</b>
				  </th>

<?if($_SESSION["admin_permission"][ch_522]=="y"){?>
				  <th style="text-align:right;border-left:0px;">
					<button type="button" class="btn btn-success" onclick="javascript:f_1();"  style="background-color:#F29661;">영수증출력</button>&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-success" onclick="javascript:f_2();"  style="background-color:#F29661;">세금계산서발행</button>&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-success" onclick="javascript:f_3();"  style="background-color:#F29661;">국토교통부체크</button>&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-success" onclick="javascript:f_excel();"  style="background-color:#F29661;">엑셀다운로드</button>&nbsp;&nbsp;&nbsp;&nbsp;
				  </th>
<?}?>

				</tr>
			  </thead>
			</table>
			</form>
		</div>


	<form name="ffm" method="post">

        <div class="widget-box">
          <div class="widget-content nopadding">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>

		  <th><input type="checkbox" id="chx"></th>
                  <th style="font-size:8pt;">No</th>
                  <th style="font-size:8pt;">접수일</th>

                  <th style="font-size:8pt;">은행</th>
                  <th style="font-size:8pt;">지점명</th>

                  <th style="font-size:8pt;">동</th>
                  <th style="font-size:8pt;">호</th>

                  <th style="font-size:8pt;">채무자</th>
                  <th style="font-size:8pt;">채권최고액</th>

                  <th style="font-size:8pt;">보수액</th>
                  <th style="font-size:8pt;">부가세</th>
                  <th style="font-size:8pt;background-color:#CEFBC9;">보수액합계<br><?=f_money($ssm[bp]+$ssm[bpv])?></th>
                  <th style="font-size:8pt;background-color:#CEFBC9;">공과금<br><?=f_money($ssm[gp])?></th>
                  <th style="font-size:8pt;background-color:#D4F4FA;">총비용<br><?=f_money($ssm[bp]+$ssm[bpv]+$ssm[gp])?></th>
                  <th style="font-size:8pt;">1회청구여부</th>

                  <th style="font-size:8pt;background-color:#70AD47;color:white;">설정비용내역서<br>출력일</th>
                  <th style="font-size:8pt;background-color:#70AD47;color:white;">세금계산서<br>발행일</th>
                  <th style="font-size:8pt;background-color:#70AD47;color:white;">국토교통부</th>
                  <th style="font-size:8pt;">재무돌이비고</th>
                  <th style="font-size:8pt;">은행비고</th>
                  <th style="font-size:8pt;">정산비고</th>
                </tr>

              </thead>
              <tbody>

	<?
	$Link_Value = "?list_num={$view_num}&s_gubun=$s_gubun&s_search=$s_search";
	$Page_link = _Make_Link($rows_total,$view_num,$Page_List,$page,$Link_Value,$img_pp,$img_p,$img_nn,$img_n);

	$sql = "select j.*,b.* from  tbl_junib j cross join tbl_suljung b on j.a1=b.a1   $wherequery order by  cast(j.h1 as unsigned) asc,cast(j.i1 as unsigned) asc limit $Page_link[start],$view_num";
	//echo "<br><br><br>----------".$sql."-------------";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	if($rows > 0){
		$T=1;$i=1;
		while($row = $stmt->fetch()){?>


		<input type=hidden name="idx_<?=$i?>" value="<?=$row[idx]?>">


                <tr class="odd gradeX">

                  <td style="text-align:center;font-size:8pt;"><input type="checkbox" name="ch[]" class="ch" value="<?=$row[idx]?>"></td>
                  <td style="text-align:center;font-size:8pt;"><?=($page-1)*$view_num+$T?></td>
                  <td style="text-align:center;font-size:8pt;"><?=f_date($row[g1])?></td>

                  <td style="text-align:center;font-size:8pt;"><?=f_bank_name($row[bank_code])?></td>
                  <td style="text-align:center;font-size:8pt;"><a href="javascript:f_popup_s('<?=$row[a1]?>');"  style="font-size:8pt;text-decoration:underline;color:red;"><?=f_jijum_name($row[e1])?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$row[h1]?></td>
                  <td style="text-align:center;font-size:8pt;"><?=$row[i1]?></td>

                  <td style="text-align:center;font-size:8pt;"><a href="javascript:f_popup_g('<?=$row[a1]?>');"  style="font-size:8pt;text-decoration:underline;color:red;"><?=$row["aw".$row[suljung_no]]?></td>

                  <td style="text-align:center;font-size:8pt;"><?=f_money($row[chaekwon_max])?></td>

                  <td style="text-align:center;font-size:8pt;"><?=f_money($row[bosu_price])?></td><!--설정보수액//-->

                  <td style="text-align:center;font-size:8pt;"><?=f_money($row[bosu_price_vat])?></td><!--설정보수액부가세//-->

                  <td style="text-align:center;font-size:8pt;"><?=f_money($row[bosu_price]+$row[bosu_price_vat])?></td><!--설정보수액총액//-->


                  <td style="text-align:center;font-size:8pt;"><?=f_money($row[gongga_price])?></td><!--공과금//-->

                  <td style="text-align:center;font-size:8pt;"><?=f_money($row[bosu_price]+$row[bosu_price_vat]+$row[gongga_price])?></td><!--설정보수액+공과금+면허세+교육세//-->

                  <td style="text-align:center;font-size:8pt;"><?=f_Y_value($row[cg_daesang])?></td>
                  <td style="text-align:center;font-size:8pt;"><?=f_date($row[sjb_c_date])?></td><!--설정비용내역서 출력일//-->
                  <td style="text-align:center;font-size:8pt;color:red;"><a href="javascript:f_segum_edit(<?=$row[idx]?>);" style="color:red;font-size:8pt;"><?=f_date($row[sg_b_date])?></a></td><!--세금계산서 발행일//-->
                  <td style="text-align:center;font-size:8pt;color:red;"><a href="javascript:f_gukto_delete(<?=$row[idx]?>);" style="color:red;font-size:8pt;"><?=f_gukto($row[gukto])?></a></td><!--국토교통부//-->
								  <td style="text-align:center;font-size:8pt;"><textarea style="width:100px;font-size:8pt;" name=ijp_s_memo  cols=50 rows=1><?=$row[ijp_s_memo]?></textarea></td>
								  <td style="text-align:center;font-size:8pt;"><textarea style="width:100px;font-size:8pt;" name=au1  cols=50 rows=1><?=$row[au1]?></textarea></td>
                  <td style="text-align:center;font-size:8pt;"><textarea style="width:100px;font-size:8pt;" name=ae1  cols=50 rows=1><?=$row[ae1]?></textarea></td>
                </tr>


	<?$T++;$i++;}
}else{?>
              <tr class="title">
                <td colspan=21 align=center>내용이 없습니다.</td>
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
			<!--
			<div style="float:right;margin-top:-25px;margin-bottom:10px;margin-right:10px;">
				<button type="button" class="btn btn-success" onclick="javascript:f_submit();">저장</button>
			</div>
			//-->
		</div>

      </div>
    </div>

	</form>

  </div>
</div>

<!--bottom-시작-->
	<?include ("../include/bottom.php");?>
<!--bottom-종료-->


<script src="/js/common.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script>


function f_excel(){
	if(<?=$rows_total?>==0){
		alert("총 0건 입니다.");
	}else{
		var frm    = document.ffx;
		frm.action = "suljung_excel.php";
		frm.method = "post";
		frm.submit();
		frm.action = "suljung.php";
	}
}

function f_popup_s(a1){//설정상세조회
	var url    ="/2_custom/1_search/popup_s.php?a1="+encodeURI(a1);
	var title  = "listpops";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1500, height=400, top=0,left=20"; 
	var a = window.open(url, title,status);
	a.focus();
}

function f_popup_g(a1){//기본상세조회
	var url    ="/2_custom/1_search/popup_g.php?a1="+encodeURI(a1);
	var title  = "listpops";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1400, height=900, top=0,left=20"; 
	var a = window.open(url, title,status);
	a.focus();
}

function f_segum_edit(idx){  //세금계산서 수정 / 삭제
	var url    ="./segum_date_edit.htm?idx="+idx;
	var title  = "listpop210";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=400, height=300, top=300,left=600";
	var aa1 = window.open(url, title,status);
	aa1.focus();
	//alert("--");
}


function f_gukto_delete(idx){
	if(confirm("선택된 국토교통부 체크를 삭제하시겠습니까?")){
			var url    ="./kukto_delete_ok.htm?idxx="+idx;
			var title  = "listpop200";
			var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=100, height=100, top=300,left=600"; 
			var aa200 = window.open(url, title,status);
			aa200.focus();
	}
}

function f_1(){  //영수증출력
		var objWrite3 = document.getElementsByName("ch[]");

		//현장명/기간1/은행으로 조회시에만 출력
		var frmx    = document.ffx;
		if((frmx.h_idx.value=="")||(frmx.target_date.value=="")||(frmx.bank_code.value=="")){
			alert("현장명/기간1/은행은 필수 검색 조건입니다.");
		}else{
			var count = 0;
			for(var i=0;i<objWrite3.length;i++){
				if(objWrite3[i].checked == true){
					count++;
				}
			}

			if(count<=0){
				alert("체크박스을 선택해 주세요.");
			}else{
				if(confirm("선택된 영수증을 출력하시겠습니까?")){
					var frm    = document.ffm;
					var url    ="/report_form/suljung/index.html";
					var title  = "listpop2";
					var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=750, height=800, top=100,left=600"; 
					var aa1 = window.open("", title,status);
					frm.target = title;
					frm.action = url;
					frm.method = "post";
					frm.submit();
					aa1.focus();
				}
			}
		}
}

function f_2(){  //세금계산서발행
		var objWrite3 = document.getElementsByName("ch[]");
		var count = 0;
		for(var i=0;i<objWrite3.length;i++){
			if(objWrite3[i].checked == true){
				count++;
			}
		}

		if(count<=0){
			alert("체크박스을 선택 해 주세요.");
		}else{
				var frm    = document.ffm;
				var url    ="./segum_date_select.htm";
				var title  = "listpop21";
				var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=400, height=300, top=100,left=600";
				var aa1 = window.open("", title,status);
				frm.target = title;
				frm.action = url;
				frm.method = "post";
				frm.submit();
				aa1.focus();
				//alert("--");
		}
}

function f_3(){  //국토교통부체크
		var objWrite3 = document.getElementsByName("ch[]");
		var count = 0;
		for(var i=0;i<objWrite3.length;i++){
			if(objWrite3[i].checked == true){
				count++;
			}
		}

		if(count<=0){
			alert("체크박스을 선택 해 주세요.");
		}else{
			if(confirm("선택된 국토교통부 체크를 진행하시겠습니까?")){
				var frm    = document.ffm;
				var url    ="./kukto_ok.htm";
				var title  = "listpop22";
				var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=100, height=100, top=300,left=600"; 
				var aa2 = window.open("", title,status);
				frm.target = title;
				frm.action = url;
				frm.method = "post";
				frm.submit();
				aa2.focus();
			}
		}
}

function f_submitx(){
	document.ffx.page.value=1;
	document.ffx.target="_self";
	document.ffx.submit();
}


function f_submit(){
	var v = document.ffm;
	if(confirm("저장하시겠습니까?")){
		var frm    = document.ffm;
		var url    = "post.php";
		var title  = "listpop22";
		var status = "toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no,width=100, height=100, top=0,left=20"; 
		var aa2 = window.open("", title,status);
		frm.target = title;
		frm.action = url;
		frm.method = "post";
		frm.submit();
		aa2.focus();
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
