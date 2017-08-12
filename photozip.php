<?php
if(!session_id()){
    session_start();
}

// Get album id from url
$album_id = isset($_GET['album_id'])?$_GET['album_id']:header("Location: index1.php");
$album_name = isset($_GET['album_name'])?$_GET['album_name']:header("Location: index1.php");

// Get access token from session
$access_token = $_SESSION['facebook_access_token'];

// Get photos of Facebook page album using Facebook Graph API
$graphPhoLink = "https://graph.facebook.com/v2.9/{$album_id}/photos?fields=source,images,name&access_token={$access_token}";
$jsonData = file_get_contents($graphPhoLink);
$fbPhotoObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);

// Facebook photos content
$fbPhotoData = $fbPhotoObj['data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Display </title>
<link rel="stylesheet" href="jquery.css">
</head>
<body>
    <?php
    $files = array();
    foreach($fbPhotoData as $data){
    $imageData = end($data['images']);
    $imgSource = isset($imageData['source'])?$imageData['source']:'';
    $name = isset($data['name'])?$data['name']:'';
    array_push($files,$imgSource);
    }
    
    $zip = new ZipArchive();

# create a temp file & open it
//$tmp_file = tempnam('.', '');
$tmp_file = "myfile.zip";
$zip->open($tmp_file, ZipArchive::CREATE);

# loop through each file
foreach ($files as $file) {
    # download file
    $download_file = file_get_contents($file);

    #add it to the zip
    $zip->addFromString(basename($file), $download_file);
}
# close zip
$zip->close();

# send the file to the browser as a download
header('Content-disposition: attachment; filename="my file.zip"');
header('Content-type: application/zip');
readfile($tmp_file);
unlink($tmp_file);
    echo "thankx";
    ?>
</body>
</html>