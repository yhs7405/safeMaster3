<?php
session_start();

//error_reporting(E_ALL); 
//ini_set("display_errors", 1);

if($_POST["token"] <> $_SESSION["token_tmp"] ) exit;
//테스트 작업이 끝났을때만 아래의 주석을 풀 것
//else $_SESSION["token"] = $_SESSION["token_tmp"];

//0.필요한 라이브러리 로드
include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";
//include $_SERVER["DOCUMENT_ROOT"]."/lib/reader.php";
include $_SERVER["DOCUMENT_ROOT"]."/inc/excel.inc";
include $_SERVER["DOCUMENT_ROOT"]."/inc/util.inc";

//0. 사용할 변수 초기화
$order_group = 1;
$excel_type = $file_name = "";
$file_dest = $_SERVER["DOCUMENT_ROOT"]."/tmp/";

//0. FORM  전송값 받기
if($_POST["order_group"]) $order_group = $_POST["order_group"];
if($_POST["excel_type"]) $excel_type = $_POST["excel_type"];
if($_FILES["excel_file"]["name"]) $file_name = $_FILES["excel_file"]["name"];

//0. 파일 확장자가 xls 인지 확인
//0. 파일 업로드 
//$file_arr = explode(".", $file_name);
//$file_arr_count = count($file_arr);
//$ext = $file_arr[ ($file_arr_count-1) ];
$ext = util_get_file_ext($file_name); 
//echo $file_name;
if( $ext == "xls" ){
	$file_name = "imsi_upload_".time().".".$ext;
	if( !move_uploaded_file($_FILES["excel_file"]["tmp_name"], $file_dest.$file_name) ){
		echo "파일을 업로드 하는 도중 오류가 발생하였습니다 [CODE : 001]";
		exit;
	}
}else{
	echo "엑셀파일 .xls 파일만 업로드가 가능합니다";
	exit;
}

//0. 엑셀 파일 메모리에 로드
$obj = new Excel_Reader($file_dest.$file_name, true, "euc-kr") or die("파일을 로드하는 도중 오류가 발생하였습니다 [CODE : 002]");
$row = $obj->rowcount();
$col = $obj->colcount();
//echo $row."". $col."<br>";
//print_r($obj);
$excel = array();
for($i=0; $i<$row; $i++){
	for($k=0; $k<$col; $k++){
		$excel[$i][$k] = trim($obj->val($i+1, $k+1));
		//echo $excel[$i][$k]."<br>";
	}
}


//0. 엑셀 파일 메모리에 로드
/*
$obj = new Spreadsheet_Excel_Reader();
$obj->setOutputEncoding('CP949');
$obj->read($file_dest.$file_name);
$row = $obj->sheet[0]['numRows'];
$col = $obj->sheet[0]['numClos'];
echo $row."-".$col."<Br>";
$excel = array();
print_r($obj);
for($i=0; $i<$row; $i++){
	for($k=0; $k<$col; $k++){
		$excel[$i][$k] = trim($obj->sheet[0]['cells'][$i+1][$k+1]);
		echo $i."-".$k."-".$excel[$i][$k]."<Br>";
		
	}
}
*/


//0. 데이터 입력 시작
$field = get_excel_field($excel_type);//마켓별 순서를 가져온다.
//echo $excel_type."<br>";
//print_r($field);
//echo "<br>";
$count = 0;
$current_idx = 0;

foreach($excel as $row=>$col){
	if($count==0){
		//필드명이 붙은 첫번째 줄 통과
		$count++;
		continue;
	}

	//print_r($col);
	//echo "<Br><Br>";

	$idx = $col[ $field["market_order_num"] ]; //주문번호
	if($current_idx <> $idx){
		if($current_idx==0){
			$current_idx = $idx;
		}else{
			//totalp_divide($current_idx);
			$current_idx = $idx;
		}
	}

	if(($col[$field["baddress"]]=="")&&($col[$field["name"]]=="")){
		//echo $col[$field["goods_name"]]."<br>";
		continue;
	}else{
		//echo $col[$field["goods_name"]]."<br>";
		if( is_already_exist_by_idx($idx) ) {  //오픈마켓 주문번호가 이미 있다면
			//이미 jumuninfo_imsi 에 해당 주문이 기록 되어있다면 
			//0) jumuninfo_imsi 의 idx 를 획득 => jumuninfo_idx
			//1) jumundata_imsi 에 해당하는 항목만 추가 한다
			//2) jumuninfo_imsi 에 jumundata 에 입력된 금액만금을 업데이트 한다 (현재 네이버 페이만 jumuninfo를 추가로 업데이트 하고, 그외는 jumuninfo를 건드리지 않음)
			//3) 엑셀의 다음 행을 수행 = continue;
			$jumuninfo_idx = get_jumuninfoidx_by_jumunidx($idx); //0)  jumuninfo 의 num 획득

			//if(get_jumundata_duplicate($idx)=="y"){ continue;}  //jumundata에 이미 있다면 튕겨냄

			if($jumuninfo_idx>0){
				$jumundata_idx = insert_jumundata($jumuninfo_idx, $col, $field, $excel_type, true); //1)
				//if($excel_type=="pay") update_jumuninfo_by_jumundata($jumundata_idx); //2)
				update_jumuninfo_by_jumundata($jumundata_idx); //2) 전체 주문 금액 계산  
				continue;//3)이미 jumuninfo 정보가 있다면 건너뜀
			}else{
				echo "{$idx} => 주문인포 idx를 가져오지 못했습니다 [CODE:004]";
				exit;
			}
		}

		//주문 기초정보 준비
		$name               = addslashes($col[ $field["name"] ]);		//주문자
		$bname              = addslashes($col[ $field["bname"] ]);		//배송받는사람
		$bzip               = $col[ $field["bzip"] ];				//배송우편번호
		$baddress           = addslashes($col[ $field["baddress"] ]);		//배송주소
		$btel               = util_tel_format( $col[ $field["btel"] ] );	//배송전화번호
		$bhp                = util_hp_format( $col[ $field["bhp"] ] );		//배송핸드폰번호
		$text               = addslashes($col[ $field["text"] ]);		//배송시 유의사항
		$paymethod          = get_paymethod( $col[$field["paymethod"]]);	//배송방법
		$totalp             = get_totalp($col, $field, $excel_type);		//전체 주문금액계산
		$assort             = $excel_type;					//마켓구분
		$market_cart_num    = $col[ $field["market_cart_num"] ];		//주문번호
		$date               = time();						//데이타입력일
		$orderdate          = date("YmdHis");					//데이타입력일
		$add_option         = $col[ $field["add_option"] ];			//추가옵션
		$shoplinker_order_num=$col[ $field["shoplinker_order_num"] ];		//사방넷주문번호

		if($add_option){
			$bom_ch="y";
		}else{
			$bom_ch="";
		}

		$stmt = $pdo->prepare("
			INSERT INTO jumuninfo_imsi 
				 ( 
					jumun_idx, name, bname, bzip, baddress, 
					btel, bhp, text, totalp, assort, 
					date, paymethod, order_group, hoicha, market_cart_num, orderdate, bom_ch
				)
			VALUES (
					?, ?, ?, ?, ?,
					?, ?, ?, ?, ?, 
					?, ?, ?, ?, ?, ?, ?
				)"
		);

		$stmt->execute([
			$idx, $name, $bname, $bzip, $baddress, 
			$btel, $bhp, $text, $totalp, $assort, 
			$date, $paymethod, $order_group, $order_group, $market_cart_num, $orderdate,$bom_ch
		]);
		
		$jumuninfo_idx = $pdo->lastInsertId();
		if( $jumuninfo_idx > 0  ){
			insert_jumundata($jumuninfo_idx, $col, $field, $excel_type);  //jumundata_imsi 에 데이타 입력
		}

	}
}

//totalp_divide($current_idx); //가장 마지막 주문도 한번 가격 정렬을 해줌

if($assort==""){
	echo "<script>alert('처리안됨,전산팀 문의하세요.');location.href = 'http://adm.monsterlabs.co.kr/src/order/total_order/upload.php?menu=GNB001|LNB001|SNB080'</script>";
}else{
	echo "<script>location.href = 'http://adm.monsterlabs.co.kr/src/order/total_order/imsi_list.php?token=$_SESSION[token]&menu=GNB001|LNB001|SNB083&assort={$assort}'</script>";
}
//exit;

