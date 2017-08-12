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
 <div id="slides">
   <div class="slides-container">
    <?php
    foreach($fbPhotoData as $data){
    $imageData = end($data['images']);
    $imgSource = isset($imageData['source'])?$imageData['source']:'';
    $name = isset($data['name'])?$data['name']:'';
    ?>
    <img src='<?php echo $imgSource ?>' width="1000" height="683" alt="Cinelli">
    
    <?    
    }
    ?>
 </div>
  <nav class="slides-navigation">
      <a href="#" class="next">Next</a>
      <a href="#" class="prev">Previous</a>
    </nav>
  </div>

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="javascripts/jquery.easing.1.3.js"></script>
  <script src="javascripts/jquery.animate-enhanced.min.js"></script>
  <script src="jquery.js" type="text/javascript" charset="utf-8"></script>
  <script>
    $(function() {
      $('#slides').superslides({
        hashchange: true
      });
    });
  </script>
</body>
</html>