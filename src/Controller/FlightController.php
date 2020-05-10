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
        $repository = $this->getDoctrine()->getRepository(Flight::class);
        $flight = $repository->find($flightId);

        if($flight) {
            $response->setContent(json_encode(
                [
                    'id' => $flight->getId(),
                    'departurePoint' => $flight->getDeparturePoint(),
                    'arrivalPoint' => $flight->getArrivalPoint(),
                    'departureDatetime' => $flight->getDepartureDatetime(),
                    'arrivalDatetime' => $flight->getArrivalDatetime(),
                    'airCompany' => $flight->getAirCompany(),
                    'flightNumber' => $flight->getFlightNumber(),
                    'cost' => $flight->getCost()
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
        $repository = $this->getDoctrine()->getRepository(Flight::class);
        $flights = $repository->findAll();
        $response = new Response();

        if($flights)
        {
            $items = [];
            foreach ($flights as $flight)
            {
                $item = [
                    'id' => $flight->getId(),
                    'departurePoint' => $flight->getDeparturePoint(),
                    'arrivalPoint' => $flight->getArrivalPoint(),
                    'departureDatetime' => $flight->getDepartureDatetime(),
                    'arrivalDatetime' => $flight->getArrivalDatetime(),
                    'airCompany' => $flight->getAirCompany(),
                    'flightNumber' => $flight->getFlightNumber(),
                    'cost' => $flight->getCost()
                ];
                $items[] = $item;
            }

            $response->headers->set('Content-type', 'application/json');
            $response->setContent(json_encode($items));

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
        $repository = $this->getDoctrine()->getRepository(Flight::class);
        $flight = $repository->find($flightId);

        if($flight)
        {
            $tariffs = $flight->getTariffs();
            $items = [];
            foreach ($tariffs as $tariff)
            {
                $item = [
                    'id' => $tariff->getId(),
                    'code' => $tariff->getDescription(),
                    'refundable' => $tariff->getRefundable(),
                    'exchangeable' => $tariff->getExchangeable(),
                    'baggage' => $tariff->getBaggage(),
                    'flightId' => $tariff->getFlight()->getId()
                ];
                $items[] = $item;
            }

            $response->headers->set('Content-type', 'application/json');
            $response->setContent(json_encode($items));
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