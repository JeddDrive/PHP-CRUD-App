<?php

//require - the connection manager, retro game accessor (accessor class), RetroGame entity class, and database constants files
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/db/RetroGameAccessor.php';
require_once dirname(__DIR__, 1) . '/entity/RetroGame.php';
require_once dirname(__DIR__, 1) . '/utils/DatabaseConstants.php';

//Step 5 for this assignment
//reading the HTTP request body
//body of the request
$body = file_get_contents('php://input');

//JSON decode method
$phpObject = json_decode($body, true);

//game object fields - need all 5 for an insert
$gameID = $phpObject['gameID'];
$name = $phpObject['name'];
$platform = $phpObject['platform'];
$releaseDate = $phpObject['releaseDate'];
$price = $phpObject['price'];

//construct a RetroGame object
$retroGameObj = new RetroGame($gameID, $name, $platform, $releaseDate, $price);

//add the object to the DB
try {
    $cm = new ConnectionManager(DatabaseConstants::$MYSQL_CONNECTION_STRING, DatabaseConstants::$MYSQL_USERNAME, DatabaseConstants::$MYSQL_PASSWORD);
    $retroGameAccessor = new RetroGameAccessor($cm->getConnection());

    //call the insertGame method from the accessor class - it handles the DB operations
    $success = $retroGameAccessor->insertGame($retroGameObj);
    echo $success ? 1 : 0;
} catch (Exception $e) {
    echo "ERROR " . $e->getMessage();
}
