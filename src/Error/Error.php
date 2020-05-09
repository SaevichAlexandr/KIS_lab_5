<?php


namespace App\Error;


class Error
{
    public function get404()
    {
        return $errorDescription = [
            'status' => 404,
            'error' => '404 Not Found',
            'type' => 'It`s 404, don`t you really understand what it means?',
            'title' => 'blah, blah, blah...'
        ];
    }

    public function get401()
    {
        return $errorDescription = [
            'status' => 401,
            'error' => '401 Access forbidden, invalid API-KEY was used',
            'type' => 'blah, blah, blah...',
            'title' => 'blah, blah, blah...'
        ];
    }

    public function get409()
    {
        return $errorDescription = [
            'status' => 409,
            'error' => '409 Conflict',
            'type' => 'blah, blah, blah...',
            'title' => 'blah, blah, blah...'
        ];
    }

    public function get422()
    {
        return $errorDescription = [
            'status' => 422,
            'error' => '422 Invalid data was sent',
            'type' => 'blah, blah, blah...',
            'title' => 'blah, blah, blah...'
        ];
    }
}