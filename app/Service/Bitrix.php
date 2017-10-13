<?php
namespace App\Service;

use Bitrix24\Bitrix24;
use Bitrix24\CRM\Contact;
use Bitrix24\CRM\Lead;

class Bitrix
{
    /**
     * @var Bitrix24
     */
    private $client;

    /**
     * @param string $accessToken
     * @param string $refreshToken
     * @return array|null
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     */
    public function init($accessToken, $refreshToken)
    {
        if (empty($accessToken) || empty($refreshToken)) {
            return null;
        }

        $this->client = new Bitrix24();;
        $this->client->setDomain(env('BITRIX_DOMAIN'));
        $this->client->setApplicationId(env('BITRIX_CLIENT_ID'));
        $this->client->setApplicationSecret(env('BITRIX_CLIENT_SECRET'));
        $this->client->setApplicationScope(["crm", "user"]);
        $this->client->setAccessToken($accessToken);
        $this->client->setRefreshToken($refreshToken);
        $this->client->setRedirectUri('http://isuzu.market/bitrix/code');

        $isGoodAccessToken = !$this->isAccessTokenExpire($accessToken);

        if (!$isGoodAccessToken) {
            $dataUpdate = $this->client->getNewAccessToken();

            return [
                "access_token" => $dataUpdate["access_token"],
                "refresh_token" => $dataUpdate["refresh_token"]
            ];
        }

        return null;
    }

    protected function isAccessTokenExpire($accessToken)
    {
        $url = sprintf(
            "https://%s/rest/app.info?auth=%s",
            env('BITRIX_DOMAIN'),
            $accessToken
        );


        $content = file_get_contents($url);

        // ошибка в соединение
        if (empty($content)) {
            throw new \RuntimeException();
        }

        $json = json_decode($content);

        if (!empty($json->result->CODE)
            && $json->result->CODE === $this->client->getApplicationId()) {
            return false;
        }

        dd($json);
    }

    /**
     * @param string $title
     * @param string $name
     * @param string $phone
     * @param string $message
     * @return int|null
     */
    public function addContact($title, $name, $phone, $message)
    {
        $params =             [
            "NAME" => $name,
            "SECOND_NAME" => $title,
            "LAST_NAME" => "-",
            "OPENED" => "Y",
            "ASSIGNED_BY_ID" => 1,
            "TYPE_ID" => "CLIENT",
            "SOURCE_ID" => "SELF",
            "PHOTO" => null,
            "PHONE" => [
                [ "VALUE" => $phone, "VALUE_TYPE" => "WORK" ]
            ]
        ];

        try {
            $response = (new Contact($this->client))->add($params);
        } catch (\Exception $e) {
            return -1;
        }

        if (!empty($response["result"])) {
            return $response["result"];
        }

        return null;
    }

    public function info()
    {
        try {
            $response = (new Contact($this->client))->getList();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        if (!empty($response)) {
            return $response;
        }

        return null;
    }

    /**
     * @param string $title
     * @param string $name
     * @param string $phone
     * @param string $message
     * @return int|null
     */
    public function addLead($title, $name, $phone, $message)
    {
        $params = [
            "TITLE" => $title,
            "NAME" => $name,
            "SECOND_NAME" => null,
            "LAST_NAME" => null,
            "STATUS_ID" => "NEW",
            "OPENED" => "Y",
            "ASSIGNED_BY_ID" => 1,
            "CURRENCY_ID" => "RUB",
            "PHONE" => [
                [ "VALUE" => $phone, "VALUE_TYPE" => "WORK" ]
            ]
        ];

        try {
            $response = (new Lead($this->client))->add($params);
        } catch (\Exception $e) {
            return -1;
        }

        if (!empty($response["result"])) {
            return $response["result"];
        }

        return null;
    }
}