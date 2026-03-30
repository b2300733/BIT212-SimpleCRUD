<?php
require 'vendor/autoload.php';

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;

// Create client
$client = new SecretsManagerClient([
    'version' => 'latest',
    'region' => 'us-east-1'
]);

$secret_name = 'bit212-db-secret';

try {
    $result = $client->getSecretValue([
        'SecretId' => $secret_name,
    ]);

    // Convert JSON string to PHP array
    $secret = json_decode($result['SecretString'], true);

    // Extract values
    $host = $secret['host'];       // your RDS endpoint
    $user = $secret['username'];
    $pass = $secret['password'];
    $db   = $secret['dbname'];

    // Connect to RDS
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

} catch (AwsException $e) {
    die("Error retrieving secret: " . $e->getMessage());
}
?>
