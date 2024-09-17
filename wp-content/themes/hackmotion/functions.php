<?php

function csv_sanitize($str) {
	$str = str_replace(',', '', $str);
	$str = str_replace(';', '', $str);
	$str = str_replace('\'', '', $str);
	$str = str_replace('"', '', $str);
	$str = str_replace('=', '', $str);
	$str = str_replace('+', '', $str);
	$str = str_replace('-', '', $str);
	$str = str_replace('@', '', $str);
	$str = str_replace('\t', '', $str);
	$str = str_replace('\n', '', $str);
	return $str;
}

session_start();

$page = $_SERVER['REQUEST_URI'];
$user_id = session_id();
$user_agent = $_SERVER['HTTP_USER_AGENT'];

$visits = fopen("visits.csv", "a");

fwrite($visits, csv_sanitize($page));
fwrite($visits, ",");
fwrite($visits, time());
fwrite($visits, ",");
fwrite($visits, $_SERVER['REMOTE_ADDR']);
fwrite($visits, ",");
fwrite($visits, csv_sanitize($user_id));
fwrite($visits, ",");
fwrite($visits, csv_sanitize($user_agent));
fwrite($visits, "\n");

fclose($visits);

function user_info($data) {
	
	$user_info = json_decode($data->get_body(), true);
	
	$info_file = fopen("user_infos.csv", "a");
	
	fwrite($info_file, session_id());
	fwrite($info_file, ",");
	fwrite($info_file, csv_sanitize($user_info["screen-width"]));
	fwrite($info_file,",");
	fwrite($info_file, csv_sanitize($user_info["screen-height"]));
	fwrite($info_file,"\n");
	
	fclose($info_file);
	
	return "OK, user info registered";
}

function video_finish_callback($data) {
	$info_file = fopen("video_finishes.csv", "a");
	
	fwrite($info_file, session_id());
	fwrite($info_file, ",");
	fwrite($info_file, time());
	fwrite($info_file, "\n");
	
	fclose($info_file);
	
	return "OK, video finish registered";
}

add_action('rest_api_init', function() {
	register_rest_route('stats', '/user-info', array(
		'methods' => 'POST',
		'callback' => 'user_info',
	));

	register_rest_route('stats', '/video-finish', array(
		'methods' => 'POST',
		'callback' => 'video_finish_callback',
	));
});
?>