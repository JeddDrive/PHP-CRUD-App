<?php
//require the RetroGame class that represents the entity
require_once dirname(__DIR__, 1) . '/entity/RetroGame.php';

//Accessor Class (DAO) - for database operations
class RetroGameAccessor
{
    //select/get all games statement
    private $getAllStatementString = "select * from RetroGames";

    //select by gameID statement
    private $getByIDStatementString = "select * from RetroGames where gameID = :gameID";
    //delete game by gameID statement
    private $deleteStatementString = "delete from RetroGames where gameID = :gameID";
    //insert game statement
    private $insertStatementString = "insert into RetroGames values (:gameID, :name, :platform, :releaseDate, :price)";
    //update game by gameID statement
    private $updateStatementString = "update RetroGames set gameID = :gameID, name = :name, platform = :platform, releaseDate = :releaseDate, price = :price where gameID = :gameID";

    private $getAllStatement = null;
    private $getByIDStatement = null;
    private $deleteStatement = null;
    private $insertStatement = null;
    private $updateStatement = null;

    /**
     * Creates a new instance of the accessor with the supplied database connection.
     * 
     * @param PDO $conn - a database connection
     */
    public function __construct($conn)
    {
        if (is_null($conn)) {
            throw new Exception("no connection");
        }

        $this->getAllStatement = $conn->prepare($this->getAllStatementString);
        if (is_null($this->getAllStatement)) {
            throw new Exception("bad statement: '" . $this->getAllStatementString . "'");
        }

        $this->getByIDStatement = $conn->prepare($this->getByIDStatementString);
        if (is_null($this->getByIDStatement)) {
            throw new Exception("bad statement: '" . $this->getByIDStatementString . "'");
        }

        $this->deleteStatement = $conn->prepare($this->deleteStatementString);
        if (is_null($this->deleteStatement)) {
            throw new Exception("bad statement: '" . $this->deleteStatementString . "'");
        }

        $this->insertStatement = $conn->prepare($this->insertStatementString);
        if (is_null($this->insertStatement)) {
            throw new Exception("bad statement: '" . $this->getAllStatementString . "'");
        }

        $this->updateStatement = $conn->prepare($this->updateStatementString);
        if (is_null($this->updateStatement)) {
            throw new Exception("bad statement: '" . $this->updateStatementString . "'");
        }
    }

    /**
     * Retrieves all of the retro games in the DB.
     * 
     * @return RetroGame[] an array of RetroGame objects
     */
    public function getAllGames()
    {
        //results array to be returned
        $results = [];

        try {
            $this->getAllStatement->execute();

            //using fetchAll, not fetch
            $dbresults = $this->getAllStatement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r) {
                $gameID = $r['gameID'];
                $name = $r['name'];
                $platform = $r['platform'];
                $releaseDate = $r['releaseDate'];
                $price = $r['price'];

                //construct retrogame object
                $gameObj = new RetroGame($gameID, $name, $platform, $releaseDate, $price);

                //push game object into results array
                array_push($results, $gameObj);
            }
        } catch (Exception $e) {
            $results = [];
        }
        //finally - regardless of success, close the cursor (and connection)
        finally {
            if (!is_null($this->getAllStatement)) {
                $this->getAllStatement->closeCursor();
            }
        }

