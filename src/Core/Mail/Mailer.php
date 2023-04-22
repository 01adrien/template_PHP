<?php

namespace Src\Core\Mail;

use Exception;
use Src\Core\Config\Config;
use Src\Core\Enums\MailerSystem;
use Src\Core\Mail\mailSystem\PhpMailer;
use Src\Core\Mail\mailSystem\SymphonyMailer;

class Mailer
{
  public static function send(Config $config, mixed $email)
  {
    switch ($config->get('mailer.default')) {
      case MailerSystem::Symphony:
        return SymphonyMailer::send($config->get('mailer.symphony'), $email);

      case MailerSystem::Php:
        return PhpMailer::send($config->get('mailer.php'), $email);

      default:
        throw new Exception('you must choose default mailer on config file');
    }
  }
}
