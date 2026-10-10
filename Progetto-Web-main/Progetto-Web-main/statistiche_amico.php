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
$user_id = $_SESSION['user_id']; 

require 'connessione.php';

$stmt_utente = $conn->prepare("SELECT username FROM utenti WHERE id = ?");
$stmt_utente->bind_param("i", $amico_id);
$stmt_utente->execute();
$result_utente = $stmt_utente->get_result();
if ($result_utente->num_rows !== 1) {
    header("Location: lista_amici.php");
    exit();
}
$amico_data = $result_utente->fetch_assoc();
$amico_username = htmlspecialchars($amico_data['username']);
$stmt_utente->close();

$stmt_tris = $conn->prepare("SELECT partite_giocate, partite_vinte, partite_perse, partite_pareggiate FROM tris_utenti WHERE id = ?");
$stmt_tris->bind_param("i", $amico_id);
$stmt_tris->execute();
$tris_result = $stmt_tris->get_result();
$tris_stats = $tris_result ? $tris_result->fetch_assoc() : null;
$stmt_tris->close();

$stmt_milionario = $conn->prepare("SELECT partite_giocate, massimo_vinto FROM milionario_utenti WHERE id = ?");
$stmt_milionario->bind_param("i", $amico_id);
$stmt_milionario->execute();
$milionario_result = $stmt_milionario->get_result();
$milionario_stats = $milionario_result ? $milionario_result->fetch_assoc() : null;
$stmt_milionario->close();

$stmt_battaglia_navale = $conn->prepare("SELECT numero_partite, vittorie, sconfitte FROM battaglia_navale_utenti WHERE id = ?");
$stmt_battaglia_navale->bind_param("i", $amico_id);
$stmt_battaglia_navale->execute();
$battaglia_navale_result = $stmt_battaglia_navale->get_result();
$battaglia_navale_stats = $battaglia_navale_result ? $battaglia_navale_result->fetch_assoc() : null;
$stmt_battaglia_navale->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiche di <?php echo $amico_username; ?></title>
    <link rel="stylesheet" href="statistiche.css">
    <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
</head>
<body>
    <div class="container">
        <h1>Statistiche di <?php echo $amico_username; ?></h1>
        <div class="stats-container">
            <div class="stats-box">
                <h2>Tris</h2>
                <?php if ($tris_stats): ?>
                    <p>Partite Giocate: <?php echo $tris_stats['partite_giocate']; ?></p>
                    <p>Partite Vinte: <?php echo $tris_stats['partite_vinte']; ?></p>
                    <p>Partite Perse: <?php echo $tris_stats['partite_perse']; ?></p>
                    <p>Partite Pareggiate: <?php echo $tris_stats['partite_pareggiate']; ?></p>
                <?php else: ?>
                    <p><?php echo $amico_username; ?> Non ha ancora giocato a Tris.</p>
                <?php endif; ?>
            </div>

            <div class="stats-box">
                <h2>Milionario</h2>
                <?php if ($milionario_stats): ?>
                    <p>Partite Giocate: <?php echo $milionario_stats['partite_giocate']; ?></p>
                    <p>Massimo Vinto: <?php echo $milionario_stats['massimo_vinto']; ?></p>
                <?php else: ?>
                    <p><?php echo $amico_username; ?> Non ha ancora giocato a Il Milionario.</p>
                <?php endif; ?>
            </div>

            <div class="stats-box">
                <h2>Battaglia Navale</h2>
                <?php if ($battaglia_navale_stats): ?>
                    <p>Partite Giocate: <?php echo $battaglia_navale_stats['numero_partite']; ?></p>
                    <p>Vittorie: <?php echo $battaglia_navale_stats['vittorie']; ?></p>
                    <p>Sconfitte: <?php echo $battaglia_navale_stats['sconfitte']; ?></p>
                <?php else: ?>
                    <p><?php echo $amico_username; ?> Non ha ancora giocato a Battaglia Navale.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="button-container">
            <a href="lista_amici.php">Torna alla Lista Amici</a>
        </div>
    </div>
</body>
</html>