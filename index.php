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

	// set the session for userId from the get url variable
	$_SESSION['userId'] = $_GET["userId"];

	// set the session for the sessiontoken for the logged in user
	$_SESSION['sessionToken'] = $_GET["sessionToken"];
		
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

<div class="main">
    
    <br/>
    <br/>
    <br/>
    
    <div id="wrappertop">JigSaw Puzzle Game</div>
    
    <!-- welcome  section -->
    <div id="welcome">
        <div><img src="images/welcome.png" /></div> 
        <br/>
        <div id="username"><? echo $_SESSION['userName'] ?></div>
    </div>
    <div style="clear:both;"></div>
    
    <br/>
    
    <!-- choose word set section -->
    <div id="content">
        <strong>Select your Kahrd set:</strong>
        <br/>
        <ul>
        <?
            // build API Url for getting the Kahrd Set information
            $xml_feed_url = "http://api.v-i-p-site.com/user/" . $_SESSION['userId'] . "/kahrdsets" . "?sessionToken=" . $_SESSION['sessionToken'];
            // send out HTTP Request using CURL
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $xml_feed_url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $xml = curl_exec($ch);
            curl_close($ch);	
    	    $kahrds = array();
            // create an array of kahrd elements from the XML feed.
            $kahrds_sets = element_set('kahrdset', $xml);
            // loop over each of the kahrd sets
			
            foreach((array)$kahrds_sets as $kahrd_set) {
                // get this kahrdset's id
                $kahrdsetid = value_in('kahrdsetid', $kahrd_set);
                // get this kahrdset's name
                $name = value_in('name', $kahrd_set);
				/*
                $remove='';
				$count=0;
				$correct_position=0;
				*/
                // write to the screen the kahrdset information and link
				/*
                echo "<li><a href='game.php?remove=$remove&count=$count&correct_position=$correct_position&kahrdsetid=" . $kahrdsetid . "&name=" . $name . "$remove='' '>" . $name . "</a></li><br/>"; 
				*/
			   echo "<li id='listype'><a href='game.php?kahrdsetid=" . $kahrdsetid . "&name=" . $name . "'>" .  $name . "</a></li><br/>";
            }
        ?>
        </ul>
    </div>
    
    <br />
    <br />
    
    <!-- Credits -->
    <div id="footer">Created by: Xiaofeng Zhu and Matt Schehl</div>
    
<!-- close main div -->    
</div>
    
</body>
</html>