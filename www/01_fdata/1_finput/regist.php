<? include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

//	error_reporting(E_ALL);
//	ini_set("display_errors", 1);

	$board_dbname	=	"tbl_junib";
	$board_dbname2	=	"tbl_suljung";

	$a1				=	trim($_REQUEST[a1]);

	//이전화면 값 가져오기
	$h_idx				=	trim($_REQUEST[h_idx]);
	$b1				=	trim($_REQUEST[b1]);
	$doc_receive_date				=	trim($_REQUEST[doc_receive_date]);
	if($doc_receive_date=="")		$doc_receive_date=date("Ymd");

	$w1				=	trim($_REQUEST[w1]);
	$con_building_area		=	trim($_REQUEST[con_building_area]);
	$con_land_area				=	trim($_REQUEST[con_land_area]);

	$s_point = "";  //저장구분 1~5단계저장 0 전체저장


	if($a1==""){
		$mode="i";  //insert 신규
		$wherequery = " where 1=1  ";
	}else{
		$mode="e";  //edit 수정

		$sql= "select * from $board_dbname where a1='{$a1}' ";
		//echo $sql;
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$row = $stmt->fetch();

		if ($row[f1]=="1"){
			$sql= "select * from $board_dbname2 where a1='{$a1}' and suljung_no='1' ";
			//echo $sql;
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$rows1 = $stmt->fetch();
		} else if ($row[f1]=="2"){
			$sql= "select * from $board_dbname2 where a1='{$a1}' and suljung_no='1' ";
			//echo $sql;
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$rows1 = $stmt->fetch();

			$sql= "select * from $board_dbname2 where a1='{$a1}' and suljung_no='2' ";
			//echo $sql;
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$rows2 = $stmt->fetch();

		} else if ($row[f1]=="3"){
			$sql= "select * from $board_dbname2 where a1='{$a1}' and suljung_no='1' ";
			//echo $sql;
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$rows1 = $stmt->fetch();

			$sql= "select * from $board_dbname2 where a1='{$a1}' and suljung_no='2' ";
			//echo $sql;
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$rows2 = $stmt->fetch();

			$sql= "select * from $board_dbname2 where a1='{$a1}' and suljung_no='3' ";
			//echo $sql;
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$rows3 = $stmt->fetch();
		} else if ($row[f1]=="4"){
			$sql= "select * from $board_dbname2 where a1='{$a1}' and suljung_no='1' ";
			//echo $sql;
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$rows1 = $stmt->fetch();

			$sql= "select * from $board_dbname2 where a1='{$a1}' and suljung_no='2' ";
			//echo $sql;
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$rows2 = $stmt->fetch();

			$sql= "select * from $board_dbname2 where a1='{$a1}' and suljung_no='3' ";
			//echo $sql;
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$rows3 = $stmt->fetch();

			$sql= "select * from $board_dbname2 where a1='{$a1}' and suljung_no='4' ";
			//echo $sql;
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$rows4 = $stmt->fetch();
		} 

	}
	//준공일 , FAQ URL
	$sql = "select * from tbl_hyunjang_info where h_idx='{$row[h_idx]}' ";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$r1 = $stmt->fetch();

	//준공일 , FAQ URL
	$sql = "select * from tbl_hyunjang_danji_info where h_idx='{$row[h_idx]}' and danji_name='{$row[b1]}' ";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$r2 = $stmt->fetch();

?>


<!DOCTYPE html>
<html lang="kr">

<head>
<title>CS돌이</title>
<?include ("../../include/common.php");?>
</head>

<body style="overflow:auto; width:1880px;">

<!--header 시작-->
	<?include ("../../include/header.php");?>
<!--header 종료-->


<!--top-메뉴시작-->
	<?include ("../../include/header_menu.php");?>
<!--top-메뉴종료-->


<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

<script>

$(function() {
		$( ".datepickx" ).datepicker({
			autosize: true,
			showOn: "button",
			buttonImage: "/images/calendar.gif",
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: "yymmdd",
			dayNamesShort:["일","월","화","수","목","금","토"],
			monthNamesShort:["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"]
		});
	 $("img.ui-datepicker-trigger").attr("style", "margin-bottom:4px; vertical-align:middle; cursor: Pointer;");

	// 취득자2 초기에 숨기기
	if($("#mode").val()=="e"){
		
		if($("#apply_count").val()== "1"){
			$('#a_cnt1').css('display', 'none');
			$('#a_cnt2').css('display', 'none');
			$('#a_cnt3').css('display', 'none');
			$('#a_cnt4').css('display', 'none');
			$('#a_cnt5').css('display', 'none');
		}
	} else {
		$("#j1_stake").val("1/1"); 
		$('#a_cnt1').css('display', 'none');
		$('#a_cnt2').css('display', 'none');
		$('#a_cnt3').css('display', 'none');
		$('#a_cnt4').css('display', 'none');
		$('#a_cnt5').css('display', 'none');
	}

//	}

	if($("#mode").val()=="e"){
		if($("#u1").val()=="1"){
			$('#u1_2').css('display', 'none');
			$('#u1_3').css('display', 'none');
			$('#u1_4').css('display', 'none');
			$('#u1_5').css('display', 'none');
			$('#u1_6').css('display', 'none');
		}else if($("#u1").val()=="2"){
			$('#u1_3').css('display', 'none');
			$('#u1_4').css('display', 'none');
			$('#u1_5').css('display', 'none');
			$('#u1_6').css('display', 'none');
		}else if($("#u1").val()=="3"){
			$('#u1_4').css('display', 'none');
			$('#u1_5').css('display', 'none');
			$('#u1_6').css('display', 'none');
		}else if($("#u1").val()=="4"){
			$('#u1_5').css('display', 'none');
			$('#u1_6').css('display', 'none');
		}else if($("#u1").val()=="5"){
			$('#u1_6').css('display', 'none');
		}else if($("#u1").val()=="6"){
			
		}else{
			$('#u1_1').css('display', 'none');
			$('#u1_2').css('display', 'none');
			$('#u1_3').css('display', 'none');
			$('#u1_4').css('display', 'none');
			$('#u1_5').css('display', 'none');
			$('#u1_6').css('display', 'none');
		}
	}else{
		$('#u1_1').css('display', 'none');
		$('#u1_2').css('display', 'none');
		$('#u1_3').css('display', 'none');
		$('#u1_4').css('display', 'none');
		$('#u1_5').css('display', 'none');
		$('#u1_6').css('display', 'none');
	}

// 설정갯수 초기화
	if($("#mode").val()=="e"){
		if($("#f1").val()=="1"){
			$('#cnt2_1').css('display', 'none');
			$('#cnt2_2').css('display', 'none');
			$('#cnt2_3').css('display', 'none');
			$('#cnt2_4').css('display', 'none');
			$('#cnt3_1').css('display', 'none');
			$('#cnt3_2').css('display', 'none');
			$('#cnt3_3').css('display', 'none');
			$('#cnt3_4').css('display', 'none');
			$('#cnt4_1').css('display', 'none');
			$('#cnt4_2').css('display', 'none');
			$('#cnt4_3').css('display', 'none');
			$('#cnt4_4').css('display', 'none');
			if($("#reduction_code_1").val()=="1"){
				$('#reduction_etc_name_1').css('display', 'none');
				$('#reduction_etc_name_2').css('display', 'none');
				$('#reduction_etc_name_3').css('display', 'none');
				$('#reduction_etc_name_4').css('display', 'none');
			}
		} else if($("#f1").val()=="2"){
			$('#cnt3_1').css('display', 'none');
			$('#cnt3_2').css('display', 'none');
			$('#cnt3_3').css('display', 'none');
			$('#cnt3_4').css('display', 'none');
			$('#cnt4_1').css('display', 'none');
			$('#cnt4_2').css('display', 'none');
			$('#cnt4_3').css('display', 'none');
			$('#cnt4_4').css('display', 'none');
		} else if($("#f1").val()=="3"){
			$('#cnt4_1').css('display', 'none');
			$('#cnt4_2').css('display', 'none');
			$('#cnt4_3').css('display', 'none');
			$('#cnt4_4').css('display', 'none');
		} else if($("#f1").val()=="4"){

		} else {
			$('#cnt1_1').css('display', 'none');
			$('#cnt1_2').css('display', 'none');
			$('#cnt1_3').css('display', 'none');
			$('#cnt1_4').css('display', 'none');
			$('#cnt2_1').css('display', 'none');
			$('#cnt2_2').css('display', 'none');
			$('#cnt2_3').css('display', 'none');
			$('#cnt2_4').css('display', 'none');
			$('#cnt3_1').css('display', 'none');
			$('#cnt3_2').css('display', 'none');
			$('#cnt3_3').css('display', 'none');
			$('#cnt3_4').css('display', 'none');
			$('#cnt4_1').css('display', 'none');
			$('#cnt4_2').css('display', 'none');
			$('#cnt4_3').css('display', 'none');
			$('#cnt4_4').css('display', 'none');
//			$('#ingam_1').css('display', 'none');
//			$('#a_cnt3').css('display', 'none');
		}
		if($("#reduction_code_1").val()=="1"){
			$('#reduction_etc_name_1').css('display', 'none');
		}
		if($("#reduction_code_2").val()=="1"){
			$('#reduction_etc_name_2').css('display', 'none');
		}
		if($("#reduction_code_3").val()=="1"){
			$('#reduction_etc_name_3').css('display', 'none');
		}
		if($("#reduction_code_4").val()=="1"){
			$('#reduction_etc_name_4').css('display', 'none');
		}
	} else {
		$('#cnt1_1').css('display', 'none');
		$('#cnt1_2').css('display', 'none');
		$('#cnt1_3').css('display', 'none');
		$('#cnt1_4').css('display', 'none');
		$('#cnt2_1').css('display', 'none');
		$('#cnt2_2').css('display', 'none');
		$('#cnt2_3').css('display', 'none');
		$('#cnt2_4').css('display', 'none');
		$('#cnt3_1').css('display', 'none');
		$('#cnt3_2').css('display', 'none');
		$('#cnt3_3').css('display', 'none');
		$('#cnt3_4').css('display', 'none');
		$('#cnt4_1').css('display', 'none');
		$('#cnt4_2').css('display', 'none');
		$('#cnt4_3').css('display', 'none');
		$('#cnt4_4').css('display', 'none');

	// 감면율1~4 초기화
		$('#reduction_etc_name_1').css('display', 'none');
		$('#reduction_etc_name_2').css('display', 'none');
		$('#reduction_etc_name_3').css('display', 'none');
		$('#reduction_etc_name_4').css('display', 'none');

//		$('#ingam_1').css('display', 'none');
//		$('#a_cnt3').css('display', 'none');

	}

	// 필지갯수 제어
	if($("#mode").val()=="e"){
		
		if($("#lot_amount").val()== "1"){
			$('#la_cnt1').css('display', '');
			$('#la_cnt2').css('display', 'none');
			$('#la_cnt3').css('display', 'none');
			$('#la_cnt4').css('display', 'none');
			$('#la_cnt5').css('display', 'none');
			$('#la_cnt6').css('display', 'none');
			$('#la_cnt7').css('display', 'none');
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
		}else if($("#lot_amount").val()== "2"){
			$('#la_cnt1').css('display', '');
			$('#la_cnt2').css('display', '');
			$('#la_cnt3').css('display', 'none');
			$('#la_cnt4').css('display', 'none');
			$('#la_cnt5').css('display', 'none');
			$('#la_cnt6').css('display', 'none');
			$('#la_cnt7').css('display', 'none');
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
		}else if($("#lot_amount").val()== "3"){
			$('#la_cnt1').css('display', '');
			$('#la_cnt2').css('display', '');
			$('#la_cnt3').css('display', '');
			$('#la_cnt4').css('display', 'none');
			$('#la_cnt5').css('display', 'none');
			$('#la_cnt6').css('display', 'none');
			$('#la_cnt7').css('display', 'none');
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
		}else if($("#lot_amount").val()== "4"){
			$('#la_cnt1').css('display', '');
			$('#la_cnt2').css('display', '');
			$('#la_cnt3').css('display', '');
			$('#la_cnt4').css('display', '');
			$('#la_cnt5').css('display', 'none');
			$('#la_cnt6').css('display', 'none');
			$('#la_cnt7').css('display', 'none');
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
		}else if($("#lot_amount").val()== "5"){
			$('#la_cnt1').css('display', '');
			$('#la_cnt2').css('display', '');
			$('#la_cnt3').css('display', '');
			$('#la_cnt4').css('display', '');
			$('#la_cnt5').css('display', '');
			$('#la_cnt6').css('display', 'none');
			$('#la_cnt7').css('display', 'none');
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
		}else if($("#lot_amount").val()== "6"){
			$('#la_cnt1').css('display', '');
			$('#la_cnt2').css('display', '');
			$('#la_cnt3').css('display', '');
			$('#la_cnt4').css('display', '');
			$('#la_cnt5').css('display', '');
			$('#la_cnt6').css('display', '');
			$('#la_cnt7').css('display', 'none');
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
		}else if($("#lot_amount").val()== "7"){
			$('#la_cnt1').css('display', '');
			$('#la_cnt2').css('display', '');
			$('#la_cnt3').css('display', '');
			$('#la_cnt4').css('display', '');
			$('#la_cnt5').css('display', '');
			$('#la_cnt6').css('display', '');
			$('#la_cnt7').css('display', '');
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
		}else if($("#lot_amount").val()== "8"){
			$('#la_cnt1').css('display', '');
			$('#la_cnt2').css('display', '');
			$('#la_cnt3').css('display', '');
			$('#la_cnt4').css('display', '');
			$('#la_cnt5').css('display', '');
			$('#la_cnt6').css('display', '');
			$('#la_cnt7').css('display', '');
			$('#la_cnt8').css('display', '');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
		}else if($("#lot_amount").val()== "9"){
			$('#la_cnt1').css('display', '');
			$('#la_cnt2').css('display', '');
			$('#la_cnt3').css('display', '');
			$('#la_cnt4').css('display', '');
			$('#la_cnt5').css('display', '');
			$('#la_cnt6').css('display', '');
			$('#la_cnt7').css('display', '');
			$('#la_cnt8').css('display', '');
			$('#la_cnt9').css('display', '');
			$('#la_cnt10').css('display', 'none');
		}else if($("#lot_amount").val()== "10"){
			$('#la_cnt1').css('display', '');
			$('#la_cnt2').css('display', '');
			$('#la_cnt3').css('display', '');
			$('#la_cnt4').css('display', '');
			$('#la_cnt5').css('display', '');
			$('#la_cnt6').css('display', '');
			$('#la_cnt7').css('display', '');
			$('#la_cnt8').css('display', '');
			$('#la_cnt9').css('display', '');
			$('#la_cnt10').css('display', '');
		}else {
			$('#la_cnt1').css('display', 'none');
			$('#la_cnt2').css('display', 'none');
			$('#la_cnt3').css('display', 'none');
			$('#la_cnt4').css('display', 'none');
			$('#la_cnt5').css('display', 'none');
			$('#la_cnt6').css('display', 'none');
			$('#la_cnt7').css('display', 'none');
			$('#la_cnt8').css('display', 'none');
			$('#la_cnt9').css('display', 'none');
			$('#la_cnt10').css('display', 'none');
		}
	} else {
		$('#la_cnt1').css('display', 'none');
		$('#la_cnt2').css('display', 'none');
		$('#la_cnt3').css('display', 'none');
		$('#la_cnt4').css('display', 'none');
		$('#la_cnt5').css('display', 'none');
		$('#la_cnt6').css('display', 'none');
		$('#la_cnt7').css('display', 'none');
		$('#la_cnt8').css('display', 'none');
		$('#la_cnt9').css('display', 'none');
		$('#la_cnt10').css('display', 'none');
	}

	if($("#mode").val()=="e"){
		if($("#point_data").val()!="3"){
			$('#point_data_name').css('display', 'none');
		}
	}
	$('#tax_cut_cause_name').css('display', 'none');

});
$( ".datepickx" ).datepicker( "option", "dayNamesShort",  [ "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam" ] );


