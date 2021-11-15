<!--
//마우스 클릭시
function focusFunction(p1){
	if(p1.name!=""){
		var jt = p1.name.split('_');
		//alert(jt.length);

		if(jt.length==5){
			kk = jt[0]+"_"+jt[1]+"_"+jt[2]+"_"+jt[3]+"1_"+jt[4];
			if(jt[0].substr(0,1)=="s"){
				$("input:checkbox[name='x_"+p1.name+"']").prop("checked", true);
			}else{
				$("input:checkbox[name='"+kk+"']").prop("checked", true);
			}
		}else if(jt.length==4){
			kk = jt[0]+"_"+jt[1]+"_"+jt[2]+"1_"+jt[3];
			if(jt[0].substr(0,1)=="s"){
				$("input:checkbox[name='x_"+p1.name+"']").prop("checked", true);
			}else{
				$("input:checkbox[name='"+kk+"']").prop("checked", true);
			}
		}else if(jt.length==3){
			kk = jt[0]+"_"+jt[1]+"1_"+jt[2];
			$("input:checkbox[name='"+kk+"']").prop("checked", true);
		}else if(jt.length==2){
			kk = jt[0]+"1_"+jt[1];
			$("input:checkbox[name='"+kk+"']").prop("checked", true);
		}
	}
}

function f_movex(p1){
	$("#view_num").val(p1.value);
	document.ffx.submit();
}

//현재 page 검색
function f_movex2(p1){
	$("#page").val(p1);
	document.ffx.submit();
}

//현재 pgae 앞,뒤 검색
function f_movex3(p1,p2){
	$("#page_now").val(p1);
	$("#page").val(p2);
	document.ffx.submit();
}

function f_h_idx(){
	var h_idx=	$("#h_idx option:selected").val();
	var p2 = "bank_code";
//	alert(h_idx);
	//location.href = "/ajax_t/ajax_select_h_idx.php?h_idx=8";

	if(h_idx!=""){
			$.ajax({
				type:"GET",
				url:'/ajax_t/ajax_select_h_idx.php',
				dataType:"json",
				data:{h_idx:h_idx},
				timeout : 30000,
				success:function (req) {
					var chk_pnum =0;
					var return_html = "";
					$.each(req,function(ind,ep){
						//alert(ep);
						if(ep.chk == 1){
							return_html += "<option value='"+ep.bank_code+"' selected>"+ep.bank_name+"</option>";
							chk_pnum = ep.num;
						}else{
							return_html += "<option value='"+ep.bank_code+"'>"+ep.bank_name+"</option>";
						}
					});
					//alert(return_html);
					$("#"+p2).html(return_html);
					$("#jijum_code").html("<option value=''>--지점--</option>");
					//alert("--");
				},
				error : function(request, status, error) {
					//통신 에러 발생시 처리
					//alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
				}
			});
	}else{  //초기화시

		//은행은 전체 나오게
			$.ajax({
				type:"GET",
				url:'/ajax_t/ajax_bank_all.php',
				dataType:"json",
				data:{h_idx:h_idx},
				timeout : 30000,
				success:function (req) {
					var chk_pnum =0;
					var return_html = "";
					$.each(req,function(ind,ep){
						//alert(ep);
						if(ep.chk == 1){
							return_html += "<option value='"+ep.bank_code+"' selected>"+ep.bank_name+"</option>";
							chk_pnum = ep.num;
						}else{
							return_html += "<option value='"+ep.bank_code+"'>"+ep.bank_name+"</option>";
						}
					});
					//alert(return_html);
					$("#bank_code").html(return_html);
					$("#jijum_code").html("<option value=''>--지점--</option>");
					//alert("--");
				},
				error : function(request, status, error) {
					//통신 에러 발생시 처리
					//alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
				}
			});

	}
}

