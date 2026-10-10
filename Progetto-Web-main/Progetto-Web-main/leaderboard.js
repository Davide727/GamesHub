function updateLeaderboard() {
    fetch('get_leaderboard_data.php')
        .then(response => response.json())
        .then(data => {
            const leaderboardBody = document.getElementById('leaderboard-body');
            leaderboardBody.innerHTML = ''; 

            data.forEach((user, index) => {
                const row = leaderboardBody.insertRow();
                const rankCell = row.insertCell();
                const playerCell = row.insertCell();
                const gamesCell = row.insertCell();

                rankCell.textContent = index + 1;
                playerCell.textContent = user.username;
                gamesCell.textContent = user.total_partite;
            });
        })
        .catch(error => {
            console.error('Errore nel recupero dei dati della leaderboard:', error);
        });
}

document.addEventListener('DOMContentLoaded', updateLeaderboard);

setInterval(updateLeaderboard, 5000); 