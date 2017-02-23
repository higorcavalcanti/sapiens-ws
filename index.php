<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

function __autoLoad($class) {
	require("{$class}.class.php");
}


$info = isset($_GET['info']) ? $_GET['info'] : 'login';
$user = isset($_POST['user']) ? $_GET['user'] : '';
$pass = isset($_POST['pass']) ? $_GET['pass'] : '';

$sapiens = new Sapiens();

$logar = $sapiens->login($user, $pass);
if(@!$logar['logado'] || $info == 'login') {
	echo json_encode($logar);
	exit;
}

if($info == 'notas') echo $sapiens->notas();
if($info == 'horarios') echo $sapiens->horarios();
?> 