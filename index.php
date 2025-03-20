<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Initialize variables
$name = $email = $phone = $address = $msg = "";
$nameErr = $emailErr = $phoneErr = $addressErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Name validation
    if (empty($_POST['name'])) {
        $nameErr = "Enter Your Name";
    } else {
        $name = htmlspecialchars($_POST['name']);
    }

    // Email validation
    if (empty($_POST['email'])) {
        $emailErr = "Enter Your Email";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    } else {
        $email = $_POST['email'];
    }

    // Phone validation
    if (empty($_POST['phone'])) {
        $phoneErr = "Phone number is required";
    } elseif (!preg_match("/^[0-9]{10,15}$/", $_POST['phone'])) {
        $phoneErr = "Invalid phone number";
    } else {
        $phone = $_POST['phone'];
    }

    // Address validation
    if (empty($_POST['address'])) {
        $addressErr = "Address is required";
    } else {
        $address = htmlspecialchars($_POST['address']);
    }

    // Message validation
    $msg = htmlspecialchars($_POST['message']);

    // If no errors, send email
    if (empty($nameErr) && empty($emailErr) && empty($phoneErr) && empty($addressErr)) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@gmail.com'; 
            $mail->Password = 'your-app-password'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('your-email@gmail.com', 'Your Name');
            $mail->addAddress('receiver-email@example.com', 'Receiver Name');
            $mail->addReplyTo($email, $name);

            $mail->isHTML(true);
            $mail->Subject = "New Contact Form Submission";
            $mail->Body = "<h3>New Message from Contact Form</h3>
                           <p><b>Name:</b> $name</p>
                           <p><b>Email:</b> $email</p>
                           <p><b>Phone:</b> $phone</p>
                           <p><b>Address:</b> $address</p>
                           <p><b>Message:</b> $msg</p>";

            if ($mail->send()) {
                echo "<script>alert('Email sent successfully!');</script>";
            } else {
                echo "<script>alert('Error: {$mail->ErrorInfo}');</script>";
            }
        } catch (Exception $e) {
            echo "<script>alert('Message could not be sent. Error: {$mail->ErrorInfo}');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form - PHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-lg w-full">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Contact Us</h2>
        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-600 font-medium">Name:</label>
                <input type="text" name="name" value="<?= $name ?>" class="w-full mt-1 p-2 border border-gray-300 rounded-md">
                <p class="text-sm text-red-600"><?= $nameErr ?></p>
            </div>
            <div>
                <label class="block text-gray-600 font-medium">Email:</label>
                <input type="email" name="email" value="<?= $email ?>" class="w-full mt-1 p-2 border border-gray-300 rounded-md">
                <p class="text-sm text-red-600"><?= $emailErr ?></p>
            </div>
            <div>
                <label class="block text-gray-600 font-medium">Phone:</label>
                <input type="text" name="phone" value="<?= $phone ?>" class="w-full mt-1 p-2 border border-gray-300 rounded-md">
                <p class="text-sm text-red-600"><?= $phoneErr ?></p>
            </div>
            <div>
                <label class="block text-gray-600 font-medium">Address:</label>
                <input type="text" name="address" value="<?= $address ?>" class="w-full mt-1 p-2 border border-gray-300 rounded-md">
                <p class="text-sm text-red-600"><?= $addressErr ?></p>
            </div>
            <div>
                <label class="block text-gray-600 font-medium">Message:</label>
                <textarea name="message" class="w-full mt-1 p-2 border border-gray-300 rounded-md" rows="4"> <?= $msg ?> </textarea>
            </div>
            <button class="w-full bg-blue-500 text-white py-2 rounded-md">Submit</button>
        </form>
    </div>
    <footer>
        
    </footer>
</body>
</html>
