<?php
include 'db_connection.php';

$id = $_POST['id'];
$title = $_POST['title'];
$price = $_POST['price'];
$category = $_POST['category'];
$author_id = $_POST['author_id'];
$stock = $_POST['stock'];

$image_name = null;

if (!empty($_FILES['image']['name'])) {
    $image_name = basename($_FILES['image']['name']);
    $target = "images/" . $image_name;
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $query = "UPDATE product SET title=?, price=?, category=?, author_id=?, stock=?, image_name=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdsdisi", $title, $price, $category, $author_id, $stock, $image_name, $id);
} else {
    $query = "UPDATE product SET title=?, price=?, category=?, author_id=?, stock=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdsdii", $title, $price, $category, $author_id, $stock, $id);
}

if ($stmt->execute()) {
    header("Location: products.php");
} else {
    echo "Update failed: " . $stmt->error;
}
