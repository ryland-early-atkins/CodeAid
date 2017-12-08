<!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is for instructors to view student progress
-->
<?php
	//resume session and get variables
        session_start();
	$instructor_id=$_SESSION['userid'];
	$_SESSION['return_path']='../instructors/instructor_home.php';

	//include several of the main helper function files
	include '../function_files.php';
	
	//verifies this is a valid session
	testInstructor($_SESSION['id'],$_SESSION['userid']);

	title("instructor home");
	$sheets=array("../CSS_sheets/instructor_home.css");
	styles($sheets);

	//this file is for testing student success in an object
	//this was done so that it will be easy to change how one wants to verify student success
	include "../students/test_student_progress.php";

	//create logout button
	echo '<form style="float:right;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>';

	//check if admin
    	if($instructor_id<=10 && $instructor_id>=0){
		echo '<h2>Admin Options:</h2>';
		echo '<form action="../admins/manage_accounts.php"><input type="submit" value="manage accounts"></form>';
	}

	//main page header
	echo '<h1> Active Classes Overview</h1>';

	//link to manage classes page
	echo '<form action="../instructors/instructor_manage_classes.php"><input type="submit" value="manage classes"></form>';

	//get an instructors active classes
	$classes=get("Class_ID,Course_ID","classes","Instructor_ID= ".$instructor_id." AND active=1"," ORDER BY year,semester DESC");

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
		if(empty($objectives)){
			echo '<h5> No Currently Active Objectives </h5>';
		}else{
			echo '<table><tr><th></th>';
			//list objective names as headers
			foreach($objectives as $objective){
				echo '<th>'.$objective[1].'</th>';
			}
			echo '</tr>';

			//get the students in this class
			$students=get("first_name,last_name,Student_ID","students NATURAL JOIN (SELECT Student_ID FROM student_classes where Class_ID=".$class[0]." AND active=1) AS temp","1=1","");
			if($students || !empty($students)){
				$objtots=array_fill(0,count($objectives),0);
				foreach($students as $student){
					//uses student name to lead row
					echo '<tr><th>'.$student[0].' '.$student[1].'</th>';
					//build the row
					$count=0;
					foreach($objectives as $objective){
						//call function to score student progress
						$prog=progSimple($student[2],$objective[0]);
						echo '<td>';
						echo '<form action="../students/attempt_history.php" method="post">';
						echo '<input type="hidden" name="objective_id" value='.$objective[0].'>';
						echo '<input type="hidden" name="student_id" value='.$student[2].'>';
						echo '<input type="submit" value='.$prog.'>';
						echo '</form></td>';
						$objtots[$count]+=$prog;
						$count++;				
					}
					echo '</tr>';
				}
				echo '<tr><th>Class Average:</th>';
				//list objective names as headers
				foreach($objtots as $objtot){
					echo '<th>'.round($objtot/(count($students)),2).'</th>';
				}
				echo '</tr>';
				echo '</table>';
			}else{
				echo '</table>';
				echo '<h5>no students enrolled in class</h5>';
			}
		}
	}
?>
</html>
