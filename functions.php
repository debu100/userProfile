<?php
require "./connection.php";
date_default_timezone_set('Asia/Kolkata');

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load .env variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Use a strong secret key (should be 32 bytes for AES-256)
define('SECRET_KEY', $_ENV['SECRET_KEY']); // Example: generated via random_bytes
// 1f4ae84893c4f6b442efc06fccc34ddc should be stored spearately in a .env file to keep it hidden and .env must be added to gitignore

function encrypt($data) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));
    $encrypted = openssl_encrypt($data, 'AES-256-CBC', SECRET_KEY, 0, $iv);
    // Combine IV and encrypted string, base64-encoded
    return base64_encode($iv . $encrypted);
}

function decrypt($data) {
    $data = base64_decode($data);
    $ivLength = openssl_cipher_iv_length('AES-256-CBC');
    $iv = substr($data, 0, $ivLength);
    $encrypted = substr($data, $ivLength);
    return openssl_decrypt($encrypted, 'AES-256-CBC', SECRET_KEY, 0, $iv);
}


// only allow to visit profile page when user is logged in
// function checkLogin(){
// 	if (empty($_SESSION['logged'])) {
// 		// echo "Please Login";
// 		header('Location:./login.php');
// 	}
// }

// only allow to visit profile page when user is logged in
// function checkLogin(){
//     if (empty($_SESSION['logged'])) {
//         echo "<script>
//                 alert('You need to be logged in to view the profile page.');
//                 window.location.href = './login.php';
//               </script>";
//         exit(); // make sure no further code is executed
//     }
// }

function checkLogin(){
    if (empty($_SESSION['logged'])) {
        echo '
        <html>
        <head>
            <style>
                .modal-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100vw;
                    height: 100vh;
                    background-color: rgba(0, 0, 0, 0.5);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 9999;
                }

                .custom-alert {
                    background: #fff;
                    padding: 20px 30px;
                    border-radius: 10px;
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                    text-align: center;
                    font-family: Arial, sans-serif;
                    max-width: 400px;
                    font-family: "Space Grotesk", sans-serif;
                }

                .custom-alert h2 {
                    margin-bottom: 0.5rem;
                    color: #e74c3c;
                    font-family: "Space Grotesk", sans-serif;
                }

                .custom-alert button {
                    margin-top: 1rem;
                    padding: 10px 20px;
                    border: none;
                    background-color: #3498db;
                    color: white;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                    font-family: "Space Grotesk", sans-serif;
                }

                .custom-alert button:hover {
                    background-color: #2980b9;
                }
            </style>
        </head>
        <body>
            <div class="modal-overlay">
                <div class="custom-alert">
                    <h2>Access Denied</h2>
                    <p>You need to be logged in to view the profile page.</p>
                    <button onclick="redirectToLogin()">Login Now</button>
                </div>
            </div>

            <script>
                function redirectToLogin() {
                    window.location.href = "./login.php";
                }
            </script>
        </body>
        </html>
        ';
        exit(); // stop further processing
    }
}

