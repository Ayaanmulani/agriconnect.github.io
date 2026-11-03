<?php
$conn = new mysqli('localhost', 'root', '', 'agri_connect');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$totalRevenue = 0;
$workers = [];


$sql = "SELECT farmer_aadhar, work_location, selected_date, start_time, end_time, total_cost, approval_time FROM done_work";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $wages = $row['total_cost'];
        $commission = $wages * 0.1;
        $revenue = $wages - $commission;
        $totalRevenue += $commission;

        $workers[] = [
            'worker_aadhar' => $row['farmer_aadhar'],
            'work_location' => $row['work_location'],
            'selected_date' => $row['selected_date'],
            'start_time' => $row['start_time'],
            'end_time' => $row['end_time'],
            'wages' => number_format($wages, 2),
            'commission' => number_format($commission, 2),
            'revenue' => number_format($revenue, 2),
            'approval_time' => $row['approval_time']
        ];
    }
}


$response = [
    'farmersCount' => $result->num_rows,
    'totalRevenue' => number_format($totalRevenue, 2),
    'workersCount' => count($workers),
    'workers' => $workers
];


$conn->close();


header('Content-Type: application/json');
echo json_encode($response);
?>
