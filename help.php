<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<?
	// if IE include trusted header for iFrame issues
	if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
		header('P3P: CP="NOI ADM DEV COM NAV OUR STP"');
		
	// include XML tools
	include 'xml_regex.php';
	
	// start session state
	session_start();

		
	// build API Url for getting User Information
	$xml_feed_url = "http://api.v-i-p-site.com/user/" . $_SESSION['userId']. "?sessionToken=" . $_SESSION['sessionToken'];
	// send out HTTP Request using CURL
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $xml_feed_url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$xml = curl_exec($ch);
	curl_close($ch);	
	
	// set the session for userName to keep the name for the user from the api request
	$_SESSION['userName'] = value_in('name', $xml);
	
?>

<body>
<center>
Hello, <? echo $_SESSION['userName'] ?>

<div id="helppage">


        	<EMBED height=394 pluginspage=http://www.macromedia.com/go/getflashplayer src="images/help.swf" type=application/x-shockwave-flash width=591 wmode="transparent" quality="high"></EMBED>

    
    <!-- Credits -->
    <div id="footer">Created by: Xiaofeng Zhu and Matt Schehl&nbsp;<a href="http://www.v-i-p-site.com/game.php?gameId=16" target="_parent">Back</a></div>
  
</div>
</center> 
</body>
</html>