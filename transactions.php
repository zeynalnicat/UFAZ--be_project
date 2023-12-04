<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Data</title>
    <link rel="stylesheet" href="style.css">
    <style>
    _ nav.navigation {
        background-color: #333;
        color: white;
        padding: 10px;
    }   

    nav.navigation a ,p  {
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
    <div style="margin-left:10px ; display: flex; justify-content: space-between; align-items:center ; gap:10px">
            <a href="index.php">Home</a> <p> &gt; Transaction Data</p> 
        </div>
    </nav>
    <h1>Transaction Data</h1>
    <div class="form-container" style="margin-top:50px;">
        <form action="" method="GET">
            <label for="search">Search:</label>
            <input  style="width: 100%;" type="text" id="search" name="search">
            <button type="submit">Search</button>
        </form>

        <form action="" method="GET">
            <label for="date_filter">Filter by Date:</label>
            <input style="width: 100%;" type="date" id="date_filter" name="date_filter">
            <label for="category_filter">Filter by Category:</label>
            <input style="width: 100%;" type="text" id="category_filter" name="category_filter">
            <label for="payment_filter">Filter by Payment Method:</label>
            <input style="width: 100%;" type="text" id="payment_filter" name="payment_filter">
            <button  style="width: 100%;" type="submit">Apply Filters</button>
        </form>
    </div>
    <div class="transaction" style="padding-bottom: 40px;">
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

                
                $date_filter = isset($_GET['date_filter']) ? $_GET['date_filter'] : '';
                $category_filter = isset($_GET['category_filter']) ? $_GET['category_filter'] : '';
                $payment_filter = isset($_GET['payment_filter']) ? $_GET['payment_filter'] : '';

                $stmt = $conn->prepare("SELECT transaction_id, amount, transaction_date, category_name, method_name 
                                        FROM transactions 
                                        INNER JOIN categories ON transactions.category_id = categories.category_id 
                                        INNER JOIN payment_methods ON transactions.payment_method_id = payment_methods.payment_method_id
                                        WHERE (transaction_id LIKE :search OR 
                                               amount LIKE :search OR 
                                               transaction_date LIKE :search OR 
                                               category_name LIKE :search OR 
                                               method_name LIKE :search) AND 
                                              (transaction_date LIKE :date_filter AND 
                                               category_name LIKE :category_filter AND 
                                               method_name LIKE :payment_filter)");
                $stmt->bindValue(':search', '%' . $search . '%');
                $stmt->bindValue(':date_filter', '%' . $date_filter . '%');
                $stmt->bindValue(':category_filter', '%' . $category_filter . '%');
                $stmt->bindValue(':payment_filter', '%' . $payment_filter . '%');
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