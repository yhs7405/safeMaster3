<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"board_faq";

	$board_uid	=	55;

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


  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">게시판</a> <a href="#" class="current">FAQ</a> </div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">

      <div class="span6" style="width:98%;height:95%;">
        <div class="widget-box">
          <div class="widget-content nopadding">

            <div class="control-group" style="height:300px;">

            <form name="ff" action="post.php" method="post" class="form-horizontal" onsubmit="return f_submit();" enctype="multipart/form-data">

		<input type="hidden" name="board_uid" id="board_uid" value="<?=$board_uid?>">

		<table  style="width:98%;margin:10px;">
		<tr>
			<td width="100%" style="font-weight:bold;"><?=trim($row[board_subject])?></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td><?=$row[board_note]?></td>
		</tr>
		</table>

            </form>
          </div>


        </div>
      </div>
    </div>
  </div>
