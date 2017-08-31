<?php

App::uses('ClassRegistry', 'Utility');
App::uses('AppShell', 'Console/Command');

class PaymentShell extends AppShell {

	public $uses = array('WaitingPayment', 'Payment', 'Variable');

    public function initialize()
    {
        ClassRegistry::init('Payment');
        parent::initialize();
    }

	public function run(){
        $this->out('Running..');
        set_time_limit(60 * 60 * 24);
        ini_set('memory_limit', '384M');

        $chanels = array(
            Payment::CHANEL_VIPPAY      => 'Vippay',
            Payment::CHANEL_HANOIPAY    => 'Hanoipay',
            Payment::CHANEL_PAYPAL      => 'Paypal',
            Payment::CHANEL_MOLIN       => 'Molin',
            Payment::CHANEL_ONEPAY      => 'OnePay',
            Payment::CHANEL_PAYMENTWALL => 'PaymentWall'
        );

        foreach($chanels as $chanel => $name) {
            if( $chanel !==  Payment::CHANEL_PAYMENTWALL ) continue;

            $lastPid = $this->Variable->getVar('payment_last_pid_checked_by_chanel_' . $chanel);
            if( !$lastPid ) $lastPid = 0;

            $this->out($this->nl(0));
            $this->out("chanel: " . $chanel . ' - lastPid: ' . $lastPid);

            $this->WaitingPayment->bindModel(array(
                'belongsTo' => array('Game', 'User')
            ));
            $watingPayments = $this->WaitingPayment->find('all', array(
                'conditions' => array(
                    'WaitingPayment.id >'  => $lastPid,
                    'WaitingPayment.chanel'  => $chanel,
                    'WaitingPayment.status <>'  => WaitingPayment::STATUS_COMPLETED,
                    'WaitingPayment.time >= ' => strtotime('-1 hour'),
                    'WaitingPayment.time < '  => strtotime('-15 minute')
                ),
                'contain' => array("User", "Game")
            ));

            if( empty($watingPayments) ) {
                continue;
            }

            # check từng giao dịch trong trạng thái chờ
            foreach ($watingPayments as $wating){
                # this WatingPayment was checked
                if (!empty($wating['WaitingPayment']['id']) && $lastPid >= $wating['WaitingPayment']['id']) {
                    $this->out("Checked pid: " . $wating['WaitingPayment']['id']);
                    continue;
                } else {
                    $this->out("saved pid: " . $wating['WaitingPayment']['id']);
                    $this->Variable->setVar("payment_last_pid_checked_by_chanel_" . $chanel, $wating['WaitingPayment']['id']);
                }

                if (method_exists($this, '__' . $name)) {
                    call_user_func(array($this, '__' . $name), $wating['WaitingPayment']);
                }
            }
        }
    }

    private function __PaymentWall($wating){
        $this->WaitingPayment->id = $wating['id'];
        $this->WaitingPayment->saveField('status', WaitingPayment::STATUS_ERROR, array('callbacks' => false));
    }
}