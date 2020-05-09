<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Error\Error;

class AuthController
{
    public function getToken()
    {
        $request = new Request();
        $response = new Response();
        $userData = json_decode($request->getContent(), true);

        if (isset($userData['email']) && isset($userData['password']))
        {
            $email = $userData['email'];
            $password = $userData['password'];

            if($this->isEmailAndPasswordExist($email, $password))
            {
                $response->setContent(json_encode($this->generateToken($email)));
                $response->headers->set('Content-type', 'application/json');
                return $response;
            }
            else
            {
                $error = new Error();
                $response->setContent(json_encode($error->get409()));
                $response->headers->set('Content-type', 'application/json');
                return $response;
            }
        }

        $error = new Error();
        $response->setContent(json_encode($error->get422()));
        $response->headers->set('Content-type', 'application/json');
        return $response;
    }

    /**
     * Здесь будет проводиться проверка наличия юзера в базе, а пока заглушка
     *
     * @param $email
     * @param $password
     * @return bool
     */
    public function isEmailAndPasswordExist($email,$password)
    {
        if(($email == 'dio_brando@mail.com' || $email == 'joseph_joestar@mail.com') && $password) {
            return true;
        }
        return false;
    }

    public function generateToken($email)
    {
        switch ($email)
        {
            case 'dio_brando@mail.com':
                $rules = 'Flight:create,update;Tariff:create';
                $token = "{$email}.{$rules}";
                return base64_encode($token);
            case 'joseph_joestar@mail.com':
                $rules = 'Flight:create,update,delete;Tariff:create,update,delete';
                $token = "{$email}.{$rules}";
                return base64_encode($token);
        }
        return false;
    }
}