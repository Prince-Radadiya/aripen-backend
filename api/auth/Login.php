<?php 
$allowedOrigins = [
    "http://localhost:5173",
    "https://aripen-frontend.vercel.app"
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

require_once __DIR__ . '/../../Config/Db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}


$data = json_decode(file_get_contents("php://input"),true);

if($data){
    $email =  htmlspecialchars($data['email']);
    $password =  htmlspecialchars($data['password']);

    try{
         $finduser = $db->User->findOne(['email'=>$email]);
    
        if($finduser){
            $role =$finduser['role'];

            $checkpass = $finduser['password'];
            if(password_verify($password ,$checkpass)){

                $_SESSION['user'] = [
                    'eid' => $finduser['empId'],
                    'role' => $role
                ];
                unset($finduser['password']); // remove password before sending
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
    }catch(Exception $e){
    echo json_encode([
        'nya'=>'error',
        'message' => $e->getMessage()
    ]);
}
   
}
?>
