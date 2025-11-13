<?php
// Logfile for errors
$log_file = ('mpesa_callback.log');
$log_data = file_get_contents("php://input");
file_put_contents($log_file,$log_data,FILE_APPEND);

// Decode data
$callback_data = json_decode($log_data);

// Get the call back data
if(isset($callback_data->TransactionType)){
    $response = [
        'ResultCode' => 0,
        'ResultDesc' => 'Accepted'
    ];
}
elseif(isset($callback_data->TransID)){
    $transactionId = $callback_data->TransID;
    $amount = $callback_data->ATransmount;
    $phoneNumber = $callback_data->MSISDN;
    $customerName = $callback_data->FirstName.' '.$callback_data->MiddleName.' '.$callbackData->LastName;
    $mpesaReceipt = $callback_data->MPESA_ReceiptNo;

    $response = [
        'ResultCode' => 0,
        'ResultDesc' => 'Confirmation Received Successfully'
    ];
}
else{
    $response = [
        'ResultCode' => 'C002',
        'ResultDesc' => 'Unknown M-Pesa callback structure'
    ];
}

// Send response to mpesa
header('Content-Type: application/json');
echo json_encode($response);
exit;


?>