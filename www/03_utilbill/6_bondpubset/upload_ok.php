<?
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");
	include ("../../include/excel.inc");

	$tablename = "tbl_junib ";  //등기이전
//	$tablename_upload = "tbl_member_upload";  //근저당설정

	$h_idx	= $_REQUEST["h_idx"];
	$job_id      = $_SESSION["admin_id"];
	$job_date = date("YmdHis");

	//0. 사용할 변수 초기화
	$a_yn = "y";
	$a_cnt = 0;
	$order_group = 1;
	$excel_type = $file_name = "";
	$file_dest = $_SERVER["DOCUMENT_ROOT"]."/tmp/";

	//0. FORM  전송값 받기

	if($_FILES["excel_file"]["name"]) $file_name = $_FILES["excel_file"]["name"];
	//echo $file_name;
	//$ext1 = explode(".", strtolower($file_name));
	//$ext = $ext1[1];
	$ext = f_ext($file_name);
	//echo $file_name;
	if( $file_name == "" ){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('업로드파일을 선택해 주세요.');location.href='upload.php';</script>";
		exit;
	} else if( $ext == "xls" ){
		$file_name = "imsi_".time().".".$ext;
		if( !move_uploaded_file($_FILES["excel_file"]["tmp_name"], $file_dest.$file_name) ){
			echo "파일을 업로드 하는 도중 오류가 발생하였습니다 [CODE : 001]";
			exit;
		}
	} else {
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('엑셀파일 .xls 파일만 업로드가 가능합니다.');location.href='upload.php';</script>";
		exit;
	}

$filename = "../../tmp/".$file_name;


$objPHPExcel = new PHPExcel();

  // 업로드 된 엑셀 형식에 맞는 Reader객체를 만든다.
    $objReader = PHPExcel_IOFactory::createReaderForFile($filename);
    // 읽기전용으로 설정
    $objReader->setReadDataOnly(true);
    // 엑셀파일을 읽는다
    $objExcel = $objReader->load($filename);
    // 첫번째 시트를 선택
    $objExcel->setActiveSheetIndex(0);
    $objWorksheet = $objExcel->getActiveSheet();
    $rowIterator = $objWorksheet->getRowIterator();
    foreach ($rowIterator as $row) { // 모든 행에 대해서
               $cellIterator = $row->getCellIterator();
               $cellIterator->setIterateOnlyExistingCells(false); 
    }

    $maxRow = $objWorksheet->getHighestRow();
    

