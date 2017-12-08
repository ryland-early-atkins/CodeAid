<!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is to view student history on question attempts
-->

<?php
	//include several of the main helper function files
	include '../function_files.php';

	session_start();
	
	//verifies this is a valid session
	//testInstructor($_SESSION['id'],$_SESSION['userid']);

	//get important variables
	$objectiveid=$_POST['objective_id'];
	if(!$objectiveid || empty($objectiveid)){
		$objectiveid=$_SESSION['objectiveid'];
	}
	$_SESSION['objectiveid']=$objectiveid;

	$studentid=$_POST['student_id'];
	if(!$studentid || empty($studentid)){
		$studentid=$_SESSION['studentid'];
	}
	$_SESSION['studentid']=$studentid;

	$questionid=$_POST['question_id'];
	if(!$questionid || !isset($questionid)){
		$bool="1=1";
	}else{$bool="Question_ID=$questionid";}
	
	//inialization info for page
	title('manage attempts');
	$sheets=array('../CSS_sheets/account_list.css','../CSS_sheets/error_messages.css');
	styles($sheets);

	if(isset($_POST['active_attempts'])){
		$active_attempts=$_POST['active_attempts'];
	}else{
		$active_attempts=0;
	}
	if(isset($_POST['limit'])){
		$limit=$_POST['limit'];
	}else{
		$limit=20;
	}

	//convenience items on how much and what to display
	$status=array("Attempts on Active Questions","Attempts on Inactive Questions","All Attempts");
	$bool_stmt=array("active=1","active=0","1=1");
	//select questions of specified active status
	$filter_active="(SELECT * FROM questions WHERE ".$bool_stmt[$active_attempts]." AND $bool) AS fq";
	//select from filtered question those associated with current objective
	$filter_object="(SELECT Question_ID,question,solution FROM $filter_active WHERE Objective_ID=$objectiveid) AS fo";
	//full from query
	$from_stmt="student_attempts NATURAL JOIN $filter_object";

	//get students enrolled in class
	$attempts=get("attempt,question,solution,answer",$from_stmt,"Student_ID=$studentid"," limit ".$limit);
	$_SESSION['attempts']=$attempts;
?>
<body>


<form style="float:right;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>
<?php echo'<form style="text-align:left;" action="'.$_SESSION['return_path'].'"><input type="submit" value="home"></form>';?>

<h1 style="text-align:center"><?php echo $status[$active_attempts] ?></h1>

<!-- The whole page is essentially a form so that each account can be activated or deactivated -->
<form action='attempt_history.php' method='post' id='manage_q'>
	<p style="float:right;"><input type='radio' id="radio" name='active_attempts' value=0 <?php if($active_attempts==0)echo "checked";?>> active
	<input type='radio' id="radio" name='active_attempts' value=1 <?php if($active_attempts==1)echo "checked";?>> inactive 
	<input type='radio' id="radio" name='active_attempts' value=2 <?php if($active_attempts==2)echo "checked";?>> both
	<input type='submit' value="update form"></p>
	Show: <input style="dislay:inline;" type='number' id="limit" name='limit' value=<?php echo "$limit"; ?>>

<!-- This section creates display for student -->
<table>
	<tr><th>Attempt Number</th><th>Question</th><th>Correct Solution</th><th>Solution</th><th>Status</th></tr>
	<?php
		echo '<input type="hidden" name="objective_id" value='.$objectiveid.'>';
		include 'test_student_progress.php';
		foreach($attempts as $attempt){
			//if edit mode show text fields
			if(verifySolution($attempt[2],$attempt[3])){
				$symbol='&#10004;';
			}else{
				$symbol='&#10006;';
			}
			echo '<tr><td>'.$attempt[0].'</td><td>'.$attempt[1].'</td><td>'.$attempt[2].'</td><td>'.$attempt[3].'</td><td>'.$symbol.'</td>';
			echo '</tr>';
		}
	?>
</table>
</p>
<input type='submit' style="float:left" value="update form">
</form>
</body>
</html>
