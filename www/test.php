<?php
require_once 'lib/PHPWord/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();
 
$phpWord = new \PhpOffice\PhpWord\PhpWord();
 
$section = $phpWord->addSection();
$section->addText( htmlspecialchars(
		'"Hello" World. �ȳ� ģ����'
	));
$section->addText( htmlspecialchars(
		'Lorem Ipsum �η� �Լ�'
	),
		array('name' => 'Tahoma', 'size' => 10)
	);
 
$writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="phpword1.docx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
