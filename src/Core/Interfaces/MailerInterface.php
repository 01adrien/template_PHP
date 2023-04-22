<?php

namespace Src\Core\Interfaces;

interface MailerInterface
{
  public static function send(array $config, mixed $mail);
}
