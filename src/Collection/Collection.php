<?php

declare(strict_types=1);

/* (c) Anton Medvedev <anton@medv.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer\Collection;

use Countable;
use IteratorAggregate;

class Collection implements Countable, IteratorAggregate
{
    protected array $values = [];

    public function all(): array
    {
        return $this->values;
    }

    public function get(string $name): mixed
    {
        if ($this->has($name)) {
            return $this->values[$name];
        }
        throw $this->notFound($name);
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->values);
    }

    public function set(string $name, mixed $object)
    {
        $this->values[$name] = $object;
    }

    public function remove(string $name): void
    {
        if ($this->has($name)) {
            unset($this->values[$name]);
            return;
        }
        throw $this->notFound($name);
    }

    public function count(): int
    {
        return count($this->values);
    }

    public function select(callable $callback): array
    {
        $values = [];

        foreach ($this->values as $key => $value) {
            if ($callback($value, $key)) {
                $values[$key] = $value;
            }
        }

        return $values;
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new \ArrayIterator($this->values);
    }

    protected function notFound(string $name): \InvalidArgumentException
    {
        return new \InvalidArgumentException("Element \"$name\" not found in collection.");
    }
}