function f_h_idx_cnt(){
	var h_idx=	$("#h_idx option:selected").val();
	var p2 = "bank_code_1";
	var p3 = "jijum_code_1";
	var p4 = "bank_code_2";
	var p5 = "jijum_code_2";
	var p6 = "bank_code_3";
	var p7 = "jijum_code_3";
	var p8 = "bank_code_4";
	var p9 = "jijum_code_4";
	//alert(h_idx);
	//location.href = "/ajax_t/ajax_select_h_idx.php?h_idx=8";

	if(h_idx!=""){
			$.ajax({
				type:"GET",
				url:'/ajax_t/ajax_select_h_idx.php',
				dataType:"json",
				data:{h_idx:h_idx},
				timeout : 30000,
				success:function (req) {
					var chk_pnum =0;
					var return_html = "";
					$.each(req,function(ind,ep){
						//alert(ep);
						if(ep.chk == 1){
							return_html += "<option value='"+ep.bank_code+"' selected>"+ep.bank_name+"</option>";
							chk_pnum = ep.num;
						}else{
							return_html += "<option value='"+ep.bank_code+"'>"+ep.bank_name+"</option>";
						}
					});
					//alert(return_html);
					$("#"+p2).html(return_html);
					$("#"+p3).html("<option value=''>--지점--</option>");
					//alert("--");
				},
				error : function(request, status, error) {
					//통신 에러 발생시 처리
					//alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
				}
			});

			$.ajax({
				type:"GET",
				url:'/ajax_t/ajax_select_h_idx.php',
				dataType:"json",
				data:{h_idx:h_idx},
				timeout : 30000,
				success:function (req) {
					var chk_pnum =0;
					var return_html = "";
					$.each(req,function(ind,ep){
						//alert(ep);
						if(ep.chk == 1){
							return_html += "<option value='"+ep.bank_code+"' selected>"+ep.bank_name+"</option>";
							chk_pnum = ep.num;
						}else{
							return_html += "<option value='"+ep.bank_code+"'>"+ep.bank_name+"</option>";
						}
					});
					//alert(return_html);
					$("#"+p4).html(return_html);
					$("#"+p5).html("<option value=''>--지점--</option>");
					//alert("--");
				},
				error : function(request, status, error) {
					//통신 에러 발생시 처리
					//alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
				}
			});

			$.ajax({
				type:"GET",
				url:'/ajax_t/ajax_select_h_idx.php',
				dataType:"json",
				data:{h_idx:h_idx},
				timeout : 30000,
				success:function (req) {
					var chk_pnum =0;
					var return_html = "";
					$.each(req,function(ind,ep){
						//alert(ep);
						if(ep.chk == 1){
							return_html += "<option value='"+ep.bank_code+"' selected>"+ep.bank_name+"</option>";
							chk_pnum = ep.num;
						}else{
							return_html += "<option value='"+ep.bank_code+"'>"+ep.bank_name+"</option>";
						}
					});
					//alert(return_html);
					$("#"+p6).html(return_html);
					$("#"+p7).html("<option value=''>--지점--</option>");
					//alert("--");
				},
				error : function(request, status, error) {
					//통신 에러 발생시 처리
					//alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
				}
			});

			$.ajax({
				type:"GET",
				url:'/ajax_t/ajax_select_h_idx.php',
				dataType:"json",
				data:{h_idx:h_idx},
				timeout : 30000,
				success:function (req) {
					var chk_pnum =0;
					var return_html = "";
					$.each(req,function(ind,ep){
						//alert(ep);
						if(ep.chk == 1){
							return_html += "<option value='"+ep.bank_code+"' selected>"+ep.bank_name+"</option>";
							chk_pnum = ep.num;
						}else{
							return_html += "<option value='"+ep.bank_code+"'>"+ep.bank_name+"</option>";
						}
					});
					//alert(return_html);
					$("#"+p8).html(return_html);
					$("#"+p9).html("<option value=''>--지점--</option>");
					//alert("--");
				},
				error : function(request, status, error) {
					//통신 에러 발생시 처리
					//alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
				}
			});
	}else{  //초기화시

		//은행은 전체 나오게
		$("#bank_code_1").html("<option value=''>--은행--</option>");
		$("#jijum_code_1").html("<option value=''>--지점--</option>");
		$("#bank_code_2").html("<option value=''>--은행--</option>");
		$("#jijum_code_2").html("<option value=''>--지점--</option>");
		$("#bank_code_3").html("<option value=''>--은행--</option>");
		$("#jijum_code_3").html("<option value=''>--지점--</option>");
		$("#bank_code_4").html("<option value=''>--은행--</option>");
		$("#jijum_code_4").html("<option value=''>--지점--</option>");

	}
}

