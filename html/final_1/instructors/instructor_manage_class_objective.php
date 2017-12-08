<!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is for instructors to manage their class objectives
-->

<?php

	//include several of the main helper function files
	include '../function_files.php';

        session_start();

	//redirects to login if invalid session
	testInstructor($_SESSION['id'],$_SESSION['userid']);

	$classid = $_POST['class_id'];
	if(!isset($classid) || !$classid){
		$classid=$_SESSION['class_id'];
	}
	$_SESSION['class_id']=$classid;
	$courseid=get("Course_ID","classes","Class_ID=".$classid,"");
	$session['course_id']=$courseid;
	
	//inialization info for page
	title('class objectives');
	$sheets=array('../CSS_sheets/account_list.css','../CSS_sheets/error_messages.css');
	styles($sheets);

	//updates the active status for accounts
	if(isset($_SESSION['objectives']) && isset($_POST['objective_status'])){
		$objectives=$_SESSION['objectives'];
		$objective_status=$_POST['objective_status'];
		$name=$_POST['name'];
		$description=$_POST['description'];
		$count=-1;
		foreach($objectives as $objective){
			$count++;
			if(isset($_POST['edit']) && $_POST['edit']){
				$objective[1]=$name[$count];
				$objective[2]=$description[$count];
			}
			if($objective_status[$count]=='active'){
				$temp=1;
			}else{$temp=0;}
			$err=updateField("objectives","active",$temp,"Objective_ID=".$objective[0]);
			if($err){
				echo '<p id="error"> failed to update student active status for'.$objective[0].'</p>';
			}
			$fields=array("","name","description");
			$type=array("","s","s");
			for($i=1;$i<count($fields);$i++){
				$err=updateFieldBind("objectives",$fields[$i],$objective[$i],"Objective_ID=".$objective[0],$type[$i]);
				if($err){
					echo '<p id="error"> failed to update '.$fields[$i].'</p>';
				}	
			}
		}
	}

	//convenience items on how much and what to display
	$status=array("Active Objectives","Inactive Objectives","All Objectives");
	$bool_stmt=array("active=1","active=0","1=1");
	$from_stmt="objectives";
	if(isset($_POST['active_objs'])){
		$active_objs=$_POST['active_objs'];
	}else{
		$active_objs=2;
	}
	if(isset($_POST['limit'])){
		$limit=$_POST['limit'];
	}else{
		$limit=20;
	}
	//get objectives from class
	$objectives=get("Objective_ID,name,description,active",$from_stmt,"Course_ID=".$courseid[0][0]." AND ".$bool_stmt[$active_objs]," limit ".$limit);
	$_SESSION['objectives']=$objectives;
?>
<body>

<form style="float:right;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>
<form style="float:left;" action='instructor_manage_classes.php'><input type="submit" value="back"></form>
<form style="text-align:left;" action="instructor_home.php"><input type="submit" value="home"></form>
<h1 style="text-align:center"><?php echo $status[$active_objs] ?></h1>

<!-- The whole page is essentially a form so that each account can be activated or deactivated -->
<form action='instructor_manage_class_objective.php' method='post' id='manage_q'>
	<p style="float:right;"><input type='radio' id="radio" name='active_objs' value=0 <?php if($active_objs==0)echo "checked";?>> active
	<input type='radio' id="radio" name='active_objs' value=1 <?php if($active_objs==1)echo "checked";?>> inactive 
	<input type='radio' id="radio" name='active_objs' value=2 <?php if($active_objs==2)echo "checked";?>> both
	<input type='submit' value="update form"></p>
	Show: <input style="dislay:inline;" type='number' id="limit" name='limit' value=<?php echo "$limit"; ?>>

<!-- This section creates display for student -->
<table>
	<tr><th>Name</th><th>Description</th><th>Edit</th><th>Active</th></tr>
	<?php
		echo '<input type="hidden" name="objective_status[0]" value="nonactive">';
		echo '<input type="hidden" name="edit" value='.$_POST['edit'].'>';
		echo '<input type="hidden" name="class_id" value="'.$classid.'">';
		for($i=0;$i<count($objectives);$i++){
			if(isset($_POST['edit']) && $_POST['edit']){
				echo '<tr><td><input type="text" name="name['.$i.']" value="'.$objectives[$i][1].'"></td>';
				echo '<td><textarea rows="4" cols="50" name="description['.$i.']" form="manage_q">'.$objectives[$i][2].'</textarea></td><td>';
				echo '<form method="post">';
				echo '<input type="hidden" name ="objective_id" value='.$objectives[$i][0].'>';
				echo '<input type="submit" name ="class_questions_button" value="Objective Questions >>" formaction="instructor_manage_objective_question.php">';
				echo '<input type="submit" name ="class_materials_button" value="Objective Material >>" formaction="instructor_manage_objective_material.php">';
			echo '</form></td><td>';
			}else{
				echo '<tr><td>'.$objectives[$i][1].'</td><td>'.$objectives[$i][2].'</td><td>';
				//add form with links to edit cell
				echo '<form method="post">';
				echo '<input type="hidden" name ="objective_id" value='.$objectives[$i][0].'>';
				echo '<input type="submit" name ="class_questions_button" value="Objective Questions >>" formaction="instructor_manage_objective_question.php">';
				echo '<input type="submit" name ="class_materials_button" value="Objective Material >>" formaction="instructor_manage_objective_material.php">';
			echo '</form></td><td>';//close table cell edit
			}
			if($objectives[$i][3]){
				echo '<input type="checkbox" name="objective_status['.$i.']" value="active" checked>';
			}else{
				echo '<input type="checkbox" name="objective_status['.$i.']" value="active">';
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
	echo '<form style="text-align:left;float:left;" action="instructor_manage_class_objective.php" method="post">';
	echo '<input type="hidden" name="class_id" value='.$classid.'>';
	echo '<input type="submit" value="exit edit mode"><input type="hidden" name="edit" value=0>';
	echo '<input type="hidden" name="limit" value='.$limit.'>';
	echo '<input type="hidden" name="active_objs" value='.$active_objs.'></form>';
	
}else{
	echo '<form style="text-align:left;float:left;" action="instructor_manage_class_objective.php" method="post">';
	echo '<input type="hidden" name="class_id" value='.$classid.'>';
	echo '<input type="submit" value="edit mode"><input type="hidden" name="edit" value=1>';
	echo '<input type="hidden" name="limit" value='.$limit.'>';
	echo '<input type="hidden" name="active_objs" value='.$active_objs.'></form>';
}
?>
<form style="text-align:left;" action="instructor_create_objective.php"><input type="submit" value="Add Objective"></form>
</body>
</html>
