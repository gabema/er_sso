<?php
define('ER_AUTH_URL', '');
define('ER_API_URL', '');
define('ER_BASE_URL', '');
define('CLIENT_SECRET', '');
define('ER_CLIENT_ID', '');
define('CLIENT_REDIRECT', 'http://localhost:8080/appv2.php');

require_once 'vendor/autoload.php';
require_once 'helpers.php';
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

if(isset($_REQUEST['logoff'])){
    setcookie('access', $tokens['access_token'], (int)(mktime().time() - 60*60), '/');
    setcookie('refresh', $tokens['refresh_token'], (int)(mktime().time() - 60*60), '/');
    header('Location: ./appv2.php');
	exit();    
}

if(isset($_REQUEST['code'])){
    $authRefresh = [
        'grant_type' => 'authorization_code',
        'code' => $_REQUEST['code'],
        'client_id' => ER_CLIENT_ID,
        'client_secret' => CLIENT_SECRET,
        'redirect_uri' => CLIENT_REDIRECT,
    ];
    $tokens = getRefreshAuthTokens(ER_AUTH_URL, $authRefresh);

    // TODO: these should not be sent to the client, but should be stored and accessible on the server side
    setcookie('access', $tokens['access_token'], (int)(mktime().time() + 60*10), '/'); // good for 10 minutes
    setcookie('refresh', $tokens['refresh_token'], (int)(mktime().time() + 60*60*24*10), '/'); // good for 10 days

    // redirect to remove the code in the url.
    header('Location: ./appv2.php');
	exit();
}

if (isset($_COOKIE['access'])) {
    // TODO: Handle API errors
    $twig_params = getUsersMe(ER_API_URL, $_COOKIE['access']);
    $data = json_encode($twig_params);
    $twig_params['data'] = $data;

    echo $twig->render('me.html', $twig_params);
} else {
    $twig_params = [
        'authorizeURL' => buildAuthorizeURL(ER_BASE_URL, [
            'clientID' => ER_CLIENT_ID,
            'state' => 'xyz',
        ]),
    ];
    echo $twig->render('logintoER.html', $twig_params);
}
