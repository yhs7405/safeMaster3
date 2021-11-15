<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_hyunjang_info";

	$h_idx		=	trim($_REQUEST[h_idx]);

	if($h_idx==""){
		$mode="i";  //insert 신규
		$wherequery = " where 1=1  ";
	}else{
		$mode="e";  //edit 수정

		$sql= "select * from $board_dbname where h_idx='{$h_idx}' ";
		//echo $sql;
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$row = $stmt->fetch();
	}
?>

<!DOCTYPE html>
<html lang="kr">

<head>
<title>재무돌이</title>
<?include ("../../include/common.php");?>
</head>

<body>

<!--header 시작-->
	<?include ("../../include/header.php");?>
<!--header 종료-->


<!--top-메뉴시작-->
	<?include ("../../include/header_menu.php");?>
<!--top-메뉴종료-->

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

function f_trust_doc(frm){
	var v = document.ff;
	//alert(frm);
	if(frm=="0"){
		$("#deunggi_cause").val("매매");
		$("#deunggi_pur").val("소유권이전");
	} else {
		$("#deunggi_cause").val("매매 및 신탁재산의 처분");
		$("#deunggi_pur").val("소유권이전 및 신탁등기말소");
	}
}
</script>

<div id="content">

  <div id="content-header">
    <?if($mode=="e"){?>
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">기본정보</a> <a href="#" class="current">현장상세정보 수정</a> </div>
    <?}else{?>
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">기본정보</a> <a href="#" class="current">현장상세정보 등록</a> </div>
    <?}?>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">

      <div class="span6" style="width:98%;">
        <div class="widget-box">
          <div class="widget-content nopadding">

         <form name="ff" action="post.php" method="post" class="form-horizontal" onsubmit="return f_submit();">
			<input type="hidden" name="id_ch" id="id_ch" value="n">
			<input type="hidden" name="mode" id="mode" value="<?=$mode?>">
			<input type="hidden" name="h_idx" id="h_idx" value="<?=$h_idx?>">

		<div class="control-group">

	 		<h4>&nbsp;▷ 기본정보</h4>
			<table style="width:98%;margin:10px;border:1px solid whtie;">
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
				<td>프로젝트코드</td>
				<td><input type="text" class="span11" name="project_code" id="project_code"   value="<?=$row[project_code]?>"/></td>
				<td rowspan=2>거래처특이사항</td>
				<td rowspan=2><textarea rows=4 class="span11" name="etc" id="etc" ><?=$row[etc]?></textarea></td>
			</tr>
			<tr>
				<td>FAQ 게시판 링크</td>
				<td><input type="text" class="span11" name="faq_link" id="faq_link"   value="<?=$row[faq_link]?>"/></td>
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
		</div>
		<div class="control-group">
	 		<h4>&nbsp;▷ 현장정보</h4>
			<table style="width:98%;margin:10px;border:1px solid whtie;">
			<tr>
				<td width="10%">준공일</td>
				<td width="25%"><input type=text name="jungong_date" id="jungong_date" value="<?=$row[jungong_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"></td>
				<td width="7%">보존등기일</td>
				<td width="25%"><input type=text name="bojon_date" id="bojon_date" value="<?=$row[bojon_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"></td>
				<td width="7%">대지권완료여부</td>
				<td width="25%">
					<select name=land_com_yn id="land_com_yn" style="width:30%;">
				  <option value="">--선택--</option>
				  <option value="y" <?if($row[land_com_yn]=="y"){?>selected<?}?>>대지권있음</option> 
				  <option value="n" <?if($row[land_com_yn]=="n"){?>selected<?}?>>대지권없음</option>
				  </select>
				</td>
			</tr>
			<tr>
				<td width="10%">신탁여부</td>
				<td width="25%">
					<select name=trust_gubun id="trust_gubun" onchange="f_trust_doc(this.value);" style="width:30%;">
				  <option value="">--선택--</option>
				  <option value="1" <?if($row[trust_gubun]=="1"){?>selected<?}?>>건물만 신탁</option> 
				  <option value="2" <?if($row[trust_gubun]=="2"){?>selected<?}?>>건물+토지 신탁</option>
				  <option value="0" <?if($row[trust_gubun]=="0"){?>selected<?}?>>신탁없음</option>
				  </select>
				</td>
				<td width="7%">입주지정기간</td>
				<td width="25%">
					<input type=text name="ipju_app_s" id="ipju_app_s" value="<?=$row[ipju_app_s]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"> ~ 
					<input type=text name="ipju_app_e" id="ipju_app_e" value="<?=$row[ipju_app_e]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"> 
				</td>
				<td width="7%">서류접수기간</td>
				<td width="25%">
					<input type=text name="doc_rec_s" id="doc_rec_s" value="<?=$row[doc_rec_s]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"> ~ 
					<input type=text name="doc_rec_e" id="doc_rec_e" value="<?=$row[doc_rec_e]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"> 
				</td>
			</tr>
			<tr>
				<td width="10%">TYPE입력</td>
				<td width="25%"><input type="text" class="span11" name="type_info" id="type_info"   value="<?=$row[type_info]?>" style="width:300px;"/></td>
				<td width="7%">* 지역</td>
				<td width="25%">
					<select name=area_gubun id="area_gubun" style="width:30%;">
				  <option value="">--선택--</option>
				  <option value="1" <?if($row[area_gubun]=="1"){?>selected<?}?>>특광지역</option> 
				  <option value="2" <?if($row[area_gubun]=="2"){?>selected<?}?>>기타지역</option>
				  </select>
				</td>
				<td width="7%">조정지역여부</td>
				<td width="25%" colspan=3>
					<select name=jojeong_yn id="jojeong_yn" style="width:30%;">
				  <option value="">--선택--</option>
				  <option value="y" <?if($row[jojeong_yn]=="y"){?>selected<?}?>>조정지역</option> 
				  <option value="n" <?if($row[jojeong_yn]=="n"){?>selected<?}?>>비조정지역</option>
				  </select>
				</td>
			</tr>
			</table>
		</div>
		<div class="control-group">
	 		<h4>&nbsp;▷ 회원종류정보</h4>
			<table style="width:98%;margin:10px;border:1px solid whtie;">
			<tr>
				<td width="10%">정회원</td>
				<td width="20%">
					<select name=reg_mem_yn id="reg_mem_yn" style="width:30%;">
				  <option value="y" <?if($row[reg_mem_yn]=="y"){?>selected<?}?> <?if($mode=="i"){ ?> selected <?}?>>활성</option> 
				  <option value="n" <?if($row[reg_mem_yn]=="n"){?>selected<?}?>>비활성</option>
				  </select>
				</td>
				<td width="5%">(비)회원</td>
				<td width="20%">
					<select name=non_mem_yn id="non_mem_yn" style="width:30%;">
				  <option value="y" <?if($row[non_mem_yn]=="y"){?>selected<?}?> <?if($mode=="i"){ ?> selected <?}?>>활성</option> 
				  <option value="n" <?if($row[non_mem_yn]=="n"){?>selected<?}?>>비활성</option>
				  </select>
				</td>
				<td width="5%">일반회원</td>
				<td width="20%">
					<select name=gen_mem_yn id="gen_mem_yn" style="width:30%;">
				  <option value="y" <?if($row[gen_mem_yn]=="y"){?>selected<?}?>>활성</option> 
				  <option value="n" <?if($row[gen_mem_yn]=="n"){?>selected<?}?> <?if($mode=="i"){ ?> selected <?}?>>비활성</option>
				  </select>
				</td>
				<td width="5%">웹회원</td>
				<td width="20%">
					<select name=web_mem_yn id="web_mem_yn" style="width:30%;">
				  <option value="y" <?if($row[web_mem_yn]=="y"){?>selected<?}?>>활성</option> 
				  <option value="n" <?if($row[web_mem_yn]=="n"){?>selected<?}?> <?if($mode=="i"){ ?> selected <?}?>>비활성</option>
				  </select>
				</td>
			</tr>
			</table>
		</div>
		<div class="control-group">

	 		<h4>&nbsp;▷ 보수료설정관련 정보</h4>
			<table style="width:98%;margin:10px;border:1px solid whtie;">
			<tr>
				<td width="10%">보수기준일</td>
				<td width="90%" >
					<input type=text name="bosu_stn_date" id="bosu_stn_date" value="<?=$row[bosu_stn_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"> 
				</td>
			</tr>
			</table>
		</div>
		<div class="control-group">

	 		<h4>&nbsp;▷ 위임장 및 신청서용 정보</h4>
			<table style="width:98%;margin:10px;border:1px solid whtie;">
			<tr>
				<td width="10%">등기원인</td>
				<td width="40%"><input type="text" class="span11" name="deunggi_cause" id="deunggi_cause"   value="<?=$row[deunggi_cause]?>" style="width:80%;"/></td>
				<td width="10%">등기의 목적</td>
				<td width="40%"><input type="text" class="span11" name="deunggi_pur" id="deunggi_pur"   value="<?=$row[deunggi_pur]?>" style="width:80%;"/></td>
			</tr>
			</table>

		</div>
		
		<div class="control-group">
			<br>
			<div style="text-align:center;" >
				<button type="button" class="btn btn-success" style="background-color:blue;" onclick="javascript:f_danji(<?=$h_idx?>);">단지추가</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="submit" class="btn btn-success">저장</button>&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="button" class="btn btn-success" onclick="javascript:location.href='index.html';">목록</button>
			</div>
			<br>



		    <table class="table table-bordered table-striped">
		      <thead>
			<tr>
			  <th width="20%">No</th>
			  <th>단지명</th>
			</tr>
		      </thead>
		      <tbody>

		<?

		$sql = "select * from tbl_hyunjang_danji_info where h_idx=$h_idx order by danji_name";
		//echo $sql;
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		$rows = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();
		//echo $rows;

	if($rows > 0){

			$T = 1;
			while($row = $stmt->fetch()){?>

			<tr class="odd gradeX">
				<input type="hidden" name="idx" id="idx" value="<?=$idx?>">
			  <td style="text-align:center;"><a href="javascript:f_danji_sub('<?=$h_idx?>','<?=$row[idx]?>');"><?=$T?></a></td>
			  <td style="text-align:center;"><a href="javascript:f_danji_sub('<?=$h_idx?>','<?=$row[idx]?>');"><?=$row[danji_name]?></a></td>
			</tr>

			<?$T++;}
	}else{?>
		      <tr class="title">
			<td colspan=12 align=center height=100 valign=middle><Br><Br><center>내용이 없습니다.</center></td>
		      </tr>
	<?}?>

		      </tbody>
		    </table>


		</div>

            </form>

        </div>
      </div>
    </div>

  </div>
