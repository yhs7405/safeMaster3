<?
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");
	include ("../include/excel.inc");

	$tablename = "tbl_junib";  //등기이전
	$tablename_suljung = "tbl_suljung";  //근저당설정

	$h_idx	= $_REQUEST["h_idx"];

	//0. 사용할 변수 초기화
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
	if( $ext == "xls" ){
		$file_name = "imsi_".time().".".$ext;
		if( !move_uploaded_file($_FILES["excel_file"]["tmp_name"], $file_dest.$file_name) ){
			echo "파일을 업로드 하는 도중 오류가 발생하였습니다 [CODE : 001]";
			exit;
		}
	}else{
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('엑셀파일 .xls 파일만 업로드가 가능합니다.');location.href='index.html';</script>";
		exit;
	}

	if($h_idx==""){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('현장을 선택하시고 업로드가 가능합니다.');location.href='index.html';</script>";
		exit;
	}

$filename = "../tmp/".$file_name;


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

	$sql = "select * from tbl_hyunjang_info where h_idx='{$h_idx}'";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();

	$no_text = $row[no_text];  //현장단축명
	$err = 0;

try {

$error_list = "";

for ($x = 0 ; $x <= $maxRow ; $x++) {
	$KEY = array();

	$a1 = trim($objWorksheet->getCell("A".$x)->getValue());
	//$g1 = trim($objWorksheet->getCell("G".$x)->getValue());
	$g1  = PHPExcel_Style_NumberFormat::toFormattedString($objWorksheet->getCell("G".$x)->getValue(), 'YYYYMMDD'); //등기접수일

	$h1 = str_replace("동","",trim($objWorksheet->getCell("H".$x)->getValue()));  //동
	$i1 = str_replace("호","",trim($objWorksheet->getCell("I".$x)->getValue()));  //호
	
	if($a1=="") continue;

	//echo $a1."<br>";

	if($x>4){
			if($a1!="고객고유번호"){
				if($a1!=""){  //고객고유번호가 공백이 아닌것
					if($g1!=""){  //등기접수일이 있는것

						if(strlen($g1)!=8){
							$error_list.= "{$x}열 {$g1} <font color=red>등기접수일이 날짜형식이 아닙니다.</font><br>";
							continue;
						}

						$a1_x = $no_text.$h1."동".$i1."호";  //현장명동호
						//echo $a1."/".$a1_x."<br>";
						if($a1==$a1_x && $h1!="")  {  //정상적일때

							for ($i = 1 ; $i <= 26 ; $i++) {
								$KEY[strtolower(f_xx($i))."1"] = $objWorksheet->getCell(f_xx($i) . $x)->getValue();
								//echo strtolower(f_xx($i))."-".$KEY[strtolower(f_xx($i))]."<br>";
							}
							for ($i = 1 ; $i <= 26 ; $i++) {
								$KEY[strtolower("A".f_xx($i))."1"] = $objWorksheet->getCell("A".f_xx($i) . $x)->getValue();
								//echo strtolower(f_xx($i))."-".$KEY[strtolower(f_xx($i))]."<br>";
							}

							//echo "{$KEY["a1"]} / {$KEY["aw1"]}=={$KEY["j1"]}-0<br>";

							$b1 = trim($objWorksheet->getCell("B".$x)->getValue());  //단지
							$b1x = "";
							if($b1==""){
								$b1x = "1단지";
							}else{
								$b1x = $b1."단지";
							}
							$KEY["b1"] = $b1x;

							//$error_list.= "{$x}열 / {$a1} / {$b1}  / {$KEY[b1]} <br>";

							$KEY["h_idx"] = $h_idx;
							$KEY["ba1"] = $objWorksheet->getCell("BA".$x)->getValue();
							$KEY["bb1"] = $objWorksheet->getCell("BB".$x)->getValue();
							$KEY["bc1"] = $objWorksheet->getCell("BC".$x)->getValue();
							$KEY["bd1"] = $objWorksheet->getCell("BD".$x)->getValue();

							$KEY["z1"] = $objWorksheet->getCell("Z".$x)->getValue();
							$KEY["aa1"] = $objWorksheet->getCell("AA".$x)->getValue();
							$KEY["ab1"] = $objWorksheet->getCell("AB".$x)->getValue();
							$KEY["ac1"] = $objWorksheet->getCell("AC".$x)->getValue();
							
							//echo $objWorksheet->getCell("G1".$x)->getValue()."<br>";
							$KEY["g1"]  = PHPExcel_Style_NumberFormat::toFormattedString($objWorksheet->getCell("G".$x)->getValue(), 'YYYYMMDD'); //등기접수일
							$KEY["q1"]  = PHPExcel_Style_NumberFormat::toFormattedString($objWorksheet->getCell("Q".$x)->getValue(), 'YYYYMMDD');
							$KEY["r1"]  = PHPExcel_Style_NumberFormat::toFormattedString($objWorksheet->getCell("R".$x)->getValue(), 'YYYYMMDD');
							$KEY["s1"]  = PHPExcel_Style_NumberFormat::toFormattedString($objWorksheet->getCell("S".$x)->getValue(), 'YYYYMMDD');
							$KEY["ah1"]  = f_de_date(PHPExcel_Style_NumberFormat::toFormattedString($objWorksheet->getCell("AH".$x)->getValue(), 'YYYYMMDDHI'));


							$KEY["d1"] = f_bank_name_code($KEY["d1"]);				//은행명->은행코드 반환
							$KEY["e1"] = f_jijum_name_code($KEY["d1"],$KEY["e1"]);			//은행코드,지점명->지점코드 반환
							
							// 20190613 지점정보 Null 처리
							if((strlen($KEY["d1"])!=0)  && (strlen($KEY["e1"])==0) ){
								$error_list.= "{$x}열 {$g1} <font color=red>등록되지 않은 지점정보 입니다.</font><br>";
								continue;
							}
							$KEY["cg_daesang"]  = f_cg_daesang($KEY["e1"],$KEY["g1"],$h_idx);	// "Y";  //1차청구대상필드 - 지점 / 등기접수일 / 현장명

							$KEY["total_pay"]  = intval($KEY["aj1"])+intval($KEY["ak1"])+intval($KEY["al1"])+intval($KEY["am1"])+intval($KEY["an1"])+intval($KEY["ao1"])+intval($KEY["ap1"])+intval($KEY["aq1"])+intval($KEY["ar1"])+intval($KEY["as1"])+intval($KEY["at1"]);  //지급금합계

							//$KEY["refund_fee"]  = intval($KEY["ai1"]) -$KEY["total_pay"] ; //환불금 = 입금액 - 지급금합계
							$imsi_refund  = intval($KEY["ai1"]) -$KEY["total_pay"] ; //환불금 = 입금액 - 지급금합계
							$imsi_refund = floor( $imsi_refund / 10 ) * 10;  //1단위 절삭
							$KEY["refund_fee"] = $imsi_refund;  //1단위 절삭


							if($KEY["refund_fee"]<0){  //0보다 크면 환불해줘야함
								$KEY["chuga_ibgum_daesang"]  = "Y"; //추가입금대상여부 - 대상  Y로
							}else{//0보다 작으면 돈을 받아야함.
								$KEY["chuga_ibgum_daesang"]  = ""; //추가입금대상여부 -X
							}

							
							$KEY["aw1_jumin"] = "";
							$KEY["aw2"] = "";
							$KEY["aw2_jumin"] = "";
							$KEY["aw3"] = "";
							$KEY["aw3_jumin"] = "";
							$KEY["aw4"] = "";
							$KEY["aw4_jumin"] = "";

							//echo "{$KEY["aw1"]}=={$KEY["j1"]}-1<br>";
							if($KEY["j1"]!=""){
								if($KEY["aw1"]==$KEY["j1"]){  //채무자1 aw1이 취득자1 j1과 같다면
								//echo "{$KEY["aw1"]}=={$KEY["j1"]}-2<br>";

									if($KEY["av1"]!=""){  //해당채권최고액1 있을때만 입력
										$KEY["aw1_jumin"] = $KEY["k1"];
									}

									if($KEY["ax1"]!=""){  //해당채권최고액2 있을때만 입력
										$KEY["aw2"] = $KEY["j1"];
										$KEY["aw2_jumin"] = $KEY["k1"];
									}
									if($KEY["ay1"]!=""){  //해당채권최고액3 있을때만 입력
										$KEY["aw3"] = $KEY["j1"];
										$KEY["aw3_jumin"] = $KEY["k1"];
									}
									if($KEY["az1"]!=""){  //해당채권최고액4 있을때만 입력
										$KEY["aw4"] = $KEY["j1"];
										$KEY["aw4_jumin"] = $KEY["k1"];
									}
								}
							}

							if($KEY["m1"]!=""){
								if($KEY["aw1"]==$KEY["m1"]){  //채무자1 aw1이 취득자2 m1과 같다면
								//echo "{$KEY["aw1"]}=={$KEY["m1"]}-3<br>";
									if($KEY["av1"]!=""){
										$KEY["aw1_jumin"] = $KEY["n1"];
									}

									if($KEY["ax1"]!=""){
										$KEY["aw2"] = $KEY["m1"];
										$KEY["aw2_jumin"] = $KEY["n1"];
									}

									if($KEY["ay1"]!=""){
										$KEY["aw3"] = $KEY["m1"];
										$KEY["aw3_jumin"] = $KEY["n1"];
									}

									if($KEY["az1"]!=""){
										$KEY["aw4"] = $KEY["m1"];
										$KEY["aw4_jumin"] = $KEY["n1"];
									}
								}
							}
							//echo "<br>--------------------------<br>";
							//메인 tbl_junib 입력
							if($h_idx!="" && $h1!=""){
								$updatewhere = " WHERE a1 = '{$KEY[a1]}' ";
								$idx = db_replace($KEY,$tablename,$updatewhere,"idx");
							}

							if($KEY["av1"]!=""){  //채권최고액1이 있다면
								if(f_cco_ch($h_idx,$KEY["a1"],"av1",$KEY["av1"])=="Y"){  //동일하거가 없거나
									$KEY1 = array();
									$KEY1["h_idx"] = $h_idx;
									$KEY1["a1"] = $KEY["a1"];
									$KEY1["suljung_no"] = 1;
									$KEY1["bank_code"] = $KEY["d1"]; //은행코드
									$KEY1["jijum_code"] = $KEY["e1"]; //지점코드
									$regi_lic_v = intval($KEY["av1"]) * 0.002;
									$KEY1["regi_lic"]  = floor($regi_lic_v/10)*10; //등록면허세1
									$local_edu_v = intval($KEY["av1"]) * 0.002 * 0.2;
									$KEY1["local_edu"]  = floor($local_edu_v/10)*10; //지방교육세1

									$KEY1["chaekwon_max"]  = intval($KEY["av1"]);//채권최고액1
									$KEY1["suljung_maeib"]  = intval($KEY["ba1"]);//설정채권매입액1
									$KEY1["chaekwon_no"]  = $KEY["z1"];//설정채권번호1

									if($KEY["cg_daesang"]=="Y") $KEY1["cg_daesang"] = "Y";  //최초대상인것

									$KEY1["suljung_bosu"]  = f_nujin_value($KEY["d1"],$KEY["e1"],$KEY["av1"],$a1, ""); //은행누진보수료 참조 (은행코드,지점코드,채권채고액)


									
									//기존자료있는지 확인
									$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no=1 ";
									$r1 = db_query_value($sql);

									//echo "00";
									if(($r1["bank_code"]==$KEY["d1"])&&($r1["jijum_code"]==$KEY["e1"])&&($r1["chaekwon_max"]==$KEY["av1"])){  //같으면 갱신
										//echo "11";
										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=1";
										db_replace($KEY1,$tablename_suljung,$updatewhere,"idx");

										$KEY1 = array();

										$ff = f_suljung_bosu2($KEY["a1"],"1","");

										$KEY1[bosu_price] = $ff[fee];
										$KEY1[bosu_price_vat] = $ff[vat];

										$KEY1[gongga_price] =  f_suljung_gongga2($KEY["a1"],"1","");
										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=1";
										db_replace($KEY1,$tablename_suljung,$updatewhere,"idx");



										//수금 자료 생성
										$KEY_S = array();
										$KEY_S[a1] = $KEY[a1];
										$KEY_S[suljung_no] = 1;
										$KEY_S[h_idx] = $h_idx;

										if($h_idx!=""){
											$idx = db_replace($KEY_S,"tbl_sugum"," WHERE a1 = '{$KEY[a1]}' and suljung_no=1 ","idx");
										}

									}else if(($r1["bank_code"]=="")&&($r1["jijum_code"]=="")&&($r1["chaekwon_max"]=="")){  //없으면 생성
										//echo "22";
										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=1";
										db_replace($KEY1,$tablename_suljung,$updatewhere,"idx");

										$KEY1 = array();

										$ff = f_suljung_bosu2($KEY["a1"],"1","");

										$KEY1[bosu_price] = $ff[fee];
										$KEY1[bosu_price_vat] = $ff[vat];

										$KEY1[gongga_price] =  f_suljung_gongga2($KEY["a1"],"1","");
										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=1";
										db_replace($KEY1,$tablename_suljung,$updatewhere,"idx");

										if($KEY["a1"]=="송도동일하이빌1009동2702호"){
											$error_list.= "a1 : {$KEY[a1]} / bosu_price : {$KEY1[bosu_price]} / bosu_price_vat : {$KEY1[bosu_price_vat]} <br>";
										}
//											$error_list.= "a1 : {$KEY[a1]} / bosu_price : {$KEY1[bosu_price]} / bosu_price_vat : {$KEY1[bosu_price_vat]} <br>";
										//수금 자료 생성
										$KEY_S = array();
										$KEY_S[a1] = $KEY[a1];
										$KEY_S[suljung_no] = 1;
										$KEY_S[h_idx] = $h_idx;
										if($h_idx!=""){
											$idx = db_replace($KEY_S,"tbl_sugum"," WHERE a1 = '{$KEY[a1]}' and suljung_no=1 ","idx");
										}

									}else{  //다르면 오류
										//echo "33";
											$error_list.= "{$x}열 / {$a1} --> 채권최고액1 기존값 상이함.확인바랍니다.1<br>";
									}

								}else{
											$error_list.= "{$x}열 / {$a1} --> 채권최고액1 기존값 상이함.확인바랍니다.2<br>";
									$err++;
									exit;
								}
							}

							if($KEY["ax1"]!=""){  //채권최고액2이 있다면
								if(f_cco_ch($h_idx,$KEY["a1"],"ax1",$KEY["ax1"])=="Y"){  //동일하거가 없거나
									$KEY2 = array();
									$KEY2["h_idx"] = $h_idx;
									$KEY2["a1"] = $KEY["a1"];
									$KEY2["suljung_no"] = 2;
									$KEY2["bank_code"] = $KEY["d1"]; //은행코드
									$KEY2["jijum_code"] = $KEY["e1"]; //지점코드
									$regi_lic_v = intval($KEY["ax1"]) * 0.002;
									$KEY2["regi_lic"]  = floor($regi_lic_v/10)*10; //등록면허세2
									$local_edu_v = intval($KEY["ax1"]) * 0.002 * 0.2;
									$KEY2["local_edu"]  = floor($local_edu_v/10)*10; //지방교육세2
									
									$KEY2["chaekwon_max"]  = intval($KEY["ax1"]);//채권최고액2
									$KEY2["suljung_maeib"]  = intval($KEY["bb1"]);//설정채권매입액2
									$KEY2["chaekwon_no"]  = $KEY["aa1"];//설정채권번호2

									if($KEY["cg_daesang"]=="Y") $KEY2["cg_daesang"] = "";

									$KEY2["suljung_bosu"]  = f_nujin_value($KEY["d1"],$KEY["e1"],$KEY["ax1"],$a1,""); //은행누진보수료 참조 (은행코드,지점코드)

									$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no=2 ";
									$r1 = db_query_value($sql);
							
									
									if(($r1["bank_code"]==$KEY["d1"])&&($r1["jijum_code"]==$KEY["e1"])&&($r1["chaekwon_max"]==$KEY["ax1"])){  //같으면 갱신

										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=2";
										db_replace($KEY2,$tablename_suljung,$updatewhere,"idx");

										$KEY2 = array();
										
										$ff = f_suljung_bosu2($KEY["a1"],"2","");

										$KEY2[bosu_price] = $ff[fee];
										$KEY2[bosu_price_vat] = $ff[vat];
										
										$KEY2[gongga_price] =  f_suljung_gongga2($KEY["a1"],"2","");
										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=2";
										db_replace($KEY2,$tablename_suljung,$updatewhere,"idx");

										//수금 자료 생성
										$KEY_S = array();
										$KEY_S[a1] = $KEY[a1];
										$KEY_S[suljung_no] = 2;
										$KEY_S[h_idx] = $h_idx;
										if($h_idx!=""){
											$idx = db_replace($KEY_S,"tbl_sugum"," WHERE a1 = '{$KEY[a1]}' and suljung_no=2 ","idx");
										}

									}else if(($r1["bank_code"]=="")&&($r1["jijum_code"]=="")&&($r1["chaekwon_max"]=="")){  //없으면 생성

										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=2";
										db_replace($KEY2,$tablename_suljung,$updatewhere,"idx");

										$KEY2 = array();
										
										$ff = f_suljung_bosu2($KEY["a1"],2,"");

										$KEY2[bosu_price] = $ff[fee];
										$KEY2[bosu_price_vat] = $ff[vat];
										
										$KEY2[gongga_price] =  f_suljung_gongga2($KEY["a1"],"2","");
										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=2";
										db_replace($KEY2,$tablename_suljung,$updatewhere,"idx");

										//수금 자료 생성
										$KEY_S = array();
										$KEY_S[a1] = $KEY[a1];
										$KEY_S[suljung_no] = 2;
										$KEY_S[h_idx] = $h_idx;
										if($h_idx!=""){
											$idx = db_replace($KEY_S,"tbl_sugum"," WHERE a1 = '{$KEY[a1]}' and suljung_no=2 ","idx");
										}

									}else{  //다르면 오류
											$error_list.= "{$x}열 / {$a1} --> 채권최고액2 기존값 상이함.확인바랍니다.1<br>";
									}

								}else{
											$error_list.= "{$x}열 / {$a1} --> 채권최고액2 기존값 상이함.확인바랍니다.2<br>";
									$err++;
									exit;
								}
							}


							if($KEY["ay1"]!=""){  //채권최고액3이 있다면
								if(f_cco_ch($h_idx,$KEY["a1"],"ay1",$KEY["ay1"])=="Y"){  //동일하거가 없거나
									$KEY3 = array();
									$KEY3["h_idx"] = $h_idx;
									$KEY3["a1"] = $KEY["a1"];
									$KEY3["suljung_no"] = 3;
									$KEY3["bank_code"] = $KEY["d1"]; //은행코드
									$KEY3["jijum_code"] = $KEY["e1"]; //지점코드
									$regi_lic_v = intval($KEY["ay1"]) * 0.002;
									$KEY3["regi_lic"]  = floor($regi_lic_v/10)*10; //등록면허세3
									$local_edu_v = intval($KEY["ay1"]) * 0.002 * 0.2;
									$KEY3["local_edu"]  = floor($local_edu_v/10)*10; //지방교육세3

									$KEY3["chaekwon_max"]  = intval($KEY["ay1"]);//채권최고액3
									$KEY3["suljung_maeib"]  = intval($KEY["bc1"]);//설정채권매입액3
									$KEY3["chaekwon_no"]  = $KEY["ab1"];//설정채권번호3

									if($KEY["cg_daesang"]=="Y") $KEY3["cg_daesang"] = "";

									$KEY3["suljung_bosu"]  = f_nujin_value($KEY["d1"],$KEY["e1"],$KEY["ay1"],$a1,""); //은행누진보수료 참조 (은행코드,지점코드)

									$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no=3 ";
									$r1 = db_query_value($sql);
							
									
									if(($r1["bank_code"]==$KEY["d1"])&&($r1["jijum_code"]==$KEY["e1"])&&($r1["chaekwon_max"]==$KEY["ay1"])){  //같으면 갱신

										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=3";
										db_replace($KEY3,$tablename_suljung,$updatewhere,"idx");

										$KEY3 = array();
										
										$ff = f_suljung_bosu2($KEY["a1"],3,"");

										$KEY3[bosu_price] = $ff[fee];
										$KEY3[bosu_price_vat] = $ff[vat];
										
										$KEY3[gongga_price] =  f_suljung_gongga2($KEY["a1"],"3","");
										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=3";
										db_replace($KEY3,$tablename_suljung,$updatewhere,"idx");

										//수금 자료 생성
										$KEY_S = array();
										$KEY_S[a1] = $KEY[a1];
										$KEY_S[suljung_no] = 3;
										$KEY_S[h_idx] = $h_idx;
										if($h_idx!=""){
											$idx = db_replace($KEY_S,"tbl_sugum"," WHERE a1 = '{$KEY[a1]}' and suljung_no=3 ","idx");
										}

									}else if(($r1["bank_code"]=="")&&($r1["jijum_code"]=="")&&($r1["chaekwon_max"]=="")){  //없으면 생성

										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=3";
										db_replace($KEY3,$tablename_suljung,$updatewhere,"idx");

										$KEY3 = array();
										
										$ff = f_suljung_bosu2($KEY["a1"],3,"");

										$KEY3[bosu_price] = $ff[fee];
										$KEY3[bosu_price_vat] = $ff[vat];
										
										$KEY3[gongga_price] =  f_suljung_gongga2($KEY["a1"],"3","");
										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=3";
										db_replace($KEY3,$tablename_suljung,$updatewhere,"idx");

										//수금 자료 생성
										$KEY_S = array();
										$KEY_S[a1] = $KEY[a1];
										$KEY_S[suljung_no] = 3;
										$KEY_S[h_idx] = $h_idx;
										if($h_idx!=""){
											$idx = db_replace($KEY_S,"tbl_sugum"," WHERE a1 = '{$KEY[a1]}' and suljung_no=3 ","idx");
										}

									}else{  //다르면 오류
											$error_list.= "{$x}열 / {$a1} --> 채권최고액3 기존값 상이함.확인바랍니다.1<br>";
									}

								}else{
											$error_list.= "{$x}열 / {$a1} --> 채권최고액3 기존값 상이함.확인바랍니다.2<br>";
									$err++;
									exit;
								}
							}

							if($KEY["az1"]!=""){  //채권최고액4이 있다면
								if(f_cco_ch($h_idx,$KEY["a1"],"az1",$KEY["az1"])=="Y"){  //동일하거가 없거나
									$KEY4 = array();
									$KEY4["h_idx"] = $h_idx;
									$KEY4["a1"] = $KEY["a1"];
									$KEY4["suljung_no"] = 4;
									$KEY4["bank_code"] = $KEY["d1"]; //은행코드
									$KEY4["jijum_code"] = $KEY["e1"]; //지점코드
									$regi_lic_v = intval($KEY["az1"]) * 0.002;
									$KEY4["regi_lic"]  = floor($regi_lic_v/10)*10; //등록면허세2
									$local_edu_v = intval($KEY["az1"]) * 0.002 * 0.2;
									$KEY4["local_edu"]  = floor($local_edu_v/10)*10; //지방교육세2

									$KEY4["chaekwon_max"]  = intval($KEY["az1"]);//채권최고액4
									$KEY4["suljung_maeib"]  = intval($KEY["bd1"]);//설정채권매입액4
									$KEY4["chaekwon_no"]  = $KEY["ac1"];//설정채권번호4

									if($KEY["cg_daesang"]=="Y") $KEY4["cg_daesang"] = "";

									$KEY4["suljung_bosu"]  = f_nujin_value($KEY["d1"],$KEY["e1"],$KEY["az1"],$a1,""); //은행누진보수료 참조 (은행코드,지점코드)

									$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no=4 ";
									$r1 = db_query_value($sql);
							
									
									if(($r1["bank_code"]==$KEY["d1"])&&($r1["jijum_code"]==$KEY["e1"])&&($r1["chaekwon_max"]==$KEY["az1"])){  //같으면 갱신

										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=4";
										db_replace($KEY4,$tablename_suljung,$updatewhere,"idx");

										$KEY4 = array();
										
										$ff = f_suljung_bosu2($KEY["a1"],"4","");

										$KEY4[bosu_price] = $ff[fee];
										$KEY4[bosu_price_vat] = $ff[vat];
										
										$KEY4[gongga_price] =  f_suljung_gongga2($KEY["a1"],"4","");
										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=4";
										db_replace($KEY4,$tablename_suljung,$updatewhere,"idx");

										//수금 자료 생성
										$KEY_S = array();
										$KEY_S[a1] = $KEY[a1];
										$KEY_S[suljung_no] = 4;
										$KEY_S[h_idx] = $h_idx;
										if($h_idx!=""){
											$idx = db_replace($KEY_S,"tbl_sugum"," WHERE a1 = '{$KEY[a1]}' and suljung_no=4 ","idx");
										}

									}else if(($r1["bank_code"]=="")&&($r1["jijum_code"]=="")&&($r1["chaekwon_max"]=="")){  //없으면 생성

										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=4";
										db_replace($KEY4,$tablename_suljung,$updatewhere,"idx");

										$KEY4 = array();
										
										$ff = f_suljung_bosu2($KEY["a1"],4,"");

										$KEY4[bosu_price] = $ff[fee];
										$KEY4[bosu_price_vat] = $ff[vat];
										
										$KEY4[gongga_price] =  f_suljung_gongga2($KEY["a1"],"4","");
										$updatewhere = " WHERE a1 = '{$KEY[a1]}' and suljung_no=4";
										db_replace($KEY4,$tablename_suljung,$updatewhere,"idx");

										//수금 자료 생성
										$KEY_S = array();
										$KEY_S[a1] = $KEY[a1];
										$KEY_S[suljung_no] = 4;
										$KEY_S[h_idx] = $h_idx;
										if($h_idx!=""){
											$idx = db_replace($KEY_S,"tbl_sugum"," WHERE a1 = '{$KEY[a1]}' and suljung_no=4 ","idx");
										}

									}else{  //다르면 오류
											$error_list.= "{$x}열 / {$a1} --> 채권최고액4 기존값 상이함.확인바랍니다.1<br>";
									}

								}else{
											$error_list.= "{$x}열 / {$a1} --> 채권최고액4 기존값 상이함.확인바랍니다.2<br>";
									$err++;
									exit;
								}
							}

							//메인 tbl_junib 입력
//							if($h_idx!="" && $h1!=""){
//								$updatewhere = " WHERE a1 = '{$KEY[a1]}' ";
//								$idx = db_replace($KEY,$tablename,$updatewhere,"idx");
//							}

						}else{
							//echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
							$error_list.= "{$x}열 / {$a1} / {$a1_x} 고객고유번호(A)와  동(H)호(I)가 상이합니다.<br>";
							echo "{$x}열 / {$a1} / {$a1_x} 고객고유번호(A)와  동(H)호(I)가 상이합니다.<br>";
							//echo "<script>alert('{$x}열 / {$a1} / {$a1_x} 고객고유번호(A)와  동(H)호(I)가 상이합니다.');location.href='index.html';</script>";
							//$err++;
						}

						//print_r($KEY);
						//echo "<br>";
					}else{
							$error_list.= "{$x}열 / {$a1} / 등기접수일이 없습니다.<br>";
					}
				}
			}
	}
}

	$sql = "delete from tbl_junib where h_idx=0";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$sql = "delete from tbl_sugum where h_idx=0";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$imsi= "<script>f_kk();location.href='index.html';</script>";
}



catch(Exception $e) {
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<script>alert('엑셀파일 .xls 파일만 업로드가 가능합니다.');location.href='index.html';</script>";
}

function f_xx($p1){
	//65부터 90 A->Z
	$p2 = $p1 + 64;
	return chr($p2);
}
?>
<html>
<body>
	<form name="ffm" id="ffm" method=post>
		<input type="hidden" name="contents" value="<?=$error_list?>">
	</form>
</body>
</html>

<script>
function f_kk(){
			var frm    = document.ffm;
			var url    ="./popup_form.html";
			var title  = "listpop2";
			var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=800, height=800, top=0,left=20"; 
			var aa1 = window.open("", title,status);
			frm.target = title;
			frm.action = url;
			frm.method = "post";
			frm.submit();
			aa1.focus();
}
</script>
<?=$imsi?>