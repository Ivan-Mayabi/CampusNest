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
$CALLBACK_URL = 'https://mayabi.me/payments/callback.php';
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My Homes - Campus Nest</title>
    <link rel="stylesheet" href="Stud_homepage.css" />
    <style>
        /* Base styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", sans-serif; background-color: #fff8e7; height: 100vh; display: flex; }
        .page-wrapper { display: flex; width: 100%; }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #b3395b;
            padding: 20px 0;
            min-height: 100vh;
        }
        .logo-container {
            width: 140px;
            height: 100px;
            margin: 20px auto;
            background-image: url('../Desktop15/logo.png');
            background-size: cover;
            border-radius: 6px;
        }
        .sidebar ul { list-style: none; }
        .sidebar ul li { margin: 20px 0; text-align: center; }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
        }
        .sidebar ul li.active a,
        .sidebar ul li a:hover {
            background-color: #e37c74;
            border-radius: 6px;
        }

        /* Main content */
        .container {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .container h2 {
            color: #b3395b;
            margin-bottom: 20px;
        }

        /* Property cards */
        .property-card {
            display: flex;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            align-items: space-evenly;
            gap: 20px;
            max-width: 350px;
        }
        .property-card img {
            width: 140px;
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .property-details h3 {
            margin-bottom: 8px;
            color: #333;
        }
        .property-details ul {
            list-style-type: disc;
            padding-left: 20px;
            color: #555;
        }

        /* Status badge */
        .property-status {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
            color: white;
        }
        .status-approved { background-color: #b3395b; }
        .status-pending { background-color: #e7a57b; }
    </style>
</head>

<body>
<div class="page-wrapper">
    <div class="sidebar">
        <div class="logo-container"></div>
        <ul>
            <li><a href="../Desktop18/PropertySearch.html">SEARCH</a></li>
            <li class="active"><a href="#">MY HOMES</a></li>
            <li><a href="../Logout/logout.php">SIGN OUT</a></li>
        </ul>
    </div>

    <div class="container" style="padding-top:25vh;padding-left:20vw;">
        <form method="POST" action="payments.php" style="font-size:large">
            <div class="property-card">
                <Label>Phone Number</Label>
                <br>
                <input type=text name="phone_number">
            </div>
            <div class="property-card" style="margin-top:5vh;">
                <Label>Amount</Label>
                <br>
                <input type=number name="amount">
            </div>
            <div class="property-card" style="margin-top:5vh;background-color:inherit;padding-left:8vw;">
                <button class="property-status status-approved" type=submit>Pay</button>
                <a class="property-status status-approved" href="../Desktop22/Stud_homepage.php" style="text-decoration:none" type=submit>Back</a>
            </div>
        </form>
    </div>
</body>