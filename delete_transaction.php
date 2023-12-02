<?php


if (isset($_GET['id'])) {
    $transaction_id = $_GET['id'];

    include 'db_connect.php';

    try {
        $stmt = $conn->prepare("DELETE FROM transactions WHERE transaction_id = ?");
        $stmt->execute([$transaction_id]);
        header("Location: transactions.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Transaction ID not provided.";
}
?>
