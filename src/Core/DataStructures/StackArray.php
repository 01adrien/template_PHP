<?php

namespace Src\Core\DataStructures;

use Src\Core\Exceptions\EmptyDataStructureException;
use Src\Core\Interfaces\StackInterface;
use ArrayObject;

/**
 * Array type stack class to handle stacked job 
 */
class StackArray extends ArrayObject implements StackInterface
{
  public function __construct(?array $datas = null)
  {
    if ($datas)
      foreach ($datas as $data) $this->push($data);
  }

  /**
   * push an element onthe top
   * 
   * @param mixed $data
   * @return StackArray
   */
  public function push(mixed $data): self
  {
    $this[] = $data;
    return $this;
  }

  /**
   * pop the top elemeent
   *
   * @return mixed
   * @throws EmptyDataStructureException
   */
  public function pop(): mixed
  {
    if (!$this->count()) throw new EmptyDataStructureException();
    $temp = $this[$this->count() - 1];
    unset($this[$this->count() - 1]);
    return $temp;
  }

  /**
   * @return int
   */
  public function length(): int
  {
    return $this->count();
  }
}
