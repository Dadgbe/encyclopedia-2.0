<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <h1><a style="color: black;" href="../public/index.php">Цифровая энциклопедия</a></h1>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h2 style="text-align:center" class="mt-5">Вход</h2>
                    <form action="auth.php" method="post">
                        <div  class="form-group">
                            <label for="username">Имя пользователя</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group" >
                            <label for="password">Пароль</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" id="getin" class="btnstyle">Войти</button>
                    </form>
                    <?php

                        session_start();
                        require '../config/config.php';

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $username = $_POST['username'];
                            $password = $_POST['password'];

                            $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE username = ?");
                            $stmt->execute([$username]);
                            $user = $stmt->fetch();

                            if ($user && password_verify($password, $user['password'])) {
                                $_SESSION['user_id'] = $user['id'];
                                $_SESSION['user_name'] = $username;
                                $_SESSION['role'] = $user['role'];

                                header("Location: ../public/index.php");
                                exit();
                            }
                            else {
                                echo "Неверное имя пользователя или пароль.";
                            }
                        }
                    ?>

                </div>
            </div>
        </div>
    </main>
</body>
</html>
