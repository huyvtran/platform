<?php

App::uses('Validation', 'Utility');
App::uses('HttpSocket', 'Network/Http');
App::uses('RedisQueue', 'Lib');
App::import('Helper', 'Imagine.Imagine');
App::import('Helper', 'Html');
class Utility {

	/**
	 * faster than Valication class in Cakephp
	 **/
	public static function validateEmail($email)
	{
		if (preg_match('/[^\@]*\@([^\.]*).*/', $email, $matches)) {
			# is popular email privoder
			if (in_array($matches[1], array('yahoo', 'gmail', 'hotmail', 'live'))) {
				return true;
			}
		}
		if ($matches[1] == 'haitacmobi') {
			return false;
		}

		# if this email provider isn't popularuse Cakephp class, process a deep check 
		return Validation::email($email, true);
	}

	public static function createUnsubscribeLink($domain, $emailAddress, $gameId = null)
	{
		$EmailMarketing = ClassRegistry::init('EmailMarketing');

		$token = $EmailMarketing->unsubscribleToken($emailAddress);
		$unsubscribeLink = "http://" . $domain . "/emailFeedbacks/unsubscribe?e="
			. $EmailMarketing->hashStr($emailAddress) . "&t=" . $token;
		if (!empty($gameId)) {
			$unsubscribeLink .= "&g=" . $EmailMarketing->hashStr($gameId);
		}
		return $unsubscribeLink;
	}

	    /**
    * Temporarily verify email 
    * http://api-docs.emailhippo.com/en/latest/data-dictionary.html
    **/
    public static function tempVerify($email)
    {
        $Http = new HttpSocket();
        try {
            $res = $Http->get('https://api1.27hub.com/api/emh/a/v2?k=B96B60DD&e=' . $email);

        } catch (Exception $e) {
            CakeLog::info('Exception, can not verify email by hippo' . $e->getMessage(), 'email');
            throw new Exception($e->getMessage);
        }

                
        if ($res->code == 200) {
            $result = json_decode($res->body, true);
            
            if (empty($result)) {
                return false;
            }

            if ($result['result'] == 'Bad') {
                return false;
            }
            if ($result['result'] == 'Unverifiable' || $result['result'] == 'RetryLater') {
                throw new Exception ('Try again');
            }
            if ($result['result'] == 'Ok' && $result['disposable'] == false) {
                return true;
            }
            return false;
        }
        CakeLog::info('Error network, can not verify email by hippo ' . $res->code, 'email');
        throw new Exception ('Try again');
    }

	# check email was unsubscribled , bounced, complained, ...
	public static function wasBlocked($address, $gameId = 0)
	{
		$Redis = new RedisQueue('default', 'emailfeedbacklist');
		$gameId = $rank = $Redis->zRank($address);
		if ($rank === false) {
			return false;
		}
		return true;
	}

	public static function image($image, $width = 0, $height = 0, $options = array())
	{
		$Helper = new HtmlHelper(new View());
		$Imaine = new ImagineHelper(new View());
		if (!empty($image['name'])) {
			if (!isset($options['type'])) {
				$options['type'] = 'outbound';
			}

			if (!empty($image['data'])) {
				foreach($image['data'] as $img) {

					if (	!empty($img['width'])
						&&	$width == $img['width']
						&& 	(!$height || ($height == $img['height']))
						&&	(isset($options['type']) && $options['type'] == $img['type'])
						&&	(	(	empty($options['retina']) && empty($img['is_retina']))
							|| (!empty($options['retina']) && !empty($img['is_retina']))
						)
						||	(	!$width && !$height
							&& isset($img['is_origin']) && $img['is_origin'] == true
						)
					) {
						# if this image was uploaded to aws s3
						if (!empty($img['aws']['ObjectURL'])) {
							$url = $img['aws']['ObjectURL'];
							if (env('HTTPS') != 'on') {
								$url = str_replace('https', 'http', $img['aws']['ObjectURL']);
							}
							$url = str_replace('s3-ap-southeast-1.amazonaws.com/emagbom.plf', 'cdn.smobgame.com', $url);
						} else {
							$url = Configure::read('static') . str_replace('\\', '/', $img['dir']);
						}
					}
				}
			}

			if (empty($url)) {
				if (isset($width, $height)) {
					try {
						unset($options['alt']); # avoid special characters from alt

						if ($imgUrl = $Imaine->imageUrl(
							array('controller' => 'images', 'action' => 'resize', $image['id'], 'admin' => false),
							array('thumbnail' => array_merge(array('width' => $width, 'height' => $height), $options))
						)) {
							$resizeUrl = Utility::image($imgUrl, $width, $height, $options);
							if ($resizeUrl != false) {
								return $resizeUrl;
							}
						}
					} catch (Exception $e) {
						CakeLog::error('images resize error' . $e->getMessage());
					}
				}
			}
		}

		if (empty($url)) {
			if (isset($options['empty'])) {
				if (is_string($options['empty'])) {
					$url = $options['empty'];
				} else {
					$url = "noAvatar.png";
				}
			} else {
				return 'NoImage';
			}
		}

		if (empty($options['urlonly'])) {
			return $Helper->image($url,
				array_merge(
					array('width' => $width, 'height' => $height),
					$options
				)
			);
		}
		return $url;
	}

}