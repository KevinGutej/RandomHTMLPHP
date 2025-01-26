<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Number Guessing Game</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            text-align: center;
            padding: 50px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: #333;
        }
        input[type="number"] {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .result {
            margin-top: 20px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Guess the Number!</h1>
        <p>I have chosen a number between 1 and 100. Can you guess it?</p>

        <form method="POST">
            <input type="number" name="guess" placeholder="Enter your guess" required min="1" max="100">
            <button type="submit">Submit</button>
        </form>

        <div class="result">
            <?php
            session_start();

            // Generate a random number if not already set
            if (!isset($_SESSION['random_number'])) {
                $_SESSION['random_number'] = rand(1, 100);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $guess = (int)$_POST['guess'];
                $randomNumber = $_SESSION['random_number'];

                if ($guess === $randomNumber) {
                    echo "<p style='color: green;'>Congratulations! You guessed the number $randomNumber correctly!</p>";
                    unset($_SESSION['random_number']); // Reset the game
                } elseif ($guess < $randomNumber) {
                    echo "<p style='color: orange;'>Too low! Try a higher number.</p>";
                } else {
                    echo "<p style='color: orange;'>Too high! Try a lower number.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>