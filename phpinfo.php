<?php
// Turn on error reporting
error_reporting(E_ALL);

// Display errors on the web page
ini_set('display_errors', 1);
$xml = new SimpleXMLElement('<root><test>Hello World</test></root>');
echo $xml->test;
?>