// 특이사항 기타 처리
function point_etc(frm){
	var v = document.ff;
	//alert(frm);
	if(frm=="3"){
		$('#point_data_name').css('display', '');
	} else {
		$('#point_data_name').css('display', 'none');
		v.point_data_name.value="";  // 특이사항명 초기화
	}
}

// 취득세감면사유 기타 처리
function tax_cut_etc(frm){
	var v = document.ff;
	//alert(frm);
	if(frm=="7"){
		$('#tax_cut_cause_name').css('display', '');
	} else {
		$('#tax_cut_cause_name').css('display', 'none');
		v.tax_cut_cause_name.value="";  // 특이사항명 초기화
	}
}

// 감면율 기타 처리
function re_code_cnt(frm, cnt){
	var v = document.ff;
	//alert(frm);
	//alert(cnt);
	if(cnt=="1"){
		if(frm=="2"){
			$('#reduction_etc_name_1').css('display', '');
		} else {
			$('#reduction_etc_name_1').css('display', 'none');
			v.reduction_etc_name_1.value="";  // 감면율명1 초기화
		}
	} else if(cnt=="2"){
		if(frm=="2"){
			$('#reduction_etc_name_2').css('display', '');
		} else {
			$('#reduction_etc_name_2').css('display', 'none');
			v.reduction_etc_name_2.value="";  // 감면율명2 초기화
		}
	} else if(cnt=="3"){
		if(frm=="2"){
			$('#reduction_etc_name_3').css('display', '');
		} else {
			$('#reduction_etc_name_3').css('display', 'none');
			v.reduction_etc_name_3.value="";  // 감면율명3 초기화
		}
	} else{
		if(frm=="2"){
			$('#reduction_etc_name_4').css('display', '');
		} else {
			$('#reduction_etc_name_4').css('display', 'none');
			v.reduction_etc_name_4.value="";  // 감면율명4 초기화
		}
	}
}

// 취득자수에 따른 제어
function a_cnt(frm) {
	if(frm=="1"){
		$('#a_cnt1').css('display', 'none');
		$('#a_cnt2').css('display', 'none');
		$('#a_cnt3').css('display', 'none');
		$('#a_cnt4').css('display', 'none');
		$('#a_cnt5').css('display', 'none');
		$("#j1_stake").val("1/1"); 
		$("#m1_stake").val(""); 
	} else {
		$('#a_cnt1').css('display', '');
		$('#a_cnt2').css('display', '');
		$('#a_cnt3').css('display', '');
		$('#a_cnt4').css('display', '');
		$('#a_cnt5').css('display', '');
		$("#j1_stake").val("1/2"); 
		$("#m1_stake").val("1/2"); 
	}

}

// 설정갯수에 따른 제어
function f1_cnt(frm) {
	if(frm=="0"){
		$('#cnt1_1').css('display', 'none');
		$('#cnt1_2').css('display', 'none');
		$('#cnt1_3').css('display', 'none');
		$('#cnt1_4').css('display', 'none');
		$('#cnt2_1').css('display', 'none');
		$('#cnt2_2').css('display', 'none');
		$('#cnt2_3').css('display', 'none');
		$('#cnt2_4').css('display', 'none');
		$('#cnt3_1').css('display', 'none');
		$('#cnt3_2').css('display', 'none');
		$('#cnt3_3').css('display', 'none');
		$('#cnt3_4').css('display', 'none');
		$('#cnt4_1').css('display', 'none');
		$('#cnt4_2').css('display', 'none');
		$('#cnt4_3').css('display', 'none');
		$('#cnt4_4').css('display', 'none');
//		$('#ingam_1').css('display', 'none');
//		$('#a_cnt3').css('display', 'none');
	}else if(frm=="1"){
		$('#cnt1_1').css('display', '');
		$('#cnt1_2').css('display', '');
		$('#cnt1_3').css('display', '');
		$('#cnt1_4').css('display', '');
		$('#cnt2_1').css('display', 'none');
		$('#cnt2_2').css('display', 'none');
		$('#cnt2_3').css('display', 'none');
		$('#cnt2_4').css('display', 'none');
		$('#cnt3_1').css('display', 'none');
		$('#cnt3_2').css('display', 'none');
		$('#cnt3_3').css('display', 'none');
		$('#cnt3_4').css('display', 'none');
		$('#cnt4_1').css('display', 'none');
		$('#cnt4_2').css('display', 'none');
		$('#cnt4_3').css('display', 'none');
		$('#cnt4_4').css('display', 'none');
//		$('#ingam_1').css('display', '');
//		$('#a_cnt3').css('display', '');
	}else if(frm=="2"){
		$('#cnt1_1').css('display', '');
		$('#cnt1_2').css('display', '');
		$('#cnt1_3').css('display', '');
		$('#cnt1_4').css('display', '');
		$('#cnt2_1').css('display', '');
		$('#cnt2_2').css('display', '');
		$('#cnt2_3').css('display', '');
		$('#cnt2_4').css('display', '');
		$('#cnt3_1').css('display', 'none');
		$('#cnt3_2').css('display', 'none');
		$('#cnt3_3').css('display', 'none');
		$('#cnt3_4').css('display', 'none');
		$('#cnt4_1').css('display', 'none');
		$('#cnt4_2').css('display', 'none');
		$('#cnt4_3').css('display', 'none');
		$('#cnt4_4').css('display', 'none');
//		$('#ingam_1').css('display', '');
//		$('#a_cnt3').css('display', '');
	}else if(frm=="3"){
		$('#cnt1_1').css('display', '');
		$('#cnt1_2').css('display', '');
		$('#cnt1_3').css('display', '');
		$('#cnt1_4').css('display', '');
		$('#cnt2_1').css('display', '');
		$('#cnt2_2').css('display', '');
		$('#cnt2_3').css('display', '');
		$('#cnt2_4').css('display', '');
		$('#cnt3_1').css('display', '');
		$('#cnt3_2').css('display', '');
		$('#cnt3_3').css('display', '');
		$('#cnt3_4').css('display', '');
		$('#cnt4_1').css('display', 'none');
		$('#cnt4_2').css('display', 'none');
		$('#cnt4_3').css('display', 'none');
		$('#cnt4_4').css('display', 'none');
//		$('#ingam_1').css('display', '');
//		$('#a_cnt3').css('display', '');
	} else {
		$('#cnt1_1').css('display', '');
		$('#cnt1_2').css('display', '');
		$('#cnt1_3').css('display', '');
		$('#cnt1_4').css('display', '');
		$('#cnt2_1').css('display', '');
		$('#cnt2_2').css('display', '');
		$('#cnt2_3').css('display', '');
		$('#cnt2_4').css('display', '');
		$('#cnt3_1').css('display', '');
		$('#cnt3_2').css('display', '');
		$('#cnt3_3').css('display', '');
		$('#cnt3_4').css('display', '');
		$('#cnt4_1').css('display', '');
		$('#cnt4_2').css('display', '');
		$('#cnt4_3').css('display', '');
		$('#cnt4_4').css('display', '');
//		$('#ingam_1').css('display', '');
//		$('#a_cnt3').css('display', '');
	}

}

// 승계여부에 따른 제어
function u1_cnt(frm) {
	var v = document.ff;
	if(frm=="1"){
		v.u1_2.value="";
		v.u1_3.value="";
		v.u1_4.value="";
		v.u1_5.value="";
		v.u1_6.value="";
	} else if(frm=="2"){
		v.u1_3.value="";
		v.u1_4.value="";
		v.u1_5.value="";
		v.u1_6.value="";
	} else if(frm=="3"){
		v.u1_4.value="";
		v.u1_5.value="";
		v.u1_6.value="";
	} else if(frm=="4"){
		v.u1_5.value="";
		v.u1_6.value="";
	} else if(frm=="5"){
		v.u1_6.value="";
	} else if(frm=="6"){
		
	} else {
		v.u1_1.value="";
		v.u1_2.value="";
		v.u1_3.value="";
		v.u1_4.value="";
		v.u1_5.value="";
		v.u1_6.value="";
	}
	var v = document.ff;
	if(frm=="1"){
		$('#u1_1').css('display', '');
		$('#u1_2').css('display', 'none');
		$('#u1_3').css('display', 'none');
		$('#u1_4').css('display', 'none');
		$('#u1_5').css('display', 'none');
		$('#u1_6').css('display', 'none');
		v.u1_2.value="";  // 승계2차
		v.u1_3.value="";  // 승계3차
		v.u1_4.value="";  // 승계4차
		v.u1_5.value="";  // 승계5차
		v.u1_6.value="";  // 승계6차
	} else if(frm=="2"){
		$('#u1_1').css('display', '');
		$('#u1_2').css('display', '');
		$('#u1_3').css('display', 'none');
		$('#u1_4').css('display', 'none');
		$('#u1_5').css('display', 'none');
		$('#u1_6').css('display', 'none');
		v.u1_3.value="";  // 승계3차
		v.u1_4.value="";  // 승계4차
		v.u1_5.value="";  // 승계5차
		v.u1_6.value="";  // 승계6차
	} else if(frm=="3"){
		$('#u1_1').css('display', '');
		$('#u1_2').css('display', '');
		$('#u1_3').css('display', '');
		$('#u1_4').css('display', 'none');
		$('#u1_5').css('display', 'none');
		$('#u1_6').css('display', 'none');
		v.u1_4.value="";  // 승계4차
		v.u1_5.value="";  // 승계5차
		v.u1_6.value="";  // 승계6차
	} else if(frm=="4"){
		$('#u1_1').css('display', '');
		$('#u1_2').css('display', '');
		$('#u1_3').css('display', '');
		$('#u1_4').css('display', '');
		$('#u1_5').css('display', 'none');
		$('#u1_6').css('display', 'none');
		v.u1_5.value="";  // 승계5차
		v.u1_6.value="";  // 승계6차
	} else if(frm=="5"){
		$('#u1_1').css('display', '');
		$('#u1_2').css('display', '');
		$('#u1_3').css('display', '');
		$('#u1_4').css('display', '');
		$('#u1_5').css('display', '');
		$('#u1_6').css('display', 'none');
		v.u1_6.value="";  // 승계6차
	} else if(frm=="6"){
		$('#u1_1').css('display', '');
		$('#u1_2').css('display', '');
		$('#u1_3').css('display', '');
		$('#u1_4').css('display', '');
		$('#u1_5').css('display', '');
		$('#u1_6').css('display', '');
	} else {
		$('#u1_1').css('display', 'none');
		$('#u1_2').css('display', 'none');
		$('#u1_3').css('display', 'none');
		$('#u1_4').css('display', 'none');
		$('#u1_5').css('display', 'none');
		$('#u1_6').css('display', 'none');
		v.u1_1.value="";  // 승계1차
		v.u1_2.value="";  // 승계2차
		v.u1_3.value="";  // 승계3차
		v.u1_4.value="";  // 승계4차
		v.u1_5.value="";  // 승계5차
		v.u1_6.value="";  // 승계6차
	}

}

</script>


