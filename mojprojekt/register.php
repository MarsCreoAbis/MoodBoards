<?php

$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "project"; 

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Połączenie z bazą danych nieudane: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobranie danych z formularza
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo "Wszystkie pola muszą być wypełnione!";
    } else {
        // Dubel usera
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "Taki użytkownik już istnieje!";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert_sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                echo "Nowy użytkownik został zarejestrowany!";
                header("Location: log.php"); 
                exit();
            } else {
                echo "Błąd podczas rejestracji: " . $conn->error;
            }
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #3498db;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Formularz Rejestracyjny</h2>
        <form action="register.php" method="post">
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Hasło:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Zarejestruj się">
        </form>
        <a href="log.php" class="login-link">Masz już konto? Zaloguj się</a>
    </div>
</body>
</html>
