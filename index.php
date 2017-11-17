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
	      protected $tableName;
	   
	      public function save()
	       {
	           if ($this->id = '') 
		   {
		    $sql = $this->insert($columnString, $valueString); 
		   } 
		   else 
		   { 
		    $sql = $this->update();
		   }

		   $db = dbConn::getConnection();
		   $statement = $db->prepare($sql);
	           $statement->execute();
                   $tableName = get_called_class();


                   $array = get_object_vars($this);
		   $columnString = implode(',', $array);
	           $valueString = ":".implode(',:', $array);
     
                   echo 'I just saved record which has ID : ' . $this->id.'<br>';
	        }
               

                private function insert()
	         {
	          $sql = "Insert Into".$this->tableName."(". $columnString . ") Values(".
		  $valueString . ") ";
		  
		  echo $sql;
		  return $sql;
	         }


                private function update()
		 {
		   $sql = "Update".$this->tableName.
		   'SET owneremail=:owneremail,
		   ownerid=:ownerid,
		   createdate=:createddate,
		   duedate=:duedate,
		   message=:message,
		   isdone=:isdone
		   WHERE id='.$this->id;

		   echo  "I just updated record which has ID : " . $this->id.'<br>';
                   return $sql;
		 }
                    
                   
	        public function delete()
                 {
		   $db=dbConn::getConnection();
		   $sql = 'Delete From'.$this->tableName.'WHERE id='.$this->id;
		   $stmt=$db->prepare($sql);
		   $stmt->execute();

		   echo "I just deleted record which has ID : ". $this->id;
		 } 
           } 

     


      class account extends model
	    {
              public $id;
              public $email;
              public $fname;
              public $lname;
              public $phone;
              public $birthday;
              public $gender;
              public $password;
     
              function _construct()
                {
                 $this->tableName = 'accounts';
                 $this->id = '14';
                 $this->email = 'skr@njit.edi';
                 $this->fname = 'Sunny';
                 $this->lname = 'Jain';
                 $this->phone = '122';
                 $this->birthday = '12-12-1995';
	         $this->gender = 'Male';
	         $this->password = 'sunny';  
                }
             }


       class todo extends model 
            {
               public $id;
               public $owneremail;
               public $ownerid;
  	       public $createdate;
	       public $duedate;
               public $message;
               public $isdone;

 
               function _construct()
	         {
	          $this->tableName = 'todos';
                  $this->id = '8';
	          $this->owneremail = 'kia@njit.edu';
	          $this->ownerid = '7';
	          $this->createdate = '10-08-2000';
	          $this->duedate = '08-10-2017';
	          $this->message = 'practise';
	          $this->isdone = '0';
		 }

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


	 $records = accounts::create();
         $result = $records->findAll();
	 table::makeTable($result);
         echo" <br>";
	 echo" <br>";
	 $result= $records->findOne(1);
	 table::makeTable($result);
         echo" <br>";
         echo" <br>";
	 echo" <br>";
         $records = todos::create();
	 $result= $records->findAll();
	 table::makeTable($result);
	 echo" <br>";
	 echo" <br>";
	 $result= $records->findOne(1);
         table::makeTable($result);
	 
	/* 
	 $record = new todo();
	 $record->message = 'some task';
	 $record->isdone = 0;


	 print_r($record);
	 $record = todos::create();
	 print_r($record);
      */
	







?>
