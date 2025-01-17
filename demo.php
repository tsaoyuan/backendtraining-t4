<?php
  require './vendor/autoload.php';
  use Opis\Database\Connection;
  use Opis\Database\Database;
  use Opis\Database\Schema\CreateTable;

  $dotenv =  Dotenv\Dotenv::createImmutable(__DIR__); 
  $dotenv->load();
  // $demo = $_ENV['DEMO'];

  $dsn = 'mysql:host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_DATABASE'];
  $user = $_ENV['DB_USERNAME'];
  $password = $_ENV['DB_PASSWORD'];

  try {
    $connection = new Connection($dsn, $user, $password);
    $connection->getPDO(); // 這裡才會真的透過 PDO 連線
    echo "Connected successfully!".PHP_EOL;
  } catch (Exception $exception) {
      echo $exception->getMessage();  
  }

  $db = new Database($connection);
  // $tables = 'districts';
  // $db->schema()->drop($tables);
  // $db->schema()->create($tables, function(CreateTable $table){
  //   $table->integer('id')->primary();
  //   $table->integer('id')->autoincrement();
  //   $table->string('name', 8);
  // });
  // // 判斷當前 database 內，有/無 tables  
  //  $msg = ($db->schema()->hasTable($tables)) ? "Database have table: $tables!": "Database have no $tables table!";
  //  echo $msg.PHP_EOL;
  
  // // districts 資訊 
//   $town = [];
//   $pathJson = '/var/www/html/weather/backendtraining-t4/whatever/*.*';
//   foreach(glob($pathJson) as $jsonFileName){
//     $fileName = pathinfo($jsonFileName, PATHINFO_FILENAME);   
//     $splice = mb_substr($fileName,-2,2, 'UTF-8');

//     if(!str_contains("$splice","區")){
//       $splice = $splice.'區';
//     }

//   // Insert into data to users table
//     $db->insert(array(
//     'name' => $splice
//     ))->into($tables);
//     // array_push($town, $splice);
//   }
  
//   $result = $db->from($tables)
//              ->select()
//              ->all();
//   // echo "$tables row data: "; print_r($result);

//   // 留下 table: users, 將其當前的 row data 全部刪除
//   // $db->schema()->truncate('users');
//   // $result = $db->from('users')
//   //            ->select()
//   //            ->all();
//   // echo "delete users row data: "; print_r($result);

//   // $columns = $db->schema()->getColumns('users', true);
//   // echo "users col 內容： "; print_r($columns);

  
// // echo "篩選 filename 的 town name: "; print_r($town);
// const BASE_DISTRICTS = [
//         '南區', '北區', '安平區', '左鎮區', '仁德區', '關廟區', '官田區', '麻豆區', '佳里區', '西港區', '七股區', '將軍區', '學甲區',
//         '北門區', '新營區', '後壁區', '白河區', '東山區', '下營區', '柳營區', '鹽水區', '山上區', '安定區',
//     ];
//     $result = array_intersect(BASE_DISTRICTS, $town);
// echo "兩陣列之交集 sort: " ;   print_r($result);

// Json data to mysql:
// Read the json file in php
// Convert JSON String into PHP Array
// 加工 array json data
// Insert JSON to MySQL Database with PHP Code

// Create tables2 = rainfalldata
// $tables = 'rainfall';
// $db->schema()->drop($tables);
// $db->schema()->create($tables, function(CreateTable $table){
//   $table->integer('id')->primary()->autoincrement();
//   $table->string('name', 8);
//   $table->dateTime('datetime');
//   $table->float('rainfall');
// });


// // $pathJsonRowData = '/var/www/html/weather/backendtraining-t4/rainfallData/';
// // $pathJsonRowData = '/var/www/html/weather/backendtraining-t4/whatever';
// // many jsonfile push into $rainfallData array
// $rainfallData = [];
// foreach (glob($pathJson) as $jsonFileName) {
//    $fileName = pathinfo($jsonFileName, PATHINFO_FILENAME);
//    $splice = mb_substr($fileName,-5,5, 'UTF-8');

//    if(!str_contains("$splice","區")){
//     $splice = $splice.'區';
//    }
//     $data = json_decode(file_get_contents($jsonFileName), true);
       
    
//     $rainfallData[$splice] = $data;
  
