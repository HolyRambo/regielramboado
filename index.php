<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle Add Expense
if (isset($_POST['add_expense'])) {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $expense_date = $_POST['expense_date'];

    $sql = "INSERT INTO expenses (user_id, item_name, amount, category, expense_date) VALUES ('$user_id', '$item_name', '$amount', '$category', '$expense_date')";
    if (mysqli_query($conn, $sql)) {
        $message = "Expense added successfully!";
    }
}

// Handle Delete Expense
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM expenses WHERE id=$id AND user_id=$user_id");
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Expense Tracker</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-blue-500 min-h-screen p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
        
        <!-- Header & Logout -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-blue-500">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 text-sm font-semibold">Logout</a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-center font-medium"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Add Expense Form -->
        <form action="dashboard.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 bg-gray-50 p-4 rounded-xl border">
            <h2 class="md:col-span-2 text-lg font-bold text-gray-800">Add New Expense</h2>
            <div>
                <label class="block font-medium text-gray-700">Expense Name:</label>
                <input type="text" name="item_name" class="w-full border rounded-md p-2 bg-white" required>
            </div>
            <div>
                <label class="block font-medium text-gray-700">Amount (₱):</label>
                <input type="number" step="0.01" name="amount" class="w-full border rounded-md p-2 bg-white" required>
            </div>
            <div>
                <label class="block font-medium text-gray-700">Category:</label>
                <select name="category" class="w-full border rounded-md p-2 bg-white">
                    <option value="Food">Food</option>
                    <option value="Transport">Transport</option>
                    <option value="Bills">Bills</option>
                    <option value="Entertainment">Entertainment</option>
                    <option value="Others">Others</option>
                </select>
            </div>
            <div>
                <label class="block font-medium text-gray-700">Date:</label>
                <input type="date" name="expense_date" class="w-full border rounded-md p-2 bg-white" required>
            </div>
            <div class="md:col-span-2">
                <button type="submit" name="add_expense" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-700 font-semibold shadow">Add Expense</button>
            </div>
        </form>

        <!-- Expense Table -->
        <h2 class="text-xl font-bold text-gray-800 mb-4">Your Expenses</h2>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="border border-gray-300 p-2">Item</th>
                        <th class="border border-gray-300 p-2">Category</th>
                        <th class="border border-gray-300 p-2">Amount</th>
                        <th class="border border-gray-300 p-2">Date</th>
                        <th class="border border-gray-300 p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM expenses WHERE user_id=$user_id ORDER BY expense_date DESC");
                    $total = 0;
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $total += $row['amount'];
                            echo "<tr class='hover:bg-gray-50'>";
                            echo "<td class='border border-gray-300 p-2'>" . $row['item_name'] . "</td>";
                            echo "<td class='border border-gray-300 p-2 text-center'>" . $row['category'] . "</td>";
                            echo "<td class='border border-gray-300 p-2 text-right'>₱" . number_format($row['amount'], 2) . "</td>";
                            echo "<td class='border border-gray-300 p-2 text-center'>" . $row['expense_date'] . "</td>";
                            echo "<td class='border border-gray-300 p-2 text-center space-x-2'>";
                            echo "<a href='edit.php?id=" . $row['id'] . "' class='text-blue-600 hover:underline font-medium'>Edit</a>";
                            echo "<a href='dashboard.php?delete=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")' class='text-red-600 hover:underline font-medium'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='border border-gray-300 p-4 text-center text-gray-500'>No expenses recorded.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 p-4 bg-emerald-50 rounded-lg flex justify-between items-center border border-emerald-200">
            <span class="text-lg font-bold text-blue-800">Total Spent:</span>
            <span class="text-xl font-bold text-blue-600">₱<?php echo number_format($total, 2); ?></span>
        </div>
    </div>
</body>
</html>