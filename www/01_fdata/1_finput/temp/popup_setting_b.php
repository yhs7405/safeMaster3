<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_bank_jijum_rate";

	$h_idx			=	trim($_REQUEST[h_idx]);
	$bank_code		=	trim($_REQUEST[bank_code]);
	$jijum_code		=	trim($_REQUEST[jijum_code]);
	$gubun_code		=	trim($_REQUEST[gubun_code]);

	$view_num		=	trim($_REQUEST[view_num]);	//한라인에 몇개를 출력할건지//
	if($_REQUEST[page]==""){$page=1;}else{$page=$_REQUEST[page];}
	$Page_List		=	10;							//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	50;					//리스트 갯수

	$wherequery = " where basic_gukto='basic'  ";
	if($h_idx!="")  $wherequery.= " and h_idx='{$h_idx}' ";
	if($bank_code!="")  $wherequery.= " and bank_code='{$bank_code}' ";
	if($jijum_code!="")  $wherequery.= " and jijum_code='{$jijum_code}' ";
	if($gubun_code!="")  $wherequery.= " and gubun_code='{$gubun_code}' ";

	db_count_all($board_dbname,$wherequery);
	$rows_total = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();
	//echo $rows_total;
	//echo "<br><br>".$wherequery;
?>
<!DOCTYPE html>
<html lang="kr">

<head>
<title>재무돌이</title>
<?include ("../../include/common.php");?>
</head>


<body style="background:white;">


<div id="content" style="margin-top:-27px;">

