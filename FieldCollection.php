<?php


namespace rushstart\field;


use ArrayAccess;
use Countable;
use Iterator;
use yii\base\Component;
use yii\base\InvalidCallException;
use yii\base\UnknownPropertyException;
use function count;
use function current;
use function key;
use function next;

class FieldCollection extends Component implements ArrayAccess, Iterator, Countable
{
    /**
     * @var array data contained in this collection.
     */
    protected $_fields;

    protected $_iteratorField;

    /**
     * Collection constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * @return array fields contained in this collection.
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * Return the current field
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        if ($this->_iteratorField === null) {
            $this->_iteratorField = $this->getFields();
        }
        return current($this->_iteratorField)->getValues();
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        if ($this->_iteratorField === null) {
            $this->_iteratorField = $this->getFields();
        }
        next($this->_iteratorField);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        if ($this->_iteratorField === null) {
            $this->_iteratorField = $this->getFields();
        }
        return key($this->_iteratorField);
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        if ($this->_iteratorField === null) {
            $this->_iteratorField = $this->getFields();
        }
        return current($this->_iteratorField) !== false;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->_iteratorField = $this->getFields();
        reset($this->_iteratorField);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return bool true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->getFields()[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->getFields()[$offset]->getValues();
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new InvalidCallException('Collection is readonly.');
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new InvalidCallException('Collection is readonly.');
    }

    /**
     * Count items in this collection.
     * @return int the count of items in this collection.
     */
    public function count()
    {
        return count($this->getFields());
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws UnknownPropertyException
     */
    public function __set($name, $value)
    {
        if (isset($this->getFields()[$name])) {
            $this->getFields()[$name]->setValues($value);
            return;
        }
        parent::__set($name, $value);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws UnknownPropertyException
     */
    public function __get($name)
    {
        if (isset($this->getFields()[$name])) {
            return $this->getFields()[$name]->getValues();
        }
        return parent::__get($name);
    }

}