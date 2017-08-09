<?php
require_once ROOT. DS . 'vendors' . DS . 'PaymentWall' . DS . 'lib' . DS . 'paymentwall.php';
class PaymentWall {
    
    private $access_key ;
	private $secret ;

    private $user_token;
    private $game_app;
    private $order_id;

    private $note = ' ';

    function __construct($access_key, $secret , $user_token, $game_app)
    {
        $this->access_key   = $access_key;
        $this->secret       = $secret;
        $this->user_token   = $user_token;
        $this->game_app     = $game_app;
    }

    public function getUserToken() {
        return $this->user_token;
    }

    public function setUserToken($user_token) {
        $this->user_token = $user_token;
    }

    public function getGameApp() {
        return $this->game_app;
    }

    public function setGameApp($game_app) {
        $this->game_app = $game_app;
    }

    public function getOrderId() {
        return $this->order_id;
    }

    public function setOrderId($order_id) {
        $this->order_id = $order_id;
    }

    public function getNote() {
        return $this->note;
    }

    public function setNote($note) {
        $this->note = $note;
    }

    # $product : dữ liệu từ bảng product
    # type: array
    public function create( $product, $country_code = "PH" ){
        Paymentwall_Base::setApiType(Paymentwall_Base::API_GOODS);
        Paymentwall_Base::setAppKey($this->access_key);
        Paymentwall_Base::setSecretKey($this->secret);

        $widget = new Paymentwall_Widget(
            $this->getOrderId(),
            'pw',
            array(
                new Paymentwall_Product(
                    $this->getOrderId(),
                    $product['price'],
                    'USD',
                    $product['title']
                )
            ),
            array(
//                'country_code' => $country_code, // set country Philippines
                'success_url' => Configure::read('Paymentwall.ReturnUrl')
                    . '?app=' . $this->getGameApp()
                    . '&qtoken='. $this->getUserToken()
                    . '&order_id=' . $this->getOrderId(),
                'pingback_url' => Configure::read('Paymentwall.UrlPingBack')
                    . '?app=' . $this->getGameApp()
                    . '&qtoken='. $this->getUserToken()
                    . '&order_id=' . $this->getOrderId()
            )
        );
        return $widget->getUrl();
    }

    public function close(){
        Paymentwall_Base::setApiType(Paymentwall_Base::API_GOODS);
        Paymentwall_Base::setAppKey($this->access_key);
        Paymentwall_Base::setSecretKey($this->secret);

        App::import('Model', 'WaitingPayment');
        ClassRegistry::init('WaitingPayment');

        $pingback = new Paymentwall_Pingback($_GET, $_SERVER['REMOTE_ADDR']);
        if ($pingback->validate()) {
            if ($pingback->isDeliverable()) {
                // deliver the product
                return WaitingPayment::STATUS_COMPLETED;
            } else if ($pingback->isCancelable()) {
                // withdraw the product
                return WaitingPayment::STATUS_ERROR;
            } else if ($pingback->isUnderReview()) {
                // set "pending" status to order
                return WaitingPayment::STATUS_QUEUEING;
            }
        }

        return WaitingPayment::STATUS_ERROR;
    }
}