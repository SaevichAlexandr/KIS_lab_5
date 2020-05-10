<?php


namespace App\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Error\Error;

class TokenSubscriber implements EventSubscriberInterface
{
    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        $method = $controller[1];
        $controller = $controller[0];

        $entityName = $this->getEntityName($controller);
        $methods = ['create', 'delete', 'update'];
        $entitys = ['Flight', 'Tariff'];

        if (in_array($entityName, $entitys) && in_array($method, $methods)) {
            $request = Request::createFromGlobals();
            if (!empty($request->headers->get('authorization'))) {
                $auth = $request->headers->get('authorization');

                list($user, $token) = explode(' ', $auth);
                $decodedToken = base64_decode($token);

                if ($this->isTokenValid($decodedToken)) {
                    if (!$this->isEligible($entityName, $method, $decodedToken)) {
                        $event->setController(
                            function () {
                                $error = new Error();
                                $response = new Response();
                                $response->setContent(json_encode($error->get410()));
                                $response->headers->set('Content-type', 'application/json');
                                return $response;
                            });
                    }
                } else {
                    $event->setController(
                        function () {
                            $error = new Error();
                            $response = new Response();
                            $response->setContent(json_encode($error->get401()));
                            $response->headers->set('Content-type', 'application/json');
                            return $response;
                        });
                }
            } else {
                $event->setController(
                    function () {
                        $error = new Error();
                        $response = new Response();
                        $response->setContent(json_encode($error->get401()));
                        $response->headers->set('Content-type', 'application/json');
                        return $response;
                    });
            }
        }
    }

    public static function getSubscribedEvents()
    {
        //return [];
        return [
            KernelEvents::CONTROLLER => 'onKernelController'
        ];
    }

    private function getEntityName($object): string
    {
        $path = "App\Controller\\";
        $controllerWord = "Controller";
        $className = get_class($object);
        $className = str_replace($path, '', $className);
        $className = str_replace($controllerWord, '', $className);
        return $className;
    }

    private function isEligible(string $entity, string $method, $token): bool
    {
        $rights = stristr($token, "{$entity}:");
        $rights = explode(';', $rights);
        $right = strpos($rights[0], $method);
        if ($right !== false) {
            return true;
        }
        return false;
    }

    private function isTokenValid($token)
    {
        if (preg_match_all('/^\S*\.(\w*:((create,?)?(update,?)?(delete,?)?);?)*$/', $token) == 1) {
            return true;
        }
        return false;
    }
}