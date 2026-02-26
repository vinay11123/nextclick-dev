<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * FCM simple server side implementation in PHP
 *
 * @author Abhishek
 */
class Fcm
{

    /** @var string     push message title */
    private $title;

    /** @var string     message */
    private $message;
    
    /** @var string     URL String */
    private $image;

    /** @var array     Custom payload */
    private $data;

    /**
     * flag indicating whether to show the push notification or not
     * this flag will be useful when perform some opertation
     * in background when push is recevied
     */

    /** @var bool     set background or not */
    private $is_background;

    /**
     * Function to set the title
     *
     * @param string    $title  The title of the push message
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Function to set the message
     *
     * @param string    $message    Message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Function to set the image (optional)
     *
     * @param string    $imageUrl    URI string of image
     */
    public function setImage($imageUrl)
    {
        $this->image = $imageUrl;
    }

    /**
     * Function to set the custom payload (optional)
     *
     * eg:
     *      $payload = array('user' => 'user1');
     *
     * @param array    $data    Custom data array
     */
    public function setPayload($data)
    {
        $this->data = $data;
    }

    /**
     * Function to specify if is set background (optional)
     *
     * @param bool    $is_background
     */
    public function setIsBackground($is_background)
    {
        $this->is_background = $is_background;
    }

    /**
     * Generating the push message array
     *
     * @return array  array of the push notification data to be send
     */

public function getPush()
{
    // Prepare the notification and data structures
    $notification = [
        'title' => $this->title,
        'body' => $this->message, // Use 'body' instead of 'message' for display content
    ];

    $data = [
        'is_background' => $this->is_background ? 'true' : 'false', // Convert boolean to string
        'image' => $this->image ? $this->image : '', // Ensure it's a string
        'payload' => json_encode($this->data), // Using json_encode if the data is an array or object
        'timestamp' => date('Y-m-d G:i:s'),
        // Ensure any other fields added are also string type
        // Add other data fields if needed
    ];

    return [
        'message' => [
            'notification' => $notification,
            'data' => $data,
        ]
    ];
}


