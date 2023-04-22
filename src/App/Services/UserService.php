<?php

namespace Src\App\Services;

use Src\App\Entities\User;
use Src\App\Models\UserModel;
use Src\Core\DataObjects\RegisterUserData;
use Src\Core\Exceptions\AuthException;
use Src\Core\Interfaces\UserInterface;
use Src\Core\Interfaces\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private MailService $mailService,
        private UserModel $userModel,

    ) {
    }

    /**
     * @param int $id
     * @return UserInterface | bool
     */
    public function getById(int $id): ?UserInterface
    {
        $user = $this->userModel->one(['id' => $id]);
        return $user ?? null;
    }

    /**
     *
     * @param string $email
     * @return UserInterface
     */
    public function getByEmail(string $email): ?UserInterface
    {
        /** @var UserInterface $user */
        $user = $this->userModel->findBy('email', $email);
        return $user;
    }

    /**
     * create a new user
     *
     * @param  RegisterUserData $data
     * @return UserInterface
     */
    public function createUser(RegisterUserData $data): UserInterface | bool
    {
        $user = (new User)
            ->setActive(0)
            ->setEmail($data->email)
            ->setPassword($data->password)
            ->setName($data->name)
            ->setCreatedAt(new \DateTime('now'))
            ->setUpdatedAt(new \DateTime('now'))
            ->setRole('user');

        $errors = $this->userModel->validate($user);

        if (!$this->passwordsMatch($data)) {
            $errors->push(
                'password_match',
                'passwords does not match'
            );
        }

        if (!$errors->getErrors()) {
            $user->setPassword(
                $this->hashPassword($data->password)
            );
        } else {
            throw new AuthException($errors->getErrors());
        }

        $insertedID = $this->userModel->insert((array)$user);

        if ($insertedID) {
            $user->id = $insertedID;
        } else {
            throw new AuthException(
                [
                    'register' => '⚠️  service out of reach, try later  ⚠️'
                ]
            );
        }
        return $user;
    }

    /**
     * resetPassword
     *
     * @param  string $email
     * @return void
     */
    public function resetPassword(UserInterface $user): void
    {
        $tempPassword = bin2hex(random_bytes(16));
        /** @var User $user */
        $user->setPassword($this->hashPassword($tempPassword));
        $this->userModel->update($user->getId(), (array)$user);
        $this->mailService->sendTempPasswordEmail($user, $tempPassword);
    }

    /**
     * deleteUser
     *
     * @param  mixed $user
     * @return void
     */
    public function deleteUser(UserInterface $user): void
    {
        $this->userModel->delete($user->getId());
    }

    /**
     * passwordsMatch
     *
     * @param  RegisterUserData $credentials
     * @return bool
     */
    public function passwordsMatch(RegisterUserData $data): bool
    {
        return $data->password === $data->confirm_password;
    }

    /**
     * hashPassword
     *
     * @param  string $password
     * @return string
     */
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}
