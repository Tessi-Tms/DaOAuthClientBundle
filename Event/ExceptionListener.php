<?php

namespace Da\OAuthClientBundle\Event;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Da\AuthCommonBundle\Security\AuthorizationRefresherInterface;

/**
 * Listener to pass the api http exceptions.
 */
class ExceptionListener
{
    /**
     * The HTTP kernel.
     *
     * @var HttpKernelInterface
     */
    private $kernel;

    /**
     * The authorization refresher.
     *
     * @var AuthorizationRefresherInterface
     */
    protected $authorizationRefresher;

    /**
     * Constructor
     *
     * @param HttpKernelInterface             $kernel                 The HTTP kernel.
     * @param AuthorizationRefresherInterface $authorizationRefresher The authorization refresher.
     */
    public function __construct(
        HttpKernelInterface $kernel,
        AuthorizationRefresherInterface $authorizationRefresher
    )
    {
        $this->kernel = $kernel;
        $this->authorizationRefresher = $authorizationRefresher;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception =  $event->getException();

        if ($exception instanceof HttpExceptionInterface) {
            $headers = $exception->getHeaders();
            $request = $event->getRequest();

            if (isset($headers['X-Da-Agent']) || isset($headers['x-da-agent'])) {
                $try = $request->headers->get('x-request-try', 0);

                if (401 === $exception->getStatusCode() && 0 >= $try) {
                    ini_set('xdebug.max_nesting_level', 200);
                    $request->headers->set('x-request-try', $try + 1);

                    // Retry the request after refreshing the authorization.
                    // Master request because we need to reload the user.
                    $this->authorizationRefresher->refresh();
                    $response = $this->kernel->handle($request);
                } else {
                    $response = new Response();
                    $response->setStatusCode(502);
                    $response->headers->set('Content-Type', 'application/json');

                    $options = 0;
                    if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
                        $options = JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT;
                    }

                    $response->setContent(json_encode($headers, $options));
                }

                $event->setResponse($response);
            }
        }
    }
}