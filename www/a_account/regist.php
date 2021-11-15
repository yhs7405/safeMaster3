<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"tbl_user";

	$idx		=	trim($_REQUEST[idx]);
	$s_gubun	=	trim($_REQUEST[s_gubun]);
	$s_search	=	trim($_REQUEST[s_search]);

	$list_num	=	trim($_REQUEST[list_num]);
	$page		=	trim($_REQUEST[page]);
	$view_num	=	trim($_REQUEST[list_num]);	//한라인에 몇개를 출력할건지//
	$Page_List	=	10;				//링크페이지를 몇개씩 보여줄건지.. 1 / 2 / 3
	if(!$view_num)	$view_num=	50;			//리스트 갯수

	if($idx==""){
		$mode="i";  //insert 신규
		$wherequery = " where idx=1245456465  ";
	}else{
		$mode="e";  //edit 수정
		$wherequery = " where idx={$idx}  ";

		$sql= "select * from tbl_user where idx='{$idx}' ";
		//echo $sql;
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$row = $stmt->fetch();

		$sql= "select * from tbl_user_permission where u_idx='{$idx}' ";
		//echo $sql;
		$stmt1 = $pdo->prepare($sql);
		$stmt1->execute();
		$stmt1->setFetchMode(PDO::FETCH_ASSOC);
		$pm = $stmt1->fetch();
	}

	if(($s_gubun!="")&&($s_search!=""))  $wherequery.= " {$s_gubun} like '%{$s_search}%' ";

	$rows_total = db_count_all($board_dbname,$wherequery);

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
    <?if($mode=="e"){?>
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">계정관리</a> <a href="#" class="current">계정수정</a> </div>
    <?}else{?>
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">계정관리</a> <a href="#" class="current">계정등록</a> </div>
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
			<input type="hidden" name="idx" id="idx" value="<?=$idx?>">

            <div class="control-group">

			<table  style="width:98%;margin:10px;">
			<tr>
				<td width="10%">* 아이디</td>
				<td width="40%"><input type="text" class="span11" name="id" id="id" placeholder="아이디" maxlength=12 <?if($mode=="e"){?>readonly<?}?> value="<?=$row[id]?>" style="width:300px;"/><?if($mode=="i"){?>&nbsp;<button type="button" class="btn btn-success" onclick="javascript:f_duplicate();">아이디중복</button><?}?></td>
				<td width="10%">* 이름</td>
				<td width="40%"><input type="text" class="span11" name="name" id="name" placeholder="이름"  maxlength=40  value="<?=$row[name]?>"/></td>
			</tr>
			<tr>
				<td>* 비밀번호</td>
				<td><input type="password" class="span11" name="pwd1" id="pwd1" placeholder="암호"  maxlength=20 /></td>
				<td>* 비밀번호</td>
				<td><input type="password" class="span11" name="pwd2" id="pwd2" placeholder="암호확인"  maxlength=20/></td>
			</tr>
			<tr>
				<td>전화번호</td>
				<td><input type="text" class="span11" name="tel" id="tel" placeholder="전화번호"  maxlength=20  value="<?=$row[tel]?>"/></td>
				<td>이메일</td>
				<td><input type="text" class="span11"name="email" id="email"  placeholder="Ex) love@mypeople.com"  maxlength=50  value="<?=$row[email]?>"/></td>
			</tr>
			<tr>
				<td>* 등급</td>
				<td><select name=grade>
						<option value="100" <?if($row[grade]=="100"){?>selected<?}?>>일반</option>
						<option value="200" <?if($row[grade]=="200"){?>selected<?}?>>관리자</option>
						<option value="300" <?if($row[grade]=="300"){?>selected<?}?>>외주</option>
						<option value="999" <?if($row[grade]=="999"){?>selected<?}?>>퇴사</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* 소속&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<select name="sosok" id="sosok">
						<?$sql = "select * from tbl_sosok";
						$sosok_r = $pdo->prepare($sql);
						$sosok_r->execute();
						while($rr = $sosok_r->fetch()){?>
							<option value="<?=$rr[sosok_code]?>" <?if($rr[sosok_code]==$row[sosok]){?>selected<?}?>><?=$rr[sosok_name]?></option>
						<?}?>
					</select>
				</td>
				<td>주민번호</td>
				<?$jumin = explode("-",$row[jumin])?>
				<td><input type="text" class="span11" name="jumin1" id="jumin1"  placeholder="주민번호"  maxlength=6 style="width:80px;" value="<?=$jumin[0]?>"/>-<input type="text" class="span11" name="jumin2" id="jumin2"  placeholder="주민번호"  maxlength=7 style="width:80px;" value="<?=$jumin[1]?>"/></td>
			</tr>
			<tr>
				<td>* 주소</td>
				<td><input type="text" class="span11" name="addr" id="addr"  placeholder="주소"  maxlength=150   value="<?=$row[addr]?>"/></td>
				<td>사업자등록번호</td>
				<td>
					<input type="text" class="span11" name="comp_no" id="comp_no" placeholder="사업자등록번호"  maxlength=15  value="<?=$row[comp_no]?>"/>
				</td>
			</tr>
			</table>


				<br><label>&nbsp;&nbsp;&nbsp;접근권한</label>
<!--test 시작-->
				<table  style="width:98%;max-width:90%;margin:10px;border:1px solid gray;" border=1>
				<tr  bgcolor="#c0c0c0">
					<td width="15%" align=center>대분류</td>
					<td width="5%" align=center>조회 / 등록</td>
					<td width="15%" align=center>대분류</td>
					<td width="5%" align=center>조회 / 등록</td>
					<td width="15%" align=center>대분류</td>
					<td width="5%" align=center>조회 / 등록</td>
					<td width="15%" align=center>대분류</td>
					<td width="5%" align=center>조회 / 등록</td>
				</tr>

				<tr bgcolor="#cfcfcf">
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;원데이터</td>
					<td align=center><input type=checkbox name=ch_c00 <?if(($pm[ch_c00]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;비용관련</td>
					<td align=center><input type=checkbox name=ch_d00 <?if(($pm[ch_d00]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;공과금산정납부</td>
					<td align=center><input type=checkbox name=ch_e00 <?if(($pm[ch_e00]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;등기관리팀</td>
					<td align=center><input type=checkbox name=ch_f00 <?if(($pm[ch_f00]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 최초입력</td>
					<td align=center><input type=checkbox name=ch_c11 <?if(($pm[ch_c11]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_c12 <?if(($pm[ch_c12]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 완납증명서입력</td>
					<td align=center><input type=checkbox name=ch_d11 <?if(($pm[ch_d11]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_d12 <?if(($pm[ch_d12]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 채권산정(이전채권)</td>
					<td align=center><input type=checkbox name=ch_e11 <?if(($pm[ch_e11]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_e12 <?if(($pm[ch_e12]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 등기관리팀리스트</td>
					<td align=center><input type=checkbox name=ch_f11 <?if(($pm[ch_f11]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_f12 <?if(($pm[ch_f12]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 수정/기간입력</td>
					<td align=center><input type=checkbox name=ch_c21 <?if(($pm[ch_c21]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_c22 <?if(($pm[ch_c22]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 취득세신고</td>
					<td align=center><input type=checkbox name=ch_d21 <?if(($pm[ch_d21]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_d22 <?if(($pm[ch_d22]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 채권산정(설정채권)</td>
					<td align=center><input type=checkbox name=ch_e21 <?if(($pm[ch_e21]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_e22 <?if(($pm[ch_e22]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 등기신청서출력</td>
					<td align=center><input type=checkbox name=ch_f21 <?if(($pm[ch_f21]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_f22 <?if(($pm[ch_f22]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 실거래정보입력</td>
					<td align=center><input type=checkbox name=ch_c31 <?if(($pm[ch_c31]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_c32 <?if(($pm[ch_c32]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 가상계좌/수납</td>
					<td align=center><input type=checkbox name=ch_d31 <?if(($pm[ch_d31]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_d32 <?if(($pm[ch_d32]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 기타공과금산정</td>
					<td align=center><input type=checkbox name=ch_e31 <?if(($pm[ch_e31]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_e32 <?if(($pm[ch_e32]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 미비서류안내</td>
					<td align=center><input type=checkbox name=ch_c41 <?if(($pm[ch_c41]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_c42 <?if(($pm[ch_c42]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 가상계좌업로드</td>
					<td align=center><input type=checkbox name=ch_d41 <?if(($pm[ch_d41]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_d42 <?if(($pm[ch_d42]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 취득세납부</td>
					<td align=center><input type=checkbox name=ch_e41 <?if(($pm[ch_e41]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_e42 <?if(($pm[ch_e42]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 오픈버스용</td>
					<td align=center><input type=checkbox name=ch_c51 <?if(($pm[ch_c51]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_c52 <?if(($pm[ch_c52]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 수납내역업로드</td>
					<td align=center><input type=checkbox name=ch_d51 <?if(($pm[ch_d51]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_d52 <?if(($pm[ch_d52]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 채권발행(이전채권)</td>
					<td align=center><input type=checkbox name=ch_e51 <?if(($pm[ch_e51]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_e52 <?if(($pm[ch_e52]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 비용안내</td>
					<td align=center><input type=checkbox name=ch_d61 <?if(($pm[ch_d61]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_d62 <?if(($pm[ch_d62]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 채권발행(이전채권)후업로드</td>
					<td align=center><input type=checkbox name=ch_e61 <?if(($pm[ch_e61]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_e62 <?if(($pm[ch_e62]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 회원데이터업로드</td>
					<td align=center><input type=checkbox name=ch_d71 <?if(($pm[ch_d71]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_d72 <?if(($pm[ch_d72]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 채권발행(설정채권)</td>
					<td align=center><input type=checkbox name=ch_e71 <?if(($pm[ch_e71]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_e72 <?if(($pm[ch_e72]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 회원엑셀업로드</td>
					<td align=center><input type=checkbox name=ch_d81 <?if(($pm[ch_d81]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_d82 <?if(($pm[ch_d82]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 채권발행(설정채권)후업로드</td>
					<td align=center><input type=checkbox name=ch_e81 <?if(($pm[ch_e81]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_e82 <?if(($pm[ch_e82]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 보수료설정</td>
					<td align=center><input type=checkbox name=ch_d91 <?if(($pm[ch_d91]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_d92 <?if(($pm[ch_d92]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 설정등록세신고납부</td>
					<td align=center><input type=checkbox name=ch_e91 <?if(($pm[ch_e91]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_e92 <?if(($pm[ch_e92]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 보수료수동입력</td>
					<td align=center><input type=checkbox name=ch_da1 <?if(($pm[ch_da1]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_da2 <?if(($pm[ch_da2]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 말소변경등록세신고납부</td>
					<td align=center><input type=checkbox name=ch_ea1 <?if(($pm[ch_ea1]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_ea2 <?if(($pm[ch_ea2]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 보수료등록</td>
					<td align=center><input type=checkbox name=ch_db1 <?if(($pm[ch_db1]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_db2 <?if(($pm[ch_db2]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr bgcolor="#cfcfcf">
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;전입세대열람조회</td>
					<td align=center><input type=checkbox name=ch_100 <?if(($pm[ch_100]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;고객지원팀</td>
					<td align=center><input type=checkbox name=ch_200 <?if(($pm[ch_200]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;양식관리</td>
					<td align=center><input type=checkbox name=ch_500 <?if(($pm[ch_500]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;수금관리</td>
					<td align=center><input type=checkbox name=ch_600 <?if(($pm[ch_600]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 전입세대열람조회</td>
					<td align=center><input type=checkbox name=ch_111 <?if(($pm[ch_111]=="y")||($mode=="i")){?>checked<?}?> value="y"> / X</td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 고객지원팀조회</td>
					<td align=center><input type=checkbox name=ch_211 <?if(($pm[ch_211]=="y")||($mode=="i")){?>checked<?}?> value="y"> / X</td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 이전양식출력</td>
					<td align=center><input type=checkbox name=ch_511 <?if(($pm[ch_511]=="y")||($mode=="i")){?>checked<?}?> value="y"> / X</td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 수금관리</td>
					<td align=center><input type=checkbox name=ch_611 <?if(($pm[ch_611]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_612 <?if(($pm[ch_612]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 전입세대열람등록</td>
					<td align=center><input type=checkbox name=ch_121 <?if(($pm[ch_121]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_122 <?if(($pm[ch_122]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 필증수령입력(이전)</td>
					<td align=center><input type=checkbox name=ch_231 <?if(($pm[ch_231]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_232 <?if(($pm[ch_232]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 설정양식출력</td>
					<td align=center><input type=checkbox name=ch_521 <?if(($pm[ch_521]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_522 <?if(($pm[ch_522]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 필증수령입력(설정)</td>
					<td align=center><input type=checkbox name=ch_241 <?if(($pm[ch_241]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_242 <?if(($pm[ch_242]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 필증전달입력(이전)</td>
					<td align=center><input type=checkbox name=ch_251 <?if(($pm[ch_251]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_252 <?if(($pm[ch_252]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 필증전달입력(설정)</td>
					<td align=center><input type=checkbox name=ch_261 <?if(($pm[ch_261]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_262 <?if(($pm[ch_262]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 정산완료입력(이전)</td>
					<td align=center><input type=checkbox name=ch_271 <?if(($pm[ch_271]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_272 <?if(($pm[ch_272]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 정산완료입력(설정)</td>
					<td align=center><input type=checkbox name=ch_281 <?if(($pm[ch_281]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_282 <?if(($pm[ch_282]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 필증배포입력(이전)</td>
					<td align=center><input type=checkbox name=ch_291 <?if(($pm[ch_291]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_292 <?if(($pm[ch_292]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 필증배포입력(설정)</td>
					<td align=center><input type=checkbox name=ch_2a1 <?if(($pm[ch_2a1]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_2a2 <?if(($pm[ch_2a2]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>

				<tr bgcolor="#cfcfcf">
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;환불금관리</td>
					<td align=center><input type=checkbox name=ch_700 <?if(($pm[ch_700]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;ERP</td>
					<td align=center><input type=checkbox name=ch_800 <?if(($pm[ch_800]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;기본정보</td>
					<td align=center><input type=checkbox name=ch_900 <?if(($pm[ch_900]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;데이터관리</td>
					<td align=center><input type=checkbox name=ch_300 <?if(($pm[ch_300]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 환불금조회</td>
					<td align=center><input type=checkbox name=ch_711 <?if(($pm[ch_711]=="y")||($mode=="i")){?>checked<?}?> value="y"> / X</td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 세금계산서발행내역</td>
					<td align=center><input type=checkbox name=ch_811 <?if(($pm[ch_811]=="y")||($mode=="i")){?>checked<?}?> value="y"> / X</td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 태율정보관리</td>
					<td align=center><input type=checkbox name=ch_911 <?if(($pm[ch_911]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_912 <?if(($pm[ch_912]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 엑셀업로드</td>
					<td align=center><input type=checkbox name=ch_311 <?if(($pm[ch_311]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_312 <?if(($pm[ch_312]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 환불계좌등록</td>
					<td align=center><input type=checkbox name=ch_721 <?if(($pm[ch_721]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_722 <?if(($pm[ch_722]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 카드승인내역</td>
					<td align=center><input type=checkbox name=ch_821 <?if(($pm[ch_821]=="y")||($mode=="i")){?>checked<?}?> value="y"> / X</td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 정산보고서조회</td>
					<td align=center><input type=checkbox name=ch_931 <?if(($pm[ch_931]=="y")||($mode=="i")){?>checked<?}?> value="y"> / X</td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 엑셀백업</td>
					<td align=center><input type=checkbox name=ch_321 <?if(($pm[ch_321]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_322 <?if(($pm[ch_322]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 환불완료일입력</td>
					<td align=center><input type=checkbox name=ch_731 <?if(($pm[ch_731]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_732 <?if(($pm[ch_732]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 현금영수증발행내역</td>
					<td align=center><input type=checkbox name=ch_831 <?if(($pm[ch_831]=="y")||($mode=="i")){?>checked<?}?> value="y"> / X</td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 현장상세정보</td>
					<td align=center><input type=checkbox name=ch_941 <?if(($pm[ch_941]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_942 <?if(($pm[ch_942]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 데이터삭제</td>
					<td align=center><input type=checkbox name=ch_331 <?if(($pm[ch_331]=="y")||($mode=="i")){?>checked<?}?> value="y"> / X</td>
				</tr>

				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 태율계좌관리</td>
					<td align=center><input type=checkbox name=ch_921 <?if(($pm[ch_921]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_922 <?if(($pm[ch_922]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 사건부</td>
					<td align=center><input type=checkbox name=ch_341 <?if(($pm[ch_341]=="y")||($mode=="i")){?>checked<?}?> value="y"> / X</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 은행기본정보</td>
					<td align=center><input type=checkbox name=ch_951 <?if(($pm[ch_951]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_952 <?if(($pm[ch_952]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 은행지점정보</td>
					<td align=center><input type=checkbox name=ch_961 <?if(($pm[ch_961]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_962 <?if(($pm[ch_962]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 은행기본비용설정</td>
					<td align=center><input type=checkbox name=ch_971 <?if(($pm[ch_971]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_972 <?if(($pm[ch_972]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 은행지점비용설정</td>
					<td align=center><input type=checkbox name=ch_981 <?if(($pm[ch_981]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_982 <?if(($pm[ch_982]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 소속관리</td>
					<td align=center><input type=checkbox name=ch_991 <?if(($pm[ch_991]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_992 <?if(($pm[ch_992]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 공휴일설정</td>
					<td align=center><input type=checkbox name=ch_9a1 <?if(($pm[ch_9a1]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_9a2 <?if(($pm[ch_9a2]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr bgcolor="#cfcfcf">
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;계정관리</td>
					<td align=center><input type=checkbox name=ch_a00 <?if(($pm[ch_a00]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;게시판관리</td>
					<td align=center><input type=checkbox name=ch_b00 <?if(($pm[ch_b00]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td style="font-weight:900">&nbsp;&nbsp;&nbsp;공통팝업</td>
					<td align=center></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 계정목록</td>
					<td align=center><input type=checkbox name=ch_a11 <?if(($pm[ch_a11]=="y")||($mode=="i")){?>checked<?}?> value="y"> / X</td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 매뉴얼</td>
					<td align=center><input type=checkbox name=ch_b11 <?if(($pm[ch_b11]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_b12 <?if(($pm[ch_b12]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td style="color:red;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 기본상세조회(팝업)</td>
					<td align=center>O / <input type=checkbox  name=ch_222 <?if(($pm[ch_222]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 계정등록</td>
					<td align=center><input type=checkbox name=ch_a21 <?if(($pm[ch_a21]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_a22 <?if(($pm[ch_a22]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 자료실</td>
					<td align=center><input type=checkbox name=ch_b21 <?if(($pm[ch_b21]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_b22 <?if(($pm[ch_b22]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ 수정/삭제이력</td>
					<td align=center><input type=checkbox name=ch_a31 <?if(($pm[ch_a31]=="y")||($mode=="i")){?>checked<?}?> value="y"> / X</td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└ FAQ</td>
					<td align=center><input type=checkbox name=ch_b31 <?if(($pm[ch_b31]=="y")||($mode=="i")){?>checked<?}?> value="y"> / <input type=checkbox  name=ch_b32 <?if(($pm[ch_b32]=="y")||($mode=="i")){?>checked<?}?> value="y"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>

				</table>
<!--test 종료-->

				<div style="text-align:center;" >

					<?if($_SESSION["admin_permission"][ch_a22]=="y"){?>
						<button type="submit" class="btn btn-success">저장</button>&nbsp;&nbsp;&nbsp;&nbsp;
						<?if($_SESSION["admin_id"]=="master"){?>
							<?if($idx!=""){?>
							<button type="button" class="btn btn-success" onclick="javascript:f_del(<?=$idx?>);">계정삭제</button>&nbsp;&nbsp;&nbsp;&nbsp;
							<?}?>
						<?}?>
					<?}?>

					<button type="button" class="btn btn-success" onclick="javascript:location.href='index.html';">계정목록 이동</button>
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
	<?include ("../include/bottom.php");?>
<!--bottom-종료-->

<script src="/js/jquery.min.js"></script>
<script src="/js/jquery.ui.custom.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/jquery.dataTables.min.js"></script>
<script src="/js/maruti.js"></script>

<script>
function f_del(idx){
	<?if($_SESSION["admin_id"]=="master"){?>
		if(confirm("계정을 삭제하시겠습니까?")){
			location.href = "delete.php?idx="+idx;
		}
	<?}else{?>
		alert("마스터만 삭제 가능합니다.");
	<?}?>
}
function f_submit(){
	var v = document.ff;
	<?if($mode=="i"){?>
		if(v.id_ch.value=="n"){
			alert("아이디 중복 확인하세요.");
			v.id.focus();
			return false;
		}else if(v.name.value==""){
			alert("이름을 입력하세요.");
			v.name.focus();
			return false;
		}else if(v.pwd1.value==""){
			alert("암호를 입력하세요.");
			v.pwd1.focus();
			return false;
		}else if(v.pwd2.value==""){
			alert("암호를 입력하세요.");
			v.pwd1.focus();
			return false;
		}else if(v.pwd1.value!=v.pwd2.value){
			alert("암호가 일치하지 않습니다.");
			v.pwd1.focus();
			return false;
		}else{
			return true;
		}
	<?}else{?>
		if(v.name.value==""){
			alert("이름을 입력하세요.");
			v.name.focus();
			return false;
<?if($mode!="e"){?>
		}else if(v.pwd1.value==""){
			alert("암호를 입력하세요.");
			v.pwd1.focus();
			return false;
		}else if(v.pwd2.value==""){
			alert("암호확인을 입력하세요.");
			v.pwd2.focus();
			return false;
<?}?>
		}else if(v.pwd1.value!=v.pwd2.value){
			alert("암호가 일치하지 않습니다.");
			v.pwd1.focus();
			return false;
		}else{
			return true;
		}
	<?}?>
}

function f_duplicate(){
	var v1=$("#id").val();
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
					alert("사용 가능한 아이디입니다.");
					$("#id_ch").val("y");
				}else{ //중복 일때
					alert("이미 사용중인 아이디입니다.");
					$("#id_ch").val("n");
				}
			},
			error : function(request, status, error) {
				//통신 에러 발생시 처리
				//alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
			}
		});
	}else{
		alert("아이디를 입력하세요.");
		$("#id").focus();
	}
}

</script>

</body>
</html>
