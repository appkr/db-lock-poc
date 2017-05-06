<?php

namespace App\Support;

use Faker\Generator;
use Faker\Provider\Base;
use GuzzleHttp\Client;

/**
 * Class KoreanLoremProvider
 * @package App\Support
 * @property string $korSentence
 * @property string $korParagraph
 */
class KoreanLoremProvider extends Base
{
    const KLOREM_URL = 'http://guny.kr/stuff/klorem/gen.php';

    protected $client;

    public function __construct(Generator $generator)
    {
        $this->client = new Client;
        parent::__construct($generator);
    }

    public function korSentence()
    {
        return $this->getLoremText(1);
    }

    public function korParagraph()
    {
        return $this->getLoremText(3);
    }

    private function getLoremText(int $line)
    {
        $response = $this->client->request(
            'POST',
            self::KLOREM_URL,
            [
                'headers' => [
                    'Content-type' => 'application/json'
                ],
                'body' => json_encode([
                    'line' => $line,
                    'trunc' => 0,
                    'type' => 'P',
                    'len' => 200,
                ])
            ]
        );

        $rawContent = json_decode($response->getBody()->getContents());

        return $rawContent->lines[0] ?? '';
    }
}