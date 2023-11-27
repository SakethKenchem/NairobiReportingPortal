<?php
session_name("officer_session");
session_start();

require('fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('Coat_of_Arms_of_Nairobi.png', 5, 5, 25);

        $this->SetFont('Arial', 'B', 18);
        $this->Cell(0, 30, 'Nairobi Reporting Portal', 0, 1, 'C');
    }

    function Footer()
    {
        $this->SetY(-15);

        $this->SetFont('Arial', '', 10);
        $officerName = $_SESSION['username'];
        $officerId = isset($_SESSION['id']) ? $_SESSION['id'] : ""; 
        $this->Cell(0, 10, 'Agency Officer: ' . $officerName . ', ID: ' . $officerId, 0, 0, 'L'); 

        date_default_timezone_set('Africa/Nairobi');
        $this->Cell(0, 10, 'Date and Time: ' . date('Y-m-d H:i:s'), 0, 0, 'R');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'isp';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT 
        (SELECT COUNT(*) FROM complaints) AS total_complaints,
        (SELECT COUNT(*) FROM complaints WHERE status = 'Received') AS received_complaints,
        (SELECT COUNT(*) FROM complaints WHERE status = 'Pending') AS pending_complaints,
        (SELECT COUNT(*) FROM complaints WHERE status = 'Underway') AS underway_complaints,
        (SELECT COUNT(*) FROM complaints WHERE status = 'Resolved') AS resolved_complaints";

$result = mysqli_query($conn, $sql);
$pdf->Cell(0, 10, 'Complaints Summary', 0, 1, 'C');
$pdf->Ln(3);


$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Status', 1);
$pdf->Cell(30, 10, 'Count', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);

while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(60, 10, 'Total Complaints', 1);
    $pdf->Cell(30, 10, $row['total_complaints'], 1);
    $pdf->Ln();

    $pdf->Cell(60, 10, 'Received', 1);
    $pdf->Cell(30, 10, $row['received_complaints'], 1);
    $pdf->Ln();

    $pdf->Cell(60, 10, 'Pending', 1);
    $pdf->Cell(30, 10, $row['pending_complaints'], 1);
    $pdf->Ln();

    $pdf->Cell(60, 10, 'Underway', 1);
    $pdf->Cell(30, 10, $row['underway_complaints'], 1);
    $pdf->Ln();

    $pdf->Cell(60, 10, 'Resolved', 1);
    $pdf->Cell(30, 10, $row['resolved_complaints'], 1);
    $pdf->Ln();
}



$sql = "SELECT
        (SELECT COUNT(*) FROM complaints WHERE issue_type = 'Roads and Related Issues') AS roads_and_related_issues,
        (SELECT COUNT(*) FROM complaints WHERE issue_type = 'Water and Sanitation') AS water_and_sanitation,
        (SELECT COUNT(*) FROM complaints WHERE issue_type = 'Solid Waste Management and Garbage') AS solid_waste_management_and_garbage";
        
    $result = mysqli_query($conn, $sql);
    
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Issue Type Complaints Summary', 0, 1, 'B');
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 10, 'Issue Type', 1);
    $pdf->Cell(30, 10, 'Count', 1);
    $pdf->Ln();
    
    $pdf->SetFont('Arial', '', 12);

while($row = mysqli_fetch_assoc($result)){
    $pdf->Cell(60, 10, 'Roads and Related Issues', 1);
    $pdf->Cell(30, 10, $row['roads_and_related_issues'], 1);
    $pdf->Ln();

    $pdf->Cell(60, 10, 'Water and Sanitation', 1);
    $pdf->Cell(30, 10, $row['water_and_sanitation'], 1);
    $pdf->Ln();

    $pdf->Cell(60, 10, 'SWM & Garbage', 1);
    $pdf->Cell(30, 10, $row['solid_waste_management_and_garbage'], 1);
    $pdf->Ln();

}

$sqlCities = "SELECT DISTINCT city FROM complaints";
$citiesResult = mysqli_query($conn, $sqlCities);


$pdf->AddPage(); 
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Locality-wise Complaints Summary', 0, 1, 'B');


$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Locality Within Nairobi', 1);
$pdf->Cell(30, 10, 'Count', 1);
$pdf->Ln();


$pdf->SetFont('Arial', '', 12);


while ($cityRow = mysqli_fetch_assoc($citiesResult)) {
    $city = $cityRow['city'];
    $sqlCityCount = "SELECT COUNT(*) AS count FROM complaints WHERE city = '$city'";
    $cityCountResult = mysqli_query($conn, $sqlCityCount);
    $cityCount = mysqli_fetch_assoc($cityCountResult)['count'];

    $pdf->Cell(60, 10, $city, 1);
    $pdf->Cell(30, 10, $cityCount, 1);
    $pdf->Ln();
}

// Output the PDF
$pdf->Output('Complaints Summary Report.pdf', 'I'); 

mysqli_close($conn);
?>