<?php
/**
 * Created by PhpStorm.
 * User: Kaotic
 * Date: 14/08/2017
 * Time: 21:38
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

$fileserver = $note['server_file'];
$path = KI_UPLOAD_PATH.$fileserver;
$filemime = mime_content_type($path);
$filename = $note['filename'];
$file_process = $note['process_name'];
$file_session = $note['session_name'];
$file_date = $note['screen_date'];
$file_time = $note['screen_time'];
$filesize = filesize($path);
$filextension = pathinfo($path, PATHINFO_EXTENSION);
$isimage = in_array($filextension, $image_whitelist);

if(isset($_POST['token'])){
    if($_POST['token'] == KI_SECURITY_TOKEN){
        $bdd->exec("DELETE FROM image WHERE url_id='".$image."'");
        unlink($path);
        header('Location: /');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $fileserver; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="//fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        body {
            font: 20px Montserrat, sans-serif;
            line-height: 1.8;
            color: #f5f6f7;
        }
        p {font-size: 16px;}
        .margin {margin-bottom: 45px;}
        .bg-1 {
            background-color: #1abc9c; /* Green */
            color: #ffffff;
        }
        .bg-2 {
            background-color: #474e5d; /* Dark Blue */
            color: #ffffff;
        }
        .bg-4 {
            background-color: #2f2f2f; /* Black Gray */
            color: #fff;
        }
        .container-fluid {
            padding-top: 50px;
            padding-bottom: 50px;
        }
    </style>
</head>
<body>

<div class="container-fluid bg-1 text-center">
    <?php
    if($isimage){
        ?> <img src="/<?= $image; ?>" class="img-responsive margin" style="display:inline"> <?php
    }else{
        ?> <h3 class="margin">Pas de preview du fichier disponible.</h3> <?php
    }
    ?>

</div>

<div class="container-fluid bg-2 text-center">
    <h3 class="margin">Informations du fichier :</h3>
    <?php
    if($isimage){
        ?>
        <p><b>Nom du fichier:</b> <i><?= $fileserver; ?></i></p>
        <p><b>Nom du processus:</b> <i><?= $file_process; ?></i></p>
        <p><b>Nom de la session:</b> <i><?= $file_session; ?></i></p>
        <p><b>Date de capture:</b> <i><?= $file_date; ?></i></p>
        <p><b>Temps de capture:</b> <i><?= $file_time; ?></i></p>

        <?php
    }else{
        ?>
        <p><b>Nom du fichier:</b> <i><?= $filename; ?></i></p>
        <?php
    }
    ?>

    <p><b>Taille du fichier:</b>  <i><?= humanFileSize($filesize); ?></i></p>
    <p><b>Extension du fichier:</b>  <i><?= $filextension; ?></i></p>
    </br>
    <form class="form-inline" action="/delete/<?= $image; ?>" method="post">
        <label class="sr-only" for="token">Token</label>
        <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="token" name="token" placeholder="Token">
        <button type="submit" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-remove"></span> Suppression</button>
    </form>
</div>

<footer class="container-fluid bg-4 text-center">
    <p>KaoImage created by <a href="https://kaotic.us/">Kaotic.us</a> in 1.5 hours.</p>
</footer>

</body>
</html>


