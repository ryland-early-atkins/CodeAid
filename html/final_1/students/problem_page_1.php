<?php
//resume session and get variables
session_start();

$student_id=$_SESSION['userid'];
$_SESSION['return_path']='student_welcome_1.php';

//include several of the main helper function files
include '../function_files.php';

//verifies this is a valid session
testStudent($_SESSION['id'],$_SESSION['userid']);

$objectiveid=$_POST['quiz_objectives'][0];


function queryTheDB($query) {
	
	//Create a new mysqli object
	$mysqli = new mysqli("localhost","teambasic","visualbasic","CodeAid");
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


//Takes a tuple of the form ($question,$answers) and displays in a nice way
function displayQuestion($theQuestion) {
	
	echo "<b><em>$theQuestion[0]</em></b></br></br>";

	//Break up the answers
	$choices = explode(",,",$theQuestion[1]);

	//Make a radio button for each choice
	foreach($choices as $value) {
		$letter = substr(trim($value),0,1);
		echo "<input type='radio' name='choice' value='$letter' required>$value</br>";
	}
	echo "</br>";
}


function getAttemptNumber($questionId,$studentId) {


	//echo gettype($questionId).$questionId;
	//echo gettype($studentId).$studentId;
	//Create query
	$sql = "SELECT attempt FROM student_attempts WHERE question_id=$questionId AND student_id=$studentId;";
	
	//Create a new mysqli object
	$mysqli = new mysqli("localhost","teambasic","visualbasic","CodeAid");
	
	
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

function getMaxAttempts($questionId) {
	

	//echo gettype($questionId).$questionId;
	//echo gettype($studentId).$studentId;
	//Create query
	$sql = "SELECT max_attempts FROM questions WHERE question_id=$questionId;";
	
	//Create a new mysqli object
	$mysqli = new mysqli("localhost","teambasic","visualbasic","CodeAid");
	
	
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
		$maxNum = (int)$row['max_attempts'];
	}
	//Close the connection
	$mysqli->close();
	return $maxNum;


}

function get_questions(array $objective_ids){	
	
	//$mysqli = new mysqli("localhost","teambasic","visualbasic","CodeAid");
	
	//Check connection
	//if ($mysqli->connect_errno) {
        //	echo "Failed to connect to MySQL: ".$mysqli->connect_errno;  }
        //else{
        	//echo "Connection Established!";
	//}
	
	//Query for questions for each objective
	$questionsAndAnswers = array();
	$questions = array();

	
	foreach($objective_ids as $objective_id){
		//$sql = "SELECT question,question_id FROM questions NATURAL JOIN objectives WHERE objective_id=$objective_id;";
		$questions[$question[1]] = $question[0];
		//Check for failure
		//if (!$result = $mysqli->query($sql)) {
		//	//Oh no! The query failed. 
                //	echo "<h2>Sorry, the website is experiencing problems.</h2>";
        	//}   
		//Parse and store
		//while ($row = $result->fetch_assoc()){
		//	$questions[$row['question_id']] = $row['question'];
		//}	
	}
echo 'WOW4</br></br>';
	//Break into questions and sets of answers
	foreach($questions as $key => $value){
		
		echo $key.'</br></br></br>';
		echo $value.'</br></br></br>';
		$questionsAndAnswers[] = explode(":",$value);
	}
	 
echo 'WOW5</br></br>';

	//$mysqli->close();
	return $questionsAndAnswers;

}


//initialization information
	title("Problem Page");
	$sheets=array("../CSS_sheets/instructor_home.css");
	styles($sheets);

	//this file is for testing student success in an object
	//this was done so that it will be easy to change how one wants to verify student success
	include "../students/test_student_progress.php";

$_SESSION['objectives'] = $_POST['objectives'];
	//create logout button
	$_SESSION['objectiveid'] = $objectiveid;
	
	echo '<form style="float:right;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>';
	echo '<form style="float:left;" action="student_welcome_1.php"><input type="submit" value="End Quiz"></form>';
	echo '<form style="float:left;" action="student_objective_material.php" method="post"><input type="hidden" name="objective_id" value=$objectiveid><input type="submit" value="Help"></form>';
	//check if admin
    if($student_id<=10 && $student_id>=0){
		echo '<h2>Admin Options:</h2>';
		echo '<form action="../admins/manage_accounts.php"><input type="submit" value="manage accounts"></form>';
	}
    
    
    //main page header
	echo '<h1>Quiz Time!</h1></br>';
    

//Show 1 question per page until finished

//Get the objectives
$objectives = $_POST['quiz_objectives'];

$questionsAndAnswers = array();
$question_ID = array();
$questions = array();

//for each objective, find the relative questions
$ind = 0;
foreach($objectives as $objective_id) {
	//Query for all of the questions
	$question_ids = get("question,question_id","questions NATURAL JOIN objectives","objective_id=$objective_id AND question_id NOT IN (SELECT questions.question_id FROM questions NATURAL JOIN student_attempts WHERE student_id=$student_id)","");
	foreach($question_ids as $question_id) {
		//$attNum = getAttemptNumber($question_id[1],$student_id);
		//$maxAtt = getMaxAttempts($question_id);
		//if($attNum < $maxAtt) {
			$questions[] = $question_id;
		//}
	}
	//for each question retireved, explode the question and answers into parts
	foreach($questions as $key => $value){
		$questionsAndAnswers[$ind] = array(explode(":",$value[0]),$value[1]);
		//$questionsAndAnswers[$ind] = ($questionsAndAnswers[$ind],$value[1]);
		//$question_ID[$ind] = $value[1];
		//$qAll[$ind] = ($questionsAndAnswers[$ind],$question_ID[$ind]);
		$ind++;
	}
}
echo "<center>";
echo "<form method='POST' action='problem_page_1.php'>";
if(isset($_POST['start_val'])) {
	echo "Start of quiz...</br>";
	$questionIndex = $_POST['start_val'];	
	echo "<h3><em>Total questions: ".count($questionsAndAnswers)."</h3></em></br>";
	shuffle($questionsAndAnswers);
	$_SESSION['qanda'] = $questionsAndAnswers;
	echo "<input type='hidden' name='qind' value=$questionIndex>";
	echo "<input type='submit' name='start_the_damn_quiz' value='Start Quiz'>";
} else {

	$questionIndex = $_POST['qind'];
	$questionsAndAnswers = $_SESSION['qanda'];
	//echo "Question index: ".$questionIndex."</br></br>";
	
	//submit the answer from the last question
	if(isset($_POST['choice'])) {

		$resp = $_POST['choice'];
		$qid = 	$questionsAndAnswers[$questionIndex-1][1];
		$attNum = getAttemptNumber($qid,$student_id)+1;
		$date = date('Y-m-d H:i:s');
		$err = recordResponse($attNum,$qid,$student_id,$resp,$date); 
		if($err) {
			echo "Error: ".$err."</br>";
		}	
		
		//echo "attNum: ".$attNum."</br>";
		//echo "Choice: ";
		//echo $_POST['choice'];
		//echo "</br></br></br>";
	
	
	}
	
	//if you're out of questions: end the quiz
	if($questionIndex == count($questionsAndAnswers)) {
		echo "<input type='submit' name='end_quiz' value='End Quiz' formaction='student_welcome_1.php'></br>";
	} else {

	//Show the questions, like damn why did it take this long
	//echo $questionsAndAnswers
	displayQuestion($questionsAndAnswers[$questionIndex][0]);
	
	echo "</br></br>";
	$questionIndex += 1;
	echo "<input type='hidden' name='qind' value=$questionIndex>";
	echo "<input type='submit' name='next_question' value='Next Question'>";
	}
}
echo "</center>";
?>
























