<?php
// Include FB config file && User class
require_once 'fbConfig.php';

if(isset($accessToken)){
  if(isset($_SESSION['facebook_access_token'])){
    $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    
  }else{
    $_SESSION['facebook_access_token'] = (string) $accessToken;
    $oAuth2Client = $fb->getOAuth2Client();
    $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
    $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
  }
  if(isset($_GET['code'])){
    header('Location: ./');
  }
  // Getting user facebook profile info
  try {
    $profileRequest = $fb->get('/me?fields=name,first_name,last_name,email,link,gender,locale,picture');
    $fbUserProfile = $profileRequest->getGraphNode()->asArray();
  } catch(FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    session_destroy();
    // Redirect user back to app login page
    header("Location: ./");
    exit;
  } catch(FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }
  
  $fbUserData = array(
    'oauth_provider'=> 'facebook',
    'oauth_uid'   => $fbUserProfile['id'],
    'first_name'  => $fbUserProfile['first_name'],
        
  );
  
  
 

    $_SESSION['uid'] = $fbUserProfile['id'];
    $_SESSION['name'] = $fbUserProfile['first_name'];
    header("location: album.php");
}else{
  // Get login url
  $loginURL = $helper->getLoginUrl($redirectURL, $fbPermissions);
  
  // Render facebook login button
  $output = '<a href="'.htmlspecialchars($loginURL).'"><img src="images/fblogin-btn.png"></a>';
}
?>
<html>
<head>
<title>Login</title>
</head>
<body>
<div><?php echo $output; ?></div>
</body>
</html>