<div id="content">

  <div id="content-header">
    <?if($mode=="e"){?>
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">원데이터</a> <a href="#" class="current">최초입력 수정</a> </div>
    <?}else{//신규일때
    ?>
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">원데이터</a> <a href="#" class="current">최초입력</a> </div>
    <?}?>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">

			<div  style="width:100%;background-color:#EFEFEF;border-top:1px solid #e7e7e7;border-left:1px solid #e7e7e7;border-right:1px solid #e7e7e7;">

      <form name="ff" action="post.php" method="post" class="form-horizontal" onsubmit="return f_submit();">
			<input type="hidden" name="mode" id="mode" value="<?=$mode?>">
			<input type="hidden" name="a1" id="a1" value="<?=$row[a1]?>">
			<input type="hidden" name="s_point" id="s_point" value="<?=$s_point?>">

		 		<div id="breadcrumb" style="text-align:left;background-color:#EFEFEF;"><h3>&nbsp;▷ 1단계 기본정보 입력</h3></div>
		 		<?if($mode=="e"){ ?>
		 		<div id="breadcrumb" style="text-align:right;background-color:#EFEFEF;"><h5>고유번호 : &nbsp;<?=$row[a1]?>&nbsp;&nbsp;&nbsp;</h3></div>
				<?}?>

				<table style="width:99%;margin:10px;border:1px solid whtie;" border=1>
				<tr>
					<td style="text-align:center;">* 현장</td>
					<td style="background-color:white;">&nbsp;&nbsp;&nbsp;
						<select name="h_idx" id="h_idx" <?if($mode=="e"){?>   <? } ?> onchange="javascript:select_danji();f_h_idx_cnt();f_a1_make();" style="width:70%;"  <?if($mode=="e"){ ?> readonly <?}?>>
							<?if($mode=="i"){?>
							<option value="">--선택--</option>
							<?}?>
							<?
							if($row[h_idx]==""){
								$sql = "select * from tbl_hyunjang_info";
							}else{
								$sql = "select * from tbl_hyunjang_info where h_idx='{$row[h_idx]}'";
							}

							$sosok_r = $pdo->prepare($sql);
							$sosok_r->execute();
							while($rr = $sosok_r->fetch()){?>
								<?if($mode=="i"){?>
									<option value="<?=$rr[h_idx]?>" <?if($rr[h_idx]==$h_idx){?>selected<?}?>><?=$rr[h_name]?></option>
								<?} else {?>
									<option value="<?=$rr[h_idx]?>" <?if($rr[h_idx]==$row[h_idx]){?>selected<?}?>><?=$rr[h_name]?></option>
								<?}?>
							<?}?>
						</select>
							<button type="button" class="btn btn-success" onclick="javascript:f_hyunjang();" style="background-color:#F29661;font-size:8pt;height:26px;">현장추가</button>
					</td>
					<td style="text-align:center;background-color:#98FB98;">취득세법적용</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name=tax_apply id="tax_apply" style="width:90%;">
					  <option value="1" <?if($row[tax_apply]=="1"){?>selected<?}?>>구법적용</option>
					  <option value="2" <?if($row[tax_apply]=="2"){?>selected<?}?>>신법(2020.8.12시행)적용</option>
					  </select>
					</td>
					<td style="text-align:center;background-color:#98FB98;">단지</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name="b1" id="b1" style='width:70%;'>
							<?
							if($row[h_idx]!=""||$mode=="i"){
								if($mode=="i"){
									$sql = "select danji_name as b1, danji_name from tbl_hyunjang_danji_info where 1=1 and h_idx = '{$h_idx}' order by danji_name asc  ";
								} else {
									$sql = "select danji_name as b1, danji_name from tbl_hyunjang_danji_info where 1=1 and h_idx = '{$row[h_idx]}' order by danji_name asc  ";
								}
								$sosok_r = $pdo->prepare($sql);
								$sosok_r->execute();
								while($rr = $sosok_r->fetch()){?>
								<?if($mode=="i"){?>
									<option value="<?=$rr[b1]?>" <?if($rr[b1]==$b1){?>selected<?}?>><?=$rr[danji_name]?></option>
								<?} else {?>
									<option value="<?=$rr[b1]?>" <?if($rr[b1]==$row[b1]){?>selected<?}?>><?=$rr[danji_name]?></option>
								<?}?>
							<?}
							}?>
						</select>
						<button type="button" class="btn btn-success" onclick="javascript:f_hyunjang_danji();"  style="background-color:#F29661;font-size:8pt;height:26px;">단지추가</button>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;">* 서류수령일</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<?if($mode=="i"){ ?>
						<input type=text name="doc_receive_date" id="doc_receive_date" value="<?=$doc_receive_date?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;">
						<?} else {?>
						<input type=text name="doc_receive_date" id="doc_receive_date" value="<?=$row[doc_receive_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;">
						<?}?>
					</td>
					<td style="text-align:center;background-color:#98FB98;">취득유형</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name=apply_type id="apply_type" style="width:90%;">
					  <option value="1" <?if($row[apply_type]=="1"){?>selected<?}?>>아파트</option>
					  <option value="2" <?if($row[apply_type]=="2"){?>selected<?}?>>오피스텔</option>
					  <option value="3" <?if($row[apply_type]=="3"){?>selected<?}?>>상가</option>
					  </select>
					</td>
					<td style="text-align:center;">특이사항</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<select name=point_data id="point_data" onchange="point_etc(this.value);" style="width:30%;">
					  <option value="">--선택--</option>
					  <option value="1" <?if($row[point_data]=="1"){?>selected<?}?>>이전ONLY</option>
					  <option value="2" <?if($row[point_data]=="2"){?>selected<?}?>>설정ONLY</option>
					  <option value="3" <?if($row[point_data]=="3"){?>selected<?}?>>기타</option>
					  </select>
						&nbsp;&nbsp;&nbsp;<input type=text name="point_data_name" id="point_data_name" value="<?=$row[point_data_name]?>" style="width:40%;" >
					</td>
				</tr>
				<tr>
					<td style="text-align:center;">* 동</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="h1" id="h1" value="<?=$row[h1]?>" style="width:90%;"  <?if($mode=="e"){ ?> readonly <?}?>>&nbsp;&nbsp;&nbsp;</td>
					<td style="text-align:center;">* 호</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="i1" id="i1" value="<?=$row[i1]?>" style="width:90%;"  <?if($mode=="e"){ ?> readonly <?}?>>&nbsp;&nbsp;&nbsp;</td>
					<td style="text-align:center;">취득자수</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name=apply_count id=apply_count onchange="a_cnt(this.value)" style="width:90%;">
					  <option value="1" <?if($row[apply_count]=="1"){?>selected<?}?>>1</option>
					  <option value="2" <?if($row[apply_count]=="2"){?>selected<?}?>>2</option>
					  </select>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;">전화1</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="p1" id="p1" value="<?=$row[p1]?>" style="width:90%;">&nbsp;&nbsp;&nbsp;</td>
					<td style="text-align:center;">전화2</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="p2" id="p2" value="<?=$row[p2]?>" style="width:90%;">&nbsp;&nbsp;&nbsp;</td>
					</td>
					<td style="text-align:center;">계약일</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="contract_date" id="contract_date" value="<?=$row[contract_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"></td>
				</tr>
				<tr>
					<td style="text-align:center;">잔금일</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="balance_date" id="balance_date" value="<?=$row[balance_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"></td>
					<td style="text-align:center;">준공일</td>
					<td style="text-align:center;background-color:white;">&nbsp;&nbsp;&nbsp;<input type=text name="jungong_date" id="jungong_date" value="<?=f_date_yyyymmdd2($r1[jungong_date])?>" style="width:120px;text-align:center;" readonly></td>
					<td style="text-align:center;">취득세신고만료일</td>
					<td style="text-align:center;background-color:white;">&nbsp;&nbsp;&nbsp;<input type=text name="tax_end_date" id="tax_end_date" value="<?=f_date_yyyymmdd2($row[tax_end_date])?>" style="width:120px;text-align:center;" readonly></td>
				</tr>
				<tr>
					<td style="text-align:center;">취득자1</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="j1" id="j1" onchange="f_owner_cnt()" value="<?=$row[j1]?>" style="width:90%;">&nbsp;&nbsp;&nbsp;</td>
					<td style="text-align:center;">주민번호1</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="k1" id="k1" value="<?=$row[k1]?>" style="width:90%;">&nbsp;&nbsp;&nbsp;</td>
					<td style="text-align:center;">취득자1지분</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="j1_stake" id="j1_stake" value="<?=$row[j1_stake]?>" style="width:90%;">&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:center;">주소1</td>
					<td colspan=5 style="background-color:white">&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;<input type=text name="l1" id="l1" value="<?=$row[l1]?>" style="width:90%;">&nbsp;&nbsp;&nbsp;
					</td>
				</tr>

				<tr name="a_cnt1" id="a_cnt1">
					<td style="text-align:center;">취득자2</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="m1" id="m1" onchange="f_owner_cnt()" value="<?=$row[m1]?>" style="width:90%;">&nbsp;&nbsp;&nbsp;</td>
					<td style="text-align:center;">주민번호2</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="n1" id="n1" value="<?=$row[n1]?>" style="width:90%;">&nbsp;&nbsp;&nbsp;</td>
					<td style="text-align:center;">취득자2지분</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="m1_stake" id="m1_stake" value="<?=$row[m1_stake]?>" style="width:90%;">&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr name="a_cnt2" id="a_cnt2">
					<td style="text-align:center;">주소2</td>
					<td colspan=5 style="background-color:white">&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;<input type=text name="o1" id="o1" value="<?=$row[o1]?>" style="width:80%;">&nbsp;&nbsp;&nbsp;<input type=checkbox name=m1_ch id="m1_ch" onClick="f_o1_copy(this, '<?=$o1?>')" value="y" >&nbsp;&nbsp;&nbsp;취득자1과 동일&nbsp;&nbsp;
					</td>
				</tr>
			
				<tr>
					<td style="text-align:center;">타입</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;<input type=text name="w1" id="w1" value="<?=$row[w1]?>" style="width:50%;">&nbsp;&nbsp;&nbsp;<input type=checkbox name=w1_ch id="w1_ch" onClick="f_w1_copy(this, '<?=$w1?>')" value="y" >&nbsp;&nbsp;&nbsp;직전입력과 동일&nbsp;&nbsp;
					</td>
					<td style="text-align:center;">계약서상건물면적</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="con_building_area" id="con_building_area" maxlength=20 style="text-align:right;width:50%;" value="<?=f_money4($row[con_building_area])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;㎡&nbsp;&nbsp;&nbsp;<input type=checkbox name=con_building_area_ch id="con_building_area_ch" onClick="f_con_building_area_copy(this, '<?=$con_building_area?>')" value="y" >&nbsp;&nbsp;&nbsp;직전입력과 동일&nbsp;&nbsp;
					</td>
					<td style="text-align:center;">계약서상토지면적</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="con_land_area" id="con_land_area" maxlength=20 style="text-align:right;width:50%;" value="<?=f_money4($row[con_land_area])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;㎡&nbsp;&nbsp;&nbsp;<input type=checkbox name=con_building_area_ch id="con_land_area_ch" onClick="f_con_land_area_copy(this, '<?=$con_land_area?>')" value="y" >&nbsp;&nbsp;&nbsp;직전입력과 동일&nbsp;&nbsp;
					</td>
				</tr>

				<tr>
					<td style="text-align:center;">회원여부</td>
					<td style="text-align:center;background-color:white;">&nbsp;&nbsp;&nbsp;<input type=text name="c1" id="c1" value="<?=f_member_value($row[a1])?>" style="width:40%;" readonly></td>
					<td style="text-align:center;">취득세감면사유</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name=tax_cut_cause id="tax_cut_cause" onchange="tax_cut_etc(this.value);" style="width:40%;">
					  <option value="">--선택--</option>
					  <option value="0" <?if($row[tax_cut_cause]=="0"){?>selected<?}?>>취감면전체</option>
					  <option value="1" <?if($row[tax_cut_cause]=="1"){?>selected<?}?>>나라사랑대출</option>
					  <option value="2" <?if($row[tax_cut_cause]=="2"){?>selected<?}?>>신혼부부</option>
					  <option value="3" <?if($row[tax_cut_cause]=="3"){?>selected<?}?>>생애최초</option>
					  <option value="4" <?if($row[tax_cut_cause]=="4"){?>selected<?}?>>임대사업자</option>
					  <option value="5" <?if($row[tax_cut_cause]=="5"){?>selected<?}?>>이전공공기관임직원</option>
					  <option value="6" <?if($row[tax_cut_cause]=="6"){?>selected<?}?>>유치원</option>
					  <option value="7" <?if($row[tax_cut_cause]=="7"){?>selected<?}?>>기타</option>
					  </select>
						&nbsp;&nbsp;&nbsp;<input type=text name="tax_cut_cause_name" id="tax_cut_cause_name" value="<?=$row[tax_cut_cause_name]?>" style="width:40%;" >
					</td>
					<td style="text-align:center;background-color:#98FB98;">다주택여부</td>
					<td style="background-color:white">
						<select name=multi_housing_type id="multi_housing_type" style="width:90%;">
					  <option value="1" <?if($row[multi_housing_type]=="1"){?>selected<?}?>>1주택</option>
					  <option value="2" <?if($row[multi_housing_type]=="2"){?>selected<?}?>>일시적2주택</option>
					  <option value="3" <?if($row[multi_housing_type]=="3"){?>selected<?}?>>2주택</option>
					  <option value="4" <?if($row[multi_housing_type]=="4"){?>selected<?}?>>3주택</option>
					  <option value="5" <?if($row[multi_housing_type]=="5"){?>selected<?}?>>4주택이상</option>
					  <option value="6" <?if($row[multi_housing_type]=="6"){?>selected<?}?>>법인</option>
					  </select>
					</td>
				</tr>
				</table>
					<H5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;◈ (분양권, 입주권 주택수에 포함, 오피스텔 입주권은 주택수포함 안됨. 나중에 준공후 주택오피스텔인 경우만 주택에 포함됨)<H5/>
				<br>