?>

<?php
function totalp_divide($jumun_idx){
	$return = 0;

	if($jumun_idx>0){
		include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";
		$data = null;
		$stmt = $pdo->prepare("
			SELECT 
				d.num data_idx,
				j.totalp,
				i.tkind, d.pn, ifnull(i.ecount_name,i.name) pname, 
				IFNULL(i.cprice,0) cprice
			FROM
				jumuninfo_imsi j
				LEFT JOIN jumundata_imsi d ON j.num = d.no
				LEFT JOIN item i ON i.num = d.pn
			WHERE
				jumun_idx = ? AND d.event_ch<>'y'
			order by
				(case i.tkind 
					when 4 then 1 
					when 7 then 2 
					when 5 then 3
					when 1 then 4 
					when 2 then 5 
					when 3 then 6 
					else 6  end) asc, 
				(case i.lkind when 13 then 1 else 2 end) asc");
		$stmt->execute([$jumun_idx]);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$data = $stmt->fetchAll();
		$result_rows = count($data);

		$totalp= 0; //주문의 총 주문액 이자 이 이상 금액이 커질 수 없는 리미트 값
		$remain = 0; //임시로 계속 보관하게 되는 나머지 값을 담는 변수
		foreach($data as $count=>$row){
			if($totalp == 0){
				$totalp = $row["totalp"];
				$remain = $totalp;
			}
			if(($count+1) == $result_rows ){
				$cprice = $remain;
			}else{
				$cprice = $row["cprice"];
				$remain = $remain - $cprice;
			}
			$data_idx = $row["data_idx"];
			$up = $pdo->prepare("UPDATE jumundata_imsi SET price=? WHERE num=?");
			$up->execute([$cprice, $data_idx]);
			$return++;
		}
	}

	return $return;
}

//@function (String) get_totalp(Array, Array, String);
//@param1 : 현재 진행중인 엑셀의 row
//@param2 : 엑셀의 각 필드순서를 이름과 매칭하는 배열
//@param3 : 엑셀의 종류 (winwin or iclub 등등)
function get_totalp($col, $field, $excel_type){
	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";
	$return = 0;

	$return = util_number_unformat( $col[$field["totalp"]] );

	return $return;
}

//@function (String) util_tel_format(String);
//@desc : 임의의 전화번호 값을 받아서, 표준 규격 [0-9]{3,4}-[0-9]{3,4}-[0-9]{4} 형식으로 반환
//@param1 : 임의의 번호 (03112345678 혹은 031-1234-5678 등등의 형식)
function util_tel_format($str){
	//차후에 정규표현식을 써서 좀더 정교하게 필터링할 예정
	$str = str_replace("'", "", $str);
	return $str;
}

function util_hp_format($str){
	//차후에 정규표현식을 써서 좀더 정교하게 필터링할 예정
	$str = str_replace("'", "", $str);
	return $str;	
}


function update_jumuninfo_by_jumundata($str){  //전체상품가격이 없을때 갱신
	
	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	//1)주문데이터를 조회해서 (price * qty) = (1) 획득
	//2)소속 주문인포를 찾아서 totalp += (1)  업데이트
	$stmt = $pdo->prepare("SELECT (price*qty) as data_price, no jumuninfo_idx FROM jumundata_imsi WHERE num=?");
	$stmt->execute([$str]);
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	$data_price = $row["data_price"];
	$jumuninfo_idx = $row["jumuninfo_idx"];

	//echo "UPDATE jumuninfo_imsi SET totalp = totalp+$data_price WHERE num=$jumuninfo_idx <br/><br/>";
	$stmt = $pdo->prepare("UPDATE jumuninfo_imsi SET totalp = totalp+? WHERE num=?");//주문합계누계 갱신
	$stmt->execute([$data_price, $jumuninfo_idx]);
}


//@function : function(String) insert_jumundata(String, Array, Array, String, Boolean);
//@desc : 주문인포 인덱스 와 엑셀 데이터 및 엑셀 타입을 인수로 받아서 jumundata에 insert. 그 후 생성된 jumundata의 idx키를 반환
//@param1 : jumuninfo_imsi의 인덱스 키 num
//@param2 : 현재 입력작업이 진행중인 엑셀의 row data
//@param3 : 엑셀의 몇번째 필드가 어떤 값을 의미하는지 매칭하는 배열
//@param4 : 엑셀의 타입 (winwin or iclub)
//@param5 : 추가 주문인지 아닌지를 판단하는 플래그. (false 이면 처음 등록하는 주문데이터 이므로 상품 가격을 입력, true 이면 이미 등록된 주문에 추가적으로 입력하는 것이므로 상품의 가격을 0으로 입력)

function insert_jumundata($idx, $col, $field, $excel_type, $is_add_jumun=false){
	$return = 0;
	$add_opt_ch = ($is_add_jumun) ? 1 : 0; //추가 주문이라면 1 아니라면 0

	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	$jumun_idx	= $col[ $field["market_order_num"] ];	//주문 고유번호
	$cart_idx	= $col[ $field["market_cart_num"] ];	//묶음 여부를 처리할 번호
	$goods_name	= $col[ $field["goods_name"] ];		//상품명
	$goods_name	= str_replace(chr(34),"",$goods_name);	//상품명 - " 제거

	$add_option	= $col[ $field["add_option"] ];		//추가옵션
	$add_option2	= $col[ $field["add_option2"] ];	//추가옵션2
	$add_option3	= $col[ $field["add_option3"] ];	//추가옵션3
	$add_option4	= $col[ $field["add_option4"] ];	//추가옵션4
	$add_option5	= $col[ $field["add_option5"] ];	//추가옵션5

	//echo $goods_name."<br>";

	$pilsu_option	= $col[ $field["pilsu_option"] ];	//필수옵션
	$qty		= $col[ $field["qty"] ];		//갯수
	$price		= $col[ $field["totalp"] ];		//실판매가
	$shoplinker_order_num=$col[ $field["shoplinker_order_num"] ];	//사방넷주문번호


	$iteminfo = get_iteminfo($goods_name, $excel_type);	//상품명으로 검색하고 리턴한다.


	if($add_option2!=""){
		$add_option = $add_option.",".$add_option2;
	}
	if($add_option3!=""){
		$add_option = $add_option.",".$add_option3;
	}
	if($add_option4!=""){
		$add_option = $add_option.",".$add_option4;
	}
	if($add_option5!=""){
		$add_option = $add_option.",".$add_option5;
	}

	//print_r($iteminfo);


	if($iteminfo["pn"]>0){
		$pn		= $iteminfo["pn"];
		$pname		= $iteminfo["pname"];
		$jecount_code	= $iteminfo["ecount_code"];
		$ecount_code	= $iteminfo["ecount_code"];
		$ecount_name	= $iteminfo["ecount_name"];

	//echo $ecount_code."--sfdsfsd------------".$ecount_name."<Br>";

	}else{
		if($pn>0) {
			if($ecount_code){
				$ss_price = 0;
			}else{
				$ecount_code = $ss_price = 0;
			}
		}else{
			$pn = $ecount_code = $ss_price = 0;
		}
		$pname = $ecount_name = $goods_name;
	}

	//echo $ecount_code."--------------".$ecount_name."<Br>";
	if($qty==0) $qty = 1;

	$price = util_number_unformat($col[ $field["totalp"] ]); 

//	echo	"<Br><Br>$idx, $pn, $pname, $qty, $price, 
//			$master_pn, $ecount_code, $option, $add_opt_ch, $jumun_idx,
//			$cart_idx, $ecount_name, $price <br><Br>";

	$stmt = $pdo->prepare("
		INSERT INTO jumundata_imsi 
			(
				no, pn, pname,ori_pname, qty, price,
				winwin_code, jecount_code, add_opt_ch, market_order_num,
				market_cart_num, jecount_name, market_sell_price, opt, pilsu_option,shoplinker_order_num 
			) 
		VALUES 
			( 
				?, ?, ?, ?, ?, ?,
				?, ?, ?, ?, 
				?, ?, ?, ?, ?, ?
			)");

	$stmt->execute([
			$idx, $pn, $pname,$pname, $qty, $price, 
			$pn, $ecount_code, $add_opt_ch, $jumun_idx,
			$cart_idx, $ecount_name, $price, $add_option, $pilsu_option, $shoplinker_order_num 
		]); //정제되기전 $pns 를 넣어야 함 (윈윈의 bom일 경우를 체크하기 위함)

	event_item_chk_and_insert($idx, $pn,$excel_type); //이벤트 증정품 처리

//	echo //$idx."--pn".$pn."--pname".$pname."qty--".$qty."--".$price."--".$master_pn."-ecount>".$ecount_code."--".$option."--".$add_opt_ch."--".$jumun_idx."--".$cart_idx."--".$ecount_name."--".$price."<br><br>";

	$jumundata_idx = $pdo->lastInsertId();

	//옵션값들을 jumundata_imsi_option에 입력한다.
	//insert_optin_data($idx, $jumundata_idx, $add_option, $pilsu_option );//메인상품의 jumundata_idx만 가져간다.

	if( $jumundata_idx > 0){
		$return = $jumundata_idx;
		//0. 주문데이터가 제대로 생성되었다면 jumundata_imsi -> shoplinker_order_num 에 'A'.{$jumundata_idx} 갑을 업데이트

		if($shoplinker_order_num==""){
			$tmp = $pdo->prepare("UPDATE jumundata_imsi SET shoplinker_order_num = ? WHERE num = ? ");
			$tmp->execute(['A'.$jumundata_idx, $jumundata_idx]);
		}

		//if($shoplinker_order_num!=""){  //오리지널 사방넷주문번호
		//	$tmp = $pdo->prepare("UPDATE jumundata_imsi SET shoplinker_order_num_main = ? WHERE num = ? ");
		//	$tmp->execute([$shoplinker_order_num, $jumundata_idx]);
		//}

		//1번 처리 - 추가옵션항목이 있으면
		$arr = explode(",", trim($add_option) );
		$count = count($arr);
		if($count==0){ // '가 없다면 |를 2순위로 한다.
			$arr = explode("|", trim($add_option) );
			$count = count($arr);
		}
		if($count==0){ // '가 없다면 |를 2순위로 한다.
			$arr = explode("^", trim($add_option) );
			$count = count($arr);
		}

		for($i=0; $i<=$count; $i++){
			if($arr[$i]){
				$sql = "SELECT * FROM jumundata_imsi_option WHERE jumundata_num={$jumundata_idx} and pname='".$arr[$i]."'";
				//echo "<Br>".$sql."<Br>";
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				$row = $stmt->fetch();
				if($row[num]==""){  //같은것이 없다면 등록한다.
					$stmt = $pdo->prepare("
						INSERT INTO jumundata_imsi_option(
								no,jumundata_num,pname,add_opt_ch,pn)
						VALUES(?, ?, ?, ?, ?)
					");
					$stmt->execute([
					$idx, $jumundata_idx, trim($arr[$i]) ,"y", $pn ]);
				}
			}
		}

		//2. 윈윈코드 를 통해 매칭이 이뤄진경우 ( $pn<> 0)
		//3. BOM 여부를 체크해서 BOM 입력 진행
		if($pn<>0 && is_bom_product($pn)){

			//BOM 삽입
			insert_bom_data($idx, $jumundata_idx, $pn, $qty, $add_option,$pilsu_option, $jumun_idx, $cart_idx,$shoplinker_order_num);

			//BOm 변경처리
			change_bom_data($idx, $jumundata_idx, $pn, $qty, $add_option,$pilsu_option, $jumun_idx, $cart_idx,$shoplinker_order_num);

		}

		//옵션 일반처리
		change_add_data($idx, $jumundata_idx, $pn, $qty, $add_option,$pilsu_option, $jumun_idx, $cart_idx);
	}
	return $return;
}

function event_item_chk_and_insert($jumuninfo_idx, $pn=0,$excel_type){//이벤트 상품 처리(bom 처리 아님)
	
	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";
	
	if($pn>0){
		if(($excel_type!="compuzone")&&($excel_type!="preflow")&&($excel_type!="winwin")&&($excel_type!="B2B")){  //컴퓨존제외,프리플로우제외

			if($excel_type=="iclub"){
				$excel_type = "superstore";
			}

			$today = date("YmdHis");
			 $event_where = " and $excel_type='y' ";
			$stmt = $pdo->prepare("SELECT event_pn FROM v_event_list2 WHERE ? BETWEEN event_start AND event_end and pn = ?  $event_where and (event_bom is null or event_bom='') ");
			$stmt->execute([$today, $pn]);

			//echo "SELECT event_pn FROM v_event_list2 WHERE {$today} BETWEEN event_start AND event_end and pn = $pn <br>";

			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			while($row = $stmt->fetch()){
				$event_pn = $row["event_pn"];

				if($event_pn>0) {
						$stmt2 = $pdo->prepare("SELECT num, name, ecount_code, ecount_name FROM item WHERE num = ? ");

						//echo "SELECT num, name, ecount_code, ecount_name FROM item WHERE num = {$event_pn} <br> ";

						$stmt2->execute([$event_pn]);
						$stmt2->setFetchMode(PDO::FETCH_ASSOC);
						$item = $stmt2->fetch();

						$num = $item["num"];
						$name = $item["name"];
						$ecount_code = $item["ecount_code"];
						$ecount_name = $item["ecount_name"];
						if($num>0){
							$insert = $pdo->prepare("
							INSERT INTO 
								jumundata_imsi 
									(no, pn, pname, qty, price, add_opt_ch, event_ch, jecount_code, jecount_name) 
								VALUES 
									(?, ?, ?, ?, ?, ?, ?, ?, ?)");
							$insert->execute([$jumuninfo_idx, $num, $name, 1, 0, 1, 'y', $ecount_code, $ecount_name]);
							//echo "$jumuninfo_idx, $num, $name, 1, 0, 1, 'y', $ecount_code, $ecount_name<br><br>";
						}
				}
			}
		}
	}
}


function get_option($str){
	$return = "";

	//선택옵션 체크
	$pattern = "/\[선택옵션:.*.\)]/";
	preg_match($pattern, $str, $res);
	if($res[0]){
		$tmp = $res[0];
		$arr = explode("(", $tmp);
		$option = $arr[0];
			$t = explode(":", $option);
			$option = trim($t[1]);
		$price = $arr[1];
			$price = str_replace("+", "", $price);
			$price = str_replace("-", "", $price);
			$price = trim(str_replace("원)]", "", $price));
			$price = trim(util_number_unformat($price));
			$qty = get_qty(0, $tmp, "winwin");
			$qty = util_number_unformat($qty);
			$qty = trim($qty);
		$return = "상품선택:{$option}/{$price}원/{$qty}개";
	}

	return $return;
}


//@function (Boolean) insert_pay_bom_data(String, String, String, String);
//@desc : 네이버페이 BOM 상품인경우 mysql->tbl->jumun_bom_imsi에 BOM 데이터를 입력한다
function insert_bom_data($idx, $data_idx, $master_pn, $qty, $add_option, $pilsu_option, $market_order_num, $market_cart_num,$shoplinker_order_num){
	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	$master_iteminfo = get_master_iteminfo($master_pn);

	$stmt = $pdo->prepare("SELECT b.tkind, b.lkind, b.pn, b.nickname, b.qty, i.ecount_code, i.ecount_name FROM bom_data b LEFT JOIN item i ON b.pn=i.num WHERE b.item = ? AND b.chk=1");
	$stmt->execute([$master_pn]);
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$bom = $stmt->fetchAll();

	foreach($bom as $count => $row){
		$tkind = $row["tkind"];
		$lkind = $row["lkind"];
		$pn = $row["pn"];
		$nickname = $row["nickname"];
		$spec_qty = $qty * $row["qty"];
		$change_ch = 'n'; 
		$ecount_code = $row["ecount_code"];
		$ecount_name = $row["ecount_name"];

		//원래의 스펙 입력
		$stmt = $pdo->prepare("
			INSERT INTO jumun_bom_imsi
				(
					no, item, tkind, lkind, pn,
					nickname, price, qty, change_ch, jecount_code,
					chk, jumundata_num, main_shoplinker_order_num, jecount_name, item_jecount_code,
					item_jecount_name
				)
			VALUES
				(
					?, ?, ?, ?, ?,
					?, ?, ?, ?, ?,
					?, ?, ?, ?, ?,
					?
				)
		");
		if($shoplinker_order_num==""){
			$stmt->execute([
				$idx, $master_pn, $tkind, $lkind, $pn,
				$nickname, 0, $spec_qty, $change_ch, $ecount_code,
				1, $data_idx, 'A'.$data_idx, $ecount_name, $master_iteminfo["ecount_code"],
				$master_iteminfo["ecount_name"]
			]);
		}else{
			$stmt->execute([
				$idx, $master_pn, $tkind, $lkind, $pn,
				$nickname, 0, $spec_qty, $change_ch, $ecount_code,
				1, $data_idx, $shoplinker_order_num, $ecount_name, $master_iteminfo["ecount_code"],
				$master_iteminfo["ecount_name"]
			]);
		}

		//echo "$idx, $master_pn, $tkind, $lkind, $pn,
		//		$nickname, 0, $spec_qty, $change_ch, $ecount_code,
		//		1, $data_idx, $shoplinker_order_num, $ecount_name, $master_iteminfo[ecount_code],
		//		$master_iteminfo[ecount_name]  <Br>";
	}
}

//옵션 추가 처리
function change_add_data($idx, $data_idx, $master_pn, $qty, $add_option, $pilsu_option, $market_order_num, $shoplinker_order_num){
	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	$master_iteminfo = get_master_iteminfo($master_pn);

			//bom 변경처리
			//1.jumundata_imsi_option 에서 no/jumundata_idx/pn/pname/add_opt_ch='y' 일때 검색
			//2.jumundata_imsi_option_history main_pn,pname 조인검색
			//3.jumundata_imsi_option_history 에서 pn,gubun,tkind,lkind 삽입하고 기존데이타 처리
			//4.jumundata_imsi_option 에서 처리건은 삭제함
			$sql = "SELECT a.num AS jumundata_imsi_idx,b.pname,b.qty,b.gubun,b.main_pn,b.pn,b.tkind,b.lkind FROM jumundata_imsi_option  a cross join jumundata_imsi_option_history b on a.pname=b.pname where a.no=$idx and a.jumundata_num=$data_idx and a.pn={$master_pn} and b.main_pn={$master_pn} and a.add_opt_ch='y' ";
			//echo "<Br>".$sql."<Br>";

			$stmt2 = $pdo->prepare($sql);
			$stmt2->execute();
			$stmt2->setFetchMode(PDO::FETCH_ASSOC);

			while($row2 = $stmt2->fetch()){
				if($row2[gubun]=="buy_add"){//그냥 상품 추가일때

					$sql = "SELECT shoplinker_order_num FROM jumundata_imsi WHERE num={$data_idx} ";
					//echo "<Br>".$sql."<Br>";
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					$row = $stmt->fetch();
					$shoplinker_order_num = $row[shoplinker_order_num];


					//기존루틴
					//echo  "SELECT ecount_code, ecount_name FROM item WHERE num = $row2[pn]";
					$stmt = $pdo->prepare("SELECT ecount_code, ecount_name FROM item WHERE num = ?");
					$stmt->execute([$row2[pn]]);
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					$master_iteminfo = $stmt->fetch();

					$change_item = get_change_iteminfo($row2[pn]);

					//추가구매
					$stmt = $pdo->prepare("
						INSERT INTO jumundata_imsi 
							(
								no, pn, pname, qty, price,
								winwin_code, jecount_code, add_opt_ch, market_order_num,
								market_cart_num, jecount_name, market_sell_price,shoplinker_order_num
							) 
						VALUES 
							( 
								?, ?, ?, ?, ?,
								?, ?, ?, ?, 
								?, ?, ?,?
							)");

					$stmt->execute([
							$idx,  $row2[pn], $change_item["name"], $row2[qty], 0, 
							'', $change_item["ecount_code"], '', $market_order_num,
							'', $change_item["ecount_name"], 0, $shoplinker_order_num
					]); //정제되기전 $pns 를 넣어야 함 (윈윈의 bom일 경우를 체크하기 위함)


					//데이타삭제
					$stmt = $pdo->prepare("delete from jumundata_imsi_option where num=?  ");
					$stmt->execute([$row2[jumundata_imsi_idx]]);

					}
			}
}

//bom 변경처리
function change_bom_data($idx, $data_idx, $master_pn, $qty, $add_option, $pilsu_option, $market_order_num, $market_cart_num, $shoplinker_order_num){
	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	$master_iteminfo = get_master_iteminfo($master_pn);

			//bom 변경처리
			//1.jumundata_imsi_option 에서 no/jumundata_idx/pn/pname/add_opt_ch='y' 일때 검색
			//2.jumundata_imsi_option_history main_pn,pname 조인검색
			//3.jumundata_imsi_option_history 에서 pn,gubun,tkind,lkind 삽입하고 기존데이타 처리
			//4.jumundata_imsi_option 에서 처리건은 삭제함
			$sql = "SELECT a.num AS jumundata_imsi_idx,b.pname,b.qty,b.gubun,b.main_pn,b.pn,b.tkind,b.lkind FROM jumundata_imsi_option  a cross join jumundata_imsi_option_history b on a.pname=b.pname where a.no=$idx and a.jumundata_num=$data_idx and a.pn={$master_pn} and b.main_pn={$master_pn} and a.add_opt_ch='y' ";
			//echo "<Br>".$sql."<Br>";

			$stmt2 = $pdo->prepare($sql);
			$stmt2->execute();
			$stmt2->setFetchMode(PDO::FETCH_ASSOC);

			while($row2 = $stmt2->fetch()){
				if($row2[gubun]=="bom_change"){//bom 변경일때
					//1.기존 tkind,lkind 상품 change=y
					$stmt = $pdo->prepare("update jumun_bom_imsi set change_ch='y' where no=? and jumundata_num=? and item=? and tkind=? and lkind=?");
					$stmt->execute([$idx,$data_idx,$master_pn,$row2[tkind],$row2[lkind]]);

					//2.변경추가상품은 c 로 하고 insert
					$stmt = $pdo->prepare("INSERT INTO jumun_bom_imsi(
							no, item, tkind, lkind, pn,
							nickname, price, qty, change_ch, jecount_code,
							chk, jumundata_num, main_shoplinker_order_num, jecount_name, item_jecount_code,
							item_jecount_name,ori_pname
						)VALUES(
							?, ?, ?, ?, ?,
							?, ?, ?, ?, ?,
							?, ?, ?, ?, ?,
							?, ?
						)");
					$pn_iteminfo = get_master_iteminfo($row2[pn]);
					//echo "bom_change<br>";
					if($shoplinker_order_num==""){
						$stmt->execute([
							$idx, $master_pn, $row2[tkind], $row2[lkind], $row2[pn],
							$pn_iteminfo["name"], 0, $row2[qty], "c", $pn_iteminfo["ecount_code"],
							1, $data_idx, 'A'.$data_idx, $pn_iteminfo["ecount_name"], $master_iteminfo["ecount_code"],
							$master_iteminfo["ecount_name"],$row2[pname]
						]);
					}else{
						$stmt->execute([
							$idx, $master_pn, $row2[tkind], $row2[lkind], $row2[pn],
							$pn_iteminfo["name"], 0, $row2[qty], "c", $pn_iteminfo["ecount_code"],
							1, $data_idx, $shoplinker_order_num, $pn_iteminfo["ecount_name"], $master_iteminfo["ecount_code"],
							$master_iteminfo["ecount_name"],$row2[pname]
						]);
					}

					$stmt = $pdo->prepare("update jumundata_imsi set bom_change='BOM변경' WHERE num = {$data_idx} ");
					$stmt->execute();

					$stmt = $pdo->prepare("update jumuninfo_imsi set bom_ch='y',bom_change='BOM변경' WHERE num = {$idx} ");
					$stmt->execute();

					//3.jumundata_imsi_option 에 해당 아이템 삭제
					$stmt = $pdo->prepare("delete from jumundata_imsi_option where num=?");
					$stmt->execute([$row2[jumundata_imsi_idx]]);

					//4.주문data bom_ch=y로변경
					$sql3 = "update jumuninfo_imsi set bom_ch='y' WHERE num=".$idx;
					//echo $sql3."<Br>";
					$stmtx3 = $pdo->prepare($sql3);
					$stmtx3->execute();

				}else if($row2[gubun]=="bom_add"){//bom 추가일때
					//1.기존 tkind,lkind 상품 추가상품은 i 로 하고 insert
					$stmt = $pdo->prepare("INSERT INTO jumun_bom_imsi(
							no, item, tkind, lkind, pn,
							nickname, price, qty, change_ch, jecount_code,
							chk, jumundata_num, main_shoplinker_order_num, jecount_name, item_jecount_code,
							item_jecount_name,ori_pname
						)VALUES(
							?, ?, ?, ?, ?,
							?, ?, ?, ?, ?,
							?, ?, ?, ?, ?,
							?, ?
						)");
					$pn_iteminfo = get_master_iteminfo($row2[pn]);
					if($shoplinker_order_num==""){
						$stmt->execute([
							$idx, $master_pn, $row2[tkind], $row2[lkind], $row2[pn],
							$pn_iteminfo["name"], 0, $row2[qty], "i", $pn_iteminfo["ecount_code"],
							1, $data_idx, 'A'.$data_idx, $pn_iteminfo["ecount_name"], $master_iteminfo["ecount_code"],
							$master_iteminfo["ecount_name"],$row2[pname]
						]); 
					}else{
						$stmt->execute([
							$idx, $master_pn, $row2[tkind], $row2[lkind], $row2[pn],
							$pn_iteminfo["name"], 0, $row2[qty], "i", $pn_iteminfo["ecount_code"],
							1, $data_idx, $shoplinker_order_num, $pn_iteminfo["ecount_name"], $master_iteminfo["ecount_code"],
							$master_iteminfo["ecount_name"],$row2[pname]
						]); 
					}

					$stmt = $pdo->prepare("update jumundata_imsi set bom_change='BOM추가' WHERE num = {$data_idx} ");
					$stmt->execute();

					$stmt = $pdo->prepare("update jumuninfo_imsi set bom_ch='y',bom_change='BOM추가' WHERE num = {$idx} ");
					$stmt->execute();

					//2.jumundata_imsi_option 에 해당 아이템 삭제
					$stmt = $pdo->prepare("delete from jumundata_imsi_option where num=?");
					$stmt->execute([$row2[jumundata_imsi_idx]]);

					//3.주문data bom_ch=y로변경
					$sql3 = "update jumuninfo_imsi set bom_ch='y' WHERE num=".$idx;
					//echo $sql3."<Br>";
					$stmtx3 = $pdo->prepare($sql3);
					$stmtx3->execute();

				}
			}
}



function convert_pay_option_to_array($ori_pn,$opt){

	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	$return = array();

	if($opt){
		$arr = explode(",", trim($opt) );
		$count = count($arr);
		for($i=0; $i<($count-1); $i++){
			$t = explode("_", $arr[$i]);   // 4475_Windows 10 Home + 한성마우스 (+139,000원)(1개)/2182_DDR4 8G PC4-17000 (+30,000원)(1개)/4432_HDD 500GB (SATA3/7200) (+55,000원)(1개)
			//앞에 hs 코드를 가져온다
			$sql = "SELECT * FROM bom_data WHERE item={$ori_pn} and pn={$t[0]}";
			//echo "<Br>".$sql."<Br>";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$row = $stmt->fetch();
			
			//자사몰은 무조건 변경이다.
			$return[$row[lkind]]=$row[nickname];
		}
	}

	return $return;
}


function convert_pay_option_to_array_hs($ori_pn,$opt){

	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	$return = array();

	if($opt){
		$arr = explode(",", trim($opt) );
		$count = count($arr);
		for($i=0; $i<($count-1); $i++){
			$t = explode("_", $arr[$i]);   // 4475_Windows 10 Home + 한성마우스 (+139,000원)(1개)/2182_DDR4 8G PC4-17000 (+30,000원)(1개)/4432_HDD 500GB (SATA3/7200) (+55,000원)(1개)
			//앞에 hs 코드를 가져온다
			$sql = "SELECT * FROM bom_data WHERE item={$ori_pn} and pn={$t[0]}";
			//echo "<Br>".$sql."<Br>";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$row = $stmt->fetch();
			
			//자사몰은 무조건 변경이다.
			$return[$row[lkind]]=$row[pn];
		}
	}

	return $return;
}



function convert_iclub_opt_to_array($str){
	$return = array();
	/* 옵션 유형 분석

		업그레이드선택:172621_기본상품-1개[1]
		나만의색상선택/업그레이드선택:퓨어화이트/175386_기본상품-1개[1]

		업그레이드선택:177684_RAM 8G에서 16G로-1개 (+55000원)|172989_전용 반투명 키스킨 10,000원 1개|137425_쿨링패드 UC17S 18,000원 1개[1]
		업그레이드선택:175430_기본상품-1개|173023_윈10+Office 2016+MS무선마우스 350,000원 1개|162332_노트북가방 칼리(15인치) 40,000원 1개|172989_전용 반투명 키스킨 10,000원 1개|135556_고광택액정보호필름(15인치) 15,000원 1개[1]
		업그레이드선택:177684_RAM 8G에서 16G로-1개 (+55000원)|166381_노트북가방 헤라클레스(11-15겸용) 29,000원 1개|150481_USB메모리(16GB) 20,000원 1개|172994_Windows 10+한성마우스+안전설치 139,000원 1개|173001_마우스 GTune M600 게이밍 12,900원 1개[1]

		==미친거 아님 이거??
		업그레이드선택:172621_기본상품/1개 -, 액세서리:150489_노트북가방 제니스마블(15인치)/34000원/1개, 주변기기:172985_마우스 GTune M30 게이밍/29900원/1개[1]
		172591_기본상품:화이트/1개, 키보드 보호스킨:175456_댄디 화이트/10000원/1개[1]


		필수옵션 단일 케이스 (0)
		필수옵션 + | 로 구분짓는 케이스 (1)
		필수옵션 + , 로 구분짓는 케이스 (2)
	*/	
	$or_count = count(explode("|", $str));
	$comma_count = count(explode(",", $str));
	//preg_match(pattern, str, res);
	if($or_count>1){
		//케이스(1)
		$arr = explode("|", $str);
		for($i=1; $i<count($arr); $i++){ //1부터 시작함에 주의
			$a = explode("_", trim($arr[$i])); //172989_전용 반투명 키스킨 10,000원 1개	
			$price = 0;
			$qty = 1;
			//상품번호 정규 표현식
			preg_match('/[0-9]{5,7}_/', $arr[$i], $n_match);
			if($n_match[0]){
				$pn = trim(str_replace("_", "", $n_match[0]));
			}

			$name = trim($arr[$i]);
			
			//가격 정규 표현식
			preg_match('/[0-9]*,*[0-9]*,*[0-9]*,*[0-9]*원/', $arr[$i], $p_match);
			if($p_match[0]){
				$p = str_replace("원", "", str_replace(",", "", $p_match[0]));
				if(is_numeric($p)) $price = intval($p);
			}
			//수량 정규 표현식
			preg_match('/[0-9]*개/', $arr[$i], $q_match);
			if($q_match[0]){
				$q = str_replace("원", "", str_replace(",", "", $q_match[0]));
				if(is_numeric($q)) $qty = intval($q);
			}

			$return[] = array(
				"pn" => $pn,
				"name" => $name,
				"price" => $price,
				"qty" => $qty,
				"ori_name" => $arr[$i]
			);
		}
	}else{
		//케이스(0) OR 케이스(2)
		if($comma_count>1){
			//케이스(2)
			//업그레이드선택:172621_기본상품/1개 -, 액세서리:150489_노트북가방 제니스마블(15인치)/34000원/1개, 주변기기:172985_마우스 GTune M30 게이밍/29900원/1개[1]
			//172591_기본상품:화이트/1개, 키보드 보호스킨:175456_댄디 화이트/10000원/1개[1]
			$arr = explode(",", $str);
			for($i=1; $i<count($arr); $i++){
				$a = $arr[$i]; // 액세서리:150489_노트북가방 제니스마블(15인치)/34000원/1개
				$t = explode(":", trim($a));
				$a = explode("/", $t[1]); //0->150489_노트북가방 제니스마블(15인치) 1->34000원 2->1개
				
				$name = $a[0];
				$price = str_replace("원", "", trim($a[1]));
				$qty = str_replace("개", "", trim($a[2]));
				$p_arr = explode("_", $name);
				$pn = trim($p_arr[0]);

				$return[] = array(
					"pn" => $pn,
					"name" => $name,
					"price" => $price,
					"qty" => $qty,
					"ori_name" => $arr[$i]
				);
			}
		}else{
			//케이스(0)
			// 업그레이드선택:172621_기본상품-1개[1]
			// 나만의색상선택/업그레이드선택:퓨어화이트/175386_기본상품-1개[1]
			;
		}
	}
	return $return;
}

function insert_iclub_bom_data($idx, $data_idx, $master_pn, $qty, $opt, $codes){
	//옵션만 처리 한다.
	//BOM 은 실주문으로 이동시 생성한다
	$options = convert_iclub_opt_to_array($opt);

	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	$option_count = count($options);
	//if($option_count<1){
		insert_original_spec($idx, $data_idx, $master_pn, $qty);
	//	return true;
	//}

	foreach($options as $count => $option){
		$item = get_iteminfo(trim($option["pn"]), "iclub");
		if( $item["pn"] > 0 ){
			$pn = $item["pn"];
			$pname = $item["pname"];
			$ecount_code = $item["ecount_code"];
			$ecount_name = $item["ecount_name"];
			$tkind = $item["tkind"];
			$lkind = $item["lkind"];
			$spec_qty = $item["qty"]*$qty;
			$price = 0;
			if($spec_qty==0) $spec_qty = 1;
			if($item["ss_price"]>0) $price = $item["ss_price"];
		}else{
			$pn = $ecount_code = $tkind = $lkind = $price = 0;
			$spec_qty = $qty;
			$pname = $ecount_name = $option["ori_name"];
		}
		if($tkind==5 || $lkind ==51){
			if( !is_original_spec($master_pn, $lkind, $pn)){
				//오리지널 스펙이 아닌 변경된 스펙이라면 
				//1) 오리지널 스펙을 jumun_bom_imsi에 기입 하되 change_ch 에 y 값으로 입력
				//2) 현재 입력해야할 스펙도 jumun_bom_imsi에 입력
				insert_original_spec_with_change_mark($idx, $data_idx, $master_pn, $lkind, $qty); //1)
			}
			$master_iteminfo = get_master_iteminfo($master_pn);
			$stmt = $pdo->prepare("
				INSERT INTO jumun_bom_imsi 
					(
						no, item, tkind, lkind, pn, 
						nickname, price, qty, change_ch, jecount_code,
						jumundata_num, main_shoplinker_order_num, jecount_name, item_jecount_code, item_jecount_name
					)
				VALUES
					(
						?, ?, ?, ?, ?,
						?, ?, ?, ?, ?,
						?, ?, ?, ?, ?
					)
				");
			$stmt->execute([
				$idx, $master_pn, $tkind, $lkind, $pn, 
				$pname, '0', $spec_qty, 'n', $ecount_code,
				$data_idx, 'A'.$data_idx, $ecount_name, $master_iteminfo["ecount_code"], $master_iteminfo["ecount_name"]
				 //샵링커를 통하지 않은 주문의 경우 main_shoplinker_order_num 필드에 'A'.{주문데이터 인덱스 번호} 형태로 기입한다 - by 안대환 과장님
			]);
		}else{
			$stmt = $pdo->prepare("
				INSERT INTO jumundata_imsi 
					(
						no, pn, pname, qty, price, 
						jecount_code, jecount_name, add_opt_ch, market_order_num, market_sell_price
					) 
				VALUES 
					(
						?, ?, ?, ?, ?, 
						?, ?, ?, ?, ?
					)
				");
			$stmt->execute([
				$idx, $pn, $pname, $spec_qty, $price, 
				$ecount_code, $ecount_name, 1, $idx, $price
			]);
			
			$new_data_idx = $pdo->lastInsertId();
			if($new_data_idx>0){
				$tmp = $pdo->prepare("UPDATE jumundata_imsi SET shoplinker_order_num = ? WHERE num = ? ");
				$tmp->execute(['A'.$new_data_idx, $new_data_idx]);
			}
		}
	}
}

//@function (Boolean) insert_winwin_bom_data(String, String, String, String, String);
//@desc : 윈윈소프트 BOM 상품인경우 mysql->tbl->jumun_bom_imsi 에 BOM 데이터를 입력한다
//@param1 : 주문인포 IDX
//@param2 : BOM의 주인이 되는 상품 => 마스터상품 (윈윈의 상품번호가 아닌 뉴한성의 상품코드) jumun_bom_imsi 에 insert시 필요
//@param3 : master_pn 의 주문갯수
//@param4 : 상품의 스펙이 # 을 구분자로 무더기로 들어옴
//	-> 스펙1#스펙2#스펙3#... #스펙n
//@param5 : 상품의 윈위놐드가 # 과 ## 을 구분자로 무더기로 들어옴 
//	-> 마스터코드#스펙코드1##스펙코드2## ... ##스펙코드n#
//	-> 당므의 코드들은 처리 하지 않고 버림 (=$garbage_code) -> {11485 [안전제작 + 완벽포장] , 10507 한성컴퓨터}
function insert_winwin_bom_data($idx, $data_idx, $master_pn, $qty, $opt, $codes){
	$garbage_code = array("11485","10507");
	$garbage_str = array("증정");

	$spec = convert_winwin_opt_to_array($opt);
	$codes = convert_winwin_code_to_array($codes);

	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	$spec_count = count($spec);
	$code_count = count($codes);
	if($spec_count==1 || $code_count==1){
		//변경 옵션이 없는 경우 태블릿 의 경우 -> 구성되어이쓴 BOM 을 jumun_bom 에 넣어주고 나머지 사항은 건드리지 않음
		//echo "insert_original_spec()###1 <br/><br/>";
		insert_original_spec($idx, $data_idx, $master_pn, $qty);
		return true;
	}

	foreach($codes as $count => $code){
		//쓰레기 코드 값 통과 루틴
		if( in_array($code, $garbage_code) ) continue;
		$flag = false;
		for($i=0; $i<count($garbage_str); $i++){ 
			if( strpos($spec[$count], $garbage_str[$i]) !== false) $flag = true;
		}
		if($flag) continue;


		$item = get_iteminfo($code);
		$option = get_option($spec[$count]);
		if( $item["pn"] > 0 ){
			$pn = $item["pn"];
			$pname = $item["pname"];
			$ecount_code = $item["ecount_code"];
			$ecount_name = $item["ecount_name"];
			$tkind = $item["tkind"];
			$lkind = $item["lkind"];
			if($lkind=="28" || $lkind=="38"){
				//데스크탑>RAM || 노트북>RAM
				$pattern = '/[0-9]{1,2}Gx[0-9]{1,2}/';
				preg_match($pattern, $pname, $result);
				if($result[0]){
					$tmp = explode("x", $result[0]);
					$spec_qty = $qty * $tmp[1];
				}
			}else{
				$spec_qty = $qty;
			}
		}else{
			$pn = $ecount_code = $tkind = $lkind = 0;
			$spec_qty = $qty;
			$pname = $ecount_name = $spec[$count];
		}

		//@중요 : tkind가 5(=컴퓨터부품) 이 아니라면 BOM 에 포함하지 않고 jumundata_imsi 에 넣어준다
		//@tkind = 5 외에 lkind = 51 (내부용>조립관련) 파트 도 BOM 에 포함되도록 수정
		if($tkind==5 || $lkind ==51){
			if( !is_original_spec($master_pn, $lkind, $pn)){
				//오리지널 스펙이 아닌 변경된 스펙이라면 
				//1) 오리지널 스펙을 jumun_bom_imsi에 기입 하되 change_ch 에 y 값으로 입력
				//2) 현재 입력해야할 스펙도 jumun_bom_imsi에 입력
				insert_original_spec_with_change_mark($idx, $data_idx, $master_pn, $lkind, $qty); //1)
			}
			$master_iteminfo = get_master_iteminfo($master_pn);
			$stmt = $pdo->prepare("
				INSERT INTO jumun_bom_imsi 
					(
						no, item, tkind, lkind, pn, 
						nickname, price, qty, change_ch, jecount_code,
						jumundata_num, main_shoplinker_order_num, jecount_name, item_jecount_code, item_jecount_name
					)
				VALUES
					(
						?, ?, ?, ?, ?,
						?, ?, ?, ?, ?,
						?, ?, ?, ?, ?
					)
				");
			$stmt->execute([
				$idx, $master_pn, $tkind, $lkind, $pn, 
				$pname, '0', $spec_qty, 'n', $ecount_code,
				$data_idx, 'A'.$data_idx, $ecount_name, $master_iteminfo["ecount_code"], $master_iteminfo["ecount_name"]
				 //샵링커를 통하지 않은 주문의 경우 main_shoplinker_order_num 필드에 'A'.{주문데이터 인덱스 번호} 형태로 기입한다 - by 안대환 과장님
			]);
		}else{
			$stmt = $pdo->prepare("
				INSERT INTO jumundata_imsi 
					(
						no, pn, pname, qty, price,
						winwin_code, jecount_code, opt, add_opt_ch, jecount_name,
						market_sell_price
					) 
				VALUES 
					(
						?, ?, ?, ?, ?, 
						?, ?, ?, ?, ?,
						?
					)
				");
			$stmt->execute([
					$idx, $pn, $pname, $spec_qty, 0, 
					$code, $ecount_code, $option, 1, $ecount_name,
					$price
				]);
		}
	}
}
function insert_original_spec($jumuninfo_idx, $jumundata_idx, $master_pn, $qty=1){
	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	//$stmt = $pdo->prepare("SELECT ecount_code, ecount_name FROM item WHERE num = ?");
	//$stmt->execute([$master_pn]);
	//$stmt->setFetchMode(PDO::FETCH_ASSOC);
	//$master_iteminfo = $stmt->fetch();
	$master_iteminfo = get_master_iteminfo($master_pn);

	$stmt = $pdo->prepare("SELECT b.tkind, b.lkind, b.pn, b.nickname, b.price, b.qty, i.ecount_code, i.ecount_name FROM bom_data b LEFT JOIN item i ON b.pn=i.num WHERE b.item = ? AND b.chk=1");
	$stmt->execute([$master_pn]);
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	while($item = $stmt->fetch()){
		$insert = $pdo->prepare("
				INSERT INTO jumun_bom_imsi 
					(
						no, item, tkind, lkind, pn,
						nickname, price, qty, jecount_code, change_ch,
						jumundata_num, main_shoplinker_order_num, jecount_name, item_jecount_code, item_jecount_name
					)
				VALUES
					(
						?, ?, ?, ?, ?,
						?, ?, ?, ?, ?,
						?, ?, ?, ?, ?
					)
				");
		$insert->execute([
			$jumuninfo_idx, $master_pn, $item["tkind"], $item["lkind"], $item["pn"],
			$item["nickname"], 0, $qty, $item["ecount_code"], 'n',
			$jumundata_idx, 'A'.$jumundata_idx, $item["ecount_name"], $master_iteminfo["ecount_code"], $master_iteminfo["ecount_name"]
		]);
	}
}
function get_master_iteminfo($master_pn){
	$return = array();
	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	$stmt = $pdo->prepare("SELECT name,ecount_code, ecount_name FROM item WHERE num = ?");
	$stmt->execute([$master_pn]);
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	if($row){
		$return["name"] = $row["name"];
		$return["ecount_code"] = $row["ecount_code"];
		$return["ecount_name"] = $row["ecount_name"];
	}

	return $return;
}

function insert_original_spec_with_change_mark($jumuninfo_idx, $jumundata_idx, $master_pn, $lkind, $qty=1){
	$return = false;
	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";
	
	$master_iteminfo = get_master_iteminfo($master_pn);

	//하나의 lkind에 대해 스펙변경이 두번 일어날 수가 있다.
	//ex) ssd120 기가를 -> ssd250gb 로 변경 + HDD 1TB 추가 등의 경우...
	//따라서 이미 변경된적이 있는 스펙은 다시 jumun_bom에 insert하지 않는다
	$stmt = $pdo->prepare("SELECT COUNT(*) cnt FROM jumun_bom_imsi WHERE no=? AND item=? AND lkind=? AND change_ch='y'");
	$stmt->execute([$jumuninfo_idx, $master_pn, $lkind]);
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();
	$cnt = $row["cnt"];
	if($cnt < 1){
		$stmt = $pdo->prepare("SELECT b.tkind, b.lkind, b.pn, b.nickname, b.price, b.qty, i.ecount_code, i.ecount_name FROM bom_data b LEFT JOIN item i ON b.pn=i.num WHERE b.item=? AND b.lkind=?");
		$stmt->execute([$master_pn, $lkind]);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$item = $stmt->fetch();

		if($item["pn"] > 0){
			$return = true;
			$stmt = $pdo->prepare("
				INSERT INTO jumun_bom_imsi 
					(
						no, item, tkind, lkind, pn,
						nickname, price,qty, jecount_code, change_ch,
						jumundata_num, main_shoplinker_order_num, jecount_name, item_jecount_code, item_jecount_name
					)
				VALUES
					(
						?, ?, ?, ?, ?,
						?, ?, ?, ?, ?,
						?, ?, ?, ?, ?
					)
				");
			$stmt->execute([
				$jumuninfo_idx, $master_pn, $item["tkind"], $item["lkind"], $item["pn"],
				$item["nickname"], 0, $qty, $item["ecount_code"], 'y',
				$jumundata_idx, 'A'.$jumundata_idx, $item["ecount_name"], $master_iteminfo["ecount_code"], $master_iteminfo["ecount_name"]
			]);
		}
	}
	return $return;
}
//@dsec : 이 상품의 원래 중분류 스펙($lkind)이 이 상품($pn)이 맞는지 확인하여 boolean타입으로 결과 반환
function is_original_spec($master_pn, $lkind, $pn){
	$return = false;

	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";
	$stmt = $pdo->prepare("SELECT COUNT(*) cnt FROM bom_data WHERE item=? AND lkind=? AND pn=?");
	$stmt->execute([$master_pn, $lkind, $pn]);
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();
	if($row["cnt"] > 0 ){
		$return = true;
	}

	return $return;
}

//@desc : insert_winwin_bom_data()에 종속된 함수
//	스펙1#스펙2#스펙3#... #스펙n
//	형태의 문자를 받아서 #을 기준자로 분해해서 배열형태로 반환
function convert_winwin_opt_to_array($s){
	return explode("#", $s);
}
//@desc : insert_winwin_bom_data() 에 종속된 함수. (다른 프로그램에서 사용되지 않습니다)
//	마스터코드#스펙코드1##스펙코드2## ... ##스펙코드N#
//	형태의 문자를 받아서 마스터코드는 제거 하고 ## 를 기준자로 분해해서 배열형태로 반환
function convert_winwin_code_to_array($s){
	$s = explode("##", $s);
	$c = count($s);
	$a = explode("#", $s[0]);
	$s[0] = $a[1];
	$s[ $c-1 ] = str_replace("#", "", $s[ $c-1] );
	return $s;
	/*
	for($i=0; $i<$c; $i++){
		switch($s[$i]){
			case "10507":
			case "11485":
				continue; break;
			default :
				$return[$i] = $s[$i];
		}
	}
	return $return;
	*/
}

//@function (Boolean) is_bom_product(String)
//@desc : 윈윈소프트의 bom 상품인지 여부를 boolean 타입으로 반환
//@param1 : 윈윈 엑셀의 윈윈코드 리스트가 매개변수로 들어옴
//                  -> BOM상품일 경우 : 마스터상품코드#구성품1##구성품2##구성품3## ... ##구성품n# 형식으로 구성
//@param1 : 한성몰 상품번호가 매개변수로 입력
//		-> tbl->bom_data 를 조회해서 bom이 구성되어있는 상품인지 여부를 반환
function is_bom_product($str){
	$return = false;

	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	$stmt = $pdo->prepare("SELECT COUNT(*) cnt FROM bom_data WHERE item = $str");
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();
	$cnt = $row["cnt"];

	if($cnt>0){
		$return = true;
	}

	return $return;
	/*
	$return = false;
	$a = explode("##", $str);
	if( count($a) > 1 ){
		$return = true;
	}
	return $return;
	*/
}
//@function (String) get_qty(String, String, String);
//@desc : 제품의 구매수량을 반환 
//@param1 : 제품수량 - 아이클럽일 경우 존재
//@param2 : 제품명 - 윈윈일경우 상품명 에서 [수량:n개]  형식에서 추출
//@param3 : 현재 엑셀이 아이클럽인지 윈윈이지 여부
function get_qty($qty, $pname, $excel_type){
	return $return;
}

//@function : function (array) get_excel_field(String);
//@desc : 각 엑셀 타입별, 필요한 값들의 필드 순서를 관계형 배열로 반환하는 함수 / 해당하는 값이 없으면 99 번 셀로 매칭
//@param1 : $excel_type 의 종류가 무엇인지에 대한 값 
/*
	<option value="winwin">윈윈 소프트</option>
	<option value="iclub">아이클럽</option>
	<option value="pay">네이버 페이</option>
*/
//@param2 : 메모리에 로드된 @excel 포인터

function get_excel_field($assort){
	$return = array();

	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	//echo $assort;
	$sql = "SELECT * from jumun_market_excel_order where assort='".$assort."' order by excel_order asc";
	//echo $sql;
	$stmt2 = $pdo->prepare($sql);
	$stmt2->execute();
	$stmt2->setFetchMode(PDO::FETCH_ASSOC);

	while($row = $stmt2->fetch()){
		//echo $row[match_excel]."--".$row[excel_order]."<br>";
		$return[$row[match_excel]]= ($row[excel_order]-1);   // "qty"=>"17"  이런식으로 들어감
	}
	return $return;
}


//@function (array) get_iteminf(String, String, String);
//@desc : 상품명(아이클럽) 상품번호(윈윈) 을 매개변수로 받아서, 가장 유사한 상품의 정보를 조회하여 
//	    결과를 배열로 반환하는 함수. 
//	    결과가 없으면 상품번로:0, 상품명:null 을 반환
//	     - 아이클럽일 경우 $pn은 이카운트코드
//	     - 윈윈일경우 $pn은 윈윈코드
//@param1 : 상품번호 (winwin_code)
//@param2 : 엑셀타입 ( winwin or iclub );
//@param2 : 윈윈일경우 교체옵션이 존재할 수 있다. 존재한다면 상품을 교체 해준다 => 한성몰번호_옵션명 => 한성몰 번호만 있으면 OK
//@param2 : 아이클럽은 교체옵션이 존재할 수 있다. 존재한다면 상품을 교체 해준다
function get_iteminfo($goods_name, $excel_type="winwin", $add_option="",$pilsu_option=""){
	$return = array();
	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	//메인상품명으로 가져오기
	$goods_name = trim($goods_name);
	$sql = "SELECT * FROM item WHERE (name='{$goods_name}' or choicerem='{$goods_name}') and status in (2) ";  //판매중
	//echo $sql."<br>";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	if($stmt){
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$row = $stmt->fetch();
	}
	if($row["num"] > 0){  //검색이 된다면
		$return["pn"]		= $row["num"];
		$return["pname"]	= $row["name"];
		$return["ecount_code"]	= $row["ecount_code"];
		$return["ecount_name"]	= $row["ecount_name"];
		$return["tkind"]	= $row["tkind"];
		$return["lkind"]	= $row["lkind"];
		$return["ss_price"]	= $row["oprice"];
	}else{  //검색이 안된다면
		$stmt2 = $pdo->prepare("SELECT * FROM jumun_market_item_match WHERE goods_name='{$goods_name}' ");
		$stmt2->execute();
		if($stmt2){
			$stmt2->setFetchMode(PDO::FETCH_ASSOC);
			$row2 = $stmt2->fetch();
		}
		if($row2["hs_code"] > 0){  //검색이 된다면
			$stmt3 = $pdo->prepare("SELECT * FROM item WHERE num='{$row2[hs_code]}' ");
			$stmt3->execute();
			if($stmt3){
				$stmt3->setFetchMode(PDO::FETCH_ASSOC);
				$row3 = $stmt3->fetch();
				$return["pn"]		= $row3["num"];
				$return["pname"]	= $row3["name"];
				$return["ecount_code"]	= $row3["ecount_code"];
				$return["ecount_name"]	= $row3["ecount_name"];
				$return["tkind"]	= $row3["tkind"];
				$return["lkind"]	= $row3["lkind"];
				$return["ss_price"]	= $row3["oprice"];
			}
		}else{
			$return["pn"] = $return["ecount_code"] = $return["ecount_name"] = $return["tkind"] = $return["lkind"] = $return["ss_price"] = 0;
			$return["pname"] = "no data";
		}
	}	
	$stmt = $pdo = null;
	return $return;
}

//@function (boolean) is_already_exist_by_idx(String);
//@desc : 고유번호 (윈윈주문번호, 샵링커 주문번호 등) 가 이미 등록되어있는 여부를 boolean 으로 반환
//@param1 : 고유 인덱스 값
function is_already_exist_by_idx($str){
	$return = false;

	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	$stmt = $pdo->prepare("SELECT COUNT(*) cnt FROM jumuninfo_imsi WHERE jumun_idx = ? ");
	$stmt->execute([$str]);	
	
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();
	if( $row["cnt"] > 0){
		$return =  true;
	}
	return $return;
}

//@function (String) get_jumuninfoidx_by_jumunidx(String);
//@desc : 주문인덱스를 가지고 주운인포  고유키를 반환한다
//@param1 : jumun_idx 키 (샵링커 주문번호, 윈윈 주분번호 등등)
function get_jumuninfoidx_by_jumunidx($str){
	$return = 0;

	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	$stmt = $pdo->prepare("SELECT num FROM jumuninfo_imsi WHERE jumun_idx=?");
	$stmt->execute([$str]);	
	
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();
	if( $row["num"] > 0){
		$return = $row["num"];
	}

	return $return;
}

//$market_order_num 이 있는지 확인한다.
function get_jumundata_duplicate($market_order_num){

	include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	$sql = "SELECT num FROM jumundata_imsi WHERE market_order_num=$market_order_num<br>";
	echo $sql;

	$stmt = $pdo->prepare("SELECT num FROM jumundata_imsi WHERE market_order_num=?");
	$stmt->execute([$market_order_num]);	
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();
	if( $row["num"] > 0){
		$return = "y";  //이미 있음
	}else{
		$return = "n";  //이미 없음
	}

	return $return;

}

//@function (Integer) get_paymethod(String);
//@desc : 결제방법을 정수값으로 반환한다
//@param1 : 결제 방법 문자열
//	현금=>1,  카드=>2, 가상=>3, 온라인=>100 (default)
function get_paymethod($str){
	switch($str){
		case "현금": case "은행": return 1; break;
		case "카드": return 2; break;
		case "가상계좌": return 3; break;
		case "온라인": case "온라인카드": default: return 100; break;
	}
}


function get_change_iteminfo($pn=0){
	$return = array();

	if($pn>0){

		include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

		$stmt = $pdo->prepare("SELECT tkind, lkind, num, name, winwin_code, ecount_code, ecount_name FROM item WHERE num=? ");
		$stmt->execute([$pn]);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$row = $stmt->fetch();
		/*
		$return["tkind"] = $row["tkind"];
		$return["lkind"] = $row["lkind"];
		$return["num"] = $row["num"];
		$return["name"] = $row["name"];
		$return["winwin_code"] = $row["winwin_code"];
		$return["ecount_code"] = $row["ecount_code"];
		$return["ecount_name"] = $row["ecount_name"];
		*/
		$return = $row;
	}

	return $return;
}

?>