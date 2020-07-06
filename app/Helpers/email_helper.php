<?php

use Illuminate\Support\Facades\Mail;

/**
 * Sends an email.
 *
 * @param  str  $email    The email
 * @param  str  $subject  The subject
 * @param  str  $message  The message
 *
 * @return bool True if mail is sent. False otherwise.
 */
function send_email($email, $subject, $message)
{
	$data = ['content' => $message];

	Mail::send('email.index',$data,function($message,$subject)
    		{
    			$message->to('jjj@narola.email','jayushi')->subject($subject);

    		});

	// $CI->email->from(get_settings('smtp_user'), get_settings('from_name'));
	// $CI->email->reply_to(get_settings('reply_to_email'), get_settings('reply_to_name'));
	// $CI->email->to($email);

	//  if BCC email is set in settings, send mail to BCC email 
	// if (get_settings('bcc_emails_to') != '')
	// {
	// 	$CI->email->bcc(get_settings('bcc_emails_to'));
	// }

	// $CI->email->subject($subject);
	// $CI->email->message($message);

	// if ($CI->email->send())
	// {
	 	return true;
	// }
	// else
	// {
	// 	return false;
	// }
}
?>