<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chesskeeper - Record Game</title>
  <link rel="stylesheet" href="/vendor/chess/chessboard-1.0.0.min.css">
  <style>
    #board { width: 400px; margin: 20px auto; }
    #controls { text-align: center; margin-top: 10px; }
    #pgnInput { width: 90%; margin: 10px auto; display: block; }
    #moveListContainer {
        text-align: center;
        margin: 20px auto;
    }

    ul.move-list {
        list-style: none;
        padding: 0;
        margin: 10px 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 6px;
        font-family: monospace;
        font-size: 14px;
    }

    ul.move-list li {
        background: #f4f4f4;
        padding: 3px 6px;
        border-radius: 4px;
    }

    ul.move-list li.current-move {
        background: #2c3e50;
        color: white;
        font-weight: bold;
    }


  </style>
</head>
<body>

  <h2 style="text-align:center;">Record Game</h2>
  <div id="board"></div>

  <div id="moveListContainer">
      <h4>Move History</h4>
      <ul id="moveList" class="move-list"></ul>
      <button id="truncateHistory">Delete from here</button>
  </div>
  

  <textarea id="pgnInput" rows="5" placeholder="Enter PGN moves here..."></textarea>

  <div id="controls">
    <button id="prev">←</button>
    <button id="next">→</button>
    <button id="loadPGN">Load PGN</button>
    <button id="reset">Reset</button>
    <p id="status">Board status will appear here.</p>
  </div>

  <script src="/vendor/jquery/jquery.min.js"></script>
  
  <script src="/vendor/chess/chess.min.js"></script>
  
  <script src="/vendor/chess/chessboard-1.0.0.min.js"></script>



  <script src="/assets/js/record.js?v=<?=time();?>"></script>

</body>
</html>