function select_detail(p1,p2){
	var p1v		=	$("#"+p1+" option:selected").val();
	var p2v		=	$("#"+p2+" option:selected").val();
	var h_idx	=	$("#h_idx").val();

	//alert(h_idx);
	//location.href = "/ajax_t/ajax_select.php?p1="+p1+"&h_idx="+h_idx;

	if(p1v!=""){
			$.ajax({
				type:"GET",
				url:'/ajax_t/ajax_select.php',
				dataType:"json",
				data:{p1:p1v,h_idx:h_idx},
				timeout : 30000,
				success:function (req) {
					var chk_pnum =0;
					var return_html = "";
					$.each(req,function(ind,ep){
						//alert(ep);
						if(ep.chk == 1){
							return_html += "<option value='"+ep.jijum_code+"' selected>"+ep.jijum_name+"</option>";
							chk_pnum = ep.num;
						}else{
							return_html += "<option value='"+ep.jijum_code+"'>"+ep.jijum_name+"</option>";
						}
					});
					//alert(return_html);
					$("#"+p2).html(return_html);
					//alert("--");
				},
				error : function(request, status, error) {
					//통신 에러 발생시 처리
					//alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
				}
			});
	}else{
		var return_html = "";
		return_html += "<option value=''>--선택--</option>";
		$("#"+p2).html(return_html);
	}
}


function select_detail_all2(p1,p2){
	var p1v		=	$("#"+p1+" option:selected").val();
	var p2v		=	$("#"+p2+" option:selected").val();
	var h_idx	=	$("#h_idx").val();

	//alert(h_idx);
	//location.href = "/ajax_t/ajax_select.php?p1="+p1+"&h_idx="+h_idx;

	if(p1v!=""){
			$.ajax({
				type:"GET",
				url:'/ajax_t/ajax_select_all.php',
				dataType:"json",
				data:{p1:p1v,h_idx:h_idx},
				timeout : 30000,
				success:function (req) {
					var chk_pnum =0;
					var return_html = "";
					$.each(req,function(ind,ep){
						//alert(ep);
						if(ep.chk == 1){
							return_html += "<option value='"+ep.jijum_code+"' selected>"+ep.jijum_name+"</option>";
							chk_pnum = ep.num;
						}else{
							return_html += "<option value='"+ep.jijum_code+"'>"+ep.jijum_name+"</option>";
						}
					});
					//alert(return_html);
					$("#"+p2).html(return_html);
					//alert("--");
				},
				error : function(request, status, error) {
					//통신 에러 발생시 처리
					//alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
				}
			});
	}else{
		var return_html = "";
		return_html += "<option value=''>--선택--</option>";
		$("#"+p2).html(return_html);
	}
}

function select_detail_all(p1,p2){
	var p1v		=	$("#"+p1+" option:selected").val();
	var p2v		=	$("#"+p2+" option:selected").val();
	var h_idx	=	"";

	//alert(h_idx);
	//location.href = "/ajax_t/ajax_select.php?p1="+p1+"&h_idx="+h_idx;

	if(p1v!=""){
			$.ajax({
				type:"GET",
				url:'/ajax_t/ajax_select.php',
				dataType:"json",
				data:{p1:p1v,h_idx:h_idx},
				timeout : 30000,
				success:function (req) {
					var chk_pnum =0;
					var return_html = "";
					$.each(req,function(ind,ep){
						//alert(ep);
						if(ep.chk == 1){
							return_html += "<option value='"+ep.jijum_code+"' selected>"+ep.jijum_name+"</option>";
							chk_pnum = ep.num;
						}else{
							return_html += "<option value='"+ep.jijum_code+"'>"+ep.jijum_name+"</option>";
						}
					});
					//alert(return_html);
					$("#"+p2).html(return_html);
					//alert("--");
				},
				error : function(request, status, error) {
					//통신 에러 발생시 처리
					//alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
				}
			});
	}else{
		var return_html = "";
		return_html += "<option value=''>--선택--</option>";
		$("#"+p2).html(return_html);
	}
}


