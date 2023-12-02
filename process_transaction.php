<?php
include 'db_connect.php';

$amount = $_POST['amount'];
$date = $_POST['date'];
$category = $_POST['category'];
$paymentMethod = $_POST['payment-method'];

try {
    $stmt = $conn->prepare("INSERT INTO transactions (amount, transaction_date, category_id, payment_method_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$amount, $date, $category, $paymentMethod]); 

    echo "Succesfully added!";
    header("Location: transactions.php");
    exit();
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
