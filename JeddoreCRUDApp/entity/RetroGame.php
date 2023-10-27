<?php

//RetroGame class to represent the entity I created in A3
//this class implements the JsonSerializable interface
//so can create a JSON string with the right data in it
class RetroGame implements JsonSerializable
{
    //5 private fields
    private $gameID;
    private $name;
    private $platform;
    private $releaseDate;
    private $price;

    //public constructor
    public function __construct($inGameID, $inName, $inPlatform, $inReleaseDate, $inPrice)
    {
        //setting the fields to the values sent into the constructor
        $this->gameID = $inGameID;
        $this->name = $inName;
        $this->platform = $inPlatform;
        $this->releaseDate = $inReleaseDate;
        $this->price = $inPrice;
    }

    //5 public getter methods below
    //referring to the private fields inside of them
    public function getGameID()
    {
        return $this->gameID;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    public function getPrice()
    {
        return $this->price;
    }

    //jsonSerialize() method
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}//end of RetroGame class