    /**
     * Function to send notification to a single device
     *
     * @param   string   $to     registration id of device (device token)
     * @param   array   $message    push notification array returned from getPush()
     *
     * @return  array   array of notification data and to address
     */
    public function send($to, $message)
    {
        $fields = array(
            'to' => $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    /**
     * Function to send notification to a topic by topic name
     *
     * @param   string   $to     topic
     * @param   array   $message    push notification array returned from getPush()
     * 
     * @return  array   array of notification data and to address (topic)
     */
    public function sendToTopic($to, $message)
    {
        $fields = array(
            'to' => '/topics/' . $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    /**
     * Function to send notification to multiple users by firebase registration ids
     *
     * @param   array   $to         array of registration ids of devices (device tokens)
     * @param   array   $message    push notification array returned from getPush()
     * 
     * @return  array   array of notification data and to addresses
     */
     
public function sendMultiple($registration_ids, $message, $server_key)
{
    // Get the structured payload from getPush()
    $fields = $this->getPush();

    log_message('error', 'sendMultiple registration_ids: ' . json_encode($registration_ids));
    
    // Initialize an array to store successful tokens
    $successful_tokens = [];
    // Initialize an array to store unregistered tokens for logging or later use
    $unregistered_tokens = [];

    // Iterate over each registration ID to send the notification
    foreach ($registration_ids as $token) {
        // Set the token for the current send
        $fields['message']['token'] = $token;

        // Send the notification and capture the response
        $response = $this->sendPushNotification($fields, $server_key);
        log_message('error', 'FCM Response for token ' . $token . ': ' . json_encode($response));

        // Check if the response indicates an unregistered token
        if (isset($response['error']) && 
            $response['error']['status'] === 'NOT_FOUND' && 
            $response['error']['details'][0]['errorCode'] === 'UNREGISTERED') {
                
            // Remove the unregistered token from your storage
            $this->removeInvalidToken($token);
            log_message('error', 'Removing unregistered token: ' . $token);
            $unregistered_tokens[] = $token; // Collect unregistered tokens for logging
            continue; // Skip to the next token
        }

        // Log success for successful notifications
        if (isset($response['success']) && $response['success'] > 0) {
            log_message('info', 'Notification sent successfully to token: ' . $token);
            $successful_tokens[] = $token; // Collect successful tokens if needed
        } else {
            log_message('error', 'Failed to send notification to token: ' . $token);
        }
    }
    

    return $response;
}


/**
 * Function makes a cURL request to Firebase servers
 *
 * @param   array   $fields      Array containing the FCM payload
 * @param   string  $server_key  The server key used to authorize the request
 * 
 * @return  mixed   Returns result from FCM server as JSON
 */
private function sendPushNotification($fields, $server_key)
{
    // Define valid server keys and corresponding service account JSON paths and URLs
    $validKeys = [
        'User Application' => [
            'serviceAccountJson' => APPPATH . 'config/service_accounts/nextclickuser.json',
            'url' => 'https://fcm.googleapis.com/v1/projects/nextclickuser-df6c4/messages:send'
        ],
        'Vendor Application' => [
            'serviceAccountJson' => APPPATH . 'config/service_accounts/nextclick-crm.json',
            'url' => 'https://fcm.googleapis.com/v1/projects/nextclick-crm-e00a6/messages:send' // Replace with the actual project ID for Vendor Application
        ],
        'Devlivery Partner' => [
            'serviceAccountJson' => APPPATH . 'config/service_accounts/nextclickdelivery.json',
            'url' => 'https://fcm.googleapis.com/v1/projects/nextclickdelivery-88b76/messages:send' // Replace with the actual project ID for Vendor Application
        ]
    ];

    // Logging the server key
    log_message('error', 'Server Key: ' . $server_key);

    // Check if the provided server key is in the list of valid keys
    if (array_key_exists($server_key, $validKeys)) {
        log_message('error', 'Valid server key provided: ' . $server_key);
        
        // Load the service account JSON file and send URL based on the server key
        $serviceAccountJson = $validKeys[$server_key]['serviceAccountJson'];
        $url = $validKeys[$server_key]['url'];
        
        // Log the service account JSON path
        log_message('error', 'Service Account JSON Path: ' . $serviceAccountJson);
        log_message('error', 'Push Notification URL: ' . $url);

        // Generate the access token
        $accessToken = $this->generateAccessToken($serviceAccountJson);

        if (!$accessToken) {
            log_message('error', 'Failed to generate access token for: ' . $serviceAccountJson);
            return ['status' => 'error', 'message' => 'Failed to generate access token'];
        }

        log_message('error', 'Access Token generated successfully: ' . $accessToken);

        // Send the actual push notification using the determined URL
        return $this->sendPush($fields, $accessToken, $url);
    } else {
        log_message('error', 'Invalid server key provided: ' . $server_key);
        return ['status' => 'error', 'message' => 'Invalid server key'];
    }
}

private function sendPush($fields, $accessToken ,$url)
{
   // $url = 'https://fcm.googleapis.com/v1/projects/nextclickuser-df6c4/messages:send'; // Replace with your project ID

    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Only for testing; consider removing in production
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    // Execute post
    $result = curl_exec($ch);

    // Check for cURL errors
    if ($result === false) {
        log_message('error', 'cURL error: ' . curl_error($ch));
        return ['status' => 'error', 'message' => 'cURL error: ' . curl_error($ch)];
    }

    // Always log the result
    log_message('error', 'FCM Response: ' . $result);

    // Close connection
    curl_close($ch);

    return $result;
}
private function sendPush22($fields, $accessToken ,$url)
{
    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);

    if ($result === false) {
        log_message('error', 'cURL error: ' . curl_error($ch));
        return ['status' => 'error', 'message' => curl_error($ch)];
    }

    curl_close($ch);

    log_message('error', 'FCM Raw Response: ' . $result);

    // 🔥 Decode response
    $response = json_decode($result, true);

    // ✅ SUCCESS CHECK
    if (isset($response['name'])) {
        return [
            'status' => 'success',
            'message_id' => $response['name']
        ];
    } else {
        return [
            'status' => 'error',
            'response' => $response
        ];
    }
}

private function generateAccessToken($serviceAccountJsonPath)
{
    // Load service account JSON file
    $serviceAccount = json_decode(file_get_contents($serviceAccountJsonPath), true);
    
    // Check if service account is valid and contains required keys
    if (!$serviceAccount) {
        log_message('error', 'Unable to read service account JSON or invalid format.');
        return null;
    }

    // Log the essential parts for debugging
    log_message('info', 'Service Account Email: ' . $serviceAccount['client_email']);
    log_message('info', 'Private Key Loaded: ' . (isset($serviceAccount['private_key']) ? 'Yes' : 'No'));

    $clientEmail = $serviceAccount['client_email'];
    $privateKey = trim(str_replace('\\n', "\n", $serviceAccount['private_key'])); // Make sure private key is correctly formatted
    
    // Ensure private key is not empty
    if (empty($privateKey)) {
        log_message('error', 'Private key is empty or improperly formatted.');
        return null;
    }

    // Create the JWT
    $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
    $claimSet = json_encode([
        'iss' => $clientEmail,
        'scope' => 'https://www.googleapis.com/auth/cloud-platform', // Ensure this is correct
        'aud' => 'https://oauth2.googleapis.com/token',
        'exp' => time() + 3600, // Token is valid for 1 hour
        'iat' => time()
    ]);

    // Encode to Base64URL
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlClaimSet = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($claimSet));

    // Signing
    $signatureInput = $base64UrlHeader . '.' . $base64UrlClaimSet;
    $signature = '';
    if (!openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
        log_message('error', 'Failed to sign JWT with private key.');
        return null;
    }

    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    
    // Create the final JWT
    $jwt = $signatureInput . '.' . $base64UrlSignature;

    // Prepare the POST request to get the access token
    $url = 'https://oauth2.googleapis.com/token';
    $postFields = http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
    ]);

    // Send the request using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    
    $response = curl_exec($ch);
    
    // Handle cURL errors
    if ($response === false) {
        log_message('error', 'cURL error: ' . curl_error($ch));
        return null;
    }

    curl_close($ch);

    // Decode the response
    $responseData = json_decode($response, true);
    
    // Log response for debugging
    log_message('info', 'Access Token Response: ' . json_encode($responseData));

    // Check for access token in response
    if (isset($responseData['access_token'])) {
        return $responseData['access_token'];
    } else {
        log_message('error', 'Access token not found in response: ' . json_encode($responseData));
        return null;
    }
}
}
