<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Simple example to get started

// This can be run by a cronjob for example (no need to time it exactly,
// just run it often enough). It checks if the already existing certificate
// needs to be renewed before making any connection to the CA

// Require ACMECert
require 'ACMECert.php';
use skoerfgen\ACMECert\ACMECert;

// Choose Certificate Authority (CA)

$thumucht = $_SERVER['DOCUMENT_ROOT'];
$tenmienht = $_SERVER['HTTP_HOST'];

$ac=new ACMECert('https://api.buypass.com/acme/directory');
/*
Live CA

$ac=new ACMECert('https://acme-v02.api.letsencrypt.org/directory');
Staging CA

$ac=new ACMECert('https://acme-staging-v02.api.letsencrypt.org/directory');
Buypass
Live CA

$ac=new ACMECert('https://api.buypass.com/acme/directory');
Staging CA

$ac=new ACMECert('https://api.test4.buypass.no/acme/directory');
Google Trust Services
Live CA

$ac=new ACMECert('https://dv.acme-v02.api.pki.goog/directory');
Staging CA

$ac=new ACMECert('https://dv.acme-v02.test-api.pki.goog/directory');
SSL.com
Live CA

$ac=new ACMECert('https://acme.ssl.com/sslcom-dv-rsa');
ZeroSSL
Live CA

$ac=new ACMECert('https://acme.zerossl.com/v2/DV90');
or any other (ACME v2 - RFC 8555) compatible CA
$ac=new ACMECert('INSERT_URL_TO_AMCE_CA_DIRECTORY_HERE');


*/



///exit($ac-> parseCertificate('file://'.__DIR__.'/account_key.pem'));


// Check if the previous certificate needs to be renewed (if there is one already)
if (file_exists(__DIR__.'/fullchain.pem')){
  $days=$ac->getRemainingDays('file://'.__DIR__.'/fullchain.pem');
  if ($days>30) { // renew 30 days before expiry
    echo 'SSL vẫn hoạt động tốt..., bạn không cần làm gì thêm';
    exit();
  } 
}

// Check if account_key.pem exists. If not generate new key and
// register it with the CA and save it.
if (!file_exists(__DIR__.'/account_key.pem')){
  
  // Generate RSA Private Key
  $key=$ac->generateRSAKey(2048);
  
  // load new key into ACMECert
  $ac->loadAccountKey($key);
  
  // Register Account Key with CA
  $ac->register(true,'admin@'.$tenmienht);
  
  // Registration succeeded, save key to account_key.pem
  file_put_contents(__DIR__.'/account_key.pem',$key); 
}else{
  // load existing account key into ACMECert
  $ac->loadAccountKey('file://'.__DIR__.'/account_key.pem');
}


// Get Certificate using http-01 challenge
$domain_config=array(
  $tenmienht =>array('challenge'=>'http-01','docroot'=>$thumucht),

);

$handler=function($opts){
  $fn=$opts['config']['docroot'].$opts['key'];
  @mkdir(dirname($fn),0777,true);
  file_put_contents($fn,$opts['value']);
  return function($opts){
    unlink($opts['config']['docroot'].$opts['key']);
  };
};

// Generate new certificate key
$private_key=$ac->generateRSAKey(2048);

$fullchain=$ac->getCertificateChain($private_key,$domain_config,$handler);

// Success! Save the certificate chain and private key
file_put_contents(__DIR__.'/fullchain.pem',$fullchain);
file_put_contents(__DIR__.'/private_key.pem',$private_key);

if($fullchain != ''){
    echo "Đã lấy xong chứng chỉ SSL, hãy bấm vào cài đặt SSL để sử dụng";
}

