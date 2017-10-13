<?php

namespace App\Service;

use InvalidArgumentException;

class Telegram
{
    const URL = "https://api.telegram.org/bot%s/sendMessage?";

    /**
     * @var string
     */
    private $bot;

    /**
     * @var int
     */
    private $chatId;

    /**
     * @var string
     */
    private $text;

    public function __construct()
    {
        if (empty(env('TELEGRAM_BOT')) || empty(env('TELEGRAM_CHAT_ID'))) {
            throw new InvalidArgumentException();
        }

        $this->bot = trim(env('TELEGRAM_BOT'));
        $this->chatId = (int)env('TELEGRAM_CHAT_ID');
    }

    public function send($text)
    {
        if (empty($text)) {
            return false;
        }

        $this->text = $text;


        $url = $this->getUrl();

        $response = @file_get_contents($url);

        return !empty($response);
    }

    public function getUrl()
    {
        $data = [
            'chat_id' => $this->chatId,
            'text' => $this->text
        ];

        return sprintf(self::URL, $this->bot) . http_build_query($data);
    }
}