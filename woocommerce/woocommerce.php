<?php

header('Content-Type: application/json');
$request = file_get_contents('php://input');
$req_dump = print_r( $request, true );
$json_data = file_put_contents( 'request.log', $req_dump );
$action = json_decode($json_data, true);

?>
