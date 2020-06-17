<?php


namespace GoomCoom\Messages;


use Illuminate\Support\MessageBag;
use GoomCoom\Messages\Exceptions\BagDoesNotExistException;

class Messages {

    protected $bags = [];


    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        foreach (config('goomcoom-laravel-messages.bags') as $name) {
            $this->bags[$name] = new MessageBag;
        }
    }

    /**
     * Add a message to the specified bag
     *
     * @param string $bag
     * @param array $messages
     * @return void
     * @throws BagDoesNotExistException
     */
    public function add($bag, ...$messages)
    {
        if (array_key_exists($bag, $this->bags)) {
            $bag = $this->bags[$bag];
            foreach ($messages as $message) {
                in_array($message, $bag->toArray()) ?: $bag->merge((array) $message);
            }
        } else {
            throw new BagDoesNotExistException($bag);
        }
    }

    /**
     * Get a specified bag
     *
     * @param string $bag
     * @return MessageBag
     * @throws BagDoesNotExistException
     */
    public function getBag($bag)
    {
        if (array_key_exists($bag, $this->bags)) return $this->bags[$bag];

        throw new BagDoesNotExistException($bag);
    }

    /**
     * Get all the messages organised by their bag names
     *
     * @return array
     */
    public function getAll()
    {
        $messages = [];
        foreach ($this->bags as $name => $bag) {
            $bag_messages = $bag->toArray();
            if (count($bag_messages)) $messages[$name] = $bag_messages;
        }
        return $messages;
    }

    /**
     * Get all the bags.
     *
     * @return array
     */
    public function getBags()
    {
        return $this->bags;
    }

    /**
     * Check if the message bags have any messages
     *
     * @return bool
     */
    public function hasAny()
    {
        return count($this->getAll()) > 0;
    }

    /**
     * Remove messages from a specific bag.
     *
     * @param $bag
     * @param string ...$messages
     * @throws BagDoesNotExistException
     */
    public function remove($bag, ...$messages)
    {
        if (!array_key_exists($bag, $this->bags)) {
            throw new BagDoesNotExistException($bag);
        }
        if (in_array('*', $messages)) {
            $this->bags[$bag] = new MessageBag();
        }
        $wanted = $this->bags[$bag]->messages();
        foreach ($messages as $message) {
            $key = array_search($message, $wanted);
            if ($key !== false) {
                unset($wanted[$key]);
            }
        }
        $this->bags[$bag] = new MessageBag;
        $this->add($bag, $wanted);
    }
}
