<?php

use PsrJwt\Helper\Request;

// GET: api/users
$app->get('/api/users', function ($request) {
	require_once('db/dbconnect.php');
	
	$data = [];
	foreach ($db -> users() as $row) {
		$data[] = $row;
	}

	echo json_encode($data, JSON_UNESCAPED_UNICODE);

})->add(\PsrJwt\Factory\JwtMiddleware::html('!secReT$123*', 'jwt', 'api_error_authorisation_failed'));;

// POST: api/user
$app->post('/api/users/create', function ($request) {
	require_once('db/dbconnect.php');
	
	$_input = $request->getParsedBody();

    $username = $_input["username"];
    $name =  $_input["name"];
    $password =  $_input["password"];
	
	if(empty($username) || empty($name) || empty($password)) {
		$result= ['status' => "NOK", 'message' => "api_error_all_files_required"];
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
		return;
	}
	
	foreach ($db -> users()->where('username', $username) as $row) {
		$data[] = $row;
	}
	
	if (isset($data)) {
		$result= ['status' => "NOK", 'message' => "api_error_user_exists"];
	}
	else{
		$user = $db->users();
		$insertedUser = $user->insert(array("username"=> $username, "name" => $name, "password" => password_hash($password, PASSWORD_DEFAULT)));
		
		if(isset($insertedUser)){
			$result = ['status' => "OK", 'user' => $insertedUser];
		}
		else{
			$result= ['status' => "NOK", 'message' => "api_error_something_went_wrong"];
		}
	}
	
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
});

// POST: api/user/signin
$app->post('/api/users/signin', function ($request) {
	
	require_once('db/dbconnect.php');
	
	$_input = $request->getParsedBody();

    $username = $_input["username"];
    $password = $_input["password"];
		
	foreach ($db -> users()
				->where('username', $username)
			 as $row) {
			$data[] = $row;
	}

	 if (isset($data)) {
            if(password_verify($password,$row['password'])){

				$factory = new \PsrJwt\Factory\Jwt();

				$builder = $factory->builder();
				
				$token = $builder->setSecret('!secReT$123*')
					->setPayloadClaim('uid', 12)
					->build();

				$result = ['status' => "OK", 'user' => $data, 'token' => $token->getToken()];
			}
			else{
				$result = ['status' => "NOK", 'message' => "api_error_wrong_login"];
			}
	 }
	 else{
		 $result = ['status' => "NOK", 'message' => "api_error_wrong_login"];
	 }
				
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
});
?>