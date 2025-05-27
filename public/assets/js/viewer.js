
// viewer.js: read-only board that plays through moves

let board = null;
const game = new Chess();
let moves = [];

function playMove(index) {
    game.reset();
    for (let i = 0; i <= index; i++) {
        game.move(moves[i]);
    }
    board.position(game.fen());
    document.getElementById("moveIndex").textContent = (index + 1) + "/" + moves.length;
}

function nextMove() {
    if (current < moves.length - 1) {
        current++;
        playMove(current);
    }
}

function prevMove() {
    if (current > 0) {
        current--;
        playMove(current);
    }
}

let current = 0;

document.addEventListener("DOMContentLoaded", function () {
    const pgn = document.getElementById("pgnText").textContent;
    game.load_pgn(pgn);
    moves = game.history();
    board = Chessboard("viewerBoard", {
        position: "start",
        draggable: false
    });
    playMove(current);

    document.getElementById("next").addEventListener("click", nextMove);
    document.getElementById("prev").addEventListener("click", prevMove);
});
