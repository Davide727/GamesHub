<?php
session_start();

include 'connessione.php';

header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $response = ['success' => false, 'error' => null]; 

    if (empty($username)) {
        $response['error'] = 'empty_username';
        echo json_encode($response);
        exit;
    }

    if (empty($password)) {
        $response['error'] = 'empty_password';
        echo json_encode($response);
        exit;
    }

    $passwordPattern = '/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/';
    if (!preg_match($passwordPattern, $password)) {
        $response['error'] = 'invalid_password';
        echo json_encode($response);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, username, password_ FROM utenti WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $password_);
        $stmt->fetch();

        if ($password_ !== null && password_verify($password, $password_)) { 
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $response['success'] = true;

            if ($remember) {
                $remember_token = uniqid();

                $stmt_update = $conn->prepare("UPDATE utenti SET remember_token = ? WHERE id = ?");
                $stmt_update->bind_param("si", $remember_token, $id);
                $stmt_update->execute();
                $stmt_update->close();

                setcookie('remember_token', $remember_token, time() + (86400 * 30), '/'); 
            }
        } else {
            $response['error'] = 'password';
        }
        $stmt->close();
    } else {
        $response['error'] = 'notexists';
    }

    echo json_encode($response);
} else {
    $response = ['success' => false, 'error' => 'invalid_request'];
    echo json_encode($response);
}

$conn->close();
?>