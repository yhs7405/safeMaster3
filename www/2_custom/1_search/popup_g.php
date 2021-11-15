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
	$r = $stmt1->fetch();

	$sql = "select * from tbl_suljung where a1='{$a1}' and suljung_no=1 ";
	$r1 = db_query_value($sql);

	$sql = "select * from tbl_suljung where a1='{$a1}' and suljung_no=2 ";
	$r2 = db_query_value($sql);

	$sql = "select * from tbl_suljung where a1='{$a1}' and suljung_no=3 ";
	$r3 = db_query_value($sql);

	$sql = "select * from tbl_suljung where a1='{$a1}' and suljung_no=4 ";
	$r4 = db_query_value($sql);
	//print_r($row1);

?>

<!DOCTYPE html>
<html lang="kr">

<head>
<title>CS돌이</title>
<meta name="viewport" content="width=500px;">
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
	.top_box th{ text-align:left;font-size:8pt;}
	.table th{font-size:8pt;}
	.table td{font-size:8pt;}
	.table input{font-size:8pt;}
	.table textarea{font-size:8pt;}
</style>

<div id="content"  >


			<form name=ff action="popup_g_proc.php" method=post>
			<input type=hidden name=a1 value="<?=$a1?>">

			<div class="widget-content nopadding" style="margin-top:-70px;">

				<div class="control-group">
					<div id="breadcrumb" style="text-align:center;">기본상세조회</div>
				<div class="widget-box">
				  <div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
					  <thead>
						<tr align=center>
						  <td style="text-align:center;text-align:center;text-align:center;background-color:#B7F0B1;" rowspan=2 width="50px" align=center><br>기본정보</td>
						  <td style="text-align:center;">고객고유정보</td>
						  <td style="text-align:center;">단지명</td>
						  <td style="text-align:center;">회원여부</td>
						  <td style="text-align:center;background-color:#e46c0a;color:white;">등기접수일</td>
						  <td style="text-align:center;">전매/증여</td>
						  <td style="text-align:center;">공유</td>
						  <td style="text-align:center;">타입-1</td>
						  <td style="text-align:center;">동</td>
						  <td style="text-align:center;">호</td>
						  <td style="text-align:center;">정산비고</td>
						</tr>
						<tr class="odd gradeX">
						  <td style="text-align:center;"><?=$r[a1]?></td>
						  <td style="text-align:center;"><?=$r[b1]?></td>
						  <td style="text-align:center;"><input type=text name=c1 value="<?=$r[c1]?>" style="width:60px;"></td>
						  <td style="text-align:center;"><input type=text name=g1 value="<?=$r[g1]?>" class="datepickx" size=8 maxlength=8 style="width:70px;height:20px;"></td>
						  <td style="text-align:center;"><input type=text name=u1 value="<?=$r[u1]?>"  style="width:60px;"></td>
						  <td style="text-align:center;"><input type=text name=v1 value="<?=$r[v1]?>"  style="width:60px;"></td>
						  <td style="text-align:center;"><input type=text name=w1 value="<?=$r[w1]?>"  style="width:60px;"></td>
						  <td style="text-align:center;"><?=$r[h1]?></td>
						  <td style="text-align:center;"><?=$r[i1]?></td>
						  <td style="text-align:center;"><textarea name=ae1 cols=70 rows=1><?=$r[ae1]?></textarea></td>
						</tr>
					  </thead>
					</table>
				  </div>
				</div>

				<div class="control-group">
				<div class="widget-box">
				  <div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
					  <thead>
						<tr>
						  <td style="text-align:center;text-align:center;text-align:center;background-color:#B7F0B1;" rowspan=3 width="50px" align=center><br>취득자<br>정보</td>
						  <td style="text-align:center;" colspan=3>취득자1</td>
						  <td style="text-align:center;" colspan=3>취득자2</td>
						  <td style="text-align:center;" rowspan=2><br>배우자</td>
						  <td style="text-align:center;" rowspan=2><br>전화번호</td>
						  <td style="text-align:center;" rowspan=2><br>CS비고</td>
						</tr>
						<tr>
						  <td style="text-align:center;">이름</td>
						  <td style="text-align:center;">주민번호</td>
						  <td style="text-align:center;">주소</td>
						  <td style="text-align:center;">이름</td>
						  <td style="text-align:center;">주민번호</td>
						  <td style="text-align:center;">주소</td>
						</tr>
						<tr class="odd gradeX">
						  <td style="text-align:center;"><input type=text name=j1 value="<?=$r[j1]?>"  style="width:50px;"></td>
						  <td style="text-align:center;"><input type=text name=k1 value="<?=$r[k1]?>"  style="width:90px;"></td>
						  <td style="text-align:center;"><textarea  name=l1  cols=50 rows=1><?=$r[l1]?></textarea></td>
						  <td style="text-align:center;"><input type=text name=m1 value="<?=$r[m1]?>"  style="width:50px;"></td>
						  <td style="text-align:center;"><input type=text name=n1 value="<?=$r[n1]?>"  style="width:90px;"></td>
						  <td style="text-align:center;"><textarea  name=o1  cols=50 rows=1><?=$r[o1]?></textarea></td>
						  <td style="text-align:center;"><input type=text name=s1 value="<?=$r[s1]?>"  style="width:50px;"></td>
						  <td style="text-align:center;"><input type=text name=p1 value="<?=$r[p1]?>"  style="width:90px;"></td>
						  <td style="text-align:center;"><textarea   name=ad1 cols=60 rows=1><?=$r[ad1]?></textarea></td>
						</tr>
					  </thead>
					</table>
				  </div>
				</div>


				<div class="control-group">
				<div class="widget-box">
				  <div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
					  <thead>
						<tr>
						  <td style="text-align:center;text-align:center;text-align:center;background-color:#B7F0B1;" rowspan=2 width="50px" align=center><br>취득정보</td>
						  <td style="text-align:center;">취득과세표</td>
						  <td style="text-align:center;">시가표준액</td>
						  <td style="text-align:center;">취득세입금일(년월일시분)</td>
						  <td style="text-align:center;">입금금액</td>
						  <td style="text-align:center;">채권최고액1</td>
						  <td style="text-align:center;">채권최고액2</td>
						  <td style="text-align:center;">채권최고액3</td>
						  <td style="text-align:center;">채권최고액4</td>
						</tr>
						<tr class="odd gradeX">
						  <td style="text-align:center;"><input type=text name=af1 value="<?=f_money($r[af1])?>" style="width:80px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>
						  <td style="text-align:center;"><input type=text name=ag1 value="<?=f_money($r[ag1])?>" style="width:80px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>
						  <td style="text-align:center;"><input type=text name=ah1 value="<?=$r[ah1]?>" style="width:120px;"></td>

						  <td style="text-align:center;"><input type=text name=ai1 value="<?=f_money($r[ai1])?>" style="width:80px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>

						  <td style="text-align:center;"><?=f_money($r1[chaekwon_max])?></td>
						  <td style="text-align:center;"><?=f_money($r2[chaekwon_max])?></td>
						  <td style="text-align:center;"><?=f_money($r3[chaekwon_max])?></td>
						  <td style="text-align:center;"><?=f_money($r4[chaekwon_max])?></td>
						</tr>
					  </thead>
					</table>
				  </div>
				</div>


				<div class="control-group">
				<div class="widget-box">
				  <div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
					  <thead>
						<tr>
						  <td style="text-align:center;text-align:center;text-align:center;background-color:#B7F0B1;" rowspan=2 width="50px" align=center>납부세금<br>정보</td>
						  <td style="text-align:center;">취득세납부</td>
						  <td style="text-align:center;">취득세합계</td>
						  <td style="text-align:center;">신탁말소등록세</td>
						  <td style="text-align:center;">인지갯수</td>
						  <td style="text-align:center;">인지금액</td>
						  <td style="text-align:center;">증지세</td>
						  <td style="text-align:center;">제증명</td>
						</tr>
						<tr class="odd gradeX">
						  <td style="text-align:center;"><input type=text name=r1 value="<?=$r[r1]?>" class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"></td>
						  <td style="text-align:center;"><input type=text name=al1 value="<?=f_money($r[al1])?>" style="width:100px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>
						  <td style="text-align:center;"><input type=text name=ao1 value="<?=f_money($r[ao1])?>" style="width:100px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>
						  <td style="text-align:center;"><input type=text name=t1 value="<?=f_money($r[t1])?>" style="width:100px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>
						  <td style="text-align:center;"><input type=text name=am1 value="<?=f_money($r[am1])?>" style="width:100px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>
						  <td style="text-align:center;"><input type=text name=an1 value="<?=f_money($r[an1])?>" style="width:100px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>
						  <td style="text-align:center;"><input type=text name=ap1 value="<?=f_money($r[ap1])?>" style="width:100px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>
						</tr>
					  </thead>
					</table>
				  </div>
				</div>


				<div class="control-group">
				<div class="widget-box">
				  <div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
					  <thead>
						<tr>
							<td style="text-align:center;text-align:center;text-align:center;background-color:#B7F0B1;" rowspan=3 width="50px" align=center><br>채권<br>정보</td> 
						  <td style="text-align:center;" colspan=3>소유권이전</td>
						  <td style="text-align:center;" colspan=5>근저당권설정</td>
						</tr>
						<tr>
						  <td style="text-align:center;">채권번호1</td>
						  <td style="text-align:center;">채권번호2</td>
						  <td style="text-align:center;">본인부담액합계</td>
						  <td style="text-align:center;">채권번호1</td>
						  <td style="text-align:center;">채권번호2</td>
						  <td style="text-align:center;">채권번호3</td>
						  <td style="text-align:center;">채권번호4</td>
						  <td style="text-align:center;">본인부담액합계</td>
						</tr>
						<tr class="odd gradeX">
						  <td style="text-align:center;"><input type=text name=x1 value="<?=$r[x1]?>" style="width:110px;text-align:right;"></td>
						  <td style="text-align:center;"><input type=text name=y1 value="<?=$r[y1]?>" style="width:110px;text-align:right;"></td>
						  <td style="text-align:center;"><input type=text name=aj1 value="<?=f_money($r[aj1])?>" style="width:80px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>
						  <td style="text-align:center;"><input type=text name=ccc1 value="<?=$r1[chaekwon_no]?>" style="width:110px;text-align:right;"></td>
						  <td style="text-align:center;"><input type=text name=ccc2 value="<?=$r2[chaekwon_no]?>" style="width:110px;text-align:right;"></td>
						  <td style="text-align:center;"><input type=text name=ccc3 value="<?=$r3[chaekwon_no]?>" style="width:110px;text-align:right;"></td>
						  <td style="text-align:center;"><input type=text name=ccc4 value="<?=$r4[chaekwon_no]?>" style="width:110px;text-align:right;"></td>
						  <td style="text-align:center;"><input type=text name=ak1 value="<?=f_money($r[ak1])?>" style="width:100px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>
						</tr>
					  </thead>
					</table>
				  </div>
				</div>


				<div class="control-group">
				<div class="widget-box">
				  <div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
					  <thead>
						<tr>
							<td style="text-align:center;text-align:center;text-align:center;background-color:#B7F0B1;" rowspan=3 width="50px" align=center><br>보수료<br>정보</td> 
						  <td style="text-align:center;" colspan=2>소유권이전등기</td>
						  <td style="text-align:center;" colspan=2>신탁말소등기</td>
						  <td style="text-align:center;background-color:#D99694;color:white;vertical-align:middle;" rowspan=2><br>보수료합계</td>
						  <td style="text-align:center;background-color:#0070C0;color:white;vertical-align:middle;" rowspan=2><br>공과금합계</td>
						  <td style="text-align:center;background-color:#C4BD97;color:white;vertical-align:middle;" rowspan=2>비용합계<br>보수료+공과금</td>
						  <td style="text-align:center;background-color:#C4BD97;color:white;vertical-align:middle;" rowspan=2>환불금<br>입금-비용합계</td>
						  <td style="text-align:center;" rowspan=2><br>환불비고</td>
						</tr>
						<tr>
						  <td style="text-align:center;">보수료</td>
						  <td style="text-align:center;">부가세</td>
						  <td style="text-align:center;">보수료</td>
						  <td style="text-align:center;">부가세</td>
						</tr>

						<!--보수료합계 = 소유권이전등기 +vat+신탁말소등기+vat//-->
						<!--공과금합계 =  취득세합계, 신탁말소등록세, 인지세, 증지세, 제증명, 이전본인부담액합계, 설정본인부담액합계 //-->
						<!--비용합계 = 보수료 + 공과금//-->
						<!--환불금 = 입금금액 - (보수료합계+비용합계)//-->
						<?
							$bos_total = intval($r[aq1])+intval($r[ar1])+intval($r[as1])+intval($r[at1]);
							$gonga_total = intval($r[al1])+intval($r[ao1])+intval($r[am1])+intval($r[an1])+intval($r[ap1])+intval($r[aj1])+intval($r[ak1]);;
							$biyong_total = $bos_total + $gonga_total;
							$refund_total = intval($r[ai1]) - ($biyong_total);
						?>

						<tr class="odd gradeX">
						  <td style="text-align:center;"><input type=text name=aq1 value="<?=f_money($r[aq1])?>" style="width:100px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>
						  <td style="text-align:center;"><input type=text name=ar1 value="<?=f_money($r[ar1])?>" style="width:100px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>
						  <td style="text-align:center;"><input type=text name=as1 value="<?=f_money($r[as1])?>" style="width:100px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>
						  <td style="text-align:center;"><input type=text name=att1 value="<?=f_money($r[at1])?>" style="width:100px;text-align:right;" onkeyup="onlyNum(this);this.value=this.value.comma();" ></td>
						  <td style="text-align:center;"><?=f_money($bos_total)?></td> <!--보수료합계 = 소유권이전등기 +신탁말소등기//-->
						  <td style="text-align:center;"><?=f_money($gonga_total)?></td> <!--공과금합계 =  취득세합계, 신탁말소등록세, 인지세, 증지세, 제증명, 이전본인부담액합계, 설정본인부담액합계 //-->
						  <td style="text-align:center;"><?=f_money($biyong_total)?></td> <!--비용합계 = 보수료 + 공과금//-->
						  <td style="text-align:center;"><?=f_money($r[refund_fee])?></td> <!--환불금 = 입금금액 - (보수료합계+비용합계)//-->
						  <td style="text-align:center;"><textarea style="width:200px;" name=refund_memo  cols=50 rows=1><?=$r[refund_memo]?></textarea></td>
						</tr>
					  </thead>
					</table>
				  </div>
				</div>
				
				<div class="control-group">
				<div class="widget-box">
				  <div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
					  <thead>
						<tr>
						  <td style="text-align:center;text-align:center;text-align:center;background-color:#B7F0B1;" rowspan=2 width="50px" align=center><br>비고<br>정보</td>
						  <td style="text-align:center;">재무돌이비고</td>
						  <td style="text-align:center;">은행비고</td>
						</tr>
						<tr class="odd gradeX">
						  <td style="text-align:center;"><textarea style="width:500px;" name=ijp_s_memo  cols=150 rows=3><?=$r[ijp_s_memo]?></textarea></td>
						  <td style="text-align:center;"><textarea style="width:500px;" name=au1  cols=150 rows=3><?=$r[au1]?></textarea></td>
						</tr>
					  </thead>
					</table>
				  </div>
				</div>

				<div style="text-align:center;" >

<?if($_SESSION["admin_permission"][ch_222]=="y"){?>
					<button type="button" class="btn btn-success" onclick="javascript:f_submit();">저장</button>&nbsp;&nbsp;
<?}?>

<?if($_SESSION["admin_id"]=="master"){?>
					<button type="button" class="btn btn-success" onclick="javascript:f_delete();">삭제</button>&nbsp;&nbsp;
<?}?>

					<button type="button" class="btn btn-success" onclick="javascript:window.close();">창닫기</button>
				</div>

			</div>
			</form>

</div>



<script src="/js/common.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script>

function f_delete(){
	if(confirm("삭제하시겠습니까?")){
		location.href="/2_custom/1_search/delete_ok.php?a1="+encodeURI("<?=$a1?>");
	}
}

function f_submit(){
	if(confirm("저장하시겠습니까?")){
/*
		$user_id =  $_SESSION["admin_id"];
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$regist_date = date("YmdHis");
		$sql="insert into tbl_login_user values('{$regist_date}','{$id}','N','{$user_ip}','저장','2_custom/1_search/popup_g.php','{$a1}') ";  //로그정보
		//echo $sql;
		db_query($sql);
*/
		document.ff.submit();
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

</script>

</body>
</html>
