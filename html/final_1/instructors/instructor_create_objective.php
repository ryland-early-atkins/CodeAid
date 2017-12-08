 <!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is for instructors to create an objective
-->   
<?php 
        //include several of the main helper function files
	include '../function_files.php';

        session_start();

	//redirects to login if invalid session
	testInstructor($_SESSION['id'],$_SESSION['userid']);

	$instructor_id=$_SESSION['userid'];
	$class_option = $_SESSION['class_id'];

	//travel buttons
	echo '<form style="float:right;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>';
	echo '<form style="float:left;" action="instructor_manage_class_objective.php"><input type="submit" value="back"></form>';
	echo '<form style="text-align:left;" action="instructor_home.php"><input type="submit" value="home"></form>';
	
	//include basic intro html stuff
	title("create objective");
	$sheets=array('../CSS_sheets/instructor_home.css');
	styles($sheets);
 ?>
        <h1>Create a new Objective</h1>
        
        <!-- CREATE MYSQLI-->
        <?php
            $mysqli = new mysqli("localhost","teambasic","visualbasic","CodeAid");
            if ($mysqli->connect_errno) { // Check connection
                echo "Failed to connect to MySQL: ".$mysqli->connect_errno;  }
           
        ?> 
        
        
        <!--get class id-->
        <?php
            //get course ID
            $sql = "SELECT courses.Course_ID
                    FROM courses,classes
                    WHERE courses.Course_ID = classes.Course_ID AND Class_ID=$class_option;" ;
            if (!$result = $mysqli->query($sql)) {
                echo "Error: Our query failed to execute and here is why: \n";
                echo "Query: " . $sql . "\n";
                echo "Errno: " . $mysqli->errno . "\n";
                echo "Error: " . $mysqli->error . "\n";
                exit;
            }
            $row = $result->fetch_assoc();
            $course_option = (int) $row[Course_ID];
            //echo "course ID is  ".$course_option;
        
        ?>
        
        <!-- FORM TO CREATE FORM -->
        <form action="instructor_create_objective.php" method="post">
            Enter objective name:<input type="text" name="objective_name_option" value="Objective Name">  
            <br/>
            Enter objective description : <textarea name="objective_description_option" cols="40" rows="5"></textarea>
            
            
            <br/>
            <input type="submit" cols="40" rows="5" value="Create This Objective" name="submit">
        </form>
        
        
        
        
        
        <!-- RELOAD PAGE -->
        <?php
            if(isset($_POST['submit']))
                {
                    
                    
                    $objective_name_option = $_POST['objective_name_option'];
                    $objective_description_option = $_POST['objective_description_option'];
                                    
                    $sql = "INSERT INTO objectives (active,name,description,Course_ID)
                            VALUES
                                (1,'$objective_name_option', '$objective_description_option',$course_option);
                                "   ;
                    if (!$result = $mysqli->query($sql)) {
                        echo "Error creating new objective";
                        exit;
                    }
                    echo "Successfully created new objective";
                    
                }
        ?>
        
        
        
        
        
    </body>
</html>
