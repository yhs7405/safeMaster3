<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_bank_jijum_rate";

	$h_idx		=	trim($_REQUEST[h_idx]);
	$jijum_code	=	trim($_REQUEST[jijum_code]);
	$gubun_code	=	trim($_REQUEST[gubun_code]);
	$basic_gukto	=	trim($_REQUEST[basic_gukto]);
	$bosu_gubun	=	trim($_REQUEST[bosu_gubun]);

	$s_h_idx		=	trim($_REQUEST[s_h_idx]);
	$s_bank_code	=	trim($_REQUEST[s_bank_code]);
	$s_jijum_code	=	trim($_REQUEST[s_jijum_code]);
	$s_gubun_code	=	trim($_REQUEST[s_gubun_code]);
	
	if($h_idx==""){
		$mode="i";  //insert 신규
		$wherequery = " where 1=1  ";
	}else{
		$mode="e";  //edit 수정

		$wherequery = " where h_idx={$h_idx} and jijum_code='{$jijum_code}' and gubun_code='{$gubun_code}'  and h_idx={$h_idx}  ";
		$sql= "select * from $board_dbname ".$wherequery." limit 1";
		//echo "<br><br>".$sql;
		$row = db_query_fetch($sql);
		//echo $row[gubun_code];

		//기본일때
		$sql = "select * from tbl_bank_jijum_rate where h_idx={$h_idx} and jijum_code='{$jijum_code}' and gubun_code='{$gubun_code}' and basic_gukto='basic' limit 1";
		//echo $sql."<br>";
		$b_rate = db_query_fetch($sql);

		//국토일때
		$sql = "select * from tbl_bank_jijum_rate where h_idx={$h_idx} and jijum_code='{$jijum_code}' and gubun_code='{$gubun_code}' and basic_gukto='gukto' limit 1";
		//echo $sql."<br>";
		$g_rate = db_query_fetch($sql);


	}

?>

<!DOCTYPE html>
<html lang="kr">

<head>
<title>재무돌이</title>
<?include ("../../include/common.php");?>
</head>

<body style="overflow:auto; width:1880px;">

<!--header 시작-->
	<?include ("../../include/header.php");?>
<!--header 종료-->


<!--top-메뉴시작-->
	<?include ("../../include/header_menu.php");?>
<!--top-메뉴종료-->


