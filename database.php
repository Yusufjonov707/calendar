<?php
// Include the database class file

require_once 'path/to/database.php';

use Database\database;

// Create a new instance of the database class
$db = new database();

// Use the $db object to execute SQL queries
$sql = "SELECT * FROM your_table";
$result = $db->sql($sql);

// Check if there was an error executing the query
if ($result['error']) {
    echo "Error: " . $result['message'];
} else {
    // Access the query results
    
}
?>