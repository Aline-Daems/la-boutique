<?php
namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{

    private $api_key ='60192848917e25a8cfa9a2818f4e5502';
    private $api_key_secret = '36d8ae3ab308adf8820f16c6334d0370';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "service-it@infinity-agency.be",
                        'Name' => "Léquipe Tiny and Pretty"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' =>  $to_name
                        ]
                    ],
                    'TemplateID' => 2963203,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                        
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}