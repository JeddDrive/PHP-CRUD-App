<?php
//require - the connection manager, retro game accessor (accessor class), and database constants files
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/db/RetroGameAccessor.php';
require_once dirname(__DIR__, 1) . '/utils/DatabaseConstants.php';

//this file asks the accessor to get all of the retro games
try {
    $cm = new ConnectionManager(DatabaseConstants::$MYSQL_CONNECTION_STRING, DatabaseConstants::$MYSQL_USERNAME, DatabaseConstants::$MYSQL_PASSWORD);
    $retroGameAccessor = new RetroGameAccessor($cm->getConnection());

    //call getAllGames() from the accessor class - it handles the DB operations
    $gamesArray = $retroGameAccessor->getAllGames();

    //an array of objects is returned
    $gamesArray = json_encode($gamesArray, JSON_NUMERIC_CHECK);
    echo $gamesArray;
} catch (Exception $e) {
    echo "ERROR " . $e->getMessage();
}
