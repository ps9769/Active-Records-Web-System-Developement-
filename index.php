<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

define('DATABASE', 'pbs29');
define('USERNAME', 'pbs29');
define('PASSWORD', '2vCO8Rt4');
define('CONNECTION', 'sql1.njit.edu');


class dbConn
{
       protected static $db;

       private function __construct()
	     {
	     	try
	          {
                self::$db = new PDO('mysql:host=' . CONNECTION .';dbname=' .DATABASE, USERNAME, PASSWORD );
                self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
              }

	        catch (PDOException $e)
	          {
	         	echo "Connection Error: " . $e->getMessage();
	          }
	     }


	   public static function getConnection()
	     {
	     	if (!self::$db)
	         {
	          new dbConn();
			 }
			 return self::$db;
	     }

}

         

class collection
{
	static public function create()
	  {
	  	$model = new static::$modelName;
	  	return $model;
	  }
	   
	public  function findAll()
	  {
	     $db = dbConn::getConnection();
	     $tableName = get_called_class();

	     $sql = 'SELECT * FROM ' . $tableName;
	     $statement = $db->prepare($sql);
	     $statement->execute();
            
	     $class = static::$modelName;
	     $statement->setFetchMode(PDO::FETCH_CLASS, $class);
	    
	     $recordsSet =  $statement->fetchAll();
	     return $recordsSet;
	  }

	public  function findOne($id)
	  {
	  	 $db = dbConn::getConnection();
	  	 $tableName = get_called_class();
	  	 $sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;

	  	 $statement = $db->prepare($sql);
	  	 $statement->execute();

	  	 $class = static::$modelName;
	  	 $statement->setFetchMode(PDO::FETCH_CLASS,$class);

	  	 $recordsSet  =  $statement->fetchAll();
	  	 return $recordsSet;
	  }
}
	  



class accounts extends collection
{
	protected static $modelName='accounts';
}


class todos extends collection
{
	protected static $modelName='todos';
}




class model
{
	      static $columnString;
	      static $valueString;
	   
	      public function save()
	       {
	       	 if (static::$id == '')
		      {
               $db=dbConn::getConnection();
               $array = get_object_vars($this);
               static::$columnString = implode(', ', $array);
               static::$valueString = implode(', ',array_fill(0,count($array),'?'));
               $sql = $this->insert();
               $stmt=$db->prepare($sql);
               $stmt->execute(static::$data);
		      }
		     else
		      {
               $db=dbConn::getConnection();
               $array = get_object_vars($this);
			   $sql = $this->update();
               $stmt=$db->prepare($sql);
               $stmt->execute();
			  }
		   }


	       private function insert()
		    {
		    	$sql = "Insert Into ".static::$tableName." (". static::$columnString . ") Values(". static::$valueString . ") ";
				return $sql;
		    }



	       private function update()
		    {
		   		$sql = "Update ".static::$tableName. " SET ".static::$columnToUpdate."='".static::$newInfo."' WHERE id=".static::$id;
			 	return $sql;
		     }
                    
                   
	        public function delete()
			 {
		        $db=dbConn::getConnection();
		        $sql = 'Delete From '.static::$tableName.' WHERE id='.static::$id;
		        $stmt=$db->prepare($sql);
		        $stmt->execute();
		        echo'I just deleted record which has ID :'.static::$id;
		     }
}

     



class account extends model
{
              public $email = 'email';
              public $fname = 'fname';
              public $lname = 'lname';
              public $phone =  'phone';
              public $birthday = 'birthday';
              public $gender= 'gender';
              public $password = 'password';
              static $tableName = 'accounts';
              static $id = '7';

              static $data = array('srk@njit.edu','Sunny','Jain','122','1995-12-12','Male','sunny');

              static $columnToUpdate = 'phone';

              static $newInfo ='123456789';
}




class todo extends model
{
               public $owneremail = 'owneremail';
               public $ownerid = 'ownerid';
  	           public $createddate = 'createddate';
	           public $duedate = 'duedate';
               public $message = 'message';
               public $isdone = 'isdone';
               static $tableName = 'todos';
               static $id = '32';

               static $data = array('wes@njit.edu','14','2017-11-15','2017-11-16','Its Done','0');


		       static $columnToUpdate = 'message';

               static $newInfo ='Its Over';
}




class table
{
	 	static	function makeTable($result)
		{
			echo '<table>';
			foreach($result as $column)
			{
				echo '<tr>';
				foreach($column as $row)
				   {
					 echo '<td>';
					 echo $row;
					 echo '</td>';
				    }
				echo '</tr>';
			}
			echo '</table>';
		}
}




         echo '<h1>Select all records from Accounts Table   <h1>';
	     $records = accounts::create();
         $result = $records->findAll();
	     table::makeTable($result);

         echo '<br>';
         echo '<br>';

	     echo '<h1>Select an ID from Accounts Table where ID is : 1 <h1>';
	     $result= $records->findOne(1);
	     table::makeTable($result);

         echo '<br>';
         echo '<br>';
         echo '<br>';

         echo '<h1>Select all from records from Todos Table <h1>';
         $records = todos::create();
	     $result= $records->findAll();
         table::makeTable($result);

         echo '<br>';
         echo '<br>';

		 echo '<h1>Select an ID  from Todos Table where ID:10 <h1>';
	     $result= $records->findOne(10);
         table::makeTable($result);


         echo '<h1>Update Phone Column in Accounts Table where ID is : 7 <h1>';
         $obj = new account;
         $obj->save();
         $records = accounts::create();
         $result = $records->findAll();
         table::makeTable($result);

         echo '<br>';
         echo '<br>';


         echo '<h1>Insert New Row in Todos Column <h1>';
         $obj = new todo;
         $obj->save();
         $records = todos::create();
         $result= $records->findAll();
         table::makeTable($result);

         echo '<br>';
         echo '<br>';


         echo '<h1>Delete ID 32 from Todos Table <h1>';
         $obj = new todo;
         $obj->delete();
         $records = todos::create();
         $result= $records->findAll();
         table::makeTable($result);

?>
