<?php

include('db_connect.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $worker_id = $_POST['worker_id'];
    $work_id = $_POST['work_id'];

    
    $query = "SELECT * FROM worker_requests WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $work_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $work_details = $result->fetch_assoc();

    
    if ($work_details) {
       
        $insert_query = "INSERT INTO assigned_work (farmer_aadhar, location, work, wages, commission, status) 
                         VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);

        $farmer_aadhar = $work_details['farmer_aadhar']; 
        $location = $work_details['work_location'];      
        $work = $work_details['work_details'];           
        $wages = $work_details['total_cost'];            
        $commission = 0.1 * $wages;                      
        $status = 'accepted';                            

        $stmt->bind_param("ssssds", $farmer_aadhar, $location, $work, $wages, $commission, $status);

        if ($stmt->execute()) {
            
            $update_query = "UPDATE worker_requests SET status = 'accepted' WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("i", $work_id);
            $stmt->execute();

            echo "Work accepted successfully.";
        } else {
            echo "Error accepting work.";
        }
    } else {
        echo "Work request not found.";
    }

    $stmt->close();
    $conn->close();
}
?>
