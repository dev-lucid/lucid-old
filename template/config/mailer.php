<?php 
global $lucid;

# a new object with this class name will be instantiated
$lucid->config['mailer']['class'] = 'PHPMailer';

# these files will be included the first time the mailer is instantiated
$lucid->config['mailer']['includes'] = [
    __DIR__.'/../vendor/phpmailer/phpmailer/class.phpmailer.php',
    __DIR__.'/../vendor/phpmailer/phpmailer/class.pop3.php',
    __DIR__.'/../vendor/phpmailer/phpmailer/class.smtp.php',
];

# these properties will be set with the corresponding values
$lucid->config['mailer']['properties'] = [
    'Mailer'     => 'smtp',
    'Host'       => 'mailtrap.io',
    'SMTPAuth'   => true,
    'Username'   => '3449835de2e4e4642',
    'Password'   => '258824957d2d8b',
    'Port'       => 2525,  
    'From'       => 'Accounts@devlucid.com',
    'FromName'   => 'Account Services',
    #'SMTPSecure' => 'tls',
];
