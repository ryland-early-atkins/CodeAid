<!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is for aiding in sql queries
-->
<?php
	//file to create object to communicate PHP with DB
	//include 'sql_conn.php';

	//gets an item associated with particular email
	//bind email parameter since the email user specified
	function getItem($email,$table,$fieldname){
		$mysqli=connect();

		//echo statement in case debugging is needed
		//echo "SELECT ".$fieldname." FROM ".$table." WHERE email = ?";

		//need prepare statement because user specifies email not php
		if(!$query=$mysqli->prepare("SELECT ".$fieldname." FROM ".$table." WHERE email = ?")){
			echo "Prepare statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
			disconnect($mysqli);
			exit;
		}

		$query->bind_param('s',$email);
		$query->execute();

		//if stmt for debugging purposes
		if(!$result=$query->get_result()){
			echo " invalid result";
			exit;
		}
		//gets all items from query into one dimensional array
		if(!$values=$result->fetch_all()){
			disconnect($mysqli);
			return 0;
			exit;
		}
		//converts char arrays to strings
		for($i=0;$i<count($values);$i++){
			$values[$i]=implode($values[$i]);
			$values[$i]=trim($values[$i]);
		}
		//for debugging
		if(count($values)>1){
			echo "this shouldnt happen. data integrity fucked.";
		}
		disconnect($mysqli);
		return $values[0];
	}

	function insertMaterialBind($name,$info,$objid){
		//get object to connect to server
		$mysqli=connect();

		//create query for database. if statment is for debugging purposes. this should never otherwise.
		$sql = "INSERT INTO supplementary_material (name,info,Objective_ID) VALUES (?,?,?)";

		//need prepare statement because user specifies value not php
		if(!$query=$mysqli->prepare($sql)){
			echo "Prepare statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
			disconnect($mysqli);
			exit;
		}

		$query->bind_param("ssi",$name,$info,$objid);
		$query->execute();
		
		//get error code in case all is not well
		$err=$mysqli->errno;
		
		//disconnect connection object
		disconnect($mysqli);

		return $err;
	}

	function recordResponse($attemptNum,$questionID,$studentID,$studentResponse,$date) {
		//get object to connect to server
		$mysqli=connect();

		//create query for database. if statment is for debugging purposes. this should never otherwise.
		$sql = "INSERT INTO student_attempts (attempt,Question_ID,Student_ID,answer,time_stamp) VALUES ($attemptNum,$questionID,$studentID,'$studentResponse','$date');";
		//echo $sql."</br>";
		if($mysqli->query($sql) === TRUE) {
			//echo "Response recorded!</br>";
			return;
		}
		//get error code in case all is not well
		$err=$mysqli->errno;
		
		//disconnect connection object
		disconnect($mysqli);

		return $err;
	}
	
	//no prepare statement needed bc php enters ID
	function insertStudentClass($classID,$studentID){
		//get object to connect to server
		$mysqli=connect();

		//create query for database. if statment is for debugging purposes. this should never otherwise.
		$sql = "INSERT INTO student_classes (Class_ID,Student_ID) VALUES ($classID,$studentID);";
		if($mysqli->query($sql) === TRUE) {
			echo "You will be added to the class once approved by the professor.";
			exit;
		}

		//get error code in case all is not well
		$err=$mysqli->errno;
		
		//disconnect connection object
		disconnect($mysqli);

		return $err;
	}

	//common generic query function
	function get($field,$table,$boolean,$extra){
		$mysqli=connect();

		//echo statement to aid in debugging
		//echo "SELECT ".$field." FROM ".$table." WHERE ".$boolean.$extra;

		//binding params is unnecessary because all params are enetered by php
		if(!$query=$mysqli->prepare("SELECT ".$field." FROM ".$table." WHERE ".$boolean.$extra)){
			echo "Prepare statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
			disconnect($mysqli);
			exit;
		}
		$query->execute();
		
		if(!$result=$query->get_result()){
			echo " invalid result";
			disconnect($mysqli);
			exit;
		}
		//put resut into 2D array
		$values= array();
		while($value = $result->fetch_array(MYSQLI_NUM)){
			$values[]=$value;
		}
		for($i=0;$i<count($values);$i++){
			for($a=0;$a<count(values[0]);$a++){
				if(is_array($values[$i][$a])){
					$values[$i][$a]=implode($values[$i]);
				}
				$values[$i][$a]=trim($values[$i][$a]);
			}
		}

		disconnect($mysqli);
		return $values;
	}
	
	//get a column from table
	function getColumn($field,$table){
		$values=get($field,$table,"1=1","");
		return $values;
	}

	//takes a tuple of all the information needed for a given user and inserts it into database
	function createUser($tuple,$table){
		//get object to connect to server
		$mysqli=connect();

		//create query for database. if statment is for debugging purposes. this should never otherwise.
		if(!$query= $mysqli->prepare("INSERT INTO ".$table." values (?,?,?,?,?,?)")){
			echo "Prepare statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
			disconnect($mysqli);
			exit;
		}
		
		//for debugging purposes to make usre binding goes properly
		if(!$query->bind_param("issssi",$tuple[0],$tuple[1],$tuple[2],$tuple[3],$tuple[4],$tuple[5])){
			echo "failed to bind params";
		}
		
		//run query assuming all works well
		$query->execute();
		//$result=$query->get_result();
		
		//get error code in case all is not well
		$err=$mysqli->errno;
		
		//disconnect connection object
		disconnect($mysqli);

		return $err;
	}

	//this function updates a field based on specified condition using the prepare statement properly
	function updateFieldBind($table,$column,$value,$bool,$type){
		//get object to connect to server
		$mysqli=connect();
		//echo "Update ".$table." SET ".$column."=".$value." WHERE ".$bool;

		//create query for database. if statment is for debugging purposes. this should never otherwise.
		//only binding for one param is necessary
		if(!$query= $mysqli->prepare("Update ".$table." SET ".$column."=? WHERE ".$bool)){
			echo "Prepare statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
			disconnect($mysqli);
			exit;
		}

		//bind field entry
		$query->bind_param($type,$value);
		//run query assuming all works well
		$query->execute();
		
		//get error code in case all is not well
		$err=$mysqli->errno;
		
		//disconnect connection object
		disconnect($mysqli);

		return $err;
	}
	//this function updates a field based on specified condition
	function updateField($table,$column,$value,$bool){
		//get object to connect to server
		$mysqli=connect();
		//echo "Update ".$table." SET ".$column."=".$value." WHERE ".$bool;

		//create query for database. if statment is for debugging purposes. this should never otherwise.
		//all query params are php specified so binding is not needed
		if(!$query= $mysqli->prepare("Update ".$table." SET ".$column."=".$value." WHERE ".$bool)){
			echo "Prepare statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
			disconnect($mysqli);
			exit;
		}
		//run query assuming all works well
		$query->execute();
		//$result=$query->get_result();
		
		//get error code in case all is not well
		$err=$mysqli->errno;
		
		//disconnect connection object
		disconnect($mysqli);

		return $err;
	}

?>
