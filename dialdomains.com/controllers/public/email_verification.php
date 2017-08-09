<!--EMAIL-->

<form action="" method="post">
  <label for="name">Name:</label>
  <input type="text" name="name" id="name" />

  <label for="Email">Email:</label>
  <input type="text" name="email" id="email" />

  <label for="Message">Message:</label><br />
  <textarea name="message" rows="20" cols="20" id="message"></textarea>

  <input type="submit" name="submit" value="Submit" />
</form>
<?php
       // from the form
       $name = trim(strip_tags($_POST['name']));
       $email = trim(strip_tags($_POST['email']));
       $message = htmlentities($_POST['message']);

       // set here
       $subject = "Contact form submitted!";
       $to = 'your@email.com';

       $body = <<<HTML
$message
HTML;

       $headers = "From: $email\r\n";
       $headers .= "Content-type: text/html\r\n";

       // send the email
       mail($to, $subject, $body, $headers);

       // redirect afterwords, if needed
       header('Location: thanks.html');
?>

<!--EMAIL-->


<!--URL VERIFICATION-->

$url = 'http://example.com';
$validation = filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);

if ( $validation ) $output = 'proper URL';
else $output = 'wrong URL';

echo $output;

<!--URL VERIFICATION-->


<!-- RANDOM STRING GENERATOR -->

<?php
$string = "abcdefghigklmnopqrstuvwxyz0123456789";
for($i=0;$i<25;$i++){
   $pos = rand(0,13);
   $str .= $string{$pos};
}
echo $str;
?>

<!-- RANDOM STRING GENERATOR -->