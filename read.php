<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");


require 'database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();


if(isset($_GET['id']))
{
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT,[
        'options' => [
            'default' => 'all_users',
            'min_range' => 1
        ]
    ]);
}
else{
    $id = 'all_users';
}



$sql = is_numeric($id) ? "SELECT * FROM `users` WHERE id='$id'" : "SELECT * FROM `users`"; 

$stmt = $conn->prepare($sql);

$stmt->execute();


if($stmt->rowCount() > 0){
    
    $users_array = [];
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        $user_data = [
            'id' => $row['id'],
            'name' => $row['name'],
            'age' => $row['age']
        ];
       
        array_push($users_array, $user_data);
    }
  
    echo json_encode($users_array);
 

}
else{
   
    echo json_encode(['message'=>'No user found']);
}
?>