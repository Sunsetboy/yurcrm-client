<?php

namespace YurcrmClient;

/**
 * Класс для работы с API YurCRM
 * Class YurcrmClient.
 */
class YurcrmClient
{
    const API_SERVER_URL = 'https://www.yurcrm.ru/api/';

    protected $route;
    protected $method;
    protected $token;
    protected $data;
    protected $serverUrl;
    protected $curlLink;

    /**
     * @param string $route
     * @param string $method
     * @param string $token
     * @param string $serverUrl
     */
    public function __construct(
        $route,
        $method,
        $token,
        $serverUrl = self::API_SERVER_URL
    )
    {
        $this->route = $route;
        $this->method = $method;
        $this->token = $token;
        $this->serverUrl = $serverUrl;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 'POST' === $this->method ? 1 : 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        $this->curlLink = $ch;
    }

    /**
     * @param string $route
     *
     * @return YurcrmClient
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return string
     */
    protected function getRequestUrl()
    {
        $route = $this->serverUrl . $this->route;

        if ('GET' == $this->method) {
            $route .= '?' . http_build_query($this->data + ['token' => $this->token]);
        } else {
            $route .= '?token=' . $this->token;
        }

        return $route;
    }

    /**
     * @param array $data
     *
     * @return YurcrmClient
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Отправка запроса в API и получение результата
     * @return YurcrmResponse
     */
    public function send()
    {
        $url = $this->getRequestUrl();
        curl_setopt($this->curlLink, CURLOPT_URL, $url);
        if ('POST' === $this->method) {
            curl_setopt($this->curlLink, CURLOPT_POSTFIELDS, $this->data);
        }

        $yurcrmResponse = new YurcrmResponse($this->curlLink);
        curl_close($this->curlLink);

        return $yurcrmResponse;
    }
}
