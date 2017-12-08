 <!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is for instructors to create a supplementary material
-->   
<?php 
	//include several of the main helper function files
	include '../function_files.php';

        session_start();

	//redirects to login if invalid session
	testInstructor($_SESSION['id'],$_SESSION['userid']);

	//get objective id that was post from manage objectives page as well as back buttons
	$objectiveid=$_POST['objective_id'];
	if(!$objectiveid || empty($objectiveid)){
		$objectiveid=$_SESSION['objectiveid'];
	}
	$_SESSION['objectiveid']=$objectiveid;

	//travel buttons
	echo '<form style="float:right;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>';
	echo '<form style="float:left;" action="instructor_manage_objective_material.php" method="post"><input type="submit" value="back">';
	echo '<input type="hidden" name="objective_id" value='.$objectiveid.'></form>';
	echo '<form style="text-align:left;" action="instructor_home.php"><input type="submit" value="home"></form>';
	
	//include basic intro html stuff
	title("create material");
	$sheets=array('../CSS_sheets/instructor_home.css');
	styles($sheets);

	//this catchs variables from form post
	if(isset($_POST['submit_material']) && isset($_POST['name']) && isset($_POST['info']))
	{
		$err=insertMaterialBind($_POST['name'],$_POST['info'],$objectiveid);
		if($error){
    		echo "failed to create material";
		}else{
			reloc('instructor_manage_objective_material.php');
		}
	}
?>
<body>
	<h1> Create Material</h1>	
	<!-- creates form to enter material information -->
	<form action="instructor_create_material.php" id="usrform" method="post">
		Name: <input type="text" name="name"><br>
		<textarea rows="4" cols="50" name="info" form="usrform">Enter material info here...</textarea> <br>
		<?php echo '<input type="hidden" name="objective_id" value='.$objectiveid.'>'; ?>
		<input type="submit" name="submit_material" value="Create Material">
	</form>
</body>
	
</html>
