<?php
error_reporting(-1);
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */


// GET route

$app->get('/', function () {

        $response['status']='fail';

        $response['data']['code']=1004;
      
        echoRespnse(404,$response);  
    }
);

// POST route
$app->post('/register', function () use ($app) {

    //Get post Data
        $username=$app->request->post('username');
        $email=$app->request->post('email');
        $password=$app->request->post('password');
    
        if (isset($username) && isset($email) && isset($password)) {
            //Create Instance of Register class
            require ('include/Register.php');
            $register=new Register();
            $response=$register->register($username,$email,$password);
        } else {
           $response['status']='fail';
           $response['data']['code']=1001;
        }

        echoRespnse(200,$response);
      
    }
);

$app->post('/login', function () use ($app) {

    //Get post Data
        $username=$app->request->post('username');
        $latitude=$app->request->post('latitude');
        $longitude=$app->request->post('longitude');
        $channel=$app->request->post('channel');
        $password=$app->request->post('password');
    
        if (isset($username) && isset($latitude) && isset($longitude) && isset($channel) && isset($password)) {
            //Create Instance of Register class
            require ('include/Login.php');
            $login=new Login();
            $response=$login->login($username,$latitude,$longitude,$channel,$password);
        } else {
           $response['status']='fail';
           $response['data']['code']=1001;
        }
        //$response['test']='Test Message';
        echoRespnse(200,$response);
      
    }
);

//Check Unique username for registration

$app->get('/checkUser', function () use ($app){
       $username=$app->request->get('username');
       if ($username) {
            require ('include/Register.php');
            $register=new Register();
            if($register->checkUserName($username)) {
                $response['status']='success';
                $response['data']['unique']=TRUE;
            } else {
                $response['status']='success';
                $response['data']['unique']=False;
            }
            
       } else {
            $response['status']='fail';
            $response['data']['code']=1001;
       }
       echoRespnse(200,$response);
       
    }
);

//Check Unique username for registration

$app->get('/checkEmail', function () use ($app){
       $email=$app->request->get('email');
       if ($email) {
            require ('include/Register.php');
            $register=new Register();
            if($register->checkEmail($email)) {
                $response['status']='success';
                $response['data']['unique']=True;
            } else {
                $response['status']='success';
                $response['data']['unique']=False;
            }
            
       } else {
            $response['status']='fail';
            $response['error']['code']=1001;
       }
       echoRespnse(200,$response);
       
    }
);

$app->post('/updateLocation', function () use ($app) {

    //Get post Data
        $username=$app->request->post('username');
        $latitude=$app->request->post('latitude');
        $longitude=$app->request->post('longitude');
        $channel=$app->request->post('channel');
    
        if (isset($username) && isset($latitude) && isset($longitude) && isset($channel)) {
            //Create Instance of Register class
            require ('include/Activity.php');
            $activity=new Activity();
            $response=$activity->updateLocation($username,$latitude,$longitude,$channel);
        } else {
            $response['status']='fail';
            $response['error']['code']=1001;
        }

        echoRespnse(200,$response);
      
    }
);


$app->get('/nOfOnline', function () use ($app) {

    //Get get Data
        $username=$app->request->get('username');
        $latitude=$app->request->get('latitude');
        $longitude=$app->request->get('longitude');
        $channel=$app->request->get('channel');
    
        if (isset($username) && isset($latitude) && isset($longitude) && isset($channel)) {
            //Create Instance of NumberOfUser class
            require ('include/NumberOfUser.php');
            $NumberOfUser=new NumberOfUser();
            $response=$NumberOfUser->nOfOnline($username,$latitude,$longitude,$channel);
        } else {
            $response['status']='fail';
            $response['data']['code']=1001;
        }

        echoRespnse(200,$response);
      
    }
);


$app->get('/getSipFriend', function () use ($app){
       $username=$_GET['username'];
       if ($username) {
            require ('include/Dialplan.php');
            $dialplan=new Dialplan();
           echo $dialplan->getSipFriend($username);
            //echo "SIP/sayem1";
 
       } else {
            echo 'SIP/';
       }
          
    }
);

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);
 
    // setting response content type to json
    $app->contentType('application/json');
 
    echo json_encode($response);
}

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
