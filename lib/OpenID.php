<?php

ini_set('include_path', Util::$rootPath . '/lib' . PATH_SEPARATOR . ini_get('include_path'));
require_once 'Auth/OpenID/Consumer.php';
require_once 'Auth/OpenID/FileStore.php';
require_once 'Auth/OpenID/SReg.php';
require_once 'Auth/OpenID/AX.php';

if (!isset($_SESSION)) {
  session_start();
}

class OpenID {

  private static function getStore() {
    $storePath = '/tmp/mule_openidStorePath';
    if (!file_exists($storePath) && !mkdir($storePath)) {
      print "Could not create the FileStore directory '$storePath'";
      exit(0);
    }
    return new Auth_OpenID_FileStore($storePath);
  }

  private static function getConsumer() {
    return new Auth_OpenID_Consumer(self::getStore());
  }

  private static function getReturnTo() {
    return Util::getFullServerUrl() . "/auth/openidReturn";
  }

  /**
   * Returns false and sets a flash message on all errors.
   **/
  static function beginAuth($openid, $policyUris) {
    $consumer = self::getConsumer();
    $authRequest = $consumer->begin($openid);

    if (!$authRequest) {
      FlashMessage::add(_('You have entered an invalid OpenID.'));
      return false;
    }

    $sregRequest = Auth_OpenID_SRegRequest::build(array('nickname'), array('fullname', 'email'));
    if ($sregRequest) {
      $authRequest->addExtension($sregRequest);
    }

    $ax = new Auth_OpenID_AX_FetchRequest;
    $ax->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson', 1, 1, 'fullname'));
    $ax->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/contact/email', 1, 1, 'email'));
    $ax->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/first', 1, 1, 'firstname'));
    $ax->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/last', 1, 1, 'lastname'));
    $authRequest->addExtension($ax);

    // For OpenID 1, send a redirect.  For OpenID 2, use a Javascript form to send a POST request to the server.
    if ($authRequest->shouldSendRedirect()) {
      $redirectUrl = $authRequest->redirectURL(Util::getFullServerUrl(), self::getReturnTo());

      if (Auth_OpenID::isFailure($redirectUrl)) {
        FlashMessage::add('Nu vă putem redirecționa către serverul OpenID: ' . $redirectUrl->message);
        return false;
      } else {
        Util::redirect($redirectUrl);
      }
    } else {
      $formHtml = $authRequest->htmlMarkup(Util::getFullServerUrl(), self::getReturnTo(), false, array('id' => 'openidMessage'));

      if (Auth_OpenID::isFailure($formHtml)) {
        FlashMessage::add(_('We cannot redirect you to the OpenID server: ') . $formHtml->message);
        return false;
      } else {
        print $formHtml;
      }
    }
    return true;
  }

  /**
   * Returns null and sets a flash message on all errors.
   **/
  static function finishAuth() {
    $consumer = self::getConsumer();
    $response = $consumer->complete(self::getReturnTo());

    if ($response->status == Auth_OpenID_CANCEL) {
      FlashMessage::add(_('OpenID login canceled.'), 'warning');
      return null;
    } else if ($response->status == Auth_OpenID_FAILURE) {
      FlashMessage::add(_('OpenID login failed: ') . $response->message);
      return null;
    } else if ($response->status == Auth_OpenID_SUCCESS) {
      $result = array();
      $result['identity'] = htmlentities($response->getDisplayIdentifier());

      if ($response->endpoint->canonicalID) {
        $escapedCanonicalId = htmlentities($response->endpoint->canonicalID);
        // Ignored for now
      }

      $sregResp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
      if ($sregResp) {
        $sreg = $sregResp->contents();
        if (isset($sreg['email'])) {
          $result['email'] = $sreg['email'];
        }
        if (isset($sreg['nickname'])) {
          $result['nickname'] = $sreg['nickname'];
        }
        if (isset($sreg['fullname'])) {
          $result['fullname'] = $sreg['fullname'];
        }
      }

      $axResp = Auth_OpenID_AX_FetchResponse::fromSuccessResponse($response);
      if ($axResp) {
        $data = $axResp->data;
        if (isset($data['http://axschema.org/contact/email']) && count($data['http://axschema.org/contact/email'])) {
          $result['email'] = $data['http://axschema.org/contact/email'][0]; // Take this over sreg
        }
        if (isset($data['http://axschema.org/namePerson']) && count($data['http://axschema.org/namePerson'])) {
          $result['fullname'] = $data['http://axschema.org/namePerson'][0];
        }
        $names = array();
        if (isset($data['http://axschema.org/namePerson/first']) && count($data['http://axschema.org/namePerson/first'])) {
          $names[] = $data['http://axschema.org/namePerson/first'][0];
        }
        if (isset($data['http://axschema.org/namePerson/last']) && count($data['http://axschema.org/namePerson/last'])) {
          $names[] = $data['http://axschema.org/namePerson/last'][0];
        }
        if (count($names)) {
          $result['fullname'] = implode(' ', $names);
        }
      }

      return $result;
    }
  }
}

?>
