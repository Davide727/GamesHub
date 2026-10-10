<?php

require 'connessione.php';

if ($conn->connect_error) {
    exit;
}

$leaderboardData = array();

$sql = "SELECT username, SUM(partite) AS total_partite
        FROM (
            SELECT username, partite_giocate AS partite FROM tris_utenti
            UNION ALL
            SELECT username, partite_giocate AS partite FROM milionario_utenti
            UNION ALL
            SELECT username, numero_partite AS partite FROM battaglia_navale_utenti
        ) AS combined_games
        GROUP BY username
        ORDER BY total_partite DESC
        LIMIT 3";

$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $leaderboardData[] = $row;
    }
    $result->free();
} else {
    echo "Errore nell'esecuzione della query: " . $conn->error;
    exit;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($leaderboardData);
?>