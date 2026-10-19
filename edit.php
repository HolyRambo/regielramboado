<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id = $_GET['id'];

// Fetch existing record
$result = mysqli_query($conn, "SELECT * FROM expenses WHERE id=$id AND user_id=$user_id");
$row = mysqli_fetch_assoc($result);

if (!$row) {
    header("Location: dashboard.php");
    exit();
}

// Handle Update
if (isset($_POST['update_expense'])) {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $expense_date = $_POST['expense_date'];

    $sql = "UPDATE expenses SET item_name='$item_name', amount='$amount', category='$category', expense_date='$expense_date' WHERE id=$id AND user_id=$user_id";
    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Expense</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-blue-600 mb-6 text-center">Edit Expense</h2>
        
        <form action="edit.php?id=<?php echo $id; ?>" method="POST" class="space-y-4">
            <div>
                <label class="block font-medium text-gray-700">Expense Name:</label>
                <input type="text" name="item_name" value="<?php echo $row['item_name']; ?>" class="w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block font-medium text-gray-700">Amount (₱):</label>
                <input type="number" step="0.01" name="amount" value="<?php echo $row['amount']; ?>" class="w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block font-medium text-gray-700">Category:</label>
                <select name="category" class="w-full border rounded-md p-2">
                    <option value="Food" <?php if($row['category']=='Food') echo 'selected'; ?>>Food</option>
                    <option value="Transport" <?php if($row['category']=='Transport') echo 'selected'; ?>>Transport</option>
                    <option value="Bills" <?php if($row['category']=='Bills') echo 'selected'; ?>>Bills</option>
                    <option value="Entertainment" <?php if($row['category']=='Entertainment') echo 'selected'; ?>>Entertainment</option>
                    <option value="Others" <?php if($row['category']=='Others') echo 'selected'; ?>>Others</option>
                </select>
            </div>
            <div>
                <label class="block font-medium text-gray-700">Date:</label>
                <input type="date" name="expense_date" value="<?php echo $row['expense_date']; ?>" class="w-full border rounded-md p-2" required>
            </div>
            <div class="flex space-x-2">
                <button type="submit" name="update_expense" class="w-full bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 font-semibold shadow">Update</button>
                <a href="dashboard.php" class="w-full bg-gray-300 text-gray-700 p-2 rounded-lg text-center hover:bg-gray-400 font-semibold shadow">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>