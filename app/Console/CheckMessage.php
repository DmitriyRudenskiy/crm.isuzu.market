<?php
namespace App\Console;

use App\Repositories\PhonesRepository;
use App\Service\Phone;
use App\Service\Sipuni\Loader;
use App\Service\Sipuni\Query;
use App\Service\Telegram;
use Illuminate\Console\Command;

class CheckMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:message';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PhonesRepository $repository)
    {
        $last = \App\Entities\Telegram::orderBy("id", "desc")->first();

        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT') ."/getUpdates";

        if (!empty($last->update_id)) {
            $url .= "?offset=" . ($last->update_id + 1);
        }

        $content = file_get_contents($url);

        $json = json_decode($content);

        if (empty($json->result)) {
            $this->warn($content);
            return null;
        }

        $data = [];

        foreach ($json->result as $value) {
            if (!empty($value->channel_post->chat->id)
                &&$value->channel_post->chat->id == env('TELEGRAM_CHAT_ID')) {

                $date = new \DateTime();
                $date->setTimestamp($value->channel_post->date);

                $data[] = [
                    "message_id" => $value->channel_post->message_id,
                    "update_id" => $value->update_id,
                    "message" => $value->channel_post->text,
                    "added_on" => $date
                ];
            }
        }

        // нет сообщений
        if (sizeof($data) < 1) {
            return null;
        }

        foreach ($data as $value) {
            $message = \App\Entities\Telegram::where("message_id", $value["message_id"])->first();

            if ($message === null) {
                \App\Entities\Telegram::forceCreate($value);

                // ищем номер телефона

                $find = $this->findPhone($value["message"]);

                if ($find !== null) {
                    $phone = $repository->get($find->number);

                    // нелефон не найден
                    if ($phone !== null) {
                        $phone->comment = trim($phone->comment . ' ' . $find->comment);
                        $phone->call = $value["added_on"];
                        $phone->save();
                    }
                }
            }
        }
    }

    protected function findPhone($text, $delimiter = " ")
    {
        $service = new Phone();
        $token = explode($delimiter, $text);
        $phone = null;
        $words = [];

        foreach ($token as $token) {
            $tmp = $service->parsing($token);

            if ($service->isValid($tmp)) {
                $phone = $tmp;
            } else {
                $words[] = $token;
            }
        }

        if ($phone === null) {
            return null;
        }

        return (object)[
            "number" => $phone,
            "comment" => implode($delimiter, $words)
        ];
    }
}
