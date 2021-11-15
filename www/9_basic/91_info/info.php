<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_info";

	$idx = 1;

	$mode="e";  //edit 수정

	$sql= "select * from $board_dbname where idx='{$idx}' ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();
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



<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">기본정보</a> <a href="#" class="current">태율정보</a> </div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">

      <div class="span6" style="width:98%;">
        <div class="widget-box">
          <div class="widget-content nopadding">

            <form name="ff" action="post.php" method="post" class="form-horizontal" onsubmit="return f_submit();">
			
			<input type="hidden" name="mode" id="mode" value="<?=$mode?>">
			<input type="hidden" name="idx" id="idx" value="1">

              <div class="control-group">

				<table  style="width:98%;margin:10px;">
				<tr>
					<td width="10%">상호</td>
					<td width="40%"><input type="text" class="span11" name="sangho"  placeholder="상호"  maxlength=40  value="<?=$row[sangho]?>"/></td>
					<td width="10%">사업자등록번호</td>
					<td width="40%"><input type="text" class="span11" name="saup_no"  placeholder="사업자등록번호"  maxlength=40  value="<?=$row[saup_no]?>"/></td>
				</tr>
				<tr>
					<td width="10%">대표자</td>
					<td width="40%"><input type="text" class="span11" name="ceo"  placeholder="대표자"  maxlength=40  value="<?=$row[ceo]?>"/></td>
					<td width="10%">법무사</td>
					<td width="40%"><input type="text" class="span11" name="bubmusa"  placeholder="법무사"  maxlength=40  value="<?=$row[bubmusa]?>"/></td>
				</tr>
				<tr>
					<td width="10%">주소</td>
					<td width="90%" colspan=3><input type="text" class="span11" name="addr"  placeholder="주소"  maxlength=40  value="<?=$row[addr]?>"/></td>
				</tr>
				<tr>
					<td width="10%">업종</td>
					<td width="40%"><input type="text" class="span11" name="upjong"  placeholder="업종"  maxlength=40  value="<?=$row[upjong]?>"/></td>
					<td width="10%">업태</td>
					<td width="40%"><input type="text" class="span11" name="uptae"  placeholder="업태"  maxlength=40  value="<?=$row[uptae]?>"/></td>
				</tr>
				<tr>
					<td width="10%">전화번호</td>
					<td width="40%"><input type="text" class="span11" name="tel"  placeholder="전화번호"  maxlength=40  value="<?=$row[tel]?>"/></td>
					<td width="10%">팩스번호</td>
					<td width="40%"><input type="text" class="span11" name="fax"  placeholder="팩스"  maxlength=40  value="<?=$row[fax]?>"/></td>
				</tr>
				<tr>
					<td width="10%">이메일</td>
					<td width="40%"><input type="text" class="span11" name="email"  placeholder="이메일"  maxlength=40  value="<?=$row[email]?>"/></td>
					<td width="10%"></td>
					<td width="40%"></td>
				</tr>
				</table>

<?if($_SESSION["admin_permission"][ch_912]=="y"){?>
				<div style="text-align:center;" >
					<button type="submit" class="btn btn-success">저장</button>
				</div>
<?}?>

				<br>
              </div>
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

<script src="/js/jquery.min.js"></script> 
<script src="/js/jquery.ui.custom.js"></script> 
<script src="/js/bootstrap.min.js"></script> 
<script src="/js/jquery.dataTables.min.js"></script> 
<script src="/js/maruti.js"></script> 

<script>
function f_submit(){
	var v = document.ff;
		if(v.sangho.value==""){
			alert("상호를 입력하세요.");
			v.sangho.focus();
			return false;
		}else if(v.saup_no.value==""){
			alert("사업자등록번호를 입력하세요.");
			v.saup_no.focus();
			return false;
		}else if(v.ceo.value==""){
			alert("대표자를 입력하세요.");
			v.ceo.focus();
			return false;
		}else if(v.bubmusa.value==""){
			alert("법무사를 입력하세요.");
			v.bubmusa.focus();
			return false;
		}else if(v.upjong.value==""){
			alert("업종을 입력하세요.");
			v.upjong.focus();
			return false;
		}else if(v.uptae.value==""){
			alert("업태를 입력하세요.");
			v.uptae.focus();
			return false;
		}else if(v.tel.value==""){
			alert("전화번호를 입력하세요.");
			v.tel.focus();
			return false;
		}else if(v.fax.value==""){
			alert("팩스를 입력하세요.");
			v.fax.focus();
			return false;
		}else if(v.email.value==""){
			alert("이메일을 입력하세요.");
			v.email.focus();
			return false;
		}else{
			return true;
		}
}

</script>

</body>
</html>
