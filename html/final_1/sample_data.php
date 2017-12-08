<?php

/*
teambasic
wesley,luke,ryland,dominic
11/14/17
this code creates sample data to test the DB initialization file.
*/

//puts sample data into students and instructors

include 'sql_work/sql_conn.php';
$mysqli=connect();

$pass=password_hash("passpass", PASSWORD_DEFAULT);
$pass1=password_hash("visualbasic", PASSWORD_DEFAULT);

//puts sample data into students
$insert_students='INSERT INTO students (Student_ID,first_name,last_name,email,password,active)
					VALUES
					(312569,"Mickey","Mouse","mickey.mouse@centre.edu","'.$pass.'",1)
					,(326481,"James","Bund","james.bund@centre.edu","'.$pass.'",0) 
					,(374895,"Dude","Brotatoe","dude.brotatoe@centre.edu","'.$pass.'",0) 
					,(348172,"Maximus","Uranus","maximus.uranus@centre.edu","'.$pass.'",0)
					,(382159,"John","Doe","john.doe@centre.edu","'.$pass.'",0)
					,(341946,"Elmo","McElmoface","elmo.mcelmoface@centre.edu","'.$pass.'",0)
					,(347451,"John","Lemon","john.lemon@centre.edu","'.$pass.'",0)
					,(394265,"Mac","Miller","mac.miller@centre.edu","'.$pass.'",0)
					,(325861,"Martin","Jiang","martin.jiang@centre.edu","'.$pass.'",0)
					,(365184,"Jim","Jom","jim.jom@centre.edu","'.$pass.'",0);';

//puts sample data into instructors
$insert_instructors='INSERT INTO instructors (Instructor_ID,first_name,last_name,email,password,active)
					VALUES
					(333331,"Tom","Allen","tom.allen@centre.edu","'.$pass.'",1)
					,(333332,"Dave","Toth","dave.toth@centre.edu","'.$pass.'",1) 
					,(333333,"Mike","Bradshaw","mike.bradshaw@centre.edu","'.$pass.'",1) 
					,(1,"wesley","murray","wesley.murray@centre.edu","'.$pass1.'",1)
					,(2,"dominic","peluso","dominic.peluso@centre.edu","'.$pass1.'",1)
					,(3,"ryland","atkins","ryland.early.atkins@gmail.com","'.$pass1.'",1)
					,(4,"luke","nguyen","minhduc.nguyen@centre.edu","'.$pass1.'",1);';

function queryDB($mysqli,$stmt){
	//program passes parameter not user meaning no need to bind params
		if(!$query=$mysqli->prepare($stmt)){
			echo "prepare statement failed";
			echo $stmt;
			exit;
		}
		$query->execute();	
}

queryDB($mysqli,$insert_students);
queryDB($mysqli,$insert_instructors);

disconnect($mysqli);
?>

