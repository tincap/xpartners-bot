<?php

namespace tincap\XpartnersBot\Request;


use tincap\Bot\Helpers\ParsingHelpers;
use tincap\Bot\Request\RequestCollection;
use tincap\XpartnersBot\Exceptions\TokenException;

class MarketingTools extends RequestCollection
{
    /**
     * @param string $subId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws TokenException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function generateLink($subId = '')
    {
        $content = $this->bot->request('get', 'markets/getpartnerlink')->getContent();

        $form = ParsingHelpers::getFormData($content);

        if (empty($form['data']['__RequestVerificationToken'])) {
            throw new TokenException("__RequestVerificationToken is empty");
        }

        return $this->bot->request('POST', 'markets/getpartnerlink')
            ->addPost('__RequestVerificationToken', $form['data']['__RequestVerificationToken'])
            ->addPost('need_php', 'False')
            ->addPost('Token', 'False')
            ->addPost('SitdeId', 76601)
            ->addPost('MerchantId', 3)
            ->addPost('CampaignId', 92)
            ->addPost('Page', '')
            ->addPost('SubId', $subId)
            ->addPost('X-Requested-With', 'XMLHttpRequest')
            ->getContent();
    }
}