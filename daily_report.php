<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <title>Complaints Status Summary</title>
    <style>
    h1 {
        text-align: center;
    }

    table {
        margin: 20px auto;
        border-collapse: collapse;
    }

    table th, table td {
        padding: 8px;
        border: 1px solid black;
        text-align: center;
    }

    .navbar {
        background-color: #333;
        overflow: hidden;
        width: auto; /* Remove the fixed width to adjust automatically */
    }

    .navbar a {
        color: white;
        text-align: center;
        padding: 14px 10px; /* Adjust padding for top/bottom and left/right */
        text-decoration: none;
        margin-right: 20px;
        margin-left: 20px;
    }

    .navbar a:hover {
        background-color: #ddd;
        color: black;
    }

    .navbar a.activeLink {
        background-color: yellow;
        color: black;
    }

    p {
        text-align: center;
        color: red;
    }

    #complaintsChart,
    #localitiesChart {
        max-width: 500px;
        width: 100%;
        margin: 20px; /* Add margin for spacing */
    }

    .chart-container {
    display: flex;
    flex-wrap: wrap; /* Allow charts to wrap to the next line on small screens */
    margin: 10px; /* Add margin for spacing */
    margin-bottom: 20px;
}

</style>

</head>
<body>
<div>
    <nav class="navbar">
        <a href="officerDashboard.php" ><i class="fas fa-briefcase"></i> Complaints</a>
        <a href="daily_report.php" class="activeLink"><i class="fas fa-chart-line"></i> Daily Report</a>
        <a href="homepageviewforofficer.php"><i class="fas fa-home"></i> Homepage</a>
        <a href="officerlogouthandler.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
</div>
<!--link to daily report pdf download-->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <a href="generate_report.php" class="btn btn-primary">Download PDF</a>
        </div>
    </div>
</div>



<h1>Complaints Status Summary</h1>


<div class="chart-container" style="position: relative; height:40vh; width:80vw;">
        <canvas id="complaintsChart"></canvas>
        <canvas id="localitiesChart"></canvas>
</div>

<table style="margin-top: auto; font-size: small;" >
    <tr>
        <th>Total Complaints</th>
        <th>Received</th>
        <th>Pending</th>
        <th>Underway</th>
        <th>Resolved</th>
    </tr>
    <tr>
    <?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'isp';

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    session_name("officer_session");
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: officerlogin.html");
        exit();
    }

    // this code will query the database and get the total number of complaints based on their status
    $sql = "SELECT 
        (SELECT COUNT(*) FROM complaints) AS total_complaints,
        (SELECT COUNT(*) FROM complaints WHERE status = 'Received') AS received_complaints,
        (SELECT COUNT(*) FROM complaints WHERE status = 'Pending') AS pending_complaints,
        (SELECT COUNT(*) FROM complaints WHERE status = 'Underway') AS underway_complaints,
        (SELECT COUNT(*) FROM complaints WHERE status = 'Resolved') AS resolved_complaints,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Westlands') AS westlands,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Eastleigh') AS eastleigh,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Kasarani') AS kasarani,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Embakasi') AS embakasi,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Githurai') AS githurai,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Karen') AS karen,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Kibera') AS kibera,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Kileleshwa') AS kileleshwa,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Kilimani') AS kilimani,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Langata') AS langata,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Lavington') AS lavington,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Madaraka') AS madaraka,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Mathare') AS mathare,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Mihango') AS mihango,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Mlolongo') AS mlolongo,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Mombasa Road') AS mombasa_road,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Muthaiga') AS muthaiga,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Ngong') AS ngong,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Ngundu') AS ngundu,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Nairobi West') AS nairobi_west,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Ongata Rongai') AS ongata_rongai,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Parklands') AS parklands,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Rongai') AS rongai,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Ruai') AS ruai,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Runda') AS runda,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Ruaka') AS ruaka,
        (SELECT COUNT(*) FROM complaints WHERE city = 'South B') AS south_b,
        (SELECT COUNT(*) FROM complaints WHERE city = 'South C') AS south_c,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Thika Road') AS thika_road,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Upper Hill') AS upper_hill,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Waiyaki Way') AS waiyaki_way,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Westlands') AS westlands,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Eastleigh') AS eastleigh,
        (SELECT COUNT(*) FROM complaints WHERE city = 'Kasarani') AS kasarani";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $totalComplaints = $row['total_complaints'];
        $receivedComplaints = $row['received_complaints'];
        $pendingComplaints = $row['pending_complaints'];
        $underwayComplaints = $row['underway_complaints'];
        $resolvedComplaints = $row['resolved_complaints'];
        $westlands = $row['westlands'];
        $eastleigh = $row['eastleigh'];
        $kasarani = $row['kasarani'];
        $embakasi = $row['embakasi'];
        $githurai = $row['githurai'];
        $karen = $row['karen'];
        $kibera = $row['kibera'];
        $kileleshwa = $row['kileleshwa'];
        $kilimani = $row['kilimani'];
        $langata = $row['langata'];
        $lavington = $row['lavington'];
        $madaraka = $row['madaraka'];
        $mathare = $row['mathare'];
        $mihango = $row['mihango'];
        $mlolongo = $row['mlolongo'];
        $mombasa_road = $row['mombasa_road'];
        $muthaiga = $row['muthaiga'];
        $ngong = $row['ngong'];
        $ngundu = $row['ngundu'];
        $nairobi_west = $row['nairobi_west'];
        $ongata_rongai = $row['ongata_rongai'];
        $parklands = $row['parklands'];
        $rongai = $row['rongai'];
        $ruai = $row['ruai'];
        $runda = $row['runda'];
        $ruaka = $row['ruaka'];
        $south_b = $row['south_b'];
        $south_c = $row['south_c'];
        $thika_road = $row['thika_road'];
        $upper_hill = $row['upper_hill'];
        $waiyaki_way = $row['waiyaki_way'];

        echo "<td>" . $totalComplaints . "</td>";
        echo "<td>" . $receivedComplaints . "</td>";
        echo "<td>" . $pendingComplaints . "</td>";
        echo "<td>" . $underwayComplaints . "</td>";
        echo "<td>" . $resolvedComplaints . "</td>";
        
    } else {
        echo "<td colspan='5'>No complaints found.</td>";
    }

    // Fetch data for localities
