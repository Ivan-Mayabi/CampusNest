<?php
// Declare the constants to be used in the payments generation:
$CONSUMER_KEY='UugkIloIFFzhktZQxtQp83K9f5JSfBRU8rHwfGRBedk6DZJH';
$CONSUMER_SECRET='UsAWJycrYBbGjyuc1JDVufI8DG0HpEVTMDWI2GDNpMNTF2r3Tf9Qx1DItSftc8m1';

// Default password and paybill number for the sandbox offered by safaricom
$PASSKEY='bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$BUSINESS_SHORT_CODE = '174379';

// Transaction type
$TRANSACTION_TYPE='CustomerPayBillOnline';

// The various URLs we need.
$CALLBACK_URL = 'https://mayabi.me/callback.php';
$AUTH_URL= 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$INITIATOR_URL='https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

// Get the access token
function get_access_token($auth_url,$consumer_key,$consumer_secret){
    // The credentials
    $credentials = base64_encode($consumer_key.':'.$consumer_secret);

    // Go to the authenticating URL using curl and get the access token 
    $ch = curl_init($auth_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER,['Authorization: Basic '.$credentials]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $response= curl_exec($ch);
    $status_code= curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status_code !== 200){
        // If the status code of the response is not 200, handle the error
        error_log("Failed to get access token. Status: $status_code. Response: $response");
        return null;
    }

    $result = json_decode($response);  
    return $result->access_token ?? null;
}

// Send the data to the daraja API and get a response
if ($_SERVER['REQUEST_METHOD']==='POST'){
    $phone = filter_input(INPUT_POST, 'phone_number');
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_INT);
    $account_reference = 'PAYMENT'.time();

    if(!$phone || !$amount || $amount<1){
        print("<script>window.alert('Please check the phone number and amount that you have entered')</script>");
    }
    else{
        // Make phone number to be in the 254XXXXXXXXX format
        $phone = preg_replace('/^0/','254',$phone);
        $phone = preg_replace ('/^(\+254)/','254',$phone);

        // Get the access token
        $access_token = get_access_token($AUTH_URL, $CONSUMER_KEY, $CONSUMER_SECRET);

        if($access_token){
            // Generate the password
            $timestamp = date('Ymdhis');
            $password = base64_encode($BUSINESS_SHORT_CODE.$PASSKEY.$timestamp);

            // Generate the header and the content fields
            $send_header=[
                'Content-type: application/json',
                'Authorization: Bearer '.$access_token
            ];
            $send_body=[
                'BusinessShortCode'=>$BUSINESS_SHORT_CODE,
                'Password'=>$password,
                'Timestamp'=>$timestamp,
                'TransactionType'=>$TRANSACTION_TYPE,
                'Amount'=>$amount,
                'PartyA'=>$phone,
                'PartyB'=>$BUSINESS_SHORT_CODE,
                'PhoneNumber'=>$phone,
                'CallBackURL'=>$CALLBACK_URL,
                'AccountReference'=> $account_reference,
                'TransactionDesc'=> 'Test Payment STK Push'
            ];

            // Send the data to the url
            $ch = curl_init($INITIATOR_URL);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $send_header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($send_body));
            curl_setopt($ch, CURLOPT_HEADER, false);
            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response);

            // Handle if it has made sense
            if(isset($result->ResponseCode) && $result->ResponseCode == '0') {
                print("<script>window.alert('Check your phone')</script>");
            }
            else{
                $error_message = $result->ResponseDescription ?? $result->errorMessage ?? "An Unknown Error Occurred";
                echo $access_token;
                echo "<pre>";
                print_r($result);
                echo "</pre>";
                print("<script>window.alert('STK push failed:".htmlspecialchars($error_message)."')</script>");
                print("<script>window.alert('There has been an error')</script>");
            }
        } 
        else{
            print("<script>window.alert('Could not Authenticate with Daraja API, check your Consumer Key/ Consumer Secret')</script>");
        }       
    }
}
?>

<!DOCTYPE HTML>
<head>
    <title>Daraja API test</title>
</head>
<body>
    <form method="POST" action="payments.php">
        <Label>Phone Number</Label>
        <input type=text name="phone_number">
        <Label>Amount</Label>
        <input type=number name="amount">
        <button type=submit>Pay</button>
    </form>
</body>