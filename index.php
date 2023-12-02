<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking Transactions</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav>
        <div class="nav-left">
            <p>Made by Nijat Zeynalli</p>
        </div>
        <ul class="nav-right">
            <li><a href="add_transaction.php">Add Transaction</a></li>
            <li><a href="transactions.php">Show Transactions</a></li>
        </ul>
    </nav>

    <div class="content">
    <h1>Welcome to Banking Transactions System</h1>
    <p>This application helps you manage your transactions efficiently.</p>

    <h2>Technical Aspects</h2>
    <div class="technical" >
    
        <p><strong>PDO (PHP Data Objects):</strong> Handles secure database connectivity and interaction with PHP.</p>
        <p><strong>HTML/CSS:</strong> Structures and styles the frontend for a user-friendly interface.</;>
        <p><strong>Forms:</strong> PHP manages form submissions for adding, editing, and processing transactions.</p>
        <p><strong>HTTP Methods:</strong> Utilizes both POST and GET methods for data modification and retrieval.</p>
        <p><strong>Error Handling:</strong> Implements error management for database connections, query failures, and input validation.</p>
   
    </div>

    <h2>Database Schema</h2>
    <p>The database consists of the following tables:</p>
    <ul>
        <li><strong>categories:</strong> Stores transaction categories.</li>
        <li><strong>payment_methods:</strong> Stores different payment methods.</li>
        <li><strong>transactions:</strong> Stores transaction details, including amount, date, category, and payment method.</li>
    </ul>

    <p>You can add transactions using the <strong>Add Transaction</strong> link and view transactions using the <strong>Show Transactions</strong> link above.</p>
</div>

</body>

</html>