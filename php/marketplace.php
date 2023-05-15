<?php
require_once 'bootstrap.php';
include_once("../inc/functions.inc.php");

$user_id = $_SESSION['user_id'];
$conn = Db::getInstance();
$statement = $conn->prepare("SELECT * FROM prompts ORDER BY date DESC");
$statement->execute();
$data = $statement->fetchAll(PDO::FETCH_ASSOC);

?>


<!-- Output the data in an HTML table -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
</head>
<body>
    <table>
    <tr><th>Name</th><th>Description</th><th>Date</th><th>Picture</th><th>Categories</th><th>Price</th></tr>

    <?php foreach ($data as $row) : ?>


        <tr>
        <td> <?php echo $row['name'] ?> </td>
        <td> <?php echo $row['description'] ?> </td>
        <td> <?php echo $row['date'] ?> </td>
        <td> <img src=<?php echo $row['pictures']?> width='100'></td>
        <td> <?php echo $row['categories'] ?> </td>
        <td> <?php echo $row['price'] ?> </td>
        </tr>

    <?php endforeach; ?>

    </table>
    
</body>
</html>