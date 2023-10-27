<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Assignment 4</title>
    <!-- Link to Bootstrap's CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- Link to my google font(s) (CSS) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400;700&family=Space+Grotesk:wght@400;700&display=swap" rel="stylesheet">
    <!-- Link to my stylesheet -->
    <link rel="stylesheet" href="styles.css">
    <!-- Importing main.js script -->
    <script src="main.js"></script>
</head>

<body>
    <div id="theBody">
        <h1 id="mainHeading">Retro Game Store</h1>
        <button id="loadButton" class="btn-success mainBtn">Load Games</button>
        <!-- panel for the 3 main CRUD buttons - add, delete, and update -->
        <div id="btnPanel">
            <button id="addButton" class="btn-success mainBtn">Add Game</button>
            <button id="deleteButton" class="btn-success mainBtn" disabled>Delete Game</button>
            <button id="updateButton" class="btn-success mainBtn" disabled>Update Game</button>
        </div>
        <!-- panel with values for an add or update -->
        <div id="addAndUpdatePanel" class="hidden">
            <div class="inputContainer">
                <div class="inputLabel">Game ID:</div>
                <div class="inputField">
                    <input id="gameID" name="gameID" type="number" min="100" max="999" />
                </div>
            </div>
            <div class="inputContainer">
                <div class="inputLabel">Name:</div>
                <div class="inputField">
                    <input id="name" name="name" />
                </div>
            </div>
            <div class="inputContainer">
                <div class="inputLabel">Platform:</div>
                <div class="inputField">
                    <input id="platform" name="platform" />
                </div>
            </div>
            <div class="inputContainer">
                <div class="inputLabel">Release Date</div>
                <div class="inputField">
                    <input id="releaseDate" name="releaseDate" placeholder="YYYY-MM-DD" />
                </div>
            </div>
            <div class="inputContainer">
                <div class="inputLabel">Price:</div>
                <div class="inputField">
                    <input id="price" name="price" type="number" min="0" max="9999" step='0.01' />
                </div>
            </div>
            <div class="inputContainer">
                <div class="inputLabel">&nbsp;</div>
                <div class="inputField">
                    <button id="completeButton" class="btn-success mainBtn">Complete</button>
                    <button id="cancelButton" class="btn-success mainBtn">Cancel</button>
                </div>
            </div>
        </div>
        <!-- div to act as a container for the table itself - that will be inserted in main.js -->
        <div id="tableContainer">
        </div>
    </div>
</body>

</html>