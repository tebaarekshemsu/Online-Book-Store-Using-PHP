<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require './PHPMailer-master/PHPMailer-master/src/Exception.php';
  require './PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
  require './PHPMailer-master/PHPMailer-master/src/SMTP.php';


function send_mail($recipient, $subject, $message)
{

  $mail = new PHPMailer();
  $mail->IsSMTP();

  $mail->SMTPDebug  = 0;  
  $mail->SMTPAuth   = TRUE;
  $mail->SMTPSecure = "tls";
  $mail->Port       = 587;
  $mail->Host       = "smtp.gmail.com"; // Change this to smtp.gmail.com
  $mail->Username   = "tamedemse83@gmail.com";
  $mail->Password   = "ebgo oevx esdj tffo";

  $mail->IsHTML(true);
  $mail->AddAddress($recipient, "recipient-name");
  $mail->SetFrom("your-email@gmail.com", "your-sender-name");
  $mail->Subject = $subject;
  $content = $message;

  $mail->MsgHTML($content); 
  if(!$mail->Send()) {
    return false;
  } else {
    return true;
  }

}

if(send_mail("demsetamrat@gmail.com", "testing from php", "hello there")){
    echo "It worked correctly";
}
else{
    echo "Errorrrr happened";
}

?>
