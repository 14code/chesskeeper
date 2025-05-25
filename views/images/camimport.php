<h1>Partie erfassen (Foto + optional Daten)</h1>

<form method="post" enctype="multipart/form-data" action="/camimport">
    <fieldset>
        <legend>Fotos aufnehmen</legend>
        <div id="imageInputs">
            <input type="file" name="images[]" accept="image/*" capture required>
        </div>
        <button type="button" onclick="addImageInput()">Weiteres Foto aufnehmen</button>
    </fieldset>

    <fieldset>
        <legend>Spielinformationen (optional)</legend>
        <label>Weißspieler:<br>
            <input type="text" name="white">
        </label><br><br>

        <label>Schwarzspieler:<br>
            <input type="text" name="black">
        </label><br><br>

        <label>Turnier:<br>
            <input type="text" name="event">
        </label><br><br>

        <label>Runde:<br>
            <input type="text" name="round">
        </label><br><br>

        <label>Datum:<br>
            <input type="date" name="date" value="<?= date('Y-m-d') ?>">
        </label><br><br>

        <label>Ergebnis:<br>
            <select name="result">
                <option value="0">–</option>
                <option value="1">1–0</option>
                <option value="0.5">½–½</option>
                <option value="-1">0–1</option>
            </select>
        </label>
    </fieldset>

    <br>
    <button type="submit">Partie erfassen</button>
</form>

<script>
    function addImageInput() {
        const container = document.getElementById('imageInputs');
        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'images[]';
        input.accept = 'image/*';
        input.setAttribute('capture', '');
        container.appendChild(document.createElement('br'));
        container.appendChild(input);
    }
</script>

