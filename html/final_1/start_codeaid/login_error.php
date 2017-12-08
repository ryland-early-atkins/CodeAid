<?php
	include '../html_beginning.php';
	title('CodeAid login');
	$sheets=array("../CSS_sheets/login_style.css");
	styles($sheets);

?>
<body>
	<br>
	<h1>Welcome to CodeAid!</h1>
	<form action="login_check.php" method="post">
		<br>
		Username:
		<input type="text" name="username"><br>
		Password:
		<input type="password" name="password"><br>
		<input type="submit" value="Submit"><br>
	</form>
	<p><a href="create_account.php" id="create_account"> Create Account </a></p>
	<p style="color:red;">**invalid entry</p>
</body>
</html>
