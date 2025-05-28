<!-- partials/record-board.php -->
<div style="max-width: 420px; margin: 0 auto;">

<div id="board" style="width: 400px; margin: 0 auto;"></div>

<div id="moveListContainer">
  <h4 style="text-align:center;">Move History</h4>
  <ul id="moveList" class="move-list"></ul>
  <button id="truncateHistory" style="display: block; margin: 10px auto;">Delete from here</button>
</div>

<div id="controls" style="text-align: center; margin-top: 10px;">
  <button id="prev">←</button>
  <button id="next">→</button>
  <button id="loadPGN">Load PGN</button>
  <button id="reset">Reset</button>
  <p id="status">Board status will appear here.</p>
</div>
    
</div>

<link rel="stylesheet" href="/vendor/chess/chessboard-1.0.0.min.css">
<style>
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
    cursor: pointer;
  }

  ul.move-list li.current-move {
    background: #2c3e50;
    color: white;
    font-weight: bold;
  }
</style>

<script src="/vendor/jquery/jquery.min.js"></script>
<script src="/vendor/chess/chess.min.js"></script>
<script src="/vendor/chess/chessboard-1.0.0.min.js"></script>
<script src="/assets/js/record.js?v=<?=time();?>"></script>