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

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	
	<a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code">Login</a>
	
</body>
</html>
