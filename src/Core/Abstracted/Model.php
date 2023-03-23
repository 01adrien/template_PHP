<?php

namespace Src\Core\Abstracted;

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
  public function all(): array
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
  public function one(int $id): Entity | bool
  {
    $query = $this
      ->pdo
      ->prepare("SELECT * FROM $this->table WHERE id = ?");
    $this->setFetchMode($query);
    $query->execute([$id]);
    return $query->fetch();
  }

  /**
   * find a record by provided field
   *
   * @param  mixed $field
   * @param  mixed $value
   * @return Entity | array
   */
  public function findBy(string $field, string $value): Entity | array
  {
    $query = $this
      ->pdo
      ->prepare("SELECT * FROM {$this->table} WHERE $field = ?");

    $query->execute([$value]);
    return $query->fetch();
  }

  /**
   * insert a record in the selected table
   *
   * @param  mixed $params
   * @return bool
   */
  public function insert(array $params)
  {
    $fields = array_keys($params);
    $values = join(', ', array_map(function ($field) {
      return ':' . $field;
    }, $fields));
    $fields = join(', ', $fields);
    $query = $this
      ->pdo
      ->prepare("INSERT INTO {$this->table} ($fields) VALUES ($values)");
    return $query->execute($params);
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
  public function count(): int
  {
    return $this
      ->pdo
      ->query("SELECT COUNT(id) FROM {$this->table}")
      ->fetchColumn();
  }

  /**
   * build the query for prepared statement => id =:id, title = :title ...  
   *
   * @param  mixed $params
   * @return string
   */
  private function buildFieldQuery(array $params): string
  {
    return join(', ', array_map(function ($field) {
      return "$field = :$field";
    }, array_keys($params)));
  }

  private function setFetchMode(PDOStatement $query): void
  {
    if ($this->entity) {
      $query->setFetchMode(
        PDO::FETCH_CLASS,
        $this->entity
      );
    }
  }
}
