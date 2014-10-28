<?php
$to = 'frederic.aoustin@gmail.com';
$subject = '[SOL World Cup Game] Welcome';
$template = "jqzrfhoqh";
$body = $template;
$header = 'From: World Cup 2014 <noreply@worldcup2014.olympe.in>'."\r\n";

mail($to, $subject, $body, $header);
?>