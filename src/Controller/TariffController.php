<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Error\Error;

class TariffController
{
    private $code = "UTW10US";
    private $description = "APPLICATION AND OTHER CONDITIONS RULE - 304/UT23 UNLESS OTHERWISE SPECIFIED ONE WAY MINIMUM".
                            "FARE APPLICATION AREA THESE FARES APPLY WITHIN AREA 2. CLASS OF SERVICE THESE FARES APPLY ".
                            "FOR ECONOMY CLASS SERVICE. TYPES OF TRANSPORTATION THIS RULE GOVERNS ONE-WAY FARES. FARES ".
                            "GOVERNED BY THIS RULE CAN BE USED TO CREATE ONE-WAY JOURNEYS.";
    private $refundable = true;
    private $exchangeable = false;
    private $baggage = "1PC";

    public function getTariff($tariffId)
    {
        $response = new Response();

        if($tariffId > 0 && $tariffId <= 10) {
            $response->setContent(json_encode(
                [
                    'id' => $tariffId,
                    'code' => $this->code,
                    'description' => $this->description,
                    'refundable' => $this->refundable,
                    'exchangeable' => $this->exchangeable,
                    'baggage' => $this->baggage
                ]
            ));
            $response->headers->set('Content-type', 'application/json');
            return $response;
        }
        else
        {
            $error = new Error();
            $response->setContent(json_encode($error->get404()));
            $response->headers->set('Content-type', 'application/json');
            return $response;
        }
    }

    public function createTariff()
    {
        $request = Request::createFromGlobals();
        $reqBody = json_decode($request->getContent(), true);
        $response = new Response();

        if($this->isValidReqBody($reqBody)) {
            // здесь потом добавится добавление данных
            $response->setContent(json_encode(['id' => rand(11, 100)]));
            $response->headers->set('Content-type', 'application/json');
            return $response;
        }
        else {
            $error = new Error();
            $response->setContent(json_encode($error->get422()));
            $response->headers->set('Content-type', 'application/json');
            return $response;
        }
    }

    public function updateTariff()
    {
        $request = Request::createFromGlobals();
        $reqBody = json_decode($request->getContent(), true);
        $response = new Response();

        if($this->isValidReqBody($reqBody)) {
            // здесь потом добавится изменение данных
            // а пока так
            $response->setContent(json_encode($reqBody));
            $response->headers->set('Content-type', 'application/json');
            return $response;
        }
        else {
            $error = new Error();
            $response->setContent(json_encode($error->get422()));
            $response->headers->set('Content-type', 'application/json');
            return $response;
        }
    }

    public function deleteTariff($tariffId)
    {
        $response = new Response();

        if($tariffId > 0 && $tariffId <= 10) {
            $response->setContent(json_encode(['isDeleted' => true]));
            $response->headers->set('Content-type', 'application/json');
            return $response;
        } else {
            $error = new Error();
            $response->setContent(json_encode($error->get404()));
            $response->headers->set('Content-type', 'application/json');
            return $response;
        }
    }

    private function isValidReqBody($reqBody)
    {
        if (
            isset($reqBody['code']) &&
            isset($reqBody['description']) &&
            isset($reqBody['refundable']) &&
            isset($reqBody['exchangeable']) &&
            isset($reqBody['baggage'])
        ) {
            if (
                    is_string($reqBody['code']) &&
                    is_string($reqBody['description']) &&
                    is_bool($reqBody['refundable']) &&
                    is_bool($reqBody['exchangeable']) &&
                    is_string($reqBody['baggage'])
            ) {
                return true;
            }
        }
        return false;
    }
}