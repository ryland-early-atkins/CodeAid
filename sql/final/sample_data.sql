#teambasic
#wesley,luke,ryland,dominic
#11/14/17
#this code creates sample data to test the DB initialization file.
    
#queries students to check data was inserted properly
SELECT Student_ID,first_name,last_name,email
FROM students
ORDER BY Student_ID;
    
#queries instructors to verify data was inserted properly
SELECT Instructor_ID,first_name,last_name,email
FROM instructors
ORDER BY Instructor_ID;
    
#puts sample data into courses
INSERT INTO courses (department,course_number,name)
VALUES
    ("CSC",117,"Introduction to Computer Science")
    ,("CSC",223,"Object-Oriented Computer Science")
    ,("CSC",300,"Software Development");


#queries courss to check data was inserted properly
SELECT Course_ID,department,course_number,name
FROM courses
ORDER BY Course_ID;

#puts sample data into classes
INSERT INTO classes (Course_ID,semester,year,section,Instructor_ID,active)
VALUES
    (1, "Fall",2017,'a',333331,1)
    ,(1, "Fall",2017,'b',333332,1)
    ,(2, "Spring",2018,'a',333333,1)
    ,(3, "Spring",2018,'a',333333,1);

#queries classes to check data was inserted properly
SELECT *
FROM classes
ORDER BY Class_ID;


#puts sample data for students enrolled in classes
INSERT INTO student_classes (Class_ID,Student_ID)
VALUES
    (1, 312569)
    ,(1, 326481)
    ,(1, 374895)
    ,(1,348172)
    ,(2,382159)
    ,(2,341946)
    ,(2,347451)
    ,(2,394265)
    ,(2,325861)
    ,(2,365184);
    
#more complex queries to make sure data distributes accross tables well
SELECT  students.first_name AS First_Name1
        ,students.last_name AS Last_Name1
        ,CONCAT(courses.department,courses.course_number,'-',classes.semester,classes.year,classes.section) AS Class
FROM student_classes NATURAL JOIN students NATURAL JOIN courses NATURAL JOIN classes;

#delete a class
delete from classes where Class_ID = 2;

#verifies that cascade deletion rule still applies
select * from student_classes;

#puts sample objectives into objectives
INSERT INTO objectives (active,name,description,Course_ID)
VALUES
    (1,"Python Syntax print","Write a simple Python script to print out 'hello world'",1)
    ,(1,"Python Syntax variable","Assign a value to a variable",1)
    ,(1,"Python Syntax array","Create an array",1)
    ,(1,"Python for loop","Use for loop to traverse through an array",1)    
    ,(1,"Python while loop","Use while loop to traverse through an array",1)
    ,(1,"Java Syntax print","Write a simple Java  script to print out 'hello world'",2)
    ,(1,"Java Syntax variable","Assign a value to a variable",2)
    ,(1,"Java Syntax array","Create an array",2)
    ,(1,"Java for loop","Use for loop to traverse through an array",2)    
    ,(1,"Java while loop","Use while loop to traverse through an array",2);
    

#more queries to verify relation between objectives and courses 
SELECT  CONCAT(courses.department,courses.course_number) as class
        ,Objective_ID    
        ,objectives.name AS Obj_Name
        ,description AS Description
FROM objectives, courses
WHERE objectives.Course_ID = courses.Course_ID;

#puts data into supplementary material
INSERT INTO supplementary_material (name, info, Objective_ID)
VALUES
        ("Learning Python, 5th Edition", "ISBN: 1449355730 - page 10-50",1)
        ,("Learning Python, 5th Edition", "ISBN: 1449355730 - page 89-70",2)
        ,("Codecademy Python", "link: http://codecademy.com/whatever",5)
        ,("Learning Java is Good", "link: http://javacool.org/for%loop",9)
        ,("Learning Java is Good", "link: http://javacool.org/while%loop",10);
        
#more queries to verify relation between objectives and supplementary_material       
SELECT  objectives.name
        ,supplementary_material.name
        ,supplementary_material.info
FROM objectives, supplementary_material
WHERE objectives.Objective_ID = supplementary_material.Objective_ID;



#puts sample data into questions
INSERT INTO questions(Objective_ID,active,question,solution,points,max_attempts)
VALUES
    (1,1,"Which line will print out 'hello,world'?: 
                A. printf(hello,world)
                ,,B. print('hello,world')
                ,,C. hello,world
                ,,D. output(hello,world)
                ,,E. out('hello,world')", "B", 2,4)
                
    ,(1,1,"What is love?: 
                A. baby don't hurt me
                ,,B. what isn't love?
                ,,C. hello,world
                ,,D. I don't know
                ,,E. all of the above", "A", 3,4)
    
    ,(1,1,"Is potato a fruit?: 
                A. yes
                ,,B. no
                ,,C. maybe
                ,,D. I don't know
                ,,E. can you repeat the question?", "E", 5,4)
                
    ,(1,1,"What year is it?:
                A. yes
                ,,B. no
                ", "A", 5,1)
                
    ,(2,1,"Blablabla?:
                A. bla1
                ,,B. bla2
                ,,C. bla3
                ,,D. bla4
                ", "C", 2,3)
                
    ,(2,1,"Blobliblo?:
                A. yes
                ,,B. no
                ", "A", 3,1);
                
                
#more queries to verify relation between objectives and questions                
SELECT objectives.name
        ,Question_ID
        ,solution
FROM objectives , questions
WHERE objectives.Objective_ID = questions.Objective_ID;


#put sample data of students attempting problems into student_attempts
INSERT INTO student_attempts(attempt,Question_ID,Student_ID,answer,time_stamp)
VALUES 
        (1, 1, 326481, "B",'2017-11-21 08:30:06')
        ,(1, 2, 326481, "A",'2017-10-21 12:45:27')
        ,(1, 3, 326481, "E",'2017-11-20 09:20:50')
	,(1, 4, 326481, "A",'2017-11-20 08:10:34')
        ,(1, 1,374895,"C",'2016-05-21 06:13:54')
        ,(2,1,374895,"B",'2017-11-30 08:30:57')
        ,(1,1,341946, "B",'2017-07-09 10:30:58')
        ,(1,2, 341946,"E",'2017-12-30 22:25:59')
        ,(2,2, 341946,"A",'2017-04-01 04:30:19')
        ,(1,3, 365184,"C",'2017-05-16 18:33:55')
        ,(2,3,365184,"D",'2017-03-18 16:54:43')
        ,(1,4,394265,"A",'2017-01-27 07:44:12');
        
#more queries to verify student_attempts properly joins students and questions
SELECT  students.first_name AS First_name
        ,students.last_name AS Last_name
        ,questions.Question_ID AS Question
        ,attempt
        ,answer
FROM students NATURAL JOIN student_attempts NATURAL JOIN questions
ORDER BY Question_ID;


