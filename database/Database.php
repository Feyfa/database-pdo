<?php 

class Database 
{
    private $dbh;
    private $stmt;

    private $db_host = "localhost";
    private $db_user = 'root';
    private $db_pass = '';
    private $db_name = 'pdo';

    private $table_name;
    
    private function clearTableName()
    {
        $this->table_name = '';
    }

    private function createTablMurids()
    {
        $query = "CREATE TABLE IF NOT EXISTS murids (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            gender ENUM('Laki-Laki', 'Perempuan')
        )";

        $this->dbh->exec($query);
    }

    private function createTableGrades()
    {
        $query = "CREATE TABLE IF NOT EXISTS grades (
            murid_id INT,
            subject VARCHAR(100),
            grade INT,
            FOREIGN KEY (murid_id) REFERENCES murids(id)
        )";

        $this->dbh->exec($query);
    }

    public function __construct() 
    {
        try 
        {
            $dsn = "mysql:host=$this->db_host;dbname=$this->db_name"; 
            $options = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            $this->dbh = new PDO($dsn, $this->db_user, $this->db_pass, $options);
            
            $this->createTablMurids();
            $this->createTableGrades();
        }
        catch (PDOException $e)
        {
            die($e->getMessage());
        }
    }

    public function table(string $table_name)
    {
        $this->table_name = $table_name;
        return $this;
    }

    public function create(array $fields, array $values): int
    {
        try 
        {
            $query = "INSERT INTO $this->table_name (";

            foreach($fields as $index => $field)
            {
                $query .= $field;
                
                $query .= $index !== count($fields) - 1 ? ", " : ") ";
            }

            $query .= "VALUES (";

            foreach($fields as $index => $field)
            {
                $query .= ":$field";
                
                $query .= $index !== count($fields) - 1 ? ", " : ") ";
            }
            
            $this->stmt = $this->dbh->prepare($query);

            for($i = 0; $i < count($fields); $i++) 
            {
                $this->stmt->bindParam(":" . $fields[$i], $values[$i]);
            }

            $this->stmt->execute();

            $this->clearTableName();
            
            return $this->stmt->rowCount();
        }
        catch (PDOException $e)
        {
            die($e->getMessage());
        }
        
    }

    public function insert(array $fields, array $values): int
    {
        try 
        {
            $query = "INSERT INTO $this->table_name (";

            foreach($fields as $index => $field)
            {
                $query .= $field;
                
                $query .= $index !== count($fields) - 1 ? ", " : ") ";
            }

            $query .= "VALUES ";

            for($i = 0; $i < count($values); $i++)
            {
                $query .= "(";

                for($j = 0; $j < count($fields); $j++)
                {
                    $query .= ":$fields[$j]$i";
                    $query .= $j !== count($fields) - 1 ? ", " : ")";
                }

                $query .= $i !== count($values) - 1 ? ", " : '';
            }

            $this->stmt = $this->dbh->prepare($query);
            
            for($i = 0; $i < count($values); $i++)
            {
                for($j = 0; $j < count($fields); $j++)
                {               
                    $this->stmt->bindParam(
                        ":$fields[$j]$i",
                        $values[$i][$j]
                    );
                }
            }

            $this->stmt->execute();

            $this->clearTableName();
            
            return $this->stmt->rowCount();
        }
        catch (PDOException $e)
        {
            die($e->getMessage());
        }
    }

    public function getAll()
    {
        try
        {
            $query = "SELECT * FROM $this->table_name";

            $this->stmt = $this->dbh->prepare($query);

            $this->stmt->execute();

            $this->clearTableName();

            return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }

    public function getWhere(array $wheres)
    {
        try
        {
            $keys = array_keys($wheres);

            $query = "SELECT * FROM $this->table_name WHERE ";

            foreach($keys as $key)
            {
                $query .= "$key  = :$key";
                $query .= $key !== end($keys) ? " AND " : '';
            } 
            
            $this->stmt = $this->dbh->prepare($query);
            
            foreach($wheres as $key => $value)
            {
                $this->stmt->bindValue(":$key", $value);
            }

            $this->stmt->execute();

            $this->clearTableName();

            return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }

    public function updateWhere(array $sets, array $wheres)
    {   
        try
        {
            $sets_keys = array_keys($sets);
            $wheres_keys = array_keys($wheres);

            $query = "UPDATE $this->table_name SET ";

            foreach($sets_keys as $sets_key)
            {
                $query .= "$sets_key = :$sets_key";
                $query .= $sets_key !== end($sets_keys) ? ", " : "";
            }

            $query .= " WHERE ";

            foreach($wheres_keys as $wheres_key)
            {
                $query .= "$wheres_key = :w_$wheres_key";
                $query .= $wheres_key !== end($wheres_keys) ? " AND " : "";
            }

            // var_dump($query);

            $this->stmt = $this->dbh->prepare($query);

            foreach($sets as $key => $value)
            {
                // var_dump("$key => $value");
                $this->stmt->bindValue(":$key", $value);
            }
            
            foreach($wheres as $key => $value)
            {
                // var_dump("w_$key => $value");
                $this->stmt->bindValue(":w_$key", $value);
            }

            $this->stmt->execute();

            $this->clearTableName();

            return $this->stmt->rowCount();

        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }

    public function deleteWhere(array $wheres)
    {
        try
        {
            $keys = array_keys($wheres);
            $query = "DELETE FROM $this->table_name WHERE ";

            foreach($keys as $key)
            {
                $query .= "$key = :$key";
                $query .= $key !== end($keys) ? " AND " : "";
            }

            $this->stmt = $this->dbh->prepare($query);

            foreach($wheres as $key => $value)
            {
                $this->stmt->bindValue(":$key", $value);
            }

            $this->stmt->execute();

            $this->clearTableName();
            
            return $this->stmt->rowCount();
            
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }


}   