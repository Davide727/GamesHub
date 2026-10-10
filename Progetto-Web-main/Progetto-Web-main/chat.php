<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: lista_amici.php");
    exit();
}

$amico_id = intval($_GET['id']);
$amico_username = isset($_GET['username']) ? htmlspecialchars(urldecode($_GET['username'])) : 'Sconosciuto';
$user_id = $_SESSION['user_id'];

require 'connessione.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['messaggio']) && isset($_POST['destinatario_id'])) {
    $messaggio = trim($_POST['messaggio']);
    $destinatario_id = intval($_POST['destinatario_id']);

    if (!empty($messaggio)) {
        $stmt = $conn->prepare("INSERT INTO messaggi (mittente_id, destinatario_id, testo) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $destinatario_id, $messaggio);
        $stmt->execute();
        $stmt->close();
    }
    exit(); 
}

$stmt_messaggi = $conn->prepare("SELECT m.testo, m.mittente_id, u.username
                                FROM messaggi m
                                JOIN utenti u ON m.mittente_id = u.id
                                WHERE (m.mittente_id = ? AND m.destinatario_id = ?)
                                   OR (m.mittente_id = ? AND m.destinatario_id = ?)
                                ORDER BY m.timestamp ASC");
$stmt_messaggi->bind_param("iiii", $user_id, $amico_id, $amico_id, $user_id);
$stmt_messaggi->execute();
$result_messaggi = $stmt_messaggi->get_result();
$messaggi = $result_messaggi->fetch_all(MYSQLI_ASSOC);
$stmt_messaggi->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat con <?php echo $amico_username; ?></title>
    <link rel="stylesheet" href="chat.css">
    <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
</head>
<body>
    <div class="container">
        <h1>Chat con <?php echo $amico_username; ?></h1>

        <div class="chat-container" id="chat-log">
            <?php foreach ($messaggi as $messaggio): ?>
                <div class="message <?php echo ($messaggio['mittente_id'] == $user_id) ? 'me' : 'other'; ?>">
                    <strong><?php echo htmlspecialchars($messaggio['username']); ?>:</strong> <?php echo htmlspecialchars($messaggio['testo']); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="input-area">
            <input type="text" id="message-input" placeholder="Scrivi un messaggio...">
            <button id="inviaBtn" onclick="inviaMessaggio(<?php echo $amico_id; ?>)">Invia</button>
            <script>
                const inviaBtn = document.getElementById('inviaBtn');
                document.addEventListener('keydown', function(event) {
                    if (event.keyCode === 13 || event.code === 'Enter') {
                        event.preventDefault();
                        inviaBtn.click();
                    }
                });
            </script>
        </div>

        <a href="lista_amici.php" class="btn-back">Torna alla Lista Amici</a>
    </div>

    <script>
        const chatLog = document.getElementById('chat-log');
        let userScrolledUp = false; 

        chatLog.addEventListener('scroll', function() {
            if (chatLog.scrollHeight - chatLog.scrollTop > chatLog.clientHeight + 10) {
                userScrolledUp = true;
            } else {
                userScrolledUp = false; 
            }
        });

        function inviaMessaggio(amicoId) {
            const messaggioInput = document.getElementById('message-input');
            const messaggio = messaggioInput.value.trim();

            if (messaggio !== "") {
                const formData = new FormData();
                formData.append('messaggio', messaggio);
                formData.append('destinatario_id', amicoId);

                fetch('chat.php?id=<?php echo $amico_id; ?>&username=<?php echo urlencode($amico_username); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        messaggioInput.value = "";
                        caricaMessaggi(amicoId, true); 
                    } else {
                        console.error('Errore durante l\'invio del messaggio.');
                    }
                })
                .catch(error => {
                    console.error('Errore di rete durante l\'invio del messaggio:', error);
                });
            }
        }

        function caricaMessaggi(amicoId, forceScroll = false) {
            fetch('chat.php?id=<?php echo $amico_id; ?>&username=<?php echo urlencode($amico_username); ?>')
            .then(response => response.text())
            .then(data => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data;
                const newChatLog = tempDiv.querySelector('#chat-log').innerHTML;

                if (chatLog.innerHTML !== newChatLog) {
                    chatLog.innerHTML = newChatLog;

                    if (!userScrolledUp || forceScroll) {
                        chatLog.scrollTop = chatLog.scrollHeight;
                        userScrolledUp = false; 
                    }
                }
            })
            .catch(error => {
                console.error('Errore durante il caricamento dei messaggi:', error);
            });
        }

        setInterval(() => {
            caricaMessaggi(<?php echo $amico_id; ?>);
        }, 3000); 

        document.addEventListener('DOMContentLoaded', () => {
            chatLog.scrollTop = chatLog.scrollHeight;
        });
    </script>
</body>
</html>