<!-- Matt Schehl and Xiaofeng Zhu -->
<!-- Senion progject FGCU Dr. Zalewski -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
<title>Jigsaw Puzzle</title>

<link rel="stylesheet" href="css/main.css" type="text/css"  >

<script src="facebox/lib/jquery.js" type="text/javascript"></script>
<link href="facebox/src/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="facebox/src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
        loadingImage : 'facebox/src/loading.gif',
        closeImage   : 'facebox/src/closelabel.png'
      })
    })
		</script>
</head>

<script>

var allHintsTried=8;

function checkAllHintsTried () {
	// document.getElementById('flash_game').style.display = 'block';
	if (allHintsTried > 8) {
		document.getElementById('flash_game').style.display = 'block';
	}
	allHintsTried++;
}

</script>



<style>
.clue_tried {
	border:0px;
	width: 97px;
	height:93px;
	padding:0px auto; 
    margin:0 0 0 0
}
.flash_game {
	display:none;
}
</style>

<!-- PHP to query an api via a URL. An XML file is returned and parsed into word, wordid, definition -->
<?
	// if IE include trusted header for iFrame issues
	if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
		header('P3P: CP="NOI ADM DEV COM NAV OUR STP"');
		
	// include XML tools
	include 'xml_regex.php';

	// start session state
	session_start();	
	
	// added variables
	$test_var = "may first php variable";
	
	// build API Url for getting Kahrds for the passed in kahrdset
	$kahrdsetid=$_GET["kahrdsetid"];
	//$xml_feed_url = "http://api.v-i-p-site.com/user/" . $_SESSION['userId'] . "/kahrdsets/" . $_GET["kahrdsetid"] . "/kahrds";	
	//$xml_feed_url = "http://api.v-i-p-site.com/user/" . $_SESSION['userId'] . "/kahrdsets/" . $kahrdsetid . "/kahrds";
	//	$xml_feed_url = "http://api.v-i-p-site.com/user/" . $_SESSION['userId'] . "/kahrdsets/" . $_GET["kahrdsetid"] . "/kahrds" . "?sessionToken=" . $_SESSION['sessionToken'];
	$xml_feed_url = "http://api.v-i-p-site.com/user/" . $_SESSION['userId'] . "/kahrdsets/" . $kahrdsetid . "/kahrds" . "?sessionToken=" . $_SESSION['sessionToken'];
	// send out HTTP Request using CURL
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $xml_feed_url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$xml = curl_exec($ch);
	curl_close($ch);	

	// create an array of kahrd elements from the XML feed.
	$kahrds = element_set('kahrd', $xml);
	
	// loop over each of the kahrds and load them into an array
	foreach(@(array)$kahrds as $kahrd) {
	    $wordid = value_in('wordid', $kahrd);
	    $word = value_in('word', $kahrd);
	    $definition = value_in('definition', $kahrd);
	
		@$kahrd_array[] = array(
		            'wordid' => $wordid,
		            'word' => $word,
		            'definition' => $definition
		    );
	}
?>
<? $username=$_SESSION['userName']?>
<!-- Javascript Area -->
<script>
// Countdown timer
var Timer;
var TotalSeconds;

function CreateTimer(TimerID, Time) {
    Timer = document.getElementById(TimerID);
    TotalSeconds = Time;
    
    UpdateTimer()
    window.setTimeout("Tick()", 1000);
}

function Tick() {
    if (TotalSeconds <= 0) {
        alert("Time's up!")
		window.location.href="fail.php";     		 
        return;
    }

    TotalSeconds -= 1;
    UpdateTimer()
    window.setTimeout("Tick()", 1000);
}


function UpdateTimer() {
	TotalMinutes = (TotalSeconds - (TotalSeconds % 60)) / 60;
	RemainingSeconds = TotalSeconds - (TotalMinutes * 60);
	str = TotalMinutes + ":";
	if (RemainingSeconds < 10) {
		str = str + "0" + RemainingSeconds;
	}else{
		str = str + RemainingSeconds;
	}
    Timer.innerHTML = str;
}
</script>

