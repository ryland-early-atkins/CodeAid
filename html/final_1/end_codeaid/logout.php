<!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is purely for logging out
-->

<?php
	session_start();
	if(ini_get("session.use_cookies")){
		$params=session_get_cookie_params();
		setcookie(session_name(),'',time()-4200,$params["path"],$params["domain"],$params["secure"],$params["httponly"]);
	}
	session_destroy();
	include '../redirect.php';
	reloc('../start_codeaid/login.php');
?> 
