<?php
	//resume session and get variables
        session_start();

	$student_id=$_SESSION['userid'];
	$_SESSION['return_path']='../students/student_welcome_1.php';

	//include several of the main helper function files
	include '../function_files.php';
	
	//verifies this is a valid session
	testStudent($_SESSION['id'],$_SESSION['userid']);

	title("student home");
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
    
    
    //main page header
	echo '<h1>Student Home Page</h1>';
    
    //link to manage classes page
	echo '<form action="../students/student_manage_class.php"><input type="submit" value="manage classes"></form>';
    
    //get an instructors active classes
	$classes=get("Class_ID, Course_ID","classes NATURAL JOIN (SELECT Class_ID FROM student_classes WHERE Student_ID = $student_id AND active = 1) AS temp","1=1"," ORDER BY year DESC");


    //create a table for each class
	if(empty($classes)){
		echo '<h5> No Currently Active Classes </h5>';
	}
	foreach($classes as $class){
        	//get and display class name
		$name=get("name","courses","Course_ID=".$class[1],"");
		echo '<h3>'.$name[0][0].':</h3>';
		//get the objectives of the course associated with this class
		$objectives=get("Objective_ID,name","objectives","Course_ID=".$class[1]." AND active=1","");
        	//start table construction
		echo '<form method="POST" action="problem_page_1.php">';
		echo '<table>';
		echo '<tr><th><input type="submit" name="start_quiz" value="Start Quiz"></th>';
		//list objective names as headers
		foreach($objectives as $objective){
			echo '<th><input type="checkbox" name="quiz_objectives[]" value='.$objective[0].' checked >';
			echo $objective[1];
			//create checkbox for each quiz item
			//echo $objective[0];
			echo '</th>';
		}
		echo '<input type="hidden" name="start_val" value=0>';
		echo '</tr></form>';
	

		$student=get("first_name,last_name,Student_ID","students NATURAL JOIN (SELECT Student_ID FROM student_classes where Class_ID=".$class[0]." AND active=1) AS temp","Student_ID = $student_id","");
        
        //uses student name to lead row
        echo '<tr><th>'.$student[0][0].' '.$student[0][1].'</th>';
        //build the row
        foreach($objectives as $objective){
            //call function to score student progress
            $prog=progSimple($student[0][2],$objective[0]);
	    echo '<td><form method="POST" action="attempt_history.php">';
	    echo '<input type="hidden" name="objective_id" value='.$objective[0].'>';
	    echo '<input type="hidden" name="student_id" value='.$student[0][2].'>';
	    echo '<input type="submit" name="obj_page" value='.$prog.'>';
	    echo '</form></td>';				
        }
	echo '</tr>';
        echo '</table>';
    }
?>


