<?php
	// I found this function online. It looks to be some regular expression matching and conditionals to append to an array that gets returned.
	
	function parse_csv ($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true)
	{
		return array_map(
			function ($line) use ($delimiter, $trim_fields) {
				return array_map(
					function ($field) {
						return str_replace('!!Q!!', '"', urldecode($field));
					},
					$trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line)
				);
			},
			preg_split(
				$skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s',
				preg_replace_callback(
					'/"(.*?)"/s',
					function ($field) {
						return urlencode($field[1]);
					},
					$enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string)
				)
			)
		);
	}
?>
