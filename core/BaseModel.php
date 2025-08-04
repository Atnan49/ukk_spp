<?php
/**
 * 
 */

class BaseModel
{
    public $table_name;
    public $mysqli;
    
    public function __construct()
    {
        $this->mysqli = new mysqli('localhost', 'root', '', 'ukk_spp');
    }
    public function getByUsername($username)
    {
        $result = $this->mysqli->query("SELECT * FROM $this->table_name WHERE username = '$username'");

        return $result->fetch_assoc();
    }
}