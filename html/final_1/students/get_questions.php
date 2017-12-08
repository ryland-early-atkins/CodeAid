<?php

function get_questions(array $objective_ids){	
	
	$mysqli = new mysqli("localhost","teambasic","visualbasic","CodeAid");
	
	//Check connection
	if ($mysqli->connect_errno) {
        	echo "Failed to connect to MySQL: ".$mysqli->connect_errno;  }
        else{
#        	echo "Connection Established!";
	}
	
	//Query for questions for each objective
	$questionsAndAnswers = array();
	$questions = array();
	foreach($objective_ids as &$objective_id){
		$sql = "SELECT question,question_id FROM questions NATURAL JOIN objectives WHERE objective_id=$objective_id;";
		//Check for failure
		if (!$result = $mysqli->query($sql)) {
			//Oh no! The query failed. 
                	echo "<h2>Sorry, the website is experiencing problems.</h2>";
        	}   
		//Parse and store
		while ($row = $result->fetch_assoc()){
			$questions[$row['question_id']] = $row['question'];
		}	
	}
	//Break into questions and sets of answers
	foreach($questions as $key => $value){
		$questionsAndAnswers[] = explode(":",$value);
	}


	$mysqli->close();
	return $questionsAndAnswers;
}
?>