$sqlLocalities = "SELECT city, COUNT(*) AS city_count FROM complaints GROUP BY city";
$resultLocalities = mysqli_query($conn, $sqlLocalities);

$localities = [];
while ($rowLocality = mysqli_fetch_assoc($resultLocalities)) {
    $localities[$rowLocality['city']] = $rowLocality['city_count'];
}
    ?>
    </tr>
</table>

<table style="font-size: small;">
    <tr>
        <th>Westlands</th>
        <td><?php echo $westlands; ?></td>
    </tr>
    <tr>
        <th>Eastleigh</th>
        <td><?php echo $eastleigh; ?></td>
    </tr>
    <tr>
        <th>Kasarani</th>
        <td><?php echo $kasarani; ?></td>
    </tr>
    <tr>
        <th>Embakasi</th>
        <td><?php echo $embakasi; ?></td>
    </tr>
    <tr>
        <th>Githurai</th>
        <td><?php echo $githurai; ?></td>
    </tr>
    <tr>
        <th>Karen</th>
        <td><?php echo $karen; ?></td>
    </tr>
    <tr>
        <th>Kibera</th>
        <td><?php echo $kibera; ?></td>
    </tr>
    <tr>
        <th>Kileleshwa</th>
        <td><?php echo $kileleshwa; ?></td>
    </tr>
    <tr>
        <th>Kilimani</th>
        <td><?php echo $kilimani; ?></td>
    </tr>
    <tr>
        <th>Langata</th>
        <td><?php echo $langata; ?></td>
    </tr>
    <tr>
        <th>Lavington</th>
        <td><?php echo $lavington; ?></td>
    </tr>
    <tr>
        <th>Madaraka</th>
        <td><?php echo $madaraka; ?></td>
    </tr>
    <tr>
        <th>Mathare</th>
        <td><?php echo $mathare; ?></td>
    </tr>
    <tr>
        <th>Mihango</th>
        <td><?php echo $mihango; ?></td>
    </tr>
    <tr>
        <th>Mlolongo</th>
        <td><?php echo $mlolongo; ?></td>
    </tr>
    <tr>
        <th>Mombasa Road</th>
        <td><?php echo $mombasa_road; ?></td>
    </tr>
    <tr>
        <th>Muthaiga</th>
        <td><?php echo $muthaiga; ?></td>
    </tr>
    <tr>
        <th>Ngong</th>
        <td><?php echo $ngong; ?></td>
    </tr>
    <tr>
        <th>Ngundu</th>
        <td><?php echo $ngundu; ?></td>
    </tr>
    <tr>
        <th>Nairobi West</th>
        <td><?php echo $nairobi_west; ?></td>
    </tr>
    <tr>
        <th>Ongata Rongai</th>
        <td><?php echo $ongata_rongai; ?></td>
    </tr>
    <tr>
        <th>Parklands</th>
        <td><?php echo $parklands; ?></td>
    </tr>
    <tr>
        <th>Rongai</th>
        <td><?php echo $rongai; ?></td>
    </tr>
    <tr>
        <th>Ruai</th>
        <td><?php echo $ruai; ?></td>
    </tr>
    <tr>
        <th>Runda</th>
        <td><?php echo $runda; ?></td>
    </tr>
    <tr>
        <th>Ruaka</th>
        <td><?php echo $ruaka; ?></td>
    </tr>
    <tr>
        <th>South B</th>
        <td><?php echo $south_b; ?></td>
    </tr>
    <tr>
        <th>South C</th>
        <td><?php echo $south_c; ?></td>
    </tr>
    <tr>
        <th>Thika Road</th>
        <td><?php echo $thika_road; ?></td>
    </tr>
    <tr>
        <th>Upper Hill</th>
        <td><?php echo $upper_hill; ?></td>
    </tr>
    <tr>
        <th>Waiyaki Way</th>
        <td><?php echo $waiyaki_way; ?></td>
    </tr>
