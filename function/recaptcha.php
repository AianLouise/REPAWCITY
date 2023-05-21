<?php
// Your reCAPTCHA secret key
$recaptchaSecretKey = '6LfMVCUmAAAAAPnQvOKXhe3eYihfyS5Zw2AyhSYI';

// Verify the reCAPTCHA response
$recaptchaResponse = $_POST['g-recaptcha-response'];
$recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
$recaptchaData = array(
    'secret' => $recaptchaSecretKey,
    'response' => $recaptchaResponse
);

$recaptchaOptions = array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => http_build_query($recaptchaData)
    )
);

$recaptchaContext = stream_context_create($recaptchaOptions);
$recaptchaResult = file_get_contents($recaptchaUrl, false, $recaptchaContext);
$recaptchaResultJson = json_decode($recaptchaResult);

// Check the reCAPTCHA verification result
if ($recaptchaResultJson->success) {
    // reCAPTCHA verification successful, process the form submission
    // ...
} else {
    // reCAPTCHA verification failed, show an error message
    // ...
}
?>
