<?php
  /*ini_set('display_errors',1);
  error_reporting(E_ALL);*/

  if (isset($_POST['library']))
    if ($_POST['library'] != '---')
      $library = $_POST['library'];
    else
      $library = '%';
  else
    $library = null;

  // variables.php holds $SELECTION_QUERY and $DELETE_QUERY. It also calls the database connection variables.

  include_once('include/variables.php');

  $db_select = $DB->prepare($DB_SELECT_STRING);
  $db_select->bind_param('s', $library);
  $db_select->execute();
  $db_select->store_result();

  // Using bind_result and fetch because get_result depends upon mysqlnd
  $db_select->bind_result($row['library'], $row['datetime'], $row['retorlo'], $row['itmbarcd'], $row['itmcalnm'], $row['ptrbarcd'], $row['ptrname'], $row['saved']);
  $db_select->fetch();

  // This line would (supposedly) work, but we do not have mysqlnd on our server
  //$results = $db_select->get_result();

  if($row['datetime'] != '')
  {
    // If the $library is null, we should not proceed to extract anything... The error for this check is broken though.

    if ($library)
    {
      // Either the library is '---' and we should grab everything, or it is set and we should use that information. The files should have branch information if applicable.

      $datetime = strftime('%F_%H%M%S');

      if ($library != '%')
      {
        $csv = fopen('output/' . $_POST['library'] . '_' . $datetime . '.csv', 'w');
        $dat = fopen('output/' . $_POST['library'] . '_' . $datetime . '.dat', 'w');
      }
      else
      {
        $csv = fopen('output/' . $datetime . '.csv', 'w');
        $dat = fopen('output/' . $datetime . '.dat', 'w');
      }

      if ($csv && $dat) // Everything depends upon the files opening, because if they are not open then trying to write to them is pointless.
      {
        do
        {
          $alma_string = substr($row['datetime'],0,12) . $row['retorlo'] . $row['itmbarcd'];
          $alma_string = str_pad($alma_string,93);  // YYYYMMDDHHmm is 12 characters, L/R is one more, then there are 80 characters for the barcode as a fixed width. If the barcode is not that long, spaces are added to the end of it until it is. 12 + 1 + 80 = 93.

          if ($row['ptrbarcd'] != '')
            $alma_string .= $row['ptrbarcd'];

          $alma_string .= "\r\n";

          if ($row['datetime'] != '')
          {
            fputcsv($csv, $row);
            fwrite($dat, $alma_string);

            $db_saved = $DB->prepare($DB_SAVED_STRING);
            $db_saved->bind_param('ss', $row['datetime'], $row['itmbarcd']);
            $db_saved->execute();
          }
        }
        while ($db_select->fetch());

        fclose($csv);
        fclose($dat);
      }
      else  // !$csv || !$dat
      {
        $errors = 'We had an error with opening the files, so they have not been written.';
      }
    }
    else  // $library == null
    {
      // For some reason, this error is broken.

      $errors = 'The \'library\' variable was never set.';
    }
  }

  $page_title = 'Extract Records';
  include_once('include/page_begin.php');
?>
      <p>This page should have just extracted all current records (verifiable on the <a href="listrecords.php">record-listing page</a>, which should now be empty). You can get to your files, listed from newest to oldest, at the page for <a href="listfiles.php">viewing previously generated files</a>.</p>
<?php
  if (isset($errors)) echo "<div class=\"errors\"><h2>Errors</h2><p>$errors</p></div>";

  include_once('include/page_end.php');
?>
