<?php

namespace Src\App\Services;

use DateTime;
use Src\App\Entities\UserActivation;
use Src\App\Models\UserActivationModel;
use Src\Core\Exceptions\UserActivationException;
use Src\Core\Interfaces\UserInterface;
use Src\Core\Interfaces\UserServiceInterface;

class UserActivationService
{
    public function __construct(
        private UserActivationModel $userActivationModel,
        private MailService $mailService,
        private UserServiceInterface $userService
    ) {
    }
    public function handle(UserInterface $user): void
    {
        $code = bin2hex(random_bytes(16));
        $activation = (new UserActivation())
            ->setActivationCode($code)
            ->setExpireAt()
            ->setUserId($user->getId());
        $this->userActivationModel->insert((array) $activation);
        $this->mailService->sendUserActivationEmail($code, $user);
    }
    public function validateAccountActivation(UserInterface $user, string $codeTovalidate): bool
    {
        $erros = [];
        [$activationData] = $this->userActivationModel->findBy('user_id', $user->getId());
        $expireDate = DateTime::createFromFormat('Y-m-d H:i:s', $activationData->expire_at)->getTimestamp();
        $now = (new DateTime('now'))->getTimestamp();

        if (!password_verify($codeTovalidate, $activationData->activation_code)) {
            $erros[] = 'activation code not valid';
        }
        if ($now - $expireDate > 24 * 60 * 60) {
            $this->userService->deleteUser($user);
            $this->userActivationModel->delete($activationData->id);
            $erros[] = 'you reached the limit of activation time';
        }
        if ($erros) {
            throw new UserActivationException($erros);
        }
        $this->userActivationModel->setUserActive(['id' => $user->getId()]);
        return true;
    }
}
