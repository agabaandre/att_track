<?php
require_once(__DIR__.'/database/DbConn.php');
class AndreModel extends DbConn{
	public function __construct()
    {
		$dbcon = new DbConn();
		 $this->connection= $dbcon->dbconnection();
		$mysqli=$this->connection;
	}
	//use for delete, update, insert sql raw queries that dont return result
	//example: $this->rawquery('Delete from users where uuid=1");
	function rawquery($sql){
		$sql=$this->appendSemicolon($sql);
		$result = $this->connection->query($sql);
		//  echo $sql;
		if($result)
		return 'SUCCESS';
		else
		return 'Failed SQL ERROR';
	}
	//Example: $this->insert('users',$this->inputpost());
	function insert($tableName,$insertWhat){
		$sql='INSERT INTO '.$tableName.'(';
		foreach ($insertWhat as $key => $value)
		$sql .= $key.',';
		$sql=rtrim($sql,',');
		$sql.=')';
		$sql.=' VALUES(';
		foreach ($insertWhat as $key => $value)
		$sql .= '\''.$value.'\',';
		$sql=rtrim($sql,',');
		$sql.=')';
		//echo $sql;
		$sql=$this->appendSemicolon($sql);
		$result = $this->connection->query($sql);
		if($result)
			return $result;
		else
		return 'Failed SQL ERROR';
	}
	//you can get the array to update from the form using $this->inputpost();, with this you have to only declare the array for conditions.
	//sample: $this->update('employee_details',array('Surname'=>'Andrew','Firstname=>'Agaba'), array('emp_id'=>'1'));
    function update($tableName,$whatToSet,$whereArgs){
    	$sql='UPDATE '.$tableName .' SET ';
    	foreach ($whatToSet as $key => $value)
    	$sql .= $key .'=\'' . $value . '\',';
    	$sql=rtrim($sql,',');
	   if($whereArgs)
		$sql= $this->where($sql,$whereArgs);	
		$sql=$this->appendSemicolon($sql);

		//echo $sql;
	    $result = $this->connection->query($sql);
		if($result)
			return $result;
		else
		return 'Failed SQL ERROR';
	}
	//example: $this->delete('users',array('id'=>'1','flag'=>'0'));
   function delete($tableName,$whereArgs){
   	    $sql='DELETE FROM '.$tableName;
	   if($whereArgs)
	   	$sql=$this->where($sql,$whereArgs);
	   	$sql=$this->appendSemicolon($sql);
	  	 $result = $this->connection->query($sql);
		if($result)
			return $result;
		else
		return 'Failed SQL ERROR';
   }
    function where($sql,$whereArgs){
    	$sql.=' WHERE ';
    	foreach ($whereArgs as $key => $value)
    		$sql.=$key.' = \''.$value.'\' AND ';
    	$sql=rtrim($sql,'AND ');  	
    	return $sql;
    }
	function appendSemicolon($sql){
		if(substr($sql,-1)!=';')
			return $sql.' ;';	
	}
	//this returns data from in either and array or object format from a raw query
	//example: $result=$this->get("array","SELECT * FROM users");
	function get($type,$query){
		$sql=$this->appendSemicolon($query);
		//echo '<br>'. $sql;
		$array=array();
		$result = $this->connection->query($sql);
		if($result){
		if ($type=="object"){
		while($row=mysqli_fetch_object($result))
		array_push($array,$row);
		return $array;
		}
		elseif ($type=="array"){
			while($row=mysqli_fetch_assoc($result))
			array_push($array,$row);
		return $array;  
		}
		else
		return 'Failed SQL ERROR'; 
		}
		}
         //Get the rows affted after running a query
		//Usage: $affectedrows = $this->affected_rows();
		function affected_rows(){
			$data=$this->connection->affected_rows;
			return $data;
		}
		// counts number of rows in  a result set from a run query.
		//$this->num_rows($sqlresult)
		function num_rows($sqlresult){
			$results=$this->connection->query($sqlresult);
			$data=$results->num_rows;
			return $data;
		}
		//close a db connection;
		public function dbclose()
		{
		return	$this->connection->close();
		}
		//this process form postinputs
		//instead of running $_POST['name]; you can run $this->inputpost('name);
		public function inputpost($fieldname=FALSE){
			$sqlines=array('SELECT','select','Select','UPDATE','Update','update','DELETE',
			'Delete','delete','*','union','UNION','WHERE','where','AS','as','As','aS','#',
			'?','%','%%','??','$$','$','+','(',')','!','^','=');
			if($fieldname){
			// remove sql key statements
			$result=($_POST[$fieldname]);
			$fresult=str_replace($sqlines,'',$result);
			}
			else{
			$result=($_POST);
			$fresult=str_replace($sqlines,'',$result);
			}
		return $fresult;
		}
		//works like above but use it for data sources you are sure of
		public function inputpost_clean($fieldname=FALSE){
			if($fieldname){
			// remove sql key statements
			$result=($_POST[$fieldname]);
			$fresult=$result;
			}
			else{
			$result=($_POST);
			$fresult=$result;
			}
		return $fresult;
		}
	
}
		
		?>