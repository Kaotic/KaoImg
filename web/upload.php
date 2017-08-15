<?php
/**
 * Created by PhpStorm.
 * User: Kaotic
 * Date: 14/08/2017
 * Time: 21:38
 */

require_once ('config.php');
require_once ('functions.php');

if (!isset($_POST["token"]) || $_POST["token"] !== KI_SECURITY_TOKEN)
{
    http_response_code(403);
    die(json_encode([ 'status' => 403, 'message' => 'Invalid access token.' ]));
}
if (!isset($_FILES[KI_FILE_PARAM]))
{
    http_response_code(500);
    die(json_encode([ 'status' => 500, 'message' => 'Missing file.' ]));
}

$file = $_FILES[KI_FILE_PARAM];
$filename = $file["name"];
$parts = explode(".", $filename);
$info_p = explode("_", $parts[0]);
$target_path = KI_UPLOAD_PATH.$filename;

if (strpos($target_path, "..") !== FALSE)
{
    http_response_code(500);
    die(json_encode([ 'status' => 500, 'message' => 'Hacking attempt! nxxb.' ]));
}

if(isset($info_p[0])) { $process_name = $info_p[0].".exe"; }else{ $process_name = "null"; }
if(isset($info_p[1])) { $session_name = $info_p[1]; }else{ $session_name = "null"; }
if(isset($info_p[2])) { $screen_date = $info_p[2]; }else{ $screen_date = "null"; }
if(isset($info_p[3])) { $screen_time = $info_p[3]; }else{ $screen_time = "null"; }
$url_id = randomString(KI_LENGTH_GEN_URL);
$server_file = $url_id.".".$parts[1];

if ($file['size'] > KI_MAX_FILE_SIZE)
{
    http_response_code(500);
    die(json_encode([ 'status' => 500, 'message' => 'File is too big.' ]));
}

if(move_uploaded_file($file['tmp_name'], $target_path)){
    rename($target_path, KI_UPLOAD_PATH.$server_file);
    $addImage = $bdd->prepare('INSERT INTO image SET process_name=:process_name, session_name=:session_name, screen_date=:screen_date, screen_time=:screen_time, filename=:filename, server_file=:server_file, url_id=:url_id');
    $addImage->execute(array(
        'process_name' => $process_name,
        'session_name' => $session_name,
        'screen_date' => $screen_date,
        'screen_time' => $screen_time,
        'filename' => $filename,
        'server_file' => $server_file,
        'url_id' => $url_id
    ));
    $data = array('direct' => KI_SITE_URL.$url_id, 'thumb' => KI_SITE_URL."thumb/".$url_id, 'delete' => KI_SITE_URL."delete/".$url_id);
    die(json_encode([ 'status' => 200, 'data' => $data ]));
}