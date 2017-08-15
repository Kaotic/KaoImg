<?php
/**
 * Created by PhpStorm.
 * User: Kaotic
 * Date: 14/08/2017
 * Time: 21:38
 */
define ('KI_MAX_FILE_SIZE', 250 * 1024 * 1024); //don't touch

define ('KI_SECURITY_TOKEN', "your_token_key");
define ('KI_FILE_PARAM', "file");
define ('KI_LENGTH_GEN_URL', 8);
define ('KI_UPLOAD_PATH', "/var/www/users/user1/site9/web/kaoimg/uploads/");
define ('KI_SITE_URL', "https://img.kaotic.us/");

$image_whitelist = array('jpg', 'jpeg', 'png', 'gif','bmp');

$preconfig = array(
    //MySQL//
    'dbhost' => 'localhost',
    'dbuser' => 'XXXX',
    'dbpass' => 'XXXX',
    'dbbase' => 'XXXX',
    'debug' => false
);

try{
    $bdd = new PDO('mysql:host='.$preconfig['dbhost'].';dbname='.$preconfig['dbbase'].';charset=utf8', $preconfig['dbuser'], $preconfig['dbpass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $bdd->exec("SET CHARACTER SET utf8");
}
catch (Exception $e){
    if($preconfig['debug'] == true){
        die('Erreur : ' . $e->getMessage());
    }
    header('content-type: text/html; charset=utf-8');
    die("Erreur de traitement des données.");
}