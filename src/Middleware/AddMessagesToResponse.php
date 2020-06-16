<?php

namespace GoomCoom\Messages\Middleware;

use Closure;
use GoomCoom\Messages\Facades\Messages;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;

class AddMessagesToResponse
{
    /**
     * @var Application|ResponseFactory|mixed
     */
    protected $factory;

    /**
     * AddMessagesToResponse constructor.
     */
    public function __construct()
    {
        $this->factory = app(ResponseFactory::class);
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!$response instanceof JsonResponse) {

            $response = $this->factory->json(
                $response->content(),
                $response->status(),
                $response->headers->all()
            );
        }

        return $this->addMessages($response);
    }

    /**
     * Check if there are any messages and add them to the response.
     *
     * @param JsonResponse $response
     * @return JsonResponse
     */
    protected function addMessages(JsonResponse $response)
    {
        $data = $response->getData(true);

        if (gettype($data) === 'string' && $data) {
            $data = json_decode($data, true);
        }

        if (!$data) $data = [];

        if (Messages::hasMessages()) $data['meta']['messages'] = Messages::allMessages();

        if (isset($data['message'])) {
            $data['meta']['messages']['error'][] = $data['message'];
        }

        return $response->setData($data);
    }
}