// }
// // print_r($rainfallData);
// // print_r($rainfallData['apple']);
// // $count1 = count($rainfallData);
// // $count2 = count($rainfallData['apple']);
// // echo "rainfallData 內含地區： $count1 ， 地區 0 之資料筆數: $count2".PHP_EOL;

// // 重構 rainfallData 內容
// function transpose($rainfallData){
//   $i = 0;
//   $result = [];
//   foreach($rainfallData as $town => $rowdata){
//     foreach($rowdata as $key => $value){
//       // 地區
//       $result[$i][0] = $town;
//       // 日期
//       $result[$i][1] = $key;
//       // 地區
//       $result[$i][2] = $value;
//       $i++; 
//     }
    
//   }
//   return $result; 
// }

// $refactorRainfallData = transpose($rainfallData);
// echo PHP_EOL."重構後的 rainfallData： ";print_r($refactorRainfallData);

// // $refactorRainfallData insert into mysql
// function importData($refactorRainfallData, $db, $tables){

//   $refactorRainfallDataKey = count($refactorRainfallData);
//   for($i = 0; $i < $refactorRainfallDataKey; $i++ ){
//     $name = $refactorRainfallData[$i][0];
//     $date = $refactorRainfallData[$i][1];
//     $rainfall = $refactorRainfallData[$i][2];

//     // echo "name: $name, date: $date, rainfall: $rainfall".PHP_EOL;

//     try{
//       $db->insert(array(
//         'name' => $name,
//         'datetime' => $date,
//         'rainfall' => $rainfall
//         ))->into($tables);
//     }catch(Exception $e){
//       echo $e->getMessage();
//     }
//   }
//   echo "Insert RainfallData into MySQL Sucess!";
// }  
// importData($refactorRainfallData, $db, $tables);

$town = $db->from('districts')->select(['name'])->all();


foreach($town as $key => $value){
    foreach($value as $subKey => $subValue){
      $townName[] = $subValue;
    }
  }
  // print_r($townName);
  
  // total rainfall of town by 2015 ~ 2018
  $totalRainfall = $db->from(['rainfall'=>'r'])
             ->Join('districts', function($join){
                $join->on('r.name','districts.name' );
             })->where('r.datetime')->between("2015-01-01 00:00:00","2018-12-31 23:59:59")->groupBy('r.name')
             ->select(function($include){
              $include->column('r.name');
              $include->sum('r.rain', '總雨量');
             })
             ->all();
  // echo "分地區, 顯示「地區」 和 該地區 2015年 ~ 2018年 的「總合雨量」".PHP_EOL;print_r($totalRainfall);

  // every year total rainfall of town (have no year)
  for($year = 2015; $year<=2018; $year++){
    $yearRainfall[] = $db->from(['rainfall'=>'r'])
             ->Join('districts', function($join){
                $join->on('r.name','districts.name' );
             })->where('r.datetime')->between("$year-01-01 00:00:00","$year-12-31 23:59:59")->groupBy('r.name')
             ->select(function($include){
              $include->column('r.name');
              // $include->column('r.datetime');
              $include->sum('r.rain', 'rain');
             })
             ->all();
    
  }
// echo "分地區, 顯示「地區」 和 該地區 每年的「年度雨量總和」, 無法顯示 Year".PHP_EOL;print_r($yearRainfall);

// 重構 $yearRainfall, 再將重構後的結果 ($result) 輸出
// every year total rainfall of town (show every year)
$result = [];
$year = 2015;
$i = 0;
foreach($yearRainfall as $key => $value){
  foreach($value as $subKey => $subValue){
    foreach($subValue as $lastKey => $lastValue){
    //  echo $key.PHP_EOL;
    $result[$i]["$lastKey"] = $lastValue;
    $result[$i]["year"] = $key + $year;
    }
    $i++;
  }
}
// echo "分地區, 顯示「地區」 和 該地區 每年的「年度雨量總和」、Year".PHP_EOL;print_r($result);

// Get year and month of rainfall
$maxDatetime = $db->from('rainfall')->max('datetime');
$maxYear = substr($maxDatetime, 0, 4);
$minDatetime = $db->from('rainfall')->min('datetime');
$minYear = substr($minDatetime, 0, 4);
echo "min datetime: $minDatetime, max datetime: $maxDatetime".PHP_EOL;
echo "min year: $minYear, max year: $maxYear".PHP_EOL;
for($year = $minYear; $year <= $maxYear; $year++){
  for($i = 1; $i<=12; $i++){
    $month = strlen($i)==1? "0$i":"$i";
    // echo "$year-$month-01 00:00:00 ~ $year-$month-31 23:59:59".PHP_EOL;
  }
}

