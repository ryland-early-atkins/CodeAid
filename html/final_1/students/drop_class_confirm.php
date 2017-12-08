
<?php

//resume session and get variables
session_start();

$student_id=$_SESSION['userid'];

//include several of the main helper function files
include '../function_files.php';

//verifies this is a valid session
testStudent($_SESSION['id'],$_SESSION['userid']);

//initialization information
title("Drop Class");
$sheets=array("../CSS_sheets/instructor_home.css");
styles($sheets);

//this file is for testing student success in an object
//this was done so that it will be easy to change how one wants to verify student success
include "../students/test_student_progress.php";

//create logout button
echo '<form style="float:right;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>';

//check if admin
if($student_id<=10 && $student_id>=0){
	echo '<h2>Admin Options:</h2>';
	echo '<form action="../admins/manage_accounts.php"><input type="submit" value="manage accounts"></form>';
}
//check if confirmed
if(!isset($_POST['confirm'])) {

	echo '<h1>Are you sure you want to permanently drop this class?</h1>';
	$_SESSION['class_id'] = $_POST['student_class_id'];
	echo '<form action="drop_class_confirm.php" method="POST"><input type="submit" name="confirm" value="Take me out!"></form>';
}
//if confirmed, update the active status to 0
if(isset($_POST['confirm'])) {
	
	//echo "StudentID: ".$student_id."</br>";
	//echo "ClassID: ".$_SESSION['class_id']."</br></br></br>";
	$err=updateField("student_classes","active",0,"Student_ID=$student_id AND Class_ID=".$_SESSION['class_id']);

}
//Add back button 
echo '<form action="student_manage_class.php" method="POST"><input type="submit" name="back" value="Back to classes"></form>';

?>
