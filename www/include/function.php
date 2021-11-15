<?
//include $_SERVER["DOCUMENT_ROOT"]."/include/ip_white_list.php";

$sid = session_id();
$GET_VARS = $_GET;
$POST_VARS = $_POST;

//원단위 절삭
function f_won($imsi){
	$imsi = floor(($imsi*0.1)/10)*10;
	return $imsi;
}

//확장자 구하기
function f_ext($file_name){
  $tmp = strpos(strrev($file_name), '.');
   $temp = strlen($file_name) - $tmp;

   if ($tmp) {
    $strName = substr($file_name, 0, $temp-1);
    $strExt = substr($file_name, strlen($strName) + 1, strlen($file_name));
    if (preg_match('/htm|php|inc|phtm|shtm|cgi|dot|asp|ztx|pl/i', $strExt)) $strExt .= '.txt';
   } else {
    $strName = $file_name;
    $strExt = 'unknown';
   }

   $strExt = strtolower($strExt);
   return $strExt;
}

function f_date_gigan($p1,$p2){  //두 날짜중 가장 최근일을 비교하여 오늘 날짜와 비교하여 경과일수 비교
	$last_date = "";
	if(($p1=="")&&($p2=="")){
		$last_date = "";
	}else if(($p1=="")&&($p2!="")){
		$last_date = $p2;
	}else if(($p1!="")&&($p2=="")){
		$last_date = $p1;
	}else if(($p1!="")&&($p2!="")){
		if(intval($p1)>intval($p2)){
			$last_date = $p1;
		}else if(intval($p1)<intval($p2)){
			$last_date = $p2;
		}else{
			$last_date = $p1;
		}
	}

	//echo $last_date;
	if($last_date==""){
		return "";
	}else{
		$preday = new DateTime($last_date);
		$today = new DateTime(date("Ymd"));
		$diff    = date_diff($preday, $today); //오늘 기준 어제까지의 날짜 비교.
		return  $diff->format("%R%a일");
	}
}

function f_day_color($p1,$p2){  //이전,현재
	if($p2==""){
		if($p1!=""){
			$preday = new DateTime($p1);
			$today = new DateTime(date("Ymd"));
			$diff    = date_diff($preday, $today); //오늘 기준 어제까지의 날짜 비교.
			$color = "white";
			if(($diff->days>2)&&($diff->days<=7)){  //2일초과 1주이내 노랑
				$color = "#FAED7D";
			}else if(($diff->days>7)&&($diff->days<=14)){  //2주이내 주황
				$color = "#FFC19E";
			}else if($diff->days>14){  //2추초과 빨강
				$color = "#FFA7A7";
			}else{
				$color = "white";
			}
		}
	}else{
		$color = "white";
	}
	return $color;
}

function f_day_color2($p1,$p2){  //이전,현재

	if($p2==""){
		if($p1!=""){
			$preday = new DateTime($p1);
			$today = new DateTime(date("Ymd"));
			$diff    = date_diff($preday, $today); //오늘 기준 어제까지의 날짜 비교.
			$color = "";
			if(($diff->days>2)&&($diff->days<=7)){  //2일초과 1주이내 노랑
				$color = "+".$diff->days."일";
			}else if(($diff->days>7)&&($diff->days<=14)){  //2주이내 주황
				$color = "+".$diff->days."일";
			}else if($diff->days>14){  //2추초과 빨강
				$color = "+".$diff->days."일";
			}else{
				$color = "";
			}
		}
	}else{
		$color = "";
	}
	return  "<font color=blue>".$color."</font>";
}

function f_null($p1){
	if(is_null($p1)){
		return "";
	}else{
		return $p1;
	}
}

function f_Y_value($p){
	if($p=="Y"){
		return "대상";
	}else{
		return "";
	}
}

function f_gubun_code($p1){
	if($p1=="basic"){
		return "기본";
	}else if($p1=="bonjum"){
		return "본점제휴";
	}
}

