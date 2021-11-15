<?
$ip = get_userip();//공인아이피 가져오기

//허용 공인아이피 리스트
$iplist = array(
"222.237.19.253", //본점
"119.192.70.24",  //지점1팀
"119.192.70.118", //지점2팀
//"1.229.101.130",
//"221.158.60.97",
//"218.49.120.124",
//"125.180.139.243",
//"110.70.57.111",
//"39.7.58.235", 
"220.125.94.157", // 개발자 NAP
"221.158.60.141", // 개발자 NAP
"121.152.189.146"); // 개발자 G5

//if(!ipBlock($ip, $iplist)){
//	header('Content-Type: text/html; charset=UTF-8');
//	echo "<table width='100%' height='100%'><tr><td valign='middle' align=center>".$ip." 접근이 안되는 IP 입니다.<br><br>전산 관리자에게 문의 바랍니다.<td></tr></table>";
//	exit;
//}

function get_userip() { 
	$first_ip = @getenv(REMOTE_ADDR); 
	$second_ip = @getenv(HTTP_X_FORWARDED_FOR); // 방화벽 + 사설아이피 
	$third_ip = @getenv(HTTP_CLIENT_IP); // 방화벽 + 공인아이피 

	if (!$second_ip && !$third_ip) { 
		return $first_ip; 
	} else { 
		if($second_ip){ 
			return "$first_ip/$second_ip"; 
		} else { 
			return "$first_ip/$third_ip"; 
		} 
	}
}

function ipBlock($ip, $iplist) {  //블럭시킬 IP
  foreach ($iplist as $value) { 
   if (strpos($ip, $value) === 0) return true;
   else continue; 
  }
  return false;
}

?>