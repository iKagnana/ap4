<?php
enum ErrorType
{
    case IsFK; #cannot delete because id used in another table
    case UnkownError;
}
/** We decompose the different step of a request for better maintainability.
 * Used in class in folder models so the user doesn't directly have access to the db
 */
class Database
{
    private $host = "mysql:host=db";
    private $dbName;
    private $user;
    private $password;
    private $query;
    private $service; # PDO
    private $statement;

    /** Constructor for the class Database
     * Set PDO in private variable
     */
    public function __construct()
    {
        $this->dbName = "dbname=" . getenv("MYSQL_DATABASE");
        $this->user = getenv("MYSQL_USER");
        $this->password = getenv("MYSQL_PASSWORD");
        # set DSN
        $dsn = $this->host . ";" . $this->dbName;
        try {
            $this->service = new PDO($dsn, $this->user, $this->password);
        } catch (PDOException $exception) {
            echo "Une erreur est survenu lors de la connection à la base :" . $exception->getMessage();
        }
    }

    /** Set statement with query
     * @param string query for prepared statement
     */
    public function query($query)
    {
        $this->statement = $this->service->prepare($query);
    }

    /** Bind value in query
     * @param string $param 
     * @param string $value
     * We bind value because we have prepared statement in order to prevent SQL injection
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

    /** function fetchAll of the statement 
     * @return []
     */
    public function fetchAll()
    {
        $this->execute();
        return $this->statement->fetchAll();
    }

    /** Method fetch of the statement
     * @return mixed
     */
    public function fetch()
    {
        $this->execute();
        return $this->statement->fetch();
    }

    /** Function to fetch and get the last insert id 
     * 
     */
    public function fetchLastId()
    {
        $this->execute();
        $this->statement->fetch();
        return $this->service->lastInsertId();
    }


    /** Function to return error type if needed
     * @param ?string $code
     */
    public function getTypeError($code)
    {
        return match ($code) {
            "23000" => ErrorType::IsFK,
            default => ErrorType::UnkownError
        };
    }
}