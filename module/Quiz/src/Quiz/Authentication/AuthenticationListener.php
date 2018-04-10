<?php

namespace Quiz\Authentication;

use Zend\Authentication\Adapter\Http as HttpAuth;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\Router\RouteMatch;

class AuthenticationListener implements ListenerAggregateInterface
{
    /**
     * @var array
     */
    protected $listeners = [];

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'doEvent']);
    }

    /**
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * @param EventInterface $event
     * @return \Zend\Http\PhpEnvironment\Response
     */
    public function doEvent(EventInterface $event)
    {
        /** @var MvcEvent $event */

        if (php_sapi_name() == "cli") {
            return;
        }

        $sm = $event->getApplication()->getServiceManager();
        $auth = $sm->get('zfcuser_auth_service');
        if ($auth->hasIdentity()) {
            return;
        }

        $whiteList = [
            'zfcuser/login',
            'zfcuser/authenticate',
            'zfcuser/register',
        ];

        $match = $event->getRouteMatch();
        // Route is white listed
        $name = $match->getMatchedRouteName();
        if (in_array($name, $whiteList)) {
            return;
        }

        $router = $event->getRouter();
        $url    = $router->assemble(
            [],
            [
                'name' => 'zfcuser/login'
            ]
        );

        /** @var \Zend\Http\PhpEnvironment\Response $response */
        $response = $event->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $url);
        $response->setStatusCode(302);

        return $response;
    }
}