<?php



    $username = htmlspecialchars($_GET["username"]);
    $password = htmlspecialchars($_GET["password"]);
    $cpanel_host = htmlspecialchars($_GET["iphost"]);


// Đường dẫn đến tệp fullchain.pem
$file_path = 'fullchain.pem';

// Đọc nội dung của tệp
$file_content = file_get_contents($file_path);

// Tìm vị trí của BEGIN CERTIFICATE và END CERTIFICATE đầu tiên
$start_pos = strpos($file_content, "-----BEGIN CERTIFICATE-----");
$end_pos = strpos($file_content, "-----END CERTIFICATE-----", $start_pos);

// Lấy nội dung giữa các thẻ BEGIN CERTIFICATE và END CERTIFICATE đầu tiên
$certificate = substr($file_content, $start_pos, $end_pos - $start_pos + strlen("-----END CERTIFICATE-----"));



// Tìm vị trí của BEGIN CERTIFICATE thứ hai
$start_pos2 = strpos($file_content, "-----BEGIN CERTIFICATE-----", strpos($file_content, "-----END CERTIFICATE-----") + strlen("-----END CERTIFICATE-----"));
$end_pos2 = strpos($file_content, "-----END CERTIFICATE-----", $start_pos2);

// Lấy nội dung giữa các thẻ BEGIN CERTIFICATE và END CERTIFICATE thứ hai
$certificate2 = substr($file_content, $start_pos2, $end_pos2 - $start_pos2 + strlen("-----END CERTIFICATE-----"));

// Hiển thị nội dung với ký tự xuống dòng được chuyển đổi thành thẻ <br>
//echo nl2br($certificate);


// Hiển thị nội dung với ký tự xuống dòng được chuyển đổi thành thẻ <br>
//echo nl2br($certificate);


$khoakey = file_get_contents('private_key.pem');

 
$request_uri = "https://$cpanel_host:2083/execute/SSL/install_ssl";

$urlwebb = $_SERVER['HTTP_HOST'];
// Set up the payload to send to the server.
$payload = array(
    'domain' => $urlwebb,
    'cert'   => $certificate,
    'key'    =>  $khoakey,
    'cabundle'    =>  $certificate2,
);

// Set up the cURL request object.
$ch = curl_init( $request_uri );
curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
curl_setopt( $ch, CURLOPT_USERPWD, $username . ':' . $password );
curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

// Set up a POST request with the payload.
curl_setopt( $ch, CURLOPT_POST, true );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

// Make the call, and then terminate the cURL caller object.
$curl_response = curl_exec( $ch );
curl_close( $ch );


echo $curl_response;


?>
