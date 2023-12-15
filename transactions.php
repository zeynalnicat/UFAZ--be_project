<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Data</title>
    <link rel="stylesheet" href="style.css">
    <style>
        nav.navigation {
            background-color: #333;
            color: white;
            padding: 10px;
        }

        nav.navigation a,
        p {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        nav.navigation a:hover {
            color: #ffd700;
        }

        .total-amount {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <nav class="navigation">
        <div style="margin-left: 10px; display: flex; justify-content: space-between; align-items: center; gap: 10px">
            <a href="index.php">Home</a>
            <p> &gt; Transaction Data</p>
        </div>
    </nav>
    <h1>Transaction Data</h1>
    <div class="form-container" style="margin-top: 50px;">
        <form action="" method="GET">
            <label for="search">Search:</label>
            <input style="width: 100%;" type="text" id="search" name="search">
            <button type="submit">Search</button>
        </form>

        <form action="" method="GET">
            <label for="date_filter">Filter by Date:</label>
            <input style="width: 100%;" type="date" id="date_filter" name="date_filter">
            <label for="category_filter">Filter by Category:</label>
            <select style="width: 100%;" id="category_filter" name="category_filter">
                <option value="">Select Category</option>
                <?php
                include 'db_connect.php';

                $stmt = $conn->prepare("SELECT category_name FROM categories");
                $stmt->execute();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($categories as $category) {
                    echo "<option value='" . $category['category_name'] . "'>" . $category['category_name'] . "</option>";
                }
                ?>
            </select>
            <label for="payment_filter">Filter by Payment Method:</label>
            <select style="width: 100%;" id="payment_filter" name="payment_filter">
                <option value="">Select Payment Method</option>
                <?php
                $stmt = $conn->prepare("SELECT method_name FROM payment_methods");
                $stmt->execute();
                $paymentMethods = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($paymentMethods as $method) {
                    echo "<option value='" . $method['method_name'] . "'>" . $method['method_name'] . "</option>";
                }
                ?>
            </select>
            <button style="width: 100%;" type="submit">Apply Filters</button>
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
                <th>Account Type</th>
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
                $accountTotals = [
                    'Credit' => 0,
                    'Debit' => 0,
                    'Other' => 0
                ];

                foreach ($transactions as $transaction) {
                    echo "<tr>";
                    echo "<td>" . $transaction['transaction_id'] . "</td>";
                    echo "<td>" . $transaction['amount'] . "</td>";
                    echo "<td>" . $transaction['transaction_date'] . "</td>";
                    echo "<td>" . $transaction['category_name'] . "</td>";
                    echo "<td>" . $transaction['method_name'] . "</td>";


                    $category = $transaction['category_name'];
                    switch ($category) {
                        case 'Utilities':
                        case 'Groceries':
                        case 'Food':
                        case 'Shopping':
                        case 'Entertainment':
                            $creditDebitType = 'Debit';
                            break;
                        case 'Salary':
                        case 'Savings':
                        case 'Bonuses':
                        case "Gifts":
                            $creditDebitType = 'Credit';
                            break;
                        default:
                            $creditDebitType = 'Other';
                            break;
                    }


                    $accountTotals[$creditDebitType] += $transaction['amount'];


                    echo "<td>$creditDebitType</td>";

                    echo "<td><a href='edit_transaction.php?id=" . $transaction['transaction_id'] . "'><button>Edit</button></a></td>"; // Edit link
                    echo "<td><a href='delete_transaction.php?id=" . $transaction['transaction_id'] . "'><button>Delete</button></a></td>"; // Delete link
                    echo "</tr>";
                }
                echo "</table>";
                echo "<div class='total-amount'>";
                foreach ($accountTotals as $accountType => $totalAmount) {
                    if ($accountType !== 'Other') {
                        echo "Total amount in $accountType: $ $totalAmount <br>";
                    }
                }
                echo "</div>";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </table>
    </div>
</body>

</html>