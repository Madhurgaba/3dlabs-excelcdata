<?php
 
ini_set("display_errors",1);
include 'excel_reader2.php';
require_once 'config.php';
?>
<form action="action.php" enctype="multipart/form-data"  method="post">
<input type ="file" name="file">
<input type="submit" name="submit" value="submit">
</form>