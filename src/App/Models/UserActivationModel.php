<?php

namespace Src\App\Models;

use PDO;
use Src\App\Entities\UserActivation;
use Src\Core\Abstracted\Model;
use Src\Core\Interfaces\ConnectionInterface;

class UserActivationModel extends Model
{   
    protected string $table = 'user_activation';
    protected string $entity = UserActivation::class;
    private PDO $pdo;
    public function __construct(
        private ConnectionInterface $connectionInterface
    ) {
        parent::__construct($connectionInterface);
        $this->pdo = $connectionInterface->pdo();
    }

    public function setUserActive(array $params): void
    {
        $sql =
        "UPDATE user SET active = 1 " .
        "WHERE id = :id";
        $sql2 =
        "UPDATE user_activation SET activated_at = NOW() " .
        "WHERE user_id = :id";
        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        $query2 = $this->pdo->prepare($sql2);
        $query2->execute($params);
    }
}