</table>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        var labels = ['Received', 'Pending', 'Underway', 'Resolved'];
        var data = [<?php echo $receivedComplaints; ?>, <?php echo $pendingComplaints; ?>, <?php echo $underwayComplaints; ?>, <?php echo $resolvedComplaints; ?>];

        var ctx = document.getElementById('complaintsChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Complaints Status',
                    data: data,
                    backgroundColor: ['rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)', 'rgba(75, 192, 192, 0.6)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)'],
                    borderWidth: 1
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });
    </script>

<script>
    var localitiesLabels = <?php echo json_encode(array_keys($localities)); ?>;
    var localitiesData = <?php echo json_encode(array_values($localities)); ?>;
    
    // Define an array of 16 different distinct colors for the pie chart
    var localityColors = [
        'rgba(255, 99, 132, 0.6)',
        'rgba(54, 162, 235, 0.6)',
        'rgba(255, 206, 86, 0.6)',
        'rgba(75, 192, 192, 0.6)',
        'rgba(255, 159, 64, 0.6)',
        'rgba(153, 102, 255, 0.6)',
        'rgba(255, 0, 0, 0.6)',
        'rgba(0, 255, 0, 0.6)',
        'rgba(0, 0, 255, 0.6)',
        'rgba(128, 0, 128, 0.6)',
        'rgba(255, 255, 0, 0.6)',
        'rgba(0, 255, 255, 0.6)',
        'rgba(255, 0, 255, 0.6)',
        'rgba(128, 128, 128, 0.6)',
        'rgba(0, 128, 128, 0.6)',
        'rgba(128, 0, 0, 0.6)',
    ];

    // Function to shuffle the colors to ensure no duplicates
    function shuffle(array) {
        var currentIndex = array.length, randomIndex;

        // While there remain elements to shuffle...
        while (currentIndex !== 0) {

            // Pick a remaining element...
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex--;

            // And swap it with the current element.
            [array[currentIndex], array[randomIndex]] = [
                array[randomIndex], array[currentIndex]];
        }

        return array;
    }

    // Shuffle the colors to ensure no duplicates
    localityColors = shuffle(localityColors);

    var ctxLoc = document.getElementById('localitiesChart').getContext('2d');
    var myChartLoc = new Chart(ctxLoc, {
        type: 'pie',
        data: {
            labels: localitiesLabels,
            datasets: [{
                data: localitiesData,
                backgroundColor: localityColors,
                borderColor: 'rgba(255, 255, 255, 1)', // Border color for legend
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: true,
                position: 'right',
                align: 'start',
                labels: {
                    boxWidth: 20,
                    padding: 10
                }
            }
        }
    });
</script>


</body>
</html>
