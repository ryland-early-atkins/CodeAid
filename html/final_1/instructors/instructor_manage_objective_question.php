<!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is for instructors to manage objective questions
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
	
	$objectiveName=get("name","objectives","Objective_ID=$objectiveid","")[0][0];	

	//inialization info for page
	title('manage questions');
	$sheets=array('../CSS_sheets/account_list.css','../CSS_sheets/error_messages.css');
	styles($sheets);

	//updates the active status for accounts
	if(isset($_SESSION['questions']) && isset($_POST['question_status'])){
		$questions=$_SESSION['questions'];
		$question_status=$_POST['question_status'];
		$quest_val=$_POST['quest_val'];
		$solution=$_POST['solution'];
		$points=$_POST['points'];
		$max_atts=$_POST['max_atts'];
		$count=-1;
		foreach($questions as $question){
			$count++;
			if(isset($_POST['edit']) && $_POST['edit']){
				$question[1]=$quest_val[$count];
				$question[2]=$solution[$count];
				$question[3]=$points[$count];
				$question[4]=$max_atts[$count];
			}
			if($question_status[$count]=='active'){
				$temp=1;
			}else{$temp=0;}
			$err=updateField("questions","active",$temp,"Question_ID=".$question[0]." AND Objective_ID=$objectiveid");
			if($err){
				echo '<p id="error"> failed to update question active status for'.$question[3].'</p>';
			}
			$fields=array("Question_ID","question","solution","points","max_attempts");
			$type=array("i","s","s","i","i");
			for($i=1;$i<count($question)-1;$i++){
				$err=updateFieldBind("questions",$fields[$i],$question[$i],"Question_ID=".$question[0]." AND Objective_ID=$objectiveid",$type[$i]);
				if($err){
					echo '<p id="error"> failed to update '.$fields[$i].'</p>';
				}	
			}
		}
	}

	//convenience items on how much and what to display
	$status=array("Active Questions","Inactive Questions","All Questions");
	$bool_stmt=array("active=1","active=0","1=1");
	$from_stmt="questions";
	if(isset($_POST['active_questions'])){
		$active_questions=$_POST['active_questions'];
	}else{
		$active_questions=0;
	}
	if(isset($_POST['limit'])){
		$limit=$_POST['limit'];
	}else{
		$limit=20;
	}
	//get students enrolled in class
	$questions=get("Question_ID,question,solution,points,max_attempts,active",$from_stmt,"Objective_ID=".$objectiveid." AND ".$bool_stmt[$active_questions]," limit ".$limit);
	$_SESSION['questions']=$questions;
?>
<body>

<form style="float:right;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>
<form style="float:left;" action='instructor_manage_class_objective.php'><input type="submit" value="back"></form>
<form style="text-align:left;" action="instructor_home.php"><input type="submit" value="home"></form>

<h1 style="text-align:center"><?php echo $status[$active_questions] ?></h1>
<h3><?php echo "Objective: $objectiveName"; ?></h3>

<!-- The whole page is essentially a form so that each account can be activated or deactivated -->
<form action='instructor_manage_objective_question.php' method='post' id='manage_q'>
	<p style="float:right;"><input type='radio' id="radio" name='active_questions' value=0 <?php if($active_questions==0)echo "checked";?>> active
	<input type='radio' id="radio" name='active_questions' value=1 <?php if($active_questions==1)echo "checked";?>> inactive 
	<input type='radio' id="radio" name='active_questions' value=2 <?php if($active_questions==2)echo "checked";?>> both
	<input type='submit' value="update form"></p>
	Show: <input style="dislay:inline;" type='number' id="limit" name='limit' value=<?php echo "$limit"; ?>>

<!-- This section creates display for student -->
<table>
	<tr><th>Question</th><th>Solution</th><th>Points</th><th>Max Attempts</th><th>Active</th></tr>
	<?php
		echo '<input type="hidden" name="question_status[0]" value="nonactive">';
		echo '<input type="hidden" name="edit" value='.$_POST['edit'].'>';
		echo '<input type="hidden" name="objective_id" value='.$objectiveid.'>';
		for($i=0;$i<count($questions);$i++){
			//if edit mode show text fields
			if(isset($_POST['edit']) && $_POST['edit']){
				echo '<tr><td><textarea rows="4" cols="50" name="quest_val['.$i.']" form="manage_q">'.$questions[$i][1].'</textarea></td>';
				echo '<td><input type="text" name="solution['.$i.']" value="'.$questions[$i][2].'"></td>';
				echo '<td><input type="number" name="points['.$i.']" value="'.$questions[$i][3].'"></td>';
				echo '<td><input type="number" name="max_atts['.$i.']" value="'.$questions[$i][4].'"></td>';
				echo '</td><td>';
			}else{
				echo '<tr><td>'.$questions[$i][1].'</td><td>'.$questions[$i][2].'</td><td>';
				echo $questions[$i][3].'</td><td>'.$questions[$i][4].'</td><td>';
			}
			//question active status
			if($questions[$i][5]){
				echo '<input type="checkbox" name="question_status['.$i.']" value="active" checked>';
			}else{
				echo '<input type="checkbox" name="question_status['.$i.']" value="active">';
			}
			echo '</td></tr>';
		}
	?>
</table>
</p>
<input type='submit' style="float:left" value="update form">
</form>
<?php
if(isset($_POST['edit']) && $_POST['edit']){
	echo '<form style="text-align:left;float:left;" action="instructor_manage_objective_question.php" method="post">';
	echo '<input type="hidden" name="objective_id" value='.$objectiveid.'>';
	echo '<input type="submit" value="exit edit mode"><input type="hidden" name="edit" value=0>';
	echo '<input type="hidden" name="limit" value='.$limit.'>';
	echo '<input type="hidden" name="active_questions" value='.$active_questions.'></form>';
	
}else{
	echo '<form style="text-align:left;float:left;" action="instructor_manage_objective_question.php" method="post">';
	echo '<input type="hidden" name="objective_id" value='.$objectiveid.'>';
	echo '<input type="submit" value="edit mode"><input type="hidden" name="edit" value=1>';
	echo '<input type="hidden" name="limit" value='.$limit.'>';
	echo '<input type="hidden" name="active_questions" value='.$active_questions.'></form>';
}
?>
<form style="text-align:left;" action="instructor_create_question.php" method="post"><?php echo '<input type="hidden" name="objective_id" value='.$objectiveid.'>'; ?><input type="submit" value="Add Question"></form>
</body>
</html>
