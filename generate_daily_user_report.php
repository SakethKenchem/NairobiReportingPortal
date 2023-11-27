<?php
session_name("admin_session");
session_start();

if (!isset($_SESSION["admin_authenticated"]) || $_SESSION["admin_authenticated"] !== true) {
    header("Location: admin_login_signup.php");
    exit();
}

require('fpdf.php');

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'isp';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$queryTotalUsers = "SELECT COUNT(*) AS total_users FROM userlogincredentials";
$resultTotalUsers = $conn->query($queryTotalUsers);
$rowTotalUsers = $resultTotalUsers->fetch_assoc();
$totalUsers = $rowTotalUsers['total_users'];

$queryBlockedUsers = "SELECT COUNT(*) AS blocked_users FROM userlogincredentials WHERE is_blocked = 1";
$resultBlockedUsers = $conn->query($queryBlockedUsers);
$rowBlockedUsers = $resultBlockedUsers->fetch_assoc();
$blockedUsers = $rowBlockedUsers['blocked_users'];

$queryTotalPosts = "SELECT COUNT(*) AS total_posts FROM posts";
$resultTotalPosts = $conn->query($queryTotalPosts);
$rowTotalPosts = $resultTotalPosts->fetch_assoc();
$totalPosts = $rowTotalPosts['total_posts'];

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 12);

$pdf->Image('Coat_of_Arms_of_Nairobi.svg.png', 10, 10, 30);

$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 10, 'Nairobi Reporting Portal', 0, 1, 'C');
$pdf->Ln(10);
// Title
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(0, 10, 'Daily User & Posts Report', 0, 1, 'C');

date_default_timezone_set('Africa/Nairobi');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Date and Time generated: ' . date('Y-m-d H:i'), 0, 1, 'C');


$pdf->SetFont('Arial', 'B', 12);
$adminName = $_SESSION['username'];
$admin_data = isset($_SESSION['adminid']) ? $_SESSION['adminid'] : "";
$pdf->Cell(0, 10, 'Downloaded by Admin: ' . $adminName . ', ID: ' . $admin_data, 0, 1, 'C');


$pdf->Ln(10);


$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Total Users', 1, 0, 'C');
$pdf->Cell(60, 10, 'Blocked Users', 1, 0, 'C');
$pdf->Cell(60, 10, 'Total Posts', 1, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(60, 10, $totalUsers, 1, 0, 'C');
$pdf->Cell(60, 10, $blockedUsers, 1, 0, 'C');
$pdf->Cell(60, 10, $totalPosts, 1, 1, 'C');

$pdf->Output('Daily User Report for Admin.pdf', 'I');

$conn->close();
?>
