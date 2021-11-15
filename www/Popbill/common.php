<?php

include_once('../Popbill/PopbillTaxinvoice.php');

// 링크허브에서 발급받은 인증정보 링크아이디(LinkID), 비밀키(SecretKey)를 입력합니다.

// 인증정보는 파트너 식별 및 API 통신전문 변조를 방지하는 기능을 수행하므로
// 외부에 유출되지 않도록 각별히 유의하여 관리해 주시기 바랍니다.
$LinkID = 'RNDTECH';
$SecretKey = 'ZVi0RI2NaznE2E4aaU3Af143w0AMm3V/ki0Hq07Tgyo=';

// 통신방식 설정. 기본값 - CURL
define('LINKHUB_COMM_MODE','CURL');

// 세금계산서 클래스 생성
$TaxinvoiceService = new TaxinvoiceService($LinkID, $SecretKey);

// true - 개발용(테스트베드), false - 상업용(실서비스)
$TaxinvoiceService->IsTest(true); 
?> 
