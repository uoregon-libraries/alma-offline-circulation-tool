<?php
	/*ini_set('display_errors',1); 
	error_reporting(E_ALL);*/
	
	// variables.php holds $INSERT_QUERY and points to another file which pulls the database name and credentials. Meanwhile, functions.php has a parse_csv() function that I found online which interprets csv values in a string.
	
	include_once('include/variables.php');
	include_once('include/functions.php');
	
	//$db = mysqli_connect($DB_SERV, $DB_USER, $DB_PASS, $DB_NAME);
	
	// These str_replace() commands are a weak prevention mechanism against SQL attacks, but also prevent bugs with names like "O'Hara".
	
	$textarea = str_replace('\'','',$_POST['textarea']);
	$textarea = str_replace('"','',$textarea);
	
	$csv = parse_csv($textarea);	// This function is in includes.php, and turns a textarea into a 2D array interpreted as csv
	
	foreach ($csv as $line)	// For each line in the csv values
	{
		if ($line[0] != '')	// If the line is not blank
		{
			/*$tempquery = $INSERT_QUERY . "('{$line[0]}', '{$line[1]}', '{$line[2]}', '{$line[3]}', '{$line[4]}', '{$line[5]}', '{$line[6]}', 0)";	// Basically, insert all the values in the order we care about them
			
			mysqli_query($db, $tempquery);*/
			
			$db_insert = $DB->prepare($DB_INSERT_STRING);
			$db_insert->bind_param('sssssss', $line[0], $line[1], $line[2], $line[3], $line[4], $line[5], $line[6]);
			$db_insert->execute();
		}
	}
	
	$page_title = "Add Records for {$LIBRARIES_ARRAY[$_POST['library']]}";
	include_once('include/page_begin.php');
?>			
			<form action="index.php" id="mainform" method="POST" name="mainform">
				<label for="library">Library</label><input id="library" name="library" readonly type="text" value="<?php echo $_POST['library']; ?>" /><br />
				<input class="submit" type="submit" value="Return" />
			</form>
<?php
	include_once('include/page_end.php');
?>
