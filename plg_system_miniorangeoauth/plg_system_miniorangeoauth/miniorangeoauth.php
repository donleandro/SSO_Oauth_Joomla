<?php

/**
 * @package     Joomla.System
 * @subpackage  plg_system_miniorangeoauth
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');


class plgSystemMiniorangeoauth extends JPlugin
{

    public function onAfterInitialise()
    {
        $app = JFactory::getApplication();
        $post = $app->input->post->getArray();



        if (isset($post['mojsp_feedback'])) {
            $radio = $post['deactivate_plugin'];
            $data = $post['query_feedback'];
            $feedback_email = isset($post['feedback_email']) ? $post['feedback_email'] : '';

            $fields = array(
                'uninstall_feedback'=>1
            );
            $conditions = array(
                'id'=>'1'
            );
            $this->miniOauthUpdateDb('#__miniorange_oauth_customer',$fields,$conditions);
            $customerResult=$this->miniOauthFetchDb('#__miniorange_oauth_customer',array('id'=>'1'));
            $admin_email = (isset($customerResult['email']) && !empty($customerResult['email'])) ? $customerResult['email'] : $feedback_email;
            $admin_phone = $customerResult['admin_phone'];

            $data1 = $radio . ' : ' . $data;

            require_once JPATH_BASE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_miniorange_oauth' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'mo_customer_setup.php';

            MoOauthCustomer::submit_feedback_form($admin_email, $admin_phone, $data1);

            require_once JPATH_SITE . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Installer' . DIRECTORY_SEPARATOR . 'Installer.php';

            foreach ($post['result'] as $fbkey) {
                $result = $this->miniOauthFetchDb('#__extensions',array('extension_id'=>$fbkey),'loadColumn','type');
                $type = 0;
                foreach ($result as $results) {
                    $type = $results;
                }
                if ($type) {
                    $cid = 0;
                    $installer = new JInstaller();
                    $installer->uninstall($type, $fbkey, $cid);

                }
            }
        }


        /*----------------------Oauth Flow-------------------------------------------*/


        $get = $app->input->get->getArray();
        $session = JFactory::getSession();
        /*----------------------Test Configuration Handling----------------------------*/
        if (isset($get['morequest']) and $get['morequest'] == 'testattrmappingconfig') {
            $mo_oauth_app_name = $get['app'];

            $app->redirect(JRoute::_(JURI::root() . '?morequest=oauthredirect&app_name=' . urlencode($mo_oauth_app_name) . '&test=true'));
        }
        /*-------------------------OAuth SSO starts with this if-----------
        *            Opening of OAuth server dialog box
                     Step 1 of Oauth/OpenID flow
        */
        else if (isset($get['morequest']) and $get['morequest'] == 'oauthredirect') {

            $appname = $get['app_name'];

            if (isset($get['test']))
                setcookie("mo_oauth_test", true);
            else
                setcookie("mo_oauth_test", false);

            // save the referrer in cookie so that we can come back to origin after SSO
            if (isset($_SERVER['HTTP_REFERER']))
                $loginredirurl = $_SERVER['HTTP_REFERER'];

            if (!empty($loginredirurl)) {
                setcookie("returnurl", $loginredirurl);
            }

            // get Ouath configuration from database
            $appdata = $this->miniOauthFetchDb('#__miniorange_oauth_config', array('custom_app'=>$appname));
            if(is_null($appdata))
                $appdata = $this->miniOauthFetchDb('#__miniorange_oauth_config', array('appname'=>$appname));
            $state = base64_encode($appname);
            $authorizationUrl = $appdata['authorize_endpoint'];
            if (strpos($authorizationUrl, '?') !== false)
                $authorizationUrl = $authorizationUrl . "&client_id=" . $appdata['client_id'] . "&scope=" . $appdata['app_scope'] . "&redirect_uri=" . JURI::root() . "&response_type=code&state=" . $state;
            else
                $authorizationUrl = $authorizationUrl . "?client_id=" . $appdata['client_id'] . "&scope=" . $appdata['app_scope'] . "&redirect_uri=" . JURI::root() . "&response_type=code&state=" . $state;

            if (session_id() == '' || !isset($session))
                session_start();
            $session->set('oauth2state', $state);
            $session->set('appname', $appname);
            if(empty($appdata['client_id']) || empty($appdata['app_scope'])){
                echo "<center><h3 style='color:indianred;border:1px dotted black;'>Sorry! client ID or Scope is empty.</h3></center>";
                exit;
            }
            header('Location: ' . $authorizationUrl);
            exit;
        }

        /*
        *   Step 2 of OAuth Flow starts. We got the code
        *
        */

        else if (isset($get['code'])) {

            if (session_id() == '' || !isset($session))
                session_start();

            try {
                // get the app name from session or by decoding state
                $currentappname = "";
                $session_var = $session->get('appname');
                if (isset($session_var) && !empty($session_var))
                    $currentappname = $session->get('appname');
                else if (isset($get['state']) && !empty($get['state']))
                    $currentappname = base64_decode($get['state']);

                if (empty($currentappname)) {
                    exit('No request found for this application.');
                }
                // get OAuth configuration
                $appname = $session->get('appname');
                $name_attr = "";
                $email_attr = "";


				$appdata = $this->miniOauthFetchDb('#__miniorange_oauth_config', array('custom_app'=>$appname));
				if(is_null($appdata))
					$appdata = $this->miniOauthFetchDb('#__miniorange_oauth_config', array('appname'=>$appname));
                $currentapp = $appdata;

                if (isset($appdata['email_attr']))
                    $email_attr = $appdata['email_attr'];
                if (isset($appdata['first_name_attr']))
                    $name_attr = $appdata['first_name_attr'];

                if (!$currentapp)
                    exit('Application not configured.');

                $authBase = JPATH_BASE . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_miniorange_oauth';
                include_once $authBase . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'oauth_handler.php';

                $mo_oauth_handler = new Mo_OAuth_Hanlder();

                /*
                 * make a back channel request for access token
                 * we may also get an ID token in openid flow
                 *
                 * */
                list($accessToken,$idToken) = $mo_oauth_handler->getAccessToken
                ($currentapp['access_token_endpoint'], 'authorization_code',
                    $currentapp['client_id'], $currentapp['client_secret'], $get['code'], JURI::root(),$currentapp['in_header_or_body']);
                $mo_oauth_handler->printError();
                /*
                *
                * if access token is valid then call userInfo endpoint to get user info or resource  owner details or extract from Id-token
                */

                $resourceownerdetailsurl = $currentapp['user_info_endpoint'];
                if (substr($resourceownerdetailsurl, -1) == "=") {
                    $resourceownerdetailsurl .= $accessToken;
                }
                $resourceOwner = $mo_oauth_handler->getResourceOwner($resourceownerdetailsurl, $accessToken,$idToken);
                $mo_oauth_handler->printError();
                list($email,$name)=$this->getEmailAndName($resourceOwner,$email_attr,$name_attr);
                $checkUser = $this->get_user_from_joomla($email);

                if ($checkUser) {
                    $this->loginCurrentUser($checkUser, $name, $email);
                } else {

                    $this->RegisterCurrentUser($name, $email, $email);
                }

            } catch (Exception $e) {

                exit($e->getMessage());

            }
        }
    }

    function onExtensionBeforeUninstall($id)
    {

        $post = JFactory::getApplication()->input->post->getArray();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('extension_id');
        $query->from('#__extensions');

        $query->where($db->quoteName('name') . " = " . $db->quote('COM_MINIORANGE_OAUTH'));
        $db->setQuery($query);
        $result = $db->loadColumn();

        $tables = JFactory::getDbo()->getTableList();

        $tab = 0;
        foreach ($tables as $table) {
            if (strpos($table, "miniorange_oauth_customer"))
                $tab = $table;
        }


        if ($tab) {

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('uninstall_feedback');
            $query->from('#__miniorange_oauth_customer');
            $query->where($db->quoteName('id') . " = " . $db->quote(1));
            $db->setQuery($query);
            $fid = $db->loadColumn();


            $tpostData = $post;


            foreach ($fid as $value) {

                if ($value == 0) {
                    foreach ($result as $results) {

                        if ($results == $id) {
                            ?>

                            <div class="form-style-6 ">
                                <!-- <span class="mojsp_close">&times;</span> -->
                                <h1> FeedBack Form for Oauth</h1>
                                <h3>What Happened? </h3>

                                <form name="f" method="post" action="" id="mojsp_feedback">
                                    <input type="hidden" name="mojsp_feedback" value="mojsp_feedback"/>
                                    <div>
                                        <p style="margin-left:2%">
                                            <?php
                                            $deactivate_reasons = array(
                                                "Facing issues During Registration",
                                                "Does not have the features I'm looking for",
                                                "Not able to Configure",
                                                "Other Reasons:"
                                            );


                                            foreach ($deactivate_reasons

                                            as $deactivate_reasons) { ?>

                                        <div class=" radio " style="padding:1px;margin-left:2%">
                                            <label style="font-weight:normal;font-size:14.6px"
                                                   for="<?php echo $deactivate_reasons; ?>">
                                                <input type="radio" name="deactivate_plugin"
                                                       value="<?php echo $deactivate_reasons; ?>" required>
                                                <?php echo $deactivate_reasons; ?></label>
                                        </div>

                                        <?php } ?>
                                        <br>

                                        <textarea id="query_feedback" name="query_feedback" rows="4"
                                                  style="margin-left:2%"
                                                  cols="50" placeholder="Write your query here"></textarea><br><br><br>

                                        <tr>
                                <td width="20%"><b>Email<span style="color: #ff0000;">*</span>:</b></td>
                                <td><input type="email" name="feedback_email" required placeholder="Enter email to contact." style="width:55%"/></td>
                                       </tr>

                                        <?php
                                        foreach ($tpostData['cid'] as $key) { ?>
                                            <input type="hidden" name="result[]" value=<?php echo $key ?>>

                                        <?php } ?>


                                        <br><br>
                                        <div class="mojsp_modal-footer">
                                            <input type="submit" name="miniorange_feedback_submit"
                                                   class="button button-primary button-large" value="Submit"/>
                                        </div>
                                    </div>
                                </form>
                                <form name="f" method="post" action="" id="mojsp_feedback_form_close">
                                    <input type="hidden" name="option" value="mojsp_skip_feedback"/>
                                </form>

                            </div>
                            <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
                            <script>


                                jQuery('input:radio[name="deactivate_plugin"]').click(function () {
                                    var reason = jQuery(this).val();
                                    jQuery('#query_feedback').removeAttr('required')

                                    if (reason == 'Facing issues During Registration') {
                                        jQuery('#query_feedback').attr("placeholder", "Can you please describe the issue in detail?");
                                    } else if (reason == "Does not have the features I'm looking for") {
                                        jQuery('#query_feedback').attr("placeholder", "Let us know what feature are you looking for");
                                    } else if (reason == "Other Reasons:") {
                                        jQuery('#query_feedback').attr("placeholder", "Can you let us know the reason for deactivation");
                                        jQuery('#query_feedback').prop('required', true);
                                    } else if (reason == "Not able to Configure") {
                                        jQuery('#query_feedback').attr("placeholder", "Not able to Configure? let us know so that we can improve the interface");
                                    }
                                });


                                // When the user clicks on <span> (x), mojsp_close the mojsp_modal
                                var span = document.getElementsByClassName("mojsp_close")[0];
                                span.onclick = function () {
                                    mojsp_modal.style.display = "none";
                                    jQuery('#mojsp_feedback_form_close').submit();

                                }


                            </script>
                            <style type="text/css">
                                .form-style-6 {
                                    font: 95% Arial, Helvetica, sans-serif;
                                    max-width: 400px;
                                    margin: 10px auto;
                                    padding: 16px;
                                    background: #F7F7F7;
                                }

                                .form-style-6 h1 {
                                    background: #43D1AF;
                                    padding: 20px 0;
                                    font-size: 140%;
                                    font-weight: 300;
                                    text-align: center;
                                    color: #fff;
                                    margin: -16px -16px 16px -16px;
                                }

                                .form-style-6 input[type="text"],
                                .form-style-6 input[type="date"],
                                .form-style-6 input[type="datetime"],
                                .form-style-6 input[type="email"],
                                .form-style-6 input[type="number"],
                                .form-style-6 input[type="search"],
                                .form-style-6 input[type="time"],
                                .form-style-6 input[type="url"],
                                .form-style-6 textarea,
                                .form-style-6 select {
                                    -webkit-transition: all 0.30s ease-in-out;
                                    -moz-transition: all 0.30s ease-in-out;
                                    -ms-transition: all 0.30s ease-in-out;
                                    -o-transition: all 0.30s ease-in-out;
                                    outline: none;
                                    box-sizing: border-box;
                                    -webkit-box-sizing: border-box;
                                    -moz-box-sizing: border-box;
                                    width: 100%;
                                    background: #fff;
                                    margin-bottom: 4%;
                                    border: 1px solid #ccc;
                                    padding: 3%;
                                    color: #555;
                                    font: 95% Arial, Helvetica, sans-serif;
                                }

                                .form-style-6 input[type="text"]:focus,
                                .form-style-6 input[type="date"]:focus,
                                .form-style-6 input[type="datetime"]:focus,
                                .form-style-6 input[type="email"]:focus,
                                .form-style-6 input[type="number"]:focus,
                                .form-style-6 input[type="search"]:focus,
                                .form-style-6 input[type="time"]:focus,
                                .form-style-6 input[type="url"]:focus,
                                .form-style-6 textarea:focus,
                                .form-style-6 select:focus {
                                    box-shadow: 0 0 5px #43D1AF;
                                    padding: 3%;
                                    border: 1px solid #43D1AF;
                                }

                                .form-style-6 input[type="submit"],
                                .form-style-6 input[type="button"] {
                                    box-sizing: border-box;
                                    -webkit-box-sizing: border-box;
                                    -moz-box-sizing: border-box;
                                    width: 100%;
                                    padding: 3%;
                                    background: #43D1AF;
                                    border-bottom: 2px solid #30C29E;
                                    border-top-style: none;
                                    border-right-style: none;
                                    border-left-style: none;
                                    color: #fff;
                                }

                                .form-style-6 input[type="submit"]:hover,
                                .form-style-6 input[type="button"]:hover {
                                    background: #2EBC99;
                                }


                            </style>

                            <?php
                            exit;
                        }
                    }
                }
            }

        }
    }
    function getEmailAndName($resourceOwner,$email_attr,$name_attr){

            //TEST Configuration
            $test_cookie = JFactory::getApplication()->input->cookie->get('mo_oauth_test');
            if (isset($test_cookie) && !empty($test_cookie)) {
                echo '<style>table{border-collapse: collapse;}table, td, th {border: 1px solid black;padding:4px}</style>';
                echo "<h2>Test Configuration</h2><table><tr><th>Attribute Name</th><th>Attribute Value</th></tr>";
                $this->testattrmappingconfig("", $resourceOwner);
                echo "</table>";
                exit();
            }

            if (!empty($email_attr)) {
                $email = $this->getnestedattribute($resourceOwner, $email_attr);
            } else {

                echo '<div style="font-family:Calibri;padding:0 3%;">';
                echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
            <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong> Login not Allowed</p>
            <p><strong>Causes</strong>: Attibute Mapping is empty. Please configure it.</p>
            </div>';
                $base_url = JURI::root();
                echo '<p align="center"><a href="' . $base_url . '" style="text-decoration: none; padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button">Done</a></p>';
                exit;

            }
            if (!empty($name_attr))
                $name = $this->getnestedattribute($resourceOwner, $name_attr);


        if (empty($email)) {

            exit('Email address not received. Check your <b>Attribute Mapping</b> configuration.');

            {
                echo '<div style="font-family:Calibri;padding:0 3%;">';
                echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
                              <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>Email Address not recived.</p>
                              <p><strong>Cause:</strong>Email Id not received for the email attribute name you configured. Check your <b>Attribute Mapping</b> configuration.</p></div></div><br>';
                $home_link = JURI::root();
                echo '<p align="center"><a href=' . $home_link . ' type="button" style="color: white; background: #185b91; padding: 10px 20px;">Back to Website</a><p>';
                exit;
            }

        }
        return array($email,$name);

    }

    function testattrmappingconfig($nestedprefix, $resourceOwnerDetails)
    {

        foreach ($resourceOwnerDetails as $key => $resource) {
            if (is_array($resource) || is_object($resource)) {
                if (!empty($nestedprefix))
                    $nestedprefix .= ".";
                $this->testattrmappingconfig($nestedprefix . $key, $resource);
            } else {
                echo "<tr><td>";
                if (!empty($nestedprefix))
                    echo $nestedprefix . ".";
                echo $key . "</td><td>" . $resource . "</td></tr>";
            }
        }
    }

    function getnestedattribute($resource, $key)
    {
        //echo $key." : ";print_r($resource); echo "<br>";
        if (empty($key))
            return "";

        $keys = explode(".", $key);
        if (sizeof($keys) > 1) {
            $current_key = $keys[0];
            if (isset($resource[$current_key]))
                return $this->getnestedattribute($resource[$current_key], str_replace($current_key . ".", "", $key));
        } else {
            $current_key = $keys[0];
            if (isset($resource[$current_key]))
                return $resource[$current_key];
        }
    }

    function get_user_from_joomla($email)
    {
        //Check if email exist in database
        $db = JFactory::getDBO();
        $query = $db->getQuery(true)
            ->select('id')
            ->from('#__users')
            ->where('email=' . $db->quote($email));
        $db->setQuery($query);
        $checkUser = $db->loadObject();
        return $checkUser;
    }

    function loginCurrentUser($checkUser, $name, $email)
    {

        $app = JFactory::getApplication();
        $user = JUser::getInstance($checkUser->id);
        $this->updateCurrentUserName($user->id, $name);

        $session = JFactory::getSession(); #Get current session vars
        // Register the needed session variables
        $session->set('user', $user);

        //$app->checkSession();
        $sessionId = $session->getId();
        $this->updateUsernameToSessionId($user->id, $user->username, $sessionId);

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__miniorange_oauth_customer'));
        $query->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $result = $db->loadAssoc();


        $user->setLastVisit();
        $db = JFactory::getDbo();
        // $query = $db->getQuery(true);
        // $query->select('*');
        // $query->from($db->quoteName('#__miniorange_oauth_config'));
        // $query->where($db->quoteName('id')." = 1");
        // $db->setQuery($query);
        // $result = $db->loadAssoc();

        $returnurl = JFactory::getApplication()->input->cookie->getArray();
        if (isset($returnurl['returnurl'])) {
            $redirectloginuri = $returnurl['returnurl'];
        } else {

            $redirectloginuri = JURI::root() . 'index.php?';
        }


        $app->redirect($redirectloginuri);
    }
	function getRows( $query, $limitStart=0, $recs=0, $key='' ){
		$db	= JFactory::getDBO();

		if ( $recs > 0 ) {
			$db->setQuery( $query, $limitStart, $recs );
		}else{
			$db->setQuery( $query );
		}

		if ($key!=''){
			$rows = $db->loadObjectList($key);
		}else{
			$rows = $db->loadObjectList();
		}
		return $rows;
	}

	function getRow( $query ){
	    $rows = $this->getRows( $query );
		$row  = '';
		if ( count( $rows ) > 0 ) $row  = $rows[0];
		return $row;
	}
	function doQuery( $query ){
		$db	= JFactory::getDBO();
		$db->setQuery( $query );

	    return $db->query();
	}
    function updateCurrentUserName($id, $name)
    {
        if (empty($name)) {
            return;
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('name') . ' = ' . $db->quote($name),
        );
        $conditions = array(
            $db->quoteName('id') . ' = ' . $db->quote($id),
        );
        $query->update($db->quoteName('#__users'))->set($fields)->where($conditions);

        $db->setQuery($query);

        $result = $db->execute();
    }

    function updateUsernameToSessionId($userID, $username, $sessionId)
    {


        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('username') . ' = ' . $db->quote($username),
            $db->quoteName('guest') . ' = ' . $db->quote('0'),
            $db->quoteName('userid') . ' = ' . $db->quote($userID),
        );

        $conditions = array(
            $db->quoteName('session_id') . ' = ' . $db->quote($sessionId),
        );

        $query->update($db->quoteName('#__session'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();
    }


    function RegisterCurrentUser($name, $username, $email)
    {
        $app = JFactory::getApplication();
        $default_group = 11;
        $data['name'] = (isset($name) && ($name != "")) ? $name : $username;
        $data['email'] = $data['email1'] = $data['email2'] = JStringPunycode::emailToPunycode($email);
        $data['password'] = $data['password1'] = $data['password2'] = JUserHelper::genRandomPassword();
        $data['activation'] = '0';
        $data['block'] = '0';
	      $pass=$data['password2'];
        $data['groups'][] = $default_group;


        if (strpos($username, '@') !== false)
        {
              $temporal = explode("@",$username);
              $data['username'] = $temporal[0];
            }
        else
        {
              $data['username'] = $username;
        }

        // Get the model and validate the data.
        jimport('joomla.application.component.model');

        if (!defined('JPATH_COMPONENT')) {
            define('JPATH_COMPONENT', JPATH_BASE . '/components/');
        }


        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__miniorange_oauth_config'));
        $query->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $result = $db->loadAssoc();


        $user = new JUser;
        //Write to database
        if (!$user->bind($data)) {
            throw new Exception("Could not bind data. Error: " . $user->getError());
        }
        if (!$user->save()) {
            echo 'Could Not Save User Details ' . $user->getError();
            exit;
        }

        $checkUser = $this->get_user_from_joomla($email);

        if ($checkUser) {
            $user = JUser::getInstance($checkUser->id);


			$credentials = array(
                    'username' => $user->username,
                    'password' => $pass);

                $options = array();

                $result2 = $app->login($credentials, array('action' => 'core.login.admin'));


            $session = JFactory::getSession(); #Get current session vars
            // Register the needed session variables
            $session->set('user', $user);

            $app->checkSession();
            $sessionId = $session->getId();
            $this->updateUsernameToSessionId($user->id, $user->username, $sessionId);

            $user->setLastVisit();

            $returnurl = JFactory::getApplication()->input->cookie->getArray();
            if (isset($returnurl['returnurl'])) {
                $redirectloginuri = $returnurl['returnurl'];
            } else {

                $redirectloginuri = JURI::root() . 'index.php?';
            }

            $app->redirect(JURI::root() . 'index.php?');
        }

    }

    function miniOauthFetchDb($tableName,$condition,$method='loadAssoc',$columns='*'){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $columns = is_array($columns)?$db->quoteName($columns):$columns;
        $query->select($columns);
        $query->from($db->quoteName($tableName));

        foreach ($condition as $key=>$value)
            $query->where($db->quoteName($key) . " = " . $db->quote($value));

        $db->setQuery($query);
        if ($method=='loadColumn')
            return $db->loadColumn();
        else if($method == 'loadObjectList')
            return $db->loadObjectList();
        else if($method== 'loadResult')
            return $db->loadResult();
        else if($method == 'loadRow')
            return $db->loadRow();
        else
            return $db->loadAssoc();
    }
    function miniOauthUpdateDb($tableName,$fields,$conditions){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        // Fields to update.
        $sanFields=array();
        foreach ($fields as $key=>$value){
            array_push($sanFields,$db->quoteName($key) . ' = ' . $db->quote($value));
        }
        // Conditions for which records should be updated.
        $sanConditions=array();
        foreach ($conditions as $key=>$value){
            array_push($sanConditions,$db->quoteName($key) . ' = ' . $db->quote($value));
        }
        $query->update($db->quoteName($tableName))->set($sanFields)->where($sanConditions);
        $db->setQuery($query);
        $db->execute();
    }

}
