<?php

namespace App\Services;

use Aws\Textract\TextractClient;

class TextractService
{
    protected $client;

    public function __construct()
    {
        $this->client = new TextractClient([
            'version'     => 'latest',
            'region'      => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function extractTextFromImage(string $filePath)
    {
        $imageBytes = file_get_contents($filePath);

        $result = $this->client->analyzeDocument([
            'Document' => ['Bytes' => $imageBytes],
            'FeatureTypes' => ['FORMS'], // Or ['TABLES', 'FORMS']
        ]);

        $lines = [];

        foreach ($result['Blocks'] as $block) {
            if ($block['BlockType'] === 'LINE') {
                $lines[] = $block['Text'];
            }
        }

        return $lines;
    }

}
