<?php
// error function
function send_error($message, $messages = [],$code = 404){
    $response = [
        'status' => false,
        'message' => $message,
    ];
    !empty($messages) ? $response['errors'] = $messages : null;
    return response()->json([$response, $code]);
}

//success function 
function send_success($message,$data = [], $code = 200){
    $response = [
        'status' => true,
        'message' => $message,
        
    ];

    !empty($data) ? $response['data'] = $data : null;

    return response()->json([$response, $code]);
}

