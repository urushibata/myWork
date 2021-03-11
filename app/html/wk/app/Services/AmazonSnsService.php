<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;
use Aws\Result;
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;

/**
 * Amazon SNS Service
 */
class AmazonSnsService
{
    /**
     * @var string SUBSCRIPTION_CONFIRMATION
     */
    const SUBSCRIPTION_CONFIRMATION = 'SubscriptionConfirmation';

    /**
     * @var string NOTIFICATION
     */
    const NOTIFICATION = 'Notification';

    /**
     * SNS トピックに送信する。
     *
     * @param string $topickArn SNSトピックArn
     * @param string $message 送信メッセージ
     * @param string $subject 送信タイトル
     */
    public function publish($topickArn, $message, $subject): Result
    {
        Log::debug('SNS publish called.');

        $client = new SnsClient([
            'region' => config('mywork.aws.default_region'),
            'version' => '2010-03-31'
        ]);

        try {
            $result = $client->publish([
                'TopicArn' => $topickArn,
                'Message' => $message,
                'Subject' => $subject
            ]);

            Log::debug("publish result: ${result}");

            return $result;
        } catch (AwsException $e) {
            Log::error($e->getMessage());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Sns HTTPSサブスクリプションを受信する。
     */
    public function subscribe(): Array
    {
        try {
            $message = Message::fromRawPostData();

            // TODO: AWSのサブスクリプションか検証する。

            Log::info("SNS post data => " . var_export($message, true));

            $validator = new MessageValidator();

            if ($validator->isValid($message)) {
                if ($message['Type'] === self::SUBSCRIPTION_CONFIRMATION) {
                    return [
                        "type" => $message['Type'],
                        "contents" => file_get_contents($message['SubscribeURL'])
                    ];
                } elseif ($message['Type'] === self::NOTIFICATION) {
                    return [
                        "type" => $message['Type'],
                        "subject" => $message['Subject'],
                        "message" => json_decode($message['Message'])
                    ];
                }
            } else {
                throw new \Exception('SNS Message Validator Error.' . var_export($message, true));
            }
        } catch (AwsException $e) {
            Log::error($e->getMessage());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            LOG::error($e->getTraceAsString());
            throw $e;
        }
    }
}
