 <!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is for instructors to create a course
-->   
<?php 
	//include several of the main helper function files
	include '../function_files.php';

        session_start();

	//redirects to login if invalid session
	testInstructor($_SESSION['id'],$instructor_id);

	$instructor_id=$_SESSION['userid'];

	//travel buttons 
	echo '<form style="float:right;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>';
	echo '<form style="float:left;" action="instructor_create_class.php"><input type="submit" value="back"></form>';
	echo '<form style="text-align:left;" action="instructor_home.php"><input type="submit" value="home"></form>';
	
	title("create course");
	$sheets=array('../CSS_sheets/instructor_home.css');
	styles($sheets);
 ?>
        <h1>Create a new course</h1>
        
        <!-- CREATE MYSQLI-->
        <?php
            $mysqli = new mysqli("localhost","teambasic","visualbasic","CodeAid");
            if ($mysqli->connect_errno) { // Check connection
                echo "Failed to connect to MySQL: ".$mysqli->connect_errno;  }
           
        ?> 
        
        <!-- FORM TO CREATE COURSE -->
        <form action="instructors/instructor_create_course.php" method="post">
            Enter course department:<input type="text" name="department_option" value="CSC">  
            <br/>
            Enter course number : <input type="number" name="coursenum_option" value=117>
            <br/>
            Enter course name: <input type="text" name="name_option" value="Name of the course">
            <br/>
            <input type="submit" value="Create This Course" name="submit">
        </form>
        
        
        
        
        
        <!-- RELOAD PAGE -->
        <?php
            if(isset($_POST['submit']))
                {
                    
                    
                    $department_option = $_POST['department_option'];
                    $coursenum_option = $_POST['coursenum_option'];
                    $name_option = $_POST['name_option'];
                    
                    
                    
                    $sql = "INSERT INTO courses (department,course_number,name)
                            VALUES
                                ('$department_option', $coursenum_option,'$name_option');
                                "   ;
                    if (!$result = $mysqli->query($sql)) {
                        echo "Error creating new course";
                        exit;
                    }
                    echo "Successfully created new course";
                    
                }
        ?>
        
        
        
        
        
    </body>
</html>
