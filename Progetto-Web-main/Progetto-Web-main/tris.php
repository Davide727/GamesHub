<?php
session_start();
include 'connessione.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

function creaUtenteTrisSeNonEsiste($conn, $user_id, $username) {
    $sql_check = "SELECT id FROM tris_utenti WHERE id = $user_id";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows == 0) {
        $sql_insert = "INSERT INTO tris_utenti (id, username, partite_giocate) VALUES ($user_id, '$username', 0)"; 
        if ($conn->query($sql_insert) === TRUE) {
        } else {
            echo "Errore nella creazione dell'utente Tris: " . $conn->error;
        }
    }
}

creaUtenteTrisSeNonEsiste($conn, $user_id, $username);

function creaNuovaPartita($conn) {
    $tabella = '---------'; 
    $sql = "INSERT INTO tris_partite (tabella, giocatore1_id, giocatore1_username, turno) VALUES 
          ('$tabella', " . $_SESSION['user_id'] . ", '" . $_SESSION['username'] . "', 1)";
    if ($conn->query($sql) === TRUE) {
        return $conn->insert_id;
    } else {
        return false;
    }
}

function cercaPartitaDisponibile($conn) {
    $sql = "SELECT id FROM tris_partite WHERE giocatore2_id IS NULL AND giocatore1_id != " . $_SESSION['user_id'] . " LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    }
    return false;
}

function uniscitiAPartita($conn, $partita_id, $user_id) {
    $sql_update_partita = "UPDATE tris_partite SET 
    giocatore2_id = " . $user_id . ", giocatore2_username = '" . $_SESSION['username'] . "' WHERE id = $partita_id AND giocatore2_id IS NULL";
    return $conn->query($sql_update_partita);
}

$partita_id = null;

if (isset($_POST['nuova_partita'])) {
    $nuova_partita_id = creaNuovaPartita($conn);
    if ($nuova_partita_id) {
        header("Location: tris.php?partita_id=" . $nuova_partita_id); 
        exit();
    } else {
        echo "<p>Errore nella creazione della nuova partita.</p>";
    }
} elseif (isset($_POST['unisciti_partita'])) {
    $partita_id_da_unire = cercaPartitaDisponibile($conn);
    if ($partita_id_da_unire) {
        if (uniscitiAPartita($conn, $partita_id_da_unire, $user_id)) {
            header("Location: tris.php?partita_id=" . $partita_id_da_unire); 
            exit();
        } else {
            echo "<p>Impossibile unirsi alla partita selezionata.</p>";
        }
    }
} elseif (isset($_GET['partita_id'])) {
    $partita_id = $_GET['partita_id'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tris Online Semplice</title>
    <link rel="stylesheet" href="tris.css">
    <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (isset($_GET['partita_id'])): ?>
    <script>
        const partitaId = <?php echo json_encode($partita_id); ?>;
        const userId = <?php echo json_encode($user_id); ?>;
        let tabella = '<?php echo $stato_partita['tabella'] ?? '---------'; ?>';
        let turno = <?php echo json_encode($stato_partita['turno'] ?? 1); ?>;
        let giocatore1Id = <?php echo json_encode($stato_partita['giocatore1_id'] ?? null); ?>;
        let giocatore2Id = <?php echo json_encode($stato_partita['giocatore2_id'] ?? null); ?>;
        let vincitorePartita = <?php echo json_encode($stato_partita['vincitore'] ?? 0); ?>; 
    </script>
    <?php endif; ?>
</head>
<body>
    <a href="games.php" class="exit-btn">Esci</a>
    <div class="container">
        <h1>Tris Online</h1>
        <?php if (!isset($_GET['partita_id'])): ?>
            <div class="button-group">
                <form method='post'><button type='submit' name='nuova_partita'>Crea Nuova Partita</button></form>
                <form method='post'><button type='submit' name='unisciti_partita'>Unisciti a Partita Esistente</button></form>
                <a href="visualizza_statistiche_tris.php" class="stats-btn">Visualizza Statistiche</a>
            </div>
        <?php else: ?>
        <div class="board">
            <div class="cell" data-index="0"></div>
            <div class="cell" data-index="1"></div>
            <div class="cell" data-index="2"></div>
            <div class="cell" data-index="3"></div>
            <div class="cell" data-index="4"></div>
            <div class="cell" data-index="5"></div>
            <div class="cell" data-index="6"></div>
            <div class="cell" data-index="7"></div>
            <div class="cell" data-index="8"></div>
        </div>
        <div id="message"></div>
        <button id="nuovaPartitaButton">Torna alla Scelta</button>
        <?php endif; ?>
    </div>
    <?php if (isset($_GET['partita_id'])): ?>
    <script src="tris.js"></script>
    <?php endif; ?>
</body>
</html>