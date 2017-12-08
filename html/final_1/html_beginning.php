<!DOCTYPE html>
<html>
<head>
	<?php
		function title($title){
			echo '<title>'.$title.'</title>';
		}
	?>
	<?php
		function styles($sheets){
			foreach($sheets as $sheet){
				echo '<link rel="stylesheet" href='.$sheet.'>';
			}
		}
	?>
</head>
