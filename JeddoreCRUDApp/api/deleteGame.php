<?php

//require - the connection manager, retro game accessor (accessor class), RetroGame entity class, and database constants files
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/db/RetroGameAccessor.php';
require_once dirname(__DIR__, 1) . '/entity/RetroGame.php';
require_once dirname(__DIR__, 1) . '/utils/DatabaseConstants.php';

//the gameID is passed as an URL parameter
//this is the only field we need for a deletion
$gameID = intval($_GET['gameID']);

//construct dummy RetroGame object - ultimately only the gameID matters here
$retroGameObj = new RetroGame($gameID, "dummyName", "dummyPlatform", "2000-01-01", 0.0);

//delete game from DB
try {
    $cm = new ConnectionManager(DatabaseConstants::$MYSQL_CONNECTION_STRING, DatabaseConstants::$MYSQL_USERNAME, DatabaseConstants::$MYSQL_PASSWORD);
    $retroGameAccessor = new RetroGameAccessor($cm->getConnection());

    //call the deleteGame method from the accessor class - it handles the DB operations
    $success = $retroGameAccessor->deleteGame($retroGameObj);
    echo $success ? 1 : 0;
} catch (Exception $e) {
    echo "ERROR " . $e->getMessage();
}
