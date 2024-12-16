<?php
session_start();


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = htmlspecialchars($_SESSION['username']);

$profilePicture = !empty($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'default-profile.jpg';

if (!file_exists($profilePicture)) {
    $profilePicture = 'default-profile.jpg';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body, h1, p, img {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .profile-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .username {
            font-size: 1.5rem;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 10px;
        }

        .profile-details {
            font-size: 1rem;
            color: #777;
            margin-bottom: 20px;
        }

        .cta-button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .cta-button:hover {
            background-color: #2980b9;
        }

        @media (max-width: 600px) {
            .profile-container {
                width: 80%;
                padding: 15px;
            }

            .profile-pic {
                width: 120px;
                height: 120px;
            }

            .username {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="profile-pic">
        <h1 class="username">Welcome, <?php echo $username; ?></h1>
        <p class="profile-details">Your profile details will be shown here...</p>
        
        <a href="userprofile.php" class="cta-button">Edit Profile</a>
        <a href="logout.php" class="cta-button">Logout</a>
    </div>
</body>
</html>
