<?php


namespace App\Controller;

use App\Entity\Tariff;
use App\Entity\Flight;
use App\Error\Error;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTime;

class FlightController extends AbstractController
{
    private $datetimeFormat = 'Y-m-d H:i:s';

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

    public function createFlight(Request $request)
    {
        $reqBody = json_decode($request->getContent(), true);
        $response = new Response();

        if($this->isValidReqBody($reqBody)) {
            $departureDatetime = DateTime::createFromFormat($this->datetimeFormat, $reqBody['departureDatetime']);
            $arrivalDatetime = DateTime::createFromFormat($this->datetimeFormat, $reqBody['arrivalDatetime']);

            $flight = new Flight();
            $flight->setDeparturePoint($reqBody['departurePoint']);
            $flight->setArrivalPoint($reqBody['arrivalPoint']);
            $flight->setDepartureDatetime($departureDatetime);
            $flight->setArrivalDatetime($arrivalDatetime);
            $flight->setAirCompany($reqBody['airCompany']);
            $flight->setFlightNumber($reqBody['flightNumber']);
            $flight->setCost($reqBody['cost']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($flight);
            $entityManager->flush();

            $response->setContent(json_encode(['id' => $flight->getId()]));
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

    public function updateFlight($flightId, Request $request)
    {
        $reqBody = json_decode($request->getContent(), true);
        $response = new Response();

        if($this->isValidReqBody($reqBody))
        {
            $repository = $this->getDoctrine()->getRepository(Flight::class);
            $flight = $repository->find($flightId);

            if($flight)
            {
                $departureDatetime = DateTime::createFromFormat($this->datetimeFormat, $reqBody['departureDatetime']);
                $arrivalDatetime = DateTime::createFromFormat($this->datetimeFormat, $reqBody['arrivalDatetime']);

                $flight->setDeparturePoint($reqBody['departurePoint']);
                $flight->setArrivalPoint($reqBody['arrivalPoint']);
                $flight->setDepartureDatetime($departureDatetime);
                $flight->setArrivalDatetime($arrivalDatetime);
                $flight->setAirCompany($reqBody['airCompany']);
                $flight->setFlightNumber($reqBody['flightNumber']);
                $flight->setCost($reqBody['cost']);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($flight);
                $entityManager->flush();

                $response->setContent(json_encode([
                    'id' => $flight->getId(),
                    'departurePoint' => $flight->getDeparturePoint(),
                    'arrivalPoint' => $flight->getArrivalPoint(),
                    'departureDatetime' => $flight->getDepartureDatetime(),
                    'arrivalDatetime' => $flight->getArrivalDatetime(),
                    'airCompany' => $flight->getAirCompany(),
                    'flightNumber' => $flight->getFlightNumber(),
                    'cost' => $flight->getCost()
                ]));
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

        $repository = $this->getDoctrine()->getRepository(Flight::class);
        $flight = $repository->find($flightId);
        if($flight)
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($flight);
            $entityManager->flush();

            $response->setContent(json_encode(['isDeleted' => true]));
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