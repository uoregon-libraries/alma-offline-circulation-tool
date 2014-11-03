<?php
	$page_title = 'List Generated Files';
	include_once('include/page_begin.php');
?>
			<p><a href="listrecords.php">Return to List Records</a></p>
			
			<ul>
				<?php
					// Basically, this list shows all the files except '.' and '..' in './output' sorted descending by name (the names are all timestamps, so in effect sorted from most to least recent). './output' needs to at least be readable to the rest of the world for this to make sense.
					
					$DIR = './output';
					$DIR_CONTENTS = array_diff(scandir($DIR), array('..','.'));
					rsort($DIR_CONTENTS);
					
					foreach($DIR_CONTENTS as $item)
					{
						$path = "output/$item";
						$filesize = filesize($path);
						echo "<li><a href=\"$path\">$item</a> - $filesize Bytes</li>";
					}
				?>
			</ul>
<?php
	include_once('include/page_end.php');
?>
