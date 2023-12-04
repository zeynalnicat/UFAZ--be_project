<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['transaction_id'])) {
        $transaction_id = $_POST['transaction_id'];
        $amount = $_POST['amount'];
        $date = $_POST['date'];
        $category_id = $_POST['category'];
        $payment_method_id = $_POST['payment-method'];

        try {
            $stmt = $conn->prepare("UPDATE transactions 
                                    SET amount = ?, transaction_date = ?, category_id = ?, payment_method_id = ? 
                                    WHERE transaction_id = ?");
            $stmt->execute([$amount, $date, $category_id, $payment_method_id, $transaction_id]);

            if ($stmt->rowCount() > 0) {
                header("Location: transactions.php");
                exit();
            } else {
                echo "No rows affected - Update failed.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Transaction ID not provided for update.";
    }
} else {
    if (isset($_GET['id'])) {
        $transaction_id = $_GET['id'];

        try {
            $stmt = $conn->prepare("SELECT * FROM transactions WHERE transaction_id = ?");
            $stmt->execute([$transaction_id]);
            $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($transaction) {
                $stmt_categories = $conn->prepare("SELECT category_id, category_name FROM categories");
                $stmt_categories->execute();
                $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

                $stmt_payment_methods = $conn->prepare("SELECT payment_method_id, method_name FROM payment_methods");
                $stmt_payment_methods->execute();
                $paymentMethods = $stmt_payment_methods->fetchAll(PDO::FETCH_ASSOC);

                ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction Details</title>
    <link rel="stylesheet" href="style.css">

    <style>
    _ nav.navigation {
        background-color: #333;
        color: white;
        padding: 10px;
    }

    nav.navigation a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
    }

    nav.navigation a:hover {
        color: #ffd700;
    }
    </style>
</head>

<body>
    <nav class="navigation">
        <a href="index.php">Home</a> &gt; Transaction Data
    </nav>
    <h1>Edit Transaction Details</h1>
    <form action="edit_transaction.php" method="post">
        <input type="hidden" name="transaction_id" value="<?php echo $transaction_id; ?>">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" value="<?php echo $transaction['amount']; ?>" required>
        <label for="date">Date of Transaction:</label>
        <input type="date" id="date" name="date" value="<?php echo $transaction['transaction_date']; ?>" required>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="">Select Category</option>
            <?php
                            foreach ($categories as $category) {
                                $selected = $transaction['category_id'] == $category['category_id'] ? 'selected' : '';
                                echo "<option value='" . $category['category_id'] . "' $selected>" . $category['category_name'] . "</option>";
                            }
                            ?>
        </select>

        <label for="payment-method">Payment Method:</label>
        <select id="payment-method" name="payment-method" required>
            <option value="">Select Payment Method</option>
            <?php
                            foreach ($paymentMethods as $method) {
                                $selected = $transaction['payment_method_id'] == $method['payment_method_id'] ? 'selected' : '';
                                echo "<option value='" . $method['payment_method_id'] . "' $selected>" . $method['method_name'] . "</option>";
                            }
                            ?>
        </select>

        <button type="submit">Save Changes</button>
    </form>
</body>

</html>
<?php
            } else {
                echo "Transaction not found.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Transaction ID not provided.";
    }
}
?>