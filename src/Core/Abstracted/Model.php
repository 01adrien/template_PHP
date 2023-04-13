<?php

namespace Src\Core\Abstracted;

use Clockwork\Support\Vanilla\Clockwork;
use PDO;
use PDOStatement;

abstract class Model
{
  protected string $table;
  protected $entity;

  public function __construct(private PDO $pdo)
  {
  }

  /**
   * get all records from selected table
   *
   * @return Entity[] | array
   */
  public function all(): array | Entity
  {
    $query = $this
      ->pdo
      ->query("SELECT * from $this->table");
    $this->setFetchMode($query);
    return $query->fetchAll();
  }

  /**
   * get one record from selected table
   *
   * @param  mixed $id
   * @return Entity | array | bool 
   */
  public function one(array $params): Entity | bool
  {
    $time = microtime(true);
    $query = $this
      ->pdo
      ->prepare($this->getByIdQuery());
    $this->setFetchMode($query);
    $query->execute($params);
    $result = $query->fetch();
    clock()->addDatabaseQuery($this->getByIdQuery(), $params, (microtime(true) - $time) * 1000);
    return $result;
  }

  public function getPaginated(int $limit, int $offset, array $params): Entity | bool | array
  {
    $query = $this
      ->pdo
      ->prepare($this->paginatedQuery($limit, $offset));
    $this->setFetchMode($query);
    $query->execute($params);
    $result = $query->fetchAll();
    return $result;
  }

  /**
   * find a record by provided field
   *
   * @param  mixed $field
   * @param  mixed $value
   * @return Entity | array
   */
  public function findBy(string $field, string $value): Entity | array | bool
  {
    $query = $this
      ->pdo
      ->prepare($this->findByQuery($field));
    $this->setFetchMode($query);
    $query->execute([$value]);
    return $query->fetchAll();
  }

  /**
   * insert a record in the selected table
   *
   * @param  mixed $params
   * @return bool
   */
  public function insert(array $params): bool | int
  {
    $fields = array_keys($params);
    $values = join(', ', array_map(function ($field) {
      return ':' . $field;
    }, $fields));
    $fields = join(', ', $fields);
    $query = $this
      ->pdo
      ->prepare("INSERT INTO {$this->table} ($fields) VALUES ($values)");
    if ($query->execute($params)) {
      return $this->pdo->lastInsertId();
    };
    return false;
  }

  /**
   * update a record in the selected table
   *
   * @param  mixed $id
   * @param  mixed $params
   * @return bool
   */
  public function update(int $id, array $params): bool
  {
    $fieldQuery = $this->buildFieldQuery($params);
    $params["id"] = $id;
    $query = $this
      ->pdo
      ->prepare("UPDATE {$this->table} SET $fieldQuery WHERE id = :id");
    return $query->execute($params);
  }

  /**
   * delete a record by ID
   *
   * @param  mixed $id
   * @return bool
   */
  public function delete(int $id): bool
  {
    $query = $this
      ->pdo
      ->prepare("DELETE FROM {$this->table} WHERE id = ?");
    return $query->execute([$id]);
  }

  /**
   * count records in table
   *
   * @return int
   */
  public function count(array $params): int
  {
    $query = $this
      ->pdo
      ->prepare($this->countQuery());
    $query->execute($params);
    return $query->fetchColumn();
  }


  /**
   *  return no more than the entity and properties no join or else...
   *
   * @param  mixed $id
   * @return Entity
   */
  public function getEntity(int $id): Entity | bool
  {
    $query = $this
      ->pdo
      ->prepare("SELECT * FROM $this->table WHERE id = ?");
    $this->setFetchMode($query);
    $query->execute([$id]);
    $result = $query->fetch();
    return $result;
  }

  /**
   * build the query for prepared statement => id =:id, title = :title ...  
   *
   * @param  mixed $params
   * @return string
   */
  protected function buildFieldQuery(array $params): string
  {
    return join(', ', array_map(function ($field) {
      return "$field = :$field";
    }, array_keys($params)));
  }

  protected function setFetchMode(PDOStatement $query): void
  {
    if ($this->entity) {
      $query->setFetchMode(PDO::FETCH_CLASS, $this->entity);
    }
  }

  protected function findByQuery($field): string
  {
    return "SELECT * FROM {$this->table} WHERE $field = ?";
  }

  protected function paginatedQuery(int $limit, int $offset): string
  {
    return "SELECT * FROM {$this->table} LIMIT $limit OFFSET $offset";
  }

  protected function countQuery(): string
  {
    return "SELECT COUNT(id) FROM {$this->table} ";
  }

  protected function getByIdQuery(): string
  {
    return "SELECT * FROM $this->table WHERE id =:id";
  }
}
