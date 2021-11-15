<?php
	include_once('../Popbill/common.php');  //디비연결
	// 세금계산서 객체 생성
	$Taxinvoice = new Taxinvoice();

	// 팝빌 연동회원 사업자번호
	$CorpNum = '2148820281';

	// 팝빌 연동회원 아이디
	$UserID = 'rndtech';

	$jumun_num	=	$_REQUEST[od_id];  //주문서 고유번호

	$tax_co_name=	$_REQUEST[tax_co_name];
	$tax_co_num	=	$_REQUEST[tax_co_num];
	$tax_ceo	=	$_REQUEST[tax_ceo];
	$tax_up		=	$_REQUEST[tax_up];
	$tax_jm		=	$_REQUEST[tax_jm];
	$tax_adm_name	=	$_REQUEST[tax_adm_name];
	$tax_addr	=	$_REQUEST[tax_addr];
	$tax_email	=	$_REQUEST[tax_email];
	$tax_tel	=	$_REQUEST[tax_tel];
	$totalp		=	$_REQUEST[totalp];
	$tt_count	=	$_REQUEST[tt_count];
	$item_name	=	$_REQUEST[item_name];

	/************************************************************
	  *                        세금계산서 정보
	************************************************************/
	// 작성일자
	$Taxinvoice->writeDate = date("Ymd");
	// 발행형태
	$Taxinvoice->issueType = '정발행';
	// 과금방향
	$Taxinvoice->chargeDirection = '정과금';
	// 영수 or 청구
	$Taxinvoice->purposeType = '영수';
	// 발행시점
	$Taxinvoice->issueTiming = '직접발행';
	// 과세형태
	$Taxinvoice->taxType = '과세';

	// 공급가액 합계
	$Taxinvoice->supplyCostTotal = intval($totalp/1.1);

	// 세액 합계
	$Taxinvoice->taxTotal = intval($totalp) - intval($totalp/1.1);

	// 합계금액
	$Taxinvoice->totalAmount = $totalp;

	// 기재상 '외상'항목
	$Taxinvoice->remark1 = '';
	 // 즉시발행 메모
	$memo = '즉시발행 메모';

	$doc_num = date("Ymd")."-".date("His");

	/************************************************************
	  *                         공급자 정보
	  ************************************************************/
	// 공급자 사업자번호
	$Taxinvoice->invoicerCorpNum = '2148820281';
	// 공급자 상호
	$Taxinvoice->invoicerCorpName = '주)알앤디테크';
	// 공급자 문서관리번호
	$Taxinvoice->invoicerMgtKey = $doc_num;
	// 공급자 대표자성명
	$Taxinvoice->invoicerCEOName = '강무성';
	// 공급자 주소
	$Taxinvoice->invoicerAddr = '서울시 송파구 법원로 128 SK V1 GL메트로씨티 C동 1010호';
	// 공급자 종목
	$Taxinvoice->invoicerBizClass = '제조,서비스';
	// 공급자 업태
	$Taxinvoice->invoicerBizType = '소프트웨어개발및공급외';
	// 공급자 담당자 성명
	$Taxinvoice->invoicerContactName = '강무성';
	// 공급자 담당자 메일주소
	$Taxinvoice->invoicerEmail = 'monica76.lee@gmail.com';
	// 공급받는자 담당자에게 알림문자 전송여부
	$Taxinvoice->invoicerSMSSendYN = false;

	/************************************************************
	  *                      공급받는자 정보
	  ************************************************************/

	// 공급받는자 구분
	$Taxinvoice->invoiceeType = '사업자';
	// 공급받는자 사업자번호
	$Taxinvoice->invoiceeCorpNum = str_replace("-","",$tax_co_num);
	// 공급자 상호
	$Taxinvoice->invoiceeCorpName = $tax_co_name;
	// 공급받는자 대표자성명
	$Taxinvoice->invoiceeCEOName = $tax_ceo;
	// 공급받는자 주소
	$Taxinvoice->invoiceeAddr = $tax_addr;
	// 공급받는자 업태
	$Taxinvoice->invoiceeBizType = $tax_up;
	// 공급받는자 종목
	$Taxinvoice->invoiceeBizClass = $tax_jm;
	// 공급받는자 담당자 성명
	$Taxinvoice->invoiceeContactName1 = $tax_adm_name;
	// 공급받는자 담당자 메일주소
	$Taxinvoice->invoiceeEmail1 = $tax_email;


	/************************************************************
	*                       상세항목(품목) 정보
	************************************************************/

	$Taxinvoice->detailList = array();

	$Taxinvoice->detailList[] = new TaxinvoiceDetail();
	$Taxinvoice->detailList[0]->serialNum = 1;					// [상세항목 배열이 있는 경우 필수] 일련번호 1~99까지 순차기재,
	$Taxinvoice->detailList[0]->purchaseDT = date("Ymd");		// 거래일자
	$Taxinvoice->detailList[0]->itemName = $item_name;			// 품명 (프로젝트명)
	$Taxinvoice->detailList[0]->spec = '';						// 규격
	$Taxinvoice->detailList[0]->qty = $tt_count;				// 수량
	$Taxinvoice->detailList[0]->unitCost = '';					// 단가
	$Taxinvoice->detailList[0]->supplyCost = intval($totalp/1.1);	// 공급가액
	$Taxinvoice->detailList[0]->tax = (intval($totalp) - intval($totalp/1.1));	// 세액
	$Taxinvoice->detailList[0]->remark = '';					// 비고


	try {
		//즉시발행
		$result = $TaxinvoiceService->RegistIssue($CorpNum, $Taxinvoice, $UserID,false, false, $memo, '', '');
		$code = $result->code;  //1이면 정상발행
		$message = $result->message;

		//$sql = "update g5_shop_order set segum_date='{$doc_num}' where od_id = '$od_id' ";
		//db_query($sql);

	}
	catch(PopbillException $pe) {
		$code = $pe->getCode();
		$message = $pe->getMessage();
	}

	echo $code."---".$message."<br>";
	$return_arr = array("code" => $code,"message"=>$od_id."/".$message);
	//echo urldecode(json_encode($return_arr));
?>
