<?
class DB {

	private $db;
	
	function __construct($DB_host, $DB_name, $DB_user, $DB_pass)
	{
	    try
	    {
	        $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name};charset=utf8",$DB_user,$DB_pass);
	        $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        $this->db = $DB_con;
	    }
	    catch(PDOException $e)
	    {
	        echo $e->getMessage();
	    }
	}

	public function select($table, $fields='*', $where='1=1', $order='', $limit = '') {
	    $sql = "SELECT " . $fields . " FROM " . $table . " WHERE " . $where . "" . (!empty($order)? " ORDER BY " . $order : "") . "" .(!emptu($limit) ? 'LIMIT '.$imit : '');
		$stmt = $this->db->prepare($sql);
	    $result=$stmt->execute();
		$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	    //$result=$stmt->fetchAll(PDO::FETCH_CLASS, 'person');
	    return $result;
	}
    
    public function insert($table, $array) {
        $fields=array_keys($array);
        $values=array_values($array);
        //$fieldlist=implode(',', $fields); 
        //$qs=str_repeat("?,",count($fields)-1);
        //$sql="INSERT INTO ".$table." (".$fieldlist.") VALUES (${qs}?)";
        $sql = "INSERT INTO ".$table." SET";
        for ($i = 0; $i < count($fields); $i++) {
            $sql .= " ".$fields[$i]."=?, ";
        }
        $sql = substr($sql, 0, strlen($sql)-2);
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($values);
        $id = $this->db->lastInsertId();
        return $id;
    }

    public function update($table, $array, $id) {
        $fields=array_keys($array);
        $values=array_values($array);

        $sql = "UPDATE ".$table." SET";
        for ($i = 0; $i < count($fields); $i++) {
            $sql .= " ".$fields[$i]."=?, ";
        }
        $sql = substr($sql, 0, strlen($sql)-2);
        $sql .= " WHERE id =?";
        $stmt = $this->db->prepare($sql);
        $values[] = $id;
        return $stmt->execute($values);
    }

    public function delete($table, $id) {
        $sql = "DELETE FROM " . $table . " WHERE id=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array($id));
    }
	
	public function resetAutoIncrement($table) {
        $sql = "ALTER TABLE " . $table . " AUTO_INCREMENT = 0";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array($id));
	}

	public function getRecordsNumber($table, $fields='*', $where='1=1') {
	    $sql = "SELECT ".$fields." FROM " . $table . " WHERE " . $where . "";
	    $stmt = $this->db->prepare($sql);
	    $result=$stmt->execute();
	    $intRecords=$stmt->rowCount();
	    //$result=$stmt->fetchAll(PDO::FETCH_CLASS, 'person');
	    return $intRecords;
	}
		
	public function getColumns($table){
		$sql = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, COLUMN_TYPE, COLUMN_KEY, EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = :table";
		try {
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(':table', $table, PDO::PARAM_STR);
			$stmt->execute();
			$output = array();
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$output[] = $row;                
			}
			return $output; 
		}

		catch(PDOException $pe) {
			trigger_error('Could not connect to MySQL database. ' . $pe->getMessage() , E_USER_ERROR);
		}
	}	

	public function getColumnNames($table){
		$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = :table";
		try {
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(':table', $table, PDO::PARAM_STR);
			$stmt->execute();
			$output = array();
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$output[] = $row['COLUMN_NAME'];                
			}
			return $output; 
		}

		catch(PDOException $pe) {
			trigger_error('Could not connect to MySQL database. ' . $pe->getMessage() , E_USER_ERROR);
		}
	}	
}