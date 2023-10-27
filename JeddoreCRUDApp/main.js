//global variable to keep track of when the done btn is clicked, are we doing an add or update?
//variable will change like "add" or "update", based on which btn is clicked
let addOrUpdate;

window.onload = function () {

    //getAllItems ftn for the load button
    document.querySelector("#loadButton").addEventListener("click", getAllGames);

    //need event handlers for these buttons
    document.querySelector("#addButton").addEventListener("click", addGame);

    document.querySelector("#deleteButton").addEventListener("click", deleteGame);

    document.querySelector("#updateButton").addEventListener("click", updateGame);

    document.querySelector("#completeButton").addEventListener("click", processForm);

    document.querySelector("#cancelButton").addEventListener("click", hideUpdatePanel);

    //add event handler for selections in the table container
    document.querySelector("#tableContainer").addEventListener("click", handleRowClick);

    hideUpdatePanel();

    //call this ftn, sending in a false
    setDeleteUpdateButtonState(false);
};

//make AJAX call to get JSON data
function getAllGames() {
    let url = "api/getAllGames.php"; //file name or server-side process name
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {

            //if status code is 200
            if (xhr.status === 200) {
                let resp = xhr.responseText;

                //log the response
                //console.log(resp);

                if (resp.search("ERROR") >= 0) {
                    alert("Retrieval Error: Unable to retrieve games...");
                }

                else {
                    //build the table
                    buildTable(xhr.responseText);
                    setDeleteUpdateButtonState(false);
                }
            }

            //else - status code not 200
            else {
                alert("Received status code " + xhr.status);
            }
        }
    };
    xhr.open("GET", url, true);
    xhr.send();
}

// text is a JSON string containing an array
function buildTable(text) {

    //getting an array of objects using JSON.parse
    let arrayOfObjects = JSON.parse(text);
    let html =
        "<table><tr><th>Game ID</th><th>Name</th><th>Platform</th><th>Release Date</th><th>Price</th></tr>";
    for (let i = 0; i < arrayOfObjects.length; i++) {
        let row = arrayOfObjects[i];
        html += "<tr><td>" + row.gameID + "</td>";
        html += "<td>" + row.name + "</td>";
        html += "<td>" + row.platform + "</td>";
        html += "<td>" + row.releaseDate + "</td>";
        html += "<td>" + row.price + "</td></tr>";
    }
    html += "</table>";
    let theTable = document.querySelector("#tableContainer");
    theTable.innerHTML = html;
}

function addGame() {
    //get the input for the gameID
    let gameID = document.querySelector("#gameID");

    //remove the readonly attribute from the gameID input if add game btn is clicked
    gameID.removeAttribute("readonly", "readonly");
    addOrUpdate = "add";
    clearUpdatePanel();
    showUpdatePanel();
}

function updateGame() {
    //get the input for the gameID
    let gameID = document.querySelector("#gameID");

    //set the gameID input to readonly if update game btn is clicked
    gameID.setAttribute("readonly", "readonly")
    addOrUpdate = "update";

    //populate the panel (because it's an update)
    populateUpdatePanel();
    showUpdatePanel();
}

function deleteGame() {

    //find the first element (row) in the document with this selectedRow class attached to it
    //should be only one
    let row = document.querySelector(".selectedRow");

    //get the content of the first TD tag for that row
    //let gameID = Number(row.querySelectorAll("#tableContainer td")[0].innerHTML);
    let gameID = Number(row.querySelector("#tableContainer td").innerHTML);

    //AJAX call below
    //sending data using the URL method
    //browser will create an item parameter called gameID, tacked onto the URL
    //can specify a URL here, or a file name (ex. .html, .json, .css, .php, etc.)
    //Apache - if it's a PHP file then it's a program file, and it will run that program and display the echo statements
    let url = "api/deleteGame.php/?gameID=" + gameID;
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let resp = xhr.responseText;

            //want to display different msg depending on the response received
            //browser should send either a 1 or 0
            //the 1 or 0 is converted to a string over HTTP
            if (resp === "1") {
                alert("Game deleted.");
            }

            else if (resp === "0") {
                alert("Game was NOT deleted.");
            }

            //some kind of error occurred
            else {
                alert("Server Error!");
            }

            //rebuild the gui
            getAllGames();
        }
    };
    xhr.open("GET", url, true); // will improve in REST version
    xhr.send();
}

