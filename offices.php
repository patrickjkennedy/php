<?php 

require "dbconfig.php";
include "templates/header.php";

echo "<h2>Offices</h2>";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT officeCode, city, phone, addressLine1, addressLine2, state, country, postalCode FROM offices";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	echo "<table border='1'>";
	echo "<tr>";
	echo "<th>City</th><th>Address</th><th>Phone Number</th><th>Employees</th>";
	echo "</tr>";
    while($row = $result->fetch_assoc()) {
		$rw = "<tr>";
        $rw .= "<td>" . $row["city"] . "</td><td>";
		
		if (!empty($row["addressLine2"])) {
			$rw .= $row["addressLine2"] . ", ";
		}
		
		$rw .= $row["addressLine1"] . ", " . $row["city"] . ", ";
		
		if (!empty($row["state"])) {
			$rw .= $row["state"] . ", ";
		}
		$rw .= $row["country"] . "</td>" . "<td>" . $row["phone"] . "</td>";
		$rw .= "<td><form method=\"post\">";
		$rw .= "<input name=\"city\" type=\"hidden\" value=\"" . $row["city"] . "\">";
		$rw .= "<input name=\"officeCode\" type=\"hidden\" value=\"" . $row["officeCode"] . "\">";
	    $rw .= "<input type=\"submit\" value=\"Get Employees\"></form></td></tr>";
		echo $rw;
    }
	echo "</table>";
} else {
    echo "No results found.";
}

$conn->close();

if (isset($_POST['officeCode'])){
	$officeCode = $_POST['officeCode'];
	$city = $_POST['city'];
	echo "<h2>$city Employee Details</h2>";
	// Create connection
	$conn = new mysqli($host, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);	
	}
	
	$sql = "SELECT employees.firstName, employees.lastName, employees.jobTitle, employees.employeeNumber, employees.email 
			FROM offices, employees 
			WHERE offices.officeCode = employees.officeCode
			AND offices.officeCode = $officeCode
			ORDER BY employees.jobTitle";
	$result = $conn->query($sql);


if ($result->num_rows > 0){
	// output data of each row
	echo "<table border='1'>";
	echo "<tr>";
	echo "<th>Full Name</th><th>Job Title</th><th>Employee Number</th><th>Email</th>";
	echo "</tr>";
	while($row = $result->fetch_assoc()) {
		$rw = "<tr>";
		$rw .= "<td>" . $row["firstName"] . " " . $row["lastName"] . "</td><td>";
		$rw .= $row["jobTitle"] . "</td><td>";
		$rw .= $row["employeeNumber"] . "</td><td>";
		$rw .= $row["email"] . "</td></tr>";
		echo $rw;
	}
} else {
	echo "No results found.";
}

$conn->close();
}
include "templates/footer.php";

?>