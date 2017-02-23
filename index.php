<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

function __autoLoad($class) {
	require("{$class}.class.php");
}

$_POST = json_decode(file_get_contents('php://input'), true);

$info = isset($_GET['info']) ? $_GET['info'] : 'login';
$user = isset($_POST['user']) ? $_POST['user'] : '';
$pass = isset($_POST['pass']) ? $_POST['pass'] : '';

$sapiens = new Sapiens();

$logar = $sapiens->login($user, $pass);
if(@!$logar['logado'] || $info == 'login') {
	echo json_encode($logar);
	exit;
}

if($info == 'notas') echo $sapiens->notas();
if($info == 'horarios') echo $sapiens->horarios();
?> 