function select_danji(){
	var h_idx=	$("#h_idx option:selected").val();
	var p2 = "b1";
	//alert(h_idx);
	//location.href = "/ajax_t/ajax_select_h_idx.php?h_idx=8";

	if(h_idx!=""){
			$.ajax({
				type:"GET",
				url:'/ajax_t/ajax_danji.php',
				dataType:"json",
				data:{h_idx:h_idx},
				timeout : 30000,
				success:function (req) {
					var chk_pnum =0;
					var return_html = "";
					$.each(req,function(ind,ep){
						//alert(ep);
						if(ep.chk == 1){
							return_html += "<option value='"+ep.b1+"' selected>"+ep.danji_name+"</option>";
							chk_pnum = ep.num;
						}else{
							return_html += "<option value='"+ep.b1+"'>"+ep.danji_name+"</option>";
						}
					});
					//alert(return_html);
					$("#"+p2).html(return_html);
					//alert("--");
				},
				error : function(request, status, error) {
					//통신 에러 발생시 처리
					//alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
				}
			});
	}else{
		var return_html = "";
		//return_html += "<option value=''>--선택--</option>";
		$("#"+p2).html(return_html);
	}
}



// 체크박스 전체선택 ##################################################
function checkCbAll(cbList, isChecked) {
	if (cbList) {
		if (typeof(cbList.length) == "undefined") {
			if (!cbList.disabled) cbList.checked = isChecked;
		}
		else {
			for (var i=0; i<cbList.length; i++) {
				if (cbList[i].type.toUpperCase() == 'CHECKBOX') {
						cbList[i].checked = isChecked;
				}
			}
		}
	}
}


function numCheck(p1)
 {

		var strr = "-0123456789";
		{
			Number_Value = p1.value;
			for (i = 0; i < Number_Value .length; i++)
			{
				for (j = 0; j < strr.length; j++)
					if (Number_Value .charAt(i) == strr.charAt(j)) break;
				if (j == strr.length)
				{
					alert("숫자만 입력가능합니다.");
					p1.focus();
					p1.value = "";
					return false;
				}
			}
		}
   }

function numCheck2(p1)
 {

		var strr = "-0123456789";
		{
			Number_Value = p1;
			for (i = 0; i < Number_Value .length; i++)
			{
				for (j = 0; j < strr.length; j++)
					if (Number_Value .charAt(i) == strr.charAt(j)) break;
				if (j == strr.length)
				{
					return false;
				}
			}
			return true;
		}
   }



function input_color_change_in(p1){
	p1.style.border="2px solid #66CC66";
}

function input_color_change_out(p1){
	p1.style.border="1px solid #a0a0a0";
}

//메뉴 색상 변경
function  theme_change(p1){
	document.getElementById("theme_table").className = "theme_table"+p1;
	document.getElementById("theme_table_td_title").className = "theme_table_td_title"+p1;
	document.getElementById("theme_table_td").className = "theme_table_td"+p1;
	parent.document.frames["blankx"].document.location.href = "/include/cookie.asp?pp="+p1;
	parent.document.frames["main"].document.getElementById("theme_colorxx").className = "theme_table_td_title"+p1;
}

//메뉴 색상변경 유지
function  theme_change_menu(p1){
	document.getElementById("theme_table").className = "theme_table"+p1;
	document.getElementById("theme_table_td_title").className = "theme_table_td_title"+p1;
	document.getElementById("theme_table_td").className = "theme_table_td"+p1;
}

//메인 프레임 색상 변경 유지
function  theme_change_main(p1){
	//테마색이 지정안되있을시에는
//		alert(getCookie("theme_color_no"));
		document.getElementById("theme_colorxx").className = "theme_table_td_title"+p1;
}

//숫자 체크
function ck_digit(target){
  var t = target.value;
  var Digit = '1234567890.,';
  if (Digit.length > 1) {
    for (i=0; i< t.length; i++)
      if(Digit.indexOf(t.substring(i,i+1))<0) {
        alert('입력하신 값에 문자가 있습니다.  \n\n숫자만 입력해주세요.');
        target.value="";
        target.focus();
        return false;
        break;
      }
  }
 }


//자바 쿠키설정
function setCookie(name,value,expiredays){
	alert(name+"------"+value);
	var today = new Date();
	today.setDate( today.getDate() + expiredays );
	document.cookie = name + "=" + escape( value ) + ";path=/;expires=" + today.toGMTString() + ";"
}

//자바 쿠키읽기
function getCookie(c_name)
{
if (document.cookie.length>0)
  {
  c_start=document.cookie.indexOf(c_name + "=");
  if (c_start!=-1)
    {
    c_start=c_start + c_name.length+1;
    c_end=document.cookie.indexOf(";",c_start);
    if (c_end==-1) c_end=document.cookie.length;
    return unescape(document.cookie.substring(c_start,c_end));
    }
  }
return "";
}


