<!DOCTYPE html>
<html lang='en'>
	<head>
		<meta charset='UTF-8'>
		<title>CodeAid</title>
	</head>
	<body>
		<center>
		<img src='CodeAid_Logo.png' alt='CodeAid'>
        <?php
            include '../sql_work/sql_conn.php';
	    $mysqli=connect();
        ?>
        <!--Query to create dropdown for Courses-->
        <?php
            $sql = "SELECT CONCAT(department,course_number,section) AS enrolled_in FROM classes NATURAL JOIN courses;";
            if (!$result = $mysqli->query($sql)) {
                // Oh no! The query failed. 
                echo "Sorry, the website is experiencing problems.";
            }   
        ?>
        
        
	<?php
	    echo "</br>";
	    echo "<select name='Student_Courses'>";
	    while ($row = $result->fetch_assoc()){
		    echo "<option value='".$row['enrolled_in']."'>".$row['enrolled_in']."</option>";
	    }
	    echo "</select>";
        ?>
        <!--Query to create dropdown for Objectives-->
        <?php
            $sql = "SELECT objectives.name AS objs FROM objectives NATURAL LEFT OUTER JOIN courses;";
            if (!$result = $mysqli->query($sql)) {
                // Oh no! The query failed. 
                echo "Sorry, the website is experiencing problems.";
            }   
	?>

	<?php
	    echo "</br>";
	    echo "<select name='Student_Objectives'>";
	    while ($row = $result->fetch_assoc()){
		    echo "<option value='".$row['objs']."'>".$row['objs']."</option>";
	    }
	    echo "</select>";
	?>
	<?php
	    echo "<form action='problem_page.php' method='post'>";
	    echo "<input type='submit' name='newquiz' value='New Quiz'>";
	    echo "</form>";
	?>
        
	<?php 
	/*
	    echo "<form action='oldQuiz.php' method='post'>";
	    echo "<input type='submit' name='newquiz' value='Old Questions'>";
	    echo "</form>";
	*/
	?>
	
        <?php
            disconnect($mysqli);
        ?>
		</center>
	</body>
</html>



