<?php
include 'connection.php';
session_start();

$message = "";
if (isset($_POST['register'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";
    if (mysqli_query($conn, $sql)) {
        $message = "Registration successful! <a href='login.php' class='text-blue-600 underline'>Login here</a>";
    } else {
        $message = "Error: Username might already exist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Expense Tracker</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-blue-500 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-blue-500 mb-6 text-center">Create Account</h2>
        <?php if(!empty($message)) echo "<div class='mb-4 p-3 bg-blue-500 text-blue-500 rounded-lg text-sm'>$message</div>"; ?>
        
        <form action="register.php" method="POST" class="space-y-4">
            <div>
            <label class="block font-medium text-gray-700">Username</label>
            <input type="text" name="username" class="w-full border rounded-md p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div>
            <label class="block font-medium text-gray-700">Password:</label>
            <input type="password" name="password" class="w-full border rounded-md p-2 focus:ring-2 focus:ring-emerald-500 outline-none" required>
            </div>
            <button type="submit" name="register" class="w-full bg-blue-500 text-white p-2 rounded-lg font-semibold shadow">Register</button>
        </form>
        <p class="mt-4 text-center text-sm text-gray-600">Already have an account? <a href="login.php" class="text-blue-500 font-medium">Login</a></p>
    </div>
</body>
</html>