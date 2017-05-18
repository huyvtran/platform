<?php
//
//use Aws\Ses\SesClient;
//use Aws\Common\Enum\Region;
//use Guzzle\Plugin\Log\LogPlugin;
//
//App::uses('AbstractTransport', 'Network/Email');
//
//require ROOT . DS . 'vendors' . DS . 'aws' . DS . 'aws-autoloader.php';
//
//class AmazonSESTransport extends AbstractTransport {
//
//    public function send(CakeEmail $email)
//    {
//        $text = $email->message('text');
//        if (!empty($text)) {
//                    $message['Body']['Text'] = array(
//                        'Data' => $text
//                    );
//        }
//        $html = $email->message('html');
//        if (!empty($html)) {
//                    $message['Body']['Html'] = array(
//                        'Data' => $html
//                    );
//        }
//        $config = $this->config();
//
//        $client = SesClient::factory(array(
//            'key' => $config['username'],
//            'secret' => $config['password'],
//            'region' => Region::US_EAST_1,
//            'curl.CURLOPT_VERBOSE' => true
//        ));
//        $client->addSubscriber(LogPlugin::getDebugPlugin());
//        $send = $client->sendEmail(array(
//            'Source' => current($email->from()),
//            'Destination' => array(
//                'ToAddresses' => array_values($email->to())
//            ),
//            'Message' => array(
//                'Subject' => array(
//                    'Data' =>  $email->subject()
//                ),
//                'Body' => $message['Body']
//            )
//        ));
//
//        return $send;
//    }
//
//}