<?if($_SESSION["admin_permission"][ch_c12]=="y"){?>
				<div style="text-align:right;" >
					<button type="button" class="btn btn-success" onclick="javascript:f_submit(1);">1단계입력완료</button>&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-success" onclick="javascript:f_chogi(1);">다시작성</button>&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
<? } ?>
				<br>
		 		<div id="breadcrumb" style="text-align:left;background-color:#EFEFEF;"><h3>&nbsp;▷ 2단계 승계정보 및 계약금액 정보 입력</h3></div>

				<table style="width:99%;margin:10px;border:1px solid whtie;" border=1>
				<tr>
					<td style="text-align:center;">승계여부</td>
					<td style="background-color:white;" colspan="3">&nbsp;&nbsp;&nbsp;
						<select name=u1 id="u1" onchange="u1_cnt(this.value);" style="width:10%;">
					  <option value="">--선택--</option>
					  <option value="0" <?if($row[u1]=="0"){?>selected<?}?>>없음</option>
					  <option value="1" <?if($row[u1]=="1"){?>selected<?}?>>1회</option>
					  <option value="2" <?if($row[u1]=="2"){?>selected<?}?>>2회</option>
					  <option value="3" <?if($row[u1]=="3"){?>selected<?}?>>3회</option>
					  <option value="4" <?if($row[u1]=="4"){?>selected<?}?>>4회</option>
					  <option value="5" <?if($row[u1]=="5"){?>selected<?}?>>5회</option>
					  <option value="6" <?if($row[u1]=="6"){?>selected<?}?>>6회</option>
					  </select>
						<select name=u1_1 id="u1_1" style="width:10%;">
					  <option value="">--1차선택--</option>
					  <option value="전매" <?if($row[u1_1]=="전매"){?>selected<?}?>>전매</option>
					  <option value="증여" <?if($row[u1_1]=="증여"){?>selected<?}?>>증여</option>
					  <option value="상속" <?if($row[u1_1]=="상속"){?>selected<?}?>>상속</option>
					  <option value="기타" <?if($row[u1_1]=="기타"){?>selected<?}?>>기타</option>
					  </select>
						<select name=u1_2 id="u1_2" style="width:10%;">
					  <option value="">--2차선택--</option>
					  <option value="전매" <?if($row[u1_2]=="전매"){?>selected<?}?>>전매</option>
					  <option value="증여" <?if($row[u1_2]=="증여"){?>selected<?}?>>증여</option>
					  <option value="상속" <?if($row[u1_2]=="상속"){?>selected<?}?>>상속</option>
					  <option value="기타" <?if($row[u1_2]=="기타"){?>selected<?}?>>기타</option>
					  </select>
						<select name=u1_3 id="u1_3" style="width:10%;">
					  <option value="">--3차선택--</option>
					  <option value="전매" <?if($row[u1_3]=="전매"){?>selected<?}?>>전매</option>
					  <option value="증여" <?if($row[u1_3]=="증여"){?>selected<?}?>>증여</option>
					  <option value="상속" <?if($row[u1_3]=="상속"){?>selected<?}?>>상속</option>
					  <option value="기타" <?if($row[u1_3]=="기타"){?>selected<?}?>>기타</option>
					  </select>
						<select name=u1_4 id="u1_4" style="width:10%;">
					  <option value="">--4차선택--</option>
					  <option value="전매" <?if($row[u1_4]=="전매"){?>selected<?}?>>전매</option>
					  <option value="증여" <?if($row[u1_4]=="증여"){?>selected<?}?>>증여</option>
					  <option value="상속" <?if($row[u1_4]=="상속"){?>selected<?}?>>상속</option>
					  <option value="기타" <?if($row[u1_4]=="기타"){?>selected<?}?>>기타</option>
					  </select>
						<select name=u1_5 id="u1_5" style="width:10%;">
					  <option value="">--5차선택--</option>
					  <option value="전매" <?if($row[u1_5]=="전매"){?>selected<?}?>>전매</option>
					  <option value="증여" <?if($row[u1_5]=="증여"){?>selected<?}?>>증여</option>
					  <option value="상속" <?if($row[u1_5]=="상속"){?>selected<?}?>>상속</option>
					  <option value="기타" <?if($row[u1_5]=="기타"){?>selected<?}?>>기타</option>
					  </select>
						<select name=u1_6 id="u1_6" style="width:10%;">
					  <option value="">--6차선택--</option>
					  <option value="전매" <?if($row[u1_6]=="전매"){?>selected<?}?>>전매</option>
					  <option value="증여" <?if($row[u1_6]=="증여"){?>selected<?}?>>증여</option>
					  <option value="상속" <?if($row[u1_6]=="상속"){?>selected<?}?>>상속</option>
					  <option value="기타" <?if($row[u1_6]=="기타"){?>selected<?}?>>기타</option>
					  </select>
					</td>
					<td style="text-align:center;">프리미엄</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="pre_cost" id="pre_cost" maxlength=20 style="text-align:right;width:80%;" value="<?=f_money($row[pre_cost])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;원&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:center;">실거래일자<br>(마지막전매)</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="sil_last_date" id="sil_last_date" value="<?=$row[sil_last_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"></td>
					<td style="text-align:center;">거래신고일련번호<br>(마지막전매)</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="singo_last_no" id="singo_last_no" value="<?=$row[singo_last_no]?>" style="width:90%;">&nbsp;&nbsp;&nbsp;</td>
					<td style="text-align:center;">거래신고금액<br>(마지막전매)</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="singo_last_cost" id="singo_last_cost" maxlength=20 style="text-align:right;width:80%;" value="<?=f_money($row[singo_last_cost])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;원&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:center;background-color:#98FB98;">원분양자신고대상</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name=singo_gubun id="singo_gubun" style="width:120px;">
					  <option value="">--선택--</option>
					  <option value="실거래" <?if($row[singo_gubun]==""){if(f_won_taget($row[contract_date])=="실거래"){?>selected<?}} else {if($row[singo_gubun]=="실거래"){?>selected<?}}?>>실거래</option>
					  <option value="검인" <?if($row[singo_gubun]==""){if(f_won_taget($row[contract_date])=="검인"){?>selected<?}} else {if($row[singo_gubun]=="검인"){?>selected<?}}?>>검인</option>
					  </select>
					</td>
					<td style="text-align:center;">CS비고</td>
					<td style="background-color:white;" colspan=3>&nbsp;&nbsp;&nbsp;<textarea rows=4 class="span11" name="ad1" id="ad1" ><?=$row[ad1]?></textarea></td>
				</tr>
				<tr>
					<td style="text-align:center;">분양가</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="bunyang_cost" id="bunyang_cost" maxlength=20 style="text-align:right;width:80%;" value="<?=f_money($row[bunyang_cost])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;원&nbsp;&nbsp;&nbsp;</td>
					<td style="text-align:center;">발코니</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="balkoni_cost" id="balkoni_cost" maxlength=20 style="text-align:right;width:80%;" value="<?=f_money($row[balkoni_cost])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;원&nbsp;&nbsp;&nbsp;</td>
					<td style="text-align:center;">옵션1</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="option1_cost" id="option1_cost" maxlength=20 style="text-align:right;width:80%;" value="<?=f_money($row[option1_cost])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;원&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:center;">옵션2</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="option2_cost" id="option2_cost" maxlength=20 style="text-align:right;width:80%;" value="<?=f_money($row[option2_cost])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;원&nbsp;&nbsp;&nbsp;</td>
					<td style="text-align:center;">옵션3</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="option3_cost" id="option3_cost" maxlength=20 style="text-align:right;width:80%;" value="<?=f_money($row[option3_cost])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;원&nbsp;&nbsp;&nbsp;</td>
					<td style="text-align:center;">옵션4</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="option4_cost" id="option4_cost" maxlength=20 style="text-align:right;width:80%;" value="<?=f_money($row[option4_cost])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;원&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:center;">할인액</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="discount_cost" id="discount_cost" maxlength=20 style="text-align:right;width:80%;" value="<?=f_money($row[discount_cost])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;원&nbsp;&nbsp;&nbsp;</td>
					<td style="text-align:center;">부가세</td>
					<td style="background-color:white" colspan=3>&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="vat" id="vat" maxlength=20 style="text-align:right;width:33%;" value="<?=f_money($row[vat])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;원&nbsp;&nbsp;&nbsp;</td>
				</tr>
				</table>
				<br>
<?if($_SESSION["admin_permission"][ch_c12]=="y"){?>
				<div style="text-align:right;" >
					<button type="button" class="btn btn-success" onclick="javascript:f_submit(2);">2단계입력완료</button>&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-success" onclick="javascript:f_chogi(2);">다시작성</button>&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
<? } ?>


				<br>
		 		<div id="breadcrumb" style="text-align:left;background-color:#EFEFEF;"><h3>&nbsp;▷ 3단계 서류정보 입력</h3></div>

				<table style="width:99%;margin:10px;border:1px solid whtie;" border=1>
				<tr>
					<td style="text-align:center;">현장별 필요서류<br>및<br>특이사항</td>
					<td style="background-color:white" colspan=5>&nbsp;&nbsp;&nbsp;
							<button type="button" class="btn btn-success" onclick="javascript:f_hyunjang_url('<?=$r1[faq_link]?>');" style="background-color:#F29661;font-size:15pt;height:40px;width:90%;"><?=$r1[faq_link]?></button>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;">미비서류<br>(기본서류)</td>
					<td style="background-color:white;" >&nbsp;&nbsp;&nbsp;<textarea rows=4 class="span11" name="mibi_doc" id="mibi_doc" ><?=$row[mibi_doc]?></textarea></td>
					<td style="text-align:center;">미비서류<br>히스토리</td>
					<td style="background-color:white;" colspan=3>&nbsp;&nbsp;&nbsp;<textarea rows=4 class="span11" name="mibi_doc_his" id="mibi_doc_his" ><?=$row[mibi_doc_his]?></textarea></td>
				</tr>
				<tr>
					<td style="text-align:center;">취득자<br>인감발행일</td>
					<td style="background-color:white">
						<table >
						<tr name="ingam_1" id="ingam_1">
							<td>
								&nbsp;&nbsp;&nbsp;취득자1인감 
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;<input type=text name="j1_ingam_date" id="j1_ingam_date" value="<?=$row[j1_ingam_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;">
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;<input type=text name="j1_ingam_limit_date" id="j1_ingam_limit_date" value="<?=$row[j1_ingam_limit_date]?>" style="width:70px;height:15px;" readonly >(유효기간)
							</td>
						</tr>
						<tr name="a_cnt3" id="a_cnt3">
							<td>
								&nbsp;&nbsp;&nbsp;취득자2인감 
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;<input type=text name="m1_ingam_date" id="m1_ingam_date" value="<?=$row[m1_ingam_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;">
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;<input type=text name="m1_ingam_limit_date" id="m1_ingam_limit_date" value="<?=$row[m1_ingam_limit_date]?>" style="width:70px;height:15px;" readonly >(유효기간)
							</td>
						</tr>
						</table>
					</td>
					<td style="text-align:center;">취득자<br>초본발행일</td>
					<td style="background-color:white">
						<table >
						<tr>
							<td>
								&nbsp;&nbsp;&nbsp;취득자1초본 
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;<input type=text name="j1_chobon_date" id="j1_chobon_date" value="<?=$row[j1_chobon_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;">
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;<input type=text name="j1_chobon_limit_date" id="j1_chobon_limit_date" value="<?=$row[j1_chobon_limit_date]?>" style="width:70px;height:15px;" readonly >(유효기간)
							</td>
						</tr>
						<tr name="a_cnt4" id="a_cnt4">
							<td>
								&nbsp;&nbsp;&nbsp;취득자2초본 
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;<input type=text name="m1_chobon_date" id="m1_chobon_date" value="<?=$row[m1_chobon_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;">
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;<input type=text name="m1_chobon_limit_date" id="m1_chobon_limit_date" value="<?=$row[m1_chobon_limit_date]?>" style="width:70px;height:15px;" readonly >(유효기간)
							</td>
						</tr>
						</table>
					</td>
					<td style="text-align:center;">기타서류<br>발행일</td>
					<td style="background-color:white">
						<table >
						<tr>
							<td>
						&nbsp;&nbsp;&nbsp;기타1 
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;<input type=text name="j1_etc_name" id="j1_etc_name" value="<?=$row[j1_etc_name]?>" style="width:120px;"> 
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;<input type=text name="j1_etc_date" id="j1_etc_date" value="<?=$row[j1_etc_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;">
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;<input type=text name="j1_etc_limit_date" id="j1_etc_limit_date" value="<?=$row[j1_etc_limit_date]?>" style="width:70px;height:15px;" readonly >(유효기간)
							</td>
						</tr>
						<tr name="a_cnt5" id="a_cnt5">
							<td>
								&nbsp;&nbsp;&nbsp;기타2 
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;<input type=text name="m1_etc_name" id="m1_etc_name" value="<?=$row[m1_etc_name]?>" style="width:120px;"> 
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;<input type=text name="m1_etc_date" id="m1_etc_date" value="<?=$row[m1_etc_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;">
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;<input type=text name="m1_etc_limit_date" id="m1_etc_limit_date" value="<?=$row[m1_etc_limit_date]?>" style="width:70px;height:15px;" readonly >(유효기간)
							</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;">주민등록등본<br>발행일</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="jumin_dngbon_date" id="jumin_dngbon_date" value="<?=$row[jumin_dngbon_date]?>""  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"></td>
					<td style="text-align:center;">가족관계증명서<br>발행일</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="family_date" id="family_date" value="<?=$row[family_date]?>""  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"></td>
					<td style="text-align:center;">서류비고</td>
					<td style="background-color:white;" >&nbsp;&nbsp;&nbsp;<textarea rows=3 class="span11" name="doc_memo" id="doc_memo" ><?=$row[doc_memo]?></textarea></td>
				</tr>
				</table>
				<br>
