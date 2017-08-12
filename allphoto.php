<?php
// Include FB config file && User class
require_once 'fbConfig.php';
require_once 'User.php';

if(isset($accessToken)){
	if(isset($_SESSION['facebook_access_token'])){
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
		
	}else{
		$_SESSION['facebook_access_token'] = (string) $accessToken;
		
	  	
		$oAuth2Client = $fb->getOAuth2Client();
		
		
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
		
		// Set default access token to be used in script
	}
	if(isset($_GET['code'])){
		header('Location: ./');
	}
	
	try {
		$photos_request = $fb->get('/me/photos?limit=100&type=uploaded');
		$photos = $photos_request->getGraphEdge();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	$all_photos = array();
	if ($fb->next($photos)) {
		$photos_array = $photos->asArray();
		$all_photos = array_merge($photos_array, $all_photos);
		while ($photos = $fb->next($photos)) {
			$photos_array = $photos->asArray();
			$all_photos = array_merge($photos_array, $all_photos);
		}
	} else {
		$photos_array = $photos->asArray();
		$all_photos = array_merge($photos_array, $all_photos);
	}
	$files = array();
	foreach ($all_photos as $key) {
		$photo_request = $fb->get('/'.$key['id'].'?fields=images');
		$photo = $photo_request->getGraphNode()->asArray();
		array_push($files,$photo['images'][2]['source']);
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
header('Content-disposition: attachment; filename="Allphoto.zip"');
header('Content-type: application/zip');
readfile($tmp_file);
unlink($tmp_file);
    

	
	
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
</body>
</html>