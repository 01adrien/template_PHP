<?php

namespace Src\App\Models;

use PDO;
use Src\App\Entities\Job;
use Src\Core\Abstracted\Model;
use Src\Core\Interfaces\ConnectionInterface;

class JobModel extends Model
{
    protected string $table = 'jobs';

    protected string $entity = Job::class;

    private PDO $pdo;
    public function __construct(private ConnectionInterface $connectionInterface)
    {
        parent::__construct($connectionInterface);
        $this->pdo = $connectionInterface->pdo();
    }

    public function pickFirstJob(array $params): Job
    {
        $sql =
        "SELECT * FROM {$this->table} " .
        "WHERE is_complete = 0 " .
        "AND attempts < :attempts " .
        "AND queue = :queue LIMIT 1";
        $query = $this->pdo->prepare($sql);
        $this->setFetchMode($query);
        $query->execute($params);
        return $query->fetch();
    }

    protected function countQuery(): string
    {
        return parent::countQuery() .
        'WHERE queue = :queue AND is_complete = 0 AND attempts < :attempts';
    }
}