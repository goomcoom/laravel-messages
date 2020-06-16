<?php

namespace GoomCoom\Messages\Tests;

use GoomCoom\Messages\Facades\Messages;
use GoomCoom\Messages\Test\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FunctionalityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array $default_bags
     */
    public $default_bags = ['error', 'info', 'success', 'warning'];

    public function setUp() :void
    {
        parent::setUp();
    }

    /** @test */
    public function the_global_message_bag_has_the_default_bags()
    {
        $bags = [];
        foreach (Messages::getBags() as $name => $bag) $bags[] = $name;
        $this->assertEquals($this->default_bags, $bags);
    }

    /** @test */
    public function messages_are_placed_in_the_relevant_bags()
    {
        $messages_1 = [
            'This should be in error' => 'error',
            'This should be in info' => 'info',
            'This should be in success' => 'success',
            'This should be in warning' => 'warning'
        ];

        foreach ($messages_1 as $message => $bag) {
            Messages::add($bag, $message);
        }

        foreach (Messages::getBags() as $bag) {
            $this->assertNotEmpty($bag->toArray());
        }
        return Messages::allMessages();
    }

    /**
     * @depends messages_are_placed_in_the_relevant_bags
     * @test
     */
    public function requesting_all_messages_returns_messages_from_all_bags(array $messages)
    {
        foreach ($this->default_bags as $name) {
            $this->assertArrayHasKey($name, $messages);
        }
    }

    /**
     * @depends messages_are_placed_in_the_relevant_bags
     * @test
     */
    public function messages_are_not_duplicated()
    {
        $messages_2 = [
            'This should be in error' => 'error',
            'This should be in info' => 'info',
            'This should be in success' => 'success',
            'This should be in warning' => 'warning'
        ];

        foreach ($messages_2 as $message => $bag) {
            Messages::add($bag, $message);
        }

        foreach (Messages::getBags() as $name => $bag) {
            $this->assertCount(1, $bag->toArray());
        }
    }

    /** @test */
    public function specifying_an_unknown_bag_places_the_message_in_the_default_bag()
    {
        $message = 'This should be in info';
        Messages::add('no_existent', $message);
        $this->assertTrue(in_array($message, Messages::getBag('info')->toArray()));
    }
}
