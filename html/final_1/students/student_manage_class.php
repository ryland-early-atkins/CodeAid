<?php
	//resume session and get variables
	session_start();

	$student_id=$_SESSION['userid'];

	//include several of the main helper function files
	include '../function_files.php';

	//verifies this is a valid session
	testStudent($_SESSION['id'],$_SESSION['userid']);

	//initialization information
	title("Manage Class");
	$sheets=array("../CSS_sheets/instructor_home.css");
	styles($sheets);

	//this file is for testing student success in an object
	//this was done so that it will be easy to change how one wants to verify student success
	include "../students/test_student_progress.php";

	//create logout button
	echo '<form style="float:right;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>';
	//create back button
	echo '<form style="float:left;" action="student_welcome_1.php"><input type="submit" value="Back"></form>';
	//check if admin
    if($student_id<=10 && $student_id>=0){
		echo '<h2>Admin Options:</h2>';
		echo '<form action="../admins/manage_accounts.php"><input type="submit" value="manage accounts"></form>';
	}
    
    
    //main page header
	echo '<h1>Student Manage Class</h1>';

    //table of ACTIVE CLASSES this student is in
    $active_classes=get("CONCAT(courses.department,courses.course_number,'-',classes.semester,' ',classes.year,'-',classes.section) AS class_name,classes.Class_ID,Student_ID,student_classes.active","student_classes,classes,courses","classes.Course_ID = courses.Course_ID AND student_classes.Class_ID = classes.Class_ID AND Student_ID=$student_id AND student_classes.active =1"," ORDER BY Class_ID");
    
    if(empty($active_classes)){
		echo '<h5> No Currently Active Classes </h5>';
	}
    echo '<table>';
    echo '<tr><th>Class ID</th><th>Drop Classes</th></tr>';
	foreach($active_classes as $active_class){
		echo '<tr><td>'.$active_class[0].'</td>';
		echo '<td><form method="POST">
		<input type="hidden" name="student_class_id" value="'.$active_class[1].'" formaction="drop_class_confirm.php">
		<input type="submit" name="drop_class" value="Drop Class >>" formaction="drop_class_confirm.php">
		</form></td></tr>';	    
	
	}
    
    echo '</table>';

echo '<form action="student_add_class.php" method="POST"><input type="submit" name="new_class" value="Add Class"></form>';

?>


