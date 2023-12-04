<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking Transactions</title>
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
    <h1>Enter Transaction Details</h1>
    <form
        action="<?php echo isset($_GET['id']) ? 'edit_transaction.php?id=' . $_GET['id'] : 'process_transaction.php'; ?>"
        method="post">

        <?php
        include 'db_connect.php';
            

        if (isset($_GET['id'])) {
            $transaction_id = $_GET['id'];
            
            try {
                $stmt = $conn->prepare("SELECT * FROM transactions WHERE transaction_id = ?");
                $stmt->execute([$transaction_id]);
                $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($transaction) {
                    echo "<input type='hidden' name='transaction_id' value='" . $transaction_id . "'>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        
        ?>
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount"
            value="<?php echo isset($transaction) ? $transaction['amount'] : ''; ?>" required>

        <label for="date">Date of Transaction:</label>
        <input type="date" id="date" name="date"
            value="<?php echo isset($transaction) ? $transaction['transaction_date'] : ''; ?>" required>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="">Select Category</option>
            <?php
            try {
                $stmt = $conn->prepare("SELECT category_id, category_name FROM categories");
                $stmt->execute();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($categories as $category) {
                    $selected = isset($transaction) && $transaction['category_id'] == $category['category_id'] ? 'selected' : '';
                    echo "<option value='" . $category['category_id'] . "' $selected>" . $category['category_name'] . "</option>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </select>

        <label for="payment-method">Payment Method:</label>
        <select id="payment-method" name="payment-method" required>
            <option value="">Select Payment Method</option>
            <?php
            try {
                $stmt = $conn->prepare("SELECT payment_method_id, method_name FROM payment_methods");
                $stmt->execute();
                $paymentMethods = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($paymentMethods as $method) {
                    $selected = isset($transaction) && $transaction['payment_method_id'] == $method['payment_method_id'] ? 'selected' : '';
                    echo "<option value='" . $method['payment_method_id'] . "' $selected>" . $method['method_name'] . "</option>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </select>

        <button type="submit"><?php echo isset($_GET['id']) ? 'Save Changes' : 'Submit'; ?></button>
    </form>
</body>

</html>