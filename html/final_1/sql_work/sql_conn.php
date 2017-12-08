<!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is for connecting to mysql using php
-->

<?php
	//Connect to the database
	function connect() {
		$mysqli = new mysqli("localhost","teambasic","visualbasic","CodeAid");
		if ($mysqli->connect_errno) { // Check connection
		    echo "Failed to connect to MySQL: ".$mysqli->connect_errno;  
		}
		return $mysqli;
	}

	// Close connection to the database
	function disconnect($mysqli) {
	  $mysqli->close();
	}
	
	//this is to verify connection is occuring
	/*
	$mysqli=connect();
	disconnect($mysqli);
	*/
?>
