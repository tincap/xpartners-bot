<?php

namespace tincap\XpartnersBot;


use GuzzleHttp\Cookie\FileCookieJar;
use tincap\Bot\Bot;
use tincap\Bot\Exceptions\ConfigException;
use tincap\Bot\SignatureUtils;
use tincap\XpartnersBot\Request\Account;
use tincap\XpartnersBot\Request\MarketingTools;

class XpartnersBot extends Bot
{
    public $account;

    public $marketingTools;

    /**
     * Mail constructor.
     * @param array $config
     * @throws ConfigException
     */
    public function __construct($config)
    {
        if (empty($config['username']) || empty($config['password']) || empty($config['proxy_ip']) || empty($config['proxy_auth'])) {
            throw new ConfigException('Missing required configurations');
        }

        // Load all function collections.
        $this->account = new Account($this);
        $this->marketingTools = new MarketingTools($this);

        $this->setCookiesJar(new FileCookieJar(self::getCookiesPath()));
        $this->setUserAgent(SignatureUtils::getUserAgent());
        $this->setProxy($config['proxy_ip'], $config['proxy_auth']);

        parent::__construct();
    }

    /**
     * @return array
     */
    public static function getMandatoryHeaders()
    {
        return [
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
            'Accept-Language' => 'en-US,en;q=0.8',
            'Connection' => 'keep-alive',
        ];
    }

    /**
     * @return string
     */
    public static function getCookiesPath()
    {
        return __DIR__ . '/session/cookies.json';
    }

    /**
     * @return string
     */
    public static function getHost()
    {
        return 'https://1xpartners.com';
    }

    /**
     * Авторизация
     *
     * @throws Exceptions\LoginException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login()
    {
        $this->account->login();
    }

    /**
     * Генерация новой ссылки
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \tincap\XpartnersBot\Exceptions\TokenException
     */
    public function generateNewLink()
    {
        return $this->marketingTools->generateLink();
    }
}