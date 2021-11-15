<?
	include ("./include/dbconn.php");
	include ("./include/function.php");

	$code	=	trim($_REQUEST["cmpny_code"]);
	$id	=	trim($_REQUEST["id"]);
	$pwd	=	trim($_REQUEST["pwd"]);

	$sql= "select * from tbl_user where id='{$id}' ";
	//echo $sql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();
	$regist_date = date("YmdHis");
	$user_ip = $_SERVER['REMOTE_ADDR'];

	if ($row[idx]!="") {  //있다면

		if($row[login_error]>10000){  //오류가 3회초과라면
				echo "<script>alert('입력내용이 3회 초과 맞지않습니다.관리자에게 문의하세요. ');</script>";
				echo "<script>location.href='/';</script>";
		}else{
			$sql= "select * from tbl_user where id='$id' and pwd = password('{$pwd}') ";
			$stmt1 = $pdo->prepare($sql);
			$stmt1->execute();
			$stmt1->setFetchMode(PDO::FETCH_ASSOC);
			$row1 = $stmt1->fetch();

			if($row1[idx]==""){  //없다면 비밀번호가 틀린거임
					$sql="update tbl_user set login_error=login_error+1 where id='$id'";  //틀린횟수 증가
					//echo $sql;
					db_query($sql);
					$sql="insert into tbl_login_user values('{$regist_date}','{$id}','N','{$user_ip}','로그인','로그인','login_ok.php','') ";  //로그정보
					//echo $sql;
					db_query($sql);
					echo "<script>alert('암호가 맞지 않습니다.관리자에게 문의하세요. ');</script>";
					echo "<script>location.href='/';</script>";
			}else{  //맞다면

				if ($row["grade"]!=999){  //퇴사자가 아니라면 접속허용
					$sql="update tbl_user set login_date='{$regist_date}' where id='$id'";
					//echo $sql;
					db_query($sql);

					$sql="insert into tbl_login_user values('{$regist_date}','{$id}','N','{$user_ip}','로그인','로그인','login_ok.php','') ";  //로그정보
					//echo $sql;
					db_query($sql);

					$_SESSION["admin_code"]			=	$row["code"];
					$_SESSION["admin_id"]				=	$row["id"];
					$_SESSION["admin_idx"]			=	$row["idx"];
					$_SESSION["admin_name"]			=	$row["name"];
					$_SESSION["admin_grade"]		=	$row["grade"];
					$_SESSION["admin_sosok"]		=	$row["sosok"];

					$sql = "select * from tbl_user_permission where u_idx='{$row[idx]}' limit 1";
					//echo $sql;
					$_SESSION["admin_permission"] = db_query_fetch($sql);

					echo "<script>location.href='/start.php';</script>";
				}else{
					echo "<script>alert('접속하실수 없습니다.관리자에게 문의하세요. ');</script>";
					echo "<script>location.href='/';</script>";
				}
			}
		}

	}else{

		echo "<script>alert('접속하실수 없습니다.관리자에게 문의하세요. ');</script>";
		echo "<script>location.href='/';</script>";

	}
?>