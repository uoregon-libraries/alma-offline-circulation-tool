<?php
  /*ini_set('display_errors',1);
  error_reporting(E_ALL);*/

  $page_title = 'List Current Records';
  include_once('include/page_begin.php');
?>
      <h2>Generate New Files</h2>

      <p>This button will extract the records from this page into *.csv and *.dat files, then flag them in the database. Please <strong>do not generate a new file</strong> if all you want to do is view <a href="listfiles.php" target="_blank">previously generated files</a> from an older date.</p>

      <p>Choosing a Library will cause the &quot;Generate Files&quot; button to only generate a file for records in that library. The default option of not choosing a library, &quot;Choose a Library (---)&quot;, will generate a file for all libraries.</p>

      <form action="extract.php" id="mainform" method="POST" name="mainform">
        <label for="library">Library</label>
        <select form="mainform" id="library" name="library">
          <?php
            // This section just loops through $LIBRARIES_ARRAY from variables.php and creates an entry for each item in that array. If there is a $_POST['library'] then it is set to be the selected option, saving the current library across submits.

            include_once('include/variables.php');

            foreach ($LIBRARIES_ARRAY as $lib => $library)
            {
              echo "\t\t\t\t\t\t<option value=\"$lib\">$library ($lib)</option>\n";
            }
          ?>
        </select><br />

        <input class="middlesubmit" type="submit" value="Generate Files" />
      </form>

      <h2>Current Unsaved Data</h2>

      <table>
        <thead>
          <tr><th>Library</th><th>Date</th><th>Return/Loan</th><th>Item Barcode</th><th>Item Call Number</th><th>Patron Barcode</th><th>Patron Name</th></tr>
        </thead>

        <tbody>
          <?php
            // variables.php is included because it has $SELECTION_QUERY and because it calls the database connection variables in as well.

            include_once('include/variables.php');

            $library = '%';

            $db_select = $DB->prepare($DB_SELECT_STRING);
            $db_select->bind_param('s', $library);
            $db_select->execute();
            $db_select->store_result();

            // Using bind_result and fetch because get_result depends upon mysqlnd
            $db_select->bind_result($row['library'], $row['datetime'], $row['retorlo'], $row['itmbarcd'], $row['itmcalnm'], $row['ptrbarcd'], $row['ptrname'], $row['saved']);

            // This line would (supposedly) work, but we do not have mysqlnd on our server
            //$results = $db_select->get_result();

            // This while loop generates a table row for each row in the database, creating table cells that either show the value in the cell or are blank and borderless to indicate no record in that particular cell.

            while ($db_select->fetch())
            {
              echo '<tr>';

              foreach ($row as $key => $item)
              {

                if ($key != 'saved')
                {
                  if ($item != '')
                    if ($key == 'itmbarcd' || $key == 'itmcalnm' || $key == 'ptrbarcd')
                      echo '<td class="mono">' . $item . '</td>';
                    else
                      echo '<td>' . $item . '</td>';
                  else
                    echo '<td style="border:none;"></td>';
                }
              }

              echo "</tr>\n\t\t\t\t";

              /*var_dump($row);
              echo '<hr />';*/
            }
          ?>
        </tbody>
      </table>
<?php
  include_once('include/page_end.php');
?>
