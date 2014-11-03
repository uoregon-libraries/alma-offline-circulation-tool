<?php
	/*ini_set('display_errors',1); 
	error_reporting(E_ALL);*/
	
	$page_title = 'Deleting Old Records';
	//$scripts = 'clearold.js';
	include_once('include/page_begin.php');
?>
			<p>Upon choosing a date and selecting &quot;Clear Records/Files&quot;, you will be taken to a page that will either report errors with the process or delete all saved database records and stored files before (but not on) the date you selected. If you browse to the <a href="deleteold.php">Delete Old Records page</a> without specifying a date, it will simply assume you meant to delete everything older than 3 months.</p>
			
			<form action="deleteold.php" id="mainform" method="POST" name="mainform">
				<label for="datetime">Date (<em>yyyy-mm-dd</em>)</label>
				<input form="mainform" id="datetime" name="datetime" type="date" />
				<input class="middlesubmit" type="submit" value="Clear Records/Files" />
			</form>
<?php
	include_once('include/page_end.php');
?>
