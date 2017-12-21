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