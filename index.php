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
function getUserID($userName){
	$url = 'https://api.instagram.com/v1/users/search?q='.$userName.'&client_id='.clientID;
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true);

	return $results['data'][0]['id'];
}
// function to print out images onto screen
function printImages($userID){
	$url = 'https://api.instagram.com/v1/users/'.$userID.'/media/recent?client_id='.clientID.'&count=5';
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true);
	// parse throughthe information one by one
	foreach ($results['data'] as $items){
		$image_url = $items['images']['low_resolution']['url']; // going to go through all of my results and give myself back the URL of those pictures because we want to save it in the PHP Server
		echo '<img src=" '.$image_url.' "/><br/>';
		// calling a function to save that $image_url
		savePictures($image_url);
	}
}
// function to save image to server
function savePictures($image_url){
	return $image_url.'<br>'; 
	$filename = basename($image_url); // filename is what we are storing. basenameis the PHP method we are using to store $image_url
	echo $filename . "<br>";

	$destination = ImageDirectory . $filename; // making sure the image doesn't exist in the storage
	file_put_contents($destination, file_get_contents($image_url)); // goes and grabs an imagefile and stores it into our server
}


if (isset($_GET['code'])) {
	$code = $_GET['code'];
	$url = 'https://api.instagram.com/oauth/access_token';
	$access_token_settings = array('client_id' => clientID,
		'client_secret' => clientSecret,
		'grant_type' => 'authorization_code',
		'redirect_uri' => redirectURI,
		'code' => $code
		);
// cURL is what we use in PHP, it's a library calls to other API's
$curl = curl_init($url); // setting a cURL session and we put in $url because that's where we are getting the data from.
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $access_token_settings); // setting the POSTFIELDS to the array setup that we created.
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // setting it equal to 1 because we are getting strings back.
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // but in live work-production we want to set this to true.


$result = curl_exec($curl);
curl_close($curl);

$results = json_decode($result, true);

$userName = $results['user']['username'];

$userID = getUserID($userName);

printImages($userID);
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