<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_message = "";
$error_message = "";

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

require 'connessione.php';

$stmt = $conn->prepare("SELECT a.id AS richiesta_id, u.username AS username_richiedente
                        FROM amicizie a
                        JOIN utenti u ON a.utente_id_richiedente = u.id
                        WHERE a.utente_id_ricevente = ? AND a.stato = 'inviata'
                        ORDER BY a.data_richiesta DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$richieste_result = $stmt->get_result();
$richieste_ricevute = $richieste_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Richieste Ricevute</title>
  <link rel="stylesheet" href="modifica_profilo.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
</head>
<body>
  <div class="profile-container">
    <h1>Richieste Ricevute</h1>

        <?php if (!empty($success_message)): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Successo!',
                    text: '<?php echo $success_message; ?>',
                    timer: 1500,
                    showConfirmButton: false
                });
            </script>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: '<?php echo $error_message; ?>',
                });
            </script>
        <?php endif; ?>

        <?php if (empty($richieste_ricevute)): ?>
            <p>Non hai nuove richieste di amicizia.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($richieste_ricevute as $richiesta): ?>
                    <li class="amico-riga">
                        Richiesta da <strong><?php echo htmlspecialchars($richiesta['username_richiedente']); ?></strong>
                        <button class="styled-button" onclick="accettaAmicizia(<?php echo $richiesta['richiesta_id']; ?>)">Accetta</button>
                        <button class="styled-button" onclick="rifiutaAmicizia(<?php echo $richiesta['richiesta_id']; ?>)">Rifiuta</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="centered-button">
            <button class="animated-button" onclick="window.location.href='index.php'">
                <svg viewBox="0 0 24 24" class="arr-2" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"/>
                </svg>
                <span class="text">Torna alla Home</span>
                <span class="circle"></span>
                <svg viewBox="0 0 24 24" class="arr-1" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"/>
                </svg>
            </button>
            </div>
        </div>
    </div>

    <script>
        function accettaAmicizia(richiestaId) {
            window.location.href = 'accetta_amicizia.php?id=' + richiestaId;
        }

        function rifiutaAmicizia(richiestaId, usernameRichiedente) {
            Swal.fire({
                title: 'Sei sicuro?',
                text: `Vuoi rifiutare la richiesta di amicizia da ${usernameRichiedente}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sì, rifiuta!',
                cancelButtonText: 'Annulla'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'rifiuta_amicizia.php?id=' + richiestaId;
                }
            })
        }
    </script>
</body>
</html>