//ftn called when the "Complete" button is pressed for either an Add or Update
function processForm() {
    console.log("OK");
    //get data/values from the form
    let gameID = Number(document.querySelector("#gameID").value);
    let name = document.querySelector("#name").value;
    let platform = document.querySelector("#platform").value;
    let releaseDate = document.querySelector("#releaseDate").value;
    let price = Number(document.querySelector("#price").value);

    //creating a plain JS object
    let plainObject = {
        gameID: gameID,
        name: name,
        platform: platform,
        releaseDate: releaseDate,
        price: price
    };

    //make AJAX call to add or update the record in the database.
    //will add or update based on what the global addOrUpdate variable is
    //that variable is set based on which btn is clicked
    let url = addOrUpdate === "add" ? "api/addGame.php" : "api/updateGame.php";
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {

        //if status code is 200
        if (xhr.readyState === 4 && xhr.status === 200) {
            let resp = xhr.responseText;
            console.log(resp, resp.length);
            console.log(xhr);

            //browser should send either a 1 or 0
            //the 1 or 0 is converted to a string over HTTP
            if (resp === "1") {
                alert("Game " + (addOrUpdate === "add" ? "added." : "updated."));
            }

            else if (resp === "0") {
                alert("Game NOT " + (addOrUpdate === "add" ? "added." : "updated."));
            }

            //some kind of error occurred
            else {
                alert("Server Error!");
            }
            hideUpdatePanel();
            getAllGames();
        }
    };

    //this must be POST because we need to send data
    xhr.open("POST", url, true);

    //request payload
    //passes the data (plain object) as part of the request body
    //will be decoded from JSON format later in addGame.php and updateGame.php
    xhr.send(JSON.stringify(plainObject));
}

function setGameIDFieldState(val) {
    let gameIDInput = document.querySelector("#gameID");
    if (val) {
        gameIDInput.removeAttribute("disabled");
    } else {
        gameIDInput.setAttribute("disabled", "disabled");
    }
}

function hideUpdatePanel() {

    //add the hidden class to the add update panel
    document.querySelector("#addAndUpdatePanel").classList.add("hidden");
}

function showUpdatePanel() {

    //remove the hidden class from the add update panel
    document.querySelector("#addAndUpdatePanel").classList.remove("hidden");
}

function clearUpdatePanel() {

    //reset the values in the inputs of the add update panel
    document.querySelector("#gameID").value = 0;
    document.querySelector("#name").value = "";
    document.querySelector("#platform").value = "";
    document.querySelector("#releaseDate").value = "";
    document.querySelector("#price").value = 0;
}

//add and update are using the same update panel
function populateUpdatePanel() {
    let selectedRowItem = document.querySelector(".selectedRow");

    //console.log(selectedRowItem);

    if (selectedRowItem !== null) {

        //first td cell of the selected row
        let gameID = Number(selectedRowItem.querySelectorAll("#tableContainer td")[0].innerHTML);

        //second td cell
        let name = selectedRowItem.querySelectorAll("#tableContainer td")[1].innerHTML;

        //third td cell
        let platform = selectedRowItem.querySelectorAll("#tableContainer td")[2].innerHTML;

        //fourth td cell
        let releaseDate = selectedRowItem.querySelectorAll("#tableContainer td")[3].innerHTML;

        //fifth td cell
        let price = Number(selectedRowItem.querySelectorAll("#tableContainer td")[4].innerHTML);

        //populate the inputs with the values from the table cells
        document.querySelector("#gameID").value = gameID;
        document.querySelector("#name").value = name;
        document.querySelector("#platform").value = platform;
        document.querySelector("#releaseDate").value = releaseDate;
        document.querySelector("#price").value = price;
    }
}

function setDeleteUpdateButtonState(selectedRowState) {

    //if state is true
    if (selectedRowState) {
        document.querySelector("#deleteButton").removeAttribute("disabled");
        document.querySelector("#updateButton").removeAttribute("disabled");
    }

    //else - state is false
    else {
        document.querySelector("#deleteButton").setAttribute("disabled", "disabled");
        document.querySelector("#updateButton").setAttribute("disabled", "disabled");
    }
}

function handleRowClick(evt) {
    clearSelections();

    //console.log(evt);

    //if evt target is a TD (table cell)
    if (evt.target.nodeName === "TD") {

        //add the selectedRow class to the evt target
        evt.target.parentElement.classList.add("selectedRow");

        setDeleteUpdateButtonState(true);
    }
}

function clearSelections() {

    //get all the rows
    let trs = document.querySelectorAll("tr");
    for (let i = 0; i < trs.length; i++) {

        //remove selectedRow class from the row
        trs[i].classList.remove("selectedRow");
    }
}
