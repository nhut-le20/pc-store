<?php
namespace App\Core;

class FlashMessage {

    public static function set($key, $message, $type = 'success') {
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }

        $_SESSION['flash_messages'][$key] = [
            'message' => $message,
            'type' => $type
        ];
    }

    public static function display() {
        if (isset($_SESSION['flash_messages'])) {
            foreach ($_SESSION['flash_messages'] as $flash) {

                $class = ($flash['type'] === 'success') 
                        ? 'flash-success' 
                        : 'flash-error';

                echo "<div class='flash-message {$class}'>"
                    . htmlspecialchars($flash['message']) .
                    "</div>";
            }

            unset($_SESSION['flash_messages']);
        }
    }
}