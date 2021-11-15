<?php 
include "$_SERVER[DOCUMENT_ROOT]/inc/header.inc"; 
include "$_SERVER[DOCUMENT_ROOT]/inc/random_generate.inc"; 
include $_SERVER["DOCUMENT_ROOT"]."/inc/pdo.inc";

	f_admin_log($_SESSION["ADM_ID"],$_SESSION["ADM_NAME"],"통합주문업로드");
?>

<div id="wrapper">
	<div id="desc">
		<h5 class="title">통합주문 업로드</h5>
		<p><strong>각종사이트의</strong> 등의 주문데이터를 한성몰에 업로드 합니다</p>
		<p>업로드 된 데이터는 실제 주문데이터에 입력되지 않고, 임시 테이블에 저장하게 됩니다.</p>
		<p>하나의 엑셀행이 하나의 상품을 담고 있으며, 주문번호를 기준으로 묶음 처리 됩니다</p>
		<p>통합업로드의 상품 구분은 상품명으로 구분됩니다.</p>
				<p><b  style='color:red;'>* 엑셀파일이 잘 안되면 .csv형태로 저장한후에 엑셀에서 불러온 후 다시 .xls로 형태로 저장해서 업로드해 보세요.</b></p>

		<p>
			<table class="example">
				<thead>
					<tr>
						<th>마켓명<span class="required">*</span></th>
						<th>주문번호<span class="required">*</span></th>
						<th>주문일자</th>
						<th>결제일자</th>
						<th>구매자명<span class="required">*</span></th>
						<th>구매자휴대폰</th>
						<th>구매자전화번호</th>
						<th>상품번호(마켓코드)</th>
						<th>상품명<span class="required">*</span></th>
						<th>수량<span class="required">*</span></th>
						<th>필수옵션</th>
						<th>추가구성(bom변경)</th>
						<th>판매단가</th>
						<th>판매금액<span class="required">*</span></th>
						<th>정산예정</th>
						<th>수령인명<span class="required">*</span></th>
						<th>수령인휴대폰</th>
						<th>수령인전화번호</th>
						<th>우편번호<span class="required">*</span></th>
						<th>주소<span class="required">*</span></th>
						<th>배송시요구사항</th>
						<th>배송비구분</th>
						<th>배송비금액</th>
						<th>배송유형</th>
						<th>hs코드</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>위메프</td>
						<td>12564</td>
						<td>20160106</td>
						<td>20160106</td>
						<td>홍길동</td>
						<td>010-3584-1234</td>
						<td>02-5614-1534</td>
						<td>12542</td>
						<td>XH560</td>
						<td>1</td>
						<td>필수옵션</td>
						<td>옵션명1(1개)^옵션명2(1개)</td>
						<th>1500</th>
						<th>1500</th>
						<th></th>
						<th>홍길동</th>
						<th>010-1520-1234</th>
						<th>02-1520-1234</th>
						<th>0546</th>
						<th>경기도 성남시 XXX</th>
						<th>경비실</th>
						<th>선불</th>
						<th>2500</th>
						<th>택배</th>
						<th>hs코드</th>
					</tr>
				</tbody>
			</table>
		</p>
		<p>하나의 엑셀행이 하나의 상품을 담고 있으며, 주문번호를 기준으로 묶음으로 처리됩니다</p>
		<p></p>
	</div>

	<div class="block">
		<form name="upload_form" method="post" enctype="multipart/form-data" action="upload_proc.php">
			<input type="hidden" name="token" value="<?=$token?>" />
			<div class="row">
				<input type=button id="button_1" class="btn" style="background-color:#B7F0B1;" value="마켓등록관리">
			</div>
			<div class="row">
				<select name="order_group">
					<option value="1" selected="selected">1회차</option>
					<option value="2">2회차</option>
					<option value="3">3회차</option>
					<option value="4">4회차</option>
					<option value="5">5회차</option>
					<option value="6">6회차</option>
					<option value="7">7회차</option>
					<option value="8">8회차</option>
					<option value="9">9회차</option>
					<option value="10">10회차</option>
				</select>
				<select name="excel_type">
					<?$stmt2 = $pdo->prepare("SELECT * from jumun_market order by assort_name asc");
					$stmt2->execute();
					$stmt2->setFetchMode(PDO::FETCH_ASSOC);
					while($row = $stmt2->fetch()){?>
					<option value="<?=$row[assort]?>"><?=$row[assort_name]?></option>
					<?}?>
				</select>
				<input type="file" name="excel_file" />
				<button id="submit" class="btn">업로드</button>
				<p><b  style='color:red;'>* 엑셀파일이 잘 안되면 .csv형태로 저장한후에 엑셀에서 불러온 후 다시 .xls로 형태로 저장해서 업로드해 보세요.</b></p>
				<p><b  style='color:red;'>* 신규상품을 최초 매칭한 경우 이벤트상품 적용이 안되니 매칭작업을 다 한후에 주문을 지우고 다시 "통합주문 업로드" 하세요.</b></p>
			</div>
		</form>
	</div>
</div>

<script>
$(document).ready(function(){
	$("#submit").click(function(e){
		var file = $("input[name='excel_file']").val();
		var arr = file.split("\\");
		var file_name = arr[ (arr.length-1) ];
		var regexp = /[a-z|ㄱ-ㅎ|ㅏ-ㅣ|가-힣|0-9]*.xls$/i;
		if ( !regexp.test( file_name ) ){
			//확장자가 xls 파일이 아니라면 SUBMIT 중지
			alert("업로드 할 수 있는 파일은 .엑셀파일(확장자 xls)만 가능합니다");
			e.preventDefault();
		}else{
			//97-2003 호환버전의 엑셀파일이라면 SUBMIT
			$("form[name='upload_form']").submit();
		}
	});
	$("#button_1").click(function(e){  //마켓등록관리
		var aa = window.open("market_manage.php","aa100","width=700,height=700,scrollbars=yes");
		aa.focus();
	});
});
</script>
<?php include "$_SERVER[DOCUMENT_ROOT]/inc/footer.inc";?>