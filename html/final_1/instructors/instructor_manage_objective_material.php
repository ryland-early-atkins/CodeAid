<!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is for instructors to manage objective materials
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
	title('manage materials');
	$sheets=array('../CSS_sheets/account_list.css','../CSS_sheets/error_messages.css');
	styles($sheets);

	//updates the active status for accounts
	if(isset($_SESSION['materials']) && isset($_POST['material_status'])){
		$materials=$_SESSION['materials'];
		$material_status=$_POST['material_status'];
		$info=$_POST['info'];
		$name=$_POST['name'];
		$count=-1;
		foreach($materials as $material){
			$count++;
			if(isset($_POST['edit']) && $_POST['edit']){
				$material[1]=$name[$count];
				$material[2]=$info[$count];
			}
			if($material_status[$count]=='active'){
				$temp=1;
			}else{$temp=0;}
			$err=updateField("supplementary_material","active",$temp,"material_ID=".$material[0]." AND Objective_ID=$objectiveid");
			if($err){
				echo '<p id="error"> failed to update material active status for'.$material[1].'</p>';
			}
			$fields=array("Material_ID","name","info");
			$type=array("i","s","s");
			for($i=1;$i<count($material)-1;$i++){
				$err=updateFieldBind("supplementary_material",$fields[$i],$material[$i],"material_ID=".$material[0]." AND Objective_ID=$objectiveid",$type[$i]);
				if($err){
					echo '<p id="error"> failed to update '.$fields[$i].'</p>';
				}	
			}
		}
	}

	//convenience items on how much and what to display
	$status=array("Active Materials","Inactive Materials","All Materials");
	$bool_stmt=array("active=1","active=0","1=1");
	if(isset($_POST['active_materials'])){
		$active_materials=$_POST['active_materials'];
	}else{
		$active_materials=0;
	}
	if(isset($_POST['limit'])){
		$limit=$_POST['limit'];
	}else{
		$limit=20;
	}
	//get students enrolled in class
	$materials=get("Material_ID,name,info,active","supplementary_material","Objective_ID=".$objectiveid." AND ".$bool_stmt[$active_materials]," limit ".$limit);
	$_SESSION['materials']=$materials;
?>
<body>
<!--top of page buttons-->
<form style="float:left;" action='instructor_manage_class_objective.php'><input type="submit" value="back"></form>
<form style="float:right;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>
<form style="text-align:left;" action="instructor_home.php"><input type="submit" value="home"></form>

<h1 style="text-align:center"><?php echo $status[$active_materials] ?></h1>
<h3><?php echo "Objective: $objectiveName"; ?></h3>

<!-- The whole page is essentially a form so that each account can be activated or deactivated -->
<form action='instructor_manage_objective_material.php' method='post' id='manage_q'>
	<p style="float:right;"><input type='radio' id="radio" name='active_materials' value=0 <?php if($active_materials==0)echo "checked";?>> active
	<input type='radio' id="radio" name='active_materials' value=1 <?php if($active_materials==1)echo "checked";?>> inactive 
	<input type='radio' id="radio" name='active_materials' value=2 <?php if($active_materials==2)echo "checked";?>> both
	<input type='submit' value="update form"></p>
	Show: <input style="dislay:inline;" type='number' id="limit" name='limit' value=<?php echo "$limit"; ?>>

<!-- This section creates display for student -->
<table>
	<tr><th>Material Name</th><th>Info</th><th>Active</th></tr>
	<?php
		echo '<input type="hidden" name="material_status[0]" value="nonactive">';
		echo '<input type="hidden" name="edit" value='.$_POST['edit'].'>';
		echo '<input type="hidden" name="objective_id" value='.$objectiveid.'>';
		for($i=0;$i<count($materials);$i++){
			//if edit mode show text fields
			if(isset($_POST['edit']) && $_POST['edit']){
				echo '<tr><td><input type="text" name="name['.$i.']" value="'.$materials[$i][1].'"></td>';
				echo '<td><textarea rows="4" cols="50" name="info['.$i.']" form="manage_q">'.$materials[$i][2].'</textarea></td><td>';
			}else{
				echo '<tr><td>'.$materials[$i][1].'</td><td>'.$materials[$i][2].'</td><td>';
			}
			//material active status
			if($materials[$i][3]){
				echo '<input type="checkbox" name="material_status['.$i.']" value="active" checked>';
			}else{
				echo '<input type="checkbox" name="material_status['.$i.']" value="active">';
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
	echo '<form style="text-align:left;float:left;" action="instructor_manage_objective_material.php" method="post">';
	echo '<input type="hidden" name="objective_id" value='.$objectiveid.'>';
	echo '<input type="submit" value="exit edit mode"><input type="hidden" name="edit" value=0>';
	echo '<input type="hidden" name="limit" value='.$limit.'>';
	echo '<input type="hidden" name="active_materials" value='.$active_materials.'></form>';
	
}else{
	echo '<form style="text-align:left;float:left;" action="instructor_manage_objective_material.php" method="post">';
	echo '<input type="hidden" name="objective_id" value='.$objectiveid.'>';
	echo '<input type="submit" value="edit mode"><input type="hidden" name="edit" value=1>';
	echo '<input type="hidden" name="limit" value='.$limit.'>';
	echo '<input type="hidden" name="active_materials" value='.$active_materials.'></form>';
}
?>
<form action="instructor_create_material.php" method="post" style="text-align:left;">
	<?php echo '<input type="hidden" name="objective_id" value='.$objectiveid.'>'; ?>
	<input type="submit" value="Add Material">
</form>
</body>
</html>
