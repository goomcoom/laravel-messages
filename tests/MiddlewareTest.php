<?php


namespace GoomCoom\Messages\Tests;


use GoomCoom\Messages\Facades\Messages;
use GoomCoom\Messages\Middleware\AddMessagesToResponse;
use GoomCoom\Messages\Test\TestCase;
use Illuminate\Http\Request;

class MiddlewareTest extends TestCase
{
    public  $callback;
    public $middleware;
    public $content;

    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new AddMessagesToResponse();
        $this->callback = function (Request $request = null) {
            return response($this->content);
        };
    }

    /** @test */
    public function messages_are_added_to_the_response()
    {
        Messages::add('info', 'This should be in the response.');
        $response = $this->middleware->handle(request(), $this->callback);
        $this->assertEquals([
            'meta' => [
                'messages' => [
                    'info' => [
                        'This should be in the response.',
                    ],
                ],
            ],
        ], $response->getData(true));
    }

    /** @test */
    public function laravel_error_message_is_added_to_the_errors_array_in_messages()
    {
        $this->content = [ 'message' => 'Something went very wrong' ];
        $response = $this->middleware->handle(request(), $this->callback);

        $this->assertEquals([
            'message' => 'Something went very wrong',
            'meta' => [
                'messages' => [
                    'error' => [
                        'Something went very wrong',
                    ],
                ],
            ],
        ], $response->getData(true));
    }
}