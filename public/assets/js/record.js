
// record.js: board preview + PGN validation using chess.js + chessboard.js

let board = null;
const game = new Chess();

function updateBoard() {
    board.position(game.fen());
}

function updateStatus() {
    const statusEl = document.getElementById("status");
    if (game.game_over()) {
        statusEl.textContent = "Game over";
    } else {
        statusEl.textContent = "Current turn: " + game.turn().toUpperCase();
    }
}

function resetBoard() {
    game.reset();
    updateBoard();
    updateStatus();
}

function loadPGN() {
    const pgn = document.getElementById("pgnInput").value;
    const ok = game.load_pgn(pgn);
    if (!ok) {
        alert("Invalid PGN");
        return;
    }
    updateBoard();
    updateStatus();
}

// Initialize board
document.addEventListener("DOMContentLoaded", function () {
    board = Chessboard("board", {
        position: "start",
        draggable: false
    });

    document.getElementById("loadPGN").addEventListener("click", loadPGN);
    document.getElementById("reset").addEventListener("click", resetBoard);
});
