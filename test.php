<?php
echo "PHP is working!";
echo "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'];
echo "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'];
echo "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'];
phpinfo();
?>
