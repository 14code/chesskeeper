
// record.js – PGN + Drag-and-Drop Eingabe

let board = null;
const game = new Chess();
let currentStep = 0;
let history = [];

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

function updatePGNField() {
    const partial = history.slice(0, currentStep);
    let pgn = '';
    for (let i = 0; i < partial.length; i++) {
        if (i % 2 === 0) pgn += `${Math.floor(i / 2) + 1}. `;
        pgn += partial[i].san + ' ';
    }
    document.getElementById("pgnInput").value = pgn.trim();
}



function resetBoard() {
    game.reset();
    updateBoard();
    updateStatus();
    updatePGNField();
}

function loadPGN() {
    const pgn = document.getElementById("pgnInput").value;
    const ok = game.load_pgn(pgn);
    if (!ok) {
        alert("Invalid PGN");
        return;
    }

    history = game.history({ verbose: true });
    currentStep = history.length;

    updateBoard();
    updateStatus();
    updatePGNField();
    updateMoveList();
}

function updateMoveList() {
    const listEl = document.getElementById("moveList");
    listEl.innerHTML = '';

    history.forEach((move, i) => {
        const li = document.createElement("li");
        li.textContent = `${i % 2 === 0 ? Math.floor(i / 2) + 1 + ". " : ""}${move.san}`;

        if (i === currentStep - 1) li.classList.add("current-move");

        // ✅ Zug klickbar machen
        li.style.cursor = 'pointer';
        li.addEventListener('click', () => {
            currentStep = i + 1;
            updateFromHistory();
        });

        listEl.appendChild(li);
    });
}




// === Drag and Drop ===

function handleDrop(source, target) {
    const move = {
        from: source,
        to: target,
        promotion: 'q'
    };

    // Spiel ab aktuellem Stand neu aufbauen
    const tempGame = new Chess();
    for (let i = 0; i < currentStep; i++) {
        tempGame.move(history[i]);
    }

    const result = tempGame.move(move);

    if (result === null) return "snapback";

    // Truncate History + hinzufügen
    history = history.slice(0, currentStep);
    history.push(result);
    currentStep++;

    // Apply
    game.load_pgn(tempGame.pgn());
    //board.position(game.fen());
    updateStatus();
    updatePGNField();
    updateMoveList();
}


function onSnapEnd() {
    const fen = board.fen();
    const expectedFen = game.fen();

    if (fen !== expectedFen) {
        board.position(expectedFen, false); // nur wenn nötig
    }
}



function updateFromHistory() {
    game.reset();
    for (let i = 0; i < currentStep; i++) {
        game.move(history[i]);
    }
    board.position(game.fen());
    updateStatus();
    updatePGNField();
    updateMoveList();
}

// === Init ===

document.addEventListener("DOMContentLoaded", function () {
    board = Chessboard("board", {
        position: "start",
        draggable: true,
        pieceTheme: "/assets/chess/pieces/cburnett/{piece}.svg",
        onDrop: handleDrop,
        onSnapEnd: onSnapEnd
    });

    document.getElementById("loadPGN").addEventListener("click", loadPGN);
    document.getElementById("reset").addEventListener("click", resetBoard);

    document.getElementById("prev").addEventListener("click", function () {
        if (currentStep > 0) {
            currentStep--;
            updateFromHistory();
        }
    });

    document.getElementById("next").addEventListener("click", function () {
        if (currentStep < history.length) {
            currentStep++;
            updateFromHistory();
        }
    });

    document.getElementById("truncateHistory").addEventListener("click", () => {
        history = history.slice(0, currentStep);
        updateFromHistory();
    });


    updateStatus();
    updatePGNField();
});


