<?php
session_start();
include "../Partials/db_connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../library/PHPMailer/PHPMailer.php'; // PHPMailer लोड करें (Composer से)
require '../library/PHPMailer/SMTP.php'; // PHPMailer लोड करें (Composer से)
require '../library/PHPMailer/Exception.php'; // PHPMailer लोड करें (Composer से)
require '../Partials/db_connection.php'; // डेटाबेस कनेक्शन फ़ाइल इंक्लूड करें
require '../library/PHPMailer/POP3.php'; // PHPMailer लोड करें (डाउनलोड करके)

// Function to generate a 6-digit OTP
function generateOTP() {
    return rand(1000, 9999);
}

// Check if email is set
if (!isset($_SESSION['email'])) {
    $_SESSION['message'] = "Please request an OTP first.";
    header("Location: _reset_credentials.php");
    exit();
}

$email = $_SESSION['email'];
$otp = generateOTP();
$_SESSION['otp'] = $otp;

$mail = new PHPMailer(true);
try {

    $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'idiscuss.smtp@gmail.com'; // admin gmail
        $mail->Password = 'wfxj vsfy krzq xyxd'; // App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('idiscuss.smtp@gmail.com', 'iDiscuss Support');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "
            <div style='max-width: 600px; margin: auto; font-family: Arial, sans-serif; border: 1px solid #ddd; padding: 20px; border-radius: 10px; background-color: #f9f9f9;'>
                <div style='text-align: center; padding-bottom: 10px;'>
                    <h2 style='color: #333; margin-bottom: 5px;'>🔒 Password Reset Request</h2>
                    <p style='color: #555;'>You're receiving this email because a request was made to reset your password.</p>
                </div>
                <div style='text-align: center; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
                    <h3 style='color: #007bff; margin-bottom: 10px;'>Your OTP Code:</h3>
                    <p style='font-size: 24px; font-weight: bold; color: #d9534f; margin: 10px 0;'>$otp</p>
                    <p style='color: #777; font-size: 14px;'>This OTP is valid for <b>3 minutes</b>. Do not share it with anyone.</p>
                </div>
                <div style='margin-top: 20px; text-align: center;'>
                    <p style='color: #666; font-size: 14px;'>If you did not request this, please ignore this email or contact support.</p>
                    <p style='color: #999; font-size: 12px;'>© 2024 iDiscuss. All rights reserved.</p>
                </div>
            </div>
        ";


    

    if ($mail->send()) {
        $_SESSION['message'] = "📩 New OTP has been sent to your email!";
    } else {
        $_SESSION['message'] = "❌ Failed to send new OTP. Try again later.";
    }
} catch (Exception $e) {
    $_SESSION['message'] = "❌ Email not sent: " . $mail->ErrorInfo;
    error_log("PHPMailer Error: " . $mail->ErrorInfo);
}

header("Location: _verify_otp.php");
exit();
?>
