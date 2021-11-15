<?	include ("../../include/dbconn.php");
	include ("../../include/login_ch.php");
	include ("../../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$tablename = "tbl_junib";

	//print_r($_REQUEST);
	
	$mode = trim($_REQUEST[mode]);
	$s_point = trim($_REQUEST[s_point]);


	// 1단계 기본정보 입력 항목
	//$KEY[a1]							=	trim($_REQUEST[a1]);		// 회원고유번호
	$KEY[a1]							=	trim($_REQUEST[a1]);		// 회원고유번호
	//	echo "-e{$KEY[a1]}";
	//	echo "-h_idx{$_REQUEST[tax_apply]}";

	$KEY[h_idx]						=	trim($_REQUEST[h_idx]);		// 현장코드
	$KEY[h1]							=	trim($_REQUEST[h1]);	//동
	$KEY[i1]							=	trim($_REQUEST[i1]);	//호


	if($mode=="i"){

		$sql = "select no_text from tbl_hyunjang_info where h_idx='{$KEY[h_idx]}'";
		//echo "-".$sql;

		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$rown = $stmt->fetch();

		//$row = db_query_fetch($sql,0,0);
		$KEY[a1] = $rown[no_text].$KEY[h1]."동".$KEY[i1]."호";
		
	} else {
		$sql = "select am1_table, af1 from tbl_junib where a1='{$KEY[a1]}'";
		//echo "-".$sql;

		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$rowt = $stmt->fetch();
		
		$am1_table = $rowt[am1_table]; //인지산정과표
		$af1 = $rowt[af1]; // 취득과세표
	}
	
	//준공일 , FAQ URL
	$sql = "select * from tbl_hyunjang_info where h_idx='{$KEY[h_idx]}' ";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$r1 = $stmt->fetch();


	$KEY[tax_apply]				=	trim($_REQUEST[tax_apply]);	//취득세법적용
	$KEY[b1]							=	trim($_REQUEST[b1]);	//단지
	$KEY[doc_receive_date]	=	trim($_REQUEST[doc_receive_date]);	//서류수령일
	$KEY[apply_type]			=	trim($_REQUEST[apply_type]);	//취득유형
	$KEY[point_data]			=	trim($_REQUEST[point_data]);	//특이사항
	$KEY[point_data_name]			=	trim($_REQUEST[point_data_name]);	//특이사항명
	$KEY[apply_count]			=	trim($_REQUEST[apply_count]);	//취득자수
	$KEY[p1]								=	trim($_REQUEST[p1]);	//전화1
	$KEY[p2]							=	trim($_REQUEST[p2]);	//전화2
	$KEY[contract_date]		=	trim($_REQUEST[contract_date]);	//계약일
	$KEY[balance_date]		=	trim($_REQUEST[balance_date]);	//잔금일

	$KEY[tax_end_date]		=	f_day_comp($KEY[balance_date],$r1[jungong_date]);	//취득세신고만료일
	$KEY[pred_g1_temp]		=	f_day_comp($KEY[balance_date],$r1[bojon_date]);	//예상등기접수일_temp 일

	$KEY[j1]							=	trim($_REQUEST[j1]);	//취득자1
	$KEY[k1]							=	trim($_REQUEST[k1]);	//주민번호1
	$KEY[j1_stake]		 		=	trim($_REQUEST[j1_stake]);	//취득자1지분
	$KEY[l1]							=	trim($_REQUEST[l1]);	//주소1
	$KEY[m1]							=	trim($_REQUEST[m1]);	//취득자2
	$KEY[n1]							=	trim($_REQUEST[n1]);	//주민번호2
	$KEY[m1_stake]				=	trim($_REQUEST[m1_stake]);	//취득자2지분
	$KEY[o1]							=	trim($_REQUEST[o1]);	//주소2
	$KEY[w1]							=	trim($_REQUEST[w1]);	//타입
	$KEY[con_building_area]	=	f_de_comma(trim($_REQUEST[con_building_area]));	//계약서상건물면적
	$KEY[con_land_area]			=	f_de_comma(trim($_REQUEST[con_land_area]));	//계약서상토지면적
	$KEY[c1]							=	trim($_REQUEST[c1]);	//회원여부
	$KEY[tax_cut_cause]		=	trim($_REQUEST[tax_cut_cause]);	//취득세감면사유
	$KEY[tax_cut_cause_name]	=	trim($_REQUEST[tax_cut_cause_name]);	//취득세감면사유명
	$KEY[multi_housing_type]	=	trim($_REQUEST[multi_housing_type]);	//다주택여부


	$KEY2[a1]							=	trim($_REQUEST[a1]);		// 회원고유번호
	$KEY2[u1]		=	trim($_REQUEST[u1]);	//승계여부
	$KEY2[u1_1]		=	trim($_REQUEST[u1_1]);	//1차선택
	$KEY2[u1_2]		=	trim($_REQUEST[u1_2]);	//2차선택
	$KEY2[u1_3]		=	trim($_REQUEST[u1_3]);	//3차선택
	$KEY2[u1_4]		=	trim($_REQUEST[u1_4]);	//4차선택
	$KEY2[u1_5]		=	trim($_REQUEST[u1_5]);	//5차선택
	$KEY2[u1_6]		=	trim($_REQUEST[u1_6]);	//6차선택
	$KEY2[u1_gubun]		=	"n";	//전매1회라도 있는경우
	if($KEY2[u1_1]=="전매"){
		$KEY2[u1_gubun] = "y";
	}else if($KEY2[u1_2]=="전매"){
		$KEY2[u1_gubun] = "y";
	}else if($KEY2[u1_3]=="전매"){
		$KEY2[u1_gubun] = "y";
	}else if($KEY2[u1_4]=="전매"){
		$KEY2[u1_gubun] = "y";
	}else if($KEY2[u1_5]=="전매"){
		$KEY2[u1_gubun] = "y";
	}else if($KEY2[u1_6]=="전매"){
		$KEY2[u1_gubun] = "y";
	}
	
	if($KEY2[u1]==""){
		$KEY2[u1_gubun] 	= "";
		$KEY2[pre_cost]		=	"";	//프리미엄
	} else {
		$KEY2[pre_cost]		=	f_de_comma(trim($_REQUEST[pre_cost]));	//프리미엄
	}
	$KEY2[gsil_last_date]		=	trim($_REQUEST[gsil_last_date]);	//실거래일자(마지막전매)
	$KEY2[singo_last_no]		=	trim($_REQUEST[singo_last_no]);	//거래신고일련번호(마지막전매)
	$KEY2[singo_last_cost]		=	f_de_comma(trim($_REQUEST[singo_last_cost]));	//거래신고금액(마지막전매)
	$KEY2[singo_gubun]		=	trim($_REQUEST[singo_gubun]);	//원분양자신고대상
	$KEY2[ad1]		=	trim($_REQUEST[ad1]);	//CS비고
	$KEY2[bunyang_cost]		=	f_de_comma(trim($_REQUEST[bunyang_cost]));	//분양가
	$KEY2[balkoni_cost]		=	f_de_comma(trim($_REQUEST[balkoni_cost]));	//발코니
	$KEY2[option1_cost]		=	f_de_comma(trim($_REQUEST[option1_cost]));	//옵션1
	$KEY2[option2_cost]		=	f_de_comma(trim($_REQUEST[option2_cost]));	//옵션2
	$KEY2[option3_cost]		=	f_de_comma(trim($_REQUEST[option3_cost]));	//옵션3
	$KEY2[option4_cost]		=	f_de_comma(trim($_REQUEST[option4_cost]));	//옵션4
	$KEY2[discount_cost]		=	f_de_comma(trim($_REQUEST[discount_cost]));	//할인액
	$KEY2[vat]		=	f_de_comma(trim($_REQUEST[vat]));	//부가세

	// 인지산정과표 계산
	$KEY2[am1_table] = ($KEY2[bunyang_cost] + $KEY2[balkoni_cost] + $KEY2[option1_cost] + $KEY2[option2_cost] + $KEY2[option3_cost] + $KEY2[option4_cost] ) - ($KEY2[discount_cost] + $KEY2[vat]);
	
	// 인지매입액 계산
//	if($KEY2[am1_table]!=""&&$af1!=""){
		if($KEY2[u1_gubun]=="y"){  //전매가 1회라도 있는경우
			if($KEY2[am1_table]<"1000000000"){
				$KEY2[am1_pur_cost] = "150000";
			} else if($KEY2[am1_table]>="1000000000"){
				if($af1<"1000000000"){
					$KEY2[am1_pur_cost] = "500000"; //35만원 + 15만원
				} else if($af1>="1000000000"){
					$KEY2[am1_pur_cost] = "700000"; //35만원 + 35만원
				}
			}
		}else if($KEY2[u1_gubun]=="n"){
			if($af1<"1000000000"){
				$KEY2[am1_pur_cost] = "150000"; //15만원
			} else if($af1>="1000000000"){
				$KEY2[am1_pur_cost] = "350000"; //35만원
			}
		}
//		$KEY2[am1_pur_cost] = 
//	}

	$KEY3[a1]							=	trim($_REQUEST[a1]);		// 회원고유번호
	$KEY3[mibi_doc]		=	trim($_REQUEST[mibi_doc]);	//미비서류(기본서류)
	$KEY3[mibi_doc_his]		=	trim($_REQUEST[mibi_doc_his]);	//미비서류히스토리
	$KEY3[j1_chobon_date]		=	trim($_REQUEST[j1_chobon_date]);	//취득자1초본
	$KEY3[j1_chobon_limit_date]		=	f_next_3month($KEY3[j1_chobon_date]);	//취득자1초본유효기간
	$KEY3[m1_chobon_date]		=	trim($_REQUEST[m1_chobon_date]);	//취득자2초본
	$KEY3[m1_chobon_limit_date]		=	f_next_3month($KEY3[m1_chobon_date]);	//취득자2초본유효기간
	$KEY3[j1_etc_name]		=	trim($_REQUEST[j1_etc_name]);	//기타1명
	$KEY3[j1_etc_date]		=	trim($_REQUEST[j1_etc_date]);	//기타1
	$KEY3[j1_etc_limit_date]		=	f_next_3month($KEY3[j1_etc_date]);	//기타1유효기간
	$KEY3[m1_etc_name]		=	trim($_REQUEST[m1_etc_name]);	//기타2명
	$KEY3[m1_etc_date]		=	trim($_REQUEST[m1_etc_date]);	//기타2
	$KEY3[m1_etc_limit_date]		=	f_next_3month($KEY3[m1_etc_date]);	//기타2유효기간
	$KEY3[jumin_dngbon_date]		=	trim($_REQUEST[jumin_dngbon_date]);	//주민등록등본발행일
	$KEY3[family_date]		=	trim($_REQUEST[family_date]);	//가족관계증명서발행일
	$KEY3[doc_memo]		=	trim($_REQUEST[doc_memo]);	//서류비고
	if(trim($_REQUEST[f1])!="0"){
		$KEY3[j1_ingam_date]		=	trim($_REQUEST[j1_ingam_date]);	//취득자1인감
		$KEY3[j1_ingam_limit_date]		=	f_next_3month($KEY3[j1_ingam_date]);	//취득자1인감유효기간
		$KEY3[m1_ingam_date]		=	trim($_REQUEST[m1_ingam_date]);	//취득자2인감
		$KEY3[m1_ingam_limit_date]		=	f_next_3month($KEY3[m1_ingam_date]);	//취득자2인감유효기간
	}else{
		$KEY3[j1_ingam_date] = "";	//취득자1인감
		$KEY3[j1_ingam_limit_date] = "";	//취득자1인감유효기간
		$KEY3[m1_ingam_date] = "";	//취득자2인감
		$KEY3[m1_ingam_limit_date] = "";	//취득자2인감유효기간
	}

	$KEY4[a1]							=	trim($_REQUEST[a1]);		// 회원고유번호
	$KEY4[f1]		=	trim($_REQUEST[f1]);	//설정갯수
//	if($KEY4[f1]!="0"&&$KEY4[f1]!=""){
		$KEY[d1]							=	trim($_REQUEST[bank_code_1]);	//은행1
		$KEY[e1]							=	trim($_REQUEST[jijum_code_1]);	//지점1
//	}
	$KEY4[av1]		=	f_de_comma(trim($_REQUEST[av1]));	//채권최고액1

	$KEY4[av1_tax] = $KEY4[av1] * 0.0024;
	// 설정등록세1 계산 // ROUNDDOWN(채권최액*0.24%,-1)
	$KEY4[av1_tax] = floor( $KEY4[av1_tax] / 10 ) * 10;  //1단위 절삭

	
	$KEY4[aw1]		=	trim($_REQUEST[aw1]);	//채무자1
	if(trim($_REQUEST[aw1_jumin])==""){
		if($KEY4[aw1]==$KEY[j1]) {$KEY4[aw1_jumin]=$KEY[k1];}
		if($KEY4[aw1]==$KEY[m1]) {$KEY4[aw1_jumin]=$KEY[n1];}
	}else{
		$KEY4[aw1_jumin]		=	trim($_REQUEST[aw1_jumin]);	//채무자1주민번호
	}
	$KEY4[ax1]		=	f_de_comma(trim($_REQUEST[ax1]));	//채권최고액2

	$KEY4[ax1_tax] = $KEY4[ax1] * 0.0024;
	// 설정등록세2 계산 // ROUNDDOWN(채권최액*0.24%,-1)
	$KEY4[ax1_tax] = floor( $KEY4[ax1_tax] / 10 ) * 10;  //1단위 절삭

	$KEY4[aw2]		=	trim($_REQUEST[aw2]);	//채무자2
	if(trim($_REQUEST[aw2_jumin])==""){
		if($KEY4[aw2]==$KEY[j1]) $KEY4[aw2_jumin]=$KEY[k1];
		if($KEY4[aw2]==$KEY[m1]) $KEY4[aw2_jumin]=$KEY[n1];
	}else{
		$KEY4[aw2_jumin]		=	trim($_REQUEST[aw2_jumin]);	//채무자1주민번호
	}
	$KEY4[ay1]		=	f_de_comma(trim($_REQUEST[ay1]));	//채권최고액3

	$KEY4[ay1_tax] = $KEY4[ay1] * 0.0024;
	// 설정등록세3 계산 // ROUNDDOWN(채권최액*0.24%,-1)
	$KEY4[ay1_tax] = floor( $KEY4[ay1_tax] / 10 ) * 10;  //1단위 절삭

	$KEY4[aw3]		=	trim($_REQUEST[aw3]);	//채무자3
	if(trim($_REQUEST[aw3_jumin])==""){
		if($KEY4[aw3]==$KEY[j1]) $KEY4[aw3_jumin]=$KEY[k1];
		if($KEY4[aw3]==$KEY[m1]) $KEY4[aw3_jumin]=$KEY[n1];
	}else{
		$KEY4[aw3_jumin]		=	trim($_REQUEST[aw3_jumin]);	//채무자1주민번호
	}
	$KEY4[az1]		=	f_de_comma(trim($_REQUEST[az1]));	//채권최고액4

	$KEY4[az1_tax] = $KEY4[az1] * 0.0024;
	// 설정등록세4 계산 // ROUNDDOWN(채권최액*0.24%,-1)
	$KEY4[az1_tax] = floor( $KEY4[az1_tax] / 10 ) * 10;  //1단위 절삭

	$KEY4[aw4]		=	trim($_REQUEST[aw4]);	//채무자4
	if(trim($_REQUEST[aw4_jumin])==""){
		if($KEY4[aw4]==$KEY[j1]) $KEY4[aw4_jumin]=$KEY[k1];
		if($KEY4[aw4]==$KEY[m1]) $KEY4[aw4_jumin]=$KEY[n1];
	}else{
		$KEY4[aw4_jumin]		=	trim($_REQUEST[aw4_jumin]);	//채무자1주민번호
	}
	$KEY4[owner_id]		=	trim($_REQUEST[owner_id]);	//소유자신분증
	$KEY4[owner_name]		=	trim($_REQUEST[owner_name]);	//소유자신분증명
	$KEY4[s1]		=	trim($_REQUEST[s1]);	//배우자이름
	$KEY4[au1]		=	trim($_REQUEST[au1]);	//은행비고

	$KEY41[a1]							=	trim($_REQUEST[a1]);		// 회원고유번호
	$KEY41[suljung_no]							=	"1";		// 회원고유번호
	$KEY41[bank_code]		=	trim($_REQUEST[bank_code_1]);	//은행1
	$KEY41[jijum_code]		=	trim($_REQUEST[jijum_code_1]);	//지점1
	$KEY41[reg_cause_date]		=	trim($_REQUEST[reg_cause_date_1]);	//등기원인일1
	$KEY41[reduction_code]		=	trim($_REQUEST[reduction_code_1]);	//감면율1코드
	$KEY41[reduction_etc_name]		=	trim($_REQUEST[reduction_etc_name_1]);	//감면율1명
	$KEY41[reduction_rate]		=	f_de_comma(trim($_REQUEST[reduction_rate_1]));	//감면율1
	$KEY41[chaekwon_max]		=	f_de_comma(trim($_REQUEST[av1]));	//채권최고액1
	
	$KEY42[a1]							=	trim($_REQUEST[a1]);		// 회원고유번호
	$KEY42[suljung_no]							=	"2";		// 회원고유번호
	$KEY42[bank_code]		=	trim($_REQUEST[bank_code_2]);	//은행2
	$KEY42[jijum_code]		=	trim($_REQUEST[jijum_code_2]);	//지점2
	$KEY42[reg_cause_date]		=	trim($_REQUEST[reg_cause_date_2]);	//등기원인일2
	$KEY42[reduction_code]		=	trim($_REQUEST[reduction_code_2]);	//감면율2코드
	$KEY42[reduction_etc_name]		=	trim($_REQUEST[reduction_etc_name_2]);	//감면율2명
	$KEY42[reduction_rate]		=	f_de_comma(trim($_REQUEST[reduction_rate_2]));	//감면율2
	$KEY42[chaekwon_max]		=	f_de_comma(trim($_REQUEST[ax1]));	//채권최고액2

	$KEY43[a1]							=	trim($_REQUEST[a1]);		// 회원고유번호
	$KEY43[suljung_no]							=	"3";		// 회원고유번호
	$KEY43[bank_code]		=	trim($_REQUEST[bank_code_3]);	//은행3
	$KEY43[jijum_code]		=	trim($_REQUEST[jijum_code_3]);	//지점3
	$KEY43[reg_cause_date]		=	trim($_REQUEST[reg_cause_date_3]);	//등기원인일3
	$KEY43[reduction_code]		=	trim($_REQUEST[reduction_code_3]);	//감면율3코드
	$KEY43[reduction_etc_name]		=	trim($_REQUEST[reduction_etc_name_3]);	//감면율3명
	$KEY43[reduction_rate]		=	f_de_comma(trim($_REQUEST[reduction_rate_3]));	//감면율3
	$KEY43[chaekwon_max]		=	f_de_comma(trim($_REQUEST[ay1]));	//채권최고액3

	$KEY44[a1]							=	trim($_REQUEST[a1]);		// 회원고유번호
	$KEY44[suljung_no]							=	"4";		// 회원고유번호
	$KEY44[bank_code]		=	trim($_REQUEST[bank_code_4]);	//은행4
	$KEY44[jijum_code]		=	trim($_REQUEST[jijum_code_4]);	//지점4
	$KEY44[reg_cause_date]		=	trim($_REQUEST[reg_cause_date_4]);	//등기원인일4
	$KEY44[reduction_code]		=	trim($_REQUEST[reduction_code_4]);	//감면율4코드
	$KEY44[reduction_etc_name]		=	trim($_REQUEST[reduction_etc_name_4]);	//감면율4명
	$KEY44[reduction_rate]		=	f_de_comma(trim($_REQUEST[reduction_rate_4]));	//감면율4
	$KEY44[chaekwon_max]		=	f_de_comma(trim($_REQUEST[az1]));	//채권최고액4

	$KEY5[a1]							=	trim($_REQUEST[a1]);		// 회원고유번호
	$KEY5[reg_building_area]		=	f_de_comma(trim($_REQUEST[reg_building_area]));	//등기상건물면적
	if(trim($_REQUEST[lot_amount])>="1"){
		$KEY5[reg_land_area1]		=	f_de_comma(trim($_REQUEST[reg_land_area1]));	//등기상토지면적1
	} else {
		$KEY5[reg_land_area1]		=	"";
	}
	if(trim($_REQUEST[lot_amount])>="2"){
		$KEY5[reg_land_area2]		=	f_de_comma(trim($_REQUEST[reg_land_area2]));	//등기상토지면적2
	} else {
		$KEY5[reg_land_area2]		=	"";
	}
	if(trim($_REQUEST[lot_amount])>="3"){
		$KEY5[reg_land_area3]		=	f_de_comma(trim($_REQUEST[reg_land_area3]));	//등기상토지면적3
	} else {
		$KEY5[reg_land_area3]		=	"";
	}
	if(trim($_REQUEST[lot_amount])>="4"){
		$KEY5[reg_land_area4]		=	f_de_comma(trim($_REQUEST[reg_land_area4]));	//등기상토지면적4
	} else {
		$KEY5[reg_land_area4]		=	"";
	}
	if(trim($_REQUEST[lot_amount])>="5"){
		$KEY5[reg_land_area5]		=	f_de_comma(trim($_REQUEST[reg_land_area5]));	//등기상토지면적5
	} else {
		$KEY5[reg_land_area5]		=	"";
	}
	if(trim($_REQUEST[lot_amount])>="6"){
		$KEY5[reg_land_area6]		=	f_de_comma(trim($_REQUEST[reg_land_area6]));	//등기상토지면적6
	} else {
		$KEY5[reg_land_area6]		=	"";
	}
	if(trim($_REQUEST[lot_amount])>="7"){
		$KEY5[reg_land_area7]		=	f_de_comma(trim($_REQUEST[reg_land_area7]));	//등기상토지면적7
	} else {
		$KEY5[reg_land_area7]		=	"";
	}
	if(trim($_REQUEST[lot_amount])>="8"){
		$KEY5[reg_land_area8]		=	f_de_comma(trim($_REQUEST[reg_land_area8]));	//등기상토지면적8
	} else {
		$KEY5[reg_land_area8]		=	"";
	}
	if(trim($_REQUEST[lot_amount])>="9"){
		$KEY5[reg_land_area9]		=	f_de_comma(trim($_REQUEST[reg_land_area9]));	//등기상토지면적9
	} else {
		$KEY5[reg_land_area9]		=	"";
	}
	if(trim($_REQUEST[lot_amount])>="10"){
		$KEY5[reg_land_area10]		=	f_de_comma(trim($_REQUEST[reg_land_area10]));	//등기상토지면적10
	} else {
		$KEY5[reg_land_area10]		=	"";
	}
	$KEY5[building_trust_date]		=	trim($_REQUEST[building_trust_date]);	//건물신탁접수일자
	$KEY5[building_trust_no]		=	trim($_REQUEST[building_trust_no]);	//건물신탁접수번호
	$KEY5[building_trust_org_date]		=	trim($_REQUEST[building_trust_org_date]);	//건물신탁원부일자
	$KEY5[building_trust_org_no]		=	trim($_REQUEST[building_trust_org_no]);	//건물신탁원부번호

	if($mode=="i"){

		$sql = "select * from tbl_junib where h_idx='{$KEY[h_idx]}' and h1='{$KEY[h1]}' and i1='{$KEY[i1]}' ";
		$bxx = db_query_fetch($sql);
		if($bxx[idx]!=""){ //중복된다면 오류
			echo "<script>alert('이미 등록되어있는 고객정보 입니다. 현장명/동/호 의 내용을 확인해 주세요.');history.go(-1);</script>";
			return;
		}

	}
	//echo "-e{$sql}";

//국토

//echo "<script>alert('test');</script>";
//	echo "-e{$KEY[h_idx]}";
	//		echo "-e{000}";
	//		echo "-2{$idx}";

	if($s_point=="1"){
		###########################################################################
		$updatewhere = " WHERE a1 = '{$KEY[a1]}' ";
		$idx = db_replace($KEY,$tablename,$updatewhere,"a1");
		###########################################################################
			//echo "-3{$tablename}";
			//echo "-3{$updatewhere}";
			//echo "-3{$idx}";

	} else if($s_point=="2"){
		###########################################################################
		$updatewhere = " WHERE a1 = '{$KEY2[a1]}' ";
		$idx = db_replace($KEY2,$tablename,$updatewhere,"a1");
		###########################################################################
	} else if($s_point=="3"){
		###########################################################################
		$updatewhere = " WHERE a1 = '{$KEY3[a1]}' ";
		$idx = db_replace($KEY3,$tablename,$updatewhere,"a1");
		###########################################################################
	} else if($s_point=="4"){
		###########################################################################
		$updatewhere = " WHERE a1 = '{$KEY[a1]}' ";
		$idx = db_replace($KEY,$tablename,$updatewhere,"a1");
		###########################################################################
		###########################################################################
		$updatewhere = " WHERE a1 = '{$KEY4[a1]}' ";
		$idx = db_replace($KEY4,$tablename,$updatewhere,"a1");
		###########################################################################

		// 저장전 저장 갯수이외의 값 삭제 처리
		if($KEY4[f1]=="1"){
			// delete 처리
			$sql = "delete from tbl_suljung where a1='{$KEY[a1]}' and suljung_no in ('2','3','4') ";
			//echo "-e{$sql}";
			db_query($sql);

			// 설정번호 1 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY41[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY41[bank_code]}',jijum_code='{$KEY41[jijum_code]}',reg_cause_date='{$KEY41[reg_cause_date]}',reduction_code='{$KEY41[reduction_code]}',reduction_etc_name='{$KEY41[reduction_etc_name]}',reduction_rate='{$KEY41[reduction_rate]}',chaekwon_max='{$KEY41[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY41[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY41[a1]}', '{$KEY41[suljung_no]}', '{$KEY41[bank_code]}', '{$KEY41[jijum_code]}', '{$KEY41[reg_cause_date]}', '{$KEY41[reduction_code]}', '{$KEY41[reduction_etc_name]}', '{$KEY41[reduction_rate]}', '{$KEY41[chaekwon_max]}' ) ";
			}
			db_query($sql);
			
		} else if($KEY4[f1]=="2"){
			$sql = "delete from tbl_suljung where a1='{$KEY[a1]}' and suljung_no in ('3','4') ";
			db_query($sql);

			// 설정번호 1 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY41[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY41[bank_code]}',jijum_code='{$KEY41[jijum_code]}',reg_cause_date='{$KEY41[reg_cause_date]}',reduction_code='{$KEY41[reduction_code]}',reduction_etc_name='{$KEY41[reduction_etc_name]}',reduction_rate='{$KEY41[reduction_rate]}',chaekwon_max='{$KEY41[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY41[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY41[a1]}', '{$KEY41[suljung_no]}', '{$KEY41[bank_code]}', '{$KEY41[jijum_code]}', '{$KEY41[reg_cause_date]}', '{$KEY41[reduction_code]}', '{$KEY41[reduction_etc_name]}', '{$KEY41[reduction_rate]}', '{$KEY41[chaekwon_max]}' ) ";
			}
			db_query($sql);

			// 설정번호 2 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY42[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY42[bank_code]}',jijum_code='{$KEY42[jijum_code]}',reg_cause_date='{$KEY42[reg_cause_date]}',reduction_code='{$KEY42[reduction_code]}',reduction_etc_name='{$KEY42[reduction_etc_name]}',reduction_rate='{$KEY42[reduction_rate]}',chaekwon_max='{$KEY42[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY42[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY42[a1]}', '{$KEY42[suljung_no]}', '{$KEY42[bank_code]}', '{$KEY42[jijum_code]}', '{$KEY42[reg_cause_date]}', '{$KEY42[reduction_code]}', '{$KEY42[reduction_etc_name]}', '{$KEY42[reduction_rate]}', '{$KEY42[chaekwon_max]}' ) ";
			}
			db_query($sql);
			
		} else if($KEY4[f1]=="3"){
			$sql = "delete from tbl_suljung where a1='{$KEY[a1]}' and suljung_no = '4' ";
			db_query($sql);

			// 설정번호 1 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY41[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY41[bank_code]}',jijum_code='{$KEY41[jijum_code]}',reg_cause_date='{$KEY41[reg_cause_date]}',reduction_code='{$KEY41[reduction_code]}',reduction_etc_name='{$KEY41[reduction_etc_name]}',reduction_rate='{$KEY41[reduction_rate]}',chaekwon_max='{$KEY41[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY41[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY41[a1]}', '{$KEY41[suljung_no]}', '{$KEY41[bank_code]}', '{$KEY41[jijum_code]}', '{$KEY41[reg_cause_date]}', '{$KEY41[reduction_code]}', '{$KEY41[reduction_etc_name]}', '{$KEY41[reduction_rate]}', '{$KEY41[chaekwon_max]}' ) ";
			}
			db_query($sql);

			// 설정번호 2 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY42[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY42[bank_code]}',jijum_code='{$KEY42[jijum_code]}',reg_cause_date='{$KEY42[reg_cause_date]}',reduction_code='{$KEY42[reduction_code]}',reduction_etc_name='{$KEY42[reduction_etc_name]}',reduction_rate='{$KEY42[reduction_rate]}',chaekwon_max='{$KEY42[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY42[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY42[a1]}', '{$KEY42[suljung_no]}', '{$KEY42[bank_code]}', '{$KEY42[jijum_code]}', '{$KEY42[reg_cause_date]}', '{$KEY42[reduction_code]}', '{$KEY42[reduction_etc_name]}', '{$KEY42[reduction_rate]}', '{$KEY42[chaekwon_max]}' ) ";
			}
			db_query($sql);
			
			// 설정번호 3 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY43[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY43[bank_code]}',jijum_code='{$KEY43[jijum_code]}',reg_cause_date='{$KEY43[reg_cause_date]}',reduction_code='{$KEY43[reduction_code]}',reduction_etc_name='{$KEY43[reduction_etc_name]}',reduction_rate='{$KEY43[reduction_rate]}',chaekwon_max='{$KEY43[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY43[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY43[a1]}', '{$KEY43[suljung_no]}', '{$KEY43[bank_code]}', '{$KEY43[jijum_code]}', '{$KEY43[reg_cause_date]}', '{$KEY43[reduction_code]}', '{$KEY43[reduction_etc_name]}', '{$KEY43[reduction_rate]}', '{$KEY43[chaekwon_max]}' ) ";
			}
			db_query($sql);
			
		} else if($KEY4[f1]=="4"){

			// 설정번호 1 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY41[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY41[bank_code]}',jijum_code='{$KEY41[jijum_code]}',reg_cause_date='{$KEY41[reg_cause_date]}',reduction_code='{$KEY41[reduction_code]}',reduction_etc_name='{$KEY41[reduction_etc_name]}',reduction_rate='{$KEY41[reduction_rate]}',chaekwon_max='{$KEY41[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY41[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY41[a1]}', '{$KEY41[suljung_no]}', '{$KEY41[bank_code]}', '{$KEY41[jijum_code]}', '{$KEY41[reg_cause_date]}', '{$KEY41[reduction_code]}', '{$KEY41[reduction_etc_name]}', '{$KEY41[reduction_rate]}', '{$KEY41[chaekwon_max]}' ) ";
			}
			db_query($sql);
			
				// 설정번호 2 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY42[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY42[bank_code]}',jijum_code='{$KEY42[jijum_code]}',reg_cause_date='{$KEY42[reg_cause_date]}',reduction_code='{$KEY42[reduction_code]}',reduction_etc_name='{$KEY42[reduction_etc_name]}',reduction_rate='{$KEY42[reduction_rate]}',chaekwon_max='{$KEY42[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY42[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY42[a1]}', '{$KEY42[suljung_no]}', '{$KEY42[bank_code]}', '{$KEY42[jijum_code]}', '{$KEY42[reg_cause_date]}', '{$KEY42[reduction_code]}', '{$KEY42[reduction_etc_name]}', '{$KEY42[reduction_rate]}', '{$KEY42[chaekwon_max]}' ) ";
			}
			db_query($sql);
			
			// 설정번호 3 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY43[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY43[bank_code]}',jijum_code='{$KEY43[jijum_code]}',reg_cause_date='{$KEY43[reg_cause_date]}',reduction_code='{$KEY43[reduction_code]}',reduction_etc_name='{$KEY43[reduction_etc_name]}',reduction_rate='{$KEY43[reduction_rate]}',chaekwon_max='{$KEY43[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY43[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY43[a1]}', '{$KEY43[suljung_no]}', '{$KEY43[bank_code]}', '{$KEY43[jijum_code]}', '{$KEY43[reg_cause_date]}', '{$KEY43[reduction_code]}', '{$KEY43[reduction_etc_name]}', '{$KEY43[reduction_rate]}', '{$KEY43[chaekwon_max]}' ) ";
			}
			db_query($sql);

			// 설정번호 4 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY44[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY44[bank_code]}',jijum_code='{$KEY44[jijum_code]}',reg_cause_date='{$KEY44[reg_cause_date]}',reduction_code='{$KEY44[reduction_code]}',reduction_etc_name='{$KEY44[reduction_etc_name]}',reduction_rate='{$KEY44[reduction_rate]}',chaekwon_max='{$KEY44[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY44[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY44[a1]}', '{$KEY44[suljung_no]}', '{$KEY44[bank_code]}', '{$KEY44[jijum_code]}', '{$KEY44[reg_cause_date]}', '{$KEY44[reduction_code]}', '{$KEY44[reduction_etc_name]}', '{$KEY44[reduction_rate]}', '{$KEY44[chaekwon_max]}' ) ";
			}
			db_query($sql);

		} else {
			$sql = "delete from tbl_suljung where a1='{$KEY[a1]}' ";
			db_query($sql);
		}			


	} else if($s_point=="5"){
		###########################################################################
		$updatewhere = " WHERE a1 = '{$KEY5[a1]}' ";
		$idx = db_replace($KEY5,$tablename,$updatewhere,"a1");
		###########################################################################
	} else {
		###########################################################################
		$updatewhere = " WHERE a1 = '{$KEY[a1]}' ";
		$idx = db_replace($KEY,$tablename,$updatewhere,"a1");
		###########################################################################

		###########################################################################
		$updatewhere = " WHERE a1 = '{$KEY2[a1]}' ";
		$idx = db_replace($KEY2,$tablename,$updatewhere,"a1");
		###########################################################################

		###########################################################################
		$updatewhere = " WHERE a1 = '{$KEY3[a1]}' ";
		$idx = db_replace($KEY3,$tablename,$updatewhere,"a1");
		###########################################################################

		###########################################################################
		$updatewhere = " WHERE a1 = '{$KEY4[a1]}' ";
		$idx = db_replace($KEY4,$tablename,$updatewhere,"a1");
		###########################################################################

		// 저장전 저장 갯수이외의 값 삭제 처리
		if($KEY4[f1]=="1"){
			// delete 처리
			$sql = "delete from tbl_suljung where a1='{$KEY[a1]}' and suljung_no in ('2','3','4') ";
			//echo "-e{$sql}";
			db_query($sql);
			//echo "-e{$sql}";

			// 설정번호 1 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY41[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY41[bank_code]}',jijum_code='{$KEY41[jijum_code]}',reg_cause_date='{$KEY41[reg_cause_date]}',reduction_code='{$KEY41[reduction_code]}',reduction_etc_name='{$KEY41[reduction_etc_name]}',reduction_rate='{$KEY41[reduction_rate]}',chaekwon_max='{$KEY41[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY41[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY41[a1]}', '{$KEY41[suljung_no]}', '{$KEY41[bank_code]}', '{$KEY41[jijum_code]}', '{$KEY41[reg_cause_date]}', '{$KEY41[reduction_code]}', '{$KEY41[reduction_etc_name]}', '{$KEY41[reduction_rate]}', '{$KEY41[chaekwon_max]}' ) ";
			}
			db_query($sql);
			
		} else if($KEY4[f1]=="2"){
			$sql = "delete from tbl_suljung where a1='{$KEY[a1]}' and suljung_no in ('3','4') ";
			db_query($sql);

			// 설정번호 1 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY41[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY41[bank_code]}',jijum_code='{$KEY41[jijum_code]}',reg_cause_date='{$KEY41[reg_cause_date]}',reduction_code='{$KEY41[reduction_code]}',reduction_etc_name='{$KEY41[reduction_etc_name]}',reduction_rate='{$KEY41[reduction_rate]}',chaekwon_max='{$KEY41[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY41[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY41[a1]}', '{$KEY41[suljung_no]}', '{$KEY41[bank_code]}', '{$KEY41[jijum_code]}', '{$KEY41[reg_cause_date]}', '{$KEY41[reduction_code]}', '{$KEY41[reduction_etc_name]}', '{$KEY41[reduction_rate]}', '{$KEY41[chaekwon_max]}' ) ";
			}
			db_query($sql);

			// 설정번호 2 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY42[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY42[bank_code]}',jijum_code='{$KEY42[jijum_code]}',reg_cause_date='{$KEY42[reg_cause_date]}',reduction_code='{$KEY42[reduction_code]}',reduction_etc_name='{$KEY42[reduction_etc_name]}',reduction_rate='{$KEY42[reduction_rate]}',chaekwon_max='{$KEY42[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY42[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY42[a1]}', '{$KEY42[suljung_no]}', '{$KEY42[bank_code]}', '{$KEY42[jijum_code]}', '{$KEY42[reg_cause_date]}', '{$KEY42[reduction_code]}', '{$KEY42[reduction_etc_name]}', '{$KEY42[reduction_rate]}', '{$KEY42[chaekwon_max]}' ) ";
			}
			db_query($sql);
			
		} else if($KEY4[f1]=="3"){
			$sql = "delete from tbl_suljung where a1='{$KEY[a1]}' and suljung_no = '4' ";
			db_query($sql);

			// 설정번호 1 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY41[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY41[bank_code]}',jijum_code='{$KEY41[jijum_code]}',reg_cause_date='{$KEY41[reg_cause_date]}',reduction_code='{$KEY41[reduction_code]}',reduction_etc_name='{$KEY41[reduction_etc_name]}',reduction_rate='{$KEY41[reduction_rate]}',chaekwon_max='{$KEY41[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY41[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY41[a1]}', '{$KEY41[suljung_no]}', '{$KEY41[bank_code]}', '{$KEY41[jijum_code]}', '{$KEY41[reg_cause_date]}', '{$KEY41[reduction_code]}', '{$KEY41[reduction_etc_name]}', '{$KEY41[reduction_rate]}', '{$KEY41[chaekwon_max]}' ) ";
			}
			db_query($sql);

			// 설정번호 2 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY42[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY42[bank_code]}',jijum_code='{$KEY42[jijum_code]}',reg_cause_date='{$KEY42[reg_cause_date]}',reduction_code='{$KEY42[reduction_code]}',reduction_etc_name='{$KEY42[reduction_etc_name]}',reduction_rate='{$KEY42[reduction_rate]}',chaekwon_max='{$KEY42[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY42[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY42[a1]}', '{$KEY42[suljung_no]}', '{$KEY42[bank_code]}', '{$KEY42[jijum_code]}', '{$KEY42[reg_cause_date]}', '{$KEY42[reduction_code]}', '{$KEY42[reduction_etc_name]}', '{$KEY42[reduction_rate]}', '{$KEY42[chaekwon_max]}' ) ";
			}
			db_query($sql);
			
			// 설정번호 3 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY43[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY43[bank_code]}',jijum_code='{$KEY43[jijum_code]}',reg_cause_date='{$KEY43[reg_cause_date]}',reduction_code='{$KEY43[reduction_code]}',reduction_etc_name='{$KEY43[reduction_etc_name]}',reduction_rate='{$KEY43[reduction_rate]}',chaekwon_max='{$KEY43[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY43[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY43[a1]}', '{$KEY43[suljung_no]}', '{$KEY43[bank_code]}', '{$KEY43[jijum_code]}', '{$KEY43[reg_cause_date]}', '{$KEY43[reduction_code]}', '{$KEY43[reduction_etc_name]}', '{$KEY43[reduction_rate]}', '{$KEY43[chaekwon_max]}' ) ";
			}
			db_query($sql);
			
		} else if($KEY4[f1]=="4"){

			// 설정번호 1 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY41[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY41[bank_code]}',jijum_code='{$KEY41[jijum_code]}',reg_cause_date='{$KEY41[reg_cause_date]}',reduction_code='{$KEY41[reduction_code]}',reduction_etc_name='{$KEY41[reduction_etc_name]}',reduction_rate='{$KEY41[reduction_rate]}',chaekwon_max='{$KEY41[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY41[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY41[a1]}', '{$KEY41[suljung_no]}', '{$KEY41[bank_code]}', '{$KEY41[jijum_code]}', '{$KEY41[reg_cause_date]}', '{$KEY41[reduction_code]}', '{$KEY41[reduction_etc_name]}', '{$KEY41[reduction_rate]}', '{$KEY41[chaekwon_max]}' ) ";
			}
			db_query($sql);
			
				// 설정번호 2 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY42[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY42[bank_code]}',jijum_code='{$KEY42[jijum_code]}',reg_cause_date='{$KEY42[reg_cause_date]}',reduction_code='{$KEY42[reduction_code]}',reduction_etc_name='{$KEY42[reduction_etc_name]}',reduction_rate='{$KEY42[reduction_rate]}',chaekwon_max='{$KEY42[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY42[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY42[a1]}', '{$KEY42[suljung_no]}', '{$KEY42[bank_code]}', '{$KEY42[jijum_code]}', '{$KEY42[reg_cause_date]}', '{$KEY42[reduction_code]}', '{$KEY42[reduction_etc_name]}', '{$KEY42[reduction_rate]}', '{$KEY42[chaekwon_max]}' ) ";
			}
			db_query($sql);
			
			// 설정번호 3 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY43[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY43[bank_code]}',jijum_code='{$KEY43[jijum_code]}',reg_cause_date='{$KEY43[reg_cause_date]}',reduction_code='{$KEY43[reduction_code]}',reduction_etc_name='{$KEY43[reduction_etc_name]}',reduction_rate='{$KEY43[reduction_rate]}',chaekwon_max='{$KEY43[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY43[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY43[a1]}', '{$KEY43[suljung_no]}', '{$KEY43[bank_code]}', '{$KEY43[jijum_code]}', '{$KEY43[reg_cause_date]}', '{$KEY43[reduction_code]}', '{$KEY43[reduction_etc_name]}', '{$KEY43[reduction_rate]}', '{$KEY43[chaekwon_max]}' ) ";
			}
			db_query($sql);

			// 설정번호 4 처리
			$sql = "select * from tbl_suljung where a1='{$KEY[a1]}' and suljung_no='{$KEY44[suljung_no]}' ";
			$bxx = db_query_fetch($sql);
			if($bxx[idx]!=""){ //update 처리
				$sql = "update  tbl_suljung set bank_code='{$KEY44[bank_code]}',jijum_code='{$KEY44[jijum_code]}',reg_cause_date='{$KEY44[reg_cause_date]}',reduction_code='{$KEY44[reduction_code]}',reduction_etc_name='{$KEY44[reduction_etc_name]}',reduction_rate='{$KEY44[reduction_rate]}',chaekwon_max='{$KEY44[chaekwon_max]}' where a1='{$KEY[a1]}' and suljung_no = '{$KEY44[suljung_no]}' ";
			} else { // insert 처리
				$sql = "insert into tbl_suljung (a1, suljung_no, bank_code, jijum_code, reg_cause_date, reduction_code, reduction_etc_name, reduction_rate, chaekwon_max) values ('{$KEY44[a1]}', '{$KEY44[suljung_no]}', '{$KEY44[bank_code]}', '{$KEY44[jijum_code]}', '{$KEY44[reg_cause_date]}', '{$KEY44[reduction_code]}', '{$KEY44[reduction_etc_name]}', '{$KEY44[reduction_rate]}', '{$KEY44[chaekwon_max]}' ) ";
			}
			db_query($sql);

		} else {
			$sql = "delete from tbl_suljung where a1='{$KEY[a1]}' ";
			db_query($sql);
		}			


		###########################################################################
		$updatewhere = " WHERE a1 = '{$KEY5[a1]}' ";
		$idx = db_replace($KEY5,$tablename,$updatewhere,"a1");
		###########################################################################
	}

//	echo "000";
//	echo "-5{$idx}";
//	echo "-6{$idx}";
//	if($idx > 0){
//	echo "-e{$updatewhere}";
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		if($mode=="i"){  //신규자료 입력
			if($s_point=="0"){
				echo "<script>alert('등록 완료되었습니다.');location.href='./regist.php?a1={$KEY[a1]}';</script>";	
			} else {
				echo "<script>alert('{$s_point}단계입력이 완료되었습니다.');location.href='./regist.php?a1={$KEY[a1]}';</script>";	
			}
		} else {
//	echo "111";
			if($s_point=="0"){
//	echo "222";
				echo "<script>alert('수정 완료되었습니다.');location.href='./regist.php?a1={$KEY[a1]}';</script>";	
			} else {
//	echo "333";
				echo "<script>alert('{$s_point}단계 수정이 완료되었습니다.');location.href='./regist.php?a1={$KEY[a1]}';</script>";	
			}
		}
		exit;
//	}


?>