// Every month total rainfall of town by rainfall.name = '仁德區'
// $distictName = '仁德區';
// $count = 0;
// for($year = $minYear; $year <= $maxYear; $year++){
//   for($i = 1; $i <= 12; $i++){
//     $date = cal_days_in_month(CAL_GREGORIAN, $i, $year);
//     $monthRain = $db->from(['districts'=>'d'])
//            ->leftJoin('rainfall', function($join){
//               $join->on('rainfall.name','d.name' );
//            })
//            ->where('rainfall.datetime')->between("$year-$i-01 00:00:00","$year-$i-$date 23:59:59")->sum('rainfall.rain');
//     //  echo "Hi: $monthRain".PHP_EOL;
//      $count++;
//      $monthRainfall[$year][$i] = $monthRain;
//     // $monthRainfall[$distictName][$year][$i] = $monthRain;

//    }
// }
// echo $count.PHP_EOL;
// print_r($monthRainfall);

// // Every month total rainfall of town group by rainfall.name 
//   for($year = $minYear; $year <= $maxYear; $year++){
//     for($i = 1; $i <= 12; $i++){
//       $date = cal_days_in_month(CAL_GREGORIAN, $i, $year);
//       $monthRain[] = $db->from(['rainfall'=>'r'])
//             ->Join('districts', function($join){
//                 $join->on('r.name','districts.name' );
//             })->where('r.datetime')->between("$year-$i-01 00:00:00","$year-$i-$date 23:59:59")
//             ->groupBy('r.name')
//             ->select(function($include){
//               $include->column('r.name');
//               $include->sum('r.rain', '總雨量');
//              })
//              ->all();
//     }
//   }

// // $count = count($monthRain); 
// // echo $count.PHP_EOL; //108
// // print_r($monthRain);

// // 重構 $monthRain, 再將重構後的結果 ($monthRainfall) 輸出
// // every month total rainfall of town (show every year & month)
// $monthRainfall = [];
// $year = $minYear;
// $i = 0;
// foreach($monthRain as $key => $value){
//   foreach($value as $subKey => $subValue){
//     foreach($subValue as $lastKey => $lastValue){
//     $monthRainfall[$i]["$lastKey"] = $lastValue;
//     $monthRainfall[$i]["year"] = intval($key/12)+$year;
//     $monthRainfall[$i]["month"] = ($key%12)+1; 
//     }
//      $i++;
//   }
  
// }
// print_r($monthRainfall);

// Anonymous function for $splice use
$path = '/var/www/html/weather/backendtraining-t4/whatever/*.*';
$splice = function($path){
  foreach(glob($path) as $jsonFileName){
    $fileName = pathinfo($jsonFileName, PATHINFO_FILENAME);   
    $splice = mb_substr($fileName,-8,8, 'UTF-8');

    if(!str_contains("$splice","區")){
                $splice = $splice.'區';
    }
    $result[] = $splice;
  }
  return $result;
};
// var_dump($splice($path));
// foreach($splice($path) as $town){
//   $db->insert(array(
//   'name' => $town))->into('districts');
// }

foreach(glob($path) as $jsonFileName){
  $jsonString = file_get_contents($jsonFileName);
  $data = json_decode($jsonString, true);
  foreach($splice($path) as $town){
    $rainfallData[$town] = $data;
  }
}
// var_dump($rainfallData);

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

// $refactorRainfallData insert into mysql
$refactorRainfallData = transpose($rainfallData);
print_r($refactorRainfallData);
function importRainfallData($refactorRainfallData, $db, $tables)
{

  $refactorRainfallDataKey = count($refactorRainfallData);
  for ($i = 0; $i < $refactorRainfallDataKey; $i++) {
    $name = $refactorRainfallData[$i][0];
    $date = $refactorRainfallData[$i][1];
    $rainfall = $refactorRainfallData[$i][2];

    // echo "name: $name, date: $date, rainfall: $rainfall".PHP_EOL;

    try {
      $db->insert(array(
        'name' => $name,
        'datetime' => $date,
        'rain' => $rainfall
      ))->into($tables);
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
  echo "Insert RainfallData into MySQL Sucess!" . PHP_EOL;
}  
importRainfallData($refactorRainfallData, $db, 'rainfall');