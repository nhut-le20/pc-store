<?php
namespace App\Core;
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {

    public static function send($toEmail, $toName, $subject, $body) {

        require_once __DIR__ . '/../../config.php';

        $mail = new PHPMailer(true);

        try {
            $mail->CharSet = 'UTF-8';

            // 🟢 CẤU HÌNH SMTP
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USERNAME;
            $mail->Password = MAIL_PASSWORD;
            $mail->SMTPSecure = 'tls';
            $mail->Port = MAIL_PORT;

            // 🟢 NGƯỜI GỬI & NGƯỜI NHẬN
            $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
            $mail->addAddress($toEmail, $toName);

            // 🟢 NỘI DUNG
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();

            return true;

        } catch (Exception $e) {
            echo "Lỗi gửi mail: " . $mail->ErrorInfo;
            return false;
        }
    }
}