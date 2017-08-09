<?php
class adfSwiftWrapper {
    private $_error_message = null;
    private $message;

    public function __construct() {
        require_once(SITE_BASE_PATH . '/includes/libraries/swiftmailer/lib/swift_required.php');
    }

    public function send_message($subject, $part_plain, $part_html,$replace_vars,$recipient_email,$sender_email) {
        try {

            # create a new message
            $this->message = new Swift_Message($subject);

            if(is_array($replace_vars) && !empty($replace_vars)){
                foreach($replace_vars as $key=>$value){
                    $part_plain = str_replace('{$'.$key.'}',$value,$part_plain);
                    $part_html = str_replace('{$'.$key.'}',$value,$part_html);
                }
            }

            # add the plain text message part
            $this->message->addPart($part_plain,"text/plain");

            # attach inline images to message
            $part_html = $this->_bind_images($part_html);

            # attach html message part
            $this->message->setBody($part_html, "text/html");

            if(!is_array($recipient_email)){
                $recipient_email = array($recipient_email);
            }
            $this->message->setTo($recipient_email);

            if(!is_array($sender_email)){
                $sender_email = array($sender_email);
            }
            $this->message->setFrom($sender_email);

            if (defined('EMAIL_BCC')) {
                $this->message->addBcc(EMAIL_BCC);
            }
            # send the message
            //$transport = Swift_SendmailTransport::newInstance('/bin/mail -bs');
            $transport = Swift_SmtpTransport::newInstance(SMTP_HOST, SMTP_PORT)
            ->setUsername(SMTP_USERNAME)
            ->setPassword(SMTP_PASSWORD);

            $mailer = Swift_Mailer::newInstance($transport);

            if ($mailer->send($this->message)) {
                return true;
            } else {

                throw new Exception("An internal error occurred during sending email");
            }
        } catch (Exception $e) {

            $this->_error_message = $e->getMessage();
            return false;
        }
    }

    private function _bind_images($part_html) {
        # parse out any images in the html source
        if (preg_match_all('#< {0,2}img.*?src {0,2}= {0,2}[\'"](.*?)[\'"].*?>#im', $part_html, $matches)) {
            # remove duplicate images
            $images = array_unique($matches[1]);

            # remove all images that begin with http
            foreach ($images as $k => $path) {
                if (preg_match('#^https{1,2}://#i', $images[$k])) {
                    unset($images[$k]);
                }
            }

            # are there any left?
            if (count($images) < 1) {
                return $part_html;
            }

            # create a container for the attachment ids
            $attachment_ids = array();

            # attach each image to the message
            foreach ($images as $k => $path) {
                if (file_exists(SITE_BASE_PATH . '/timThumb/email_images/' . $path)) {
                    $attachment_ids[$path] = $this->message->embed(Swift_Image::fromPath(SITE_BASE_PATH . '/timThumb/email_images/' . $path));

                }
            }

            # replace all of the image paths with the associated image id
            foreach ($attachment_ids as $path => $attachment_id) {
                $part_html = str_replace($path, $attachment_id, $part_html);
            }
        }

        return $part_html;
    }

    public function get_error() {
        return $this->_error_message;
    }

}