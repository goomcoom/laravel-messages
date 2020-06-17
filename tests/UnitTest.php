<?php


namespace GoomCoom\Messages\Tests;


use Illuminate\Support\MessageBag;
use GoomCoom\Messages\Test\TestCase;
use GoomCoom\Messages\Facades\Messages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use GoomCoom\Messages\Exceptions\BagDoesNotExistException;


class UnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array $default_bags
     */
    public $default_bags = ['error', 'info', 'success', 'warning'];

    /** @test */
    public function the_global_message_bag_has_the_default_bags()
    {
        foreach ($this->default_bags as $name) {
            $this->assertArrayHasKey($name, Messages::getBags());
        }
    }

    /** @test */
    public function messages_are_placed_in_the_relevant_bags()
    {
        $messages = [
            'error' => 'This should be in error',
            'info' => 'This should be in info',
            'success' => 'This should be in success',
            'warning' => 'This should be in warning',
        ];

        foreach ($messages as $bag => $message) {
            Messages::add($bag, $message);
        }

        foreach ($messages as $bag => $message) {
            $this->assertEquals([ $message ], Messages::allMessages()[$bag]);
        }

        return Messages::allMessages();
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

    /**
     * @param array $messages
     * @depends messages_are_placed_in_the_relevant_bags
     * @test
     */
    public function requesting_all_messages_returns_messages_from_all_bags(array $messages)
    {
        foreach ($this->default_bags as $name) {
            $this->assertArrayHasKey($name, $messages);
        }
    }

    /** @test */
    public function getBag_method_returns_the_specified_message_bag()
    {
        $this->assertInstanceOf(MessageBag::class, Messages::getBag('error'));
    }

    /** @test */
    public function requesting_a_bag_that_does_not_exist_throws_the_BadDoesNotExist_exception()
    {
        $this->expectException(BagDoesNotExistException::class);
        Messages::getBag('does-not-exist');
    }

    /** @test */
    public function specifying_an_unknown_bag_throws_an_exception()
    {
        $this->expectException(BagDoesNotExistException::class);

        Messages::add('no-existent', 'An exception should be thrown.');
    }
    
    /** @test */
    public function the_BagDoesNotExistException_formats_the_message_correctly()
    {
        $non_existent_bag = 'non-existent';
        $this->expectDeprecationMessage(
            "The bag \"${non_existent_bag}\" does not exist. You may edit the available message bags via the config."
        );
        Messages::add($non_existent_bag, 'An exception should be thrown.');
    }
}