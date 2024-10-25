<?php
session_start();
require '../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['role'])) {
    $title = $_POST['title'];
    $author_id = $_SESSION['user_id'];
    $category = $_POST['category'];
    $brief_info = $_POST['brief_info'] ?? '';

    $fields = [];
    for ($i = 1; $i <= 10; $i++) {
        $fields["field{$i}"] = $_POST["textarea{$i}"] ?? '';
    }

    $status = 'Черновик';
    if ($_SESSION['role'] === 1) {
        $status = 'Опубликовано';
    } else if ($_SESSION['role'] === 3 || $_SESSION['role'] === 2) {
        $status = 'На рецензии';
    }

    $stmt_cat = $pdo->prepare("SELECT ID FROM categories WHERE CategoryName = :categoryname");
    $stmt_cat->execute(['categoryname' => $category]);
    $category_id = $stmt_cat->fetchColumn();

    $image = $_FILES['image']['name'];
    $target_dir = "../public/img/";
    $target_file = $target_dir . basename($image);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (!empty($image) && in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        $check = getimagesize($_FILES['image']['tmp_name']);
        if($check !== false) {
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        } else {
            $image = '';
        }
    } else {
        $image = '';
    }

    if (empty($image)) {
        $image = $_POST['current_image'];
    }

    // Handle PDF files
    $pdf_files = [];
    for ($i = 1; $i <= 10; $i++) {
        $pdf_field = 'pdf' . $i;
        if (isset($_FILES[$pdf_field]) && $_FILES[$pdf_field]['error'] == UPLOAD_ERR_OK) {
            $pdf_name = $_FILES[$pdf_field]['name'];
            $pdf_target_file = "../uploads/" . basename($pdf_name);
            move_uploaded_file($_FILES[$pdf_field]['tmp_name'], $pdf_target_file);
            $pdf_files[$pdf_field] = $pdf_name;
        } else {
            $pdf_files[$pdf_field] = $_POST["current_{$pdf_field}"] ?? '';
        }
    }

    if (isset($_POST['article_id']) && !empty($_POST['article_id'])) {
        $article_id = $_POST['article_id'];
        $sql = "UPDATE articles SET title = ?, organizational = ?, economic = ?, marketing = ?, phisics = ?, technical = ?, mathematical = ?, normative = ?, pravo = ?, constitutional = ?, socialComputer = ?, abstract = ?, author_id = ?, status = ?, category = ?, image = ?, pdf1 = ?, pdf2 = ?, pdf3 = ?, pdf4 = ?, pdf5 = ?, pdf6 = ?, pdf7 = ?, pdf8 = ?, pdf9 = ?, pdf10 = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_merge([$title, $fields['field1'], $fields['field2'], $fields['field3'], $fields['field4'], $fields['field5'], $fields['field6'], $fields['field7'], $fields['field8'], $fields['field9'], $fields['field10'], $brief_info, $author_id, $status, $category_id, $image], array_values($pdf_files), [$article_id]));

        if ($_SESSION['role'] === 1) {
            $_SESSION['message'] = 'Статья успешно опубликована';
        } else {
            $_SESSION['message'] = 'Статья отправлена на рецензию модератору';
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        $sql = "INSERT INTO articles (title, organizational, economic, marketing, phisics, technical, mathematical, normative, pravo, constitutional, socialComputer, abstract, author_id, status, views, category, image, pdf1, pdf2, pdf3, pdf4, pdf5, pdf6, pdf7, pdf8, pdf9, pdf10) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_merge([$title, $fields['field1'], $fields['field2'], $fields['field3'], $fields['field4'], $fields['field5'], $fields['field6'], $fields['field7'], $fields['field8'], $fields['field9'], $fields['field10'], $brief_info, $author_id, $status, 1, $category_id, $image], array_values($pdf_files)));

        if ($_SESSION['role'] === 1) {
            $_SESSION['message'] = 'Статья успешно опубликована';
        } else {
            $_SESSION['message'] = 'Статья отправлена на рецензию модератору';
        }

        header("Location: ". $_SERVER['HTTP_REFERER']);
        exit();
    }
} else {
    echo "Нет доступа.";
}
?>
