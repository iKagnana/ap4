<?php
class Database
{
    private $host = "mysql:host=db";
    private $dbName = "dbname=ap4";
    private $user = "user";
    private $password = "pass";
    private $query;
    private $service; # PDO
    private $statement;
    private $error; # return error if neeeded

    /** Constructor for the class Database
     * Set PDO in private variable
     */
    public function __construct()
    {
        # set DSN
        $dsn = $this->host . ";" . $this->dbName;
        try {
            $this->service = new PDO($dsn, $this->user, $this->password);
        } catch (PDOException $exception) {
            echo "Une erreur est survenu lors de la connection à la base :" . $exception->getMessage();
        }
    }

    /** Set statement with query
     * @param string query
     */
    public function query($query)
    {
        $this->statement = $this->service->prepare($query);
    }

    /** Bind value in query
     * @param string $param 
     * @param string $value
     */
    public function bind($param, $value, $type = null)
    {
        # set type according to type value
        switch (is_null($type)) {
            case is_double($value):
                $type = PDO::PARAM_INT;
                break;
            case is_int($value):
                $type = PDO::PARAM_INT;
                break;
            case is_string($value):
                $type = PDO::PARAM_STR;
                break;
            default:
                $type = PDO::PARAM_STR;
        }
        $this->statement->bindValue($param, $value, $type);
    }

    private function execute()
    {
        # return to use in fetchAll or fetch
        return $this->statement->execute();
    }

    public function fetchAll()
    {
        $this->execute();
        return $this->statement->fetchAll();
    }

    public function fetch()
    {
        $this->execute();
        return $this->statement->fetch();
    }
}