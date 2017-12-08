<!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is for instructors to create questions
-->

<?php 
        //include several of the main helper function files
	include '../function_files.php';

        session_start();

	//redirects to login if invalid session
	testInstructor($_SESSION['id'],$_SESSION['userid']);

	$objectiveid=$_POST['objective_id'];
	if(!$objectiveid || empty($objectiveid)){
		$objectiveid=$_SESSION['objectiveid'];
	}
	$_SESSION['objectiveid']=$objectiveid;

	echo '<form style="float:right;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>';
	echo '<form style="float:left;" action="instructor_manage_objective_question.php" method="post"><input type="submit" value="back">';
	echo '<input type="hidden" name="objective_id" value='.$objectiveid.'></form>';
	echo '<form style="text-align:left;" action="instructor_home.php"><input type="submit" value="home"></form>';

	title("create questions");
	$sheets=array('../CSS_sheets/instructor_home.css');
	styles($sheets);
 ?>
	<body>
		<h1> Create Question</h1>	
		
		<?php
            $mysqli = new mysqli("localhost","teambasic","visualbasic","CodeAid");
            if ($mysqli->connect_errno) { // Check connection
                echo "Failed to connect to MySQL: ".$mysqli->connect_errno;  }
            
		?> 	
		
		<textarea rows="4" cols="50" name="question" form="usrform">
		Enter questions here...</textarea> <br>
		
		<form action="instructor_create_question.php" id="usrform" method="post">
			Answer: <input type="text" name="answer"><br>
			points: <input type="number" name="points"><br>
			Attempts: <input type="number" name="attempts"><br>
			<?php echo '<input type="hidden" name="objective_id" value='.$objectiveid.'>'; ?>
			<input type="submit" name="submit">
		</form>
		<form action="instructor_bulk_upload.php" id="usrform" method="post">
		<input type="submit" name="bulk_upload" value="Bulk Upload">
		</form>
		<br>
		
		<?php
			
		if(isset($_POST['submit']))
                {

					$question = $_POST['question'];
					$answer = $_POST['answer'];
					$points = (int) $_POST['points'];
					$attempts = (int) $_POST['attempts'];
					
					echo "$obj_option<br>";
					$sql = "INSERT INTO questions (Objective_ID,active,question,solution,points,max_attempts)
							VALUES
							($objectiveid, 1,'$question', '$answer', $points, $attempts);";
								
					if (!$result = $mysqli->query($sql)) {
                        echo "Error creating new question";
                        exit;
                    }
                    echo "Successfully created new question";
							
					
                }
		?>

	</body>
	
</html>
