<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require 'database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();


$data = json_decode(file_get_contents("php://input"));


if(isset($data->id)){
    $msg['message'] = '';
    
    $id = $data->id;
    
   
    $check_users = "SELECT * FROM `users` WHERE id=:id";
    $check_users_stmt = $conn->prepare($check_users);
    $check_users_stmt->bindValue(':id', $id,PDO::PARAM_INT);
    $check_users_stmt->execute();
    
    
    if($check_users_stmt->rowCount() > 0){
        
       
        $delete_user = "DELETE FROM `users` WHERE id=:id";
        $delete_user_stmt = $conn->prepare($delete_user);
        $delete_user_stmt->bindValue(':id', $id,PDO::PARAM_INT);
        
        if($delete_user_stmt->execute()){
            $msg['message'] = 'User Deleted Successfully';
        }else{
            $msg['message'] = 'User Not Deleted';
        }
        
    }else{
        $msg['message'] = 'Invlid ID';
    }
    
    echo  json_encode($msg);
    
}
?>