        return $results;
    }

    /**
     * Gets the retro game with the specified ID from the DB.
     * 
     * @param Integer the gameID wanted to retrieve 
     * @return RetroGame RetroGame object with the specified game ID, or null if not found
     */
    private function getGameByID($gameID)
    {
        //initialize object to be returned to null
        $gameObj = null;

        try {

            //bindParam replaces the colon value with it's actual variable
            $this->getByIDStatement->bindParam(":gameID", $gameID);
            $this->getByIDStatement->execute();

            //using fetch, not fetchAll (want a single game/item)
            $dbresults = $this->getByIDStatement->fetch(PDO::FETCH_ASSOC);

            if ($dbresults) {
                $gameID = $dbresults['gameID'];
                $name = $dbresults['name'];
                $platform = $dbresults['platform'];
                $releaseDate = $dbresults['releaseDate'];
                $price = $dbresults['price'];

                //construct retrogame object
                $gameObj = new RetroGame($gameID, $name, $platform, $releaseDate, $price);
            }
        } catch (Exception $e) {
            $gameObj = null;
        }
        //finally - regardless of success, close the cursor (and connection)
        finally {
            if (!is_null($this->getByIDStatement)) {
                $this->getByIDStatement->closeCursor();
            }
        }

        return $gameObj;
    }

    /**
     * Does the game already exist in the DB (by checking it's ID)?
     * 
     * @param RetroGame the game to check
     * @return boolean true if the game exists, and false if not
     */
    public function gameExists(RetroGame $game)
    {
        return $this->getGameByID($game->getGameID()) !== null;
    }

    /**
     * Deletes a retro game from the DB.
     * 
     * @param RetroGame an object whose ID is the same to the ID of the game to delete
     * @return boolean indicates whether the game was deleted
     */
    public function deleteGame(RetroGame $gameObj)
    {
        //boolean to be returned
        $success = false;

        //if game doesn't exist, return false
        if (!$this->gameExists($gameObj)) {
            return $success;
        }

        //get the gameID using the getter method - it is the only RetroGame field that is required
        $gameID = $gameObj->getGameID();

        try {
            $this->deleteStatement->bindParam(":gameID", $gameID);

            //execute() returns true if it doesn't crash. An invalid ID won't cause a crash, and will still return true
            $success = $this->deleteStatement->execute();

            //success will only be true if the rowCount() ftn also returns a 1 exactly
            $success = $success && $this->deleteStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
        }
        //finally - regardless of success, close the cursor (and connection)
        finally {
            if (!is_null($this->deleteStatement)) {
                $this->deleteStatement->closeCursor();
            }
        }
        return $success;
    }

    /**
     * Inserts a retro game into the DB.
     * 
     * @param RetroGame an object of type RetroGame
     * @return boolean indicates if the game was inserted
     */
    public function insertGame(RetroGame $gameObj)
    {
        //boolean to be returned
        $success = false;

        //if game already exists, return false
        if ($this->gameExists($gameObj)) {
            return $success;
        }

        //get the fields of the object
        $gameID = $gameObj->getGameID();
        $name = $gameObj->getName();
        $platform = $gameObj->getPlatform();
        $releaseDate = $gameObj->getReleaseDate();
        $price = $gameObj->getPrice();

        try {

            //bindParam replaces the colon values with their actual variables
            $this->insertStatement->bindParam(":gameID", $gameID);
            $this->insertStatement->bindParam(":name", $name);
            $this->insertStatement->bindParam(":platform", $platform);
            $this->insertStatement->bindParam(":releaseDate", $releaseDate);
            $this->insertStatement->bindParam(":price", $price);
            $success = $this->insertStatement->execute();

            //success will only be true if the rowCount() ftn also returns a 1 exactly
            $success = $this->insertStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
        }
        //finally - regardless of success, close the cursor (and connection)
        finally {
            if (!is_null($this->insertStatement)) {
                $this->insertStatement->closeCursor();
            }
        }
        return $success;
    }

    /**
     * Updates an existing retro game already in the DB.
     * 
     * @param RetroGame an object of type RetroGame, containing the new values to replace the DB's current values
     * @return boolean indicates if the game was updated
     */
    public function updateGame(RetroGame $gameObj)
    {
        //boolean var to be returned
        $success = false;

        //if game doesn't exist, return false
        if (!$this->gameExists($gameObj)) {
            return false;
        }

        //get the fields of the object
        $gameID = $gameObj->getGameID();
        $name = $gameObj->getName();
        $platform = $gameObj->getPlatform();
        $releaseDate = $gameObj->getReleaseDate();
        $price = $gameObj->getPrice();

        try {

            //bindParam replaces the colon values with their actual variables
            $this->updateStatement->bindParam(":gameID", $gameID);
            $this->updateStatement->bindParam(":name", $name);
            $this->updateStatement->bindParam(":platform", $platform);
            $this->updateStatement->bindParam(":releaseDate", $releaseDate);
            $this->updateStatement->bindParam(":price", $price);
            $success = $this->updateStatement->execute();

            //success will only be true if the rowCount() ftn also returns a 1 exactly
            $success = $this->updateStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
        }
        //finally - regardless of success, close the cursor (and connection)
        finally {
            if (!is_null($this->updateStatement)) {
                $this->updateStatement->closeCursor();
            }
        }
        return $success;
    }
}//end of RetroGameAccessor class