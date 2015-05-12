<?php
//Configuration for our PHP Server
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

//Make Constant using define.
define('clientID', 'd15f2eee69744a55b53457b348a2a9de');
define('clientSecret', 'be8613e9ab8a45b8a68a028b4b693cd2');
define('redirectURI', 'http://localhost/michaelapi/index.php');
define('ImageDirectory', 'pics/');

// function that is going to connect to Instagram
function connectToInstagram($url){
	$ch = curl_init();

	curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 2,
	));
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
// function to get userID cause userNAME doesn't allow us to get pictures!
function getUserID($userNAME){
	$url = 'https://api.instagram.com/cl/users/search?q='.$userName.'&client_id='.clientID;
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true);

	echo $results['data']['0']['id'];
}

if (isset($_GET['code'])) {
	$code = ($_GET['code']);
	$url = 'https://api.instagram.com/oauth/access_token';
	$access_token_settings = array('client_id' => clientID,
		'client_secret' => clientSecret,
		'grant_type' => 'authorization_code',
		'redirect_uri' => redirectURI,
		'code' => $code
		);
// cURL is what we use in PHP, it's a library calls to other API's
$cURL = curl_init($url); // setting a cURL session and we put in $url because that's where we are getting the data from.
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $access_token_settings); // setting the POSTFIELDS to the array setup that we created.
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // setting it equal to 1 because we are getting strings back.
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // but in live work-production we want to set this to true.

$result = curl_exec($curl);
curl_close();

$results = json_decode($result, true);
getUserID($results['user']['username']);
}
else{
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<!-- Creating a login for people to go and give approval for our web app to access their Instagram Account
	After getting approval we are now going to have the information so that we can play with it.
	 -->
	<a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code">Login</a>
	
</body>
</html>
<?php 
}
 ?>