<?if($_SESSION["admin_permission"][ch_c12]=="y"){?>
				<div style="text-align:right;" >
					<button type="button" class="btn btn-success" onclick="javascript:f_submit(3);">3단계입력완료</button>&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-success" onclick="javascript:f_chogi(3);">다시작성</button>&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
<? } ?>
				<br>
				
				
		 		<div id="breadcrumb" style="text-align:left;background-color:#EFEFEF;"><h3>&nbsp;▷ 4단계 은행 설정 정보 입력</h3></div>

				<table style="width:99%;margin:10px;border:1px solid whtie;" border=1>
				<tr>
					<td style="text-align:center;">설정갯수</td>
					<td style="background-color:white" colspan=5>&nbsp;&nbsp;&nbsp;
						<select name=f1 id="f1" onchange="f1_cnt(this.value);" style="width:120px;">
					  <option value="0" <?if($row[f1]=="0"){?>selected<?}?>>0</option>
					  <option value="1" <?if($row[f1]=="1"){?>selected<?}?>>1</option>
					  <option value="2" <?if($row[f1]=="2"){?>selected<?}?>>2</option>
					  <option value="3" <?if($row[f1]=="3"){?>selected<?}?>>3</option>
					  <option value="4" <?if($row[f1]=="4"){?>selected<?}?>>4</option>
					  </select>
					</td>
				</tr>
				<tr name="cnt1_1" id="cnt1_1">
					<td style="text-align:center;" colspan=6>순위1</td>
				</tr>
				<tr name="cnt1_2" id="cnt1_2">
					<td style="text-align:center;">은행1</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name="bank_code_1" id="bank_code_1"  onchange="javascript:select_detail_all2('bank_code_1','jijum_code_1');f_bank_set();" style='width:250px;'>
							<option value="">--은행--</option>
							<?
							if($row[h_idx]!=""){
								//$sql = "select b.bank_code,b.bank_name from tbl_bank_info b left join tbl_junib j on b.bank_code=j.d1 where j.h_idx={$row[h_idx]} group by b.bank_code ";
								$sql = "select b.bank_code,b.bank_name from tbl_bank_jijum_rate a , tbl_bank_info b where a.bank_code=b.bank_code and a.h_idx = '{$row[h_idx]}' group by b.bank_code  order by b.bank_code,b.bank_name asc";
								//$sql = "select * from tbl_bank_info  ";
								$sosok_r = $pdo->prepare($sql);
								$sosok_r->execute();
								while($rr = $sosok_r->fetch()){?>
									<option value="<?=$rr[bank_code]?>" <?if($rr[bank_code]==$rows1[bank_code]){?>selected<?}?>><?=$rr[bank_name]?></option>
							<?}
							} ?>
						</select>
						<button type="button" class="btn btn-success" onclick="javascript:f_bank();"  style="background-color:#F29661;font-size:8pt;height:26px;">은행추가</button>
					</td>
					<td style="text-align:center;">지점1</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name="jijum_code_1" id="jijum_code_1" style='width:250px;'>
							<?
							if(($rows1[bank_code]!="")&&($row[h_idx]!="")){
								//$sql = "select b.jijum_code,b.jijum_name from tbl_bank_jijum b left join tbl_junib j on b.jijum_code=j.e1 where j.h_idx={$row[h_idx]} and j.d1='{$rows1[bank_code]}'  group by b.jijum_code order by b.jijum_code,b.jijum_name asc  ";
								$sql = "select b.jijum_code,b.jijum_name from tbl_bank_jijum_rate a , tbl_bank_jijum b where a.bank_code=b.bank_code and a.jijum_code=b.jijum_code and a.h_idx = '{$row[h_idx]}' and a.bank_code = '{$rows1[bank_code]}' group by b.jijum_code  order by b.jijum_code,b.jijum_name asc";
								//$sql = "select * from tbl_bank_jijum where bank_code='{$rows1[bank_code]}' ";
								$mm = 1;
							}
							if($mm==1){
								$sosok_r = $pdo->prepare($sql);
								$sosok_r->execute();?>
									<option value="">--지점--</option>
								<?while($rr = $sosok_r->fetch()){?>
									<option value="<?=$rr[jijum_code]?>" <?if($rr[jijum_code]==$rows1[jijum_code]){?>selected<?}?>><?=$rr[jijum_name]?></option>
								<?}?>
							<?}else{?>
									<option value="">--지점--</option>
							<?}?>
						</select>
						<button type="button" class="btn btn-success" onclick="javascript:f_bank_jijum();"  style="background-color:#F29661;font-size:8pt;height:26px;">지점추가</button>
					</td>
					<td style="text-align:center;">채권최고액1</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="av1" id="av1" maxlength=20 style="text-align:right;width:290px;" value="<?=f_money($row[av1])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;원</td>
				</tr>
				<tr name="cnt1_3" id="cnt1_3">
					<td style="text-align:center;">등기원인일1</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="reg_cause_date_1" id="reg_cause_date_1" value="<?=$rows1[reg_cause_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"></td>
					<td style="text-align:center;">감면율1</td>
					<td style="background-color:white" colspan=3>&nbsp;&nbsp;&nbsp;
						<select name=reduction_code_1 id="reduction_code_1" onchange="re_code_cnt(this.value, 1)" style="width:250px;">
					  <option value="">--선택--</option>
					  <option value="1" <?if($rows1[reduction_code]=="1"){?>selected<?}?>>나라사랑대출</option>
					  <option value="2" <?if($rows1[reduction_code]=="2"){?>selected<?}?>>기타</option>
					  </select>
					  <input type=text name="reduction_etc_name_1" id="reduction_etc_name_1" value="<?=$rows1[reduction_etc_name]?>" style="width:20%;">
					  <input type="text" class="span11" name="reduction_rate_1" id="reduction_rate_1" maxlength=3 style="text-align:right;width:10%;" value="<?=f_money($rows1[reduction_rate])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/> %감면
					</td>
				</tr>
				<tr name="cnt1_4" id="cnt1_4">
					<td style="text-align:center;">채무자1</td>
					<td style="background-color:white" colspan=3>&nbsp;&nbsp;&nbsp;<input type=text name="aw1" id="aw1" value="<?=$row[aw1]?>" style="width:80%;"></td>
					<td style="text-align:center;background-color:#98FB98;">채무자1주민번호</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="aw1_jumin" id="aw1_jumin" value="<?=$row[aw1_jumin]?>" maxlength=14 style="width:150px;"></td>
				</tr>
	
				<tr name="cnt2_1" id="cnt2_1">
					<td style="text-align:center;" colspan=6>&nbsp;&nbsp;&nbsp;순위2</td>
				</tr>
				<tr name="cnt2_2" id="cnt2_2">
					<td style="text-align:center;">은행2</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name="bank_code_2" id="bank_code_2"  onchange="javascript:select_detail_all2('bank_code_2','jijum_code_2');" style='width:250px;'>
							<option value="">--은행--</option>
							<?
							if($row[h_idx]!=""){
								//$sql = "select b.bank_code,b.bank_name from tbl_bank_info b left join tbl_junib j on b.bank_code=j.d1 where j.h_idx={$row[h_idx]} group by b.bank_code ";
								$sql = "select b.bank_code,b.bank_name from tbl_bank_jijum_rate a , tbl_bank_info b where a.bank_code=b.bank_code and a.h_idx = '{$row[h_idx]}' group by b.bank_code  order by b.bank_code,b.bank_name asc";
								//$sql = "select * from tbl_bank_info  ";
								$sosok_r = $pdo->prepare($sql);
								$sosok_r->execute();
								while($rr = $sosok_r->fetch()){?>
									<option value="<?=$rr[bank_code]?>" <?if($rr[bank_code]==$rows2[bank_code]){?>selected<?}?>><?=$rr[bank_name]?></option>
							<?}
							} ?>
						</select>
					</td>
					<td style="text-align:center;">지점2</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name="jijum_code_2" id="jijum_code_2" style='width:250px;'>
							<?
							if(($rows2[bank_code]!="")&&($row[h_idx]!="")){
								//$sql = "select b.jijum_code,b.jijum_name from tbl_bank_jijum b left join tbl_junib j on b.jijum_code=j.e1 where j.h_idx={$row[h_idx]} and j.d1='{$rows2[bank_code]}'  group by b.jijum_code order by b.jijum_code,b.jijum_name asc  ";
								$sql = "select b.jijum_code,b.jijum_name from tbl_bank_jijum_rate a , tbl_bank_jijum b where a.bank_code=b.bank_code and a.jijum_code=b.jijum_code and a.h_idx = '{$row[h_idx]}' and a.bank_code = '{$rows2[bank_code]}' group by b.jijum_code  order by b.jijum_code,b.jijum_name asc";
								//$sql = "select * from tbl_bank_jijum where bank_code='{$rows2[bank_code]}' ";
								$mm = 1;
							}
							if($mm==1){
								$sosok_r = $pdo->prepare($sql);
								$sosok_r->execute();?>
									<option value="">--지점--</option>
								<?while($rr = $sosok_r->fetch()){?>
									<option value="<?=$rr[jijum_code]?>" <?if($rr[jijum_code]==$rows2[jijum_code]){?>selected<?}?>><?=$rr[jijum_name]?></option>
								<?}?>
							<?}else{?>
									<option value="">--지점--</option>
							<?}?>
						</select>
					</td>
					<td style="text-align:center;">채권최고액2</td>
					<td style="background-color:white">
						&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="ax1" id="ax1" maxlength=20 style="text-align:right;width:290px;" value="<?=f_money($row[ax1])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;원
					</td>
				</tr>
				<tr name="cnt2_3" id="cnt2_3">
					<td style="text-align:center;">등기원인일2</td>
					<td style="background-color:white">
						&nbsp;&nbsp;&nbsp;<input type=text name="reg_cause_date_2" id="reg_cause_date_2" value="<?=$rows2[reg_cause_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;">
						&nbsp;&nbsp;&nbsp;<input type=checkbox name=reg_cause_date_2_ch id="reg_cause_date_2_ch" onClick="f_reg1_copy(this, 2)" value="y">&nbsp;&nbsp;&nbsp;등기원인일1과 동일&nbsp;&nbsp;	
					</td>
					<td style="text-align:center;">감면율2</td>
					<td style="background-color:white" colspan=3>&nbsp;&nbsp;&nbsp;
						<select name=reduction_code_2 id="reduction_code_2" onchange="re_code_cnt(this.value, 2)" style="width:250px;">
					  <option value="">--선택--</option>
					  <option value="1" <?if($rows2[reduction_code]=="1"){?>selected<?}?>>나라사랑대출</option>
					  <option value="2" <?if($rows2[reduction_code]=="2"){?>selected<?}?>>기타</option>
					  </select>
					  <input type=text name="reduction_etc_name_2" id="reduction_etc_name_2" value="<?=$rows2[reduction_etc_name]?>" style="width:20%;">
					  <input type="text" class="span11" name="reduction_rate_2" id="reduction_rate_2" maxlength=3 style="text-align:right;width:10%;" value="<?=f_money($rows2[reduction_rate])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/> %감면
					</td>
				</tr>
				<tr name="cnt2_4" id="cnt2_4">
					<td style="text-align:center;">채무자2</td>
					<td style="background-color:white" colspan=3>
						&nbsp;&nbsp;&nbsp;<input type=text name="aw2" id="aw2" value="<?=$row[aw2]?>" style="width:80%;">
						&nbsp;&nbsp;&nbsp;<input type=checkbox name=aw2_ch id="aw2_ch" onClick="f_chaemuja_1_copy(this, 2)" value="y" >&nbsp;&nbsp;&nbsp;채무자1과 동일&nbsp;&nbsp;	
					</td>
					<td style="text-align:center;background-color:#98FB98;">채무자2주민번호</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="aw2_jumin" id="aw2_jumin" value="<?=$row[aw2_jumin]?>" maxlength=14 style="width:150px;"></td>
				</tr>
				<tr name="cnt3_1" id="cnt3_1">
					<td style="text-align:center;" colspan=6>순위3</td>
				</tr>
				<tr name="cnt3_2" id="cnt3_2">
					<td style="text-align:center;">은행3</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name="bank_code_3" id="bank_code_3"  onchange="javascript:select_detail_all2('bank_code_3','jijum_code_3');" style='width:250px;'>
							<option value="">--은행--</option>
							<?
							if($row[h_idx]!=""){
								//$sql = "select b.bank_code,b.bank_name from tbl_bank_info b left join tbl_junib j on b.bank_code=j.d1 where j.h_idx={$row[h_idx]} group by b.bank_code ";
								$sql = "select b.bank_code,b.bank_name from tbl_bank_jijum_rate a , tbl_bank_info b where a.bank_code=b.bank_code and a.h_idx = '{$row[h_idx]}' group by b.bank_code  order by b.bank_code,b.bank_name asc";
								//$sql = "select * from tbl_bank_info  ";
								$sosok_r = $pdo->prepare($sql);
								$sosok_r->execute();
								while($rr = $sosok_r->fetch()){?>
									<option value="<?=$rr[bank_code]?>" <?if($rr[bank_code]==$rows3[bank_code]){?>selected<?}?>><?=$rr[bank_name]?></option>
							<?}
							} ?>
						</select>
					</td>
					<td style="text-align:center;">지점3</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name="jijum_code_3" id="jijum_code_3" style='width:250px;'>
							<?
							if(($rows3[bank_code]!="")&&($row[h_idx]!="")){
								//$sql = "select b.jijum_code,b.jijum_name from tbl_bank_jijum b left join tbl_junib j on b.jijum_code=j.e1 where j.h_idx={$row[h_idx]} and j.d1='{$rows1[bank_code]}'  group by b.jijum_code order by b.jijum_code,b.jijum_name asc  ";
								$sql = "select b.jijum_code,b.jijum_name from tbl_bank_jijum_rate a , tbl_bank_jijum b where a.bank_code=b.bank_code and a.jijum_code=b.jijum_code and a.h_idx = '{$row[h_idx]}' and a.bank_code = '{$rows3[bank_code]}' group by b.jijum_code  order by b.jijum_code,b.jijum_name asc";
								//$sql = "select * from tbl_bank_jijum where bank_code='{$rows3[bank_code]}' ";
								$mm = 1;
							}
							if($mm==1){
								$sosok_r = $pdo->prepare($sql);
								$sosok_r->execute();?>
									<option value="">--지점--</option>
								<?while($rr = $sosok_r->fetch()){?>
									<option value="<?=$rr[jijum_code]?>" <?if($rr[jijum_code]==$rows3[jijum_code]){?>selected<?}?>><?=$rr[jijum_name]?></option>
								<?}?>
							<?}else{?>
									<option value="">--지점--</option>
							<?}?>
						</select>
					</td>
					<td style="text-align:center;">채권최고액3</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="ay1" id="ay1" maxlength=20 style="text-align:right;width:290px;" value="<?=f_money($row[ay1])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;원</td>
				</tr>
				<tr name="cnt3_3" id="cnt3_3">
					<td style="text-align:center;">등기원인일3</td>
					<td style="background-color:white">
						&nbsp;&nbsp;&nbsp;<input type=text name="reg_cause_date_3" id="reg_cause_date_3" value="<?=$rows3[reg_cause_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;">
						&nbsp;&nbsp;&nbsp;<input type=checkbox name=reg_cause_date_3_ch id="reg_cause_date_3_ch" onClick="f_reg1_copy(this, 3)" value="y" >&nbsp;&nbsp;&nbsp;등기원인일1과 동일&nbsp;&nbsp;	
					</td>
					<td style="text-align:center;">감면율3</td>
					<td style="background-color:white" colspan=3>&nbsp;&nbsp;&nbsp;
						<select name=reduction_code_3 id="reduction_code_3" onchange="re_code_cnt(this.value, 3)" style="width:250px;">
					  <option value="">--선택--</option>
					  <option value="1" <?if($rows3[reduction_code]=="1"){?>selected<?}?>>나라사랑대출</option>
					  <option value="2" <?if($rows3[reduction_code]=="2"){?>selected<?}?>>기타</option>
					  </select>
					  <input type=text name="reduction_etc_name_3" id="reduction_etc_name_3" value="<?=$rows3[reduction_etc_name]?>" style="width:20%;">
					  <input type="text" class="span11" name="reduction_rate_3" id="reduction_rate_3" maxlength=3 style="text-align:right;width:10%;" value="<?=f_money($rows3[reduction_rate])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/> %감면
					</td>
				</tr>
				<tr name="cnt3_4" id="cnt3_4">
					<td style="text-align:center;">채무자3</td>
					<td style="background-color:white" colspan=3>
						&nbsp;&nbsp;&nbsp;<input type=text name="aw3" id="aw3" value="<?=$row[aw3]?>" style="width:80%;">
						&nbsp;&nbsp;&nbsp;<input type=checkbox name=aw3_ch id="aw3_ch" onClick="f_chaemuja_1_copy(this, 3)" value="y" >&nbsp;&nbsp;&nbsp;채무자1과 동일&nbsp;&nbsp;	
					</td>
					<td style="text-align:center;background-color:#98FB98;">채무자3주민번호</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="aw3_jumin" id="aw3_jumin" value="<?=$row[aw3_jumin]?>" maxlength=14 style="width:150px;"></td>
				</tr>


				<tr name="cnt4_1" id="cnt4_1">
					<td style="text-align:center;" colspan=6>순위4</td>
				</tr>
				<tr name="cnt4_2" id="cnt4_2">
					<td style="text-align:center;">은행4</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name="bank_code_4" id="bank_code_4"  onchange="javascript:select_detail_all2('bank_code_4','jijum_code_4');" style='width:250px;'>
							<option value="">--은행--</option>
							<?
							if($row[h_idx]!=""){
								//$sql = "select b.bank_code,b.bank_name from tbl_bank_info b left join tbl_junib j on b.bank_code=j.d1 where j.h_idx={$row[h_idx]} group by b.bank_code ";
								$sql = "select b.bank_code,b.bank_name from tbl_bank_jijum_rate a , tbl_bank_info b where a.bank_code=b.bank_code and a.h_idx = '{$row[h_idx]}' group by b.bank_code  order by b.bank_code,b.bank_name asc";
								//$sql = "select * from tbl_bank_info  ";
								//"select b.jijum_code,b.jijum_name from tbl_bank_jijum_rate a , tbl_bank_jijum b where a.bank_code=b.bank_code and a.jijum_code=b.jijum_code and a.h_idx = '{$h_idx}' and a.bank_code = '{$p1}' order by b.jijum_code,b.jijum_name asc";
								$sosok_r = $pdo->prepare($sql);
								$sosok_r->execute();
								while($rr = $sosok_r->fetch()){?>
									<option value="<?=$rr[bank_code]?>" <?if($rr[bank_code]==$rows4[bank_code]){?>selected<?}?>><?=$rr[bank_name]?></option>
							<?}
							} ?>
						</select>
					</td>
					<td style="text-align:center;">지점4</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name="jijum_code_4" id="jijum_code_4" style='width:250px;'>
							<?
							if(($rows4[bank_code]!="")&&($row[h_idx]!="")){
								//$sql = "select b.jijum_code,b.jijum_name from tbl_bank_jijum b left join tbl_junib j on b.jijum_code=j.e1 where j.h_idx={$row[h_idx]} and j.d1='{$rows1[bank_code]}'  group by b.jijum_code order by b.jijum_code,b.jijum_name asc  ";
								//$sql = "select * from tbl_bank_jijum where bank_code='{$rows4[bank_code]}' ";
								$sql = "select b.jijum_code,b.jijum_name from tbl_bank_jijum_rate a , tbl_bank_jijum b where a.bank_code=b.bank_code and a.jijum_code=b.jijum_code and a.h_idx = '{$row[h_idx]}' and a.bank_code = '{$rows4[bank_code]}' group by b.jijum_code  order by b.jijum_code,b.jijum_name asc";
								$mm = 1;
							}
							if($mm==1){
								$sosok_r = $pdo->prepare($sql);
								$sosok_r->execute();?>
									<option value="">--지점--</option>
								<?while($rr = $sosok_r->fetch()){?>
									<option value="<?=$rr[jijum_code]?>" <?if($rr[jijum_code]==$rows4[jijum_code]){?>selected<?}?>><?=$rr[jijum_name]?></option>
								<?}?>
							<?}else{?>
									<option value="">--지점--</option>
							<?}?>
						</select>
					</td>
					<td style="text-align:center;">채권최고액4</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="az1" id="az1" maxlength=20 style="text-align:right;width:290px;" value="<?=f_money($row[az1])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;원</td>
				</tr>
				<tr name="cnt4_3" id="cnt4_3">
					<td style="text-align:center;">등기원인일4</td>
					<td style="background-color:white">
						&nbsp;&nbsp;&nbsp;<input type=text name="reg_cause_date_4" id="reg_cause_date_4" value="<?=$rows4[reg_cause_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;">
						&nbsp;&nbsp;&nbsp;<input type=checkbox name=reg_cause_date_4_ch id="reg_cause_date_4_ch" onClick="f_reg1_copy(this, 4)" value="y" >&nbsp;&nbsp;&nbsp;등기원인일1과 동일&nbsp;&nbsp;	
					</td>
					<td style="text-align:center;">감면율4</td>
					<td style="background-color:white" colspan=3>&nbsp;&nbsp;&nbsp;
						<select name=reduction_code_4 id="reduction_code_4" onchange="re_code_cnt(this.value, 4)" style="width:250px;">
					  <option value="">--선택--</option>
					  <option value="1" <?if($rows4[reduction_code]=="1"){?>selected<?}?>>나라사랑대출</option>
					  <option value="2" <?if($rows4[reduction_code]=="2"){?>selected<?}?>>기타</option>
					  </select>
					  <input type=text name="reduction_etc_name_4" id="reduction_etc_name_4" value="<?=$rows4[reduction_etc_name]?>" style="width:20%;">
					  <input type="text" class="span11" name="reduction_rate_4" id="reduction_rate_4" maxlength=3 style="text-align:right;width:10%;" value="<?=f_money($rows4[reduction_rate])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/> %감면
					</td>
				</tr>
				<tr name="cnt4_4" id="cnt4_4">
					<td style="text-align:center;">채무자4</td>
					<td style="background-color:white" colspan=3>
						&nbsp;&nbsp;&nbsp;<input type=text name="aw4" id="aw4" value="<?=$row[aw4]?>" style="width:80%;">
						&nbsp;&nbsp;&nbsp;<input type=checkbox name=aw4_ch id="aw4_ch" onClick="f_chaemuja_1_copy(this, 4)" value="y" >&nbsp;&nbsp;&nbsp;채무자1과 동일&nbsp;&nbsp;	
					</td>
					<td style="text-align:center;background-color:#98FB98;">채무자4주민번호</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="aw4_jumin" id="aw4_jumin" value="<?=$row[aw4_jumin]?>" maxlength=14 style="width:150px;"></td>
				</tr>

				<tr>
					<td style="text-align:center;">소유자신분증</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;
						<select name=owner_id id="owner_id" onchange="f_owner_cnt(this.value)" style="width:30%;">
					  <option value="0" <?if($row[owner_id]=="0"){?>selected<?}?>>없음</option> 
					  <option value="1" <?if($row[owner_id]=="1"){?>selected<?}?>>취득자1</option>
					  <option value="2" <?if($row[owner_id]=="2"){?>selected<?}?>>취득자2</option>
					  <option value="3" <?if($row[owner_id]=="3"){?>selected<?}?>>취득자1,취득자2</option>
					  </select>
					  <input type=text name="owner_name" id="owner_name" value="<?=$row[owner_name]?>" style="width:20%;" readonly >
					</td>
					<td style="text-align:center;">배우자이름</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="s1" id="s1" value="<?=$row[s1]?>" style="width:85%;"></td>
					<td style="text-align:center;">은행비고</td>
					<td style="background-color:white;" >&nbsp;&nbsp;&nbsp;<textarea rows=3 class="span11" name="au1" id="au1" ><?=$row[au1]?></textarea></td>
				</tr>

				</table>
				<br>
