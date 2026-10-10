<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['azione']) && $_POST['azione'] == 'aggiungi_amico') {
    $username_destinatario = isset($_POST['username_destinatario']) ? trim($_POST['username_destinatario']) : '';
    $response = ['success' => false, 'message' => ''];

    if (empty($username_destinatario)) {
        $response['message'] = "Campo username non può essere vuoto.";
    } else {
        require 'connessione.php';

        $stmt = $conn->prepare("SELECT id FROM utenti WHERE username = ?");
        $stmt->bind_param("s", $username_destinatario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $utente_id_destinatario = $row['id'];

            if ($utente_id_destinatario == $user_id) {
                $response['message'] = "Stai cercando di inviare una richiesta a te stesso.";
            } else {
                $stmt = $conn->prepare("SELECT id FROM amicizie WHERE (utente_id_richiedente = ? AND utente_id_ricevente = ?) OR (utente_id_richiedente = ? AND utente_id_ricevente = ?)");
                $stmt->bind_param("iiii", $user_id, $utente_id_destinatario, $utente_id_destinatario, $user_id);
                $stmt->execute();
                $result_esistenza = $stmt->get_result();

                if ($result_esistenza->num_rows > 0) {
                    $response['message'] = "Esiste già una richiesta di amicizia o siete già amici.";
                } else {
                    $stmt = $conn->prepare("INSERT INTO amicizie (utente_id_richiedente, utente_id_ricevente) VALUES (?, ?)");
                    $stmt->bind_param("ii", $user_id, $utente_id_destinatario);
                    if ($stmt->execute()) {
                        $response['success'] = true;
                        $response['message'] = "Richiesta di amicizia inviata con successo a " . htmlspecialchars($username_destinatario);
                    } else {
                        $response['message'] = "Errore durante l'invio della richiesta di amicizia.";
                    }
                }
            }
        } else {
            $response['message'] = "Username non registrato.";
        }

        $stmt->close();
        $conn->close();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Amico</title>
    <link rel="stylesheet" href="modifica_profilo.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
</head>
<body>
    <div class="profile-container">
        <h1>Aggiungi un Amico</h1>

        <div id="aggiunta_result"></div>
        <form id="aggiungiAmicoForm">
            <label for="username_destinatario">Nome utente dell'amico:</label>
            <input type="text" id="username_destinatario" name="username_destinatario" list="usernamesDatalist">
            <datalist id="usernamesDatalist"></datalist> <button type="submit">Invia richiesta</button>
        </form>

        <div class="centered-button">
            <button class="animated-button" onclick="window.location.href='index.php'">
                <svg viewBox="0 0 24 24" class="arr-2" xmlns="http://www.w3.org/2000/svg">
                    <path
                    d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"
                    ></path>
                </svg>
                <span class="text">Torna alla Home</span>
                <span class="circle"></span>
                <svg viewBox="0 0 24 24" class="arr-1" xmlns="http://www.w3.org/2000/svg">
                    <path
                    d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"
                    ></path>
                </svg>
            </button>
        </div>
    </div>
    <script src="aggiungi_amico.js"></script>
</body>
</html>
