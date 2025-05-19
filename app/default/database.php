<?php
class MySql{

	private static $connection 	= NULL;
	private static $result 		= NULL;		
	private static $sql 		= NULL;
	private static $error 		= NULL;
	private static $prefix 		= '';
	
	public static function connect(){
        includeDefault(['key']);
        $KEY 			  = Key::credential();
		self::$connection = mysqli_connect($KEY['SERVER'], $KEY['USERNAME'], $KEY['PASSWORD'], $KEY['DATABASE']) or self::debug(mysqli_connect_error());
		return self::$connection;	
	}	

	public static function disconnect(){
		mysqli_close(self::connect());
	}	

	public static function debug($error, $table=''){
		echo "<b>MYQL ERROR:</b><br />".$error; 
		echo "<br /><br /><b>TABLE:</b><br />".$table; 
	}

	public static function log_error($message) {
	    $backtrace 	   = debug_backtrace();
	    $error_details = [
							'message'   => $message,
							'file'      => $backtrace[0]['file'],
							'line'      => $backtrace[0]['line'],
							'function'  => $backtrace[1]['function'],
					     ];
	    error_log(json_encode($error_details));
	}
				
	public static function select($table, $fields, $where='', $orderby='', $limit=''){
		$row 	 = NULL;
		$where 	 = (trim($where) != '') ? "WHERE {$where}" : $where;
        $orderby = (trim($orderby) != '') ? "ORDER BY {$orderby}" : $orderby;
        $limit 	 = (trim($limit) != '') ? "LIMIT {$limit}" : $limit;
		$query   = "SELECT {$fields} FROM ".self::$prefix.$table." {$where} {$orderby} {$limit}";
		$result  = mysqli_query(self::connect(), $query);

	    if(!$result){
	        self::log_error("MySQL Error in query: {$query} - " . mysqli_error(self::$connection));
	    }

		while($fetchrow = mysqli_fetch_assoc($result)) $row[] = $fetchrow;
		mysqli_free_result($result);
		
		return $row;
	}	
				
    public static function insert($table, $fields, $where=''){
        if($where != '') $where = " WHERE $where";
        $query = mysqli_query(self::connect(), "INSERT INTO ".self::$prefix.$table." SET $fields" . $where) or self::debug(mysqli_error(self::$connection));

		if($query){
			return true;
		}
		return false;
    }

	public static function update($table, $fields, $where=''){
		if($where != '') $where = " WHERE $where";
		$query = mysqli_query(self::connect(), "UPDATE ".self::$prefix.$table." SET $fields" . $where) or self::debug(mysqli_error(self::$connection));
		
		if($query){
			return true;
		}
		return false;
	}
	
	public static function delete($table, $where = ""){
		$where 	= (trim($where) != "") ? "where {$where}" : $where;
		$query 	= mysqli_query(self::connect(), "DELETE FROM ".self::$prefix.$table." {$where}") or self::debug(mysqli_error(self::$connection));
		
		return $query;
	}
	
	public static function count($table, $fields, $where = ""){
		$where 	= (trim($where) != "") ? "where {$where}" : $where;
		$result = mysqli_query(self::connect(), "SELECT $fields FROM ".self::$prefix.$table." $where") or self::debug(mysqli_error(self::$connection));
		$count 	= mysqli_num_rows($result);
		
		return($count);
	}	

	public static function query($query, $type='select'){
		$row 	= NULL;
		$result = mysqli_query(self::connect(), $query) or self::debug(mysqli_error(self::$connection));

		if($type == 'select'){
			while($fetchrow = mysqli_fetch_assoc($result)) $row[] = $fetchrow;
			mysqli_free_result($result);
			
			return $row;
		}else{
			if($result){
				return true;
			}
			return false;
		}
	}	
	
	public static function insertedId(){
		return mysqli_insert_id(self::$connection);
	}

    public static function buildFields($post, $sep=" "){    
        $count  	= '';
        $fields 	= ''; 
		//$connection = self::$connection;
		
        foreach($post as $key => $value){

            //$value = mysqli_escape_string($connection,$value);

            if($count == 0){
                $fields .= "$key='$value'";
            }else{
                $fields .= $sep . "$key='$value'";
            }

            $count++;
        }
        return $fields;
    }
}
?>