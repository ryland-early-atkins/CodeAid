<?php

function get_objectives($student_id){	
	
	$mysqli = new mysqli("localhost","root","root","CodeAid");
	
	//Check connection
	if ($mysqli->connect_errno) {
        	echo "Failed to connect to MySQL: ".$mysqli->connect_errno;  }
        else{
#        	echo "Connection Established!";
	}
	
	//Query for objectives
	$sql = "SELECT objectives.active,objectives.objective_id FROM objectives NATURAL LEFT OUTER JOIN courses NATURAL JOIN classes NATURAL JOIN student_classes WHERE student_id=$student_id;";
	if (!$result = $mysqli->query($sql)) {
		//Oh no! The query failed. 
                echo "<h2>Sorry, the website is experiencing problems.</h2>";
        }   
	
	//Parse and store
	$objectives = array();
	while ($row = $result->fetch_assoc()){
		if($row['active'] == 1){
			$objectives[$row['objective_id']] = $row['active'];
		}
	}
	$mysqli->close();
	return $objectives;
}
?>
