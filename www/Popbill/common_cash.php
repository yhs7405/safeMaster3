<?php

include_once('../Popbill/PopbillCashbill.php');

// 링크허브에서 발급받은 인증정보 링크아이디(LinkID), 비밀키(SecretKey)를 입력합니다.

// 인증정보는 파트너 식별 및 API 통신전문 변조를 방지하는 기능을 수행하므로
// 외부에 유출되지 않도록 각별히 유의하여 관리해 주시기 바랍니다.
$LinkID = 'TAEYUL';
$SecretKey = 'dpEgZCue/e7vr62ZgfhXQpakMOX3lZ8dV6E55qJNp14=';

// 통신방식 설정. 기본값 - CURL
define('LINKHUB_COMM_MODE','CURL');

// 현금영수증 클래스 생성
$CashbillService = new CashbillService($LinkID, $SecretKey);

// true - 개발용(테스트베드), false - 상업용(실서비스)
$CashbillService->IsTest(true); 

?> 