</div>

<!--bottom-시작-->
	<?include ("../../include/bottom.php");?>
<!--bottom-종료-->

<script src="/js/common.js"></script> 
<script src="/js/bootstrap.min.js"></script> 


<script>
function f_danji(p1){
	//var aa = window.open("/9_basic/941_hyunjang_danji/regist.php?h_idx="+p1,"dfdf","width=1500,height=800","scrollbars=yes");
	var aa = window.open("/9_basic/941_hyunjang_danji/regist.php?h_idx="+p1, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=50,width=1500,height=800");

	aa.focus();
}

function f_danji_sub(p1,p2){
	var aa = window.open("/9_basic/941_hyunjang_danji/regist.php?h_idx="+p1+"&idx="+p2, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=50,width=1500,height=800");
	//var aa = window.open("/9_basic/941_hyunjang_danji/regist.php?h_idx="+p1+"&idx="+p2,"dfdf","width=1500,height=800","scrollbars=yes");
	aa.focus();
}

// 특이사항 기타 처리


function f_submit(){
	var v = document.ff;
	<?if($mode=="i"){?>
		if(v.h_name.value=="n"){
			alert("현장명을 입력하세요.");
			v.h_name.focus();
			return false;
		}else if(v.area_gubun.value=="n"){
			alert("지역을 선택하세요.");
			v.area_gubun.focus();
			return false;
		}else if(v.no_text.value=="n"){
			alert("고유번호용 문구 중복 확인하세요.");
			v.no_text.focus();
			return false;
		}else{
			return true;
		}
	<?}else{?>
		if(v.h_name.value=="n"){
			alert("현장명을 입력하세요.");
			v.h_name.focus();
			return false;
		}else{
			return true;
		}
	<?}?>
}

function f_duplicate(){
	var v1=$("#no_text").val();
	if(v1!=""){
		$.ajax({
			type:"GET",
			url:'./ajax_id.php',
			dataType:"text",
			data:{id:v1},
			timeout : 30000,
			success:function (req) {
				//alert(req);
				if($.trim(req)=="y"){ //중복 아닐때
					alert("사용 가능한 현장문구입니다.");
					$("#id_ch").val("y");
				}else{ //중복 일때
					alert("이미 사용중인 현장문구입니다.");
					$("#id_ch").val("n");
				}
			},
			error : function(request, status, error) {
				//통신 에러 발생시 처리
				//alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
			}
		});
	}else{
		alert("고유번호용문구 입력하세요.");
		$("#no_text").focus();
	}
}

</script>

</body>
</html>
