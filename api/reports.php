<?php

// GET: api/reports
$app->get('/api/reports/read', function ($request) {
	require_once('db/dbconnect.php');
	
    $data = [];

    foreach ($db->reports() 
        ->order("created_at desc") as $row) {

            array_push($data, (object)[
                'id' => $row['id'],
                'title' => $row['title'],
                'description' => $row['description'],
                'created_at' => $row['created_at'],
                'users_id' => $row['users_id'],
                'user_name' => $row->users['name']
        ]);
        }
	echo json_encode($data, JSON_UNESCAPED_UNICODE);

})->add(\PsrJwt\Factory\JwtMiddleware::html('!secReT$123*', 'jwt', 'api_error_authorisation_failed'));

// POST: api/report/create
$app->post('/api/reports/create', function ($request) {
	require_once('db/dbconnect.php');
	
	$_input = $request->getParsedBody();

    $title = $_input["title"];
    $description =  $_input["description"];
    $users_id =  $_input["users_id"];
	
    $report = $db->reports();
    $insertedReport = $report->insert(array("title" => $title, "description" => $description, "users_id" => $users_id, "created_at" => date("Y-m-d H:i:s")));		

    if(isset($insertedReport)){
        $result = ['status' => "OK", 'report' => $insertedReport];
    }
    else {
        $result= ['status' => "NOK", 'message' => "api_error_something_went_wrong"];
    }
	
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
    
})->add(\PsrJwt\Factory\JwtMiddleware::html('!secReT$123*', 'jwt', 'api_error_authorisation_failed'));

// POST: api/report/update
$app->post('/api/reports/update', function ($request) {
	require_once('db/dbconnect.php');
	
	$_input = $request->getParsedBody();

    $id = $_input['id'];
    $title = $_input["title"];
    $description =  $_input["description"];
    
    if (isset($db->reports[$id])){
        $result = $db->reports[$id]->update(array("title" => $title, "description" => $description));
        
        if ($result) {
            $result = ['status' => "OK", 'report' => $db->reports[$id]];
        } 
        else {
            $result= ['status' => "NOK", 'message' => "api_error_something_went_wrong"];
        }
    } 
    else {
        $result= ['status' => "NOK", 'message' => "api_error_report_not_found"];
    }
	
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
})->add(\PsrJwt\Factory\JwtMiddleware::html('!secReT$123*', 'jwt', 'api_error_authorisation_failed'));

// POST: api/report/delete
$app->post('/api/reports/delete', function ($request) {
	require_once('db/dbconnect.php');
	
	$_input = $request->getParsedBody();
    $id = $_input['id'];
	
    $report = $db->reports[$id];
    if ($report){
        $result = $report->delete();
        
        if ($result) {
            $result = ['status' => "OK"];
        } 
        else {
            $result= ['status' => "NOK", 'message' => "api_error_something_went_wrong"];
        }
    } 
    else {
        $result= ['status' => "NOK", 'message' => "api_error_report_not_found"];
    }
	
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
})->add(\PsrJwt\Factory\JwtMiddleware::html('!secReT$123*', 'jwt', 'api_error_authorisation_failed'));
?>