<div id="content">

  <div id="content-header">
    <?if($mode=="e"){?>
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">기본정보</a> <a href="#" class="current">은행지점비용 수정</a> </div>
    <?}else{//신규일때
	$b_rate[g_singonabbu_ch]="y";
	$b_rate[g_kyotong_ch]="y";
	$b_rate[b_singonabbu_ch]="y";
	$b_rate[b_kyotong_ch]="y";
	$g_rate[g_singonabbu_ch]="y";
	$g_rate[g_kyotong_ch]="y";
	$g_rate[b_singonabbu_ch]="y";
	$g_rate[b_kyotong_ch]="y";
    ?>
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">기본정보</a> <a href="#" class="current">은행지점비용 등록</a> </div>
    <?}?>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">

      <div class="span6" style="width:98%;">
        <div class="widget-box">
          <div class="widget-content nopadding">

            <form name="ff" action="post.php" method="post" class="form-horizontal" onsubmit="return f_submit();">
						<input type="hidden" name="mode" id="mode" value="<?=$mode?>">

            <div class="widget-content nopadding">

							<table  style="width:98%;margin:10px;">
							<tr>
								<td width="5%">&nbsp;&nbsp;&nbsp;* 현장</td>
							<td width="120px" colspan=3><select name="h_idx" id="h_idx" <?if($mode=="e"){?>   <? } ?> style="width:300px;>
										<option value="">--현장--</option>
										<?
										if($h_idx==""){
											$sql = "select * from tbl_hyunjang_info";
										}else{
											$sql = "select * from tbl_hyunjang_info where h_idx='{$h_idx}'";
										}
				
										$sosok_r = $pdo->prepare($sql);
										$sosok_r->execute();
										while($rr = $sosok_r->fetch()){?>
											<option value="<?=$rr[h_idx]?>" <?if($rr[h_idx]==$row[h_idx]){?>selected<?}?>><?=$rr[h_name]?></option>
										<?}?>
									</select></td>
								<td width="5%">&nbsp;&nbsp;&nbsp;* 은행</td>
								<td width="80px"><select name="bank_code" id="bank_code"  onchange="javascript:select_detail_all('bank_code','jijum_code');" <?if($mode=="e"){?>   <? } ?> >
										<option value="">--은행--</option>
										<?
										if($h_idx==""){
											$sql = "select * from tbl_bank_info";
										}else{
											$sql = "select * from tbl_bank_info where bank_code='{$row[bank_code]}' order by bank_name asc";
										}
				
										$sosok_r = $pdo->prepare($sql);
										$sosok_r->execute();
										$i = 1;
										while($rr = $sosok_r->fetch()){?>
											<?if($i==1) $bank_code_f=$rr[bank_code];?>
											<option value="<?=$rr[bank_code]?>" <?if($rr[bank_code]==$row[bank_code]){?>selected<?}?>><?=$rr[bank_name]?></option>
										<?$i++;}?>
									</select></td>
								<td width="5%">&nbsp;&nbsp;&nbsp;* 지점</td>
								<td width="120px"><select name="jijum_code" id="jijum_code" style="width:200px;" <?if($mode=="e"){?>   <? } ?> >
										<option value="">--지점--</option>
										<?
										if($h_idx==""){
											$sql = "select * from tbl_bank_jijum where bank_code='{$bank_code_f}' ";
										}else{
											$sql = "select * from tbl_bank_jijum where bank_code='{$bank_code_f}'  order by jijum_name asc";
										}
				
										$sosok_r = $pdo->prepare($sql);
										$sosok_r->execute();
										while($rr = $sosok_r->fetch()){?>
												<option value="<?=$rr[jijum_code]?>" <?if($rr[jijum_code]==$row[jijum_code]){?>selected<?}?>><?=$rr[jijum_name]?></option>
										<?}?>
									</select></td>
								<td width="5%">&nbsp;&nbsp;&nbsp;* 구분</td>
								<td width=80px><select name="gubun_code" id="gubun_code" style="width:80px;">
										<option value="">--구분--</option>
										<?if($h_idx==""){?>
											<option value="basic" <?if($row[gubun_code]=="basic"){?>selected<?}?>>기본</option>
											<option value="bonjum" <?if($row[gubun_code]=="bonjum"){?>selected<?}?>>본점제휴</option>
										<?}else{?>
											<option value="basic" <?if($row[gubun_code]=="basic"){?>selected<?}?>>기본</option>
											<option value="bonjum" <?if($row[gubun_code]=="bonjum"){?>selected<?}?>>본점제휴</option>
										<?}?>
									</select></td>
								<td width="5%">&nbsp;&nbsp;&nbsp;* 태율계좌</td>
								<td width="15%"><select name="account_code" id="account_code" style="width:300px;">
										<option value="">--태율계좌--</option>
										<?
											$sql = "select * from tbl_account";
				
										$sosok_r = $pdo->prepare($sql);
										$sosok_r->execute();
										while($rr = $sosok_r->fetch()){?>
											<option value="<?=$rr[bank_code]?>" <?if($rr[bank_code]==$row[account_code]){?>selected<?}?>><?=$rr[bank_name]?> / <?=$rr[bank_account]?> / <?=$rr[bank_nickname]?></option>
										<?}?>
									</select>
									</td>
								<td width="5%">&nbsp;&nbsp;&nbsp;* 보수표</td>
								<td width=100px><select name="bosu_gubun" id="bosu_gubun" style="width:100px;">
										<option value="">--구분--</option>
											<option value="1" <?if($row[bosu_gubun]=="1"){?>selected<?}?>>구보수표</option>
											<option value="2" <?if($row[bosu_gubun]=="2"){?>selected<?}?>>신보수표</option>
									</select></td>
							</tr>
							</table>
	<!--tab 시작-->
					&nbsp;&nbsp;<button type="button" class="btn btn-success" onclick="javascript:f_setting_search();"  style="background-color:#F29661;">설정값조회</button>
					<br><br>

			        <ul class="nav nav-tabs">
			            <li class="active">
			                <a href="#tab_1_1" data-toggle="tab"> &nbsp;&nbsp;&nbsp;기&nbsp;&nbsp;본&nbsp;&nbsp;&nbsp; </a>
			            </li>
			            <li>
			                <a href="#tab_1_2" data-toggle="tab"> &nbsp;&nbsp;&nbsp;국토부&nbsp;&nbsp;&nbsp; </a>
			            </li>
			        </ul>
		        	<div class="tab-content">
		            <div class="tab-pane fade active in" id="tab_1_1">
									<table style="width:90%;margin:10px;border:1px solid whtie;" border=1>
									<tr>
										<td width="5%" align=center rowspan=8 style="background-color:#6799FF;color:white">법<br>무<br>사<br>보<br>수<br>료</td>
										<td width="20%" align=center style="background-color:#47C83E;color:white">구분</td>
										<td width="25%" align=center style="background-color:#47C83E;color:white">비용</td>
						
										<td width="5%" align=center rowspan=8 style="background-color:#6799FF;color:white">공<br>과<br>금</td>
										<td width="20%" align=center style="background-color:#47C83E;color:white">구분</td>
										<td width="25%" align=center style="background-color:#47C83E;color:white">비용</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;기본보수료</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="b_basic_bosu" maxlength=20 style="text-align:right;border:solid 1px red;" value="<?=f_money($b_rate[b_basic_bosu])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원</td>
										<td>&nbsp;&nbsp;&nbsp;증지대(공)</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="g_jungjidae" maxlength=20 style="text-align:right;border:solid 1px red;" value="<?=f_money($b_rate[g_jungjidae])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원</td>
									</tr>
									<tr>
										<td rowspan=3>&nbsp;&nbsp;&nbsp;누진보수료(수식입력)</td>
										<td align=center><b>하단 은행별 개별 수식</b></td>
										<td>&nbsp;&nbsp;&nbsp;등록세신고납부대행(공)</td>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=g_singonabbu_ch value="y" <?if($b_rate[g_singonabbu_ch]=="y"){?>checked<?}?>>&nbsp;&nbsp;&nbsp;1회청구&nbsp;&nbsp;
											&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="g_singonabbu" maxlength=20 style="text-align:right;width:65%;" value="<?=f_money($b_rate[g_singonabbu])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원
										</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;절삭위치&nbsp;&nbsp;&nbsp;&nbsp;<select name="b_julsak_position"  id="b_julsak_position"  style="width:74%;border:solid 1px red;">
													<option <?if($b_rate[b_julsak_position]=="0"){?>selected<?}?> value="0">절삭없음</option>
													<option <?if($b_rate[b_julsak_position]=="1"){?>selected<?}?> value="1">원단위절삭</option>
													<option <?if($b_rate[b_julsak_position]=="2"){?>selected<?}?> value="2">십원단위절삭</option>
											</select></td>
										<td>&nbsp;&nbsp;&nbsp;교통비(공)</td>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=g_kyotong_ch value="y"  <?if($b_rate[g_kyotong_ch]=="y"){?>checked<?}?>>&nbsp;&nbsp;&nbsp;1회청구&nbsp;&nbsp;
											&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="g_kyotong" maxlength=20 style="text-align:right;width:65%;" value="<?=f_money($b_rate[g_kyotong])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원
										</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;할인율&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text"  name="b_halin" maxlength=3  value="<?=$b_rate[b_halin]?>" style="width:71%;text-align:right;border:solid 1px red;" onkeyup="onlyNum(this);this.value=this.value.comma();"/>%</td>
										<td>&nbsp;&nbsp;&nbsp;제증명(공)</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="g_jejungmyung" maxlength=20 style="text-align:right;" value="<?=f_money($b_rate[g_jejungmyung])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;등록세신고납부대행</td>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=b_singonabbu_ch value="y"  <?if($b_rate[b_singonabbu_ch]=="y"){?>checked<?}?>>&nbsp;&nbsp;&nbsp;1회청구&nbsp;&nbsp;
											&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="b_singonabbu" maxlength=20 style="text-align:right;width:65%;" value="<?=f_money($b_rate[b_singonabbu])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원
										</td>
										<td>&nbsp;&nbsp;&nbsp;열람증지대(우리공)</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="g_yeolamjunggi" maxlength=20 style="text-align:right;" value="<?=f_money($b_rate[g_yeolamjunggi])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;교통비</td>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=b_kyotong_ch value="y"  <?if($b_rate[b_kyotong_ch]=="y"){?>checked<?}?>>&nbsp;&nbsp;&nbsp;1회청구&nbsp;&nbsp;
												&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="b_kyotong" maxlength=20 style="text-align:right;width:65%;" value="<?=f_money($b_rate[b_kyotong])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원
										</td>
										<td>&nbsp;&nbsp;&nbsp;등초본발급(공)</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="g_deungchobon" maxlength=20 style="text-align:right;" value="<?=f_money($b_rate[g_deungchobon])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;원인증서작성료</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="b_woninjungseo" maxlength=20 style="text-align:right;" value="<?=f_money($b_rate[b_woninjungseo])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원</td>
										<td>&nbsp;&nbsp;&nbsp;지배인초본발급(하나공)</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="g_jibaeinchobon" maxlength=20 style="text-align:right;" value="<?=f_money($b_rate[g_jibaeinchobon])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원</td>
									</tr>
						
									<tr>
										<td colspan=2 style="background-color:#6799FF;color:white;text-align:center;">누진보수료(확인용)</td>
										<td colspan=4>
											&nbsp;&nbsp;&nbsp;<textarea rows=4 class="span11" name="b_nujin_bosu" ><?=$b_rate[b_nujin_bosu]?></textarea>
										</td>
									</tr>
									</table>
		            </div>
		            <div class="tab-pane fade" id="tab_1_2">
									<table style="width:90%;margin:10px;border:1px solid whtie;" border=1>
									<tr>
										<td width="5%" align=center rowspan=8 style="background-color:#6799FF;color:white">법<br>무<br>사<br>보<br>수<br>료</td>
										<td width="20%" align=center style="background-color:#47C83E;color:white">구분</td>
										<td width="25%" align=center style="background-color:#47C83E;color:white">비용</td>
							
										<td width="5%" align=center rowspan=8 style="background-color:#6799FF;color:white">공<br>과<br>금</td>
										<td width="20%" align=center style="background-color:#47C83E;color:white">구분</td>
										<td width="25%" align=center style="background-color:#47C83E;color:white">비용</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;기본보수료</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="b_basic_bosu_1" maxlength=20 style="text-align:right;border:solid 1px red;" value="<?=f_money($g_rate[b_basic_bosu])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원</td>
										<td>&nbsp;&nbsp;&nbsp;증지대(공)</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="g_jungjidae_1" maxlength=20 style="text-align:right;border:solid 1px red;" value="<?=f_money($g_rate[g_jungjidae])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원</td>
									</tr>
									<tr>
										<td rowspan=3>&nbsp;&nbsp;&nbsp;누진보수료(수식입력)</td>
										<td align=center><b>하단 은행별 개별 수식</b></td>
										<td>&nbsp;&nbsp;&nbsp;등록세신고납부대행(공)</td>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=g_singonabbu_ch_1 value="y" <?if($g_rate[g_singonabbu_ch]=="y"){?>checked<?}?>>&nbsp;&nbsp;&nbsp;1회청구&nbsp;&nbsp;
												&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="g_singonabbu_1" maxlength=20 style="text-align:right;width:65%;" value="<?=f_money($g_rate[g_singonabbu])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원
										</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;절삭위치&nbsp;&nbsp;&nbsp;&nbsp;<select name="b_julsak_position_1"  id="b_julsak_position_1"  style="width:74%;border:solid 1px red;">
													<option <?if($g_rate[b_julsak_position]=="0"){?>selected<?}?> value="0">절삭없음</option>
													<option <?if($g_rate[b_julsak_position]=="1"){?>selected<?}?> value="1">원단위절삭</option>
													<option <?if($g_rate[b_julsak_position]=="2"){?>selected<?}?> value="2">십원단위절삭</option>
											</select></td>
										<td>&nbsp;&nbsp;&nbsp;교통비(공)</td>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=g_kyotong_ch_1 value="y"  <?if($g_rate[g_kyotong_ch]=="y"){?>checked<?}?>>&nbsp;&nbsp;&nbsp;1회청구&nbsp;&nbsp;
												&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="g_kyotong_1" maxlength=20 style="text-align:right;width:65%;" value="<?=f_money($g_rate[g_kyotong])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원
										</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;할인율&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text"  name="b_halin_1" maxlength=3  value="<?=$g_rate[b_halin]?>" style="width:71%;text-align:right;border:solid 1px red;" onkeyup="onlyNum(this);this.value=this.value.comma();"/>%</td>
										<td>&nbsp;&nbsp;&nbsp;제증명(공)</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="g_jejungmyung_1" maxlength=20 style="text-align:right;" value="<?=f_money($g_rate[g_jejungmyung])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;등록세신고납부대행</td>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=b_singonabbu_ch_1 value="y"  <?if($g_rate[b_singonabbu_ch]=="y"){?>checked<?}?>>&nbsp;&nbsp;&nbsp;1회청구&nbsp;&nbsp;
												&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="b_singonabbu_1" maxlength=20 style="text-align:right;width:65%;" value="<?=f_money($g_rate[b_singonabbu])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원
										</td>
										<td>&nbsp;&nbsp;&nbsp;열람증지대(우리공)</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="g_yeolamjunggi_1" maxlength=20 style="text-align:right;" value="<?=f_money($g_rate[g_yeolamjunggi])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;교통비</td>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=b_kyotong_ch_1 value="y"  <?if($g_rate[b_kyotong_ch]=="y"){?>checked<?}?>>&nbsp;&nbsp;&nbsp;1회청구&nbsp;&nbsp;
												&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="b_kyotong_1" maxlength=20 style="text-align:right;width:65%;" value="<?=f_money($g_rate[b_kyotong])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원
										</td>
										<td>&nbsp;&nbsp;&nbsp;등초본발급(공)</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="g_deungchobon_1" maxlength=20 style="text-align:right;" value="<?=f_money($g_rate[g_deungchobon])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;원인증서작성료</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="b_woninjungseo_1" maxlength=20 style="text-align:right;" value="<?=f_money($g_rate[b_woninjungseo])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원</td>
										<td>&nbsp;&nbsp;&nbsp;지배인초본발급(하나공)</td>
										<td>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="g_jibaeinchobon_1" maxlength=20 style="text-align:right;" value="<?=f_money($g_rate[g_jibaeinchobon])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>원</td>
									</tr>
							
									<tr>
										<td colspan=2 style="background-color:#6799FF;color:white;text-align:center;">누진보수료(확인용)</td>
										<td colspan=4>
											&nbsp;&nbsp;&nbsp;<textarea rows=4 class="span11" name="b_nujin_bosu_1" ><?=$g_rate[b_nujin_bosu]?></textarea>
										</td>
									</tr>
									</table>
		            </div>
		
		          </div>
		        </div>
		        <br>
						<div style="text-align:center;" >
							<?if($mode=="e"){?>
							<b style="color:red;"><b>* 지점비율 변경시 설정-누진보수료/보수료/공과금이 갱신됩니다.</b><br><br>
							<?}?>
							<button type="button" class="btn btn-success" onclick="javascript:f_submit();">저장</button>&nbsp;&nbsp;&nbsp;&nbsp;
							<button type="button" class="btn btn-success" onclick="javascript:location.href='index.html?h_idx=<?=$s_h_idx?>&bank_code=<?=$s_bank_code?>&jijum_code=<?=$s_jijum_code?>&gubun_code=<?=$s_gubun_code?>';">목록 이동</button>
						</div> 
						<br>
        		</form>
      		</div>
      	</div>
    	</div>
		</div>
  </div>
