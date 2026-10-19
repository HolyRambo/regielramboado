<?php
include 'connection.php';
session_start();

$error = "";
if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Expense Tracker</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-blue-500 flex items-center justify-center min-h-screen">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-blue-500 mb-6 text-center">User Login</h2>
        <?php if(!empty($error)) echo "<div class='mb-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm'>$error</div>"; ?>
        
        <form action="login.php" method="POST" class="space-y-4">
            <div>
            <label class="block font-medium text-gray-700">Username</label>
            <input type="text" name="username" class="w-full border rounded-md p-2 focus:ring-2 focus:ring-emerald-500 outline-none" required>
            </div>
            <div>
            <label class="block font-medium text-gray-700">Password</label>
            <input type="password" name="password" class="w-full border rounded-md p-2 focus:ring-2 focus:ring-emerald-500 outline-none" required>
            </div>
            <button type="submit" name="login" class="w-full bg-blue-500 text-white p-2 rounded-lg font-semibold shadow">Login</button>
        </form>
        <p class="mt-4 text-center text-sm text-gray-600">Don't have an account? <a href="register.php" class="text-blue-500 font-medium">Register</a></p>
    </div>
</body>
</html>