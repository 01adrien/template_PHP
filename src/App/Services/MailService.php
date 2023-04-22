<?php

namespace Src\App\Services;

use Src\Core\Config\Config;
use Src\Core\Interfaces\UserInterface;
use Src\Core\Mail\Mailer;

class MailService
{
    public function __construct(
        private QueueService $queue,
        private Config $config
    ) {
    }

    public function sendUserActivationEmail(string $code, UserInterface $user): void
    {
        $emailBody = $this->makeActivationEmailBody($code, $user);
        $this->sendMail($emailBody, $user, 'activate your account');
    }

    public function sendTempPasswordEmail(UserInterface $user, string $tempPassword): void
    {
        $emailBody = $this->makeTempPasswordEmailBody($tempPassword);
        $this->sendMail($emailBody, $user, 'reset password');
    }

    private function sendMail(string $emailBody, UserInterface $user, string $subject): void
    {
        $email = $this->queue->createJob(
            fn (array $params) => Mailer::send($params['config'], $params['mail']),
            [
                'config' => $this->config,
                'mail' => [
                    'to'      => $user->getEmail(),
                    'subject' => $subject,
                    'text'    => 'some text',
                    'html'    => $emailBody
                ]
            ]
        );
        $this->queue->enqueue('email', $email);
    }

    private function makeActivationEmailBody(string $code, UserInterface $user): string
    {
        return "<p>click the button below to activate your account</p>
            <a href='{$this->config->get('baseUrl')}/auth/activation?email={$user->getEmail()}&code=$code'>
            <button>activate account</button>
            </a>
            ";
    }

    private function makeTempPasswordEmailBody(string $tempPassword): string
    {
        return "<p>Below your temporary password</p>
            <p>$tempPassword</p>
            <p>Change it after login</p>
            ";
    }
}
