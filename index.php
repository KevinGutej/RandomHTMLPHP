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
        .scoreboard {
            margin-top: 20px;
            font-size: 1em;
            color: #555;
        }
        .hint {
            margin-top: 10px;
            font-size: 1em;
            color: #6a5acd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Guess the Number Game!</h1>
        <p>I have chosen a number between 1 and 100. Can you guess it?</p>

        <?php
        session_start();
        if (!isset($_SESSION['random_number'])) {
            $_SESSION['random_number'] = rand(1, 100);
            $_SESSION['lives'] = 5;
            $_SESSION['attempts'] = 0;
            $_SESSION['hints'] = 2;
        }

        if (isset($_POST['reset'])) {
            unset($_SESSION['random_number']);
            unset($_SESSION['lives']);
            unset($_SESSION['attempts']);
            unset($_SESSION['hints']);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        $message = "";
        $hint_message = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guess'])) {
            $guess = (int)$_POST['guess'];
            $randomNumber = $_SESSION['random_number'];
            $_SESSION['attempts']++;

            if ($guess === $randomNumber) {
                $message = "<p style='color: green;'>Congratulations! You guessed the number <strong>$randomNumber</strong> correctly in <strong>" . $_SESSION['attempts'] . "</strong> attempts!</p>";
                unset($_SESSION['random_number']);
                unset($_SESSION['lives']);
                unset($_SESSION['hints']);
            } elseif ($guess < $randomNumber) {
                $_SESSION['lives']--;
                $message = "<p style='color: orange;'>Too low! Try a higher number.</p>";
            } else {
                $_SESSION['lives']--;
                $message = "<p style='color: orange;'>Too high! Try a lower number.</p>";
            }

            if ($_SESSION['lives'] <= 0) {
                $message = "<p style='color: red;'>Game over! You've run out of lives. The correct number was <strong>$randomNumber</strong>.</p>";
                unset($_SESSION['random_number']);
                unset($_SESSION['lives']);
                unset($_SESSION['hints']);
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hint']) && $_SESSION['hints'] > 0) {
            $randomNumber = $_SESSION['random_number'];
            $hint_message = "<p class='hint'>Hint: The number is " . ($randomNumber % 2 == 0 ? "even" : "odd") . "!</p>";
            $_SESSION['hints']--;
        }
        ?>

        <form method="POST">
            <input type="number" name="guess" placeholder="Enter your guess" required min="1" max="100">
            <button type="submit">Submit</button>
            <button type="submit" name="reset" style="background-color: #dc3545;">Reset</button>
            <button type="submit" name="hint" style="background-color: #28a745;" <?php echo ($_SESSION['hints'] <= 0) ? 'disabled' : ''; ?>>Hint</button>
        </form>

        <div class="result">
            <?php echo $message; ?>
        </div>

        <div class="hint">
            <?php echo $hint_message; ?>
        </div>

        <div class="scoreboard">
            <p>Lives Remaining: <strong><?php echo $_SESSION['lives'] ?? 0; ?></strong></p>
            <p>Attempts Made: <strong><?php echo $_SESSION['attempts'] ?? 0; ?></strong></p>
            <p>Hints Remaining: <strong><?php echo $_SESSION['hints'] ?? 0; ?></strong></p>
        </div>
    </div>
</body>
</html>
