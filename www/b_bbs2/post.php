<?	include ("../include/dbconn.php");
	include ("../include/login_ch.php");
	include ("../include/function.php");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	$board_dbname	=	"board_data";

	$board_uid	=	trim($_REQUEST[board_uid]);

	$SavePath	=	$_SERVER['DOCUMENT_ROOT'] . "/files/UploadFiles";
	$filenum = 5;
	
	for($i=1;$i<=$filenum;$i++){

		if(is_uploaded_file($_FILES["UpFile".$i]["tmp_name"])){
			//echo "-2-";
			## 이전파일이 존재한다면 삭제한다.	
			$oldfile_name = $SavePath. "/" . $_FILES["old_file".$i];
			if(file_exists($oldfile_name)){
				@unlink($oldfile_name);
			}
			
			$tempfile 	= $_FILES["UpFile".$i];		## 업로드된 파일
			$tempfile_name 	= $_FILES["UpFile".$i]["name"];	## 업로드된 파일명
			$tempfile_size 	= $_FILES["UpFile".$i]["size"];	## 업로드된 파일사이즈
			$tempfile_type 	= $_FILES["UpFile".$i]["type"];	## 업로드된 파일타입
		
			$strFileName = guid();				##GUID로 저장될 파일명을 만듬!
			//$Ext_Array = explode(".", $tempfile_name);	
			//$Ext = $Ext_Array[1];	## 확장자를 추출	
			$Ext = f_ext($tempfile_name);

			$strCopyFile = $strFileName.".".$Ext;
			$Save_dir = $SavePath . "/" . $strCopyFile;

			## 임시 디렉토리에 저장된 바이너리 파일을 해당 디렉토리에 복사한다
			//echo $Save_dir;
			move_uploaded_file($_FILES["UpFile".$i]['tmp_name'], $Save_dir);


			## 인서트 필드 배열에 답는다. 
			$KEY["UpFile".$i] = $strCopyFile;
			$KEY["UpFile_Name".$i] = $tempfile_name . "|" . $tempfile_size;
			//echo $tempfile;
			//echo "<br>------------------------<br>";
			//print_r($KEY);

		## 업로드를 하지 않았다면
		} else {

			//echo "N";
			## 파일 삭제를 선택했으면 파일 삭제후 인서트 필드 배열에 빈값을 넣는다.
			$temp_delete = ${"del_".$i};	
			if($temp_delete == 'Y'){
				$oldfile_name = $SavePath. "/" . ${"org_".$i};
				@unlink($oldfile_name);
				$KEY["UpFile".$i] = '';
				$KEY["UpFile_Name".$i] = '';
			}
		}
	}
			//echo "<br>------------------------<br>";

if($board_uid==""){

	$KEY[board_insertdate]	=	date("Ymd");
	$KEY[board_hit]		=	"1";

}else{

	$KEY[board_insertdate]	=	date("Ymd");
	$KEY[board_modifydate]	=	date("Ymd");
}


	$KEY[board_subject]	=	trim(addslashes($_REQUEST[board_subject]));
	$KEY[board_note]	=	trim(addslashes($_REQUEST[board_note]));
//	$KEY[board_note]	=	$KEY[board_note].replace("^", "\'" );

	$KEY[board_writer]	=	$_SESSION["admin_id"];

	$updatewhere = " WHERE board_uid = '{$board_uid}' ";
	$board_uid = db_replace($KEY,$board_dbname,$updatewhere,"board_uid");

	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<script>alert('처리 되었습니다.');location.href='index.html';</script>";
	?>