<?php


namespace App\Controller;


use App\Entity\Tariff;
use App\Error\Error;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class TariffController extends AbstractController
{
    public function getTariff($tariffId)
    {
        $response = new Response();
        $repository = $this->getDoctrine()->getRepository(Tariff::class);
        $tariff = $repository->find($tariffId);
        if($tariff)
        {
            $response->setContent(json_encode([
                'id' => $tariff->getId(),
                'code' => $tariff->getDescription(),
                'refundable' => $tariff->getRefundable(),
                'exchangeable' => $tariff->getExchangeable(),
                'baggage' => $tariff->getBaggage()
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

    public function createTariff(Request $request)
    {
        $reqBody = json_decode($request->getContent(), true);
        $response = new Response();

        if($this->isValidReqBody($reqBody)) {
            $tariff = new Tariff();
            $tariff->setCode($reqBody['code']);
            $tariff->setDescription($reqBody['description']);
            $tariff->setRefundable($reqBody['refundable']);
            $tariff->setExchangeable($reqBody['exchangeable']);
            $tariff->setBaggage($reqBody['baggage']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tariff);
            $entityManager->flush();

            $response->setContent(json_encode(['id' => $tariff->getId()]));
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

    public function updateTariff($tariffId, Request $request)
    {
        $reqBody = json_decode($request->getContent(), true);
        $response = new Response();
        if($this->isValidReqBody($reqBody))
        {
            $repository = $this->getDoctrine()->getRepository(Tariff::class);
            $tariff = $repository->find($tariffId);
            if($tariff)
            {
                $tariff->setCode($reqBody['code']);
                $tariff->setDescription($reqBody['description']);
                $tariff->setRefundable($reqBody['refundable']);
                $tariff->setExchangeable($reqBody['exchangeable']);
                $tariff->setBaggage($reqBody['baggage']);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($tariff);
                $entityManager->flush();

                $response->setContent(json_encode([
                    'id' => $tariff->getId(),
                    'code' => $tariff->getDescription(),
                    'refundable' => $tariff->getRefundable(),
                    'exchangeable' => $tariff->getExchangeable(),
                    'baggage' => $tariff->getBaggage()
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
        else
        {
            $error = new Error();
            $response->setContent(json_encode($error->get422()));
            $response->headers->set('Content-type', 'application/json');
            return $response;
        }
    }

    public function deleteTariff($tariffId)
    {
        $response = new Response();

        $repository = $this->getDoctrine()->getRepository(Tariff::class);
        $tariff = $repository->find($tariffId);
        if($tariff)
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tariff);
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