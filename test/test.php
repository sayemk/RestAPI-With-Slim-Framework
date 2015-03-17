<?php
//
// A very simple PHP example that sends a HTTP POST to a remote site
//

// $ch = curl_init();

// $curl_handle = curl_init();
//     curl_setopt($curl_handle, CURLOPT_URL, 'http://127.0.0.1:8000/CI/application/create/job_id/42/candidate_email/sa@gmail.com.json');
//     //curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
//     //curl_setopt($curl_handle, CURLOPT_POST, 1);
//     //curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array(
//     //     'job_id' => 42,
//     //     'title' => ' title Test',
//     //     'candidate_email'=>'sa@gmail.com',
//     //     'vendor' =>1,
//     //     'key' => 'testkey',
//     //     'password'=>'testpass',

//     // ));

// $server_output = curl_exec ($curl_handle);

// curl_close ($curl_handle);

// // further processing ....
// echo $server_output;
// //echo '<br />'.time();
$title='Test Tiltle';
echo file_get_contents('http://127.0.0.1:8000/CI/index.php/application/create/vendor/1/key/testkey/password/testpass/title/'.$title.'/job_id/42/candidate_email/sa@gmail.com.json')
?>