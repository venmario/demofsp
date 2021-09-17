<?php
class ParentClass
{

    protected $mysqli;

    public function __construct($server, $user, $pass, $db)
    {
        $this->mysqli = new mysqli($server, $user, $pass, $db);
    }

    function __destruct()
    {
        $this->mysqli->close();
    }
}
