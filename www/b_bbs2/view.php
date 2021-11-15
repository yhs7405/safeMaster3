<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"board_data";

	$board_uid	=	trim($_REQUEST[board_uid]);

	if($board_uid==""){
		$mode="i";  //insert 신규
		$wherequery = " where 1=1  ";
	}else{
		$mode="e";  //edit 수정
		$wherequery = " where board_uid={$board_uid}  ";

		$sql= "select * from $board_dbname $wherequery ";
		$row = db_query_fetch($sql);

		$sql= "update $board_dbname set board_hit=board_hit+1 $wherequery ";
		db_query($sql);
	}

	$filenum = 5;
?>

<!DOCTYPE html>
<html lang="kr">

<head>
<title>재무돌이</title>
<?include ("../include/common.php");?>
</head>

<body>

<!--header 시작-->
	<?include ("../include/header.php");?>
<!--header 종료-->


<!--top-메뉴시작-->
	<?include ("../include/header_menu.php");?>
<!--top-메뉴종료-->



<div id="content">

  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">게시판</a> <a href="#" class="current">자료실</a> </div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">

      <div class="span6" style="width:98%;">
        <div class="widget-box">
          <div class="widget-content nopadding">

            <div class="control-group">

            <form name="ff" action="post.php" method="post" class="form-horizontal" onsubmit="return f_submit();" enctype="multipart/form-data">

		<input type="hidden" name="board_uid" id="board_uid" value="<?=$board_uid?>">

		<table  style="width:98%;margin:10px;">
		<tr>
			<td width="100%"><?=trim(stripslashes($row[board_subject]))?></td>
		</tr>
		<tr>
			<td><?=stripslashes($row[board_note])?></td>
		</tr>

<?if($filenum > 0){?>

<?$SavePath = "../../files/UploadFiles";
for($K = 1; $K <= $filenum; $K++){?>

		<tr>
		  <td>
		<?	$temp_picture = "UpFile" . $K;
			$imx  = explode("|",urldecode($row["UpFile_Name".$K]));
		?>
			<?if($row["UpFile".$K]!=""){?>
			<a href="/files/UploadFiles/<?=urldecode($row["UpFile".$K]);?>" target="_blank"><?=$imx[0]?>&nbsp;&nbsp;|&nbsp;&nbsp;<?=intval($imx[1]/1024)?> KB</a>&nbsp;&nbsp;<br>
			<?}?>
		  </td>
		</tr>
<?}?>
<?}?>
		</table>

            </form>
          </div>


        </div>
      </div>
    </div>

  </div>
</div>


	<div style="text-align:center;" >

		<?if($_SESSION["admin_permission"][ch_b12]=="y"){?>
			<button type="button" class="btn btn-success" onclick="javascript:location.href='regist.php?board_uid=<?=$board_uid?>';">수정하기</button>
		<?}?>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<button type="button" class="btn btn-success" onclick="javascript:location.href='index.html';">목록 이동</button>
	</div>
	<br>


<!--bottom-시작-->
	<?include ("../include/bottom.php");?>
<!--bottom-종료-->

<script src="/js/jquery.min.js"></script> 
<script src="/js/jquery.ui.custom.js"></script> 
<script src="/js/bootstrap.min.js"></script> 
<script src="/js/jquery.dataTables.min.js"></script> 
<script src="/js/maruti.js"></script> 


<script type="text/javascript" src="/lib/smart_editor/js/HuskyEZCreator.js" ></script>
<script>
	var editor = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef : editor,
		elPlaceHolder : "board_note",
		sSkinURI : "/lib/smart_editor/SmartEditor2Skin.html",
		fCreator : "createEditor2"
	});
function f_delete(){
	if(confirm("삭제하시겠습니까?")){
		location.href="delete.php?board_uid=<?=$board_uid?>";
	}
}
function f_submit(){
	editor.getById["board_note"].exec("UPDATE_CONTENTS_FIELD", []); //SmartEditor Update
	var v = document.ff;
		if(v.board_subject.value==""){
			alert("제목을 확인하세요.");
			v.board_subject.focus();
			return false;	
		}else if(v.board_note.value==""){
			alert("내용을 입력하세요.");
			v.board_note.focus();
			return false;
		}else{
			return true;
		}
}
</script>

</body>
</html>
