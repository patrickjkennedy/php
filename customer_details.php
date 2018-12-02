<?php 

require "dbconfig.php";
include "templates/header.php";

if (isset($_POST['customerNumber'])){
	$customerNumber = $_POST['customerNumber'];
	echo "<h2>Customer #$customerNumber Details: </h2>";
	echo "<p><a href=\"payments.php\">Back to Payments</a></p>";
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
	echo "<table>";
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
	echo "<table>";
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