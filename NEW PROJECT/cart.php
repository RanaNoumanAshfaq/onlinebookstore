<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'nouman');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_price = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        foreach ($_POST['quantities'] as $book_id => $quantity) {
            if ($quantity > 0) {
                $_SESSION['cart'][$book_id] = $quantity;
            } else {
                unset($_SESSION['cart'][$book_id]); 
            }
        }
        header("Location: cart.php"); 
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
        }

        header {
            background: #2c3e50;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .cart-item img {
            width: 60px;
            height: 90px;
            margin-right: 15px;
            border-radius: 5px;
        }

        .cart-item-info {
            display: flex;
            align-items: center;
        }

        .cart-item-info p {
            margin: 0;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
        }

        .cart-item-quantity input {
            width: 50px;
            padding: 5px;
            margin-left: 10px;
        }

        .total {
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }

        .promo-code {
            margin-top: 20px;
        }

        .promo-code input {
            width: calc(100% - 100px);
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .promo-code button {
            padding: 10px 20px;
            background-color: #27ae60;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .payment-button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            display: block;
            margin: 20px 0;
            text-align: center;
            text-decoration: none;
        }

        .payment-button:hover {
            background-color: #2980b9;
        }

        .continue-shopping {
            text-align: center;
            margin-top: 20px;
        }

        .continue-shopping a {
            color: #3498db;
            text-decoration: none;
        }

        .continue-shopping a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header>
    <h1>Your Cart</h1>
</header>
<div class="container">
    <?php if (!empty($cart_items)): ?>
        <form method="POST">
            <?php
            foreach ($cart_items as $book_id => $quantity) {
                $sql = "SELECT * FROM books WHERE id = $book_id";
                $result = $conn->query($sql);

                if ($result && $row = $result->fetch_assoc()) {
                    $subtotal = $row['price'] * $quantity;
                    $total_price += $subtotal;

                    echo "<div class='cart-item'>
                            <div class='cart-item-info'>
                               
                                <p>" . htmlspecialchars($row['title']) . "</p>
                            </div>
                            <div class='cart-item-quantity'>
                                <p>PKR " . number_format($subtotal, 2) . "</p>
                                <input type='number' name='quantities[$book_id]' value='$quantity' min='0'>
                            </div>
                          </div>";
                }
            }
            ?>

            <p class="total">Total: PKR <?php echo number_format($total_price, 2); ?></p>

            <div class="promo-code">
                <input type="text" placeholder="Enter Promo Code">
                <button type="button">Apply</button>
            </div>

            <button type="submit" name="update" class="payment-button">Update Cart</button>
        </form>
        <a class="payment-button" href="checkout.php">Proceed to Checkout</a>
    <?php else: ?>
        <p>Your cart is empty. <a href="books.php">Browse Books</a></p>
    <?php endif; ?>

    <div class="continue-shopping">
        <a href="books.php">Continue Shopping</a>
    </div>
</div>
</body>
</html>

<?php $conn->close(); ?>
