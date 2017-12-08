<?php
	include '../html_beginning.php';
	title('create account');
	$sheets=array('../CSS_sheets/login_style.css','../CSS_sheets/error_messages.css');
	styles($sheets);

	function errorMessage($mess){
		echo '<p style="color: red">**'.$mess.'</p>';
	}
?>
	<body>
		<a href="login.php" style="text-align:left;">login_screen</a>
		<h1>Create Account</h1>
		<p>
		<form action='create_account_check.php' method='post'>
			<?php
				$labels=array("College ID:","First Name:","Last Name:","Email Address:","Password:","Verify Password:");
				$names=array("id","first_name","last_name","email","password","pass_verif");
				$types=array("number","text","text","text","password minlength='4'","password minlength='4'");
				$values=array("","","","","","");
				for($i=0;$i<count($names);$i++){
					echo $labels[$i];
					echo '<input type='.$types[$i].' name='.$names[$i].' value='.$values[$i].'><br>';
				}
			?>
			<input type="checkbox" name="user_type" value="student">I am an Instructor<br><br>
			<input type="submit">
		</form>
		</p>
	</body>
</html>
