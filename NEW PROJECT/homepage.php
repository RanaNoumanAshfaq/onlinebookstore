<?php
session_start();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nouman";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$searchTerm = "";


if (isset($_POST['search'])) {
    $searchTerm = $_POST['search_term'];
    $searchTerm = $conn->real_escape_string($searchTerm);
    $sql = "SELECT * FROM books WHERE title LIKE '%$searchTerm%' OR author LIKE '%$searchTerm%'";
} else {
    $sql = "";
}

if (!empty($sql)) {
    $result = $conn->query($sql);
} else {
    $result = null; 
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Book Store</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
    <style>
        
        .user-profile {
            padding: 20px;
            text-align: center;
            color: white;
        }

        .profile-info {
            display: inline-block;
            text-align: center;
            margin-top: 20px;
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .user-profile p {
            font-size: 1.2rem;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            background: url('bg.jfif') no-repeat center center fixed;
            background-size: cover;
        }

        header {
            background-color: #3498db;
            color: white;
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        header img {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            max-width: 100px;
        }

        header .nav-links {
            margin-right: auto;
            display: flex;
            gap: 20px;
            align-items: center;
        }

        header .nav-links a {
            text-decoration: none;
            color: white;
            font-size: 1.5rem;
            transition: color 0.3s;
        }

        header .nav-links a:hover {
            color: #3498db;
        }

        .hero-section {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 100px 20px;
            text-align: center;
            color: white;
            position: relative;
            z-index: 1;
        }

        .hero-section h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .cta-button {
            background-color: #3498db;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            font-size: 1.2rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .cta-button:hover {
            background-color: #2980b9;
        }

        .featured-books {
            padding: 50px 20px;
            text-align: center;
        }

        .featured-books h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .featured-books .books-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .book {
            background-color: white;
            width: 250px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .book img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .book h3 {
            font-size: 1.2rem;
            margin-top: 10px;
        }

        .book p {
            font-size: 1rem;
            color: #888;
        }

        .footer {
            background-color: #333;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .footer a {
            color: white;
            text-decoration: none;
            padding: 0 10px;
            transition: color 0.3s;
        }

        .footer a:hover {
            color: #3498db;
        }
    </style>
</head>
<body>

<header>
    <img src="logo.png" alt="Online Book Store Logo">
    <div class="nav-links">
        <a href="homepage.php"><i class="fas fa-home"></i></a>
        <a href="contact.php"><i class="fas fa-envelope"></i></a>
        <a href="aboutus.php"><i class="fas fa-info-circle"></i></a>
        <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
    </div>
</header>

<
<div class="hero-section">
    <h1>Welcome to Our Online Book Store</h1>
    <p>Find your next great read with just a few clicks.</p>
    <a href="books.php" class="cta-button">Browse books</a>
</div>


<div class="user-profile">
    <?php
    if (isset($_SESSION['username'])) {
        
        $username = htmlspecialchars($_SESSION['username']);
        
        
        $profilePicture = !empty($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'default-profile.jpg';

     
        if (!file_exists($profilePicture)) {
            $profilePicture = 'default-profile.jpg'; 
        }

        echo "<div class='profile-info'>";
        echo "<img src='$profilePicture' alt='Profile Picture' class='profile-pic'>"; 
        echo "<p>Welcome, $username! <a href='profile.php'>View Profile</a></p>";
        echo "</div>";

        
        echo "<form method='POST' action=''>";
        echo "<button type='submit' name='logout' class='cta-button'>Logout</button>";
        echo "</form>";
    } else {
        
        echo "<p>Welcome, guest! Please <a href='profile.php'>log in</a> to view your profile.</p>";
    }
    ?>
</div>

</div>


<div class="featured-books">
    <h2>Featured Books</h2>
    <div class="books-container">
        
        <div class="book">
            <img src="book1.jfif" alt="Book Image">
            <h3>Book Title</h3>
            <p>by Author</p>
        </div>
        <div class="book">
            <img src="book2.jfif" alt="Book Image">
            <h3>Book Title</h3>
            <p>by Author</p>
        </div>
        <div class="book">
            <img src="book3.jpg" alt="Book Image">
            <h3>Book Title</h3>
            <p>by Author</p>
        </div>
    </div>
</div>


<?php

if (!empty($searchTerm)) {
    echo '<div class="book-list">';
    echo '<h2>Books Found</h2>';
    echo '<div class="books-container">';
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='book'>";
            
            if (!empty($row['image'])) {
                echo "<img src='uploads/{$row['image']}' alt='Book Image'>";
            } else {
                echo "<img src='default-book.jpg' alt='Book Image'>";
            }
            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
            echo "<p>by " . htmlspecialchars($row['author']) . "</p>";
            echo "<p><strong>Price:</strong> $" . htmlspecialchars($row['price']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No books found for '$searchTerm'.</p>";
    }
    echo '</div>';
    echo '</div>';
}
?>


<div class="footer">
    <p>&copy; 2024 Online Book Store. All rights reserved.</p>
    <p>Follow us on <a href="#">Facebook</a>, <a href="#">Instagram</a>, <a href="#">Twitter</a></p>
</div>

</body>
</html>
