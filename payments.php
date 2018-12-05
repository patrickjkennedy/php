<?php 

require "dbconfig.php";
include "templates/header.php";

echo "<h2>Payments</h2>";

$dropdown = "";

$dropdown .= "<h3>Fetch number of rows:</h3><form method=\"post\"><select name=\"rows\"><option value=\"20\" ";

if(isset($_POST['rows']) && $_POST['rows']==20) {
	$dropdown .= "selected";
}

$dropdown .= ">20</option><option value=\"40\"";

if(isset($_POST['rows']) && $_POST['rows']==40) {
	$dropdown .= "selected";
}

$dropdown .= ">40</option><option value=\"60\"";

if(isset($_POST['rows']) && $_POST['rows']==60) {
	$dropdown .= "selected";
}

$dropdown .= ">60</option></select><br/><br/><input type=\"submit\"><br/><br/></form>";

echo $dropdown;

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['rows'])){
	$rows = $_POST['rows'];
	
} else{
	//Set rows to be 20 on page load
	$rows = 20;
}

$sql = "SELECT payments.checkNumber, payments.paymentDate, payments.amount, payments.customerNumber 
		FROM payments 
		LIMIT $rows";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	echo "<table>";
	echo "<tr>";
	echo "<th>Check Number</th><th>Payment Date</th><th>Payment Amount</th><th>Customer Number</th>";
	echo "</tr>";
    while($row = $result->fetch_assoc()) {
		$rw = "<tr>";
        $rw .= "<td>" . $row["checkNumber"] . "</td>";
		$rw .= "<td>" . $row["paymentDate"] . "</td>";
		$rw .= "<td>" . $row["amount"] . "</td>";
		$rw .= "<td><form method=\"post\" action=\"customer_details.php\">";
		$rw .= "<input name=\"customerNumber\" type=\"submit\" value=\"" . $row["customerNumber"] . "\"></form></td></tr>";
		echo $rw;
    }
	echo "</table>";
} else {
    echo "No results found.";
}

$conn->close();

include "templates/footer.php";

?>