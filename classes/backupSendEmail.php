<?php 

    function sendemailSEL($option)
    {

        $email_info = $this->get_web_info('system_email');
        $email_config = array();
        foreach ($email_info['system_email']['data'] as $key => $value) {
            $email_config[$value['info_title']] = $value['attribute'];
        }

        // print_r($email_config); exit();

        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';
        $mail = new PHPMailer;
        // $mail->isSMTP();

        $mail->CharSet = "utf-8";
        $mail->IsHTML(true);
        $mail->SMTPDebug = 0;
        $mail->Host = $email_config['SMTP_HOST'];
        $mail->Port = $email_config['SMTP_PORT'];
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = $email_config['SMTP_USER'];
        $mail->Password = $email_config['SMTP_PASS'];



        $mail->setFrom($email_config['SMTP_USER'], $option['sendFromName']);
        if (is_array($option['addAddress'])) {
            foreach ($option['addAddress'] as $key => $value) {
                $mail->AddAddress($value['email'], $value['name']);
            }
        }
        if (is_array($option['addBcc'])) {
            foreach ($option['addBcc'] as $key => $value) {
                $mail->addBcc($value['email'], $value['name']);
            }
        }

        if (is_array($option['addAttachment'])) {
            foreach ($option['addAttachment'] as $key => $value) {
                // echo $value['path'];
                $mail->addAttachment($value['path'], $value['title']);
            }
        }
        $mail->Subject = $option['subject'];
        $mail->msgHTML($option['content']);

        // print_r($mail); exit();
        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }

    function sendemailSELTest($option)
    {

        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';
        // $mail = new PHPMailer;
        $mail = new PHPMailer();
        $mail->IsHTML(true);
        $mail->IsSMTP();
        $mail->SMTPAuth = false; // enable SMTP authentication
        $mail->SMTPSecure = ""; // sets the prefix to the servier
        $mail->Host = "smtp.kku.ac.th"; // sets GMAIL as the SMTP server
        $mail->Port = 25; // set the SMTP port for the GMAIL server
        $mail->Username = ""; // GMAIL username
        $mail->Password = ""; // GMAIL password
        $mail->From = "pparin@kku.ac.th"; // "name@yourdomain.com";
        $mail->FromName = "Test3";  // set from Name
        $mail->Subject = "Test sending mail.";
        $mail->Body = "My Body & <b>My Description</b>";
        $mail->AddAddress("kotbassrock@gmail.com"); // to Address
        if($mail->Send()){
        echo "OK";
        }else{
        echo "Fail";
        }


    }


/*
    Array
(
    [STMP_HOST] => smtp.gmail.com
    [SMTP_PORT] => 587
    [SMTP_USER] => kotdev.wynnsoft@gmail.com
    [SMTP_PASS] => Kotbass23@gmail.com0999275035
    [MAIL_RECEIVE] => kotdev.wynnsoft@gmail.com
)
*/