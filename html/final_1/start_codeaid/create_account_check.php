<?php

	//get values from form
	$tuple=array(trim($_POST['id']),$_POST['first_name'],$_POST['last_name'],trim($_POST['email']),trim($_POST['password']),0);

	//verify all entries have a value	
	for($i=0;$i<count($tuple)-1;$i++){
		if(empty($tuple[$i])){
			include 'create_account.php';
			errorMessage("all blanks are required fields.");
			exit;
		}
	}

	//file to aid in sql queries
	include '../sql_work/sql_conn.php';
	include '../sql_work/sql_queries.php';

	//verifies that user email doesnt already exist
	$semails=getColumn("email","students");
	$iemails=getColumn("email","instructors");
	foreach($semails as $semail){
		if($semail===$tuple[3]){
			include 'create_account.php';
			errorMessage("invalid email");
			exit;
		}
	}
	foreach($iemails as $iemail){
		if($iemail===$tuple[3]){
			include 'create_account.php';
			errorMessage("invalid email");
			exit;
		}
	}

	//verifies that user id doesnt already exist
	$sids=getColumn("Student_ID","students");
	$iids=getColumn("Instructor_ID","instructors");
	foreach($sids as $sid){
		if($sid===$tuple[0]){
			include 'create_account.php';
			errorMessage("invalid id");
			exit;
		}
	}
	foreach($iids as $iid){
		if($iid===$tuple[0]){
			include 'create_account.php';
			errorMessage("invalid id");
			exit;
		}
	}

	//check to make sure passwords are identical
	if($tuple[4]!==trim($_POST['pass_verif'])){
		include 'create_account.php';
		errorMessage("passwords dont match");
		exit;
	}

	//insert information into database
	if($_POST['user_type']!=='student') {
		$tuple[4]=password_hash($tuple[4], PASSWORD_DEFAULT);
		//attemts to insert account info into DB
		$err=createUser($tuple,"students");
		//makes sure no table level rules are violated
		if(!$err){
			echo '<h1 style="text-align: center;"> Thank you for choosing CodeAid.</h1>';
			echo '<h1 style="text-align: center;"> You will be notified once your account is approved.</h1>';
			echo '<p style="text-align: center;"><a href="login.php">login page</a></p>';
			exit;
		}else{
			//reloads create account page if there was issues
			include 'create_account.php';
			errorMessage("invalid entry");
			exit;
		}
	} else {
		$tuple[4]=password_hash($tuple[4], PASSWORD_DEFAULT);
		//attemts to insert account info into DB
		$err=createUser($tuple,"instructors");
		//makes sure no table level rules are violated
		if(!$err){
			echo '<h1 style="text-align: center;"> Thank you for choosing CodeAid.</h1>';
			echo '<h1 style="text-align: center;"> You will be notified once your account is approved.</h1>';
			echo '<p style="text-align: center;"><a href="login.php">login page</a></p>';
			exit;
			exit;
		}else{
			//reloads create account page if there was issues
			include 'create_account.php';
			errorMessage("invalid entry");
			exit;
		}
	}
?>
