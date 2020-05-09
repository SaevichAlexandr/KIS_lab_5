<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Error\Error;

class FlightController
{
    private $departurePoint = 'MOW';
    private $arrivalPoint = 'LED';
    private $departureDatetime = '2017-07-21T18:30:00Z';
    private $arrivalDatetime = '2017-07-24T08:00:00Z';
    private $airCompany = 'UT';
    private $flightNumber = 'UT-450';
    private $cost = 10900.00;

    public function getFlight($flightId)
    {
        $response = new Response();

        if($flightId > 0 && $flightId <= 10) {
            $response->setContent(json_encode(
                [
                    'id' => $flightId,
                    'departurePoint' => $this->departurePoint,
                    'arrivalPoint' => $this->arrivalPoint,
                    'departureDatetime' => $this->departureDatetime,
                    'arrivalDatetime' => $this->arrivalDatetime,
                    'airCompany' => $this->airCompany,
                    'flightNumber' => $this->flightNumber,
                    'cost' => $this->cost
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

    public function getFlights()
    {
        $flights = [
            [
                'id' => 1,
                'departurePoint' => $this->departurePoint,
                'arrivalPoint' => $this->arrivalPoint,
                'departureDatetime' => $this->departureDatetime,
                'arrivalDatetime' => $this->arrivalDatetime,
                'airCompany' => $this->airCompany,
                'flightNumber' => $this->flightNumber,
                'cost' => $this->cost
            ],
            [
                'id' => 2,
                'departurePoint' => "LED",
                'arrivalPoint' => "MOW",
                'departureDatetime' => "2020-07-21T18:30:00Z",
                'arrivalDatetime' => "2020-08-24T08:00:00Z",
                'airCompany' => "SU",
                'flightNumber' => "SU-7090",
                'cost' => 29300.00
            ]
        ];

        $response = new Response();
        $response->headers->set('Content-type', 'application/json');
        $response->setContent(json_encode($flights));

        return $response;
    }

    public function createFlight()
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

    public function updateFlight()
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

    public function deleteFlight($flightId)
    {
        $response = new Response();

        if($flightId > 0 && $flightId <= 10) {
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

    public function getFlightTariffs($flightId)
    {
        $response = new Response();

        if($flightId > 0 && $flightId <= 10)
        {
            $tariffs = [
                'flightId' => $flightId,
                [
                    'code' => "UTOW10S",
                    'description' => "APPLICATION AND OTHER CONDITIONS RULE - 304/UT23 ".
                        "UNLESS OTHERWISE SPECIFIED ONE WAY MINIMUM FARE APPLICATION AREA THESE ".
                        "FARES APPLY WITHIN AREA 2. CLASS OF SERVICE THESE FARES APPLY FOR ".
                        "ECONOMY CLASS SERVICE. TYPES OF TRANSPORTATION THIS RULE GOVERNS ONE-WAY ".
                        "FARES. FARES GOVERNED BY THIS RULE CAN BE USED TO CREATE ONE-WAY JOURNEYS.",
                    'refundable' => true,
                    'exchangeable'=> false,
                    'baggage' => "1PC"
                ],
                [
                    'code' => "UTOW20S",
                    'description' => "APPLICATION AND OTHER CONDITIONS RULE - 304/UT23 ".
                        "UNLESS OTHERWISE SPECIFIED ONE WAY MINIMUM FARE APPLICATION AREA THESE ".
                        "FARES APPLY WITHIN AREA 2. CLASS OF SERVICE THESE FARES APPLY FOR ".
                        "ECONOMY CLASS SERVICE. TYPES OF TRANSPORTATION THIS RULE GOVERNS ONE-WAY ".
                        "FARES. FARES GOVERNED BY THIS RULE CAN BE USED TO CREATE ONE-WAY JOURNEYS.",
                    'refundable' => true,
                    'exchangeable'=> true,
                    'baggage' => "2PC"
                ]
            ];
            $response->headers->set('Content-type', 'application/json');
            $response->setContent(json_encode($tariffs));
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

    private function isValidReqBody($reqBody)
    {
        if (
            isset($reqBody['departurePoint']) &&
            isset($reqBody['arrivalPoint']) &&
            isset($reqBody['departureDatetime']) &&
            isset($reqBody['arrivalDatetime']) &&
            isset($reqBody['airCompany']) &&
            isset($reqBody['flightNumber']) &&
            isset($reqBody['cost'])
        ) {
            if (
                is_string($reqBody['departurePoint']) &&
                is_string($reqBody['arrivalPoint']) &&
                is_string($reqBody['departureDatetime']) &&
                is_string($reqBody['arrivalDatetime']) &&
                is_string($reqBody['airCompany']) &&
                is_string($reqBody['flightNumber']) &&
                is_float($reqBody['cost'])
            ) {
                return true;
            }
        }

        return false;
    }
}