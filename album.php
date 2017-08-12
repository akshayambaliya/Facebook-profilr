<?php
if(!session_id()){
    session_start();
}

if(isset($_SESSION['facebook_access_token'])){
    $access_token = $_SESSION['facebook_access_token'];
}else{
    $graphActLink = "https://graph.facebook.com/oauth/access_token?client_id={$appId}&client_secret={$appSecret}&grant_type=client_credentials";
    $accessTokenJson = file_get_contents($graphActLink);
    $accessTokenObj = json_decode($accessTokenJson);
    $access_token = $accessTokenObj->access_token;
    $_SESSION['facebook_access_token'] = $access_token;
}

$fields = "id,name,description,link,cover_photo,count";
$fb_page_id = $_SESSION['uid'];
$graphAlbLink = "https://graph.facebook.com/v2.9/{$fb_page_id}/albums?fields={$fields}&access_token={$access_token}";
$jsonData = file_get_contents($graphAlbLink);
$fbAlbumObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);
$fbAlbumData = $fbAlbumObj['data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Display Facebook Albums and Photos on the Website using PHP by CodexWorld</title>
<style type="text/css">
.fb-album{width: 25%;padding: 10px;float: left;}
.fb-album img{width: 100%;height: 200px;}
.fb-album h3{font-size: 18px;}
.fb-album p{font-size: 14px;}
</style>
</head>
<body>
    <h1>Welcome <?php echo $_SESSION['name'] ?></h1>
    
   <a href="allphoto.php"><div style='width:25%;height:25px;text-align:center;background-color:blue;padding:10px;border-left:10px solid white;color:black'>DOWNLOAD ALL ALBUM</div></a>
<?php

// Render all photo albums
foreach($fbAlbumData as $data){
    $id = isset($data['id'])?$data['id']:'';
    $name = isset($data['name'])?$data['name']:'';
    $description = isset($data['description'])?$data['description']:'';
    $link = isset($data['link'])?$data['link']:'';
    $cover_photo_id = isset($data['cover_photo']['id'])?$data['cover_photo']['id']:'';
    $count = isset($data['count'])?$data['count']:'';
    
    $pictureLink = "photos.php?album_id={$id}&album_name={$name}";
    $downloadlink = "photozip.php?album_id={$id}&album_name={$name}";
    echo "<div class='fb-album'>";
    echo "<a href='{$pictureLink}'>";
    echo "<img src='https://graph.facebook.com/v2.9/{$cover_photo_id}/picture?access_token={$access_token}' alt=''>";
    echo "</a>";
    echo "<h3>{$name}</h3>";

    $photoCount = ($count > 1)?$count. 'Photos':$count. 'Photo';
    echo "<a href='{$downloadlink}'>";
    echo "<div style='width:90%;height:25px;text-align:center;background-color:blue;padding:10px;border-left:10px solid white;color:black;'>DOWNLOAD ALBUM</div>";
    echo "</a";
    echo "<p>{$description}</p>";
    echo "</div>";
}
?>
</body>
</html>