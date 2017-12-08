<?php
	//include several of the main helper function files
	include '../function_files.php';

	//information obtained from html form
	$username=trim($_POST['username']);
	$password=trim($_POST['password']);
	
	//get passwords from DB. will return 0 if no such email exists
	$shash=getItem($username,"students","password");
	$ihash=getItem($username,"instructors","password");

	//tests if password for the username existed and if it matches specified password
	if(password_verify($password,$shash) && getItem($username,"students","active")){
		session_start();
		$_SESSION=array();
		$_SESSION['userid']=getItem($username,"students","Student_ID");
		$_SESSION['id']=password_hash($_SESSION['userid'],PASSWORD_DEFAULT);
		reloc("../students/student_welcome_1.php");
		exit;
	}else if(password_verify($password,$ihash) && getItem($username,"instructors","active")){
		session_start();
		$_SESSION=array();
		$_SESSION['userid']=getItem($username,"instructors","Instructor_ID");
		$_SESSION['id']=password_hash($_SESSION['userid'],PASSWORD_DEFAULT);
		reloc("../instructors/instructor_home.php");
		exit;
	}
	
	//lets user know they entered something wrong
	reloc("login_error.php");
?>
