<?php
// deployment_form.php
?>
<h2>Enter a New Deployment</h2>
<form action="index.php" method="POST">
    <label for="time">Time:</label>
    <input type="text" name="time" id="time" required><br>
    <label for="application">Application:</label>
    <input type="text" name="application" id="application" required><br>
    <label for="version">Version:</label>
    <input type="text" name="version" id="version" required><br>
    <label for="environment">Environment:</label>
    <input type="text" name="environment" id="environment" required><br>
    <input type="submit" value="Submit">
</form>