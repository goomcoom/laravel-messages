<?php


namespace GoomCoom\Messages;

use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag;
use Illuminate\Contracts\Support\MessageBag as MessageBagContract;

class Messages {
    /**
     * @var array $bags
     */
    protected array $bags = [];

    /**
     * @var string $default_bag
     */
    protected string $default_bag = 'info';

    /**
     * @var array $bag_names
     */
    protected array $bag_names = ['error', 'info', 'success', 'warning'];

    /**
     * Create the default bags
     * Messages constructor.
     */
    public function __construct()
    {
        foreach ($this->bag_names as $name) $this->put($name, new MessageBag);
    }

    /**
     * Add a message to the relevant bag
     *
     * @param string $bag
     * @param array $messages
     * @return void
     */
    public function add(string $bag, ...$messages)
    {
        $bag = Arr::has($this->bags, $bag) ? $this->bags[$bag] : $this->bags[$this->default_bag];
        foreach ($messages as $message) {
            in_array($message, $bag->toArray()) ?: $bag->merge((array) $message);
        }
    }

    /**
     * Get a specified bag or the default bag
     *
     * @param string $name
     * @return MessageBag
     */
    public function getBag($name)
    {
        return Arr::get($this->bags, $name) ?: $this->bags[$this->default_bag];
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
    protected function put($key, MessageBagContract $bag)
    {
        $this->bags[$key] = $bag;

        return $this;
    }

    /**
     * Get all the bags.
     *
     * @return array
     */
    public function getBags() :array
    {
        return $this->bags;
    }

    /**
     * Check if the message bag has any messages
     *
     * @return bool
     */
    public function hasMessages() :bool
    {
        return count($this->allMessages()) > 0;
    }
}