<?if($_SESSION["admin_permission"][ch_c12]=="y"){?>
				<div style="text-align:right;" >
					<button type="button" class="btn btn-success" onclick="javascript:f_submit(4);">4단계입력완료</button>&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-success" onclick="javascript:f_chogi(4);">다시작성</button>&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
<? } ?>
				<br>
				
				
		 		<div id="breadcrumb" style="text-align:left;background-color:#EFEFEF;"><h3>&nbsp;▷ 5단계 신청서/위임장용 데이터 입력 (등기관리팀)</h3></div>

				<table style="width:99%;margin:10px;border:1px solid whtie;" border=1>
				<input type=hidden name="lot_amount" id="lot_amount" value="<?=$r2[lot_amount]?>">
				<input type=hidden name="lot_area_1" id="lot_area_1" value="<?=f_money4($r2[lot_area_1])?>">
				<input type=hidden name="lot_area_2" id="lot_area_2" value="<?=f_money4($r2[lot_area_2])?>">
				<input type=hidden name="lot_area_3" id="lot_area_3" value="<?=f_money4($r2[lot_area_3])?>">
				<input type=hidden name="lot_area_4" id="lot_area_4" value="<?=f_money4($r2[lot_area_4])?>">
				<input type=hidden name="lot_area_5" id="lot_area_5" value="<?=f_money4($r2[lot_area_5])?>">
				<input type=hidden name="lot_area_6" id="lot_area_6" value="<?=f_money4($r2[lot_area_6])?>">
				<input type=hidden name="lot_area_7" id="lot_area_7" value="<?=f_money4($r2[lot_area_7])?>">
				<input type=hidden name="lot_area_8" id="lot_area_8" value="<?=f_money4($r2[lot_area_8])?>">
				<input type=hidden name="lot_area_9" id="lot_area_9" value="<?=f_money4($r2[lot_area_9])?>">
				<input type=hidden name="lot_area_10" id="lot_area_10" value="<?=f_money4($r2[lot_area_10])?>">
				<tr>
					<td style="text-align:center;">등기상<br>건물면적</td>
					<td style="background-color:white">
					  &nbsp;&nbsp;&nbsp;<input type="text" class="span11" name="reg_building_area" id="reg_building_area" maxlength=20 style="text-align:right;width:40%;" value="<?=f_money4($row[reg_building_area])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>
						&nbsp;&nbsp;&nbsp;<input type=checkbox name=reg_building_area_ch id="reg_building_area_ch" onClick="f_toji_copy(this)" value="y" >&nbsp;&nbsp;&nbsp;계약상 건물면적과 동일&nbsp;&nbsp;
					</td>
					<td style="text-align:center;">등기상<br>토지면적<br>대지권의비율<br>(분자)</td>
					<td style="background-color:white">
						<table>
							<tr name="la_cnt1" id="la_cnt1">
								<td>
								  &nbsp;&nbsp;&nbsp;&nbsp;1. <input type="text" class="span11" name="reg_land_area1" id="reg_land_area1" maxlength=20 style="text-align:right;width:40%;" value="<?=f_money4($row[reg_land_area1])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>&nbsp;&nbsp;&nbsp;㎡&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=reg_land_area_ch id="reg_land_area_ch" onClick="f_lot_copy(this)" value="y" >&nbsp;&nbsp;&nbsp;계약상 토지면적과 동일<br>
								</td>
							</tr>
							<tr name="la_cnt2" id="la_cnt2">
								<td>
								  &nbsp;&nbsp;&nbsp;&nbsp;2. <input type="text" class="span11" name="reg_land_area2" id="reg_land_area2" maxlength=20 style="text-align:right;width:40%;" value="<?=f_money4($row[reg_land_area2])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>
								</td>
							</tr>
							<tr name="la_cnt3" id="la_cnt3">
								<td>
								  &nbsp;&nbsp;&nbsp;&nbsp;3. <input type="text" class="span11" name="reg_land_area3" id="reg_land_area3" maxlength=20 style="text-align:right;width:40%;" value="<?=f_money4($row[reg_land_area3])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>
								</td>
							</tr>
							<tr name="la_cnt4" id="la_cnt4">
								<td>
								  &nbsp;&nbsp;&nbsp;&nbsp;4. <input type="text" class="span11" name="reg_land_area4" id="reg_land_area4" maxlength=20 style="text-align:right;width:40%;" value="<?=f_money4($row[reg_land_area4])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>
								</td>
							</tr>
							<tr name="la_cnt5" id="la_cnt5">
								<td>
								  &nbsp;&nbsp;&nbsp;&nbsp;5. <input type="text" class="span11" name="reg_land_area5" id="reg_land_area5" maxlength=20 style="text-align:right;width:40%;" value="<?=f_money4($row[reg_land_area5])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>
								</td>
							</tr>
							<tr name="la_cnt6" id="la_cnt6">
								<td>
								  &nbsp;&nbsp;&nbsp;&nbsp;6. <input type="text" class="span11" name="reg_land_area6" id="reg_land_area6" maxlength=20 style="text-align:right;width:40%;" value="<?=f_money4($row[reg_land_area6])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>
								</td>
							</tr>
							<tr name="la_cnt7" id="la_cnt7">
								<td>
								  &nbsp;&nbsp;&nbsp;&nbsp;7. <input type="text" class="span11" name="reg_land_area7" id="reg_land_area7" maxlength=20 style="text-align:right;width:40%;" value="<?=f_money4($row[reg_land_area7])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>
								</td>
							</tr>
							<tr name="la_cnt8" id="la_cnt8">
								<td>
								  &nbsp;&nbsp;&nbsp;&nbsp;8. <input type="text" class="span11" name="reg_land_area8" id="reg_land_area8" maxlength=20 style="text-align:right;width:40%;" value="<?=f_money4($row[reg_land_area8])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>
								</td>
							</tr>
							<tr name="la_cnt9" id="la_cnt9">
								<td>
								  &nbsp;&nbsp;&nbsp;&nbsp;9. <input type="text" class="span11" name="reg_land_area9" id="reg_land_area9" maxlength=20 style="text-align:right;width:40%;" value="<?=f_money4($row[reg_land_area9])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>
								</td>
							</tr>
							<tr name="la_cnt10" id="la_cnt10">
								<td>
								  &nbsp;&nbsp;&nbsp;10. <input type="text" class="span11" name="reg_land_area10" id="reg_land_area10" maxlength=20 style="text-align:right;width:40%;" value="<?=f_money4($row[reg_land_area10])?>" onkeyup="onlyNum(this);this.value=this.value.comma();"/>
								</td>
							</tr>
						</table>
					</td>
					<td style="text-align:center;">건물신탁<br>접수일자</td>
					<td style="background-color:white">
						&nbsp;&nbsp;&nbsp;<input type=text name="building_trust_date" id="building_trust_date" value="<?=$row[building_trust_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;">
					</td>
				</tr>
				<tr>
					<td style="text-align:center;">건물신탁<br>접수번호</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="building_trust_no" id="building_trust_no" value="<?=$row[building_trust_no]?>" style="width:250px;"></td>
					<td style="text-align:center;">건물신탁<br>원부일자</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="building_trust_org_date" id="building_trust_org_date" value="<?=$row[building_trust_org_date]?>"  class="datepickx" size=8 maxlength=8 style="width:70px;height:15px;"></td>
					<td style="text-align:center;">건물신탁<br>원부번호</td>
					<td style="background-color:white">&nbsp;&nbsp;&nbsp;<input type=text name="building_trust_org_no" id="building_trust_org_no" value="<?=$row[building_trust_org_no]?>" style="width:250px;"></td>
				</tr>
				</table>
				<br>