<div class="container-fluid">
  <div class="row-fluid">

      <div class="span6" style="width:98%;">
        <div class="widget-box">
          <div class="widget-content nopadding">


            <div class="widget-content nopadding">
					<!--tab 시작-->
			        <ul class="nav nav-tabs">
			            <li>
			                <a href="popup_setting.php">&nbsp;&nbsp;&nbsp;은&nbsp;&nbsp;행&nbsp;&nbsp;&nbsp; </a>
			            </li>
			            <li  class="active">
			                <a href="#">&nbsp;&nbsp;&nbsp;현&nbsp;&nbsp;장&nbsp;&nbsp;&nbsp; </a>
			            </li>
	        		</ul>
        	</div>


			<div  style="width:100%;background-color:#EFEFEF;border-top:1px solid #e7e7e7;border-left:1px solid #e7e7e7;border-right:1px solid #e7e7e7;">
			<form name=ffx method=post>
				<input type=hidden name="view_num"  id="view_num"  value="<?=$view_num?>">
				<input type=hidden name="page"  id="page"  value="<?=$page?>">
			<center>
			<table style="background-color:#EFEFEF;">
			  <thead>
				<tr>
				  <th style="text-align:center;margin-top:20px;" valign="top">

					&nbsp;&nbsp;&nbsp;&nbsp;현 장 명&nbsp;&nbsp;&nbsp;<?=f_hyunjang_select("h_idx",$h_idx," style='width:281px;'")?>
					&nbsp;&nbsp;&nbsp;&nbsp;은행명&nbsp;
					<select name="bank_code" id="bank_code"  onchange="javascript:select_detail('bank_code','jijum_code');" style='width:120px;'>
										<option value="">--은행--</option>
									<?
									$sql = "select * from tbl_bank_info ";
									$sosok_r = $pdo->prepare($sql);
									$sosok_r->execute();
									while($rr = $sosok_r->fetch()){?>
										<option value="<?=$rr[bank_code]?>" <?if($rr[bank_code]==$bank_code){?>selected<?}?>><?=$rr[bank_name]?></option>
									<?}?>
								</select>
					&nbsp;&nbsp;&nbsp;&nbsp;지점&nbsp;
								<select name="jijum_code" id="jijum_code" style='width:160px;'>
									<?
									if($bank_code!=""){
										$sql = "select * from tbl_bank_jijum where bank_code='{$bank_code}'  ";
										$sosok_r = $pdo->prepare($sql);
										$sosok_r->execute();
										while($rr = $sosok_r->fetch()){?>
											<option value="<?=$rr[jijum_code]?>" <?if($rr[jijum_code]==$jijum_code){?>selected<?}?>><?=$rr[jijum_name]?></option>
										<?}?>
									<?}else{?>
											<option value="">----</option>
									<?}?>
								</select>&nbsp;&nbsp;&nbsp;
					구분&nbsp;<select name="gubun_code" id="gubun_code" >
											<option value="" <?if($gubun_code==""){?>selected<?}?>>--선택--</option>
											<option value="basic" <?if($gubun_code=="basic"){?>selected<?}?>>기본</option>
											<option value="bonjum" <?if($gubun_code=="bonjum"){?>selected<?}?>>본점제휴</option>
									</select>
					  &nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-success" onclick="javascript:f_submitx();" style="background-color:#4374D9;height:30px;width:60px;margin-top:-2px;">조회</button>


				  </th>
				</tr>
			  </thead>
			</table>
			</center>
			</form>
			</div>


        <div class="widget-box">
          <div class="widget-content nopadding">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>현장</th>
                  <th>은행</th>
                  <th>지점</th>
                  <th>구분</th>
                </tr>
              </thead>
              <tbody>

	<?
	$Link_Value = "?list_num={$view_num}&s_gubun=$s_gubun&s_search=$s_search";
	$Page_link = _Make_Link($rows_total,$view_num,$Page_List,$page,$Link_Value,$img_pp,$img_p,$img_nn,$img_n);

	$sql = "select * from $board_dbname  $wherequery  order by h_idx desc,bank_code asc,jijum_code asc limit $Page_link[start],$view_num";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();

	if($rows > 0){
		if($_REQUEST[page]==""){$page=1;}else{$page=$_REQUEST[page];}
		$T=1;
		while($row = $stmt->fetch()){?>

                <tr class="odd gradeX">
                  <td style="text-align:center;"><a href="javascript:f_idx('<?=$row[bank_code]?>','<?=$row[gubun_code]?>','<?=$row[jijum_code]?>','<?=$row[h_idx]?>');"><?=($page-1)*$view_num+$T?></a></td>
                  <td style="text-align:center;"><a href="javascript:f_idx('<?=$row[bank_code]?>','<?=$row[gubun_code]?>','<?=$row[jijum_code]?>','<?=$row[h_idx]?>');"><?=f_hyunjang_name($row[h_idx])?></a></td>
                  <td style="text-align:center;"><a href="javascript:f_idx('<?=$row[bank_code]?>','<?=$row[gubun_code]?>','<?=$row[jijum_code]?>','<?=$row[h_idx]?>');"><?=f_bank_code_name($row[bank_code])?></a></td>
                  <td style="text-align:center;"><a href="javascript:f_idx('<?=$row[bank_code]?>','<?=$row[gubun_code]?>','<?=$row[jijum_code]?>','<?=$row[h_idx]?>');"><?=f_jijum_code_name($row[jijum_code])?></a></td>
                  <td style="text-align:center;"><a href="javascript:f_idx('<?=$row[bank_code]?>','<?=$row[gubun_code]?>','<?=$row[jijum_code]?>','<?=$row[h_idx]?>');"><?=f_gubun_code($row[gubun_code])?></a></td>
                </tr>

	<?$T++;}
}else{?>
              <tr class="title">
                <td colspan=3 align=center>내용이 없습니다.</td>
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


<script src="/js/jquery.min.js"></script> 
<script src="/js/jquery.ui.custom.js"></script> 
<script src="/js/bootstrap.min.js"></script> 
<script src="/js/jquery.dataTables.min.js"></script> 
<script src="/js/maruti.js"></script> 

<script src="/js/common.js"></script>

<script>
function f_submitx(){
	var v = document.ffx;
	v.submit();
}


function f_idx(bank_code,gubun_code,jijum_code,h_idx){//은행비율정보 가져오기
	if(confirm("적용하시겠습니까?")){
		//location.href = "ajax_bank.php?bank_code="+bank_code+"&gubun_code="+gubun_code;
		if(bank_code!=""){
			//alert("");
			$.ajax({
				type:"POST",
				url:'./ajax_jijum.php',
				dataType:"json",
				data:{bank_code:bank_code,gubun_code:gubun_code,jijum_code:jijum_code,h_idx:h_idx},
				timeout : 30000,
				success:function (req) {
					//alert(req.b_julsak_position);
					//$("input[id=txt]").val();

				//기본
					$("input[name=b_basic_bosu]",opener.document).val(req.b_basic_bosu);
					$("#b_julsak_position",opener.document).val(req.b_julsak_position);
					$("textarea[name=b_nujin_bosu]",opener.document).val(req.b_nujin_bosu);
					$("input[name=b_halin]",opener.document).val(req.b_halin);
					$("input[name=b_singonabbu]",opener.document).val(req.b_singonabbu);
					$("input[name=b_kyotong]",opener.document).val(req.b_kyotong);
					$("input[name=b_woninjungseo]",opener.document).val(req.b_woninjungseo);

					//alert(req.b_nujin_bosu);

					$("input[name=g_singonabbu]",opener.document).val(req.g_singonabbu);
					$("input[name=g_jungjidae]",opener.document).val(req.g_jungjidae);
					$("input[name=g_kyotong]",opener.document).val(req.g_kyotong);
					$("input[name=g_jejungmyung]",opener.document).val(req.g_jejungmyung);
					$("input[name=g_yeolamjunggi]",opener.document).val(req.g_yeolamjunggi);
					$("input[name=g_deungchobon]",opener.document).val(req.g_deungchobon);
					$("input[name=g_jibaeinchobon]",opener.document).val(req.g_jibaeinchobon);

					if(req.b_singonabbu_ch=="y"){
						$("input:checkbox[name='b_singonabbu_ch']",opener.document).prop("checked", true);
					}else{
						$("input:checkbox[name='b_singonabbu_ch']",opener.document).prop("checked", false);
					}

					if(req.b_kyotong_ch=="y"){
						$("input:checkbox[name='b_kyotong_ch']",opener.document).prop("checked", true);
					}else{
						$("input:checkbox[name='b_kyotong_ch']",opener.document).prop("checked", false);
					}

					if(req.g_singonabbu_ch=="y"){
						$("input:checkbox[name='g_singonabbu_ch']",opener.document).prop("checked", true);
					}else{
						$("input:checkbox[name='g_singonabbu_ch']",opener.document).prop("checked", false);
					}

					if(req.g_kyotong_ch=="y"){
						$("input:checkbox[name='g_kyotong_ch']",opener.document).prop("checked", true);
					}else{
						$("input:checkbox[name='g_kyotong_ch']",opener.document).prop("checked", false);
					}

				//국토
					$("input[name=b_basic_bosu_1]",opener.document).val(req.b_basic_bosu_1);
					$("#b_julsak_position_1",opener.document).val(req.b_julsak_position_1);
					$("textarea[name=b_nujin_bosu_1]",opener.document).val(req.b_nujin_bosu_1);
					$("input[name=b_halin_1]",opener.document).val(req.b_halin_1);
					$("input[name=b_singonabbu_1]",opener.document).val(req.b_singonabbu_1);
					$("input[name=b_kyotong_1]",opener.document).val(req.b_kyotong_1);
					$("input[name=b_woninjungseo_1]",opener.document).val(req.b_woninjungseo_1);

					//alert(req.b_nujin_bosu_1);

					$("input[name=g_singonabbu_1]",opener.document).val(req.g_singonabbu_1);
					$("input[name=g_jungjidae_1]",opener.document).val(req.g_jungjidae_1);
					$("input[name=g_kyotong_1]",opener.document).val(req.g_kyotong_1);
					$("input[name=g_jejungmyung_1]",opener.document).val(req.g_jejungmyung_1);
					$("input[name=g_yeolamjunggi_1]",opener.document).val(req.g_yeolamjunggi_1);
					$("input[name=g_deungchobon_1]",opener.document).val(req.g_deungchobon_1);
					$("input[name=g_jibaeinchobon_1]",opener.document).val(req.g_jibaeinchobon_1);

					if(req.b_singonabbu_ch_1=="y"){
						$("input:checkbox[name='b_singonabbu_ch_1']",opener.document).prop("checked", true);
					}else{
						$("input:checkbox[name='b_singonabbu_ch_1']",opener.document).prop("checked", false);
					}

					if(req.b_kyotong_ch_1=="y"){
						$("input:checkbox[name='b_kyotong_ch_1']",opener.document).prop("checked", true);
					}else{
						$("input:checkbox[name='b_kyotong_ch_1']",opener.document).prop("checked", false);
					}

					if(req.g_singonabbu_ch_1=="y"){
						$("input:checkbox[name='g_singonabbu_ch_1']",opener.document).prop("checked", true);
					}else{
						$("input:checkbox[name='g_singonabbu_ch_1']",opener.document).prop("checked", false);
					}

					if(req.g_kyotong_ch_1=="y"){
						$("input:checkbox[name='g_kyotong_ch_1']",opener.document).prop("checked", true);
					}else{
						$("input:checkbox[name='g_kyotong_ch_1']",opener.document).prop("checked", false);
					}

					alert("적용되었습니다.");
					window.close();

				},
				error:function(ecode,etext,ethrow) {
					//alert(etext);
				}
			});
		}
	}
}

</script>

</body>
</html>
