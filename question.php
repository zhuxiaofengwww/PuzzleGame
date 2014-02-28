<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>question</title>
   <script type="text/javascript">
   <!--
   function checkForm()
   {
	  var valid=false;
	  var message="Error: Please choose one option \n";
	  with(document.answerQuestion)
	  {	 
	  for(i=0;i <word.length;i++) 
            if(word[i].checked) 
           { 	
			  valid=true;
			  }
		  }

		  if(!valid)
		     alert(message);
		  return valid;
		  
	   }
	   </script>
</head>
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
//	$xml_feed_url = "http://api.v-i-p-site.com/user/7/kahrdsets/21/kahrds";
//	$xml_feed_url = $_GET[xml_feed_url];
	$kahrdsetid=$_GET["kahrdsetid"];
	//$xml_feed_url = "http://api.v-i-p-site.com/user/" . $_SESSION['userId'] . "/kahrdsets/" . $kahrdsetid . "/kahrds";
		$xml_feed_url = "http://api.v-i-p-site.com/user/" . $_SESSION['userId'] . "/kahrdsets/" . $kahrdsetid . "/kahrds" . "?sessionToken=" . $_SESSION['sessionToken'];
		
	// send out HTTP Request using CURL
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $xml_feed_url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$xml = curl_exec($ch);
	curl_close($ch);	

	// create an array of kahrd elements from the XML feed.
	$kahrds = array();
	$kahrds = element_set('kahrd', $xml);
	
	// loop over each of the kahrds and load them into an array
	foreach(@(array)$kahrds as $kahrd) {
	    $wordid = value_in('wordid', $kahrd);
	    $word = value_in('word', $kahrd);
	    $definition = value_in('definition', $kahrd);
	
		$kahrd_array[] = array(
		            'wordid' => $wordid,
		            'word' => $word,
		            'definition' => $definition
		    );	
		
	}
		$count=$_GET["count"];
		//$remove=$_GET[remove];
	    //$remove=array(0,0,0,0,0,0,0,0,0);
		//global $remove;
?>
<body>

	<?
	    $mark[] = array(
		            'mark' => "false"
		    );
		
		$remove=$_GET["remove"];
		global $remove;
		// make sure that there are kahrds to display
		if (sizeof(@$kahrd_array) > 8) {
			// keep count of the current kahrd

			// get the total size of the kahrd array
			$kahrds_size = sizeof($kahrd_array)-1; 
			
			// loop over each of the kahrds to write the questions to the screen
		    //foreach ($kahrd_array as $kahrd) {
				// write out the question for the word
				//$remove[$count]=2;
				$counter=$count+1;
				echo $counter . ". What does " . $kahrd_array[$count]['word'] . " mean?<br/>\n";
				
				// randomly select where the correct definition will be in the list of answers
				$correct_position = rand(1, 4);
				//echo"<form name='answerQuestion' action='test.php?' method='post' onSubmit='return checkForm();'>";

				// code to send back number of question variables
				$questions_answered=$_GET["questions_answered"];
				
				echo"<form name='answerQuestion' action='game.php?kahrdsetid=$kahrdsetid&remove=$remove&count=$count&correct_position=$correct_position&questions_answered=$questions_answered' method='post' onSubmit='return checkForm();'>";
				
				// loop over 4 times to create a multiple choice test
				for ($i = 1; $i <= 4; $i++) {
					if ( $i == $correct_position ) {
						// if it is the correct position write out the correct definition
/*						echo "<input type='radio' name='word' onclick=\"alert('Correct Answer!');\<?$remove=2;?>\" id='word". $kahrd_array[$count]['wordid'] . "-" . $i . "' /> ";
*/
						echo "<input type='radio' name='word' value='$i' id='word". $kahrd_array[$count]['wordid'] . "-" . $i . "' /> ";	
											
						echo "<label for='word". $kahrd_array[$count]['wordid'] . "-" . $i . "'>" . $kahrd_array[$count]['definition']  . "</label>";
						echo "<br/>\n";
					} else {
						// if it is not the correct position write out an incorrect definition
						$random_definition = $count - 1;
						// make sure wrong definition isn't the correct one

						while (($random_definition == $count - 1)||($random_definition ==$count)||(@$mark[$random_definition]['mark']=="true")) {
						    $random_definition = rand(0, $kahrds_size-1);

						}
						echo "<input type='radio' name='word'  value='$i' id='word". $kahrd_array[$count]['wordid'] . "-" . $i . "' /> ";
						echo "<label for='word". $kahrd_array[$count]['wordid'] . "-" . $i . "'>" . $kahrd_array[$random_definition]['definition']  . "</label>";
						echo "<br/>\n";
						$mark[$random_definition]['mark']="true";

					} 
				}
			    echo"<input type='submit' name='submit' value='Submit'/>";
				
				echo "</form>";
		        echo "<br/><br/>\n";
		   // }
		} else {
			echo "You should have at least 9 words in each kahrds set up for this set! ";
		}
	?>
</body>
</html>