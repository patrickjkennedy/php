<?php 

require "dbconfig.php";
include "templates/header.php";

echo "<h2>Payments</h2>";

$dropdown = "";

$dropdown .= "<p>Fetch number of rows:</p><form method=\"post\"><select name=\"rows\"><option value=\"20\" ";

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

$dropdown .= ">60</option></select><input type=\"submit\"><br/><br/></form>";

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
	echo "<table border='1'>";
	echo "<tr>";
	echo "<th>Check Number</th><th>Payment Date</th><th>Payment Amount</th><th>Customer Number</th>";
	echo "</tr>";
    while($row = $result->fetch_assoc()) {
		$rw = "<tr>";
        $rw .= "<td>" . $row["checkNumber"] . "</td>";
		$rw .= "<td>" . $row["paymentDate"] . "</td>";
		$rw .= "<td>" . $row["amount"] . "</td>";
		$rw .= "<td><form method=\"post\">";
		$rw .= "<input name=\"customerNumber\" type=\"submit\" value=\"" . $row["customerNumber"] . "\"></form></td></tr>";
		echo $rw;
    }
	echo "</table>";
} else {
    echo "No results found.";
}

$conn->close();

if (isset($_POST['customerNumber'])){
	$customerNumber = $_POST['customerNumber'];
	echo "<h2>Customer ($customerNumber) Details</h2>";
	// Create connection
	$conn = new mysqli($host, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);	
	}
	
	$sql = "SELECT customers.phone, employees.firstName, employees.lastName, customers.creditLimit, SUM(payments.amount) 
			FROM employees, customers, payments 
			WHERE payments.customerNumber = customers.customerNumber 
			AND employees.employeeNumber = customers.salesRepEmployeeNumber
			AND payments.customerNumber = $customerNumber";
	
	$result = $conn->query($sql);
		
	if ($result->num_rows > 0) {
    // output data of each row
	echo "<table border='1'>";
	echo "<tr>";
	echo "<th>Customer Phone Number</th><th>Sales Rep</th><th>Credit Limit</th><th>Sum of Payments</th>";
	echo "</tr>";
    while($row = $result->fetch_assoc()) {
		$rw = "<tr>";
        $rw .= "<td>" . $row["phone"] . "</td>";
		$rw .= "<td>" . $row["firstName"] . " " . $row["lastName"] . "</td>";
		$rw .= "<td>" . $row["creditLimit"] . "</td>";
		$rw .= "<td>" . round($row["SUM(payments.amount)"], 2) . "</td></tr>";
		echo $rw;
    }
	echo "</table>";
	echo "<br/>";
} else {
    echo "No results found.";
}
	
	$sql = "SELECT payments.paymentDate, payments.checkNumber, payments.amount
			FROM payments 
			WHERE payments.customerNumber = $customerNumber
			ORDER BY payments.paymentDate DESC";
	
	$resultPayments = $conn->query($sql);
		
	if ($resultPayments->num_rows > 0) {
    // output data of each row
	echo "<table border='1'>";
	echo "<tr>";
	echo "<th>Payment Date</th><th>Check Number</th><th>Payments</th>";
	echo "</tr>";
    while($rowPayments = $resultPayments->fetch_assoc()) {
		echo "<tr>";
		echo "<td>" . $rowPayments["paymentDate"] . "</td>";
		echo "<td>" . $rowPayments["checkNumber"] . "</td>";
        echo "<td>" . round($rowPayments["amount"],2) . "</td></tr>";
    }
	echo "</table>";
} else {
    echo "No results found.";
}

$conn->close();

}

include "templates/footer.php";

?>