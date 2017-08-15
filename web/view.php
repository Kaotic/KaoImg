<?php
/**
 * Created by PhpStorm.
 * User: Kaotic
 * Date: 14/08/2017
 * Time: 22:23
 */

require_once ('config.php');
require_once ('functions.php');

if (!isset($_GET['image']))
{
    http_response_code(404);
    die(json_encode([ 'status' => 404, 'message' => 'File not found in database.' ]));
}
if (strpos($_GET['image'], "..") !== FALSE)
{
    http_response_code(500);
    die(json_encode([ 'status' => 500, 'message' => 'Hacking attempt! nxxb.' ]));
}

$image = basename(urldecode($_GET['image']));
$noted = $bdd->query("SELECT * FROM image WHERE url_id='".$image."'");
$note = $noted->fetchAll();
$note = $note[0];

if(empty($note)){
    http_response_code(404);
    die(json_encode([ 'status' => 404, 'message' => 'File not found in database.' ]));
}

$path = KI_UPLOAD_PATH.$note['server_file'];
$mime = mime_content_type(KI_UPLOAD_PATH.$note['server_file']);
$filename = $note['filename'];
$filesize = filesize($path);
$filextension = pathinfo($path, PATHINFO_EXTENSION);

if ($mime !== FALSE)
{
    header('Content-type: ' . $mime);
    header("Content-Length: " . $filesize);
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    if(!in_array($filextension, $image_whitelist)){
        header('Content-Disposition: attachment; filename="' . $filename . '"');
    }

    ob_clean();
    flush();
    readfile($path);
}
