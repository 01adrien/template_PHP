<?php

namespace Src\App\Entities;

use DateTime;
use Src\Core\Abstracted\Entity;

/** @psalm-suppress PropertyNotSetInConstructor */
class UserActivation extends Entity
{
    /** @var string */
    public string $activation_code;
    
    /** @var string */
    public string $expire_at;

    /** @var string */
    public ?string $activated_at = null;

    /** @var int */
    public int $user_id;

    public function __construct()
    {
    }
    public function setActivationCode(string $code): self
    {
        $this->activation_code = password_hash(
            $code,
            PASSWORD_BCRYPT,
            ["cost" => 12]
        );
        return $this;
    }

    public function setExpireAt(): self
    {
        $expireTimeStamp = time() + 24 * 60 * 60;
        $expireDate = new DateTime("@$expireTimeStamp");
        $this->expire_at = $expireDate->format('Y-m-d H-i-s');
        return $this;
    }

    public function setUserId(int $id): self
    {
        $this->user_id = $id;
        return $this;
    }
}