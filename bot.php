<?php

	include_once ('classes.php');
		
	if (!isset($_REQUEST)) 
		return; 
	
	$vk = new Soket();
	$vk->Handler();
	
	while(1)
	{
		set_time_limit(4000);
	
		$host = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
		$email = 'some_name@gmail.com';
		$pass = 'some_pass';
		$connect = imap_open($host, $email, $pass)
			or die("can't connect: ".imap_last_error()); ;
			
		$new_mails = imap_search($connect, 'UNSEEN');
		$tmp = count($new_mails);
		$new_mails = implode(",", $new_mails);
		
		while($tmp != 0)
		{
			$overview = imap_fetch_overview($connect,$new_mails,0);
						 
			foreach ($overview as $ow) 
			{ 
				$subject = iconv_mime_decode($ow->subject,0,"UTF-8");
				$body = imap_fetchbody($connect,$ow->msgno,1);
				$body = base64_decode($body);
				$from = $ow->from;
					
				$pos1 = strpos($from, '<');
				$pos2 = strpos($from, '>');
				$from1 = substr($from, 10, $pos1 - 10);
				$from1 = base64_decode($from1);
				$from = substr($from, $pos1, $pos2 - $pos1 + 1);
			
				$text = "Новое сообщение:\nОт: " . $from1 . " " . $from . "\nТема: " . $subject . "\nТекст письма: " . $body;
							 
				$vk->Send_Msg($text);
			}	
			$tmp--;
		}
						
		imap_close($connect);
		
		sleep(60);
	}	
?>