function f_suljung_bosu($idx,$gukto){//은행지점 설정보수액계산
		//보수액 : 기본보수료 + 누진보수료 + 등록세신고납부대행 + 교통비 + 원원인증서작성료 + 부가세
		//부가세  = 아래 수식 적용하여 저장(신한은행 원단위까지 나와.타은행은 원단위 버림)

		//설정정보
		$sql = "select * from tbl_suljung where idx={$idx} limit 1";
		//echo $sql;
		$ss = db_query_fetch($sql);

		//이전정보설정
		$sql = "select * from tbl_junib where a1='{$ss[a1]}' limit 1";
		//echo $sql;
		$ii = db_query_fetch($sql);


		//은행지점비율정보
		if($ss[gukto]=="gukto"){
			$basic_gukto = "gukto";
			$basic_gukto_t = "basic";
		}else{
			$basic_gukto = "basic";
			$basic_gukto_t = "gukto";
		}
		$sql = "select * from tbl_bank_jijum_rate where jijum_code='{$ss[jijum_code]}' and h_idx='{$ii[h_idx]}' and basic_gukto='{$basic_gukto}' limit 1";
		//echo $sql;
		$jj = db_query_fetch($sql);

		$imsi_daesang = "";  //하나라도 y이면 1회대상임
		if($jj[b_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($jj[b_kyotong_ch]=="y")$imsi_daesang = "y";
		if($jj[g_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($jj[g_kyotong_ch]=="y")$imsi_daesang = "y";


		$sql = "select bank_alias from tbl_bank_info where bank_code='{$jj[bank_code]}' limit 1";
		//echo $sql;
		$bb = db_query_fetch($sql);

		$imsi = 0;
		$vat = 0;

		//기본보수료(고정값) + 누진보수료(엑셀업로드시) + 등록세신고납부대행(1회청구비교) + 교통비(1회청구비교) + 원인증서작성료(고정) + 부가세
		if($imsi_daesang=="y"){  //1회 대상인 경우
			if($ss[cg_daesang]=="Y"){  //1회청구라면 그사람만 나옴.
				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
				//if($jj[b_singonabbu_ch]=="y") $imsi = $imsi + $jj[b_singonabbu];
				//if($jj[b_kyotong_ch]=="y") $imsi = $imsi + $jj[b_kyotong];
				$imsi = $imsi + $jj[b_singonabbu];
				$imsi = $imsi + $jj[b_kyotong];
			}else{
				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
				if($jj[b_singonabbu_ch]=="") {
					$imsi = $imsi + $jj[b_singonabbu];
				} else if($jj[b_singonabbu_ch]==NULL) {
					$imsi = $imsi + $jj[b_singonabbu];
				}
				if($jj[b_kyotong_ch]==""){
					$imsi = $imsi + $jj[b_kyotong];
				}else if($jj[b_kyotong_ch]==NULL){
					$imsi = $imsi + $jj[b_kyotong];
				}
				//$imsi = $imsi + $jj[b_kyotong];
			}
		}else{//1회 대상이 아닌경우
				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
				$imsi = $imsi + $jj[b_singonabbu];
				$imsi = $imsi + $jj[b_kyotong];
		}

		$vat = floor(($imsi*0.1)/1)*1;
		if($jj[b_julsak_position]=="1"){
			$vat = floor( $vat / 10 ) * 10;  //1단위 절삭
		}else if($jj[b_julsak_position]=="2"){
			$vat = floor( $vat / 100 ) * 100;  //10단위 절삭
		}

		return $imsi;
}


function f_suljung_bosu2($a1,$suljung_no,$gukto){//은행지점 설정보수액계산
		//보수액 : 기본보수료 + 누진보수료 + 등록세신고납부대행 + 교통비 + 원원인증서작성료 + 부가세
		//부가세  = 아래 수식 적용하여 저장(신한은행 원단위까지 나와.타은행은 원단위 버림)

		//설정정보
		$sql = "select * from tbl_suljung where a1='{$a1}' and suljung_no='{$suljung_no}' limit 1";
		//echo $sql;
		$ss = db_query_fetch($sql);

		//이전정보설정
		$sql = "select * from tbl_junib where a1='{$ss[a1]}' limit 1";
		//echo $sql;
		$ii = db_query_fetch($sql);


		//은행지점비율정보
		if($ss[gukto]=="gukto"){
			$basic_gukto = "gukto";
			$basic_gukto_t = "basic";
		}else{
			$basic_gukto = "basic";
			$basic_gukto_t = "gukto";
		}
		$sql = "select * from tbl_bank_jijum_rate where jijum_code='{$ss[jijum_code]}' and h_idx='{$ii[h_idx]}' and basic_gukto='{$basic_gukto}' limit 1";
		//echo $sql;
		$jj = db_query_fetch($sql);

		$imsi_daesang = "";  //하나라도 y이면 1회대상임
		if($jj[b_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($jj[b_kyotong_ch]=="y")$imsi_daesang = "y";
		if($jj[g_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($jj[g_kyotong_ch]=="y")$imsi_daesang = "y";


		$sql = "select bank_alias from tbl_bank_info where bank_code='{$jj[bank_code]}' limit 1";
		//echo $sql;
		$bb = db_query_fetch($sql);

		$imsi = 0;
		$vat = 0;

				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];

		//기본보수료(고정값) + 누진보수료(엑셀업로드시) + 등록세신고납부대행(1회청구비교) + 교통비(1회청구비교) + 원인증서작성료(고정) + 부가세
		if($imsi_daesang=="y"){  //1회 대상인 경우
			if($ss[cg_daesang]=="Y"){  //1회청구라면 그사람만 나옴.
				//if($jj[b_singonabbu_ch]=="y") $imsi = $imsi + $jj[b_singonabbu];
				//if($jj[b_kyotong_ch]=="y") $imsi = $imsi + $jj[b_kyotong];
				$imsi = $imsi + $jj[b_singonabbu];
				$imsi = $imsi + $jj[b_kyotong];
			}else{
//				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
				if($jj[b_singonabbu_ch]=="") {
					$imsi = $imsi + $jj[b_singonabbu];
				} else if($jj[b_singonabbu_ch]==NULL) {
					$imsi = $imsi + $jj[b_singonabbu];
				}
				if($jj[b_kyotong_ch]==""){
					$imsi = $imsi + $jj[b_kyotong];
				}else if($jj[b_kyotong_ch]==NULL){
					$imsi = $imsi + $jj[b_kyotong];
				}
				//$imsi = $imsi + $jj[b_kyotong];
			}
		}else{//1회 대상이 아닌경우
//				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
				$imsi = $imsi + $jj[b_singonabbu];
				$imsi = $imsi + $jj[b_kyotong];
		}

		$vat = floor(($imsi*0.1)/1)*1;
		if($jj[b_julsak_position]=="1"){
			$vat = floor( $vat / 10 ) * 10;  //1단위 절삭
		}else if($jj[b_julsak_position]=="2"){
			$vat = floor( $vat / 100 ) * 100;  //10단위 절삭
		}


		$imsik[fee] = $imsi;
		$imsik[vat] = $vat;

		return $imsik;
}

function f_suljung_bosu_imsi($idx,$gukto){//은행지점 설정보수액계산
		//보수액 : 기본보수료 + 누진보수료 + 등록세신고납부대행 + 교통비 + 원원인증서작성료 + 부가세
		//부가세  = 아래 수식 적용하여 저장(신한은행 원단위까지 나와.타은행은 원단위 버림)

		//설정정보
		$sql = "select * from tbl_suljung where idx={$idx} limit 1";
		//echo $sql;
		$ss = db_query_fetch($sql);

		//이전정보설정
		$sql = "select * from tbl_junib where a1='{$ss[a1]}' limit 1";
		//echo $sql;
		$ii = db_query_fetch($sql);


		//은행지점비율정보
		if($ss[gukto]=="gukto"){
			$basic_gukto = "gukto";
			$basic_gukto_t = "basic";
		}else{
			$basic_gukto = "basic";
			$basic_gukto_t = "gukto";
		}
		$sql = "select * from tbl_bank_jijum_rate where jijum_code='{$ss[jijum_code]}' and h_idx='{$ii[h_idx]}' and basic_gukto='{$basic_gukto}' limit 1";
		//echo $sql;
		$jj = db_query_fetch($sql);

		$imsi_daesang = "";  //하나라도 y이면 1회대상임
		if($jj[b_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($jj[b_kyotong_ch]=="y")$imsi_daesang = "y";
		if($jj[g_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($jj[g_kyotong_ch]=="y")$imsi_daesang = "y";


		$sql = "select bank_alias from tbl_bank_info where bank_code='{$jj[bank_code]}' limit 1";
		//echo $sql;
		$bb = db_query_fetch($sql);

		$imsi = 0;
		$vat = 0;

		//기본보수료(고정값) + 누진보수료(엑셀업로드시) + 등록세신고납부대행(1회청구비교) + 교통비(1회청구비교) + 원인증서작성료(고정) + 부가세
		if($imsi_daesang=="y"){  //1회 대상인 경우
			if($ss[cg_daesang]=="Y"){  //1회청구라면 그사람만 나옴.
				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
				//if($jj[b_singonabbu_ch]=="y") $imsi = $imsi + $jj[b_singonabbu];
				//if($jj[b_kyotong_ch]=="y") $imsi = $imsi + $jj[b_kyotong];
				$imsi = $imsi + $jj[b_singonabbu];
				$imsi = $imsi + $jj[b_kyotong];
			}else{
				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
				if($jj[b_singonabbu_ch]=="") {
					$imsi = $imsi + $jj[b_singonabbu];
				} else if($jj[b_singonabbu_ch]==NULL) {
					$imsi = $imsi + $jj[b_singonabbu];
				}
				if($jj[b_kyotong_ch]==""){
					$imsi = $imsi + $jj[b_kyotong];
				}else if($jj[b_kyotong_ch]==NULL){
					$imsi = $imsi + $jj[b_kyotong];
				}
				//$imsi = $imsi + $jj[b_kyotong];
			}
		}else{//1회 대상이 아닌경우
				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
				$imsi = $imsi + $jj[b_singonabbu];
				$imsi = $imsi + $jj[b_kyotong];
		}

		$vat = floor(($imsi*0.1)/1)*1;
		if($jj[b_julsak_position]=="1"){
			$vat = floor( $vat / 10 ) * 10;  //1단위 절삭
		}else if($jj[b_julsak_position]=="2"){
			$vat = floor( $vat / 100 ) * 100;  //10단위 절삭
		}


		return $imsi+$vat;
}

function f_suljung_bosu_vat($idx,$gukto){//은행지점 설정보수액계산
		//보수액 : 기본보수료 + 누진보수료 + 등록세신고납부대행 + 교통비 + 원원인증서작성료 + 부가세
		//부가세  = 아래 수식 적용하여 저장(신한은행 원단위까지 나와.타은행은 원단위 버림)

		//설정정보
		$sql = "select * from tbl_suljung where idx={$idx} limit 1";
		//echo $sql;
		$ss = db_query_fetch($sql);


		//이전정보설정
		$sql = "select * from tbl_junib where a1='{$ss[a1]}' limit 1";
		//echo $sql;
		$ii = db_query_fetch($sql);


		//은행지점비율정보
		if($ss[gukto]=="gukto"){
			$basic_gukto = "gukto";
			$basic_gukto_t = "basic";
		}else{
			$basic_gukto = "basic";
			$basic_gukto_t = "gukto";
		}
		$sql = "select * from tbl_bank_jijum_rate where jijum_code='{$ss[jijum_code]}' and h_idx='{$ii[h_idx]}' and basic_gukto='{$basic_gukto}' limit 1";
		//echo $sql;
		$jj = db_query_fetch($sql);

		$imsi_daesang = "";  //하나라도 y이면 1회대상임
		if($jj[b_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($jj[b_kyotong_ch]=="y")$imsi_daesang = "y";
		if($jj[g_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($jj[g_kyotong_ch]=="y")$imsi_daesang = "y";


		$sql = "select bank_alias from tbl_bank_info where bank_code='{$jj[bank_code]}' limit 1";
		//echo $sql;
		$bb = db_query_fetch($sql);

		$imsi = 0;
		$vat = 0;

		//기본보수료(고정값) + 누진보수료(엑셀업로드시) + 등록세신고납부대행(1회청구비교) + 교통비(1회청구비교) + 원인증서작성료(고정) + 부가세
		if($imsi_daesang=="y"){  //1회 대상인 경우
			if($ss[cg_daesang]=="Y"){  //1회청구라면 그사람만 나옴.
				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
				//if($jj[b_singonabbu_ch]=="y") $imsi = $imsi + $jj[b_singonabbu];
				//if($jj[b_kyotong_ch]=="y") $imsi = $imsi + $jj[b_kyotong];
				$imsi = $imsi + $jj[b_singonabbu];
				$imsi = $imsi + $jj[b_kyotong];
			}else{
				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
				if($jj[b_singonabbu_ch]=="") {
					$imsi = $imsi + $jj[b_singonabbu];
				} else if($jj[b_singonabbu_ch]==NULL) {
					$imsi = $imsi + $jj[b_singonabbu];
				}
				if($jj[b_kyotong_ch]==""){
					$imsi = $imsi + $jj[b_kyotong];
				}else if($jj[b_kyotong_ch]==NULL){
					$imsi = $imsi + $jj[b_kyotong];
				}
				//$imsi = $imsi + $jj[b_kyotong];
			}
		}else{//1회 대상이 아닌경우
				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
				$imsi = $imsi + $jj[b_singonabbu];
				$imsi = $imsi + $jj[b_kyotong];
		}

		$vat = floor(($imsi*0.1)/1)*1;
		if($jj[b_julsak_position]=="1"){
			$vat = floor( $vat / 10 ) * 10;  //1단위 절삭
		}else if($jj[b_julsak_position]=="2"){
			$vat = floor( $vat / 100 ) * 100;  //10단위 절삭
		}


		return $vat;
}

function f_suljung_bosu_all($idx,$gukto){//은행지점 설정보수액계산
		//보수액 : 기본보수료 + 누진보수료 + 등록세신고납부대행 + 교통비 + 원원인증서작성료 + 부가세
		//부가세  = 아래 수식 적용하여 저장(신한은행 원단위까지 나와.타은행은 원단위 버림)

		//설정정보
		$sql = "select * from tbl_suljung where idx={$idx} limit 1";
		//echo $sql;
		$ss = db_query_fetch($sql);


		//이전정보설정
		$sql = "select * from tbl_junib where a1='{$ss[a1]}' limit 1";
		//echo $sql;
		$ii = db_query_fetch($sql);


		//은행지점비율정보
		if($ss[gukto]=="gukto"){
			$basic_gukto = "gukto";
			$basic_gukto_t = "basic";
		}else{
			$basic_gukto = "basic";
			$basic_gukto_t = "gukto";
		}
		$sql = "select * from tbl_bank_jijum_rate where jijum_code='{$ss[jijum_code]}' and h_idx='{$ii[h_idx]}' and basic_gukto='{$basic_gukto}' limit 1";
		//echo $sql;
		$jj = db_query_fetch($sql);

		$imsi_daesang = "";  //하나라도 y이면 1회대상임
		if($jj[b_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($jj[b_kyotong_ch]=="y")$imsi_daesang = "y";
		if($jj[g_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($jj[g_kyotong_ch]=="y")$imsi_daesang = "y";


		$sql = "select bank_alias from tbl_bank_info where bank_code='{$jj[bank_code]}' limit 1";
		//echo $sql;
		$bb = db_query_fetch($sql);

		$imsi = 0;
		$vat = 0;

		//기본보수료(고정값) + 누진보수료(엑셀업로드시) + 등록세신고납부대행(1회청구비교) + 교통비(1회청구비교) + 원인증서작성료(고정) + 부가세
		if($imsi_daesang=="y"){  //1회 대상인 경우
			if($ss[cg_daesang]=="Y"){  //1회청구라면 그사람만 나옴.
				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
				//if($jj[b_singonabbu_ch]=="y") $imsi = $imsi + $jj[b_singonabbu];
				//if($jj[b_kyotong_ch]=="y") $imsi = $imsi + $jj[b_kyotong];
				$imsi = $imsi + $jj[b_singonabbu];
				$imsi = $imsi + $jj[b_kyotong];
			}else{
				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
				if($jj[b_singonabbu_ch]=="") {
					$imsi = $imsi + $jj[b_singonabbu];
				} else if($jj[b_singonabbu_ch]==NULL) {
					$imsi = $imsi + $jj[b_singonabbu];
				}
				if($jj[b_kyotong_ch]==""){
					$imsi = $imsi + $jj[b_kyotong];
				}else if($jj[b_kyotong_ch]==NULL){
					$imsi = $imsi + $jj[b_kyotong];
				}
				//$imsi = $imsi + $jj[b_kyotong];
			}
		}else{//1회 대상이 아닌경우
				$imsi = $ss[suljung_bosu] + $jj[b_basic_bosu] + $jj[b_woninjungseo];
				$imsi = $imsi + $jj[b_singonabbu];
				$imsi = $imsi + $jj[b_kyotong];
		}

		$vat = floor(($imsi*0.1)/1)*1;
		if($jj[b_julsak_position]=="1"){
			$vat = floor( $vat / 10 ) * 10;  //1단위 절삭
		}else if($jj[b_julsak_position]=="2"){
			$vat = floor( $vat / 100 ) * 100;  //10단위 절삭
		}


		$imsik[fee] = $imsi;
		$imsik[vat] = $vat;

		return $imsik;
}


function f_suljung_gongga($idx,$kukto){//은행지점 공과금계산
		//증지대(공) + 등록세신고납부대행(공) + 교통비(공) + 제증명(공) + 열람증지대(우리공) + 등초본발급(공) + 지배인초본발급(하나공)


		//설정정보
		$sql = "select * from tbl_suljung where idx={$idx} limit 1";
		//echo $sql;
		$ss = db_query_fetch($sql);

		//이전정보설정
		$sql = "select * from tbl_junib where a1='{$ss[a1]}' limit 1";
		//echo $sql;
		$ii = db_query_fetch($sql);

		//은행지점정보
		if($ss[gukto]=="gukto"){
			$basic_gukto = "gukto";
		}else{
			$basic_gukto = "basic";
		}

		$sql = "select * from tbl_bank_jijum_rate where jijum_code='{$ss[jijum_code]}' and h_idx='{$ii[h_idx]}' and basic_gukto='{$basic_gukto}' limit 1";
		//echo $sql;
		$jj = db_query_fetch($sql);

		$imsi_daesang = "";  //하나라도 y이면 1회대상임
		if($jj[b_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($jj[b_kyotong_ch]=="y")$imsi_daesang = "y";
		if($jj[g_singonabbu_ch]=="y")$imsi_daesang = "y";
		if($jj[g_kyotong_ch]=="y")$imsi_daesang = "y";

		$imsi = 0;

		//증지대(공) + 등록세신고납부대행(공-Y) + 교통비(공-Y) + 제증명(공) + 열람증지대(우리공) + 등초본발급(공) + 지배인초본발급(하나공)
		//$imsi = $jj[g_jungjidae] + $jj[g_singgonabbu] + $jj[g_kyotong] + $jj[g_jejungmyung] + $jj[g_yeolamjunggi] + $jj[g_deungchobon] + $jj[g_jibaeinchobon];

		if($imsi_daesang=="y"){  //1회 대상인 경우
			if($ss[cg_daesang]=="Y"){  //1회청구라면 그사람만 나옴.
				$imsi = $jj[g_jungjidae] + $jj[g_jejungmyung] + $jj[g_yeolamjunggi] + $jj[g_deungchobon] + $jj[g_jibaeinchobon] + $ss[regi_lic] + $ss[local_edu];
				if($jj[g_singonabbu_ch]=="y") $imsi = $imsi + $jj[g_singonabbu];
				if($jj[g_kyotong_ch]=="y") $imsi = $imsi + $jj[g_kyotong];
			}else{
				$imsi = $jj[g_jungjidae] + $jj[g_jejungmyung] + $jj[g_yeolamjunggi] + $jj[g_deungchobon] + $jj[g_jibaeinchobon] + $ss[regi_lic] + $ss[local_edu];
				if($jj[g_singonabbu_ch]=="") $imsi = $imsi + $jj[g_singonabbu];
				if($jj[g_kyotong_ch]=="") $imsi = $imsi + $jj[g_kyotong];
			}
		}else{  //1회 대상이 아닌경우 다 더함
				$imsi = $jj[g_jungjidae] + $jj[g_jejungmyung] + $jj[g_yeolamjunggi] + $jj[g_deungchobon] + $jj[g_jibaeinchobon] + $ss[regi_lic] + $ss[local_edu];
				$imsi = $imsi + $jj[g_singonabbu];
				$imsi = $imsi + $jj[g_kyotong];
		}

		//echo "/".$imsi."/".$ss[regi_lic]."/".$ss[local_edu]."<br>";

//		$imsi = floor(($imsi*0.1)/10)*10;

		return $imsi;

}


function f_suljung_gongga2($a1,$suljung_no,$kukto){//은행지점 공과금계산
		//증지대(공) + 등록세신고납부대행(공) + 교통비(공) + 제증명(공) + 열람증지대(우리공) + 등초본발급(공) + 지배인초본발급(하나공)


		//설정정보
		$sql = "select * from tbl_suljung where a1='{$a1}' and suljung_no='{$suljung_no}' limit 1";
		//echo $sql;
		$ss = db_query_fetch($sql);

		//이전정보설정
		$sql = "select * from tbl_junib where a1='{$ss[a1]}' limit 1";
		//echo $sql;
		$ii = db_query_fetch($sql);

		//은행지점정보
		if($ss[gukto]=="gukto"){
			$basic_gukto = "gukto";
		}else{
			$basic_gukto = "basic";
		}


		$sql = "select * from tbl_bank_jijum_rate where jijum_code='{$ss[jijum_code]}' and h_idx='{$ii[h_idx]}' and basic_gukto='{$basic_gukto}' limit 1";
		//echo $sql;
		$jj = db_query_fetch($sql);

		$imsi = 0;

		//증지대(공) + 등록세신고납부대행(공-Y) + 교통비(공-Y) + 제증명(공) + 열람증지대(우리공) + 등초본발급(공) + 지배인초본발급(하나공)
		//$imsi = $jj[g_jungjidae] + $jj[g_singgonabbu] + $jj[g_kyotong] + $jj[g_jejungmyung] + $jj[g_yeolamjunggi] + $jj[g_deungchobon] + $jj[g_jibaeinchobon];
		if($ss[cg_daesang]=="Y"){  //1회청구라면 그사람만 나옴.
			$imsi = $jj[g_jungjidae] + $jj[g_jejungmyung] + $jj[g_yeolamjunggi] + $jj[g_deungchobon] + $jj[g_jibaeinchobon];
			$imsi = $imsi + $jj[g_singonabbu];
			$imsi = $imsi + $jj[g_kyotong];
			$imsi = $imsi + $ss[regi_lic] + $ss[local_edu];  //설정테이블의 등록세+교육세
		}else{
			$imsi = $jj[g_jungjidae] + $jj[g_jejungmyung] + $jj[g_yeolamjunggi] + $jj[g_deungchobon] + $jj[g_jibaeinchobon];
			if($jj[g_singonabbu_ch]!="y") $imsi = $imsi + $jj[g_singonabbu];
			if($jj[g_kyotong_ch]!="y") $imsi = $imsi + $jj[g_kyotong];
			$imsi = $imsi + $ss[regi_lic] + $ss[local_edu];  //설정테이블의 등록세+교육세
		}

//		$imsi = floor(($imsi*0.1)/10)*10;

		return $imsi;


}


function f_gukto($p1){
	if($p1==""){
			return "";
	}else{
			return "국토";
	}
}

function f_jung($p1){
	if($p1==""){
		return 0;
	}else {
		return intval($p1);
	}
}

//주민번호 정합성  14이면 그냥 출력 / 아니면 6자리 7자리 구분 출력
function f_jumin_valid($p1){
	if($p1==""){
		return "";
	}else{
		$p1 = str_replace("-","",$p1);
		if(strlen($p1)==14){
			return $p1;
		}else{
			return substr($p1,0,6)."-".substr($p1,6);
		}
	}
}

function f_cate($p1){
		if($p1=="ijy_c_date") return "이전영수증출력일";
		if($p1=="sjb_c_date") return "설정비용내역서출력일";
		if($p1=="biyong_c_date") return "비용청구일";
		if($p1=="g1") return "접수일";
		if($p1=="biyong_c_date") return "비용청구일";
		if($p1=="ijp_s_date") return "필증수령일(이전)";
		if($p1=="ijp_j_date") return "필증전달일(이전)";
		if($p1=="ijj_w_date") return "필증정산일(이전)";
		if($p1=="ijp_b_date") return "고객배포일(이전)";
		if($p1=="refund_date") return "환불일";
		if($p1=="sjp_s_date") return "필증수령일(설정)";
		if($p1=="sjp_j_date") return "필증전달일(설정)";
		if($p1=="sjj_w_date") return "필증정산일(설정)";
		if($p1=="sjp_b_date") return "고객배포일(설정)";
		if($p1=="hy_b_date") return "현금영수증";
		if($p1=="100") return "최근작업데이타";
}

function f_cate2($p1){
		if($p1=="doc_receive_date") return "서류수령일";
		if($p1=="comp_req_date") return "완증요청일";
		if($p1=="comp_rec_date") return "완증수령일";
		if($p1=="prob_apply_date") return "검인신청일";
		if($p1=="bond_req_date") return "채최요청일";
		if($p1=="bond_req_date") return "채최요청일";
		if($p1=="bond_1conf_date") return "채최확인일1";
		if($p1=="bond_2conf_date") return "채최확인일2";
		if($p1=="ch1_junib_date_ch") return "(1차)전열";
		if($p1=="pred_g1") return "(예상)등기접수일";
		if($p1=="g1") return "등기접수일";
		if($p1=="cert_req_date") return "실필요청일";
		if($p1=="cert_rec_date") return "실필수령일";
		if($p1=="gsil_org_date") return "실거래일자(원)";
		if($p1=="gsil_last_date") return "실거래일자(마지막전매)";
		if($p1=="fir_text_date") return "최초문자안내일";
		if($p1=="mibi_doc_date") return "미비서류안내일";
		if($p1=="mibi_doc_his_date") return "미비서류수령일";
		if($p1=="mibi_doc_dun_date") return "미비서류독촉일";
		if($p1=="etc_text_date") return "기타문자발송일";
		if($p1=="balance_date") return "잔금일";
		if($p1=="vir_acc_date") return "가상계좌생성일";
		if($p1=="woori_sand_date") return "우리모아전송일";
		if($p1=="accep_date") return "수납일";
		if($p1=="al1_acp_date") return "취득세신고일";
		if($p1=="al1_rec_date") return "취득세수령일";
		if($p1=="al1_reg_date") return "취득세전달일";
		if($p1=="r1") return "취득세납부일";
		if($p1=="gch1_cost_date") return "1차비용안내일";
		if($p1=="gch2_cost_date") return "2차비용안내일";
		if($p1=="ijeon_purrec_date") return "이전채권요청일";
		if($p1=="pre_spur_date") return "설정채권요청일";
		if($p1=="am1_pur_date") return "인지구매일";
		if($p1=="am1_relay_date") return "인지전달일";
		if($p1=="tax_text_date") return "취득세납부안내문자일";
		if($p1=="aj1_rec1_date") return "이전채권1매입일";
		if($p1=="aj1_rec2_date") return "이전채권2매입일";
		if($p1=="ba1_date") return "설정채권1매입일";
		if($p1=="bb1_date") return "설정채권2매입일";
		if($p1=="bc1_date") return "설정채권3매입일";
		if($p1=="bd1_date") return "설정채권4매입일";
		if($p1=="av1_dec_date") return "설정등록세1신고일";
		if($p1=="av1_rec_date") return "설정등록세1수령일";
		if($p1=="av1_pay_date") return "설정등록세1납부일";
		if($p1=="ax1_dec_date") return "설정등록세2신고일";
		if($p1=="ax1_rec_date") return "설정등록세2수령일";
		if($p1=="ax1_pay_date") return "설정등록세2납부일";
		if($p1=="ay1_dec_date") return "설정등록세3신고일";
		if($p1=="ay1_rec_date") return "설정등록세3수령일";
		if($p1=="ay1_pay_date") return "설정등록세3납부일";
		if($p1=="az1_dec_date") return "설정등록세4신고일";
		if($p1=="az1_rec_date") return "설정등록세4수령일";
		if($p1=="az1_pay_date") return "설정등록세4납부일";
		if($p1=="ao1_dec_date") return "말소등록세신고일";
		if($p1=="ao1_rec_date") return "말소등록세수령일";
		if($p1=="ao1_pay_date") return "말소등록세납부일";
		if($p1=="ao1_tdec_date") return "변경등록세신고일";
		if($p1=="ao1_trec_date") return "변경등록세수령일";
		if($p1=="ao1_tpay_date") return "변경등록세납부일";
		if($p1=="man_req_date") return "위임장요청일";
		if($p1=="man_rec_date") return "위임장수령일";
		if($p1=="bdoc_req_date") return "건축대장요청일";
}

//소유자 관계
function f_sou($p_name,$p_value,$p_style){
	$pp[$p_value] = " selected ";
	$imsi = "<select name='".$p_name."' id='".$p_name."' {$p_style}>";
	$imsi.= "<option value=''>--선택--</option>";
	$imsi.= "<option value='100' ".$pp[100].">OK(본인)</option>";
	$imsi.= "<option value='200' ".$pp[200].">OK(배우자)</option>";
	$imsi.= "<option value='300' ".$pp[300].">OK(그외동거가족)</option>";
	$imsi.= "<option value='400' ".$pp[400].">OK(대항력후순위세입자)</option>";
	$imsi.= "<option value='500' ".$pp[500].">세입자(대항력있음)</option>";
	$imsi.= "<option value='600' ".$pp[600].">확인중</option>";
	$imsi.= "<option value='900' ".$pp[900].">기타</option>";
	$imsi.="</select>";
	return $imsi;
}

//취득세법적용
function f_taxset($p_name,$p_value,$p_style){
	$pp[$p_value] = " selected ";
	$imsi = "<select name='".$p_name."' id='".$p_name."' {$p_style}>";
	$imsi.= "<option value=''>--선택--</option>";
	$imsi.= "<option value='1' ".$pp[1].">구법적용</option>";
	$imsi.= "<option value='2' ".$pp[2].">신법적용</option>";
	$imsi.="</select>";
	return $imsi;
}


function f_sou_s($p_name,$p_value,$p_style){
	$pp[$p_value] = " selected ";
	$imsi = "<select name='".$p_name."' id='".$p_name."' {$p_style}>";
	$imsi.= "<option value=''>--선택--</option>";
	$imsi.= "<option value='000' ".$pp["000"].">OK(전체)</option>";
	$imsi.= "<option value='100' ".$pp["100"].">OK(본인)</option>";
	$imsi.= "<option value='200' ".$pp["200"].">OK(배우자)</option>";
	$imsi.= "<option value='300' ".$pp["300"].">OK(그외동거가족)</option>";
	$imsi.= "<option value='400' ".$pp["400"].">OK(대항력후순위세입자)</option>";
	$imsi.= "<option value='500' ".$pp["500"].">세입자(대항력있음)</option>";
	$imsi.= "<option value='600' ".$pp["600"].">확인중</option>";
	$imsi.= "<option value='900' ".$pp["900"].">기타</option>";
	$imsi.="</select>";
	return $imsi;
}


function f_month_s($p_name,$p_value,$p_style){
	$pp[$p_value] = " selected ";
	$imsi = "<select name='".$p_name."' id='".$p_name."' {$p_style}>";
	$imsi.= "<option value=''>--선택--</option>";
	$imsi.= "<option value='01' ".$pp["01"].">01</option>";
	$imsi.= "<option value='02' ".$pp["02"].">02</option>";
	$imsi.= "<option value='03' ".$pp["03"].">03</option>";
	$imsi.= "<option value='04' ".$pp["04"].">04</option>";
	$imsi.= "<option value='05' ".$pp["05"].">05</option>";
	$imsi.= "<option value='06' ".$pp["06"].">06</option>";
	$imsi.= "<option value='07' ".$pp["07"].">07</option>";
	$imsi.= "<option value='08' ".$pp["08"].">08</option>";
	$imsi.= "<option value='09' ".$pp["09"].">09</option>";
	$imsi.= "<option value='10' ".$pp["10"].">10</option>";
	$imsi.= "<option value='11' ".$pp["11"].">11</option>";
	$imsi.= "<option value='12' ".$pp["12"].">12</option>";
	$imsi.="</select>";
	return $imsi;
}

function f_day_s($p_name,$p_value,$p_style){
	$pp[$p_value] = " selected ";
	$imsi = "<select name='".$p_name."' id='".$p_name."' {$p_style}>";
	$imsi.= "<option value=''>--선택--</option>";
	$imsi.= "<option value='01' ".$pp["01"].">01</option>";
	$imsi.= "<option value='02' ".$pp["02"].">02</option>";
	$imsi.= "<option value='03' ".$pp["03"].">03</option>";
	$imsi.= "<option value='04' ".$pp["04"].">04</option>";
	$imsi.= "<option value='05' ".$pp["05"].">05</option>";
	$imsi.= "<option value='06' ".$pp["06"].">06</option>";
	$imsi.= "<option value='07' ".$pp["07"].">07</option>";
	$imsi.= "<option value='08' ".$pp["08"].">08</option>";
	$imsi.= "<option value='09' ".$pp["09"].">09</option>";
	$imsi.= "<option value='10' ".$pp["10"].">10</option>";
	$imsi.= "<option value='11' ".$pp["11"].">11</option>";
	$imsi.= "<option value='12' ".$pp["12"].">12</option>";
	$imsi.= "<option value='13' ".$pp["13"].">13</option>";
	$imsi.= "<option value='14' ".$pp["14"].">14</option>";
	$imsi.= "<option value='15' ".$pp["15"].">15</option>";
	$imsi.= "<option value='16' ".$pp["16"].">16</option>";
	$imsi.= "<option value='17' ".$pp["17"].">17</option>";
	$imsi.= "<option value='18' ".$pp["18"].">18</option>";
	$imsi.= "<option value='19' ".$pp["19"].">19</option>";
	$imsi.= "<option value='20' ".$pp["20"].">20</option>";
	$imsi.= "<option value='21' ".$pp["21"].">21</option>";
	$imsi.= "<option value='22' ".$pp["22"].">22</option>";
	$imsi.= "<option value='23' ".$pp["23"].">23</option>";
	$imsi.= "<option value='24' ".$pp["24"].">24</option>";
	$imsi.= "<option value='25' ".$pp["25"].">25</option>";
	$imsi.= "<option value='26' ".$pp["26"].">26</option>";
	$imsi.= "<option value='27' ".$pp["27"].">27</option>";
	$imsi.= "<option value='28' ".$pp["28"].">28</option>";
	$imsi.= "<option value='29' ".$pp["29"].">29</option>";
	$imsi.= "<option value='30' ".$pp["30"].">30</option>";
	$imsi.= "<option value='31' ".$pp["31"].">31</option>";
	$imsi.="</select>";
	return $imsi;
}

//소유자 관계 값
function f_sou_value($p_value){
	if($p_value=="000") return "OK(전체)";
	if($p_value=="100") return "OK(본인)";
	if($p_value=="200") return "OK(배우자)";
	if($p_value=="300") return "OK(그외동거가족)";
	if($p_value=="400") return "OK(대항력후순위세입자)";
	if($p_value=="500") return "세입자(대항력있음)";
	if($p_value=="600") return "확인중";
	if($p_value=="900") return "기타";
}

//소유자 관계 값
function f_sou1_value($p_value){
	if($p_value=="100") return "OK(본인)";
	if($p_value=="200") return "OK(배우자)";
	if($p_value=="300") return "OK(그외동거가족)";
	if($p_value=="400") return "OK(대항력후순위세입자)";
	if($p_value=="500") return "세입자(대항력있음)";
	if($p_value=="600") return "확인중";
	if($p_value=="900") return "기타";
}
//취득세감면사유 값
function f_tax_cut_cause_value($p_value){
	if($p_value=="0") return "취감면전체";
	if($p_value=="1") return "나라사랑대출";
	if($p_value=="2") return "신혼부부";
	if($p_value=="3") return "생애최초";
	if($p_value=="4") return "임대사업자";
	if($p_value=="5") return "공공임직원";
	if($p_value=="6") return "유치원";
	if($p_value=="7") return "기타";
}

//취득유형 값
function f_apply_type_value($p_value){
	if($p_value=="1") return "아파트";
	if($p_value=="2") return "오피스텔";
	if($p_value=="3") return "상가";
}

//취득유형 값
function f_area_gubun_value($p_value){
	if($p_value=="1") return "특광지역";
	if($p_value=="2") return "기타지역";
}

//다주택여부 유형 값
function f_multihouse_type_value($p_value){
	if($p_value=="1") return "1주택";
	if($p_value=="2") return "일시적2주택";
	if($p_value=="3") return "2주택";
	if($p_value=="4") return "3주택";
	if($p_value=="5") return "4주택이상";
	if($p_value=="6") return "법인";
}

//신탁여부 값
function f_turst_gubun_value($p_value){
	global $pdo;
	$sql = "select trust_gubun from tbl_hyunjang_info where h_idx='{$p_value}'";
	//Echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$row = $stmt->fetch();
	if($row[trust_gubun]=="0") return "신탁없음";
	if($row[trust_gubun]=="1") return "건물만 신탁";
	if($row[trust_gubun]=="2") return "건물+토지 신탁";
}

//신탁여부 값
function f_turst_gubun0_value($p_value){
	if($p_value=="0") return "신탁없음";
	if($p_value=="1") return "건물만 신탁";
	if($p_value=="2") return "건물+토지 신탁";
}

//필지갯수  값
function f_lot_amount_value($p_value,$p2_value){
	global $pdo;
	$sql = "select lot_amount from tbl_hyunjang_danji_info where h_idx='{$p_value}' and danji_name='{$p2_value}' ";
	//Echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$row = $stmt->fetch();
	return $row[lot_amount];
}

//취득유형 값
function f_yn_value($p_value){
	if($p_value=="y"){
		return "1";
	} else {
		return "0";
	}
}


//보수료 상세정보 조회
function f_bosu_set_value($p_value,$p_value1,$p_value2,$p_value3,$p_value4,$p_value5){
	global $pdo;
	$sql = "SELECT * FROM tbl_bosu_cost_set WHERE (('{$p_value1}'='y' and member_gubun = '1') or ('{$p_value2}'='y' and member_gubun = '2') or ('{$p_value3}'='y' and member_gubun = '3') or ('{$p_value4}'='y' and member_gubun = '4')) AND h_idx = '{$p_value}' AND gigan_gubun = '{$p_value5}' ORDER BY member_gubun, dachul_gubun desc";
	//echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$imsi = "<table>";
	while($row = $stmt->fetch()){
		
		if($row[member_gubun]=="1"){
			 $member_name = "정회원";
		} else if($row[member_gubun]=="2"){
			 $member_name = "(비)회원";
		} else if($row[member_gubun]=="3"){
			 $member_name = "일반회원";
		} else if($row[member_gubun]=="4"){
			 $member_name = "웹회원";
		}
		
		if($row[dachul_gubun]=="y"){
			 $dachul_name = "대출";
		} else if($row[dachul_gubun]=="n"){
			 $dachul_name = "무대출";
		}
		
		$imsi.= "<tr> <td> ".$member_name."(".$dachul_name.") </td> <td>".f_money0($row[ijeon_bosu_cost])."원(".f_money0($row[ijeon_bosu_vat])."원) </td> <td>".f_money0($row[proof_cost])	."원 </td> <td>".f_money0($row[sintak_bosu_cost])."원(".f_money0($row[sintak_bosu_vat])."원) </td></tr>";
	}
	$imsi.="</table>";

	if($imsi=="<table></table>") {
		return "-";
	}else{
		return $imsi;
	}
	
}



//현장 등기소 이름
function f_registery_office_value($p_value){
	global $pdo;
	$sql = "select registery_office from tbl_hyunjang_info where h_idx='{$p_value}'";
	//echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$row = $stmt->fetch();

	if($row[registery_office]=="") {
		return "";
	}else{
		return $row[registery_office];
	}
}

// 회원엑셀업로드 이력조회
function f_mem_upload_value($h_idx){
	global $pdo;
	$sql = "select e_upload_date from tbl_member_upload where h_idx='{$h_idx}'";
	//echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$row = $stmt->fetch();

	if($row[e_upload_date]=="") {
		return "";
	}else{
		return $row[e_upload_date];
	}
}

//설정등기원인일 가져오기
function f_reg_cause_date_value($p_value,$p2_value){
	global $pdo;
	$sql = "select reg_cause_date from tbl_suljung where a1='{$p_value}' and  suljung_no='{$p2_value}'";
	//echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$row = $stmt->fetch();

	if($row[reg_cause_date]=="") {
		return "";
	}else{
		return $row[reg_cause_date];
	}
}
//소속
function f_sosok($p_value){
	global $pdo;
	if($p_value!=""){
		$sql = "select sosok_name from tbl_sosok where sosok_code='{$p_value}'";
		//echo $sql."<br>";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$row = $stmt->fetch();

		if($row[0]=="") {
			return "";
		}else{
			return $row[0];
		}
	}else{
			return "";
	}
}


//담당자(외주) id 값
function f_damdang_oiju_value($p_value){
	global $pdo;
	$sql = "select name,id from tbl_user where id='{$p_value}'";
	//echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$row = $stmt->fetch();

	if($row[name]=="") {
		return "";
	}else{
		return $row[name]." / ".$row[id];
	}
}


//id->이름
function f_id_value($p_value){
	global $pdo;
	$sql = "select name from tbl_user where id='{$p_value}' limit 1";
	//echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$row = $stmt->fetch();

	if($row[name]=="") {
		return "";
	}else{
		return $row[name];
	}
}

//a1 -> 회원종류
function f_member_value($p_value){
	global $pdo;
	$sql = "select member_gubun from tbl_member_manual where a1='{$p_value}' limit 1";
	//echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$row = $stmt->fetch();

	if($row[member_gubun]=="1"){
		return "정회원";
	}else if($row[member_gubun]=="2"){
		return "(비)회원";
	}else if($row[member_gubun]=="3"){
		return "일반회원";
	}else if($row[member_gubun]=="4"){
		return "웹회원";
	}else{
		return "(비)회원";
	}
}

//수동보수료 count
function f_bosu_cnt_value($p_value){
	global $pdo;
	$sql = "select count(*) as cnt from tbl_bosu_cost_manual where h_idx='{$p_value}' limit 1";
	//echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$row = $stmt->fetch();

	return $row[cnt];
}

//a1 -> 회원종류
function f_membercode_value($p_value){
	if($p_value=="1") return "정회원";
	if($p_value=="2") return "(비)회원";
	if($p_value=="3") return "일반회원";
	if($p_value=="4") return "웹회원";
}

//a1 -> 신탁보수료/소이등보수/제증명 계산
function f_member_cost_value($p_value,$p2_value){
	global $pdo;
	$p_date=date("Ymd");
	// 수동보수료정보
	$sql = "select * from tbl_bosu_cost_manual where a1='{$p_value}' limit 1";
	//echo $sql;
	$row3 = db_query_fetch($sql);

	if ($row3[a1]!="") {
	 	$ijeon_bosu = $row3[ijeon_bosu_cost] + $row3[ijeon_bosu_vat];
	 	$proof_cost = $row3[proof_cost];
	 	$sintak_bosu = $row3[sintak_bosu_cost] + $row3[sintak_bosu_vat];
	}	else {
		
		$sql = "select * from tbl_member_manual where a1='{$p_value}' limit 1";
		//echo $sql."<br>";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$row = $stmt->fetch();

		// 회원구분
		if($row[member_gubun]!=""){
			$member_gubun = $row[member_gubun];
		}else{
			$member_gubun = "2";
		}
		
		// 고객정보 (f1 / 설정갯수)
		$sql = "select * from tbl_junib where a1='{$p_value}' limit 1";
		//echo $sql;
		$row1 = db_query_fetch($sql);

		// 대출여부
		if($row[f1]!="0"){
			$dachul_gubun = "y"; // 대출여부
		} else {
			$dachul_gubun = "n"; // 대출여부
		}
		
		// 현장정보
		$sql = "select * from tbl_hyunjang_info where h_idx='{$row1[h_idx]}' limit 1";
		//echo $sql;
		$row2 = db_query_fetch($sql);

		// 기간구분
		if ($row1[ipju_app_s]<=$p_date && $row1[ipju_app_e]>=$p_date) { // 1.입주기간내
			$gigan_gubun = "1";
		} else if ($row1[ipju_app_e]< $p_date && $row1[bosu_stn_date]>=$p_date) { // 2.입주기간종료후 보수기준까지
			$gigan_gubun = "2";
		} else if ($row1[bosu_stn_date]< $p_date) { // 3.보수기준일 다음날부터
			$gigan_gubun = "3";
		}


		// 설정 보수료정보
		$sql = "select * from tbl_bosu_cost_set where h_idx='{$row1[h_idx]}' and gigan_gubun='{$gigan_gubun}' and member_gubun='{$member_gubun}' and dachul_gubun='{$dachul_gubun}' limit 1";
		//echo $sql;
		$row4 = db_query_fetch($sql);
		
		$ijeon_bosu = $row4[ijeon_bosu_cost] + $row4[ijeon_bosu_vat];
	 	$proof_cost = $row4[proof_cost];
	 	$sintak_bosu = $row4[sintak_bosu_cost] + $row4[sintak_bosu_vat];

		// 원단위 절삭 소숫점 필요시 수정
	 	$ijeon_bosu = floor( $ijeon_bosu / 10 ) * 10;  //1단위 절삭
	 	$proof_cost = floor( $proof_cost / 10 ) * 10;  //1단위 절삭
	 	$sintak_bosu = floor( $sintak_bosu / 10 ) * 10;  //1단위 절삭

	}

	if($p2_value=="1"){
		return $ijeon_bosu;
	}else if($p2_value=="2"){
		return $proof_cost;
	}else if($p2_value=="3"){
		return $sintak_bosu;
	}else{
		return "";
	}
}


//담당자(외주) id
function f_damdang_oiju($p_name,$p_value,$p_style){
	global $pdo;

	$sql = "select name,id from tbl_user where grade=300 order by name asc,id asc ";
	//echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$pp[$p_value] = " selected ";
	$imsi = "<select name='".$p_name."' id='".$p_name."' {$p_style}>";
	$imsi.= "<option value=''>--선택--</option>";

	while($row = $stmt->fetch()){
		$imsi.= "<option value='{$row[id]}' ".$pp[$row[id]].">{$row[name]}/({$row[id]})</option>";
	}
	$imsi.="</select>";

	return $imsi;
}



//현장select
function f_hyunjang_select($p_name,$p_value,$p_style){
	global $pdo;

	$sql = "select h_name,h_idx from tbl_hyunjang_info order by regist_date desc";
	//echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$pp[$p_value] = " selected ";
	$imsi = "<select name='".$p_name."' id='".$p_name."' {$p_style} onchange='javascript:f_h_idx();'>";
	$imsi.= "<option value=''>--선택--</option>";

	while($row = $stmt->fetch()){
		if($row[h_idx]==$p_value){
			$imsi.= "<option value='{$row[h_idx]}' selected>{$row[h_name]}</option>";
		}else{
			$imsi.= "<option value='{$row[h_idx]}'>{$row[h_name]}</option>";
		}
	}
	$imsi.="</select>";

	return $imsi;
}



//현장select2  - 선택없이 필수인경우
function f_hyunjang_select2($p_name,$p_value,$p_style){
	global $pdo;

	$sql = "select h_name,h_idx from tbl_hyunjang_info order by regist_date desc";
	//echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$pp[$p_value] = " selected ";
	$imsi = "<select name='".$p_name."' id='".$p_name."' {$p_style} onchange='javascript:f_h_idx();'>";

	while($row = $stmt->fetch()){
		if($row[h_idx]==$p_value){
			$imsi.= "<option value='{$row[h_idx]}' selected>{$row[h_name]}</option>";
		}else{
			$imsi.= "<option value='{$row[h_idx]}'>{$row[h_name]}</option>";
		}
	}
	$imsi.="</select>";

	return $imsi;
}



function f_grade($p1){
	if($p1==1) return "마스터";
	if($p1==100) return "일반";
	if($p1==200) return "관리자";
	if($p1==300) return "외주";
	if($p1==999) return "퇴사";
}

$sys_address = $_SERVER['REMOTE_ADDR'];
$Programmer = "121.171.183.106";## 프로그래머 아이피{ DEBUG() 함수 사용시 Programmer에 아이피를 넣어야지만 디버그 함수 실행됩니다.}
###################################################################################################
## DEBUG_R 배열의 구조를 보여준다.
###################################################################################################

function DEBUG_R($val){
	global $sys_address,$Programmer;
	if($sys_address == $Programmer){
		if(is_array($val)){
			foreach($val as $key=>$value) {
				$temp_name = $key;
				${$temp_name} = addslashes($value);
				DEBUG("$temp_name",${$temp_name});
			}
		}
	}
}
###################################################################################################
## DEBUG 프린터
###################################################################################################
function DEBUG($str,$val){
	global $sys_address,$Programmer;
	if($sys_address == $Programmer){
		$str = "<span style=\"font-family:tahoma; font-size:11px;color:#CC0000\"><li>$str</li></span>\n";
		$str .= "<span style=\"font-family:tahoma; font-size:11px;color:Royalblue\"> = {$val}</span><br>\n";
		echo $str;
	}
}
###################################################################################################
## 입력된 데이터가 nodd 라면 'nodd' 라고 바꾸고, 공백문자면 NULL로 바꾼다.
###################################################################################################
function add_null(&$text) {
	$text = ($text=='') ? 'NULL' : "'$text'";
}
###################################################################################################
## 입력된 데이터가 nodd 라면 'nodd' 라고 바꾸고, 공백문자면 NULL로 바꾼다.
###################################################################################################
function add_space(&$text) {
	$text = ($text=='') ? "''" : "'$text'";
}
###################################################################################################
## 디비리스트 출력하기
###################################################################################################
function db_list() {
	GLOBAL $sys_dbname;
	return mysql_list_tables($sys_dbname);
}
###################################################################################################
## 디비내의 존재하는 테이블 비교
###################################################################################################
function db_exist($exist) {
    $result = db_list();
	$row_exist = db_numrows($result);
    for ($i = 0; $i < $row_exist; $i++) {
    $rec_exist = db_result($result, $i, 0);
      if ($exist == $rec_exist) {
              $exist_out = "1";
       }
    }
		return $exist_out;
}
###################################################################################################
## mysql_error 에러 프린터
###################################################################################################
function err_chk($states){
	if(!$states){
		$errno = mysql_errno();
		$errmsg = mysql_error();

		$str = "<span style=\"font-family:tahoma; font-size:11px;color:#CC0000\"><li>에러번호 [{$errno}] : </li></span>";
		$str .= "<span style=\"font-family:tahoma; font-size:11px;color:Royalblue\"> {$errmsg}</span><br>";

		echo $str;
		exit;
	}
}
###################################################################################################
## 핸들을 가지고 있다면, 데이터의 Row 수를 Return 하고, 아니면 0을 Return한다.
###################################################################################################
function db_numrows($qhandle) {
	if ($qhandle) {
		return @mysql_numrows($qhandle);
	} else {
		return 0;
	}
}
###################################################################################################
## 핸들의 해당 Row와 Field의 값을 구합니다.
###################################################################################################
function db_result($qhandle,$row,$field=0) {
	return @mysql_result($qhandle,$row,$field);
}
###################################################################################################
## 한개의 Row, 한개의 Field의 값을 구합니다.
###################################################################################################
function db_query_result($qstring,$errdie=1,$errprint=1) {
	$result = db_query($qstring,$errdie,$errprint);
	return @db_result($result,0);
}
###################################################################################################
## 핸들의 해당 Field의 갯수를 구합니다.
###################################################################################################
function db_numfields($lhandle) {
	return @mysql_numfields($lhandle);
}
###################################################################################################
## 필드번호를 통해 해당 번호에 해당하는 필드의 이름을 구합니다.
###################################################################################################
function db_fieldname($lhandle,$fnumber) {
	return @mysql_fieldname($lhandle,$fnumber);
}
###################################################################################################
## 이전 MySQL 작업에서 영향을 받은 row 수를 구합니다.
###################################################################################################
function db_affected_rows($qhandle) {
	return @mysql_affected_rows();
}

###################################################################################################
## 총 Row 수를 구합니다.
###################################################################################################
function db_count_all($table,$where="") {
	global $pdo;
	$cc = 0;

	$sql = "SELECT count(*) cc FROM {$table} {$where}";
	//echo "<br><br><br>".$sql;
	$result = $pdo->query($sql)->fetchColumn();

	if ($result) return $result;
	else return 0;
}
###################################################################################################
## 핸들을 이용해서 결과물을 구합니다.
###################################################################################################
function db_fetch_array($qhandle,$errdie=0,$errprint=0) {
	if (db_affected_rows($qhandle))	return @mysqli_fetch_array($qhandle);
	else {
		if ($errprint)
			echo "<br><font color=red style='font-size:12'>에러가 발생했습니다.<br>에러: DB결과가 없는 상황에서 fetch_array를 시도했습니다.</font><br><br>";
		if ($errdie) exit;
		return array();
	}
}
###################################################################################################
## 쿼리문을 이용해서 결과물을 구합니다.
###################################################################################################
function db_query_fetch($qstring,$errdie=1,$errprint=1) {
	$result = db_query_value($qstring,$errdie,$errprint);
	return $result;
}
###################################################################################################
## 연관배열을 DB에 입력하는 함수
##-------------------------------------------------------------------------------------------------
## 예:
## $kv[date_out] = $date_out;
## $kv[id_processtype] = $id_processtype;
## $kv[name] = $name;
## db_insert($kv,"user");
###################################################################################################
function db_insert($kv,$table) {
	global $pdo;
	for(reset($kv);$key=key($kv);next($kv)) {
		$val = $kv[$key]; add_space($val);
		$keys .= $key.",";
		$vals .= $val.",";
	}
	$keys = substr($keys,0,strlen($keys)-1);
	$vals = substr($vals,0,strlen($vals)-1);

	if(($table=="tbl_junib")||($table=="tbl_sugum")||($table=="tbl_bank_jijum_rate")||($table=="tbl_hyunjang_danji_info")){
		if(($kv[h_idx]!="")&&($kv[h_idx]!="0")){
			$query = "insert into $table ($keys) values ($vals)";
			//echo $query."<br>";
			$stmt = $pdo->prepare($query);
			$stmt->execute();
			return $pdo->lastInsertId();
		}
	}else{
		$query = "insert into $table ($keys) values ($vals)";
		//echo $query."<br>";
		$stmt = $pdo->prepare($query);
		$stmt->execute();
		return $pdo->lastInsertId();
	}
}

function f_hyunjang_name($p1){
	global $pdo;
	$sql = "select h_name from tbl_hyunjang_info where h_idx='{$p1}' limit 1";
	//echo "-".$sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	//$row = db_query_fetch($sql,0,0);
	return $row[h_name];
}

function f_hyunjang_danji_addr($p1, $p2){
	global $pdo;
	$sql = "select doro_addr from tbl_hyunjang_danji_info where h_idx='{$p1}' and danji_name='{$p2}' limit 1";
	//echo "-".$sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	//$row = db_query_fetch($sql,0,0);
	return $row[doro_addr];
}

//은행명 넣으면 은행코드로 반환
function f_bank_name_code($p1){
	global $pdo;
	$sql = "select bank_code from tbl_bank_info where bank_alias= '{$p1}'";  //2글자 한글과 매칭여부판별
	//echo "-".$sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	//$row = db_query_fetch($sql,0,0);
	return $row[bank_code];
}


//은행코드 넣으면 은행명로 반환
function f_bank_name($p1){
	global $pdo;
	$sql = "select bank_name from tbl_bank_info where bank_code= '{$p1}' limit 1";  //2글자 한글과 매칭여부판별
	//echo "-".$sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	//$row = db_query_fetch($sql,0,0);
	return $row[bank_name];
}

//은행코드 넣으면 약식명칭로 반환
function f_bank_alias($p1){
	global $pdo;
	$sql = "select bank_alias from tbl_bank_info where bank_code= '{$p1}'";  //2글자 한글과 매칭여부판별
	//echo "-".$sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	//$row = db_query_fetch($sql,0,0);
	return $row[bank_alias];
}

//은행코드 넣으면 버전정보로 반환
function f_bank_ver_name($p1){
	global $pdo;
	$sql = "select ver_name from tbl_bank_info where bank_code= '{$p1}'";  //2글자 한글과 매칭여부판별
	//echo "-".$sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	//$row = db_query_fetch($sql,0,0);
	return $row[ver_name];
}


//은행코드,지점명->지점코드 반환
function f_jijum_name_code($bank_code,$jijum_name){
	global $pdo;
	$sql = "select jijum_code from tbl_bank_jijum where bank_code= '{$bank_code}' and jijum_name='{$jijum_name}'";  //2글자 한글과 매칭여부판별
	//echo $sql."<br>";

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	//$row = db_query_fetch($sql,0,0);
	return $row[jijum_code];
}


//지점정보 반환
function f_jijum_all($jijum_name){
	global $pdo;
	$sql = "select * from tbl_bank_jijum where jijum_code='{$jijum_name}'";  //2글자 한글과 매칭여부판별
	//echo $sql."<br>";
	$row = db_query_fetch($sql);
	return $row;
}


//현장정보 반환
function f_hyunjang_all($h_idx){
	global $pdo;
	$sql = "select * from tbl_hyunjang_info where h_idx='{$h_idx}'";
	//echo $sql."<br>";
	$row = db_query_fetch($sql);
	return $row;
}


//전입마스터자료 반환
function f_junib_all($a1){
	global $pdo;
	$sql = "select * from tbl_junib where a1='{$a1}'";
	//echo $sql."<br>";
	$row = db_query_fetch($sql);
	return $row;
}


//은행코드,지점명->지점명으로 반환
function f_jijum_name($p1){
	global $pdo;
	$sql = "select jijum_name from tbl_bank_jijum where jijum_code='{$p1}' limit 1";
	//echo $sql."<br>";

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	//$row = db_query_fetch($sql,0,0);
	return $row[jijum_name];
}

//은행코드 넣으면 은행명으로 반환
function f_bank_code_name($p1){
	global $pdo;
	$sql = "select bank_name from tbl_bank_info where bank_code='{$p1}'";
	//echo "-".$sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	//$row = db_query_fetch($sql,0,0);
	return $row[bank_name];
}

function f_jijum_code_name($p1){
	global $pdo;
	$sql = "select jijum_name from tbl_bank_jijum where jijum_code='{$p1}'";
	//echo "-".$sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	//$row = db_query_fetch($sql,0,0);
	return $row[jijum_name];
}

function f_a1_name($p1,$p2,$p3){
	global $pdo;
	$sql = "select no_text from tbl_hyunjang_info where h_idx='{$p1}'";
	//echo "-".$sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	//$row = db_query_fetch($sql,0,0);
	return $row[no_text]+$p2+"동"+$p3+"호";
}

function f_gubun_name($p1){
	if($p1=="basic") return "기본";
	if($p1=="bonjum") return "본점제휴";
}

function f_gubun2_name($p1){
	if($p1=="basic") return "기본";
	if($p1=="gukto") return "국토부";
}

function f_point_data_name($p1, $p2){
	global $pdo;
	$sql = "select point_data_name from tbl_junib where a1='{$p2}'";
	//echo "-".$sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();


	if($p1=="1") return "이전ONLY";
	if($p1=="2") return "설정ONLY";
	if($p1=="3"){
		return $row[point_data_name];
	}
	if($p1=="0") return "";
}

function f_point_data_name_etc($p1){
	global $pdo;
	$sql = "select point_data_name from tbl_junib where a1='{$p1}'";
	//echo "-".$sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();


	return $row[point_data_name];
}


###################################################################################################
## 연관배열로 받은 레코드가 DB에 있으면 업데이트하고 있으면 인서트하는 함수
## $updatewhere 인 것이 있으면 업데이트, 없으면 인서트
## $increcolname 은, $table 에 있는 AUTO_INCREMENT 인 칼럼이름, 없으면 아무거나 PK
###################################################################################################
function db_replace($kv,$table,$updatewhere,$increcolname) {
	global $pdo;
	$sql = "select $increcolname from $table $updatewhere";
	//echo "-".$sql;

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	//$row = db_query_fetch($sql,0,0);
	$updated = $row[$increcolname];

	//echo "--".$updated."--";

	if ($updated=="") {	//기존 값이 없으므로 인서트해야함
		//echo "-insert";
		return db_insert($kv,$table);
	}else{
		//echo "-edit";
		for(reset($kv);$key=key($kv);next($kv)) {
			$val = $kv[$key]; add_space($val);
			$str .= "$key={$val},";
		}
		$str = substr($str,0,strlen($str)-1);
		$query = "update $table set $str $updatewhere";
		//echo $query;
		$result = db_query($query);
		if ($updated) { return $updated; }
	}

}
###################################################################################################
## 결과물을 보여주도록 하는 함수
###################################################################################################
function get_result_all($table,$fields="*",$where,$orderby,&$result) {

	$totalnum = db_count_all($table,$where);

	$where1 = str_replace("'",'"',$where1);

	$result = db_query("select $fields from $table $where $orderby");
  //echo "select $fields from $table $where $orderby<br>";
}
###################################################################################################
## 결과물을 페이지단위로 보여주도록 하는 함수
###################################################################################################
function get_result_for_page($table,$fields="*",$where,$orderby,$num_per_page,$pre_link,&$page,
							&$totalnum,&$pageselector,&$result,&$total_page_num,
							&$prevpage,&$nextpage,&$vnum, $where1) {

	$result = db_query("select $fields from $table $where $orderby");

	$totalnum = mysql_num_rows($result);

	$page = ($page) ? $page : 1;
	$num_offset = $num_per_page  * ($page-1);
	$vnum=$totalnum-$num_per_page*($page-1);

	$where1=str_replace("'",'"',$where1);
	//이전,다음페이지와, 페이지 셀렉터 구하기
	$total_page_num = ceil($totalnum / $num_per_page);
	if ($page > 1) {
		$prevpage = "$pre_link&page=".($page - 1). "&where=".$where1;
	}
	if ($page < $total_page_num) {
		$nextpage = "$pre_link&page=".($page + 1). "&where=".$where1;
	}
	$ps1 = $page-(($page-1)%10);
	//$ps1 = (floor(($page-1)/10)*10)+1;
	for($i=0;$i<10;$i++) {
		$tmppage = $ps1 + $i;
		if ($tmppage <= $total_page_num) {
			if ($tmppage==$page) {
				$pageselector .= " <b>[$tmppage]</b> ";
			}else{
				$pageselector .= " <a href='$pre_link&page=$tmppage&vnum=$vnum&where=$where1'>[$tmppage]</a> ";
			}
		}
	}
	if ($ps1 > 1) {
		$pageselector = " <a href='$pre_link&page=1'>[1]</a> ".
			" <a href='$PHP_SELF?bbsid=$bbsid&page=".($ps1-10)."&where=$where1'>[prev]</a> - ".$pageselector;
	}
	if ($ps1 < ($total_page_num-(($total_page_num-1)%10))) {
		$pageselector .= " - <a href='$pre_link&page=$total_page_num&where=$where1'>[$total_page_num]</a> ".
			" <a href='$pre_link&page=".($ps1+10)."&where=$where1'>[next]</a>";
	}
	$pageselector = "<font size='1'>$pageselector</font>";
	//페이지 셀렉터 구하기 끝

	$result = db_query("select $fields from $table $where $orderby limit $num_offset, $num_per_page");
//  echo "select $fields from $table $where $orderby limit $num_offset, $num_per_page";

}
###################################################################################################
## 쿼리 수행 함수
###################################################################################################
function db_query_value($sql,$errdie=1,$errprint=1) {
	global $pdo;

	//DEBUG("db_query",$pdo);
	//echo $sql."<br>";

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$result = $stmt->fetch();

	//echo $sql;

	//print_r($result);

	//$result = @mysql($pdo,$qstring);

	if (!$result && $errprint) {

		//$errno = mysql_errno();
		//$errmsg = mysql_error();

		$str = "<span style=\"font-family:tahoma; font-size:11px;color:#000000\"><li>▼에러가 발생했습니다.</li></span><br>\n";
		$str .= "<span style=\"font-family:tahoma; font-size:11px;color:#CC0000\"><li>에러번호- [{$errno}] : </li></span>\n";
		$str .= "<span style=\"font-family:tahoma; font-size:11px;color:Royalblue\"> {$errmsg}</span><br>\n";
		$str .= "<span style=\"font-family:tahoma; font-size:11px;color:#CC0000\"><li>에러 쿼리 : =></li></span>\n";
		$str .= "<span style=\"font-family:tahoma; font-size:11px;color:Royalblue\"> {$qstring}</span><br>\n";

		//////////////////////////////////////
		// 프로그램 작업시만 열어 놓고 하세요.
		//echo $str;

		//if ($errdie) exit;
		//////////////////////////////////////
	}
	return $result;
}


function db_query($sql,$errdie=1,$errprint=1) {
	global $pdo;

	//DEBUG("db_query",$pdo);
	//echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	//$result = $stmt->fetch();

	//echo "<br>".$sql;

	//print_r($result);

	//$result = @mysql($pdo,$qstring);

	if (!$result && $errprint) {

		//$errno = mysql_errno();
		//$errmsg = mysql_error();

		$str = "<span style=\"font-family:tahoma; font-size:11px;color:#000000\"><li>▼에러가 발생했습니다.</li></span><br>\n";
		$str .= "<span style=\"font-family:tahoma; font-size:11px;color:#CC0000\"><li>에러번호 [{$errno}] : </li></span>\n";
		$str .= "<span style=\"font-family:tahoma; font-size:11px;color:Royalblue\"> {$errmsg}</span><br>\n";
		$str .= "<span style=\"font-family:tahoma; font-size:11px;color:#CC0000\"><li>에러 쿼리 : =></li></span>\n";
		$str .= "<span style=\"font-family:tahoma; font-size:11px;color:Royalblue\"> {$qstring}</span><br>\n";

		//////////////////////////////////////
		// 프로그램 작업시만 열어 놓고 하세요.
		//echo $str;

		//if ($errdie) exit;
		//////////////////////////////////////
	}
	return $result;
}



####################################################################################################################
## 페이징 _Make_Link()
####################################################################################################################
function _Make_Link($total_data,$list_config="",$page_config="",$page="",$Link_Value, $img_pp, $img_p, $img_nn, $img_n) {
	//if(!$var) $imgs = "admin_page/images"; else $imgs = "images";
	//echo "전채게시물 갯수 : ".$total_data."<br>";

	if(!$page) $page = 1; //page 가 없다면.. 첫페이지로 설정..
	$list_total = ceil($total_data / $list_config); //전체 페이지
	$list_first = $list_config * ($page - 1); //쿼리시작 번호
	$list_last = $list_config * $page; // 마지막.. 리스트.....
	$list_next = $total_data - $list_last; // 다음.. 리스트..
	if ($list_next<0) $list_last = $total_data; //다음.. 리스트가 영보다 작다면..전체자료수로 한다..
	$page_a = $page -1; // 다음.. 페이지
	$page_b = $page +1; // 이전 페이지..
	if(!$page_now) $page_now = ceil($page / $page_config); //현제페이지를 구한다...
	$page_total = ceil($list_total / $page_config); //전체 페이지
	$page_first_a = ($page_now -1) * $page_config; // 첫번제 페이지..
	$page_first = $page_first_a+1; // 앞 페이지..
	$page_last = $page_now * $page_config; // 다음 페이지..
	if($page_last > $list_total) $page_last = $list_total; // 다음페이지가 전체 페이지보다 크다면..
	$page_c = $page_now -1;//넘겨주는 페이지 값이 작을 경우
	if($page_c <=0 ) $page_c = 1;
	$page_e = $page_first -$page_config;
	if($page_e <= 0) $page_e  = 1;


	if($page_now <= 1) $text = "<a tabindex='0' class='first ui-corner-tl ui-corner-bl fg-button ui-button ui-state-default' id='DataTables_Table_0_first'><</a>&nbsp;";
//	else $text .="<a tabindex='0' class='first ui-corner-tl ui-corner-bl fg-button ui-button ui-state-default' id='DataTables_Table_0_first' href='{$Link_Value}&page_now={$page_c}&page={$page_e}'><<</a>&nbsp;";
	else $text .="<a tabindex='0' class='first ui-corner-tl ui-corner-bl fg-button ui-button ui-state-default' id='DataTables_Table_0_first' href='javascript:f_movex3({$page_c},{$page_e})'><<</a>&nbsp;";

	$text .="<span>";

	if($page <= 1) $text .="";
	else $text .="<a href='".$Link_Value."&page=$page_a'></a>";

		//$text .="&nbsp;&nbsp;";
		for ($j=$page_first;$j<=$page_last;$j++) {
			if ($page == $j){  //같을때
				$text .="<a tabindex='0' class='fg-button ui-button ui-state-default ui-state-disabled'>{$j}</a>&nbsp;";
			}else{
				$text .="<a tabindex='0' class='fg-button ui-button ui-state-default' href='javascript:f_movex2({$j})'>{$j}</a>&nbsp;";
			}
		}

	if($page >= $list_total) $text .="";//page   +1// 넘겨주는 페이지값이 더 클경우
	else $text .="<a href='".$Link_Value."&page=$page_b'></a>";

	$page_d = $page_now +1;// page + page_config
	$page_e = $page_first + $page_config;
	if($page_e > $list_total) $page_e  = $list_total;

	$text .="</span>";
	if($page_now >= $page_total) $text .="<a tabindex='0' class='next fg-button ui-button ui-state-default' id='DataTables_Table_0_next'>></a>&nbsp;";
//	else $text .="<a tabindex='0' class='last ui-corner-tr ui-corner-br fg-button ui-button ui-state-default' id='DataTables_Table_0_last' href='{$Link_Value}&page_now={$page_c}&page={$page_e}'>>></a>";
	else $text .="<a tabindex='0' class='first ui-corner-tl ui-corner-bl fg-button ui-button ui-state-default' id='DataTables_Table_0_first' href='javascript:f_movex3({$page_c},{$page_e})'>>></a>&nbsp;";


	$result[link] = $text; // 하단 링크.. 출력..
	$result[total] = $total_data; // 전체 자료수..
	$result[start] = $list_first; // 쿼리 시작넘버.. limit...
	//$result[post] = $link_end; // post 로 넘어온 값..

	return $result;

}


function formatSize($bytes, $decimals = 2) {
   $size = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
   $factor = floor((strlen($bytes) - 1) / 3);
   return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}


function f_de_comma($p1){  //콤마를 제거한다.
	$p1 = str_replace(",","",$p1);
	return $p1;
}


function f_de_date($p1){  //날짜 - : 삭제
	$p1 = str_replace("-","",$p1);
	$p1 = str_replace(":","",$p1);
	$p1 = str_replace(" ","",$p1);
	return $p1;
}


function f_money($p1){
	if($p1){
		return number_format($p1,0);
	}else{
		return "";
	}
}

function f_money_won($p1){
	if($p1){
		return number_format($p1,0)." 원";
	}else{
		return "";
	}
}

function f_money0($p1){
	if($p1){
		return number_format($p1,0);
	}else{
		return 0;
	}
}

function f_money4($p1){
	$sNumber = 0;
	if($p1){
		$sNumber = number_format($p1,4);
		$sNumber = rtrim($sNumber, 0);
		$sNumber= rtrim($sNumber, '.');


		return $sNumber;
		
	}else{
		return "";
	}
}

function f_next_day($datex){ //지정날짜를 넣어서 다음날이 토요일6,일요일0,인지 확인하고 +1/+2일씩 증가한다.

		$imsi_yoil = date("w", strtotime($datex." + 1day"));
		if($imsi_yoil==0) {  //다음날이 0일때는 일요일 오늘기준 +2 월요일로 설정
			$imsi_date = date("Ymd", strtotime($datex." + 2day"));
		}else if($imsi_yoil==6) {  //6일때는 토요일 +3 월요일로 설정
			$imsi_date = date("Ymd", strtotime($datex." + 3day"));
		}else{
			$imsi_date = date("Ymd", strtotime($datex." + 1day"));
		}
		return $imsi_date;
}

function f_next_3month($datex){ //지정날짜를 넣어서 다음날이 토요일6,일요일0,인지 확인하고 +1/+2일씩 증가한다.
		// 지정일자의 3개월 계산
		if($datex!=""){
		$imsi_year = substr($datex,0,4);
			$imsi_month = substr($datex,4,2);
			$imsi_day = substr($datex,6,2);
			$imsi_monday = substr($datex,4,4);
			if ($imsi_monday=="0131") {
				$imsi_date = $imsi_year."0430";
			} else if ($imsi_monday=="0331") {
				$imsi_date = $imsi_year."0630";
			} else if ($imsi_monday=="0831") {
				$imsi_date = $imsi_year."1130";
			} else if ($imsi_monday=="1130") {
				$imsi_temp = date("Ymd", strtotime($datex." + 3month"));
				//$imsi_temp_year = substr($imsi_temp,0,4);
				//$imsi_temp_month = substr($imsi_temp,4,2);
				$imsi_temp_day = substr($imsi_temp,6,2);
				if($imsi_temp_day=="01"){
					$imsi_date = date("Ymd", strtotime($imsi_temp." - 1day"));
				} else if($imsi_temp_day=="02"){
					$imsi_date = date("Ymd", strtotime($imsi_temp." - 2day"));
				}
			} else {
				$imsi_date = date("Ymd", strtotime($datex." + 3month"));
			}
			$imsi_yoil = date("w", strtotime($imsi_date));
			//echo "<script>alert({$imsi_date});</script>";
			//echo "<script>alert({$imsi_yoil});</script>";
			if($imsi_yoil==0) {  //다음날이 0일때는 일요일 오늘기준 +2 월요일로 설정
				$imsi_date = date("Ymd", strtotime($imsi_date." + 1day"));
			}else if($imsi_yoil==6) {  //6일때는 토요일 +3 월요일로 설정
				$imsi_date = date("Ymd", strtotime($imsi_date." + 2day"));
			}

	    for ($i = 0; $i < 10; $i++) {
				//이전정보설정
				$sql = "select * from tbl_holiday_set where h_date='{$imsi_date}' limit 1";
				//echo $sql;
				$row = db_query_fetch($sql);

	      if ($row[h_date] != "") {
	        $imsi_date = date("Ymd", strtotime($imsi_date." + 1day"));
	      
					$imsi_yoil = date("w", $imsi_date);
					if($imsi_yoil==0) {  //다음날이 0일때는 일요일 오늘기준 +2 월요일로 설정
						$imsi_date = date("Ymd", strtotime($imsi_date." + 1day"));
					}else if($imsi_yoil==6) {  //6일때는 토요일 +3 월요일로 설정
						$imsi_date = date("Ymd", strtotime($imsi_date." + 2day"));
					}
	      }

	    }

		} else {
			$imsi_date = "";
		}
		return $imsi_date;
}

function f_day_comp($p1,$p2){  //늦은날로부터 60일

	if($p2!=""){
		if($p1!=""){
			$p1_day = new DateTime($p1);
			$p2_day = new DateTime($p2);
			//$diff    = date_diff($p1_day, $p2_day); //오늘 기준 어제까지의 날짜 비교.
			//echo "<script>alert({$diff->days});</script>";
//			echo "<script>alert({$p1_day});</script>";
			if($p1_day > $p2_day){  //2일초과 1주이내 노랑
				$p3 = $p1;
			}else {
				$p3 = $p2;
			}
			$p3 = date("Ymd", strtotime($p3." + 60day"));
		}else{
			$p3 = "";
		}
	}else{
		$p3 = "";
	}
	return $p3;
}

function f_won_taget($p1){  //실거래/검인구분

	if($p1!=""){
		$p1_day = new DateTime($p1);
		$p2_day = new DateTime("20170120");
		//$diff    = date_diff($p1_day, $p2_day); //오늘 기준 어제까지의 날짜 비교.
		//echo "<script>alert({$diff->days});</script>";
		if($p1_day >= $p2_day){  //2일초과 1주이내 노랑
			$p3 = "실거래";
		}else {
			$p3 = "검인";
		}
	}else{
		$p3 = "";
	}
	
	return $p3;
}

function f_ic($p1){
	if ($p1=="") {
		return "<font color=white>-</font>";
	}else{
		return iconv("UTF-8", "EUC-KR",$p1);
	}
}

//문자열 길이
function f_strlen($p1,$p2){
	if(strlen($p1)>=$p2){
		return substr($p1,0,$p2);
	}else{
		return $p1;
	}
}

function f_mid_str($p1,$p2){
	if (strlen($p1)>$p2){
		return mb_substr($p1,0,$p2,'utf-8')."<br>".mb_substr($p1,$p2+1,strlen($p1),'utf-8');
	}else{
		return $p1;
	}
}

function f_mid2($p1){
	if (strlen($p1)==1){
		return "0".$p1;
	}else{
		return $p1;
	}
}

function f_mid3($p1){
	if (strlen($p1)==1){
		return "00".$p1;
	}else if (strlen($p1)==2){
		return "0".$p1;
	}else{
		return $p1;
	}
}

function f_mid4($p1){
	if (strlen($p1)==1){
		return "000".$p1;
	}else if (strlen($p1)==2){
		return "00".$p1;
	}else if (strlen($p1)==3){
		return "0".$p1;
	}else{
		return $p1;
	}
}

function get_date_diff($d1, $d2) {
	$temp = new DateTime($d1);
	$d = $temp->diff( new DateTime($d2) );
	$sign = ($d->invert==0)?1:-1;
	return $d->days*$sign;
}

function f_date_full($p1){  //날짜 풀
	if(is_null($p1) or trim($p1)==""){
		return "";
	}else{
		return substr($p1,0,4).".".substr($p1,4,2).".".substr($p1,6,2)." ".substr($p1,8,2).":".substr($p1,10,2).":".substr($p1,12,2);
	}
}

function f_date_full2($p1){  //날짜 풀
	if(is_null($p1) or trim($p1)==""){
		return "";
	}else{
		return substr($p1,0,4).".".substr($p1,4,2).".".substr($p1,6,2)." ".substr($p1,8,2).":".substr($p1,10,2);
	}
}

function f_date_yyyymmdd($p1){  //날짜 yyyy.mm.dd
	if(is_null($p1) or trim($p1)==""){
		return "";
	}else{
		return substr($p1,0,4).".".substr($p1,4,2).".".substr($p1,6,2);
	}
}

function f_date_han($p1){  //날짜 yyyy년mm월dd일
	if(is_null($p1) or trim($p1)==""){
		return "";
	}else{
		return substr($p1,0,4)."년 ".substr($p1,4,2)."월".substr($p1,6,2)."일";
	}
}

function f_date_han_yyyymm($p1){  //날짜 yyyy년mm월  일
	if(is_null($p1) or trim($p1)==""){
		return "";
	}else{
		return substr($p1,0,4)."년 ".substr($p1,4,2)."월      일";
	}
}


function f_date_yyyymmdd2($p1){  //날짜 yyyy-mm-dd
	if(is_null($p1) or trim($p1)==""){
		return "";
	}else{
		return substr($p1,0,4)."-".substr($p1,4,2)."-".substr($p1,6,2);
	}
}

function f_date_yymmdd($p1){  //날짜 yyyy.mm.dd
	if(is_null($p1) or trim($p1)==""){
		return "";
	}else{
		return substr($p1,2,2).".".substr($p1,4,2).".".substr($p1,6,2);
	}
}


function f_date($p1){
	if(is_null($p1) or trim($p1)==""){
		return "";
	}else{
		return substr($p1,0,4).".".substr($p1,4,2).".".substr($p1,6,2);
	}
}
function f_date_cut($p1){
	if(is_null($p1) or trim($p1)==""){
		return "";
	}else{
		return substr($p1,0,4).substr($p1,4,2).substr($p1,6,2);
	}
}

function f_date3($p1){
	if(is_null($p1) or trim($p1)==""){
		return "";
	}else{
		return substr($p1,0,4)."-".substr($p1,4,2)."-".substr($p1,6,2)." ".substr($p1,8,2).":".substr($p1,10,2);
	}
}

function f_date2($p1){
	if(is_null($p1) or trim($p1)==""){
		return "";
	}else if($p1==""){
		return "";
	}else if(strlen($p1)==0){
		return "";
	}else{
		return substr($p1,4,2)."/".substr($p1,6,2);
	}
}

function f_cal($p1){
	if($p1==1){
		return "January";
	}else if($p1==2){
		return "Feburary";
	}else if($p1==3){
		return "March";
	}else if($p1==4){
		return "April";
	}else if($p1==5){
		return "May";
	}else if($p1==6){
		return "June";
	}else if($p1==7){
		return "July";
	}else if($p1==8){
		return "August";
	}else if($p1==9){
		return "September";
	}else if($p1==10){
		return "October";
	}else if($p1==11){
		return "November";
	}else if($p1==12){
		return "December";
	}
}


###################################################################################################
## 상태바 표시 함수
###################################################################################################
function Status($status)
{
	echo" onMouseOver=\"javascript:window.status='$status';return true;\"";
}

###################################################################################################
## 메타이동
###################################################################################################
function Meta_Move($Url){
	echo ("<meta http-equiv='refresh' content='0; url=$Url'>");
}

###################################################################################################
## 페이지 리플레쉬
###################################################################################################
function Msg_Meta($Msg,$Href)
{
	echo "<script language='javascript'>
			 alert('$Msg');
		  </script>";
	echo("<meta http-equiv='Refresh' content='0; URL=$Href'>");
}

###################################################################################################
## 페이지 리플레쉬
###################################################################################################
function ReFresh2($href)
{
	echo "<script language='javascript'>
	         location.href='$href';
          </script>";
}

###################################################################################################
## 자바스크립트 메시지 출력 후 페이지 이동
###################################################################################################
function MsgView($Msg,$go)
{
	  echo"
		  <script language='javascript'>
		     alert('$Msg');
	         history.go($go);
		  </script>
		  ";
		  return true;
}

###################################################################################################
## 자바스크립트 메시지 출력만
###################################################################################################
function OnlyMsgView($Msg)
{
	  echo"
		  <script language='javascript'>
		     alert('$Msg');
		  </script>
		  ";
}

###################################################################################################
## 자바스크립트 메시지 출력 후 부모창페이지 이동
###################################################################################################
function MsgViewParent($msg,$href)
{
	echo"
		<script language='javascript'>
		   alert('$msg');
	       parent.location.href='$href';
		</script>
		";
		return true;
}

###################################################################################################
## 자바스크립트 메시지 출력 후 오프너페이지 이동
###################################################################################################
function MsgViewOpen($msg,$href)
{
	echo"
		<script language='javascript'>
		   alert('$msg');
	       opener.location.href='$href';
		   self.close();
		</script>
		";
		return true;
}

###################################################################################################
## 자바스크립트 메시지 출력 후 페이지 이동
###################################################################################################
function MsgViewHref($Msg,$href)
{
	echo"
		  <script language='javascript'>
		     alert('$Msg');
	         location.href='$href';
          </script>
		   ";
	return true;
}

###################################################################################################
## 자바스크립트 메시지 출력 후 페이지 닫기
###################################################################################################
function MsgViewClose($Msg)
{
	  echo "<script language=JavaScript>
			alert('$Msg');
			self.close();
			</script>";
	return true;
}

###################################################################################################
## 자바스크립트 에러메시지 출력
###################################################################################################
function ErrMsg($Msg)
{
	  $Msg= addslashes($Msg);
	  $Msg= "Err. \\n\\n".$Msg;
	  echo "<script language=JavaScript>
			alert('$Msg');
			</script>";
}

###################################################################################################
## 자바스크립트 메세지 출력후 페이지 리로드 (아이프레임에서) 재석 추가
###################################################################################################
function MsgViewifr($Msg)
{
	  echo "<script language=JavaScript>
			alert('$Msg');
			parent.location.reload();
			</script>";
		return true;
}

###################################################################################################
## 자바스크립트 메세지 출력후 페이지 리로드 (아이프레임에서) 재석 추가
###################################################################################################
function MsgViewOre($Msg)
{
	  echo "<script language=JavaScript>
			alert('$Msg');
			opener.location.reload();
			self.close();
			</script>";
		return true;
}

###################################################################
## var 값이 NULL 이면 value 값으로 리턴시킨다.
###################################################################
function GetVarCheck($var, $value){

	if(!$var) {
		$return_value = "$value";
	}else{
		$return_value = $var;
	}

	return $return_value;
}
###################################################################################################
## $n 개의 문자열과 '...' 붙이기 함수 함수
###################################################################################################
function StringCut($string,$n)  //$n : Cutting String Number
{
	if($n%2)
		$n++;
	$len=strlen($string);   //string length
	if($len<$n)
		return $string;
	else {
		$OneNextN=$n+1;
		$newstring=substr($string,0,$n);
		$total=0;
		for($i=0;$i<$n;$i++)
		{
			$asc=ord(substr($string,$i,1));
   			if($asc>128) $total++;
    		}
		if($total%2)
		{
			$newstring=substr($string,0,$OneNextN);
		}
		$newstring.="...";
		return $newstring;
	}
}

###################################################################################################
## 가격 출력 ,
###################################################################################################
function PriceFormat($price)
{
	return number_format($price, 0, "-", "`");
}

###################################################################################################
## 현재일과 특정일 사이 기간
###################################################################################################
function BetweenPeriod($datetime,$periodDay)
{//2003-02-19 11:32:15
	$now = time();
	$timeArr= explode(":",substr($datetime,11,8));
	$dayArr	= explode("-",substr($datetime,0,10));

	$mktime = mktime($timeArr[0],$timeArr[1],$timeArr[2],$dayArr[1],$dayArr[2],$dayArr[0]);
	$period	= $periodDay*24*60*60;		//기간계산

	if($now >$mktime && $now < ($mktime+$period))
		return 1;
	else if( ($mktime-$period) <$now && $now <$mktime)
		return -1;
	else
		return 0;
}

###################################################################################################
## 문장 자르고 ...
###################################################################################################
function str_cut($str, $number){
	//$str = html_move($str);
	$length = strlen($str);

	for($k = 0; $k < $number; $k++)
		if(ord(substr($str, $k, 1)) > 127)
	    	$k++;

	if($length > $number)
		$return_str = substr($str, 0, $k)."...";
	else
		$return_str = $str;

	return $return_str;
}

###################################################################################################
## 문장 자르고 엔터
###################################################################################################
function str_br($str, $number){
	$str = html_move($str);
	$length = strlen($str);

	for($k = 0; $k < $number; $k++)
		if(ord(substr($str, $k, 1)) > 127)
	    	$k++;

	if($length > $number)
		$return_str = substr($str, 0, $k) . "<br>" . substr($str, $k, $length);
	else
		$return_str = $str;

	return $return_str;
}

###################################################################################################
## 검색 이름 색 변환
###################################################################################################
function search_text_change($change_text, $original_text){
	$color_text = "<span style='background-color:YELLOW; color:RED;'>{$change_text}</span>";
	$text = eregi_replace($change_text, $color_text, $original_text);

	return $text;
}

###################################################################################################
## 이미지 리사이즈 보이기
###################################################################################################
function image_resize($file_name, $max_width, $max_height, $dot){
	$filename = $dot . urldecode($file_name);

	if($file_name && file_exists($filename)){
		$image_size = GetImageSize($filename);

		$width = $image_size[0];
		$height = $image_size[1];

		if($width > $max_width){
			$percent_w = $width / $max_width;
			$width = $max_width;
			$height = $height / $percent_w;
		}else
			$width = $width;

		if($max_height){
			if($height > $max_height){
				$percent_h = $height / $max_height;
				$height = $max_height;
				$width = $width / $percent_h;
			}else
				$height = $height;
		}

		$image_tag = "<img src=\"".$dot.$file_name."\" width=\"$width\"";

		if($height)
			$image_tag .= " height=\"$height\"";

		$image_tag .= " border=\"0\">";

		return $image_tag;
	}
}

###################################################################################################
## 경고창 후 페이지 이동
###################################################################################################
function alert_msg($kind, $comment, $location){
	if($kind == "tag"){
		$script = "javascript:alert('$comment');";

		if($location)
			$script .= "location.replace('$location');";
	}else if(!$location)
		$script = "<script language='javascript'>alert('$comment');</script>";
	else
		$script = "<script language='javascript'>alert('$comment');location.replace('$location');</script>";

	return $script;
}

###################################################################################################
##  디비 값 셀렉트
###################################################################################################
function get_options($table,$where="",$value_field="id", $name_field="name") {
	$result = db_query("select $value_field, $name_field from $table $where");
	while ($row = db_fetch_array($result)) {
		$out .= "<option value='".$row[$value_field]."'>".$row[$name_field]."</option>\n";
	}
	return $out;
}

####################################################################################################################
## selected 박스 같은값 셀렉트
####################################################################################################################
function GetSelected($Val, $str)
{
	if($Val == $str){
		$return_str = "selected";
	}else{
		$return_str = "";
	}
	return $return_str;
}

####################################################################################################################
## checkbox & radio  같은 값은 checked 시킨다.
####################################################################################################################
function GetChecked($str, $var){

	if($str == $var){
		$return_str = "checked";
	}else{
		$return_str = "";
	}


	return $return_str;
}

###################################################################
## $var, $str 값을 체카하여 Checkbox 의 값을 Checked 시킨다.
###################################################################
function GetChcked($var, $str){
	if($var == $str) {
		$return_str = "Checked";
	}else{
		$return_str = "";
	}
	return $return_str;
}

####################################################################################################################
## SearchKeyword_Change() 검색어와 같은 문자열 색상을 변경한다.
####################################################################################################################
function SearchKeyword_Change($ChangeText, $strText){

	$ColorText = "<span style='background-color:YELLOW; color:RED;'>{$ChangeText}</span>";
	//$str_Text = eregi_replace($ChangeText, $ColorText, $strText);
	$str_Text = str_replace($ChangeText, $ColorText, $strText);
	return $str_Text;
}

###################################################################
## 파일의 확장자를 제외한 파일명을 취한다
###################################################################
function get_filename($string)
{
	$temp = explode(".",$string);
	return $temp[0];
}

###################################################################
## 파일의 확장자를 취한다
###################################################################
function get_Ext($string)
{
	$temp = explode(".",$string);
	$size = sizeof($temp);
	$size--;

	# 소문자로 바꾼다.
	return strtolower($temp[$size]);
}

###################################################################
## 파일의 확장자에 따라 보여줄 파일형식이미지를 리턴한다
###################################################################
function get_Ext_image($string)
{
	switch (get_Ext($string)) {
		case "bmp" : $extimg="bmp"; break;
		case "doc" : $extimg="doc"; break;
		case "exe" : $extimg="exe"; break;
		case "gif" : $extimg="gif"; break;
		case "htm" : $extimg="htm"; break;
		case "hwp" : $extimg="hwp"; break;
		case "jpg" : $extimg="jpg"; break;
		case "ppt" : $extimg="ppt"; break;
		case "txt" : $extimg="txt"; break;
		case "mp3" :
		case "wma" :
		case "wav" : $extimg="wav"; break;
		case "mpeg" :
		case "mpg" :
		case "mpe" :
		case "wmv" :
		case "asf" :
		case "asx" :
		case "mov" : $extimg="mov"; break;
		case "xls" : $extimg="xls"; break;
		case "zip" : $extimg="zip"; break;
		case "" : $extimg="unknown";
		default : $extimg="unknown";
	}

	# 소문자로 바꾼다.
	return $extimg;
}

###################################################################
## 업로드할 수 없는 파일을 제한한다.
## 업로드 불가능 파일 : .html, .html, .phtml, .php, .php3, .php4, .inc, .cfg, .pl., .cgi
###################################################################
function Ext_Check($string)
{
	# 파일의 확장자를 취한다
	$temp = get_Ext($string);

	if($temp == 'html' || $temp == 'htm' || $temp == 'phtml' || $temp == 'phtm' || $temp == 'php' || $temp == 'php3' || $temp == 'php4' || $temp == 'inc' || $temp == 'cfg' || $temp == 'pl' || $temp == 'cgi')
	{
		return 0;
	} else {
		return 1;
	}
}

###################################################################
## 팝업 메시지박스를 보여준다
###################################################################
function popup_msg($msg,$url="") {
  echo"
		<script language=\"javascript\">
   alert(\"$msg\");
	";
	if($url) echo "document.location.href=\"" . $url . "\";";
	else echo "history.back();";
	echo "</script>";
}

###################################################################################################
## GUID 를 생성합니다.
###################################################################################################
function guid(){
//   if (function_exists('com_create_guid')){
//	   return com_create_guid();
//   }else{
	   mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	   $charid = strtoupper(md5(uniqid(rand(), true)));
	   $hyphen = chr(45);// "-"
	   /*
	   $uuid = chr(123)// "{"
			   .substr($charid, 0, 8).$hyphen
			   .substr($charid, 8, 4).$hyphen
			   .substr($charid,12, 4).$hyphen
			   .substr($charid,16, 4).$hyphen
			   .substr($charid,20,12)
			   .chr(125);// "}"
		*/
		$uuid = substr($charid, 0, 8).$hyphen
			   .substr($charid, 8, 4).$hyphen
			   .substr($charid,12, 4).$hyphen
			   .substr($charid,16, 4).$hyphen
			   .substr($charid,20,12);
	   return $uuid;
//   }
}

####################################################################################################################
## GetFileUpLoad() 파일을 업로드 합니다.
####################################################################################################################
function GetFileUpLoad($Tempfile,$Tempfile_name,$Tempfile_size,$Tempfile_type,$SavePath){
	$return_file = array();
	if ($Tempfile_size == 0) {
		echo "<script language='javascript'>
			alert('$Tempfile_name 파일은 설정된 업로드 용량을 초과하였습니다\\n\\n다른 파일들은 업로드작업이 계속 수행됩니다');
			</script>";
	}

	## 업로드 디렉토리가 시스템에 있는지 조사한다.
	if (!is_dir($SavePath)) {
		MsgView("[$SavePath] 디렉토리가 존재하지 않습니다.      ", -1);	exit;
	}

	$strFileName = guid();						##GUID로 저장될 파일명을 만듬!
	$Ext_Array = explode(".", $Tempfile_name);
	$Ext = $Ext_Array[sizeof($Ext_Array)-1];	## 확장자를 추출

	if( eregi("\(php|phtm|inc|class|htm|html|shtm|pl|cgi|ztx)",$Ext) ) {
		MsgView("업로드가 금지된 파일 형식 입니다.   ", -1);	exit;
	}else{
		$strCopyFile = $strFileName.".".$Ext;
		$Save_dir = $SavePath . "/" . $strCopyFile;

		//echo $Save_dir;

		## 임시 디렉토리에 저장된 바이너리 파일을 해당 디렉토리에 복사한다
		if (!@copy($Tempfile, $Save_dir)) {
			MsgView("원본 파일을 서버로 복사하는도중 에러가 발생했습니다.\\n\\n관리자에게 문의하여 주십시오.", -1);	exit;
		}

		## 임시 디렉토리의 바이너리 파일을 삭제한다
		if (!@unlink($Tempfile)) {
			MsgView("원본 파일을 삭제하는도중 에러가 발생했습니다.\\n\\n업로드는 완료되었습니다.", -1);	exit;
		}

		$return_file[0] = $strCopyFile;		## 저장파일 이름
		$return_file[1] = $Tempfile_name;	## 원본파일 이름
		$return_file[2] = $Tempfile_size;	## 저장파일 사이즈
	}

	return $return_file;
}
####################################################################################################################
## GetFileUpLoad_name() 파일을 업로드 합니다. 이름을 정해서 만든다.
####################################################################################################################
function GetFileUpLoad_name($Tempfile,$Tempfile_name,$Tempfile_size,$Tempfile_type,$SavePath,$name){
	$return_file = array();
	if ($Tempfile_size == 0) {
		echo "<script language='javascript'>
			alert('$Tempfile_name 파일은 설정된 업로드 용량을 초과하였습니다\\n\\n다른 파일들은 업로드작업이 계속 수행됩니다');
			</script>";
	}

	## 업로드 디렉토리가 시스템에 있는지 조사한다.
	if (!is_dir($SavePath)) {
		MsgView("[$SavePath] 디렉토리가 존재하지 않습니다.      ", -1);	exit;
	}

	$strFileName = $name;						## 저장될 파일명을 만듬!
	$Ext_Array = explode(".", $Tempfile_name);
	$Ext = $Ext_Array[sizeof($Ext_Array)-1];	## 확장자를 추출

	if( eregi("\(php|phtm|inc|class|htm|html|shtm|pl|cgi|ztx)",$Ext) ) {
		MsgView("업로드가 금지된 파일 형식 입니다.   ", -1);	exit;
	}else{
		$strCopyFile = $strFileName.".".$Ext;
		$Save_dir = $SavePath . "/" . $strCopyFile;

		## 임시 디렉토리에 저장된 바이너리 파일을 해당 디렉토리에 복사한다
		if (!@copy($Tempfile, $Save_dir)) {
			MsgView("원본 파일을 서버로 복사하는도중 에러가 발생했습니다.\\n\\n관리자에게 문의하여 주십시오.", -1);	exit;
		}

		## 임시 디렉토리의 바이너리 파일을 삭제한다
		if (!@unlink($Tempfile)) {
			MsgView("원본 파일을 삭제하는도중 에러가 발생했습니다.\\n\\n업로드는 완료되었습니다.", -1);	exit;
		}

		$return_file[0] = $strCopyFile;		## 저장파일 이름
		$return_file[1] = $Tempfile_name;	## 원본파일 이름
		$return_file[2] = $Tempfile_size;	## 저장파일 사이즈
	}

	return $return_file;
}

####################################################################################################################
## GetFileIcon() 파일아이콘은 가져온다.
####################################################################################################################
function GetFileIcon($ext,$strPath){

	$location = "{$strPath}/images/filetypeicon/";
	switch(strtoupper($ext)){
		case "XLS"	:	$return_icon = "<img src='".$location."icon-excel.gif' align='absmiddle'>";		break;
		case "ZIP"	:	$return_icon = "<img src='".$location."icon-application-x-gzip.gif' align='absmiddle'>";			break;
		case "ARJ"	:	$return_icon = "<img src='".$location."icon-application-x-gzip.gif' align='absmiddle'>";			break;
		case "GZ"	:	$return_icon = "<img src='".$location."icon-application-x-gzip.gif' align='absmiddle'>";			break;
		case "PPT"	:	$return_icon = "<img src='".$location."icon_application_ms_powerpoint.gif' align='absmiddle'>";	break;
		case "DLL"	:	$return_icon = "<img src='".$location."icon-application-dll.gif' align='absmiddle'>";				break;
		case "EXE"	:	$return_icon = "<img src='".$location."icon-application-exe.gif' align='absmiddle'>";				break;
		case "INI"	:	$return_icon = "<img src='".$location."icon-application-ini.gif' align='absmiddle'>";				break;
		case "DOC"	:	$return_icon = "<img src='".$location."icon-application-msword.gif' align='absmiddle'>";			break;
		case "PDF"	:	$return_icon = "<img src='".$location."icon-application-pdf.gif' align='absmiddle'>";				break;
		case "JS"	:	$return_icon = "<img src='".$location."icon-application-x-javascript.gif' align='absmiddle'>";		break;

		case "BMP"	:	$return_icon = "<img src='".$location."icon-image-bmp.gif' align='absmiddle'>";					break;
		case "GIF"	:	$return_icon = "<img src='".$location."icon-image-gif.gif' align='absmiddle'>";					break;
		case "JPG"	:	$return_icon = "<img src='".$location."icon-image-jpg.gif' align='absmiddle'>";					break;
		case "PNG"	:	$return_icon = "<img src='".$location."icon-image-png.gif' align='absmiddle'>";					break;
		case "txt"	:	$return_icon = "<img src='".$location."icon-text.gif' align='absmiddle'>";							break;
		case "XML"	:	$return_icon = "<img src='".$location."icon-text-xml.gif' align='absmiddle'>";						break;
		case "AVI"	:	$return_icon = "<img src='".$location."icon-video.gif' align='absmiddle'>";						break;
		case "MOV"	:	$return_icon = "<img src='".$location."icon-video.gif' align='absmiddle'>";						break;
		case "MPG"	:	$return_icon = "<img src='".$location."icon-video.gif' align='absmiddle'>";						break;
		case "MPEG"	:	$return_icon = "<img src='".$location."icon-video.gif' align='absmiddle'>";						break;
		case "DAT"	:	$return_icon = "<img src='".$location."icon-video.gif' align='absmiddle'>";						break;
		case "ASF"	:	$return_icon = "<img src='".$location."icon-video.gif' align='absmiddle'>";						break;

		case "MDB"	:	$return_icon = "<img src='".$location."icon_application_ms_powerpoint.gif' align='absmiddle'>";	break;
		default 	:	$return_icon = "<img src='".$location."default.gif' align='absmiddle'>";				break;

	}
	return $return_icon;
}
####################################################################################################################
## GetHttpVars() global_register OFF 로 설정시 변수값이 안넘어오는 것을 수정과함께 변수도 출력
####################################################################################################################
function GetHttpVars($int){
	global $GET_VARS,$POST_VARS;

	if($int){
		//echo "<span style=\"font-family:tahoma; font-size:11px;color:#000000\">▼ GET 방식으로 넘어온 변수입니다.</span><br>";
	}

	foreach($GET_VARS as $key=>$value) {
		$temp_name = $key;
		${$temp_name} = addslashes($value);
		if($int){DEBUG("$temp_name",${$temp_name});}
	}

	if($int){
		//echo "<span style=\"font-family:tahoma; font-size:11px;color:#000000\">▼ POST 방식으로 넘어온 변수입니다.</span><br>";
	}

	foreach($POST_VARS as $key=>$value) {
		$temp_name = $key;
		${$temp_name} = addslashes($value);
		if($int){DEBUG("$temp_name",${$temp_name});}
	}
}
####################################################################################################################
## View 페이지에서 첨부파일이 이미지 파일이면 이미지리 리사이즈해서 보여준다.
####################################################################################################################
function Board_IMG_ReSize($file_name, $max_width, $max_height, $dot){
	global $UpFile_Path;
	$filename = $dot . $file_name;
	if($file_name && file_exists($filename)){
		$image_size = GetImageSize($filename);
		$width = $image_size[0];
		$height = $image_size[1];

		if($max_width != "100%"){
			if($width > $max_width){
				$percent_w = $width / $max_width;
				$width = $max_width;
				$height = $height / $percent_w;
			}else
				$width = $width;

			if($max_height){
				if($height > $max_height){
					$percent_h = $height / $max_height;
					$height = $max_height;
					$width = $width / $percent_h;
				}else
					$height = $height;
			}
		}

		$image_tag = "<img src=\"{$UpFile_Path}" . urlencode($file_name) . "\" width=\"$width\"";

		if($height)
			$image_tag .= " height=\"$height\"";

		$image_tag .= " border=\"0\" vspace=\"5\" hspace=\"5\"";

		$image_tag .= " onClick=\"cnj_win_view(src)\" alt=\"이미지를 클릭하시면 원본 이미지로 볼수있습니다.\" class=\"hand\">";

		return $image_tag;
	}
}
####################################################################################################################
## getfilesize() 파일 사이즈를 kb, mb에 맞추어서 변환해서 리턴
####################################################################################################################
function GetFileSize($size) {
	if(!$size) return "0 Byte";
	if($size<1024) {
		return ($size." Byte");
	} elseif($size >1024 && $size< 1024 *1024)  {
		return sprintf("%0.1f KB",$size / 1024);
	}
	else return sprintf("%0.2f MB",$size / (1024*1024));
}
####################################################################################################################
## cut_string() 문자열을 원하는 길이로 자른다(한글버그수정)
####################################################################################################################
function cut_string($String, $MaxLen, $ShortenStr='...')  {

	$StringLen = strlen($String);  // 원래 문자열의 길이를 구함

	for ($i = 0, $count = 0, $tag = 0; $i <= $StringLen && $count < $MaxLen; $i++ ) {
		$LastStr = substr($String, $i, 1);
		if ($LastStr == '<') $tag = 1; ## 태그 시작
		if ($tag && $LastStr == '>') { $tag = 0; continue; } ## 태그 끝
		if ($tag) continue;
		if ( ord($LastStr) > 127 ) { $count++; $i++; }
		$count++;
		## 2바이트문자라고 생각되면 $i를 1을 더 증가시켜
		## 결국은 2가 증가하게 된다.
		## 다음에 오는 1바이트는 당연 지금 바이트의 문자에 귀속되는 문자이다.
	}

	$RetStr = substr($String, 0, $i);
	## 위에서 구한 문자열의 길이만큼으로 자른다.
	if ($StringLen<=$MaxLen)
		return $RetStr;
	else
		return $RetStr .= $ShortenStr;
	## 여기에 말줄임문자를 붙여서 리턴해준다.
}
###################################################################
## 테그 삭제
###################################################################
function delete_tag($text) {
  $src = array("/\n/i","/<html.*<body[^>]*>/i","/<\/body.*<\/html>.*/i",
               "/<\/*(div|span|layer|body|html|head|meta|input|select|option|form|font)[^>]*>/i",
               "/<(style|script|title).*<\/(style|script|title)>/i",
               "/<\/*(script|style|title|xmp)>/i","/<(\\?|%)/i","/(\\?|%)>/i",
               "/#\^--ENTER--\^#/i");
  $tar = array("#^--ENTER--^#","","","","","","&lt;\\1","\\1&gt;","\n");

  $text = chop(preg_replace($src,$tar,$text));

  return $text;
}



//GD를 이용한 이미지 리사이즈 함수
//$img_file : 원본파일
//$simg_name :리사이즈 파일 : 없을 경우 이미지를 직접출력합니다.
//*리사이즈와 워터 마크를 사용하지 않을 경우 직접 출력하는건 효율성이 떨어집니다.
//(직접 출력의 경우 header가 수정되기 때문에 다른 출력이 있으면 안됩니다.)
//$simg_width :리사이즈 너비
//$simg_height :리사이즈 높이
//* $simg_width와$simg_height 가 둘다 없을 경우 원본크기 그대로 작업합니다.
//$simg_type :리사이즈 파일타입 (1:gif , 2:jpg , 3:png) : 기본 gif
//$simg_str : 워터마크 문자열 (시작 위치:10px,20px ) 폰트는 gulim.ttc 지만, 없을 경우 ""로 바꿔주세요.
//gulim.ttc 는 윈도우 font 폴더 안에 있습니다.

function gd_image_resize ($img_file,$simg_name="", $simg_width="", $simg_height="", $simg_type=2,$simg_str=""){

	if(!is_file($img_file)){ return '원본 파일이 없습니다.'; }

	//if(!$simg_name){ return ‘리사이즈 파일이름이 없습니다.’; } : 리사이즈 파일 이름이 없으면, 이미지로 그냥 출력합니다.
	//if(!$simg_width && !$simg_height){ return ‘너비 와 높이 둘중 하나는 값이 있어야합니다’; } : 원본 크기로 작업합니다.

	//GD 버젼체크
	$gd = gd_info();
	$gdver = substr(preg_replace("/[^0-9]/", "", $gd['GD Version']), 0, 1);
	if(!$gdver) return "GD 버젼체크 실패거나 GD 버젼이 1 미만입니다.";

	list($img_width, $img_height, $img_type, $img_attr) = getimagesize($img_file); //소스이미지파일 크기
	if(!$simg_width && !$simg_height){
	$simg_width = $img_width;
	$simg_height = $img_height;
	}else if(!$simg_width){
	$simg_width = $img_width * ($simg_height/$img_height); //자동 비율생성 : 너비
	}else if(!$simg_height){
	$simg_height = $img_height * ($simg_width/$img_width); //자동 비율생성 : 높이
	}
	/*
	지원 이미지 타입
	1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order),
	9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM.
	1,2,3 만 지원하도록한다.
	*/

	if($img_type<1 && $img_type > 3){
	return "GIF,JPG,PNG 가 아닙니다.";
	}

	if($img_type==1){
	$img_im = imagecreatefromgif($img_file); //원본 이미지: gif
	}else if($img_type==2){
	$img_im = imagecreatefromjpeg($img_file); //원본 이미지: jpg
	}else if($img_type==3){
	$img_im = imagecreatefrompng($img_file); //원본 이미지: png
	}

	if($gdver >= 2){
	//GD 2.XX : truecolor로 작업한다.
	$simg_im = imagecreatetruecolor($simg_width, $simg_height);
	imagecopyresampled($simg_im, $img_im, 0, 0, 0, 0, $simg_width, $simg_height,$img_width, $img_height); //이미지를 리사이즈한다.
	}else{
	//GD 1.xxx
	$simg_im = imagecreate($simg_width, $simg_height);
	imagecopyresized($simg_im, $img_im, 0, 0, 0, 0, $simg_width, $simg_height,$img_width, $img_height); //이미지를 리사이즈한다.
	}

	if($simg_str){
	$color_000000 = imagecolorallocate($simg_im, 0, 0, 0); //색상 : 검정
	$color_FFFFFF = imagecolorallocate($simg_im, 0xFF, 0xFF, 0xFF); //색상 : 흰색
	$simg_str = iconv("EUC-KR","UTF-8",$simg_str); // UTF-8로 한글 변경
	@imagettftext($simg_im, 10, 0, 12, 22, $color_000000, "/gulim.ttc",$simg_str); //글자 적기
	@imagettftext($simg_im, 10, 0, 10, 20, $color_FFFFFF, "/gulim.ttc",$simg_str); //글자 적기
	}

	if($simg_name){
	if($simg_type==1){
	imagegif($simg_im,$simg_name);//원본 이미지: gif
	}else if($simg_type==2){
	imagejpeg($simg_im,$simg_name,100);//원본 이미지: jpg
	}else if($simg_type==3){
	imagepng($simg_im,$simg_name);//원본 이미지: png
	}
	}else{
	Header("Content-Disposition: attachment;; filename=".basename($img_file));
	header("Content-Transfer-Encoding: binary");
	if($simg_type==1){
	header("Content-type: image/gif"); //이미지 타입에 맞도록 해더 구성
	imagegif($simg_im); //원본 이미지: gif
	}else if($simg_type==2){
	header("Content-type: image/jpg"); //이미지 타입에 맞도록 해더 구성
	imagejpeg($simg_im,"",100);//원본 이미지: jpg
	}else if($simg_type==3){
	header("Content-type: image/png"); //이미지 타입에 맞도록 해더 구성
	imagepng($simg_im); //원본 이미지: png
	}
	}

	// 메모리에 있는 그림 삭제
	imagedestroy($img_im);
	imagedestroy($simg_im);
	return $simg_name;

}

function get_usd(){
	$tt = iconv("euc-kr","utf-8",get_content("https://finance.naver.com/marketindex/exchangeList.nhn"));
	//echo $tt;

	$im_s = strpos($tt,"sale");
	$im_c = substr($tt,$im_s,20);

	$im_c = str_replace("sale","",$im_c);
	$im_c = str_replace(chr(34),"",$im_c);
	$im_c = str_replace(">","",$im_c);
	$im_c = str_replace("</td","",$im_c);

	return $im_c;
}

function get_content($url) {
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)';
	$curlsession = curl_init ();
	curl_setopt ($curlsession, CURLOPT_URL, $url);
	curl_setopt ($curlsession, CURLOPT_HEADER, 0);
	curl_setopt ($curlsession, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($curlsession, CURLOPT_POST, 0);
	curl_setopt ($curlsession, CURLOPT_USERAGENT, $agent);
	curl_setopt ($curlsession, CURLOPT_REFERER, "");
	curl_setopt ($curlsession, CURLOPT_TIMEOUT, 3);
	$buffer = curl_exec ($curlsession);
	$cinfo = curl_getinfo($curlsession);
	curl_close($curlsession);
	if ($cinfo['http_code'] != 200)
	{
	return "";
	}
	return $buffer;
}

//청구대상 Y
function f_cg_daesang($jijum_code,$regist_date,$h_idx){
	global $pdo;
	$sql = "select count(idx) as cc from tbl_junib where e1= '{$jijum_code}' and g1='{$regist_date}' and h_idx=$h_idx ";  //지점/등기접수일/현장명이 최초인것  = 0 일때
	//echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	if ($row[cc]==""){
			return "Y";
	}else if ($row[cc]==0){
			return "Y";
	}else{
			return "";
	}
}

//채권채고액 동일성확인 - 기존과 같으면 Y  / 다르면 N
function f_cco_ch($h_idx,$a1,$p1,$v1){
	global $pdo;
	$sql = "select {$p1} from tbl_junib where a1= '{$a1}' and h_idx=$h_idx ";  //채권최고액을 가져온다
	//echo $sql."<br>";

	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	//echo  $row[$p1]." / ".$v1."<br>";

	if ($row[$p1]==$v1){ //동일하면 갱신
		//echo "Y";
		return "Y";
	}else if ($row[$p1]==""){ //동일하면 갱신
		//echo "Y";
		return "Y";
	}else{  //동일하지 않으면 오류
		//echo "N";
		return "N";
	}
}

//은행코드,지점코드,채권채고액
function f_nujin_value($bank_code,$jijum_code,$aaa,$a1,$suljung_no){
	//B02우리은행,B04하나은행 -> gubun_code (basic/bonjum-기본/본점)
	global $pdo;

	$imsi = 0;

	//은행정보 반환
	if($suljung_no==""){
		$sql = "select * from tbl_suljung where a1='{$a1}' limit 1 ";
	}else{
		$sql = "select * from tbl_suljung where a1='{$a1}' and suljung_no='{$suljung_no}' limit 1 ";
	}
	$rowt = db_query_value($sql);

	if($rowt[gukto]=="gukto"){
		$basic_gukto="gukto";
	}else{
		$basic_gukto="basic";
	}

	//은행정보 반환
	$sql = "select * from tbl_bank_info where bank_code='{$rowt[bank_code]}' limit 1 ";
	$bb = db_query_value($sql);

	$bank_alias = $bb[bank_alias];  //은행단축명

	//점점정보 반환
	$sql = "select * from tbl_bank_jijum where jijum_code='{$jijum_code}' limit 1 ";
	$jj = db_query_value($sql);

	//현장정보 반환
	$sql = "select h_idx from tbl_junib where a1='{$a1}' limit 1 ";
	$kk = db_query_value($sql);

	$sql = "select gubun_code,b_halin,b_julsak_position from tbl_bank_jijum_rate where bank_code= '{$bank_code}' and jijum_code='{$jijum_code}' and basic_gukto='{$basic_gukto}' and h_idx = $kk[h_idx] ";  //2글자 한글과 매칭여부판별
	$row = db_query_value($sql);
	//echo $sql."<br>";

	//$row = db_query_fetch($sql,0,0);
	$gubun = $row[gubun_code];


	if(($bank_alias=="우리")&&($gubun=="basic")){  //우리은행,기본 - 할인율 하드 코딩 - 변경 20190917 / 20191012
		//echo "우리/기본";
		if($aaa>1000000000){
				$imsi = 784000+(($aaa-1000000000)*(5/10000));
		}else if($aaa>500000000){
				$imsi = 434000+(($aaa-500000000)*(7/10000));
		}else if($aaa>300000000){
				$imsi = 274000+(($aaa-300000000)*(8/10000));
		}else if($aaa>100000000){
				$imsi = 94000+(($aaa-100000000)*(9/10000));
		}else if($aaa>50000000){
				$imsi = 44000+(($aaa-50000000)*(10/10000));
		}else if($aaa>10000000){
				$imsi = ($aaa-10000000)*(11/10000);
		}else{
				$imsi = 0;
		}
		//$imsi = $imsi * f_halin($row[b_halin]);
		//$imsi = intval($imsi * f_halin($row[b_halin]));
		if($row[b_halin]==0 || $row[b_halin]=="" ){
			$imsi = intval($imsi * $row[b_halin]/1);
		} else {
			$imsi = intval($imsi * $row[b_halin]/100);
		}

		if($row[b_julsak_position]=="1"){
			$imsi = floor( $imsi / 10 ) * 10;  //1단위 절삭
		}else if($row[b_julsak_position]=="2"){
			$imsi = floor( $imsi / 100 ) * 100;  //10단위 절삭
		}

	}else if(($bank_alias=="우리")&&($gubun=="bonjum")){  //우리은행,본점제휴 - 할인율  받아서
		//echo "우리/본점";
		if($aaa>1000000000){
				$imsi = 705000+(($aaa-1000000000)*(5/10000));
		}else if($aaa>500000000){
				$imsi = 405000+(($aaa-500000000)*(6/10000));
		}else if($aaa>300000000){
				$imsi = 265000+(($aaa-300000000)*(7/10000));
		}else if($aaa>100000000){
				$imsi = 105000+(($aaa-100000000)*(8/10000));
		}else if($aaa>50000000){
				$imsi = 60000+(($aaa-50000000)*(9/10000));
		}else if($aaa>10000000){
				$imsi = 20000+(($aaa-10000000)*(10/10000));
		}else{
				$imsi = 0;
		}
		//$imsi = $imsi * f_halin($row[b_halin]);
		//$imsi = intval($imsi * f_halin($row[b_halin]));
		if($row[b_halin]==0 || $row[b_halin]=="" ){
			$imsi = intval($imsi * $row[b_halin]/1);
		} else {
			$imsi = intval($imsi * $row[b_halin]/100);
		}

		if($row[b_julsak_position]=="1"){
			$imsi = floor( $imsi / 10 ) * 10;  //1단위 절삭
		}else if($row[b_julsak_position]=="2"){
			$imsi = floor( $imsi / 100 ) * 100;  //10단위 절삭
		}

	}else if(($bank_alias=="하나")&&($gubun=="basic")){  //하나은행,기본 -할인율 받아서
		//echo "하나/기본";
		if(($aaa)<=10000000){
				$imsi = 0;
		}else if(($aaa)<=50000000){
				$imsi = (($aaa)-10000000)*11/10000;
		}else if(($aaa)<=100000000){
				$imsi = (($aaa)-50000000)*10/10000+44000;
		}else if(($aaa)<=300000000){
				$imsi = ((($aaa)-100000000)*9/10000)+94000;
		}else if(($aaa)<=500000000){
				$imsi = ((($aaa)-300000000)*8/10000)+274000;
		}else if(($aaa)<=1000000000){
				$imsi = ((($aaa)-500000000)*7/10000)+434000;
		}else if(($aaa)<=2000000000){
				$imsi = ((($aaa)-1000000000)*5/10000)+784000;
		}else if(($aaa)<=20000000000){
				$imsi = ((($aaa)-2000000000)*4/10000)+1284000;
		}else{
				$imsi = 8484000+(($aaa-20000000000)*1/10000);
		}
		//$imsi = $imsi * f_halin($row[b_halin]);
		//$imsi = intval($imsi * f_halin($row[b_halin]));
		if($row[b_halin]==0 || $row[b_halin]=="" ){
			$imsi = intval($imsi * $row[b_halin]/1);
		} else {
			$imsi = intval($imsi * $row[b_halin]/100);
		}

		if($row[b_julsak_position]=="1"){
			$imsi = floor( $imsi / 10 ) * 10;  //1단위 절삭
		}else if($row[b_julsak_position]=="2"){
			$imsi = floor( $imsi / 100 ) * 100;  //10단위 절삭
		}

		//echo f_money($aaa)." / ".$imsi." / ".$row[b_halin]."<br>";

	}else if(($bank_alias=="하나")&&($gubun=="bonjum")){  //하나은행,본점제휴 - 할인율 받아서
		//echo "하나/본점";
		if(($aaa)<=10000000){
				$imsi = 0;
		}else if(($aaa)<=50000000){
				$imsi = (($aaa)-10000000)*11/10000;
		}else if(($aaa)<=100000000){
				$imsi = ((($aaa)-50000000)*10/10000)+44000;
		}else if(($aaa)<=300000000){
				$imsi = ((($aaa)-100000000)*9/10000)+94000;
		}else if(($aaa)<=500000000){
				$imsi = ((($aaa)-300000000)*8/10000)+274000;
		}else if(($aaa)<=1000000000){
				$imsi = ((($aaa)-500000000)*7/10000)+434000;
		}else if(($aaa)<=2000000000){
				$imsi = ((($aaa)-1000000000)*5/10000)+784000;
		}else if(($aaa)<=20000000000){
				$imsi = ((($aaa)-2000000000)*4/10000)+1284000;
		}else{
			$imsi = 8484000+(($aaa-20000000000)*1/10000);
		}
		//$imsi = $imsi * f_halin($row[b_halin]);
		//$imsi = intval($imsi * f_halin($row[b_halin]));
		if($row[b_halin]==0 || $row[b_halin]=="" ){
			$imsi = intval($imsi * $row[b_halin]/1);
		} else {
			$imsi = intval($imsi * $row[b_halin]/100);
		}

		if(($aaa)<=50000000){
			$imsi = $imsi + 0;
		}else{
			$imsi = $imsi + 20000;
		}

		if($row[b_julsak_position]=="1"){
			$imsi = floor( $imsi / 10 ) * 10;  //1단위 절삭
		}else if($row[b_julsak_position]=="2"){
			$imsi = floor( $imsi / 100 ) * 100;  //10단위 절삭
		}

		//echo $imsi;
		//echo "/".$row[b_halin];

	}else if($bank_alias=="신한"){  //신한은행, - 할인율 받아서 - 추가 20190522
		//echo "신한은행";
		if($aaa>1000000000){
				$imsi = 784000+(($aaa-1000000000)*(5/10000));
		}else if($aaa>500000000){
				$imsi = 434000+(($aaa-500000000)*(7/10000));
		}else if($aaa>300000000){
				$imsi = 274000+(($aaa-300000000)*(8/10000));
		}else if($aaa>100000000){
				$imsi = 94000+(($aaa-100000000)*(9/10000));
		}else if($aaa>50000000){
				$imsi = 44000+(($aaa-50000000)*(10/10000));
		}else if($aaa>10000000){
				$imsi = ($aaa-10000000)*(11/10000);
		}else{
				$imsi = 0;
		}
		//$imsi = $imsi * f_halin($row[b_halin]);
		if($row[b_halin]==0 || $row[b_halin]=="" ){
			$imsi = intval($imsi * $row[b_halin]/1);
		} else {
			$imsi = intval($imsi * $row[b_halin]/100);
		}

		if($row[b_julsak_position]=="1"){
			$imsi = floor( $imsi / 10 ) * 10;  //1단위 절삭
		}else if($row[b_julsak_position]=="2"){
			$imsi = floor( $imsi / 100 ) * 100;  //10단위 절삭
		} else {
			$imsi = floor( $imsi / 1) * 1;  //절삭 없음
		}
		//echo $imsi;
		//echo "/".$row[b_halin];

	}else if($bank_alias=="농협"){  //농협은행, - 할인율 받아서 - 추가 20190522 - 변경 20190917 / 20191012
		//echo "농협은행";
		if($aaa>1000000000){
				$imsi = 784000+(($aaa-1000000000)*(5/10000));
		}else if($aaa>500000000){
				$imsi = 434000+(($aaa-500000000)*(7/10000));
		}else if($aaa>300000000){
				$imsi = 274000+(($aaa-300000000)*(8/10000));
		}else if($aaa>100000000){
				$imsi = 94000+(($aaa-100000000)*(9/10000));
		}else if($aaa>50000000){
				$imsi = 44000+(($aaa-50000000)*(10/10000));
		}else if($aaa>10000000){
				$imsi = ($aaa-10000000)*(11/10000);
		}else{
				$imsi = 0;
		}
		//$imsi = $imsi * f_halin($row[b_halin]);
		//$imsi = intval($imsi * f_halin($row[b_halin]));
		if($row[b_halin]==0 || $row[b_halin]=="" ){
			$imsi = intval($imsi * $row[b_halin]/1);
		} else {
			$imsi = intval($imsi * $row[b_halin]/100);
		}

		if($row[b_julsak_position]=="1"){
			$imsi = floor( $imsi / 10 ) * 10;  //1단위 절삭
		}else if($row[b_julsak_position]=="2"){
			$imsi = floor( $imsi / 100 ) * 100;  //10단위 절삭
		}

		//echo $imsi;
		//echo "/".$row[b_halin];

	}else if($bank_alias=="기업"){  //기업은행, - 할인율 받아서 - 추가 20190522 - 변경 20190917 / 20191012
		//echo "기업은행";
		if($aaa>1000000000){
				$imsi = 784000+(($aaa-1000000000)*(5/10000));
		}else if($aaa>500000000){
				$imsi = 434000+(($aaa-500000000)*(7/10000));
		}else if($aaa>300000000){
				$imsi = 274000+(($aaa-300000000)*(8/10000));
		}else if($aaa>100000000){
				$imsi = 94000+(($aaa-100000000)*(9/10000));
		}else if($aaa>50000000){
				$imsi = 44000+(($aaa-50000000)*(10/10000));
		}else if($aaa>10000000){
				$imsi = ($aaa-10000000)*(11/10000);
		}else{
				$imsi = 0;
		}
		//$imsi = $imsi * f_halin($row[b_halin]);
		//$imsi = intval($imsi * f_halin($row[b_halin]));
		if($row[b_halin]==0 || $row[b_halin]=="" ){
			$imsi = intval($imsi * $row[b_halin]/1);
		} else {
			$imsi = intval($imsi * $row[b_halin]/100);
		}

		if($row[b_julsak_position]=="1"){
			$imsi = floor( $imsi / 10 ) * 10;  //1단위 절삭
		}else if($row[b_julsak_position]=="2"){
			$imsi = floor( $imsi / 100 ) * 100;  //10단위 절삭
		}

		//echo $imsi;
		//echo "/".$row[b_halin];
	}else if($bank_alias=="국민"){  //국민은행, - 할인율 받아서 - 추가 20190522 - 변경 20190627
		//echo "국민은행";
		if($aaa>1000000000){  //10억이상
				$imsi = 548800+(($aaa-1000000000)*(5/10000))*0.7;
		}else if($aaa>500000000){ //5억이상
				$imsi = 303800+(($aaa-500000000)*(7/10000))*0.7;
		}else if($aaa>300000000){  //3억이상
				$imsi = 191800+(($aaa-300000000)*(8/10000))*0.7;
		}else if($aaa>100000000){ //1억이상
				$imsi = 65800+(($aaa-100000000)*(9/10000))*0.7;
		}else if($aaa>50000000){  //5천이상
				$imsi = 30800+(($aaa-50000000)*(10/10000))*0.7;
		}else if($aaa>10000000){  //5천이상
				$imsi = ($aaa-10000000)*(11/10000)*0.7;
		}else{
				$imsi = 0;
		}
	//$imsi = intval($imsi * f_halin($row[b_halin]));
		$imsi = intval($imsi);

		if($row[b_julsak_position]=="1"){
			$imsi = floor( $imsi / 10 ) * 10;  //1단위 절삭
		}else if($row[b_julsak_position]=="2"){
			$imsi = floor( $imsi / 100 ) * 100;  //10단위 절삭
		}

		//echo $imsi;
		//echo "/".$row[b_halin];
	}else if($bank_alias=="수협"){  //수협은행, - 할인율 받아서 - 추가 20190522
		//echo "수협은행";
		if($aaa>1000000000){  //10억이상
				$imsi = 548800+(($aaa-1000000000)*(5/10000))*$row[b_halin]/100;
		}else if($aaa>500000000){ //5억이상
				$imsi = 303800+(($aaa-500000000)*(7/10000))*$row[b_halin]/100;
		}else if($aaa>300000000){  //3억이상
				$imsi = 191800+(($aaa-300000000)*(8/10000))*$row[b_halin]/100;
		}else if($aaa>100000000){ //1억이상
				$imsi = 65800+(($aaa-100000000)*(9/10000))*$row[b_halin]/100;
		}else if($aaa>50000000){  //5천이상
				$imsi = 30800+(($aaa-50000000)*(10/10000))*$row[b_halin]/100;
		}else if($aaa>10000000){  //5천이상
				$imsi = ($aaa-10000000)*(11/10000)*$row[b_halin];
		}else{
				$imsi = 0;
		}
//		$imsi = intval($imsi * f_halin($row[b_halin]));
		$imsi = intval($imsi);
//		if($row[b_halin]==0 || $row[b_halin]=="" ){
//			$imsi = intval($imsi * $row[b_halin]/1);
//		} else {
//			$imsi = intval($imsi * $row[b_halin]/100);
//		}

		if($row[b_julsak_position]=="1"){
			$imsi = floor( $imsi / 10 ) * 10;  //1단위 절삭
		}else if($row[b_julsak_position]=="2"){
			$imsi = floor( $imsi / 100 ) * 100;  //10단위 절삭
		}

		//echo $imsi;
		//echo "/".$row[b_halin];
	}else if($bank_alias=="광주"){  //광주은행, - 할인율 받아서 - 추가 20190522
		//echo "광주은행";
		if($aaa>1000000000){  //10억이상
				$imsi = 548800+(($aaa-1000000000)*(5/10000))*$row[b_halin]/100;
		}else if($aaa>500000000){ //5억이상
				$imsi = 303800+(($aaa-500000000)*(7/10000))*$row[b_halin]/100;
		}else if($aaa>300000000){  //3억이상
				$imsi = 191800+(($aaa-300000000)*(8/10000))*$row[b_halin]/100;
		}else if($aaa>100000000){ //1억이상
				$imsi = 65800+(($aaa-100000000)*(9/10000))*$row[b_halin]/100;
		}else if($aaa>50000000){  //5천이상
				$imsi = 30800+(($aaa-50000000)*(10/10000))*$row[b_halin]/100;
		}else if($aaa>10000000){  //5천이상
				$imsi = ($aaa-10000000)*(11/10000)*$row[b_halin];
		}else{
				$imsi = 0;
		}
//		$imsi = intval($imsi * f_halin($row[b_halin]));
		$imsi = intval($imsi);
//		if($row[b_halin]==0 || $row[b_halin]=="" ){
//			$imsi = intval($imsi * $row[b_halin]/1);
//		} else {
//			$imsi = intval($imsi * $row[b_halin]/100);
//		}

		if($row[b_julsak_position]=="1"){
			$imsi = floor( $imsi / 10 ) * 10;  //1단위 절삭
		}else if($row[b_julsak_position]=="2"){
			$imsi = floor( $imsi / 100 ) * 100;  //10단위 절삭
		}

		//echo $imsi;
		//echo "/".$row[b_halin];
	}else { //나머지 경우는 기본 - 할인율 적용 받음
//		//echo "나머지";
//		if($aaa>1000000000){  //10억이상
//				$imsi = 685000+(($aaa-1000000000)*(5/10000));
//		}else if($aaa>500000000){ //5억이상
//				$imsi = 385000+(($aaa-500000000)*(6/10000));
//		}else if($aaa>300000000){  //3억이상
//				$imsi = 245000+(($aaa-300000000)*(7/10000));
//		}else if($aaa>100000000){ //1억이상
//				$imsi = 85000+(($aaa-100000000)*(8/10000));
//		}else if($aaa>50000000){  //5천이상
//				$imsi = 40000+(($aaa-50000000)*(9/10000));
//		}else if($aaa>10000000){  //5천이상
//				$imsi = ($aaa-10000000)*(10/10000);
//		}else{
//				$imsi = 0;
		//echo "나머지"; 20200721 신보수표 적용
		if($aaa>1000000000){  //10억이상
				$imsi = 548800+(($aaa-1000000000)*(5/10000))*$row[b_halin]/100;
		}else if($aaa>500000000){ //5억이상
				$imsi = 303800+(($aaa-500000000)*(7/10000))*$row[b_halin]/100;
		}else if($aaa>300000000){  //3억이상
				$imsi = 191800+(($aaa-300000000)*(8/10000))*$row[b_halin]/100;
		}else if($aaa>100000000){ //1억이상
				$imsi = 65800+(($aaa-100000000)*(9/10000))*$row[b_halin]/100;
		}else if($aaa>50000000){  //5천이상
				$imsi = 30800+(($aaa-50000000)*(10/10000))*$row[b_halin]/100;
		}else if($aaa>10000000){  //5천이상
				$imsi = ($aaa-10000000)*(11/10000)*$row[b_halin];
		}else{
				$imsi = 0;
		}
//		$imsi = intval($imsi * f_halin($row[b_halin]));
		$imsi = intval($imsi);
//		if($row[b_halin]==0 || $row[b_halin]=="" ){
//			$imsi = intval($imsi * $row[b_halin]/1);
//		} else {
//			$imsi = intval($imsi * $row[b_halin]/100);
//		}

		if($row[b_julsak_position]=="1"){
			$imsi = floor( $imsi / 10 ) * 10;  //1단위 절삭
		}else if($row[b_julsak_position]=="2"){
			$imsi = floor( $imsi / 100 ) * 100;  //10단위 절삭
		}else {
			$imsi = floor( $imsi / 1) * 1;  //절삭 없음
		}

		//echo $imsi;
		//echo "/".$row[b_halin];

	}

	return $imsi;
}

function f_halin($p1){  //할인울 계산 0이면 1반환 ,아니면 x/100
	if($p1==""){
		return 1;
	}else if($p1=="0"){
		return 1;
	}else{
		return ($p1/100);
	}
}

//취득세금액산출
function f_cheduk_value($a1,$gubun){
	//B02우리은행,B04하나은행 -> gubun_code (basic/bonjum-기본/본점)
	global $pdo;
	// tax_apply 취득세법 적용 (1-구법/2-신법)
	// apply_type 취득유형 (1-아파트/ 2-오피스텔 / 3-상가)
	// multi_housing_type 다주택여부 ( 1-1주택 / 2-일시적2주택 / 3-2주택 / 4-3주택 / 5-4주택이상 / 6-법인)
	// af1 취득세과표
	// balance_date 잔금일
	// contract_date 계약일
	$datex = date("Ymd");
	$imsi = 0;
	//전입상세정보 조회
	$sql = "select * from tbl_junib where a1='{$a1}' limit 1 ";
	$row = db_query_value($sql);
	
	// 취득세율 구하기
	$yoyul = round(($row[af1]*2/300000000-3)*0.01,6);
	
	//현장정보 반환
	$sql = "select * from tbl_hyunjang_info where h_idx='{$row[h_idx]}' limit 1 ";
	$kk = db_query_value($sql);
	// jungong_date 준공일
	// jojeong_yn 조정지역여부 (y-조정지역 / n-비조정지역)
	// area_gubun 지역 (1-특광지역 / 2-기타지역)
	//echo $sql."<br>";

	//$row = db_query_fetch($sql,0,0);

	// 조건식1
	if($row[af1]<=600000000){
		$gb1 = floor( $row[af1] * 0.01 / 10 ) * 10;  // 취득과세표 * 1% (1단위 절삭)
	} else if($row[af1]>600000000&&$row[af1]<=750000000){
		$gb1 = floor( $row[af1] * $yoyul / 10 ) * 10;  // 취득과세표 * 취득세율 (1단위 절삭)
	} else if($row[af1]>750000000&&$row[af1]<=90000000){
		$gb1 = floor( $row[af1] * 0.02 / 10 ) * 10;  // 취득과세표 * 2% (1단위 절삭)
	} else if($row[af1]>900000000){
		$gb1 = floor( $row[af1] * 0.03 / 10 ) * 10;  // 취득과세표 * 3% (1단위 절삭)
	}

	// 조건식2
	if($row[af1]<=600000000){
		$gb2 = floor( $row[af1] * 0.01 / 10 ) * 10;  // 취득과세표 * 1% (1단위 절삭)
	} else if($row[af1]>600000000&&$row[af1]<=90000000){
		$gb2 = floor( $row[af1] * $yoyul / 10 ) * 10;  // 취득과세표 * 취득세율 (1단위 절삭)
	} else if($row[af1]>900000000){
		$gb2 = floor( $row[af1] * 0.03 / 10 ) * 10;  // 취득과세표 * 3% (1단위 절삭)
	}

	// 조건식3
	if($row[af1]<=600000000){
		$gb3 = floor( $row[af1] * 0.01 / 10 ) * 10;  // 취득과세표 * 1% (1단위 절삭)
	} else if($row[af1]>600000000&&$row[af1]<=90000000){
		$gb3 = floor( $row[af1] * 0.02 / 10 ) * 10;  // 취득과세표 * 2% (1단위 절삭)
	} else if($row[af1]>900000000){
		$gb3 = floor( $row[af1] * 0.03 / 10 ) * 10;  // 취득과세표 * 3% (1단위 절삭)
	}


	// 조건식4
	if($row[af1]<=600000000){
		$gb4 = floor( $row[af1] * 0.001 / 10 ) * 10;  // 취득과세표 * 0.1% (1단위 절삭)
	} else if($row[af1]>600000000&&$row[af1]<=90000000){
		$gb4 = floor( $row[af1] * $yoyul * 0.1 / 10 ) * 10;  // 취득과세표 * 취득세율 * 0.1 (1단위 절삭)
	} else if($row[af1]>900000000){
		$gb4 = floor( $row[af1] * 0.003 / 10 ) * 10;  // 취득과세표 * 0.3% (1단위 절삭)
	}

	/////////////////////////////////////////////////////////////
	// 취득세 구하기
	/////////////////////////////////////////////////////////////

//	if($gubun=="1"){ // 취득세

	// 취득유형
	if($row[apply_type]=="1"){// 아파트

		// 취득세법 적용
		if($row[tax_apply]=="1"){ // 구법적용

			// 다주택여부
			if($row[multi_housing_type]=="1"){ // 1주택
				if(get_date_diff($row[contract_date],'20191231')>=0 || get_date_diff($row[balance_date],'20221231')>=0 ){
					$imsi1 = $gb1;  // 조건식1 적용
				} else {
					$imsi1 = $gb2;  // 조건식2 적용
				}
			} else if($row[multi_housing_type]=="2"){ // 일시적2주택
				if(get_date_diff($row[contract_date],'20191231')>=0 || get_date_diff($row[balance_date],'20221231')>=0 ){
					$imsi1 = $gb1;  // 조건식1 적용
				} else {
					$imsi1 = $gb2;  // 조건식2 적용
				}
			} else if($row[multi_housing_type]=="3"){ // 2주택
				if(get_date_diff($row[contract_date],'20191231')>=0 || get_date_diff($row[balance_date],'20221231')>=0 ){
					$imsi1 = $gb1;  // 조건식1 적용
				} else {
					$imsi1 = $gb2;  // 조건식2 적용
				}
			} else if($row[multi_housing_type]=="4"){ // 3주택
				if(get_date_diff($row[contract_date],'20191231')>=0 || get_date_diff($row[balance_date],'20221231')>=0 ){
					$imsi1 = $gb1;  // 조건식1 적용
				} else {
					$imsi1 = $gb2;  // 조건식2 적용
				}
			} else if($row[multi_housing_type]=="5"){ // 4주택이상
				if(get_date_diff($row[contract_date],'20191231')>=0 || get_date_diff($row[balance_date],'20221231')>=0 ){
					$imsi1 = $gb3;  // 조건식3 적용
				} else {
					$imsi1 = floor( $row[af1] * 0.04 / 10 ) * 10;  // 취득과세표 * 4% (1단위 절삭)
				}
			} else if($row[multi_housing_type]=="6"){ // 법인
				$imsi1 = $gb3;  // 조건식3 적용
			}

		} else if($row[tax_apply]=="2"){ // 신법적용

			// 조정지역여부
			if($kk[jojeong_yn]=="y"){ // 조정지역
			
				// 다주택여부
				if($row[multi_housing_type]=="1"){ // 1주택
					$imsi1 = $gb2;  // 조건식2 적용
				} else if($row[multi_housing_type]=="2"){ // 일시적2주택
					$imsi1 = $gb2;  // 조건식2 적용
				} else if($row[multi_housing_type]=="3"){ // 2주택
					$imsi1 = floor( $row[af1] * 0.08 / 10 ) * 10;  // 취득과세표 * 8% (1단위 절삭)
				} else if($row[multi_housing_type]=="4"){ // 3주택
					$imsi1 = floor( $row[af1] * 0.12 / 10 ) * 10;  // 취득과세표 * 12% (1단위 절삭)
				} else if($row[multi_housing_type]=="5"){ // 4주택이상
					$imsi1 = floor( $row[af1] * 0.12 / 10 ) * 10;  // 취득과세표 * 12% (1단위 절삭)
				} else if($row[multi_housing_type]=="6"){ // 법인
					$imsi1 = floor( $row[af1] * 0.12 / 10 ) * 10;  // 취득과세표 * 12% (1단위 절삭)
				}
			} else if($kk[jojeong_yn]=="n"){ // 비조정지역

				// 다주택여부
				if($row[multi_housing_type]=="1"){ // 1주택
					$imsi1 = $gb2;  // 조건식2 적용
				} else if($row[multi_housing_type]=="2"){ // 일시적2주택
					$imsi1 = $gb2;  // 조건식2 적용
				} else if($row[multi_housing_type]=="3"){ // 2주택
					$imsi1 = $gb2;  // 조건식2 적용
				} else if($row[multi_housing_type]=="4"){ // 3주택
					$imsi1 = floor( $row[af1] * 0.08 / 10 ) * 10;  // 취득과세표 * 8% (1단위 절삭)
				} else if($row[multi_housing_type]=="5"){ // 4주택이상
					$imsi1 = floor( $row[af1] * 0.12 / 10 ) * 10;  // 취득과세표 * 12% (1단위 절삭)
				} else if($row[multi_housing_type]=="6"){ // 법인
					$imsi1 = floor( $row[af1] * 0.12 / 10 ) * 10;  // 취득과세표 * 12% (1단위 절삭)
				}
			}
		}
		
	} else if($apply_type=="2"){ // 오피스텔
		$imsi1 = floor( $row[af1] * 0.04 / 10 ) * 10;  // 취득과세표 * 4% (1단위 절삭)
		
	} else if($apply_type=="3"){ // 상가
		$imsi1 = floor( $row[af1] * 0.04 / 10 ) * 10;  // 취득과세표 * 4% (1단위 절삭)
	}
//		echo "<script>alert({$imsi1});</script>";

	/////////////////////////////////////////////////////////////
	// 지방교육세
	/////////////////////////////////////////////////////////////
	
//	}else if($gubun=="2"){ // 지방교육세

	// 취득유형
	if($row[apply_type]=="1"){// 아파트

		// 취득세법 적용
		if($row[tax_apply]=="1"){ // 구법적용

			// 다주택여부
			if($row[multi_housing_type]=="1"){ // 1주택
				$imsi2 = floor( $imsi1 * 0.1 / 10 ) * 10;  // 취득세 * 10% (1단위 절삭)
			} else if($row[multi_housing_type]=="2"){ // 일시적2주택
				$imsi2 = floor( $imsi1 * 0.1 / 10 ) * 10;  // 취득세 * 10% (1단위 절삭)
			} else if($row[multi_housing_type]=="3"){ // 2주택
				$imsi2 = floor( $imsi1 * 0.1 / 10 ) * 10;  // 취득세 * 10% (1단위 절삭)
			} else if($row[multi_housing_type]=="4"){ // 3주택
				$imsi2 = floor( $imsi1 * 0.1 / 10 ) * 10;  // 취득세 * 10% (1단위 절삭)
			} else if($row[multi_housing_type]=="5"){ // 4주택이상
				$imsi2 = floor( $imsi1 * 0.1 / 10 ) * 10;  // 취득세 * 10% (1단위 절삭)
			} else if($row[multi_housing_type]=="6"){ // 법인
				$imsi2 = floor( $imsi1 * 0.1 / 10 ) * 10;  // 취득세 * 10% (1단위 절삭)
			}

		} else if($row[tax_apply]=="2"){ // 신법적용
	
			// 조정지역여부
			if($kk[jojeong_yn]=="y"){ // 조정지역
			
				// 다주택여부
				if($row[multi_housing_type]=="1"){ // 1주택
					$imsi2 = $gb4;  // 조건식4 적용
				} else if($row[multi_housing_type]=="2"){ // 일시적2주택
					$imsi2 = $gb4;  // 조건식4 적용
				} else if($row[multi_housing_type]=="3"){ // 2주택
					$imsi2 = floor( $row[af1] * 0.004 / 10 ) * 10;  // 취득과세표 * 0.4% (1단위 절삭)
				} else if($row[multi_housing_type]=="4"){ // 3주택
					$imsi2 = floor( $row[af1] * 0.004 / 10 ) * 10;  // 취득과세표 * 0.4% (1단위 절삭)
				} else if($row[multi_housing_type]=="5"){ // 4주택이상
					$imsi2 = floor( $row[af1] * 0.004 / 10 ) * 10;  // 취득과세표 * 0.4% (1단위 절삭)
				} else if($row[multi_housing_type]=="6"){ // 법인
					$imsi2 = floor( $row[af1] * 0.004 / 10 ) * 10;  // 취득과세표 * 0.4% (1단위 절삭)
				}
			} else if($kk[jojeong_yn]=="n"){ // 비조정지역

				// 다주택여부
				if($row[multi_housing_type]=="1"){ // 1주택
					$imsi2 = $gb4;  // 조건식4 적용
				} else if($row[multi_housing_type]=="2"){ // 일시적2주택
					$imsi2 = $gb4;  // 조건식4 적용
				} else if($row[multi_housing_type]=="3"){ // 2주택
					$imsi2 = $gb4;  // 조건식4 적용
				} else if($row[multi_housing_type]=="4"){ // 3주택
					$imsi2 = floor( $row[af1] * 0.004 / 10 ) * 10;  // 취득과세표 * 0.4% (1단위 절삭)
				} else if($row[multi_housing_type]=="5"){ // 4주택이상
					$imsi2 = floor( $row[af1] * 0.004 / 10 ) * 10;  // 취득과세표 * 0.4% (1단위 절삭)
				} else if($row[multi_housing_type]=="6"){ // 법인
					$imsi2 = floor( $row[af1] * 0.004 / 10 ) * 10;  // 취득과세표 * 0.4% (1단위 절삭)
				}
			}
		}
		
	} else if($apply_type=="2"){ // 오피스텔
		$imsi2 = floor( $imsi1 * 0.1 / 10 ) * 10;  // 취득세 * 10% (1단위 절삭)
		
	} else if($apply_type=="3"){ // 상가
		$imsi2 = floor( $imsi1 * 0.1 / 10 ) * 10;  // 취득세 * 10% (1단위 절삭)
	}

	/////////////////////////////////////////////////////////////
	// 농어촌특별세
	/////////////////////////////////////////////////////////////
	
//	}else if($gubun=="3"){ // 농어촌특별세

	// 취득유형
	if($row[apply_type]=="1"){// 아파트
		
		// 85㎡이하는 0원 처리
		if($row[con_building_area]>85){
			
			// 취득세법 적용
			if($row[tax_apply]=="1"){ // 구법적용

				// 다주택여부
				if($row[multi_housing_type]=="1"){ // 1주택
					$imsi3 = floor( $row[af1] * 0.002 / 10 ) * 10;  // 취득과세표 * 0.2% (1단위 절삭)
				} else if($row[multi_housing_type]=="2"){ // 일시적2주택
					$imsi3 = floor( $row[af1] * 0.002 / 10 ) * 10;  // 취득과세표 * 0.2% (1단위 절삭)
				} else if($row[multi_housing_type]=="3"){ // 2주택
					$imsi3 = floor( $row[af1] * 0.002 / 10 ) * 10;  // 취득과세표 * 0.2% (1단위 절삭)
				} else if($row[multi_housing_type]=="4"){ // 3주택
					$imsi3 = floor( $row[af1] * 0.002 / 10 ) * 10;  // 취득과세표 * 0.2% (1단위 절삭)
				} else if($row[multi_housing_type]=="5"){ // 4주택이상
					$imsi3 = floor( $row[af1] * 0.002 / 10 ) * 10;  // 취득과세표 * 0.2% (1단위 절삭)
				} else if($row[multi_housing_type]=="6"){ // 법인
					$imsi3 = floor( $row[af1] * 0.002 / 10 ) * 10;  // 취득과세표 * 0.2% (1단위 절삭)
				}

			} else if($row[tax_apply]=="2"){ // 신법적용

				// 조정지역여부
				if($kk[jojeong_yn]=="y"){ // 조정지역
				
					// 다주택여부
					if($row[multi_housing_type]=="1"){ // 1주택
						$imsi3 = floor( $row[af1] * 0.002 / 10 ) * 10;  // 취득과세표 * 0.2% (1단위 절삭)
					} else if($row[multi_housing_type]=="2"){ // 일시적2주택
						$imsi3 = floor( $row[af1] * 0.002 / 10 ) * 10;  // 취득과세표 * 0.2% (1단위 절삭)
					} else if($row[multi_housing_type]=="3"){ // 2주택
						$imsi3 = floor( $row[af1] * 0.006 / 10 ) * 10;  // 취득과세표 * 0.6% (1단위 절삭)
					} else if($row[multi_housing_type]=="4"){ // 3주택
						$imsi3 = floor( $row[af1] * 0.01 / 10 ) * 10;  // 취득과세표 * 1% (1단위 절삭)
					} else if($row[multi_housing_type]=="5"){ // 4주택이상
						$imsi3 = floor( $row[af1] * 0.01 / 10 ) * 10;  // 취득과세표 * 1% (1단위 절삭)
					} else if($row[multi_housing_type]=="6"){ // 법인
						$imsi3 = floor( $row[af1] * 0.01 / 10 ) * 10;  // 취득과세표 * 1% (1단위 절삭)
					}
				} else if($kk[jojeong_yn]=="n"){ // 비조정지역

					// 다주택여부
					if($row[multi_housing_type]=="1"){ // 1주택
						$imsi3 = floor( $row[af1] * 0.002 / 10 ) * 10;  // 취득과세표 * 0.2% (1단위 절삭)
					} else if($row[multi_housing_type]=="2"){ // 일시적2주택
						$imsi3 = floor( $row[af1] * 0.002 / 10 ) * 10;  // 취득과세표 * 0.2% (1단위 절삭)
					} else if($row[multi_housing_type]=="3"){ // 2주택
						$imsi3 = floor( $row[af1] * 0.002 / 10 ) * 10;  // 취득과세표 * 0.2% (1단위 절삭)
					} else if($row[multi_housing_type]=="4"){ // 3주택
						$imsi3 = floor( $row[af1] * 0.006 / 10 ) * 10;  // 취득과세표 * 0.6% (1단위 절삭)
					} else if($row[multi_housing_type]=="5"){ // 4주택이상
						$imsi3 = floor( $row[af1] * 0.01 / 10 ) * 10;  // 취득과세표 * 1% (1단위 절삭)
					} else if($row[multi_housing_type]=="6"){ // 법인
						$imsi3 = floor( $row[af1] * 0.01 / 10 ) * 10;  // 취득과세표 * 1% (1단위 절삭)
					}
				}
			}
		} else {
			$imsi3 = 0;
		}
		
	} else if($apply_type=="2"){ // 오피스텔
		$imsi3 = floor( $row[af1] * 0.002 / 10 ) * 10;  // 취득과세표 * 0.2% (1단위 절삭)
		
	} else if($apply_type=="3"){ // 상가
		$imsi3 = floor( $row[af1] * 0.002 / 10 ) * 10;  // 취득과세표 * 0.2% (1단위 절삭)
	}

//	}
	if($gubun=="1"){
		$imsi = $imsi1;
	} else if($gubun=="2"){
		$imsi = $imsi2;
	} else if($gubun=="3"){
		$imsi = $imsi3;
	} else if($gubun=="4"){
		$imsi = $imsi1 + $imsi2 + $imsi3;
	} else {
		$imsi = 0;
	}
	//echo "<script>alert({$imsi});</script>";
	return $imsi;
	//return $aa;
}
?>
