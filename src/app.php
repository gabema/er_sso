<?php

define('SHARED_SECRET', 'firetruck');
define('HALLIGAN_HOST', 'http://localhost:8080');
define('HALLIGAN_URL', 'er-launch');

require_once 'vendor/autoload.php';
require_once 'helpers.php';
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);



$url_params = array(
	'userId' => 123,
	'accountId' => 456,
	'email' => 'sample@example.com',
	'password' => 'password',
	'timestamp' => time()	
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$url_params = updateUrlParams($url_params, $_POST);
}

$twig_params = $url_params;
$twig_params['url'] = buildURL($url_params);

if(isset($_POST['login'])){
	header('Location: '. $twig_params['url']);
	exit();
}

echo $twig->render('SSOForm.html', $twig_params);