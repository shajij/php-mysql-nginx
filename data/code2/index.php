<html>
 <head>
  <title>PHP App1</title>
 </head>
 <body>
 <?php echo '<p>App 1 connected to myDB</p>'; 

?> 

<?php
$servername = "mysql.default.svc.cluster.local";
$username = "root";
$password = "password";

$mysqli = new mysqli($servername, $username, $password, 'myDB2');

$sql = "SELECT * FROM places";
if (!$result = $mysqli->query($sql)) {
    // Oh no! The query failed. 
    echo "Sorry, the website is experiencing problems.";

    // Again, do not do this on a public site, but we'll show you how
    // to get the error information
    echo "Error: Our query failed to execute and here is why: \n";
    echo "Query: " . $sql . "\n";
    echo "Errno: " . $mysqli->errno . "\n";
    echo "Error: " . $mysqli->error . "\n";
    exit;
}

$actor = $result->fetch_assoc();
echo "<ul>\n";
while ($actor = $result->fetch_assoc()) {
    echo "<li>";
    echo $actor['name'] . ' ' . $actor['zip'];
    echo "</li>\n";
}
echo "</ul>\n";

$result->free();
$mysqli->close();

?>

 </body>
</html>
