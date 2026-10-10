<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

require 'connessione.php';

$stmt = $conn->prepare("SELECT u.id, u.username
                        FROM amicizie a
                        JOIN utenti u ON (a.utente_id_richiedente = u.id OR a.utente_id_ricevente = u.id)
                        WHERE a.stato = 'accettata'
                        AND (a.utente_id_richiedente = ? OR a.utente_id_ricevente = ?)
                        AND u.id != ?");
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$amici_result = $stmt->get_result();
$lista_amici = $amici_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();

$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : "";
unset($_SESSION['success_message']);

$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : "";
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista Amici</title>
  <link rel="stylesheet" href="modifica_profilo.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
</head>
<body>
  <div class="profile-container">
    <h1>Lista Amici</h1>

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

        <?php if (empty($lista_amici)): ?>
            <p>Non hai ancora amici.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($lista_amici as $amico): ?>
                    <li>
                        <?php echo htmlspecialchars($amico['username']); ?>
                        <button class="styled-button" onclick="rimuoviAmico(<?php echo $amico['id']; ?>, '<?php echo htmlspecialchars($amico['username']); ?>')">Rimuovi Amico</button>
                        <button class="styled-button" onclick="visualizzaStatistiche(<?php echo $amico['id']; ?>)">Statistiche</button>
                        <button class="styled-button" onclick="apriChat(<?php echo $amico['id']; ?>, '<?php echo htmlspecialchars($amico['username']); ?>')">Chat</button>
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

    <script>
        function rimuoviAmico(amicoId, amicoUsername) {
            Swal.fire({
                title: 'Sei sicuro?',
                text: `Vuoi rimuovere ${amicoUsername} dalla tua lista amici?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sì, rimuovi!',
                cancelButtonText: 'Annulla'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'rimuovi_amico.php?id=' + amicoId;
                }
            })
        }

        function visualizzaStatistiche(amicoId) {
            window.location.href = 'statistiche_amico.php?id=' + amicoId;
        }

        function apriChat(amicoId, amicoUsername) {
            window.location.href = 'chat.php?id=' + amicoId + '&username=' + encodeURIComponent(amicoUsername);
        }
    </script>
</body>
</html>