//윈도우 팝업 창 화면 가운데 띄우기
function center_popup(mypage, myname, w, h, scroll) {
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable=yes,status=yes';
//		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars=no,resizable=no';
		win = window.open(mypage, myname, winprops);
		if (parseInt(navigator.appVersion) >= 4) {
			win.window.focus();
		}
}

function top_alert(p1){
	parent.document.frames['top_menu'].document.getElementById('memo').innerHTML =p1;
}


String.prototype.comma = function() {
    tmp = this.split('.');
    var str = new Array();
    var v = tmp[0].replace(/,/gi,'');
    for(var i=0; i<=v.length; i++) {
        str[str.length] = v.charAt(v.length-i);
        if(i%3==0 && i!=0 && i!=v.length) {
            str[str.length] = '.';
        }
    }
    str = str.reverse().join('').replace(/\./gi,',');
    return (tmp.length==2) ? str + '.' + tmp[1] : str;
}

function onlyNum(obj) {
    var val = obj.value;
    var re = /[^0-9\.\,\-]/gi;
	obj.value = val.replace(re, '');

	//<input type="text" onkeyup="onlyNum(this);this.value=this.value.comma();" /> 예제
}

function setcomma(str){
	return Number(String(str).replace(/\..*|[^\d]/g,"")).toLocaleString().slice(0,-3);
}

function set_comma(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}



function commax(n) {
 var reg = /(^[+-]?\d+)(\d{3})/;
 n += '';

 while (reg.test(n)) {
  n = n.replace(reg, '$1' + ',' + '$2');
 }

 return n;
}

function de_comma(str) {
		//alert("---"+str);
		n = parseFloat(str.replace(/,/g,""));
		//alert("---"+n);
		return n;
}

function f_date0(){//당일
		$("#s_date").val(caldate(0));
		$("#e_date").val(caldate(0));
}
function f_date1(){//3일
		$("#s_date").val(caldate(3));
		$("#e_date").val(caldate(0));
}
function f_date2(){//1주일
		$("#s_date").val(caldate(7));
		$("#e_date").val(caldate(0));
}
function f_date3(){//2주일
		$("#s_date").val(caldate(14));
		$("#e_date").val(caldate(0));
}
function f_date4(){//1개월
		$("#s_date").val(caldate(30));
		$("#e_date").val(caldate(0));
}
function f_date5(){//3개월
		$("#s_date").val(caldate(90));
		$("#e_date").val(caldate(0));
}

function f_date20(){//당일
		$("#s_date2").val(caldate(0));
		$("#e_date2").val(caldate(0));
}
function f_date21(){//3일
		$("#s_date2").val(caldate(3));
		$("#e_date2").val(caldate(0));
}
function f_date22(){//1주일
		$("#s_date2").val(caldate(7));
		$("#e_date2").val(caldate(0));
}
function f_date23(){//2주일
		$("#s_date2").val(caldate(14));
		$("#e_date2").val(caldate(0));
}
function f_date24(){//1개월
		$("#s_date2").val(caldate(30));
		$("#e_date2").val(caldate(0));
}
function f_date25(){//3개월
		$("#s_date2").val(caldate(90));
		$("#e_date2").val(caldate(0));
}

// 날짜를 입력 하면 오늘 날짜로부터 숫자만큼 전날의 날짜를 mm/dd/yyyy 형식으로 돌려 준다.
function caldate(day){

	 var caledmonth, caledday, caledYear;
	 var loadDt = new Date();
	 var v = new Date(Date.parse(loadDt) - day*1000*60*60*24);

	 caledYear = v.getFullYear();

	 if( parseInt(v.getMonth()) < 9 ){
	  caledmonth = '0'+(v.getMonth()+1);
	 }else{
	  caledmonth = v.getMonth()+1;
	 }

	 if( parseInt(v.getDate()) < 10 ){
	  caledday = '0'+v.getDate();
	 }else{
	  caledday = v.getDate();
	 }
	 //return caledYear+caledmonth+caledday;
	 return caledYear.toString()+caledmonth.toString()+caledday.toString();

}


//-->