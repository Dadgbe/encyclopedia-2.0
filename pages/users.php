<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Пользователи</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<?php
session_start();
require '../config/config.php';
$content = '';

if ($_SESSION['role'] == 2 || $_SESSION['role'] == 1) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        foreach ($_POST['roles'] as $userId => $roleName) {
            $stmtRoleId = $pdo->prepare("SELECT id FROM roles WHERE RoleName = :roleName");
            $stmtRoleId->execute(['roleName' => $roleName]);
            $roleId = $stmtRoleId->fetchColumn();

            $stmtUpdate = $pdo->prepare("UPDATE users SET role = :roleId WHERE id = :userId");
            $stmtUpdate->execute(['roleId' => $roleId, 'userId' => $userId]);
        }
        $_SESSION['notification'] = "Роли обновлены успешно!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id != :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $users = $stmt->fetchAll();

    $content .= '<form method="POST" action="">';
    $content .= '<div class="users-container">';

    foreach ($users as $key => $user) {
        $stmtRole = $pdo->prepare("SELECT RoleName FROM roles WHERE id = :id");
        $stmtRole->execute(['id' => $user['role']]);
        $roleName = $stmtRole->fetchColumn();

        $stmtRoles = $pdo->prepare("SELECT * FROM roles");
        $stmtRoles->execute();
        $roles = $stmtRoles->fetchAll();

        $content .= '<div class="user-info">';
        $content .= '<p>' . htmlspecialchars($user['username'], ENT_QUOTES) . '</p>';
        $content .= '<select name="roles[' . htmlspecialchars($user['id'], ENT_QUOTES) . ']" class="role-select">';
            foreach ($roles as $key => $role) {
                $selected = ($role['RoleName'] == $roleName) ? 'selected' : '';
                $content .= '<option value="' . htmlspecialchars($role['RoleName'], ENT_QUOTES) . '"' . $selected . '>' . htmlspecialchars($role['RoleName'], ENT_QUOTES) .'</option>';
            }
        $content .= '</select>';
        $content .= '</div>';
    }
    $content .= '<button type="submit" class="save-users-button">Сохранить</button>';
    $content .= '</div>';
    $content .= '</form>';

    if (isset($_SESSION['notification'])) {
        $notificationMessage = $_SESSION['notification'];
        $notificationScript = "<script>document.getElementById('notification').style.display = 'flex'; document.getElementById('notification-content').innerHTML = '" . addslashes($notificationMessage) . "';</script>";
        unset($_SESSION['notification']);
    }

    $content .= '<div id="notification" class="notification-overlay" style="display: none;">';
    $content .= '<div class="notification-box">';
    $content .= '<p id="notification-content">Роли обновлены успешно</p>';
    $content .= '<button onclick="closeNotification()">ОК</button>';
    $content .= '</div>';
    $content .= '</div>';

    $content .= '<script>
                    function closeNotification() {
                        document.getElementById("notification").style.display = "none";
                    }
                 </script>';

    include '../templates/template.php';
} else {
    echo "Нет доступа.";
}

echo $notificationScript ?? '';
?>

