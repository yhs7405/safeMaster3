<?php
	include_once('../Popbill/common_cash.php');  //디비연결


$name = $row[j1];
$tel  = $row[p1];
$tt  = $row[aq1]+$row[ar1]+$row[as1]+$row[at1];

$tt_1  = $row[aq1]+$row[as1];  //본가격
$tt_v  = $row[ar1]+$row[at1];  //vat


// 팝빌 회원 사업자번호, '-' 제외 10자리
$CorpNum = '2348109369';

// 문서관리번호, 사업자별로 중복없이 1~24자리 영문, 숫자, '-', '_' 조합으로 구성
$mgtKey = date("Ymd")."-".$row[idx];

// 현금영수증 객체 생성
$Cashbill = new Cashbill();

// [필수] 현금영수증 문서관리번호,
$Cashbill->mgtKey = $mgtKey;

// [취소 현금영수증 발행시 필수] 원본 현금영수증 국세청승인번호
// 국세청승인번호는 GetInfo API의 ConfirmNum 항목으로 확인할 수 있습니다.
$Cashbill->orgConfirmNum = '';

// [취소 현금영수증 발행시 필수] 원본 현금영수증 거래일자
// 현금영수증 거래일자는 GetInfo API의 TradeDate 항목으로 확인할 수 있습니다.
$Cashbill->orgTradeDate = '';

// [필수] 문서형태, (승인거래, 취소거래) 중 기재
$Cashbill->tradeType = '승인거래';

// [필수] 거래구분, (소득공제용, 지출증빙용) 중 기재
$Cashbill->tradeUsage = '소득공제용';

// [필수] 거래유형, (일반, 도서공연, 대중교통) 중 기재
$Cashbill->tradeOpt = '일반';

// [필수] 과세형태, (과세, 비과세) 중 기재
$Cashbill->taxationType = '과세';

// [필수] 거래금액, ','콤마 불가 숫자만 가능
$Cashbill->totalAmount = $tt;

// [필수] 공급가액, ','콤마 불가 숫자만 가능
$Cashbill->supplyCost = intval($tt/1.1);

// [필수] 부가세, ','콤마 불가 숫자만 가능
$Cashbill->tax = $tt-intval($tt/1.1);

// [필수] 봉사료, ','콤마 불가 숫자만 가능
$Cashbill->serviceFee = '0';

// [필수] 가맹점 사업자번호
$Cashbill->franchiseCorpNum = $CorpNum;

// 가맹점 상호
$Cashbill->franchiseCorpName = '법무사법인 태율(TAEYUL)';

// 가맹점 대표자 성명
$Cashbill->franchiseCEOName = '성미애';

// 가맹점 주소
$Cashbill->franchiseAddr = '서울 도봉구 해등로 123, 301,302호(창동)';

// 가맹점 전화번호
$Cashbill->franchiseTEL = '02-955-3348';

// [필수] 식별번호, 거래구분에 따라 작성
// 소득공제용 - 주민등록/휴대폰/카드번호 기재가능
// 지출증빙용 - 사업자번호/주민등록/휴대폰/카드번호 기재가능
$Cashbill->identityNum = str_replace("-","",$tel);

// 주문자명
$Cashbill->customerName = $name;

// 주문상품명
$Cashbill->itemName = '소유권이전보수료';

// 주문주문번호
$Cashbill->orderNumber = $tel;

// 주문자 이메일
$Cashbill->email = "";

// 주문자 휴대폰
$Cashbill->hp = $tel;

// 발행시 알림문자 전송여부
$Cashbill->smssendYN = false;

// 메모
$memo = '';

//print_r($Cashbill);

try {
    $result = $CashbillService->RegistIssue($CorpNum, $Cashbill, $memo);
    $code = $result->code;
    $message = $result->message;
} catch (PopbillException $pe) {
    $code = $pe->getCode();
    $message = $pe->getMessage();
}

if($code==1){  //성공일때만처리
	$sql = "update tbl_junib set hy_b_date='{$datex}' where a1 = '{$row[a1]}'";  //현금영수증 발행일 갱신
	//echo $sql;
	db_query($sql);

	$return_arr = array("code" => $code,"message"=>$od_id."/".$message);
	//echo urldecode(json_encode($return_arr));	
}else{//실패 메시지
	echo $row[a1]."--".$code."---".$message."<br>";
	$return_arr = array("code" => $code,"message"=>$od_id."/".$message);
	//echo urldecode(json_encode($return_arr))."<br>";	
}
?>