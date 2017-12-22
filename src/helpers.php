<?php 

function updateUrlParams($url_params, $post){
	foreach($url_params as $k => $v){
		if(!empty($post[$k])){
			$url_params[$k] = $post[$k];
		} 
	}
	return $url_params;
}

function buildURL($url_params){
	ksort($url_params);
	$hash = md5(implode('', array_values($url_params)) . SHARED_SECRET);
	$url_params['hash'] = $hash;
	return HALLIGAN_HOST . '/' . HALLIGAN_URL . '?' . http_build_query($url_params);
}

function buildAuthorizeURL($baseUrl, $urlParams) {
	return "$baseUrl/authorize?" . http_build_query($urlParams);
}

function getRefreshAuthTokens($authURL, $payload) {
    $client = new \GuzzleHttp\Client();
    $res = $client->request('POST', "$authURL/Token.php", [
        'json' => $payload,
        'verify' => false, // ONLY ON FOR DEBUG / DEMO purposes
    ]);

    // TODO: Handle non success error codes
    return json_decode($res->getBody(), true);
}

function getUsersMe($apiURL, $accessToken) {
    $client = new \GuzzleHttp\Client();
    $res = $client->request('GET', "$apiURL/V1/users/me", [
        'headers' => ['Authorization' => $accessToken],
        'verify' => false, // ONLY ON FOR DEBUG / DEMO purposes
    ]);
    return json_decode($res->getBody(), true);        
}
