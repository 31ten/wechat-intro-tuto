<?php

// APP CONFIG
// Like superglobals, the scope of a constant is global. You can access constants anywhere in your script without regard to scope
define("APPID",     "wxe97fa6dd70d513ff");
define("APPSECRET",     "8cc5d56069f4f37f4f79daf952cb4e45");

/*
 * I HELPER FUNCTIONS
 */

/*
 * Log function
 * 
 * A function to log informations (Wechat API answers etc...) and put them into a text file for easier debug
 */

function logger($stringToLog) 
{
	$now = date('j-m-y, H-i-s - '); 
	$result = $now.$stringToLog;
 	file_put_contents('logs.txt', $result.PHP_EOL , FILE_APPEND | LOCK_EX);
}

/*
 * HTTP GET function
 * 
 * A function to proceed the calls to the wechat API
 */

function httpGet($url,$post_data)
{

	logger("httpGet call to URL: ".$url);

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	if($post_data){
		//if we need to post some data, prepare it
		//http_build_query_for_curl($post_data,$post_data_string);
		//$post_data_string = http_build_query($post_data);
		$post_data_string = json_encode($post_data,JSON_UNESCAPED_UNICODE);
		logger("httpGet call POST parameters: ".$post_data_string);
		//and config our request accordingly
		//curl_setopt($c, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
		curl_setopt($curl,CURLOPT_POST, 1);
		curl_setopt($curl,CURLOPT_POSTFIELDS, $post_data_string);
	}
	curl_setopt($curl, CURLOPT_URL, $url);
	$result = curl_exec($curl);
	curl_close($curl);

	logger("httpGet call result: ".$result);

	return $result;
}

//"http://weixin.qq.com\q/02TbJzBL3eb0T1oF9eho1c
/*
 * II APPLICATION FUNCTIONS
 */

/*
 * getAccessToken
 * A function that get the access token from wechat servers and cache it for X seconds inside a JSON file
 * If the token is not expired, take it from the JSON
 */

function getAccessToken()
{
	$data = json_decode(file_get_contents("access_token.json"));
	// If expired or the access token does not exist
	if(!isset($data->expire_time) || $data->expire_time < time()){
		// call the api to get it	
		logger("getAccessToken : call the api to get it");
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".APPID."&secret=".APPSECRET;
		$result = json_decode(httpGet($url));
		$access_token = $result->access_token;
		// store it in our JSON
		if ($access_token) {
			$data->expire_time = time() + 7000; // we keep the token 2h - 200seconds for treshold security
			$data->access_token = $access_token;
			file_put_contents('access_token.json', json_encode($data) , LOCK_EX);
		}
	}else{
		// If the access token is in the JSON file and is not expired
		logger("getAccessToken : get the accesstoken from the file");
		$access_token = $data->access_token;
	}
	return $access_token;
}

/*
 * III EFFECTIVE CODE
 */

https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx56e08b08894f0d35&redirect_uri=http//dev.travelmate.31ten.cn&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect


// let test our token thing
//print getAccessToken();

// if we are calling the QRcode Scene generator
$accessToken = getAccessToken();
$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$accessToken;
$datas = array(
	"expire_seconds" => 604800,
	"action_name" => "QR_SCENE",
	"action_info" => array(
		"scene" => array(
			"scene_id" => 123
		)
	)
);
print_r(httpGet($url,$datas));

// if we are calling the service getListUser api call
// if we are calling user send message
// if we are calling the menu rebuild function