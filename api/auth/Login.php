<?php 
require_once __DIR__ . '/../../Config/Db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


header("Access-Control-Allow-Origin:  http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}


$data = json_decode(file_get_contents("php://input"),true);

if($data){
    $email =  htmlspecialchars($data['email']);
    $password =  htmlspecialchars($data['password']);

    
    $finduser = $db->User->findOne(['email'=>$email]);
    
   if($finduser){
    $role =$finduser['role'];

    $checkpass = $finduser['password'];
    if(password_verify($password ,$checkpass)){

        $_SESSION['user'] = [
            'eid' => $finduser['empId'],
            'role' => $role
        ];
        echo json_encode([
            'session'=>$_SESSION['user'],
             'loggedIn' => true,
             "UserData"=> $finduser
        ]);
    }else{
         echo json_encode([
            'nya'=>'notlogin'
        ]);
    }
   }else{
    echo json_encode([
            'nya'=>'user not found'
        ]);
   }

  
}

?>
