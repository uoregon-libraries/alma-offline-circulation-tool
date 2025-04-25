<?php
  $scripts = 'index.js';
  include_once('include/page_begin.php');
?>
      <form action="append.php" id="mainform" method="POST" name="mainform">
        <div id="users">
          <label class="exception" for="library">Library</label>
          <select form="mainform" id="library" name="library">
            <?php
              // This section just loops through $LIBRARIES_ARRAY from variables.php and creates an entry for each item in that array. If there is a $_POST['library'] then it is set to be the selected option, saving the current library across submits.

              include_once('include/variables.php');
              include_once('include/functions.php');

              foreach ($LIBRARIES_ARRAY as $lib => $library)
              {
                echo "<option value=\"$lib\"";
                if (isset($_POST['library']))
                  if ($lib == $_POST['library'])
                    echo ' selected';
                echo ">$library ($lib)</option>";
              }
            ?>
          </select><br />

          <div class="left">
            <h2>Check Out</h2>
            <label for="pbcode">Patron Primary ID</label><input id="pbcode" name="pbcode" type="text" /><br />
            <label for="pname">Patron Name</label><input id="pname" name="pname" type="text" /><br />
            <label for="ibcode">Item Barcode</label><input id="ibcode" name="ibcode" type="text" /><br />
            <label for="icallnum">Item Call Number</label><input id="icallnum" name="icallnum" type="text" /><br />
            <input class="button" id="checkoutbutton" name="checkoutbutton" type="button" value="Add Item" />
          </div>

          <div class="right">
            <h2>Check In</h2>
            <label for="ibcode2">Item Barcode</label><input id="ibcode2" name="ibcode2" type="text" /><br />
            <label for="icallnum2">Item Call Number</label><input id="icallnum2" name="icallnum2" type="text" /><br />
            <input class="button" id="checkinbutton" name="checkinbutton" type="button" value="Add Item" />
          </div>

          <div style="clear:both;"><input class="submit" type="submit" value="Submit Records" /></div>
        </div>

        <div class="debug">
          <h2>Technical Output</h2>
          <p>This technical output is a computer representation of records to be added to the database. Until you click submit, these records are temporary and will vanish if the browser is closed or crashes. Remember to submit regularly while we are offline.</p>
          <!--p style="font-size:small;">This field can safely be ignored by most users. It is for technical troubleshooting only.</p-->

          <label for="textarea" style="visibility:hidden; width:0 !important;">Text Area</label><textarea id="textarea" name="textarea" readonly rows="8" cols="80"></textarea></p>
        </div>
      </form>
<?php
  include_once('include/page_end.php');
?>