<!-- css styling -->
<style>

</style>
<script>
	function startGame () {
		document.getElementById('flash_game').style.display = 'block';
		document.getElementById('disabled_game').style.display = 'none';
		document.getElementById('timerBox').style.display = 'block';
		CreateTimer("timer", 600);
	}
</script>
<body>

<div class="main">
    <!-- Left time -->
    <div id="uppart">  
     <div style="float:left; font-size:14px;">Time Left: </div>      
       <div id="timerBox" style="display:none;">    

             <div id='timer' style="float:left;font-size:14px;">timer here</div> 
       </div>   
    <!-- welcome statement --> 
    <div id="gameusername">
        User: <? echo $_SESSION['userName'] ?>  // <? echo $xml_feed_url?>

    </div>
    
</div> 
    <div style="clear:both;"></div>
    <br />

    <!-- Puzzle piece area -->
    <div id="puzzle">
    	<div id="disabled_game" ><img src="images/disabled_game.png"/></div>
    	<div id="flash_game" style="display:none;">
        	<EMBED height=374 pluginspage=http://www.macromedia.com/go/getflashplayer src="images/puzzle.swf" type=application/x-shockwave-flash width=500 wmode="transparent" quality="high"></EMBED>
    	</div>
    </div>
    <!-- game board area -->
    <div id="gameboard">
        Game board area
        <table border="0" cellspacing="0" cellpadding="0" style="position:relative;top:30px;left:5px;border-collapse:collapse;" id="tabledes">
			<?
            @$remove=$_GET["remove"];

            global $remove;
            @$word=$_POST["word"];
			// count how many questions answered
			if (isset($_GET['questions_answered'])) {
				$questions_answered = $_GET['questions_answered'];
				$questions_answered++;
			}else{
				$questions_answered = 1;
			}	
            @$correct_position=$_GET["correct_position"];
            for ($j = 0; $j <= 2; $j++) {     
                echo"<tr>";	  
                for ($i = 1; $i <= 3; $i++) {
                    $pn=$j*3+$i;
                    $count=$j*3+$i-1;
                    @$number=$_GET["count"];

                    //$remove[$number]=$_GET[remove];
                    if( !isset($remove[$number]) ) $remove[$number]=0;			
                    if(@$word==$correct_position){
        
                        $remove[$number]=2;
                        }
                    else{
                        $remove[$number]=1;
                        }
                    if( !isset($remove[$count]) ) $remove[$count]=0;				
                    if($remove[$count]==0){
                        echo"<td class='cue' id='cue_tried'><a rel='facebox' href='question.php?kahrdsetid=$kahrdsetid&count=$count&remove=$remove&questions_answered=$questions_answered'><img src='images/".$pn.".jpg'  align='left' /></a></td>";
                    }
                    if($remove[$count]==1){
                        echo"<td class='cue' id='cue_tried'><img src='images/".$pn.".jpg'  align='left' /></td>";
                    }
                    if($remove[$count]==2){
                        echo"<td class='cue'></td>";
                    }
                } 
                echo"</tr>";
            }
            ?> 
        </table>
 </br>
 </br>         
 
<!-- Demonstrate a question -->


<!--<div style="postition:absolute; bottom:2px;float:left; padding:10px 0 0 10px;"><a href="http://www.v-i-p-site.com/games.php" target="_parent">Home</a></div>-->
<div id="home"><a href="http://www.v-i-p-site.com/games.php" target="_parent">Home</a></div>
<!--<div style="postition:absolute; bottom:2px;float:right; padding:10px 10px 0 0px;"><a href="http://www.v-i-p-site.com/game.php?gameId=16" target="_parent">Exit</a></div>-->
<div id="help"><a href="help.php" target="_parent">help</a></div>
<div id="exit"><a href="http://www.v-i-p-site.com/game.php?gameId=16" target="_parent">Exit</a></div>
    
</div>

	<div style="clear:both;"></div>
    


<!-- close main div -->
</div>
<? 
	if ($questions_answered >9) {

		echo "<script>window.onload = startGame;</script>";
	}
	
?>
</body>
</html>