<?if($_SESSION["admin_permission"][ch_c12]=="y"){?>
				<div style="text-align:right;" >
					<button type="button" class="btn btn-success" onclick="javascript:f_submit(5);">5단계입력완료</button>&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-success" onclick="javascript:f_chogi(5);">다시작성</button>&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
<? } ?>
				<br>

				<table style="width:100%;margin:10px;">
					<tr>
						<td>
							<div style="text-align:left;" >
								<button type="button" class="btn btn-success" onclick="javascript:location.href='regist.php?w1=<?=$row[w1]?>&con_building_area=<?=$row[con_building_area]?>&con_land_area=<?=$row[con_land_area]?>&h_idx=<?=$row[h_idx]?>&b1=<?=$row[b1]?>&doc_receive_date=<?=$row[doc_receive_date]?>';">신규</button>
<?if($_SESSION["admin_id"]=="master"){?>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<button type="button" class="btn btn-success" onclick="javascript:f_delete();">삭제</button>&nbsp;&nbsp;
<?}?>
							</div>
						</td>
						<td>
<?if($_SESSION["admin_permission"][ch_c12]=="y"){?>
							<div style="text-align:right;" >
								<button type="button" class="btn btn-success" onclick="javascript:f_submit(0);">저장</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</div>
<? } ?>
						</td>
					</tr>
				</table>
			</form>
			</div>
		</div>
  </div>
</div>

<!--bottom-시작-->
	<?include ("../../include/bottom.php");?>
<!--bottom-종료-->


<script src="/js/common.js"></script> 
<script src="/js/bootstrap.min.js"></script> 

<script>
	
function f_delete(){
	if(confirm("삭제하시겠습니까?")){
		location.href="/01_fdata/1_finput/delete_ok.php?a1="+encodeURI("<?=$a1?>");
	}
}

// 현장추가 버튼	
function f_hyunjang(){
	var url    ="/9_basic/94_hyunjang_info/regist.php";
	var title  = "listpoph";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1500, height=900, top=0,left=20"; 
	var a = window.open(url, title,status);
	a.focus();

}

// 단지추가 버튼	
function f_hyunjang_danji(){
	if(document.ff.h_idx.value==''){
		alert("현장을 선택해주세요.");
	}else{
		var p1 = document.ff.h_idx.value;
		var url    ="/9_basic/941_hyunjang_danji/regist.php?h_idx="+p1;
		var title  = "listpoph";
		var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1500, height=900, top=0,left=20"; 
		var a = window.open(url, title,status);
		a.focus();
	}
}

// 은행추가 버튼	
function f_bank(){
	var url    ="/9_basic/95_bank_info/index.html";
	var title  = "listpoph";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1500, height=900, top=0,left=20"; 
	var a = window.open(url, title,status);
	a.focus();

}

// 지점추가 버튼	
function f_bank_jijum(){
	var url    ="/9_basic/96_bank_jijum/index.html";
	var title  = "listpoph";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1500, height=900, top=0,left=20"; 
	var a = window.open(url, title,status);
	a.focus();

}

// 현장별 필요서류 및 특이사항	
function f_hyunjang_url(frm){
	var url    = frm
	var title  = "listpoph";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1000, height=500, top=0,left=20"; 
	var a = window.open(url, title,status);
	a.focus();

}

//소유자신분증 처리
function f_owner_cnt(frm){  
	var v = document.ff;
	if(frm==""){
		frm = v.owner_id.value;
	}
	if(frm=="0"){
		v.owner_name.value="";  // 소유자신분증명
	} else if(frm=="1"){
		if($("#j1").val()==""){
			alert("취득자1의 값이 없습니다.");
			$("#owner_id").val("0");
			v.owner_name.value="";
		}	else{
			$("#owner_name").val($("#j1").val());
		}
	} else if(frm=="2"){
		if($("#m1").val()==""){
			alert("취득자1의 값이 없습니다.");
			$("#owner_id").val("0");
			v.owner_name.value="";
		}	else{
			$("#owner_name").val($("#m1").val());
		}
	} else{
		if($("#j1").val()==""||$("#m1").val()==""){
			if($("#j1").val()==""&&$("#m1").val()==""){
				alert("취득자1,취득자2 의 값이 없습니다.");
				$("#owner_id").val("0");
				v.owner_name.value="";
			}else	if($("#j1").val()==""){
				alert("취득자1 의 값이 없습니다.");
				$("#owner_id").val("0");
				v.owner_name.value="";
			}else	if($("#m1").val()==""){
				alert("취득자2 의 값이 없습니다.");
				$("#owner_id").val("0");
				v.owner_name.value="";
			}
		}	else{
			$("#owner_name").val($("#j1").val()+","+$("#m1").val());
		}
	}
}


//취득자1 주소값과 동일 체크 처리
function f_a1_make(){  
	var v = document.ff;
	v.a1.value = f_a1_name(v.h_idx.value,v.h1.value,v.i1.value);

}

//유효기간 계산
function f_limits(){  
	var v = document.ff;
	//v.j1_ingam_limit_date.value = f_next_3month(v.j1_ingam_date.value);
  //alert(f_next_3month(v.j1_ingam_date.value));
}
//취득자1 주소값과 동일 체크 처리
function f_o1_copy(chk, frm){  
	var v = document.ff;
	if(chk.checked==true){
		if($("#l1").val()==""){
			alert("취득자1의 주소 값이 없습니다.");
			chk.checked=false;
		} else {
			$("#o1").val($("#l1").val());  // 

		}

	}

}

//타입 직전입력과 동일 체크 처리
function f_w1_copy(chk, frm){  
	var v = document.ff;
	if(chk.checked==true){
		if(frm==""){
			alert("타입의 직전입력 값이 없습니다.");
			chk.checked=false;
		} else {
			$("#w1").val(frm);  // 

		}

	}

}

//계약서상건물면적  직전입력과 동일 체크 처리
function f_con_building_area_copy(chk, frm){  
	var v = document.ff;
	if(chk.checked==true){
		if(frm==""){
			alert("계약서상건물면적의 직전입력 값이 없습니다.");
			chk.checked=false;
		} else {
			//frm == f_money4(frm);
			$("#con_building_area").val(frm);  // 

		}

	}

}

//계약서상토지면적 직전입력과 동일 체크 처리
function f_con_land_area_copy(chk, frm){  
	var v = document.ff;
	if(chk.checked==true){
		if(frm==""){
			alert("계약서상건물면적의 직전입력 값이 없습니다.");
			chk.checked=false;
		} else {
			$("#con_land_area").val(frm);  // 

		}

	}

}

function f_bank_set(){  //적용

alert($("select[name='bank_code_1']").val());
	if($("select[name='bank_code_1']").val()!=""){  //은행1

		if($("select[name='f1']").val()=="2"){
			if($("select[name='bank_code_2']").val()==""){
				$("select[name='bank_code_2']").val($("select[name='bank_code_1']").val());
			}
			$("select[name='bank_code_3']").val("");
			$("select[name='bank_code_4']").val("");
		} else if($("select[name='f1']").val()=="3"){
			if($("select[name='bank_code_2']").val()==""){
				$("select[name='bank_code_2']").val($("select[name='bank_code_1']").val());
			}
			if($("select[name='bank_code_3']").val()==""){
				$("select[name='bank_code_3']").val($("select[name='bank_code_1']").val());
			}
			$("select[name='bank_code_4']").val("");
		} else if($("select[name='f1']").val()=="4"){
			if($("select[name='bank_code_2']").val()==""){
				$("select[name='bank_code_2']").val($("select[name='bank_code_1']").val());
			}
			if($("select[name='bank_code_3']").val()==""){
				$("select[name='bank_code_3']").val($("select[name='bank_code_1']").val());
			}
			if($("select[name='bank_code_4']").val()==""){
				$("select[name='bank_code_4']").val($("select[name='bank_code_1']").val());
			}
		} else {
			
		}
	}

}


//등기원인일1 체크 처리
function f_reg1_copy(chk, frm){  
	var v = document.ff;
	if(chk.checked==true){
		if($("#reg_cause_date_1").val()==""){
			alert("등기원인일1 의 값이 없습니다.");
			chk.checked=false;
		} else {
			if(frm=="2"){
				$("#reg_cause_date_2").val($("#reg_cause_date_1").val());  // 
			} else if(frm=="3"){
				$("#reg_cause_date_3").val($("#reg_cause_date_1").val());  // 
			} else{
				$("#reg_cause_date_4").val($("#reg_cause_date_1").val());  // 
			}

		}

	}

}


//등기상 토지면적(계약상 토지면적과 동일 처리
function f_lot_copy(chk){  
	var v = document.ff;
	if(chk.checked==true){
			$("#reg_land_area1").val($("#lot_area_1").val());  // 
			$("#reg_land_area2").val($("#lot_area_2").val());  // 
			$("#reg_land_area3").val($("#lot_area_3").val());  // 
			$("#reg_land_area4").val($("#lot_area_4").val());  // 
			$("#reg_land_area5").val($("#lot_area_5").val());  // 
			$("#reg_land_area6").val($("#lot_area_6").val());  // 
			$("#reg_land_area7").val($("#lot_area_7").val());  // 
			$("#reg_land_area8").val($("#lot_area_8").val());  // 
			$("#reg_land_area9").val($("#lot_area_9").val());  // 
			$("#reg_land_area10").val($("#lot_area_10").val());  // 

	}

}

