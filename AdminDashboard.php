<?php
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard | Bookish</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css">
  <style>
    :root {
      --pink: #e84393;
    }

    body {
      margin: 0;
      font-family: Verdana, Geneva, Tahoma, sans-serif;
      background-color: #f8f8f8;
    }

    header {
      position: fixed;
      top: 0; left: 0; right: 0;
      background: #fff;
      z-index: 1000;
      box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
    }

    header .flex {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1.5rem 2rem;
      max-width: 1200px;
      margin: auto;
    }

    header .logo {
      font-size: 3rem;
      font-weight: bold;
      color: #333;
    }

    header .logo span {
      color: var(--pink);
    }

    .btn {
      display: inline-block;
      border-radius: 5rem;
      background: #333;
      color: #fff;
      padding: 1rem 3rem;
      cursor: pointer;
      font-size: 1.6rem;
      text-decoration: none;
      transition: background 0.3s;
    }

    .btn:hover {
      background: var(--pink);
    }


    .dashboard-container {
      max-width: 600px;
      background: #ffe3f1;
      border-radius: 1rem;
      box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.1);
      padding: 4rem 3rem;
      margin: 10rem auto 4rem auto; 
      text-align: center;
    }

    .dashboard-container h1 {
      font-size: 2.8rem;
      margin-bottom: 1rem;
      color: #333;
    }

    .dashboard-container p {
      font-size: 1.6rem;
      color: #666;
      margin-bottom: 3rem;
    }

    .dashboard-buttons {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .dashboard-buttons .btn {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.2rem;
      font-size: 1.6rem;
    }

    .dashboard-buttons .btn i {
      margin-right: 1rem;
      font-size: 1.6rem;
    }

    @media (max-width: 600px) {
      .dashboard-container {
        margin: 11rem 2rem 3rem;
        padding: 3rem 2rem;
      }
    }
  </style>
</head>
<body>

  <header>
    <section class="flex">
      <a href="index.php" class="btn"><i class="fas fa-arrow-left"></i> Back</a>
      <a href="index.php" class="logo">Bookish<span>.</span></a>
    </section>
  </header>

  <section class="dashboard-container">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h1>

    <p>Use the tools below to manage your bookstore professionally.</p>
    <div class="dashboard-buttons">
    <a href="add_product.php" class="btn"><i class="fas fa-plus-circle"></i> Add Product</a>
    <a href="search.php?action=modify" class="btn"><i class="fas fa-pen-to-square"></i> Modify Product</a>
    <a href="search.php?action=delete" class="btn"><i class="fas fa-trash"></i> Delete Product</a>
</div>
  </section>

</body>
</html>
