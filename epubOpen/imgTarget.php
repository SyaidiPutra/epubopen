<?php
if(isset($_POST['path']) && isset($_POST['file'])){
    include('EpubRead.php');
    $epub = new EpubRead();
    $path = $_POST['path'];
    $file = $_POST['file'];
    $location = $path . $file;
    $epub->base64IMG($location);
    echo $imgBase64;
}else{
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    echo '404 Not Found';
}