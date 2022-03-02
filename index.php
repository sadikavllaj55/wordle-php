<?php ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/wordle.css">
    <title>Wordle</title>
    <style>

    </style>
</head>
<body>
    <header id="main-header">
        <h1 class="header-title">Wordle</h1>
    </header>
    <div id="main-container">
        <div class="main-body">
            <div id="row1" class="word-row">
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
             </div>
            <div id="row2" class="word-row">
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
            </div>
            <div id="row3" class="word-row">
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
            </div>
            <div id="row4" class="word-row">
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
            </div>
            <div id="row5" class="word-row">
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
            </div>
            <div id="row6" class="word-row">
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
                <div class="element"></div>
            </div>
        </div>
        <div class="keyboard-container">
            <div class="keyboard-row">
                <div class="key">q</div>
                <div class="key">w</div>
                <div class="key">e</div>
                <div class="key">r</div>
                <div class="key">t</div>
                <div class="key">y</div>
                <div class="key">u</div>
                <div class="key">i</div>
                <div class="key">o</div>
                <div class="key">p</div>
            </div>
            <div class="keyboard-row">
                <div class="key">a</div>
                <div class="key">s</div>
                <div class="key">d</div>
                <div class="key">f</div>
                <div class="key">g</div>
                <div class="key">h</div>
                <div class="key">j</div>
                <div class="key">k</div>
                <div class="key">l</div>
            </div>
            <div class="keyboard-row">
                <div class="key" id="enter-key">enter</div>
                <div class="key">z</div>
                <div class="key">x</div>
                <div class="key">c</div>
                <div class="key">v</div>
                <div class="key">b</div>
                <div class="key">n</div>
                <div class="key">m</div>
                <div class="key" id="delete-key">&#9003;</div>
            </div>
        </div>
    </div>
<script>
    var current_row = 0;
    var letter_position = 0;
    var words = [];
    var wordsInStorage = window.localStorage.getItem('words');

    if (wordsInStorage !== null) {
        words = JSON.parse(wordsInStorage);
    }

    var key_buttons = document.querySelectorAll('.key');

    key_buttons.forEach(function(el) {
        el.onclick = function () {
            if (el.id === 'delete-key') {
                pressKey('backspace');
            } else {
                pressKey(el.textContent);
            }
        }
    });

    document.onkeydown = function (ev) {
        var keycode = ev.key;
        pressKey(keycode);
    }

    function pressKey(key) {
        key = validateInput(key);

        console.warn(key);

        if (key === false) {
            return;
        }

        if (key === 'enter') {
            submitWord();
            return;
        }

        if (key === 'backspace') {
            deleteLetter();
            return;
        }

        /**
         * 0. Validim i inputit
         * 1. Do selektojme elementin ku do vendoset shkronja (rreshti, kolona).
         * 2. Do shkruajme shkronjen ne pozicionin e duhur.
         * 3. Sipas kerkeses do rrisim kolonen dhe rreshtin.
         */
        var row = document.querySelector('.word-row:nth-child(' + (current_row + 1) + ')');
        var col = row.querySelector('.element:nth-child(' + (letter_position + 1) + ')');
        if (col !== null) {
            col.textContent = key;
            col.classList.add('written');
            letter_position++;
        }
    }

    function validateInput(key) {
        var letters = /^[A-Za-z]$/;
        if(key.match(letters)) { // Is single letter
            return key;
        } else if (key.toLowerCase() === 'enter') { // is Enter
            return 'enter';
        } else if (key.toLowerCase() === 'backspace') { // is Backspace
            return 'backspace';
        } else {
            return false;
        }
    }

    function submitWord() {
        /**
         * Enter pressed
         */
        if (letter_position >= 4) {
            var word = getCurrentWord();
            checkWord(word);
            words.push(word);
            letter_position = 0;
            current_row++;
        }

        window.localStorage.setItem('words', JSON.stringify(words));
    }

    function deleteLetter() {
        if (letter_position > 0) {
            var row = document.querySelector('.word-row:nth-child(' + (current_row + 1) + ')');
            var col = row.querySelector('.element:nth-child(' + (letter_position) + ')');
            col.textContent = '';
            col.classList.remove('written');
            letter_position--;
        }
    }

    function getCurrentWord() {
        var row = document.querySelector('.word-row:nth-child(' + (current_row + 1) + ')');
        var letterElements = row.querySelectorAll('.element');
        var word = '';
        letterElements.forEach(function (el) {
            word += el.textContent;
        });
        return word;
    }

    function writeCurrentWords() {
        for (var i = 0; i < words.length; i++) {
            var row = document.querySelector('.word-row:nth-child(' + (current_row + 1) + ')');
            var cols = row.querySelectorAll('.element');
            cols.forEach(function (el, ind) {
                el.textContent = words[i][ind];
                el.classList.add('written');
            });
            current_row++;
        }
    }

    function checkWord(word) {
        function reqListener () {
            var response = JSON.parse(this.responseText);
            console.log(response);
        }

        var oReq = new XMLHttpRequest();
        oReq.addEventListener('load', reqListener);
        oReq.open('GET', '/wordle.php?word=' + word);
        oReq.send();
    }

    writeCurrentWords();
</script>
</body>
</html>