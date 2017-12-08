#teambasic
#ryland,wesley,luke,dominic
#11/14/17
#this file initializes database tables and administrators
#by default deletion rules are restrict.

#first_name,last_name,email,password are not the field names specified in design document.
#these field names show up in two tables: students and instructors.
#the table they are contained in can be used to uniquly identify them rather than use a unique field name

#this section wipes any preexisting information
DROP DATABASE CodeAid;
CREATE DATABASE CodeAid;
USE CodeAid;

#this table represents the users who will be using the database to learn
CREATE TABLE students(
	Student_ID		INT			NOT NULL 	
	,first_name		varchar(80)		NOT NULL
	,last_name		varchar(80)		NOT NULL
	,email			varchar(320)		NOT NULL
	,password		varchar(128)		NOT NULL
	,PRIMARY KEY(Student_ID)
	,UNIQUE KEY(email)
);

#this table represents the users who will be using the database to track student progress
CREATE TABLE instructors(
	Instructor_ID 		INT			NOT NULL	
	,first_name		varchar(80)		NOT NULL
	,last_name		varchar(80)		NOT NULL
	,email			varchar(320)		NOT NULL
	,password		varchar(128)		NOT NULL
	,PRIMARY KEY(Instructor_ID)
	,UNIQUE KEY(email)
    
);

#contain materials that are common from class to class each year
CREATE TABLE courses(
	Course_ID 		INT			NOT NULL	AUTO_INCREMENT
	,department		char(3)			NOT NULL
	,course_number		INT			NOT NULL	CHECK (course_number > 000 AND course_number <999)
	,name			varchar(80)		
	,PRIMARY KEY(Course_ID)
	,UNIQUE KEY(department,course_number)
    
);

#instance of courses that includes more specific information unique to every class
#Instructor_ID is required. this was not specified in the design document.
CREATE TABLE classes(
	Class_ID		INT			NOT NULL	AUTO_INCREMENT
	,Course_ID		INT			NOT NULL
	,semester		char(10)		NOT NULL
	,year			INT			NOT NULL
	,section		char(1)			NOT NULL
	,Instructor_ID		INT			NOT NULL     
	,PRIMARY KEY(Class_ID)
	,FOREIGN KEY(Course_ID) REFERENCES courses(Course_ID)
	,FOREIGN KEY(Instructor_ID) REFERENCES instructors(Instructor_ID)
	,UNIQUE KEY(Course_ID,semester,year,section)
    
);

#tracks what students are enrolled in what classes
#cascade deletion rule is used so that all students are removed from a class if the class is deleted
CREATE TABLE student_classes(
	Class_ID		INT			NOT NULL
	,Student_ID		INT			NOT NULL
	,PRIMARY KEY(Class_ID,Student_ID)
	,FOREIGN KEY(Class_ID) REFERENCES classes(Class_ID) ON DELETE CASCADE
	,FOREIGN KEY(Student_ID) REFERENCES students(Student_ID)
    
);

#this table contains skills students will need to complete for given courses
#active field was changed to type boolean rather than ENUM as specified in the design 
CREATE TABLE objectives(
	Objective_ID		INT			NOT NULL	AUTO_INCREMENT
	,active			BOOLEAN			NOT NULL	DEFAULT 1		
	,name			varchar(80)		NOT NULL
	,description		TEXT
	,Course_ID		INT			NOT NULL
	,PRIMARY KEY(Objective_ID)
	,FOREIGN KEY(Course_ID) REFERENCES courses(Course_ID)
    
);

#contains material to aid students on application exercises 
CREATE TABLE supplementary_material(
	Material_ID		INT			NOT NULL	AUTO_INCREMENT
	,name			varchar(400)		NOT NULL
	,info			TEXT			NOT NULL
	,Objective_ID		INT			NOT NULL
	,PRIMARY KEY(Material_ID)
	,FOREIGN KEY(Objective_ID) REFERENCES objectives(Objective_ID)
    
);

#keeps track of exercises available to students on the application
#active field was changed to type boolean rather than ENUM as specified in the design 
CREATE TABLE questions(
	Question_ID		INT			NOT NULL	AUTO_INCREMENT
	,Objective_ID		INT			NOT NULL
	,active			BOOLEAN			NOT NULL	DEFAULT 1
	,question		TEXT			NOT NULL
	,solution		TEXT			NOT NULL
	,points			INT			NOT NULL
	,max_attempts		INT			NOT NULL
	,PRIMARY KEY(Question_ID)
	,FOREIGN KEY(Objective_ID) REFERENCES objectives(Objective_ID)
    
);

#keeps track of student progress in solving problems
CREATE TABLE student_attempts(
	attempt			INT			NOT NULL
	,Question_ID		INT			NOT NULL
	,Student_ID		INT			NOT NULL
	,answer			TEXT			NOT NULL
	,PRIMARY KEY(attempt,Question_ID,Student_ID)
	,FOREIGN KEY(Question_ID) REFERENCES questions(Question_ID)
	,FOREIGN KEY(Student_ID) REFERENCES students(Student_ID)
    
);

#this provides admins
INSERT INTO instructors
values(1,'team','basic','wesley.murray@centre.edu','visualbasic'),
(2,'team','basic','dominic.peluso@centre.edu','visualbasic'),
(3,'team','basic','minhduc.nguyen@centre.edu','visualbasic'),
(4,'team','basic','ryland.early.atkins@gmail.com','visualbasic');
