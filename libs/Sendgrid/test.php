<?php

// require $_SERVER['DOCUMENT_ROOT'] . '/libs/Sendgrid/sendgrid-php.php';

require("sendgrid-php.php");

$email = new \SendGrid\Mail\Mail();
$email->setFrom("test@example.com", "Example User");
$email->setSubject("Sending with SendGrid is Fun");
$email->addTo("test@example.com", "Example User");
$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
$email->addContent(
    "text/html",
    "<strong>and easy to do anywhere, even with PHP</strong>"
);
$sendgrid = new \SendGrid('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');
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}
