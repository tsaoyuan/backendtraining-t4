<?php

namespace App\Controller;

use App\Core\Database\CollectData;
use App\Core\Database\RainfallSchema;
// 使用 Opis ORM
use Opis\Database\Connection;
use Opis\Database\Database;
use Opis\Database\Schema\CreateTable;
// 使用 PDO
use PDO;
// index.php line 12 用 $pdo, 使用 DB
use App\Core\Database\DB;
// 使用 例外處理
use Exception;


class DatabaseController implements RainfallSchema, CollectData
{
// Property 
// index.php line 12 用 $pdo 作為連線的物件
// 無須使用 $conn, $dsn, $user, $pwd
    private $pdo, $db, $schema;
// table name
    private $rainfallsTableName = 'rainfall';
    private $districtsTableName = 'districts';
    private $path = '/var/www/html/weather/backendtraining-t4/rainfallData/*.*';
// Methods
// RainfallSchema
    public function __construct($pdo){
        // 建立連線作業
        try{
            $this->pdo = $pdo;
            $this->db = DB::init()->database();
            $this->schema = $this->db->schema();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function createRainfallsTable(){
        $tables = $this->rainfallsTableName;
        $this->schema->drop($tables);
        $this->schema->create($tables, function(CreateTable $table){
        $table->integer('id')->primary()->autoincrement();
        $table->string('name', 8);
        $table->dateTime('datetime');
        $table->float('rainfall');
        });
       echo "Catch  Create Rain table" .PHP_EOL;
    }

    public function createDistrictsTable(){
        $tables = $this->districtsTableName;
        $this->schema->drop($tables);
        $this->schema->create($tables, function(CreateTable $table){
            $table->integer('id')->primary()->autoincrement();
            $table->string('name', 8);
        });
       echo "Catch  Create Districts table" .PHP_EOL;
    }

    public function importData()
    {
       $this->createRainfallsTable();
       $this->createDistrictsTable();

       $tableList = $this->schema->getTables();
       $totalTables = count($tableList);

       // Check databases have tables?
       if ($totalTables !== 2) {
           // Databases have no tables
           echo "Databases have tables" . PHP_EOL;
           // Create rainfallTable and districtTable, then import data
       } else {
           // Databases have rainfallTable and districtTable
           echo "Databases have tables" . PHP_EOL;
           // Clear two table's row data 
           $this->schema->truncate($this->rainfallsTableName);
           $this->schema->truncate($this->districtsTableName);
           // Then import data
           // import rainfallTable data use php
           $rainfallData = [];
           foreach (glob($this->path) as $jsonFileName) {
                $fileName = pathinfo($jsonFileName, PATHINFO_FILENAME); 
                $splice = mb_substr($fileName,-2,2, 'UTF-8');

                if(!str_contains("$splice","區")){
                    $splice = $splice.'區';
                }

                // $jsonString = file_get_contents($jsonFileName);
                // $data = json_decode($jsonString, true);  
                $data = json_decode(file_get_contents($jsonFileName), true);

                $rainfallData[$splice] = $data;
              
           }
           // 重構 rainfallData 內容
           function transpose($rainfallData){
                $i = 0;
                $result = [];
                foreach($rainfallData as $town => $rowdata){
                    foreach($rowdata as $key => $value){
                    // 地區
                    $result[$i][0] = $town;
                    // 日期
                    $result[$i][1] = $key;
                    // 地區
                    $result[$i][2] = $value;
                    $i++; 
                    }
                }
               return $result; 
            }
            //$refactorRainfallData insert into mysql
            $refactorRainfallData = transpose($rainfallData);
            function importData($refactorRainfallData, $db, $tables){

                $refactorRainfallDataKey = count($refactorRainfallData);
                for($i = 0; $i < $refactorRainfallDataKey; $i++ ){
                    $name = $refactorRainfallData[$i][0];
                    $date = $refactorRainfallData[$i][1];
                    $rainfall = $refactorRainfallData[$i][2];

                    // echo "name: $name, date: $date, rainfall: $rainfall".PHP_EOL;

                    try{
                    $db->insert(array(
                        'name' => $name,
                        'datetime' => $date,
                        'rainfall' => $rainfall
                        ))->into($tables);
                    }catch(Exception $e){
                    echo $e->getMessage();
                    }
                }
            echo "Insert RainfallData into MySQL Sucess!".PHP_EOL;
            }  
            importData($refactorRainfallData, $this->db, $this->rainfallsTableName);

            // import districts data use php
            foreach(glob($this->path) as $jsonFileName){
              $fileName = pathinfo($jsonFileName, PATHINFO_FILENAME);   
              $splice = mb_substr($fileName,-2,2, 'UTF-8');

              if(!str_contains("$splice","區")){
                $splice = $splice.'區';
              }

              // Insert into data to districts table
              $this->db->insert(array(
              'name' => $splice
              ))->into($this->districtsTableName);
            }
            echo "Insert DistrictsData into MySQL Sucess!".PHP_EOL;

       }
    }
// CollectData
    public function showDistricts(): array{
        $result = ['Creating...'];
        return $result;
    }

    public function sumByYear($district = null): array{
        $result = ['Creating...'];
        return $result;
    }

    public function sumByMonth($district = null): array{
        $result = ['Creating...'];
        return $result;
    }
}