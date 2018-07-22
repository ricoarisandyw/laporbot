<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

namespace LINE\LINEBot\KitchenSink\EventHandler\MessageHandler;

use LINE\LINEBot;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\KitchenSink\EventHandler;
use LINE\LINEBot\KitchenSink\EventHandler\MessageHandler\Util\UrlBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;

require 'db.php';

class TextMessageHandler implements EventHandler
{
    /** @var LINEBot $bot */
    private $bot;
    /** @var \Monolog\Logger $logger */
    private $logger;
    /** @var \Slim\Http\Request $logger */
    private $req;
    /** @var TextMessage $textMessage */
    private $textMessage;

    /**
     * TextMessageHandler constructor.
     * @param $bot
     * @param $logger
     * @param \Slim\Http\Request $req
     * @param TextMessage $textMessage
     */
    public function __construct($bot, $logger, \Slim\Http\Request $req, TextMessage $textMessage)
    {
        $this->bot = $bot;
        $this->logger = $logger;
        $this->req = $req;
        $this->textMessage = $textMessage;
        $this->dbconn = getDB();
    }

    public function handle()
    {
        $text = $this->textMessage->getText();
        $replyToken = $this->textMessage->getReplyToken();
        $this->logger->info("Got text message from $replyToken: $text");

        //TODO: Check in database if client has ACTIVE status or not.
        $ACTIVE = false;
        if($ACTIVE){
            active(strtolower($text));
        }else{
            switch (strtolower($text)) {
                case 'profile':
                    $userId = $this->textMessage->getUserId();
                    $this->sendProfile($replyToken, $userId);
                    break;
                case 'bye':
                    if ($this->textMessage->isRoomEvent()) {
                        $this->bot->replyText($replyToken, 'Leaving room');
                        $this->bot->leaveRoom($this->textMessage->getRoomId());
                        break;
                    }
                    if ($this->textMessage->isGroupEvent()) {
                        $this->bot->replyText($replyToken, 'Leaving group');
                        $this->bot->leaveGroup($this->textMessage->getGroupId());
                        break;
                    }
                    $this->bot->replyText($replyToken, 'Bot cannot leave from 1:1 chat');
                    break;
                case 'confirm':
                    $this->bot->replyMessage(
                        $replyToken,
                        new TemplateMessageBuilder(
                            'Confirm alt text',
                            new ConfirmTemplateBuilder('Do it?', [
                                new MessageTemplateActionBuilder('Yes', 'Yes!'),
                                new MessageTemplateActionBuilder('No', 'No!'),
                            ])
                        )
                    );
                    break;
                case 'buttons':
                    $url = 'https://kitchenshink.herokuapp.com/public/static/buttons/1040.jpg';
                    $imageUrl = UrlBuilder::buildUrl($this->req, ['static', 'buttons', $url]);
                    $buttonTemplateBuilder = new ButtonTemplateBuilder(
                        'My button sample',
                        'Hello my button',
                        $imageUrl,
                        [
                            new UriTemplateActionBuilder('Go to line.me', 'https://line.me'),
                            new PostbackTemplateActionBuilder('Buy', 'action=buy&itemid=123'),
                            new PostbackTemplateActionBuilder('Add to cart', 'action=add&itemid=123'),
                            new MessageTemplateActionBuilder('Say message', 'hello hello'),
                        ]
                    );
                    $templateMessage = new TemplateMessageBuilder('Button alt text', $buttonTemplateBuilder);
                    $this->bot->replyMessage($replyToken, $templateMessage);
                    break;
                case 'carousel':
                    $url = 'https://kitchenshink.herokuapp.com/public/static/buttons/1040.jpg';
                    $imageUrl = UrlBuilder::buildUrl($this->req, ['static', 'buttons', $url]);
                    $carouselTemplateBuilder = new CarouselTemplateBuilder([
                        new CarouselColumnTemplateBuilder('foo', 'bar', $imageUrl, [
                            new UriTemplateActionBuilder('Go to line.me', 'https://line.me'),
                            new PostbackTemplateActionBuilder('Buy', 'action=buy&itemid=123'),
                        ]),
                        new CarouselColumnTemplateBuilder('buz', 'qux', $imageUrl, [
                            new PostbackTemplateActionBuilder('Add to cart', 'action=add&itemid=123'),
                            new MessageTemplateActionBuilder('Say message', 'hello hello'),
                        ]),
                    ]);
                    $templateMessage = new TemplateMessageBuilder('Button alt text', $carouselTemplateBuilder);
                    $this->bot->replyMessage($replyToken, $templateMessage);
                    break;
                case 'imagemap':
                    $url = 'https://kitchenshink.herokuapp.com/public/static/buttons/1040.jpg';
                    $richMessageUrl = UrlBuilder::buildUrl($this->req, ['static', 'rich']);
                    $imagemapMessageBuilder = new ImagemapMessageBuilder(
                        $richMessageUrl,
                        'This is alt text',
                        new BaseSizeBuilder(1040, 1040),
                        [
                            new ImagemapUriActionBuilder(
                                'https://store.line.me/family/manga/en',
                                new AreaBuilder(0, 0, 520, 520)
                            ),
                            new ImagemapUriActionBuilder(
                                'https://store.line.me/family/music/en',
                                new AreaBuilder(520, 0, 520, 520)
                            ),
                            new ImagemapUriActionBuilder(
                                'https://store.line.me/family/play/en',
                                new AreaBuilder(0, 520, 520, 520)
                            ),
                            new ImagemapMessageActionBuilder(
                                'URANAI!',
                                new AreaBuilder(520, 520, 520, 520)
                            )
                        ]
                    );
                    $this->bot->replyMessage($replyToken, $imagemapMessageBuilder);
                    break;
                case 'sticker':
                    $packageId = '1';
                    $stickerId = '3';
                    $stickerMessageBuilder = new StickerMessageBuilder($packageId, $stickerId);
                    $this->bot->replyMessage($replyToken, $stickerMessageBuilder);
                    break;
                case 'image':
                    $packageId = 'https://kitchenshink.herokuapp.com/public/static/buttons/1040.jpg';
                    $stickerId = 'https://kitchenshink.herokuapp.com/public/static/buttons/1040.jpg';
                    $stickerMessageBuilder = new ImageMessageBuilder($packageId, $stickerId);
                    $this->bot->replyMessage($replyToken, $stickerMessageBuilder);
                    break;
                case 'lapor!':
                    $userId = $this->textMessage->getUserId();
                    $this->mulaiLapor($replyToken,$userId);
                    break;
                case 'tidak lapor!':
                    $this->bot->echoBack($replyToken, "Terima kasih, aku akan selalu ada jika kamu ingin melapor.");
                    break;
                default:
                    // TODO: Check Status user
                    $userId = $this->textMessage->getUserId();
                    $response = $this->bot->getProfile($userId);
                    if (!$response->isSucceeded()) {
                        $this->bot->replyText($replyToken, $response->getRawBody());
                        return;
                    }

                    $profile = $response->getJSONDecodedBody();
                    $this->isActive($profile,$replyToken,$text);
                    break;
                    // $this->echoBack($replyToken, $text);
                    // $this->echoBack($replyToken, "Maaf, saya tidak paham maksud anda");
                    // break;
            }
        }
    }

