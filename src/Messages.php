<?php


namespace GoomCoom\Messages;


use Illuminate\Support\MessageBag;
use GoomCoom\Messages\Exceptions\BagDoesNotExistException;
use Illuminate\Contracts\Support\MessageBag as MessageBagContract;

class Messages {

    protected $bags = [];


    public function __construct()
    {
        foreach (config('goomcoom-laravel-messages.bags') as $name) {
            $this->createBag($name, new MessageBag);
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
    public function add(string $bag, ...$messages)
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
     * @param string $name
     * @return MessageBag
     * @throws BagDoesNotExistException
     */
    public function getBag($name)
    {
        if (array_key_exists($name, $this->bags)) return $this->bags[$name];

        throw new BagDoesNotExistException($name);
    }

    /**
     * Get all the messages organised by their bag names
     *
     * @return array
     */
    public function allMessages()
    {
        $messages = [];
        foreach ($this->bags as $name => $bag) {
            $bag_messages = $bag->toArray();
            if (count($bag_messages)) $messages[$name] = $bag_messages;
        }
        return $messages;
    }

    /**
     * Add a new MessageBag instance to the bags.
     *
     * @param string $key
     * @param MessageBagContract $bag
     * @return Messages
     */
    protected function createBag($key, MessageBagContract $bag)
    {
        $this->bags[$key] = $bag;

        return $this;
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
        return count($this->allMessages()) > 0;
    }
}
