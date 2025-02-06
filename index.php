<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Number Guessing Game</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            text-align: center;
            padding: 50px;
        }
        .container {
            max-width: 600px;
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
            margin: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .result, .scoreboard, .hint {
            margin-top: 20px;
            font-size: 1.2em;
        }
        .hint {
            color: #6a5acd;
        }
        .leaderboard {
            margin-top: 20px;
            font-size: 1em;
            background: #eee;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Advanced Guess the Number Game!</h1>
        <p>Select a difficulty level and try to guess the number!</p>

        <?php
        session_start();

        if (!isset($_SESSION['difficulty'])) {
            $_SESSION['difficulty'] = 'medium';
        }

        if (!isset($_SESSION['leaderboard'])) {
            $_SESSION['leaderboard'] = [];
        }

        function initializeGame($difficulty = 'medium') {
            $_SESSION['difficulty'] = $difficulty;
            $_SESSION['random_number'] = rand(1, 100);
            $_SESSION['attempts'] = 0;
            $_SESSION['hints'] = 3;
            $_SESSION['lives'] = $difficulty === 'easy' ? 10 : ($difficulty === 'hard' ? 3 : 5);
        }

        if (!isset($_SESSION['random_number'])) {
            initializeGame($_SESSION['difficulty']);
        }

        if (isset($_POST['reset'])) {
            initializeGame($_SESSION['difficulty']);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        if (isset($_POST['difficulty'])) {
            initializeGame($_POST['difficulty']);
        }

        $message = "";
        $hint_message = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guess'])) {
            $guess = (int)$_POST['guess'];
            $randomNumber = $_SESSION['random_number'];
            $_SESSION['attempts']++;

            if ($guess === $randomNumber) {
                $message = "<p style='color: green;'>🎉 Congratulations! You guessed the number <strong>$randomNumber</strong> in <strong>" . $_SESSION['attempts'] . "</strong> attempts!</p>";
                $_SESSION['leaderboard'][] = $_SESSION['attempts'];
                unset($_SESSION['random_number']);
                unset($_SESSION['lives']);
            } elseif ($guess < $randomNumber) {
                $_SESSION['lives']--;
                $message = "<p style='color: orange;'>⬆️ Too low! Try a higher number.</p>";
            } else {
                $_SESSION['lives']--;
                $message = "<p style='color: orange;'>⬇️ Too high! Try a lower number.</p>";
            }

            if ($_SESSION['lives'] <= 0) {
                $message = "<p style='color: red;'>💀 Game over! The correct number was <strong>$randomNumber</strong>.</p>";
                unset($_SESSION['random_number']);
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hint']) && $_SESSION['hints'] > 0) {
            $randomNumber = $_SESSION['random_number'];
            if ($_SESSION['hints'] == 3) {
                $hint_message = "<p class='hint'>📌 Hint: The number is " . ($randomNumber % 2 == 0 ? "even" : "odd") . ".</p>";
            } elseif ($_SESSION['hints'] == 2) {
                $hint_message = "<p class='hint'>🔢 Hint: The number is " . ($randomNumber > 50 ? "greater than 50" : "less than or equal to 50") . ".</p>";
            } else {
                $range = $randomNumber + rand(-5, 5);
                $hint_message = "<p class='hint'>🎯 Hint: The number is close to $range.</p>";
            }
            $_SESSION['hints']--;
        }
        ?>

        <form method="POST">
            <input type="number" name="guess" placeholder="Enter your guess" required min="1" max="100">
            <button type="submit">Submit</button>
            <button type="submit" name="reset" style="background-color: #dc3545;">Reset</button>
            <button type="submit" name="hint" style="background-color: #28a745;" <?php echo ($_SESSION['hints'] <= 0) ? 'disabled' : ''; ?>>Hint</button>
        </form>

        <form method="POST">
            <label>Select Difficulty:</label>
            <button type="submit" name="difficulty" value="easy" style="background-color: #00c851;">Easy</button>
            <button type="submit" name="difficulty" value="medium" style="background-color: #ffbb33;">Medium</button>
            <button type="submit" name="difficulty" value="hard" style="background-color: #ff4444;">Hard</button>
        </form>

        <div class="result">
            <?php echo $message; ?>
        </div>

        <div class="hint">
            <?php echo $hint_message; ?>
        </div>

        <div class="scoreboard">
            <p>❤️ Lives Remaining: <strong><?php echo $_SESSION['lives'] ?? 0; ?></strong></p>
            <p>📊 Attempts Made: <strong><?php echo $_SESSION['attempts'] ?? 0; ?></strong></p>
            <p>💡 Hints Remaining: <strong><?php echo $_SESSION['hints'] ?? 0; ?></strong></p>
        </div>

        <div class="leaderboard">
            <h3>🏆 Leaderboard - Best Scores</h3>
            <ul>
                <?php
                if (!empty($_SESSION['leaderboard'])) {
                    sort($_SESSION['leaderboard']);
                    foreach ($_SESSION['leaderboard'] as $score) {
                        echo "<li>⏳ $score attempts</li>";
                    }
                } else {
                    echo "<li>No scores yet!</li>";
                }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>
