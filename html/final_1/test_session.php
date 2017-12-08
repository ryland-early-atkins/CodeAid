<!--
teambasic
wesley,ryland,luke,dominic
11/25/17
this file is for verifying session ids
-->

<?php

function testInstructor($id,$userid){
	if(!$id || empty($id) || !$userid || empty(userid)){
		reloc("../end_codeaid/logout.php");
	}
	$exists=get("Instructor_ID","instructors","Instructor_ID=$userid","");
	if($exists[0][0]!=$userid || !password_verify($userid,$id)){
		reloc("../end_codeaid/logout.php");
	}
}
function testStudent($id,$userid){
	$exists=get("Student_ID","students","Student_ID=$userid","");
	if($exists[0][0]!=$userid || !password_verify($userid,$id)){
		reloc("../end_codeaid/logout.php");
	}
}
?>
