<?php
App::uses('AppModel', 'Model');

class Profile extends AppModel {

    public $actsAs = array(
        'Search.Searchable'
    );

    public $filterArgs = array(
        'from_time' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'Profile.birthday >= '),
        'to_time' => array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'Profile.birthday <= '),
    );

    public $validate = array(
        'email_contact' => array(
            'required' => array(
                'rule'       => 'notEmpty',
                'required'   => true,
                'allowEmpty' => false,
                'message'    => 'Hãy nhập email.'
            ),
            'email' => array(
                'rule'    => array('email', true),
                'message' => 'Email không hợp lệ.'
            )
        ),
        'peopleId'=>array(
            'required' => array(
                'rule'       => 'notEmpty',
                'required'   => true,
                'allowEmpty' => false,
                'message'    => 'CMND không được để trống.'
            ),
            'minLength' => array(
                'rule' => array('minLength', 9),
                'message' => 'CMND phải từ 9 đến 13 ký tự.'),
            'maxLength' => array(
                'rule'    => array('maxLength', 13),
                'message' => 'CMND phải từ 9 đến 13 ký tự.'
            )
        ), 'phone'=>array(
            'required' => array(
                'rule'       => 'notEmpty',
                'required'   => true,
                'allowEmpty' => false,
                'message'    => 'Số điện thoại không được để trống.'
            ),
            'minLength' => array(
                'rule' => array('minLength', 10),
                'message' => 'Số điện thoại phải từ 10 đến 11 số.'),
            'maxLength' => array(
                'rule'    => array('maxLength', 11),
                'message' => 'Số điện thoại phải từ 10 đến 11 số.'
            )
        ),
        'peopleId_place_get'=>array(
            'required' => array(
                'rule'       => 'notEmpty',
                'required'   => true,
                'allowEmpty' => false,
                'message'    => 'Nơi cấp không được để trống.'
            )),
        'peopleId_date_get'=>array(
            'required' => array(
                'rule'       => 'notEmpty',
                'required'   => true,
                'allowEmpty' => false,
                'message'    => 'Ngày cấp không được để trống.'
            ),
            'checkDate'=>array(
                'rule'       => 'checkDate',
                'message'    => 'Ngày cấp không hợp lệ'
            ),
            'date' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Bạn phải nhập đúng định dạng YYYY-MM-DD.',
            )
        ),
        'birthday'=>array(
            'required' => array(
                'rule'       => 'notEmpty',
                'required'   => true,
                'allowEmpty' => false,
                'message'    => 'Ngày sinh không được để trống.'
            ),
            'checkBirthday'=>array(
                'rule'       => 'checkBirthday',
                'message'    => 'Ngày sinh không hợp lệ'
            ),
            'date' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Bạn phải nhập đúng định dạng YYYY-MM-DD.',
            )
        )
    );
    public function fromTimeCond($data = array())
    {
        return date('Y-m-d 0:0:0', $data['from_time']);
    }

    public function toTimeCond($data = array())
    {
        return date('Y-m-d 23:59:59', $data['to_time']);
    }

    public function emailVerify($postData = array()){
        $existed_profile = $this->find('first', array(
            'conditions' => array(
                'Profile.user_id' => $postData['Profile']['user_id']
            )
        ));
        $user = ClassRegistry::init('User');
        $result = array();
        $token = $user->generateToken();
        if (empty($existed_profile)) {
            $this->create();
            if($this->save(array('Profile' => array(
                'user_id'                     => $postData['Profile']['user_id'],
                'email_contact'               => $postData['Profile']['email_contact'],
                'email_contact_token'         => $token,
                'email_contact_token_expires' => date('Y-m-d H:i:s', time() + 7 * 86400),
                'email_contact_verified'      => 0
                )
            )))
            {
                $result = array(
                    'code'    => 1, 
                    'message' => __('Đã tạo mới thành công')
                );
            }
            else
            {
                $result = array(
                    'code'    => -1, 
                    'message' => __('Đã có lỗi xảy ra khi lưu')
                );
            }
        }else{
            $this->id = $existed_profile['Profile']['id'];
            if($this->save(array('Profile' => array(
                'email_contact'               => $postData['Profile']['email_contact'],
                'email_contact_token'         => $token,
                'email_contact_token_expires' => date('Y-m-d H:i:s', time() + 7 * 86400),
                )
            )))
            {
                $result = array(
                    'code'    => 1, 
                    'message' => __('Đã cập nhật thành công')
                );
            }
            else
            {
                $result = array(
                    'code'    => -1, 
                    'message' => __('Đã có lỗi xảy ra khi lưu')
                );
            }
        }

        if($result['code'] == 1)
        {
            //send mail.
            $infoGame        = ClassRegistry::init('Game');
            $emailMarketing  = ClassRegistry::init('EmailMarketing');
            $gameHash        = $emailMarketing->hashStr($postData['Game']['id']);
            $infomationGame  = $infoGame->find('first',array(
                'conditions'=> array('Game.id'=>$postData['Game']['id']),
                'contain'   => array('Website','Logo')
            ));
            $infomationGame['User']['email']          = $postData['Profile']['email_contact'];
            $infomationGame['User']['email_token']    = 'email_token';
            $infomationGame['User']['password_token'] = 'password_token';
            $infomationGame['User']['username']       = 'User';
            $infomationGame['Game']['Website']        = $infomationGame['Website'];
            $infomationGame['Game']['Logo']           = $infomationGame['Logo'];
            if(count($infomationGame) > 0){ 
                $emailModel = ClassRegistry::init('Email');
                $template = $file = 'profile_update_email';
            }
            $emailParameter               = $emailModel->composeParameterEmail($template,$infomationGame);
            $verifyLink                   = 'http://'.$infomationGame['Website']['url'].'/profiles/verifyemail?code='.$token."&g=".$gameHash;
            $emailParameter['verifyLink'] = $verifyLink;
            $subject                      = __('Xác nhận email liên hệ');
            $type_email                   = 'update_email_contact';
            $email                        = $postData['Profile']['email_contact'];
            if($email!= ''){
                try {
                    if(!$emailModel->sendEmail($emailParameter,$infomationGame,$subject,$type_email,$email))
                        CakeLog::info('problem update email contact: '. $infomationGame['User']['email'], 'email');    
                } catch (Exception $e) {
                    CakeLog::info('problem ' . $e->getMessage(), 'email');
                }
                $message = 'Get Content and Send email success!';
            }
        }
        return $result;

    }

    public function verifyEmailToken($token = null) {
        $profile = $this->find('first', array(
            'conditions' => array(
                $this->alias . '.email_contact_token' => $token),
            'fields' => array(
                'id', 'email_contact', 'email_contact_token_expires', 'email_contact_verified')));
        if (empty($profile)) {
            throw new RuntimeException('Invalid PIN, please check the email you were sent, and retry the verification link.');
        }
        if ($profile[$this->alias]['email_contact_verified'] == true) {
            return $profile;
        }

        $expires = strtotime($profile[$this->alias]['email_contact_token_expires']);
        if ($expires < time()) {
            throw new RuntimeException('The PIN has expired.' . $profile[$this->alias]['email_contact_token_expires']);
        }

        $profile[$this->alias]['email_contact_verified'] = 1;

        $profile = $this->save($profile, array(
            'validate' => false,
            'callbacks' => false));
        $this->data = $profile;
        return $profile;
    }
    public function saveInfoUser($data){
            if($this->save($data)){
            }else{
                CakeLog::error('Error save data to pfofile');
            }
    }
    public function updateProfile($user_id,$data){
        $data['Profile']['user_id']  =   $user_id;
        $existed_profile = $this->find('first', array(
            'conditions' => array(
                'user_id' => $user_id
            )
        ));
        if(count($existed_profile) > 0){
            $this->id = $existed_profile['Profile']['id'];
        }else{
            $this->create();
        }
        if($this->save($data)){
           return 1;
        }else{
           return 0;
        }
    }
    public function checkProfile($id_user){
        $profile    =   $this->findByUser_id($id_user);
        if(count($profile) > 0){
           if($profile['Profile']['email_contact'] != null && $profile['Profile']['fullname'] != null &&  $profile['Profile']['peopleId'] != null
               &&  $profile['Profile']['province'] != null &&  $profile['Profile']['peopleId_place_get'] != null &&  $profile['Profile']['birthday'] != null
               &&  $profile['Profile']['peopleId_date_get'] != null &&  $profile['Profile']['phone'] != null &&  $profile['Profile']['address'] != null
               &&  $profile['Profile']['gender'] != null ){
                return true;
           }else{
               return false;
           }
        }else{
            return false;
        }
    }
    public function questionDefault(){
        return array(
            'list_quest1'=>array(
                '1'=>'Bạn ghét điều gì nhất?',
                '2'=>'Bạn thường làm gì khi rảnh rỗi?',
                '3'=>'Bạn yêu quí người nào nhất?',
                '4'=>'Bộ phim nào gây ấn tượng nhất với bạn?',
                '5'=>'Ca sĩ nào là thần tượng của bạn?',
                '6'=>'Công việc lý tưởng của bạn là gì?',
                '7'=>'Diễn viên nào là thần tượng của bạn?',
                '8'=>'Mơ ước của bạn là gì?',
                '9'=>'Món ăn bạn ưa thích nhất?'
        ),
            'list_quest2'=>array(
                '10'=>'Môn thể thao yêu thích của bạn là gì?',
                '11'=>'Người bạn thân nhất của bạn là ai?',
                '12'=>'Số PinCode trên thẻ tạo sẵn?',
                '13'=>'Nơi sinh của bạn ở đâu?',
                '14'=>'Trường học tiểu học của bạn tên gì?',
                '15'=>'Họ của mẹ bạn là gì?',
                '16'=>'Tên công ty đầu tiên bạn làm việc?',
                '17'=>'Tên trường đại học mà bạn đã học?',
                '18'=>'Bạn gặp vợ (chồng) mình ở đâu?'
            ),


        );
    }
    public function getProvince(){
         return  array(
             'Hà Nội' => 'Hà Nội',
             'TP HCM' => 'TP HCM',
            'An Giang' => 'An Giang',
            'Bà Rịa - Vũng Tàu' => 'Bà Rịa - Vũng Tàu',
            'Bắc Giang' => 'Bắc Giang',
            'Bắc Kạn' => 'Bắc Kạn',
            'Bạc Liêu' => 'Bạc Liêu',
            'Bắc Ninh' => 'Bắc Ninh',
            'Bến Tre' => 'Bến Tre',
            'Bình Định' => 'Bình Định',
            'Bình Dương' => 'Bình Dương',
            'Bình Phước' => 'Bình Phước',
            'Bình Thuận' => 'Bình Thuận',
            'Cà Mau' => 'Cà Mau',
            'Cao Bằng' => 'Cao Bằng',
            'Đắk Lắk' => 'Đắk Lắk',
            'Đắk Nông' => 'Đắk Nông',
            'Điện Biên' => 'Điện Biên',
            'Đồng Nai' => 'Đồng Nai',
            'Đồng Tháp' => 'Đồng Tháp',
            'Gia Lai' => 'Gia Lai',
            'Hà Giang' => 'Hà Giang',
            'Hà Nam' => 'Hà Nam',
            'Hà Tĩnh' => 'Hà Tĩnh',
            'Hải Dương' => 'Hải Dương',
            'Hậu Giang' => 'Hậu Giang',
            'Hòa Bình' => 'Hòa Bình',
            'Hưng Yên' => 'Hưng Yên',
            'Khánh Hòa' => 'Khánh Hòa',
            'Kiên Giang' => 'Kiên Giang',
            'Kon Tum' => 'Kon Tum',
            'Lâm Đồng' => 'Lâm Đồng',
            'Lạng Sơn' => 'Lạng Sơn',
            'Lào Cai' => 'Lào Cai',
            'Lai Châu' => 'Lai Châu',
            'Long An' => 'Long An',
            'Nam Định' => 'Nam Định',
            'Nghệ An' => 'Nghệ An',
            'Ninh Bình' => 'Ninh Bình',
            'Ninh Thuận' => 'Ninh Thuận',
            'Phú Thọ' => 'Phú Thọ',
            'Quảng Bình' => 'Quảng Bình',
            'Quảng Nam' => 'Quảng Nam',
            'Quảng Ngãi' => 'Quảng Ngãi',
            'Quảng Ninh' => 'Quảng Ninh',
            'Quảng Trị' => 'Quảng Trị',
            'Sóc Trăng' => 'Sóc Trăng',
            'Sơn La' => 'Sơn La',
            'Tây Ninh' => 'Tây Ninh',
            'Thái Nguyên' => 'Thái Nguyên',
            'Thái Bình' => 'Thái Bình',
            'Thanh Hóa' => 'Thanh Hóa',
            'Thừa Thiên Huế' => 'Thừa Thiên Huế',
            'Tiền Giang' => 'Tiền Giang',
            'Trà Vinh' => 'Trà Vinh',
            'Tuyên Quang' => 'Tuyên Quang',
            'Vĩnh Long' => 'Vĩnh Long',
            'Vĩnh Phúc' => 'Vĩnh Phúc',
            'Yên Bái' => 'Yên Bái',
            'Phú Yên' => 'Phú Yên',
            'Cần Thơ' => 'Cần Thơ',
            'Đà Nẵng' => 'Đà Nẵng',
            'Hải Phòng' => 'Hải Phòng'
        );
    }
    public function checkProfileByFields($user_id,$input){
        $profile    =   $this->findByUser_id($user_id);
        if(empty($profile)){
            return false;
        }
        foreach($input as $t){
            if($profile['Profile'][$t] == null){
                return false;
                break;
            }
        }
       return true;
    }
    public function checkToken($user_id,$token,$type_token,$type_token_expires){
        $check_code = $this->find('first', array(
            'conditions' => array(
                'user_id' => $user_id,
                $type_token=> $token,
                $type_token_expires .' >= ' => date('Y-m-d H:i:s')
            ),
            'contain' => array()
        ));
        if(empty($check_code)){
            return false;
        }else{
            return true;
        }
    }
    public function checkDate() {
        if (isset($this->data[$this->alias]['peopleId_date_get']) && strtotime($this->data[$this->alias]['peopleId_date_get']) > time()){
            return false;
        }
        return true;
    }
    public function checkBirthday() {
        if (isset($this->data[$this->alias]['birthday']) && strtotime($this->data[$this->alias]['birthday']) > time()){
            return false;
        }
        return true;
    }
}