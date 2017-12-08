<!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is the second step for 2-step verification process for creating account
-->

<?php
	//include several of the main helper function files
	include '../function_files.php';
	
	session_start();
	
	//redirects to login if invalid session
	if(!($_SESSION['userid']>0 && $_SESSION['userid']<10) || empty($_SESSION['userid'])){
		reloc('login.php');
	}
	
	//inialization info for page
	title('account verification');
	$sheets=array('../CSS_sheets/account_list.css','../CSS_sheets/error_messages.css');
	styles($sheets);

	//updates the active status for accounts
	if(isset($_SESSION['saccounts']) && isset($_SESSION['iaccounts']) && isset($_POST['sstatus']) && isset($_POST['istatus'])){
		$saccounts=$_SESSION['saccounts'];$iaccounts=$_SESSION['iaccounts'];
		$istatus=$_POST['istatus'];$sstatus=$_POST['sstatus'];
		$count=-1;
		foreach($iaccounts as $iaccount){
			$count++;
			if($istatus[$count]==='active'){
				$temp=1;
			}else{$temp=0;}
			$err=updateField("instructors","active",$temp,"email='".$iaccount[3]."'");
			if($err){
				echo '<p id="error"> failed to update instructor active status for'.$iaccount[3].'</p>';
			}
		}
		$count=-1;
		foreach($saccounts as $saccount){
			$count++;
			if($sstatus[$count]=='active'){
				$temp=1;
			}else{$temp=0;}
			$err=updateField("students","active",$temp,"email='".$saccount[3]."'");
			if($err){
				echo '<p id="error"> failed to update student active status for'.$saccount[3].'</p>';
			}
		}
	}

	//convience items on how much and what to display
	$status=array("Active","Pending","All");
	$bool_stmt=array("active=1","active=0","1=1");
	if(isset($_POST['active'])){
		$active=$_POST['active'];
	}else{
		$active=1;
	}
	if(isset($_POST['limit'])){
		$limit=$_POST['limit'];
	}else{
		$limit=20;
	}
	$saccounts=get("Student_ID,first_name,last_name,email,active","students",$bool_stmt[$active]," limit ".$limit);
	$iaccounts=get("Instructor_ID,first_name,last_name,email,active","instructors",$bool_stmt[$active]," limit ".$limit);
?>
<body>
<form style="text-align:left;display:inline;" action='<?php echo $_SESSION['return_path']?>'><input type="submit" value="home"></form>
<form style="float:right;display:inline;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>
<h1 style="text-align:center"><?php echo $status[$active] ?> Accounts</h1>

<!-- The whole page is essentially a form so that each account can be activated or deactivated -->
<form action='manage_accounts.php' method='post'>
	<p style="float:right;"><input type='radio' id="radio" name='active' value=0 <?php if($active==0)echo "checked";?>> active
	<input type='radio' id="radio" name='active' value=1 <?php if($active==1)echo "checked";?>> inactive 
	<input type='radio' id="radio" name='active' value=2 <?php if($active==2)echo "checked";?>> both
	<input type='submit' value="update form"></p>
	Show: <input style="dislay:inline;" type='number' id="limit" name='limit' value=<?php echo "$limit"; ?>>

<!-- This section is for instructor accounts -->
<p style="text-align:center;">
<h2> Instructors: </h2>
<table>
	<tr><th>ID</th><th>Name</th><th>Email</th><th>Active</th></tr>
	<?php
		$_SESSION['saccounts']=$saccounts;
		$_SESSION['iaccounts']=$iaccounts;
		echo '<input type="hidden" name="istatus[0]" value="nonactive">';
		for($i=0;$i<count($iaccounts);$i++){
			echo '<tr><td>'.$iaccounts[$i][0].'</td><td>'.$iaccounts[$i][1].' '.$iaccounts[$i][2].'</td><td>'.$iaccounts[$i][3].'</td><td>';

			if($iaccounts[$i][4]){
				echo '<input type="checkbox" name="istatus['.$i.']" value="active" checked>';
			}else{
				echo '<input type="checkbox" name="istatus['.$i.']" value="active">';
			}
			echo '</td></tr>';
		}
	?>
</table>

<!-- This section is for student accounts -->
<h2> Students: </h2>
<table>
	<tr><th>ID</th><th>Name</th><th>Email</th><th>Active</th></tr>
	<?php
		echo '<input type="hidden" name="sstatus[0]" value="nonactive">';
		for($i=0;$i<count($saccounts);$i++){
			echo '<tr><td>'.$saccounts[$i][0].'</td><td>'.$saccounts[$i][1].' '.$saccounts[$i][2].'</td><td>'.$saccounts[$i][3].'</td><td>';
			if($saccounts[$i][4]){
				echo '<input type="checkbox" name="sstatus['.$i.']" value="active" checked>';
			}else{
				echo '<input type="checkbox" name="sstatus['.$i.']" value="active">';
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
