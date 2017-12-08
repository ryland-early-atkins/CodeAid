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

	title("upload questions");
	$sheets=array('../CSS_sheets/instructor_home.css');
	styles($sheets);
 ?>
	<body>
	<h1> Bulk Upload </h1>	
		
	<?php
        $mysqli = new mysqli("localhost","teambasic","visualbasic","CodeAid");
        if ($mysqli->connect_errno) { // Check connection
		echo "Failed to connect to MySQL: ".$mysqli->connect_errno;
	}
        ?> 	
		
		<textarea rows="4" cols="50" name="question" form="usrform">
IMPORTANT! PLEASE USE THIS FORMAT:

		#question text 1...
		
		A. blablabla
		,,B. blablabla
		,,C. blablabla
		...
		,,N. blablabla
		;;B //correct answer
		;;5 //points assigned to question
		;;3 //maximum attempts allowed

		#question text 2...
		
		A. blablabla
		,,B. blablabla
		,,C. blablabla
		...
		,,N. blablabla
		;;C
		;;10
		;;5

</textarea> <br>
		
		<form action="instructor_bulk_upload.php" id="usrform" method="post">
		<?php echo '<input type="hidden" name="objective_id" value='.$objectiveid.'>'; ?>
		<input type="submit" name="submit">
		</form>
		<br>
		
		<?php
			
		if(isset($_POST['submit']))
		{
			$questions = array();
			$question_text = $_POST['question'];
			$question_set = explode("#",$question_text);
			foreach($question_set as $each) {
						$split = explode(";;",$each);
						$question = $split[0];
						$answer = $split[1];
						$points = (int)$split[2];
						$attempts = (int)$split[3];
			}
		
			echo "$obj_option<br>";
			$sql = "INSERT INTO questions 
				(Objective_ID,active,question,solution,points,max_attempts) 
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
