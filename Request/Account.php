<?php

namespace tincap\XpartnersBot\Request;


use tincap\Bot\helpers\ParsingHelpers;
use tincap\Bot\Request\RequestCollection;
use tincap\XpartnersBot\Exceptions\LoginException;

class Account extends RequestCollection
{
    /**
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws LoginException
     */
    public function login()
    {
        $content = $this->bot->request('get', 'home/login')->getContent();

        $form = ParsingHelpers::getFormData($content);

        if (empty($form['data']['__RequestVerificationToken'])) {
            throw new LoginException("__RequestVerificationToken is empty");
        }

        return $this->bot->request('POST', 'home/login')
            ->addPost('__RequestVerificationToken', $form['data']['__RequestVerificationToken'])
            ->addPost('UserName', $this->bot->config['username'])
            ->addPost('Password', $this->bot->config['password'])
            ->getContent();
    }
}