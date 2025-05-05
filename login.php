<?php
session_start();


$login_error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = strtolower(trim($_POST['username']));
    $password = trim($_POST['password']); // Trim spaces


    $admin_credentials = [
        'Reema' => '1234',
        'Deena' => '112233',
        'Noor' => '0000',
        'Linah' => '123456'
    ];

 
    foreach ($admin_credentials as $admin_username => $admin_password) {
        if ($username === strtolower($admin_username) && $password === $admin_password) {
    
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $admin_username;
            $_SESSION['is_admin'] = true; 
            $_SESSION['admin_name'] = $admin_username; 
            header('Location: AdminDashboard.php'); 
            exit;
        }
    }

   
    $filePath = 'users.txt';
    if (file_exists($filePath)) {
        $users = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($users as $user) {
            list($stored_username, $stored_password, $gender) = explode('|', $user);
            if ($username === strtolower(trim($stored_username)) && $password === trim($stored_password)) {
          
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $stored_username;
                $_SESSION['is_admin'] = false; 
                header('Location: index.php'); 
                exit;
            }
        }
    }


    $login_error = 'Invalid username or password';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://use.fontawesome.com/be1ba39dfe.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
</head>
<body>
    <header>
        <section class="flex">
            <a href="index.php" class="btn" >
               <!-- <i class="fas fa-arrow-left">-->Back</i>
            </a>
            <a href="index.php" class="logo">Bookish<span>.</span></a>
        </section>
    </header>

    <div class="login-page">
        <form method="POST" action="">
            <h1>Login</h1>
            <?php if (!empty($login_error)): ?>
                <p style="color:red;"><?php echo $login_error; ?></p>
            <?php endif; ?>
            <div class="input-box">
                <input type="text" name="username" placeholder="Name" required>
                <i class='bx bxs-user'></i>
            </div> 
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div> 
            <div class="remember-forgot">
                <label>
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="Contact.html">Forgot password?</a>
            </div>
            <button type="submit" name="login" class="btn">Login</button>

            <div class="register-link">
                <p>Thank you for using our website! </p>
            </div>
        </form>
    </div>
</body>
</html>