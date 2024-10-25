<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <h1><a style="color: black;" href="../public/index.php">Цифровая энциклопедия</a></h1>
    <main>

        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h2 class="mt-5" style="text-align:center">Регистрация</h2>
                    <form action="reg.php" method="post">
                        <div class="form-group">
                            <label for="username">Имя пользователя</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <label for="confirm_password">Подтвердите пароль</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btnstyle">Зарегистрироваться</button>
                    </form>
                    <?php
                        require '../config/config.php';
                        session_start();

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $username = $_POST['username'];
                            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                            $role = 3;

                            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                            $stmt->execute([$username, $password, $role]);

                            header("Location: auth.php");
                            exit();
                        }
                    ?>

                </div>
            </div>
        </div>
    </main>
    <script>
        const password = document.getElementById("password");
        const confirm_password = document.getElementById("confirm_password");

        function validatePassword(){
            if(password.value !== confirm_password.value) {
                confirm_password.setCustomValidity("Пароли не совпадают");
            } else {
                confirm_password.setCustomValidity('');
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    </script>
</body>
</html>