//등기상 건물면적(계약상 건물면적과 동일 처리
function f_toji_copy(chk){  
	var v = document.ff;
	if(chk.checked==true){
			$("#reg_building_area").val($("#con_building_area").val());  // 
	}

}

//채무자1 체크 처리
function f_chaemuja_1_copy(chk, frm){  
	var v = document.ff;
	if(chk.checked==true){
		if($("#aw1").val()==""){
			alert("채무자1 의 값이 없습니다.");
			chk.checked=false;
		} else {
			if(frm=="2"){
				$("#aw2").val($("#aw1").val());  // 
				$("#aw2_jumin").val($("#aw1_jumin").val());  // 
			} else if(frm=="3"){
				$("#aw3").val($("#aw1").val());  // 
				$("#aw3_jumin").val($("#aw1_jumin").val());  // 
			} else{
				$("#aw4").val($("#aw1").val());  // 
				$("#aw4_jumin").val($("#aw1_jumin").val());  // 
			}

		}

	}
}

function f_chogi(frm){  //초기화
	var v = document.ff;
	if(frm=="1"){
		if(confirm("1단계를 다시 작성 하시겠습니까?")){
			$("#m1_ch").prop("checked", false); //취득자1과 동일
			$("#w1_ch").prop("checked", false); // 타입 직전입력과 동일
			$("#con_building_area_ch").prop("checked", false); // 계약서상건물면적 직전입력과 동일
			$("#con_land_area_ch").prop("checked", false); // 계약서상토지면적 직전입력과 동일

			<? if($mode=="i"){?>
//				v.h_idx.value="";  // 현장
				v.h1.value="";  // 동
				v.i1.value="";  // 호
//				v.doc_receive_date.value=""; //서류수령일
			<?}?>
			v.tax_apply.value=""; // 취듣세법적용
			v.b1.value="";  // 단지
			v.apply_type.value="";  // 취득유형
			v.point_data.value=""; // 특이사항
			v.point_data_name.value=""; // 특이사항 기타입력
			v.apply_count.value="1";  // 취득자수
			v.p1.value="";  // 전화1
			v.p2.value="";  // 전화2
			v.contract_date.value="";  // 계약일
			v.balance_date.value="";  // 잔금일
			
//			v.h_idx.value="";  // 준공일
			
			v.tax_end_date.value="";  // 취득세신고만료일
			v.j1.value="";  // 취득자1
			v.k1.value="";  // 주민번호1
			v.j1_stake.value="";  // 취득자1지분
			v.l1.value="";  // 주소1
			v.m1.value="";  // 취득자2
			v.n1.value="";  // 주민번호2
			v.m1_stake.value="";  // 취득자2지분
			v.o1.value="";  // 주소2
			v.w1.value="";  // 타입
			v.con_building_area.value="";  // 계약서상건물면적
			v.con_land_area.value="";  // 계약서상토지면적
			v.c1.value="";  // 회원여부
			v.tax_cut_cause.value="";  // 취득세감면사유
			v.tax_cut_cause_name.value="";  // 취득세감면사유명
			v.multi_housing_type.value="";  // 다주택여부
			a_cnt(1); // 취득자2 영역 감추기
		}
	} else if(frm=="2"){
		if(confirm("2단계를 다시 작성 하시겠습니까?")){
			v.u1.value="";  // 승계여부
			v.u1_1.value="";  // 승계1차
			v.u1_2.value="";  // 승계2차
			v.u1_3.value="";  // 승계3차
			v.u1_4.value="";  // 승계4차
			v.u1_5.value="";  // 승계5차
			v.u1_6.value="";  // 승계6차
			v.pre_cost.value="";  // 프리미엄
			v.sil_last_date.value="";  // 실거래일자(마지막전매)
			v.singo_last_no.value="";  // 거래신고일련번호(마지막전매)
			v.singo_last_cost.value="";  // 거래신고금액(마지막전매)
			v.singo_gubun.value="";  // 원분양자신고대상
			v.ad1.value="";  // CS비고
			v.bunyang_cost.value="";  // 분양가
			v.balkoni_cost.value="";  // 발코니
			v.option1_cost.value="";  // 옵션1
			v.option2_cost.value="";  // 옵션2
			v.option3_cost.value="";  // 옵션3
			v.option4_cost.value="";  // 옵션4
			v.discount_cost.value="";  // 할인액
			v.vat.value="";  // 부가세
			u1_cnt(0);
		}		
	} else if(frm=="3"){
		if(confirm("3단계를 다시 작성 하시겠습니까?")){
			v.mibi_doc.value="";  // 미비서류
			v.mibi_doc_his.value="";  // 미비서류히스토리
			v.j1_ingam_date.value="";  // 취득자1인감
			v.j1_ingam_limit_date.value="";  // 취득자1인감유효기간
			v.m1_ingam_date.value="";  // 취득자2인감
			v.m1_ingam_limit_date.value="";  // 취득자2인감유효기간
			v.j1_chobon_date.value="";  // 취득자1초본
			v.j1_chobon_limit_date.value="";  // 취득자1초본유효기간
			v.m1_chobon_date.value="";  // 취득자2초본
			v.m1_chobon_limit_date.value="";  // 취득자2초본유효기간
			v.j1_etc_name.value="";  // 기타1명
			v.j1_etc_date.value="";  // 기타1
			v.j1_etc_limit_date.value="";  // 기타1유효기간
			v.m1_etc_name.value="";  // 기타2명
			v.m1_etc_date.value="";  // 기타2
			v.m1_etc_limit_date.value="";  // 기타2유효기간
			v.jumin_dngbon_date.value="";  // 주민등록등본발행일
			v.family_date.value="";  // 가족관계증명서발행일
			v.doc_memo.value="";  // 서류비고


		}		
	} else if(frm=="4"){
		if(confirm("4단계를 다시 작성 하시겠습니까?")){
			$("#reg_cause_date_2_ch").prop("checked", false); //등기원인일1과 동일
			$("#reg_cause_date_3_ch").prop("checked", false); //등기원인일1과 동일
			$("#reg_cause_date_4_ch").prop("checked", false); //등기원인일1과 동일
			$("#chaemuja_2_ch").prop("checked", false); //채무자1과 동일
			$("#chaemuja_3_ch").prop("checked", false); //채무자1과 동일
			$("#chaemuja_4_ch").prop("checked", false); //채무자1과 동일

			v.f1.value="0";  // 설정갯수
			v.bank_code_1.value="";  // 은행1
			v.jijum_code_1.value="";  // 지점1
			v.chaekwon_max_1.value="";  // 채권최고액1
			v.reg_cause_date_1.value="";  // 등기원인일1
			v.reduction_code_1.value="";  // 감면율1코드
			v.reduction_etc_name_1.value="";  // 감면율1명
			v.reduction_rate_1.value="";  // 감면율1
			v.chaemuja_1.value="";  // 채무자1
			v.bank_code_2.value="";  // 은행2
			v.jijum_code_2.value="";  // 지점2
			v.chaekwon_max_2.value="";  // 채권최고액2
			v.reg_cause_date_2.value="";  // 등기원인일2
			v.reduction_code_2.value="";  // 감면율2코드
			v.reduction_etc_name_2.value="";  // 감면율2명
			v.reduction_rate_2.value="";  // 감면율2
			v.chaemuja_2.value="";  // 채무자2
			v.bank_code_3.value="";  // 은행3
			v.jijum_code_3.value="";  // 지점3
			v.chaekwon_max_3.value="";  // 채권최고액3
			v.reg_cause_date_3.value="";  // 등기원인일3
			v.reduction_code_3.value="";  // 감면율3코드
			v.reduction_etc_name_3.value="";  // 감면율3명
			v.reduction_rate_3.value="";  // 감면율3
			v.chaemuja_3.value="";  // 채무자3
			v.bank_code_4.value="";  // 은행4
			v.jijum_code_4.value="";  // 지점4
			v.chaekwon_max_4.value="";  // 채권최고액4
			v.reg_cause_date_4.value="";  // 등기원인일4
			v.reduction_code_4.value="";  // 감면율4코드
			v.reduction_etc_name_4.value="";  // 감면율4명
			v.reduction_rate_4.value="";  // 감면율4
			v.chaemuja_4.value="";  // 채무자4
			v.owner_id.value="0";  // 소유자신분증
			v.owner_name.value="";  // 소유자신분증명
			v.s1.value="";  // 배우자이름
			v.au1.value="";  // 서류비고
			v.doc_memo.value="";  // 은행비고
			f1_cnt(0);
			f_owner_cnt(0);
		}		
	} else if(frm=="5"){
		if(confirm("5단계를 다시 작성 하시겠습니까?")){
			$("#reg_building_area_ch").prop("checked", false); //계약상 건물면적과 동일
			$("#reg_land_area_ch").prop("checked", false); //계약상 토지면적과 동일

			v.reg_building_area.value="0";  // 등기상건물면적
			v.reg_land_area1.value="";  // 등기상토지면적1
			v.reg_land_area2.value="";  // 등기상토지면적2
			v.reg_land_area3.value="";  // 등기상토지면적3
			v.reg_land_area4.value="";  // 등기상토지면적4
			v.reg_land_area5.value="";  // 등기상토지면적5
			v.reg_land_area6.value="";  // 등기상토지면적6
			v.reg_land_area7.value="";  // 등기상토지면적7
			v.reg_land_area8.value="";  // 등기상토지면적8
			v.reg_land_area9.value="";  // 등기상토지면적9
			v.reg_land_area10.value="";  // 등기상토지면적10

			v.building_trust_date.value="";  // 건물신탁접수일자
			v.building_trust_no.value="";  // 건물신탁접수번호
			v.building_trust_org_date.value="";  // 건물신탁원부일자
			v.building_trust_org_no.value="";  // 건물신탁원부번호

		}		
	}
}


function a_cnt11(frm){

	switch(frm){
	case "1":
	document.getElementById('a_cnt2').style.display="none";
	break;
	case "2":
	document.getElementById('a_cnt2').style.display="";
	break;
	}
}

function change_menu1(frm){
	switch(frm){
	case "1":
	document.all('layer1').style.visibility="visible";
	document.all('layer2').style.visibility="hidden";
	break;
	case "2":
	document.all('layer2').style.visibility="visible";
	document.all('layer1').style.visibility="hidden";
	break;
	}
}

function f_soyo(){
	document.ffx.page.value=1;
	document.ffx.action = "index.html";
	document.ffx.submit();
}


function f_submit(frm){
	var v = document.ff;
	//$("#s_point").val(frm);
	v.s_point.value=frm;
	if(v.h_idx.value==""){
		alert("현장은 필수항목입니다.");
		$('#h_idx').focus();
	}else if(v.doc_receive_date.value==""){
		alert("서류수령일은 필수항목입니다.");
		$('#doc_receive_date').focus();
	}else if(v.h1.value==""){
		alert("동은 필수항목입니다.");
		$('#h1').focus();
	}else if(v.i1.value==""){
		alert("호는 필수항목입니다.");
		$('#i1').focus();
	}else{
		<? if($mode=="e"){?>
			//alert(v.jijum_code.value);
		//if(confirm("지점비율 변경시 설정-누진보수료/보수료/공과금이 갱신됩니다.")){
			v.submit();
		//}
		<?}else{?>
			
			v.submit();
		<?}?>

	}
}


function f_pop(){
	var url    ="./popup.php";
	var title  = "listpops111";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=500, height=400, top=0,left=20"; 
	var a1 = window.open(url, title,status);
	a1.focus();
}


function f_popup_h(a1){//현장상세조회
	var url    ="popup_h.php?a1="+encodeURI(a1);
	var title  = "listpoph";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1500, height=805, top=0,left=20";
	var a = window.open(url, title,status);
	a.focus();
}
function f_popup_s(a1){//설정상세조회
	var url    ="popup_s.php?a1="+encodeURI(a1);
	var title  = "listpops";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1500, height=400, top=0,left=20";
	var a = window.open(url, title,status);
	a.focus();
}
function f_popup_g(a1){//기본상세조회
	var url    ="popup_g.php?a1="+encodeURI(a1);
	var title  = "listpops";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1400, height=900, top=0,left=20";
	var a = window.open(url, title,status);
	a.focus();
}
function f_popup_j(a1){//진행사항상세조회
	var url    ="popup_j.php?a1="+encodeURI(a1);
	var title  = "listpops";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1500, height=800, top=0,left=20";
	var a = window.open(url, title,status);
	a.focus();
}


function f_popup_a(){//A폼 출력(이전)
	var frm    = document.ffx;
	var url    ="popup_a.php";
	var title  = "listpopa";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1500, height=900, top=0,left=20";
	var a1 = window.open("", title,status);
	a1.focus();
	frm.target = title;
	frm.action = url;
	frm.method = "post";
	frm.submit();
}

function f_popup_b(){//B폼 출력(설정)
	var frm    = document.ffx;
	var url    ="popup_b.php";
	var title  = "listpopb";
	var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1500, height=900, top=0,left=20";
	var a2 = window.open("", title,status);
	a2.focus();
	frm.target = title;
	frm.action = url;
	frm.method = "post";
	frm.submit();
}

function f_popup_c(){//C폼 출력(은행)
	var frm    = document.ffx;
	if(($("select[name=h_idx]").val()=="")||($("select[name=jijum_code]").val()=="")){
		alert("현장명/은행(지점)은 필수입니다.");
	}else{
		var url    ="popup_c.php";
		var title  = "listpopb";
		var status = "toolbar=no,directories=no,scrollbars=yes,resizable=no,status=no,menubar=no,width=1500, height=900, top=0,left=20";
		var a3 = window.open("", title,status);
		a3.focus();
		frm.target = title;
		frm.action = url;
		frm.method = "post";
		frm.submit();
	}
}

</script>

</body>
</html>
