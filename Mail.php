<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <title>Send Personalized Email By Uploading CSV</title>
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
        <script src="js/jquery.js"></script>
    </head>
    <body>
        <?php
        // Include PHPMailerAutoload.php library file
        // include("lib/PHPMailerAutoload.php");
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require 'lib/Exception.php';
        require 'lib/PHPMailer.php';
        require 'lib/SMTP.php';


        // $email_sub = "";
        // $msg = "";
        $gift = "";
        $user_list = array();
        $status = array();
        $file_path = "";
        $subject = 'Hi {user}, congratulations!';

        // Retrieving & storing user's submitted information
        if (isset($_POST['gift'])) {
            $coupon = $_POST['gift'];
        }
        if (isset($_POST['user_list'])) {
            $user_list = json_decode($_POST['user_list']);
        }
        // if (isset($_POST['email_sub'])) {
        //     $email_sub = $_POST['email_sub'];
        // }
        // if (isset($_POST['box_msg'])) {
        //     $msg = $_POST['box_msg'];
        // }
        if (isset($_POST['uploaded_file_path'])) {
            $file_path = $_POST['uploaded_file_path'];
        }

        // Sending personalized email
        foreach ($user_list as $list) {
            $receiver_name = "";
            $receiver_add = "";
            // $per_msg = "";
            // $per_email_sub = "";
            $receiver_name = $list[0];
            $receiver_add = $list[1];

            // Replacing {user} with client name from subject and message
            // $per_msg = str_replace("{user}", $receiver_name, $msg);
            // $per_email_sub = str_replace("{user}", $receiver_name, $email_sub);

            // Alvaro Subject modification
            $global_subject = str_replace("{user}", $receiver_name, $subject);

            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->Mailer = "smtp";
            $mail->Host = "smtp.zoho.com";
            $mail->Port = 465;

            // Enable SMTP authentication
            $mail->SMTPAuth = true;

            // SMTP username
            $mail->Username = 'alvaro@lobu.mx';

            // SMTP password
            $mail->Password = 'lobu.servidor.17';

            // Enable encryption, 'tls' also accepted
            $mail->SMTPSecure = 'ssl';

            // Sender Email address
            $mail->From = 'alvaro@lobu.mx';

            // Sender name
            $mail->FromName = "Alvaro";

            // Receiver Email address
            $mail->addAddress($receiver_add);
            //
            // $mail->Subject = $per_email_sub;
            // $mail->Body = $per_msg;
            // $mail->WordWrap = 50;
            //Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $global_subject;
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            // Sending message and storing status
            if (!$mail->send()) {
                $status[$receiver_add] = False;
            } else {
                $status[$receiver_add] = TRUE;
            }
        }
        ?>
        <div id="main" class="col-sm-12 col-md-6 col-lg-6">
            <h1>Message Status</h1>
            <div id="status">
                <ul>
                    <?php
                    foreach ($status as $user => $sent_status) {
                        if ($sent_status == True) {
                            $img = "img/errorFree.png";
                        } else {
                            $img = "img/error.png";
                        }
                        echo "<li> <img src='$img'/>" . $user;
                    }
                    // Deleting iuploaded CSV file from the uploads folder
                    unlink($file_path);
                    ?>
                </ul>
                <a href="index.php" id="more">Send More Emails...</a>
            </div>
        </div>
    </body>
</html>
