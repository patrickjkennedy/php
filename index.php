<?php 

require "dbconfig.php";
include "templates/header.php";

echo "<h2>Available Products</h2>";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT productLine, textDescription FROM productlines";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	echo "<table>";
	echo "<tr>";
	echo "<th>Product Line</th><th>Description</th>";
	echo "</tr>";
    while($row = $result->fetch_assoc()) {
		echo "<tr>";
        echo "<td>" . $row["productLine"] . "</td><td>" . $row["textDescription"] . "</td>";
		echo "</tr>";
    }
	echo "</table>";
	
} else {
    echo "No results found.";
}
$conn->close();

include "templates/footer.php";

?>
