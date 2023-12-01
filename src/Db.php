<?

namespace Src;


use PDO;
use PDOException;


class Db
{
    private static $instance = null;

    private $dsn = 'mysql:host=localhost;';
    private $username = 'root';
    private $password = 'root';
    private $conn;


    /**
     * @return void
     */
    public function connect(): void
    {

        try {
            $this->conn = new PDO($this->dsn, $this->username, $this->password);
            // Проверяем существование базы данных
            $databases = $this->conn->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array('base-comment', $databases)) {
                $sql = include 'sql.php';
                foreach ($sql as $item) {
                    $this->conn->exec($item);
                }
            }
            $this->conn->query("use `base-comment`");
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }


    /**
     * @return Db|null
     */
    public static function getInstance(): ?Db
    {
        if (self::$instance === null) {
            self::$instance = new self();
            self::$instance->connect();
        }

        return self::$instance;
    }

    /**
     * Возвращает текущее подключение к БД
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->conn;
    }
}
