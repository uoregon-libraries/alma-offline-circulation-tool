<?php
	include_once('include/variables.php');
?>
<!doctype html>
<html lang="en">
	<head>
		<!--UO Libraries Offline Circulation Tool
			Designed by Lara Nesselroad,
			Coded by Spencer Bellerby,
			Reviewed by Duncan Barth, Jeremy Echols, and Tyler Stewart
			-->
		
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		
		<title><?php echo $MAIN_TITLE; if(isset($page_title)) echo " - $page_title"; ?></title>
		
		<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
		<link href="http://fonts.googleapis.com/css?family=Oxygen+Mono" rel="stylesheet" type="text/css" />
		<link href="styles/style.css" media="all" rel="stylesheet" type="text/css" />
		<?php if(isset($scripts)) echo "<script defer language=\"javascript\" src=\"scripts/$scripts\" type=\"text/javascript\"></script>"; else echo "\n" ?>
	</head>
	
	<body>
		<div id="document">
			<h1><?php echo $MAIN_TITLE; if(isset($page_title)) echo " - $page_title"; ?></h1>
