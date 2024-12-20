<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    try {
        $sql = "UPDATE network_issues SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['status' => $status, 'id' => $id]);
        header('Location: technician_view.php');
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