try {

$error_list = "";

	for ($x = 0 ; $x <= $maxRow ; $x++) {
		$KEY = array();

		$a1 = trim($objWorksheet->getCell("C".$x)->getValue());
		$gubun = trim($objWorksheet->getCell("D".$x)->getValue());
		//취득자1
		if ($gubun=="1") {
		$aw1 = trim($objWorksheet->getCell("E".$x)->getValue());
		$aw1_jumin = trim($objWorksheet->getCell("F".$x)->getValue());
		$ba1 = f_de_comma(trim($objWorksheet->getCell("G".$x)->getValue()));
		$z1 = trim($objWorksheet->getCell("H".$x)->getValue());
		$ba1_cost = f_de_comma(trim($objWorksheet->getCell("I".$x)->getValue()));
		$ba1_date = f_de_date(PHPExcel_Style_NumberFormat::toFormattedString($objWorksheet->getCell("B".$x)->getValue(), 'YYYYMMDD'));
		} else if ($gubun=="2") {
		$aw2 = trim($objWorksheet->getCell("E".$x)->getValue());
		$aw2_jumin = trim($objWorksheet->getCell("F".$x)->getValue());
		$bb1 = f_de_comma(trim($objWorksheet->getCell("G".$x)->getValue()));
		$aa1 = trim($objWorksheet->getCell("H".$x)->getValue());
		$bb1_cost = f_de_comma(trim($objWorksheet->getCell("I".$x)->getValue()));
		$bb1_date = f_de_date(PHPExcel_Style_NumberFormat::toFormattedString($objWorksheet->getCell("B".$x)->getValue(), 'YYYYMMDD'));
		} else if ($gubun=="3") {
		$aw3 = trim($objWorksheet->getCell("E".$x)->getValue());
		$aw3_jumin = trim($objWorksheet->getCell("F".$x)->getValue());
		$bc1 = f_de_comma(trim($objWorksheet->getCell("G".$x)->getValue()));
		$ab1 = trim($objWorksheet->getCell("H".$x)->getValue());
		$bc1_cost = f_de_comma(trim($objWorksheet->getCell("I".$x)->getValue()));
		$bc1_date = f_de_date(PHPExcel_Style_NumberFormat::toFormattedString($objWorksheet->getCell("B".$x)->getValue(), 'YYYYMMDD'));
		} else if ($gubun=="4") {
		$aw4 = trim($objWorksheet->getCell("E".$x)->getValue());
		$aw4_jumin = trim($objWorksheet->getCell("F".$x)->getValue());
		$bd1 = f_de_comma(trim($objWorksheet->getCell("G".$x)->getValue()));
		$ac1 = trim($objWorksheet->getCell("H".$x)->getValue());
		$bd1_cost = f_de_comma(trim($objWorksheet->getCell("I".$x)->getValue()));
		$bd1_date = f_de_date(PHPExcel_Style_NumberFormat::toFormattedString($objWorksheet->getCell("B".$x)->getValue(), 'YYYYMMDD'));
		}

		if($a1=="") continue;
//echo $vir_acc_date."<br>";
		//echo $a1."/".$vir_acc_no."<br>";
		//		print_r($vir_acc_no);

		if($x>1){
			if($a1!="고객고유번호"){
				if($a1!=""){  //고객고유번호가 공백이 아닌것
					$sql = "select * from tbl_junib where a1='{$a1}' ";
					//echo $sql;
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					$row = $stmt->fetch();
					if($row["a1"]!="") {
						if($row["h_idx"]==$h_idx) {
							
							if($row["aw1"]==$aw1&& $row["aw1_jumin"]==$aw1_jumin&& $row["ba1"]==$ba1&& $gubun=="1") {
								if($row["z1"]==""|| $row["ba1_cost"]==""|| $row["ba1_date"]=="") {
									$error_list.= "{$x}열 / {$a1} / 해당 고객고유번호의 데이터가 존재합니다.(에러사유:이미 데이터존재)<br>";
									echo "{$x}열 / {$a1} / 해당 고객고유번호의 데이터가 존재합니다.(에러사유:이미 데이터존재)<br>";
								} else {
									$KEY["a1"] = $a1;
									$KEY["z1"] = $z1;
									$KEY["ba1_cost"] = $ba1_cost;
									$KEY["ba1_date"] = $ba1_date;

									$updatewhere = " WHERE a1 = '{$KEY[a1]}' ";
									$idx = db_replace($KEY,$tablename,$updatewhere,"a1");
									$a_cnt++;
								}
	//							$error_list.= "{$x}열 / {$a1} / 해당 고객고유번호의 가상계좌번호가 존재합니다.<br>";
	//							echo "{$x}열 / {$a1} / 해당 고객고유번호의 가상계좌번호가 존재합니다.<br>";
							} else if($row["aw2"]==$aw1&& $row["aw2_jumin"]==$aw1_jumin&& $row["bb1"]==$ba1&& $gubun=="2") {
								if($row["aa1"]==""|| $row["bb1_cost"]==""|| $row["bb1_date"]=="") {
									$error_list.= "{$x}열 / {$a1} / 해당 고객고유번호의 데이터가 존재합니다.(에러사유:이미 데이터존재)<br>";
									echo "{$x}열 / {$a1} / 해당 고객고유번호의 데이터가 존재합니다.(에러사유:이미 데이터존재)<br>";
								} else {
									$KEY["a1"] = $a1;
									$KEY["aa1"] = $aa1;
									$KEY["bb1_cost"] = $bb1_cost;
									$KEY["bb1_date"] = $bb1_date;

									$updatewhere = " WHERE a1 = '{$KEY[a1]}' ";
									$idx = db_replace($KEY,$tablename,$updatewhere,"a1");
									$a_cnt++;
								}
							} else if($row["aw3"]==$aw1&& $row["aw3_jumin"]==$aw1_jumin&& $row["bc1"]==$ba1&& $gubun=="3") {
								if($row["ab1"]==""|| $row["bc1_cost"]==""|| $row["bc1_date"]=="") {
									$error_list.= "{$x}열 / {$a1} / 해당 고객고유번호의 데이터가 존재합니다.(에러사유:이미 데이터존재)<br>";
									echo "{$x}열 / {$a1} / 해당 고객고유번호의 데이터가 존재합니다.(에러사유:이미 데이터존재)<br>";
								} else {
									$KEY["a1"] = $a1;
									$KEY["ab1"] = $ab1;
									$KEY["bc1_cost"] = $bc1_cost;
									$KEY["bc1_date"] = $bc1_date;

									$updatewhere = " WHERE a1 = '{$KEY[a1]}' ";
									$idx = db_replace($KEY,$tablename,$updatewhere,"a1");
									$a_cnt++;
								}
							} else if($row["aw4"]==$aw1&& $row["aw4_jumin"]==$aw1_jumin&& $row["bd1"]==$ba1&& $gubun=="4") {
								if($row["ac1"]==""|| $row["bd1_cost"]==""|| $row["bd1_date"]=="") {
									$error_list.= "{$x}열 / {$a1} / 해당 고객고유번호의 데이터가 존재합니다.(에러사유:이미 데이터존재)<br>";
									echo "{$x}열 / {$a1} / 해당 고객고유번호의 데이터가 존재합니다.(에러사유:이미 데이터존재)<br>";
								} else {
									$KEY["a1"] = $a1;
									$KEY["ac1"] = $ac1;
									$KEY["bd1_cost"] = $bd1_cost;
									$KEY["bd1_date"] = $bd1_date;

									$updatewhere = " WHERE a1 = '{$KEY[a1]}' ";
									$idx = db_replace($KEY,$tablename,$updatewhere,"a1");
									$a_cnt++;
								}
							} else {
								$error_list.= "{$x}열 / {$a1} / 필수항목이 상이합니다.(에러사유:동일성정보 불일치)<br>";
								echo "{$x}열 / {$a1} / 필수항목이 상이합니다.(에러사유:동일성정보 불일치)<br>";
							}
						} else {
							$error_list.= "{$x}열 / {$a1} / 입력하고자하는 현장정보가 상이 합니다.<br>";
							echo "{$x}열 / {$a1} / 입력하고자하는 현장정보가 상이 합니다.<br>";
						}
					} else {
						
						$error_list.= "{$x}열 / {$a1} / 존재하지 않는 고객고유번호 입니다.<br>";
						echo "{$x}열 / {$a1} / 존재하지 않는 고객고유번호 입니다.<br>";
					}
				}else{
					//echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
					//echo "<script>alert('{$x}열 / {$a1} / {$a1_x} 고객고유번호(A)와  동(H)호(I)가 상이합니다.');location.href='index.html';</script>";
					//$err++;
				}

				//print_r($KEY);
				//echo "<br>";
			}
		}
	}

	$imsi= "<script>f_kk();location.href='upload.php';</script>";
}



catch(Exception $e) {
//echo "<script>alert('test2');</script>";
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('엑셀파일 .xls 파일만 업로드가 가능합니다.');location.href='upload.php';</script>";
}

?>
<html>
<body>
	<form name="ffm" id="ffm" method=post>
		<input type="hidden" name="contents" value="<?=$error_list?>">
		<input type="hidden" name="cnt" value="<?=$a_cnt?>">
	</form>
</body>
</html>

<script>
function f_kk(){
			var frm    = document.ffm;
			var url    ="./popup_form.html";
			var title  = "listpop2";
			var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=880, height=800, top=0,left=20"; 
			var aa1 = window.open("", title,status);
			frm.target = title;
			frm.action = url;
			frm.method = "post";
			frm.submit();
//			aa1.focus();
}
</script>
<?=$imsi?>