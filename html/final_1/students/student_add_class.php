

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
echo '<form style="float:left;" action="student_manage_class.php" method="POST"><input type="submit" name="back" value="Back"></form>';

//check if admin
if($student_id<=10 && $student_id>=0){
	echo '<h2>Admin Options:</h2>';
	echo '<form action="../admins/manage_accounts.php"><input type="submit" value="manage accounts"></form>';
}

//query for dropdown menu
echo '<h1>What class would you like to add?</h1>';
$classOptions = get("CONCAT(courses.department,courses.course_number,'-',classes.semester,' ',classes.year,'-',classes.section) AS class_name,classes.Class_ID","classes NATURAL JOIN courses","classes.Class_ID NOT IN (SELECT Class_ID FROM student_classes WHERE student_ID=$student_id)"," ORDER BY classes.Class_ID");
//check if join_class button has been hit
if(!isset($_POST['join_class'])) {
echo "<form method='POST' action='student_add_class.php'>";
	echo "<select name='options'>";
	foreach($classOptions as $classOption) {
		echo "<option value=$classOption[1]>$classOption[0]</option>";
	}	

	echo "</select>";
	echo "<input type='submit' name='join_class' value='Join Class'>";
echo "</form>";
}
//update student_classes
if(isset($_POST['join_class'])) {
	//echo $_POST['options']."</br>";
	//echo $student_id."</br>";
	$err = insertStudentClass($_POST['options'],$student_id);
}	
	

?>
