<?php
chdir(dirname(__FILE__));
include '../config.php';
try {
    $tropo = new Tropo();
    $tropo->ask('What is your favorite programming language?', array(
      	'choices'=>'PHP, Ruby(Ruby, Rails, Ruby on Rails), Python, Java(Groovy, Java), Perl',
      	'event'=> array(
            'nomatch' => 'Never heard of it.',
            'timeout' => 'Speak up!',
        )
    ));
    // Tell Tropo how to continue if a successful choice was made
    $tropo->on(
        array(
            array('event' => 'incomplete','say' => new Say("No answer provided")),
            array('event' => 'continue', 'say'=> new Say('Fantastic! I love that, too!'))
        )
    );
    // Render the JSON back to Tropo
    $tropo->renderJSON();
} catch (Exception $ex) {
    echo $ex->getMessage();
}