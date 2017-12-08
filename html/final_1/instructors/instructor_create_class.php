<!-- 
teambasic
wesley,ryland,luke,dominic
11/25/17
this page is for instructors to create a class
-->   
<?php 
	//include several of the main helper function files
	include '../function_files.php';

        session_start();

	//verifies this is a valid session
	testInstructor($_SESSION['id'],$_SESSION['userid']);

	$instructor_id=$_SESSION['userid'];


	//travel buttons on all pages
	echo '<form style="float:right;" action="../end_codeaid/logout.php"><input type="submit" value="logout"></form>';
	echo '<form style="float:left;" action="instructor_manage_classes.php"><input type="submit" value="back"></form>';
	echo '<form style="text-align:left;" action="instructor_home.php"><input type="submit" value="home"></form>';

	title("class creation");
	$sheets=array('../CSS_sheets/instructor_home.css');
	styles($sheets);
 ?>
    <body>
        <h1>Create a new class</h1>
        
        <!-- CREATE MYSQLI CONNECTION -->
        <?php
		$mysqli=connect();
        ?>
        
        <!-- FORM -->
        <form action="instructor_create_class.php" method="post">
            Select a course:
            <select name="course_option">             
                
                <!-- COURSES OPTIONS-->
                <?php 
                    $sql = "SELECT CONCAT(courses.department,courses.course_number) AS course_name
                    ,Course_ID
                            FROM courses;";
                    
                    if (!$result = $mysqli->query($sql)) {
                        echo "Sorry, the website is experiencing problems.";
                        exit;
                    }
                    
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="'.$row[Course_ID].'">'.$row[course_name].'</option>';
                    }
                    
                
                ?>
                
            </select>
            <a href = "instructor_create_course.php">Create a new course</a>
            <br/>
            Class semester: 
            <select name="semester_option">             
                <option value="Fall">Fall</option>
                <option value="Spring">Spring</option>
                <option value="Winter">Winter</option>
             </select>
            
            <br/>
            Class year: <input type="number" name="year_option" value=2017>
            
            <br/>
            Class section: <input type="text" name="section_option" value="a">
            
            <br/>
            <input type="submit" value="Create This Class" name="submit">
        </form>
        
         <!-- RELOAD PAGE -->
        <?php
            if(isset($_POST['submit']))
                {
                    
                    
                    $course_option = $_POST['course_option'];
                    $semester_option = $_POST['semester_option'];
                    $year_option = $_POST['year_option'];
                    $section_option = $_POST['section_option'];
                    
                    
                    $sql = "INSERT INTO classes (Course_ID,semester,year,section,Instructor_ID)
                            VALUES
                                ($course_option, '$semester_option',$year_option,'$section_option',$instructor_id );
                                "   ;
                    if (!$result = $mysqli->query($sql)) {
                        echo "Error creating new class";
                        exit;
                    }
                    echo "Successfully created new class";
                    
                }
        ?>
        
        <?php
            disconnect($mysqli);
	?>
        
        
    </body>
</html>
