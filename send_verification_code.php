<?php
// send_verification_code.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Generate a random 6-digit verification code
    $verification_code = sprintf('%06d', mt_rand(0, 999999));

    // Set the expiration time to 10 minutes from now
    $expiration_time = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    // Store the verification code and expiration time in the verification_codes table
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "isp";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $user_id_query = "SELECT userid FROM userlogincredentials WHERE email = '$email'";
    $user_id_result = mysqli_query($conn, $user_id_query);

    if ($user_id_result) {
        $user_id_row = mysqli_fetch_assoc($user_id_result);
        $user_id = $user_id_row["userid"];

        $insert_code_query = "INSERT INTO verification_codes (user_id, code, expires_at) VALUES ('$user_id', '$verification_code', '$expiration_time')";
        $insert_code_result = mysqli_query($conn, $insert_code_query);

        if (!$insert_code_result) {
            echo "Failed to generate and store verification code. Please try again later.";
            exit();
        }

        // Your email sending logic with mail function
        $subject = "Verification Code";
        $message = "Your verification code is: $verification_code";
        $headers = 'From: your@gmail.com'; // Replace with your Gmail email address

        // Replace the email sending part with your actual email sending code
        if (mail($email, $subject, $message, $headers)) {
            echo "Verification code sent successfully.";
        } else {
            echo "Failed to send verification code. Please try again later.";
        }
    } else {
        echo "Email not found. Please check your email address.";
    }

    mysqli_close($conn);
} else {
    echo "Invalid request.";
}
?>