    /**
     * @param string $replyTokenr
     * @param string $text
     */
    private function echoBack($replyToken, $text)
    {
        $this->logger->info("Returns echo message $replyToken: $text");
        $this->bot->replyText($replyToken, $text);
    }

    private function cariKosong($profile,$replyToken,$text){
        // Performing SQL query
        $query = "SELECT * FROM public.report WHERE user_id='".$profile["userId"]."' AND status='ACTIVE';";
        $result = pg_query($query);

        //FIND WHICH DATA IS BLANK
        //SEND ID REPORT TO BE UPDATED
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            if($line["message"]==''){                
                $this->createMessage($profile,$text);
                $this->bot->echoBack($replyToken, "Kejadiannya ada dimana ya?");
            }else if($line["location"]==''){
                $this->createPlace($profile,$text);
                $this->bot->echoBack($replyToken, "Laporan ini mau ditujukan ke siapa ya?");
            }else if($line["disposition"]==''){
                $this->createDisposition($profile,$text);
                $this->bot->echoBack($replyToken, 
                    "Terima kasih atas laporannya.",
                    "Kalau ada lagi silahkan dilaporkan (moon wink)");
                $this->deactiveReport($profile);
            }else{
                //TODO: Set status user jadi DONE
                $this->bot->echoBack($replyToken, 
                    "Terima kasih atas laporannya.",
                    "Kalau ada lagi silahkan dilaporkan (moon wink)");
            }
            // *** COOMING SOON FEATURE ***
            // }else if($line["content"]){
                
            // }else if($line["content"]){
                
            // }
        }
    }

    private function mulaiLapor($replyToken, $userId){
        if (!isset($userId)) {
            $this->bot->replyText($replyToken, "Bot can't use profile API without user ID");
            return;
        }
        $response = $this->bot->getProfile($userId);
        if (!$response->isSucceeded()) {
            $this->bot->replyText($replyToken, $response->getRawBody());
            return;
        }
        $profile = $response->getJSONDecodedBody();
        
        //Save User data
        $this->createReport($profile);

        //Ask for message
        $this->bot->replyText(
            $replyToken,
            'Hai'.$profile['displayName']." Silahkan tuliskan keluhan anda"
            // ,json_encode($profile)
        );
    }

    private function sendProfile($replyToken, $userId)
    {
        if (!isset($userId)) {
            $this->bot->replyText($replyToken, "Bot can't use profile API without user ID");
            return;
        }

        $response = $this->bot->getProfile($userId);
        if (!$response->isSucceeded()) {
            $this->bot->replyText($replyToken, $response->getRawBody());
            return;
        }

        $profile = $response->getJSONDecodedBody();
        $this->bot->replyText(
            $replyToken,
            'Display name: ' . $profile['displayName'],
            'Status message: ' . $profile['statusMessage']
        );
    }

    private function createReport($profile){
        // Performing SQL query
        $query = "INSERT INTO public.report(
            user_id, created_date, report_date, status)
            VALUES ('".$profile['userId']."',  now(), now(), 'ACTIVE');";
        $result = pg_query($query);
    }

    private function createPlace($profile,$data){
        // Performing SQL query
        $query = "UPDATE public.report
        SET location='".$data."' 
        WHERE user_id='".$profile["userId"]."' AND status='ACTIVE';";
        $result = pg_query($query);
    }

    private function createMessage($profile,$data){
        // Performing SQL query
        $query = "UPDATE public.report
        SET message='".$data."' 
        WHERE user_id='".$profile["userId"]."' AND status='ACTIVE';";
        $result = pg_query($query);
        
        // Free resultset
        pg_free_result($result);
        // Closing connection
        //pg_close($dbconn);
    }

    private function deactiveReport($profile){
        // Performing SQL query
        $query = "UPDATE public.report
        SET status='DONE' 
        WHERE user_id='".$profile["userId"]."' AND status='ACTIVE';";
        $result = pg_query($query);
    }

    private function createDisposition($id,$data){
        // Performing SQL query
        $query = "UPDATE public.report
        SET disposition='".$data."' 
        WHERE user_id='".$profile["userId"]."' AND status='ACTIVE';";
        $result = pg_query($query);
        // Free resultset
        pg_free_result($result);
        // Closing connection
        //pg_close($dbconn);
    }
    
    private function isActive($profile,$replyToken,$text){
        // Performing SQL query
        $query = "SELECT * FROM public.report WHERE user_id='".$profile["userId"]."' AND status='ACTIVE';";
        $result = pg_query($query);

        //IS DATA WITH user_id not ACTIVE?
        if(pg_num_rows($result)==0){ //YES
            //CREATE REPORT
            $this->bot->replyMessage(
                $replyToken,
                new TemplateMessageBuilder(
                    'Confirm alt text',
                    new ConfirmTemplateBuilder('Hai! Apakah anda ingin melapor?', [
                        new MessageTemplateActionBuilder('Ya', 'lapor!'),
                        new MessageTemplateActionBuilder('Tidak', 'tidak lapor'),
                    ])
                )
            );
            // // Free resultset
            // pg_free_result($result);
            // // Closing connection
            // pg_close($dbconn);
        }else{ //NO
            // // Free resultset
            // pg_free_result($result);
            // // Closing connection
            // pg_close($dbconn);
            //Cari data kosong
            $this->cariKosong($profile,$replyToken,$text);
        }
    }
}