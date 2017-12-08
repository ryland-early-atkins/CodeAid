<!--
teambasic
wesley,ryland,dominic,luke
11/28/17
-->
<?php

function progSimple($studentid,$objectiveid){
	//track max points possible
	$maxpoints=0;
	//amount of max points needed to achieve mastery
	$SCALE_FACTOR=0.75;
	
	//gets questions associated with this objective
	$questions=get("Question_ID,points,max_attempts,solution","questions","Objective_ID=".$objectiveid,"");
	$score=0;

	//goes through each question associated with the objective
	foreach($questions as $question){
		$maxpoints+=$question[1];
		//gets the attempts asscoiated with a student and question
		$attempts=get("attempt,answer","student_attempts","Question_ID=".$question[0]." AND Student_ID=".$studentid,"");
		if($attempts && !empty($attempts)){
			foreach($attempts as $attempt){
				if(verifySolution($question[3],$attempt[1])){
					$score+=questionScore($question[1],count($attempts),$question[2]);
					break;
				}
			}
		}
	}
	
	$maxpoints=$SCALE_FACTOR*$maxpoints;
	if($maxpoints==0){
		$score="";
	}else{
		$score=($score/$maxpoints)*100;
		if($score>100){$score=100;}
	}
	return round($score,2);
}

//helper function to calculate the score of a question based on attempts
function questionScore($points,$attempts,$max_attempts){
	$val=$points-$attempts*($points/($max_attempts));
	return $val;
}
function verifySolution($correctAns,$ans){
	$correctAns=strtolower(trim($correctAns));
	$ans=strtolower(trim($ans));
	return $correctAns==$ans;
	
}
?>
