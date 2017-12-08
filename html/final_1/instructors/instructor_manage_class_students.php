<!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is for instructors to manage students in their classes
-->

<?php
	//include several of the main helper function files
	include '../function_files.php';

        session_start();

	//redirects to login if invalid session
	testInstructor($_SESSION['id'],$_SESSION['userid']);

	$classid = $_POST['class_id'];
	if(!isset($classid)){
		$classid=$_SESSION['class_id'];
	}
	$_SESSION['class_id']=$classid;
	
	//inialization info for page
	title('student enrollment');
	$sheets=array('../CSS_sheets/account_list.css','../CSS_sheets/error_messages.css');
	styles($sheets);

	//updates the active status for accounts
	if(isset($_SESSION['enrollment']) && isset($_POST['enroll_status'])){
		$enrollment=$_SESSION['enrollment'];
		$enroll_status=$_POST['enroll_status'];
		$count=-1;
		foreach($enrollment as $enrollee){
			$count++;
			if($enroll_status[$count]=='active'){
				$temp=1;
			}else{$temp=0;}
			$err=updateField("student_classes","active",$temp,"Student_ID='".$enrollee[0]."' AND Class_ID='".$classid."'");
			if($err){
				echo '<p id="error"> failed to update student active status for'.$enrollee[3].'</p>';
			}
		}
	}

	//convenience items on how much and what to display
	$status=array("Enrolled Students","Students Pending Enrollment","All Students Joining Class");
	$bool_stmt=array("active=1","active=0","1=1");
	$from_stmt="student_classes NATURAL JOIN (SELECT Student_ID,first_name,last_name,email FROM students where active=1) AS temp";
	if(isset($_POST['active_studs'])){
		$active_studs=$_POST['active_studs'];
	}else{
		$active_studs=2;
	}
	if(isset($_POST['limit'])){
		$limit=$_POST['limit'];
	}else{
		$limit=20;
	}
	//get students enrolled in class
	$enrollment=get("Student_ID,first_name,last_name,email,active",$from_stmt,"Class_ID=".$classid." AND ".$bool_stmt[$active_studs]," limit ".$limit);
	$_SESSION['enrollment']=$enrollment;
?>
<body>
<form style="float:left;" action='instructor_manage_classes.php'><input type="submit" value="back"></form>
<form style="float:right;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>
<form style="text-align:left;" action="instructor_home.php"><input type="submit" value="home"></form>
<h1 style="text-align:center"><?php echo $status[$active_studs] ?></h1>

<!-- The whole page is essentially a form so that each account can be activated or deactivated -->
<form action='instructor_manage_class_students.php' method='post'>
	<p style="float:right;"><input type='radio' id="radio" name='active_studs' value=0 <?php if($active_studs==0)echo "checked";?>> enrolled
	<input type='radio' id="radio" name='active_studs' value=1 <?php if($active_studs==1)echo "checked";?>> pending 
	<input type='radio' id="radio" name='active_studs' value=2 <?php if($active_studs==2)echo "checked";?>> both
	<input type='submit' value="update form"></p>
	Show: <input style="dislay:inline;" type='number' id="limit" name='limit' value=<?php echo "$limit"; ?>>

<!-- This section creates display for student -->
<table>
	<tr><th>ID</th><th>Name</th><th>Email</th><th>Enrolled</th></tr>
	<?php
		echo '<input type="hidden" name="enroll_status[0]" value="nonactive">';
		echo '<input type="hidden" name="class_option" value="'.$classid.'">';
		for($i=0;$i<count($enrollment);$i++){
			echo '<tr><td>'.$enrollment[$i][0].'</td><td>'.$enrollment[$i][1].' '.$enrollment[$i][2].'</td><td>'.$enrollment[$i][3].'</td><td>';
			if($enrollment[$i][4]){
				echo '<input type="checkbox" name="enroll_status['.$i.']" value="active" checked>';
			}else{
				echo '<input type="checkbox" name="enroll_status['.$i.']" value="active">';
			}
			echo '</td></tr>';
		}
	?>
</table>
</p>
<input type='submit' value="update form">
</form>
</body>
</html>
