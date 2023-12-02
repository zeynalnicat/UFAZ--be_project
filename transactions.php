<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Transaction Data</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Transaction Data</h1>
    <form action="" method="GET">
        <label for="search">Search:</label>
        <input type="text" id="search" name="search" style="width: 100%">
        <button type="submit">Search</button>
    </form>
    <div class="transaction">
        <table>
            <tr>
                <th>Transaction ID</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Category</th>
                <th>Payment Method</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php
            include 'db_connect.php';

            try {
                $search = isset($_GET['search']) ? $_GET['search'] : '';

                $stmt = $conn->prepare("SELECT transaction_id, amount, transaction_date, category_name, method_name 
                                        FROM transactions 
                                        INNER JOIN categories ON transactions.category_id = categories.category_id 
                                        INNER JOIN payment_methods ON transactions.payment_method_id = payment_methods.payment_method_id
                                        WHERE transaction_id LIKE :search OR 
                                              amount LIKE :search OR 
                                              transaction_date LIKE :search OR 
                                              category_name LIKE :search OR 
                                              method_name LIKE :search");
                $stmt->bindValue(':search', '%' . $search . '%');
                $stmt->execute();
                $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($transactions as $transaction) {
                    echo "<tr>";
                    echo "<td>" . $transaction['transaction_id'] . "</td>";
                    echo "<td>" . $transaction['amount'] . "</td>";
                    echo "<td>" . $transaction['transaction_date'] . "</td>";
                    echo "<td>" . $transaction['category_name'] . "</td>";
                    echo "<td>" . $transaction['method_name'] . "</td>";
                    echo "<td><a href='edit_transaction.php?id=" . $transaction['transaction_id'] . "'><button>Edit</button></a></td>"; // Edit link
                    echo "<td><a href='delete_transaction.php?id=" . $transaction['transaction_id'] . "'><button>Delete</button></a></td>"; // Delete link
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </table>
    </div>
</body>

</html>