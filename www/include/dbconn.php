<?session_start();
Header("Content-type: text/html; charset=utf-8");

//error_reporting(E_ALL);
ini_set("display_errors", 0);

	global $pdo;

	$pdo = new PDO("mysql:host=localhost;dbname=gdtestserver;charset=utf8;", "gdtestserver", "pudori21");
	//$pdo = new PDO("mysql:host=localhost;dbname=gaemu;charset=utf8;", "gaemu", "melonapple!@");
	$pdo->exec("set names utf8");

	ini_set('memory_limit', '512M');

	// 1. 에러 출력하지 않음
	//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	//  2. Warning만 출력
	//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	// 3. 에러 출력
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