</div>

<!--bottom-시작-->
	<?include ("../../include/bottom.php");?>
<!--bottom-종료-->

<script src="/js/common.js"></script> 
<script src="/js/jquery.min.js"></script> 
<script src="/js/jquery.ui.custom.js"></script> 
<script src="/js/bootstrap.min.js"></script> 
<script src="/js/jquery.dataTables.min.js"></script> 
<script src="/js/maruti.js"></script> 

<script>
function f_setting_search(){
	var url    ="popup_setting.php";
	var title  = "listpoph";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1500, height=900, top=0,left=20"; 
	var a = window.open(url, title,status);
	a.focus();
}
/* index.html?h_idx=<?=$row[h_idx]?>&bank_code=<?=$row[bank_code]?>&jijum_code=<?=$row[jijum_code]?>&gubun_code=<?=$row[gubun_code]?> */
function f_submit(){
	var v = document.ff;
	if(v.h_idx.value==""){
		alert("현장코드는 필수입니다.");
	}else if(v.bank_code.value==""){
		alert("은행코드는 필수입니다.");
	}else if(v.jijum_code.value==""){
		alert("지점코드는 필수입니다.");
	}else if(v.gubun_code.value==""){
		alert("구분은 필수입니다.");
	}else if(v.account_code.value==""){
		alert("계좌는 필수입니다.");
	}else if(v.bosu_gubun.value==""){
		alert("보수표는 필수입니다.");
	}else{
		<?if($mode=="e"){?>
			//alert(v.jijum_code.value);
		if(confirm("지점비율 변경시 설정-누진보수료/보수료/공과금이 갱신됩니다.")){
			v.submit();
		}
		<?}else{?>
			
			v.submit();
		<?}?>

	}
}

</script>

</body>
</html>
