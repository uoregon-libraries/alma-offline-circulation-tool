<?php
  // Including the configuration file from the variables file reduces include statements in the rest of the code because literally every page needs this at some point in time.

  include_once('include/configuration.php');

  // This array is used to fill in the first row of the spreadsheet, and can also be useful for keeping track of the order of items in the database.

  $FIRST_ROW = array(
    'Library',
    'Date/Time',
    'Return or Loan',
    'Item Barcode',
    'Item Call Number',
    'Patron Barcode',
    'Patron Name'
  );

  // This database connection is used extensively enough to warrant just putting it here for easy access.

  $DB = new mysqli($DB_SERV, $DB_USER, $DB_PASS, $DB_NAME);

  // These are strings with which to prepare statements for the database tasks that we expect need to be performed during regular usage.

  $DB_INSERT_STRING = "INSERT INTO $DB_TABL VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
  $DB_SELECT_STRING = "SELECT * FROM $DB_TABL WHERE saved = 0 AND library LIKE ?";
  $DB_SAVED_STRING = "UPDATE $DB_TABL SET saved=1 WHERE datetime = ? AND itmbarcd = ?";
  $DB_DELETE_STRING = "DELETE QUICK FROM $DB_TABL WHERE saved = 1 AND datetime < ?";
?>
