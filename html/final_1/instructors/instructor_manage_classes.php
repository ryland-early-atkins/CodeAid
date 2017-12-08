<!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is for instructors to manage their classes
-->

<?php
	//include several of the main helper function files
	include '../function_files.php';

	session_start();
	$instructor_id=$_SESSION['userid'];

	//verifies this is a valid session
	testInstructor($_SESSION['id'],$_SESSION['userid']);
	
	//inialization info for page
	title('manage classes');
	$sheets=array('../CSS_sheets/account_list.css','../CSS_sheets/error_messages.css');
	styles($sheets);

	//updates the active status for accounts
	if(isset($_SESSION['classes']) && isset($_POST['class_status'])){
		$classes=$_SESSION['classes'];
		$class_status=$_POST['class_status'];
		$count=-1;
		foreach($classes as $class){
			$count++;
			if($class_status[$count]=='active'){
				$temp=1;
			}else{$temp=0;}
			$err=updateField("classes","active",$temp,"Class_ID=".$class[0]);
			if($err){
				echo '<p id="error"> failed to update class active status for'.$class[1].$class[2].$class[3].'</p>';
			}
		}
	}

	//convenience items on how much and what to display
	$status=array("Active Classes","Inactive Classes","All Classes");
	$bool_stmt=array("active=1","active=0","1=1");
	$sel="Class_ID,department,course_number,section,name,active,semester,year";
	$from="(SELECT Course_ID,Class_ID,section,active,year,semester FROM classes WHERE Instructor_ID=$instructor_id) AS temp NATURAL JOIN courses";
	if(isset($_POST['active'])){
		$active=$_POST['active'];
	}else{
		$active=0;
	}
	if(isset($_POST['limit'])){
		$limit=$_POST['limit'];
	}else{
		$limit=20;
	}
	//get instructors class
	$classes=get($sel,$from,$bool_stmt[$active]," ORDER BY year,semester DESC limit ".$limit);
	$_SESSION['classes']=$classes;
?>
<body>
<form style="text-align:left;display:inline;" action='instructor_home.php'><input type="submit" value="back"></form>
<form style="float:right;display:inline;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>
<h1 style="text-align:center"><?php echo $status[$active] ?></h1>

<!-- The whole page is essentially a form so that each account can be activated or deactivated -->
<form action='instructor_manage_classes.php' method='post'>
	<p style="float:right;"><input type='radio' id="radio" name='active' value=0 <?php if($active==0)echo "checked";?>> active
	<input type='radio' id="radio" name='active' value=1 <?php if($active==1)echo "checked";?>> inactive 
	<input type='radio' id="radio" name='active' value=2 <?php if($active==2)echo "checked";?>> both
	<input type='submit' value="update form"></p>
	Show: <input style="dislay:inline;" type='number' id="limit" name='limit' value=<?php echo "$limit"; ?>>

<!-- This section creates display for student -->
<table>
	<tr><th>Semsester</th><th>Year</th><th>Department</th><th>Course Number</th><th>Section</th><th>Name</th><th>Edit</th><th>Active</th></tr>
	<?php
		echo '<input type="hidden" name="class_status[0]" value="nonactive">';
		for($i=0;$i<count($classes);$i++){
			//build row
			echo '<tr><td>'.$classes[$i][6].'</td><td>'.$classes[$i][7].'</td><td>'.$classes[$i][1].'</td><td>'.$classes[$i][2].'</td><td>'.$classes[$i][3].'</td><td>'.$classes[$i][4].'</td><td>';
			//add form with links to edit cell
			echo '<form method="post">';
			echo '<input type="hidden" name ="class_id" value='.$classes[$i][0].'>';
			echo '<input type="submit" name ="class_objective_button" value="Class Objectives >>" formaction="instructor_manage_class_objective.php">';
			echo '<input type="submit" name ="class_students_button" value="Students >>" formaction="instructor_manage_class_students.php"></form>';
			echo '</td><td>';//close table cell edit
			if($classes[$i][5]){
				echo '<input type="checkbox" name="class_status['.$i.']" value="active" checked>';
			}else{
				echo '<input type="checkbox" name="class_status['.$i.']" value="active">';
			}
			echo '</td></tr>';
		}
	?>
</table>
</p>
<input type='submit' style="float:left" value="update form">
</form>
<form style="text-align:left;" action="instructor_create_class.php"><input type="submit" value="Add Class"></form>
</body>
</html>
