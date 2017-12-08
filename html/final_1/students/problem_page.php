
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='UTF-8'>
	<title>CodeAid</title>
</head>
<body>
	<center>
	<img src='CodeAid_Logo.png' alt='CodeAid'>
	</br>
	</br>

<!--These are all of the helper functions-->
<?php

function queryTheDB($query) {
	
	//Create a new mysqli object
	$mysqli = new mysqli("localhost","root","root","CodeAid");
	//Check the connection
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: ".$mysqli->connect_errno;
	} else { /*echo "Connection established!";*/ }
	//Check for failure
	if (!$result = $mysqli->query($query)) {
		echo "Sorry, the website is experiencing problems.";
	}
	//Store everything
	$returnValue = array();
	while ($row = $result->fetch_assoc()) {
		$returnValue[] = $row;
	}
	//Close connection
	$mysqli->close();
	//Send it back
	return $returnValue;
}

function recordResponse($attemptNum,$key,$student_id,$studentResponse) {

	//Create query
	$sql = "INSERT INTO student_attempts (attempt,question_id,student_id,answer) VALUES ($attemptNum,$key,$student_id,'$studentResponse')";
	
	//Create a new mysqli object
	$mysqli = new mysqli("localhost","root","root","CodeAid");
	
	//Check the connection
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: ".$mysqli->connect_errno;
	} else { /*echo "Connection established!";*/ }
	
	//Check for failure
	if (!($mysqli->query($sql) === TRUE)) {
		echo "</br>Sorry, the website is experiencing problems. (respErr) </br>".$sql."</br>".$mysqli->error;
	} else { /* echo "Response recorded successfully!"; */ }
	
	//Close connection
	$mysqli->close();
	
	return;
}

function displayQuestions($theQuestions) {
	if(count($theQuestions) > 0) {
		foreach($theQuestions as $key => $value) {
			echo "<b><em>$value[0]</em></b></br></br>";

			//Break up the answers
			$choices = explode(",,",$value[1]);

			//Make a radio button for each choice
			foreach($choices as $value) {
				$letter = substr(trim($value),0,1);
				echo "<input type='radio' name='$key' value='$letter'>$value</br>";
			}
			echo "</br>";
		}
	} else {
		echo "No questions to display.";
	}
}

function getAttemptNumber($questionId,$studentId) {


	//echo gettype($questionId).$questionId;
	//echo gettype($studentId).$studentId;
	//Create query
	$sql = "SELECT attempt FROM student_attempts WHERE question_id=$questionId AND student_id=$studentId;";
	
	//Create a new mysqli object
	$mysqli = new mysqli("localhost","root","root","CodeAid");
	
	//Check the connection
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: ".$mysqli->connect_errno;
	} else { /*echo "Connection established!";*/ }
	
	//Check for failure
	if (!$result = $mysqli->query($sql)) {
		echo "Sorry, the website is experiencing problems. (attNumErr) </br>";
	}
	
	//Get maxNum
	$maxNum = 0;
	while ($row = $result->fetch_assoc()) {
		if ($row['attempt'] > $maxNum) {
			$maxNum = (int)$row['attempt'];
		}
	}
	//Close the connection
	$mysqli->close();
	return $maxNum;
}
?>	

<!--This is the main form-->
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php 
	include 'get_objectives.php';
	include 'get_questions.php';
	session_start();
	
	#$student_id = $_SESSION['student_id'];
	
	//FOR TESTING
	$student_id = 326481;

	//Get the active objectives
	$objectives = get_objectives($student_id);
	//Get associated questions
	$questions = get_questions($objectives);
	
//Display the questions
if(!isset($_POST['submitQuiz'])) {	
	displayQuestions($questions);
	echo '<input type="submit" name="submitQuiz" value="Submit Quiz"/>';
}	

//Submit the responses to the server
if(isset($_POST['submitQuiz'])) {

	//Make sure array is reset
	//unset($questions);
	//For every question find the attempt number an submit response to server
	foreach($questions as $key => $value) {
		if(isset($_POST[$key])) {
			$attemptNum = getAttemptNumber($key+1,$student_id) + 1;
			$studentResponse = $_POST[$key];
			$questionNum = $key+1;
			recordResponse($attemptNum,$questionNum,$student_id,$studentResponse);
		}
	}

echo "</br></br><b>Thank you!</br>";
}
?>
</form>

</br>
</br>

<form method="POST" action="student_welcome.php">
<input type="submit" name="goHome" value="Back to Home"/>
</form>
		</center>
	</body>
</html>

