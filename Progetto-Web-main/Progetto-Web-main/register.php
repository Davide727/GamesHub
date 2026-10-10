<?php
require 'connessione.php';

header('Content-Type: application/json'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $terms = isset($_POST["terms"]);

    $errors = [];
    $response = ['success' => false, 'errors' => []]; 

    if (empty($username)) {
        $errors['username'] = 'Inserisci un username.';
    }
    if (empty($email)) {
        $errors['email'] = 'Inserisci un\'email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Inserisci un\'email valida.';
    }
    if (empty($password)) {
        $errors['password'] = 'Inserisci una password.';
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/', $password)) {
        $errors['password'] = 'La password deve avere 8-16 caratteri, 1 maiuscola e 1 numero.';
    }
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Le password non coincidono.';
    }
    if (!$terms) {
        $errors['terms'] = 'Devi accettare i termini e condizioni.';
    }

    if (!empty($errors)) {
        $response['errors'] = $errors;
        echo json_encode($response);
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM utenti WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $response['error'] = 'exists';
        echo json_encode($response);
        $stmt->close();
        exit;
    }
    $stmt->close();

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO utenti (username, email, password_) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['error'] = 'registration_failed';
    }
    $stmt->close();

    echo json_encode($response);

} else {
    $response['error'] = 'invalid_request';
    echo json_encode($response);
}

$conn->close();
?>

