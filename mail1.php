<?php 
$email = "connectrishi06@gmail.com";
$subject =  "Email Test";
$message = "this is a mail testing email function on server 2";
$time = date('H i s');


$sendMail = mail($email, $subject, $time);
print_r($sendMail);
if($sendMail){
	echo "Email Sent Successfully";
}else{
echo "Mail Failed";
}
?>
