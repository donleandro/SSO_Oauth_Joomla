<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_miniorange_oauth
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JHtml::_('jquery.framework');
JHtml::stylesheet(JURI::base() . 'components/com_miniorange_oauth/assets/css/miniorange_oauth.css', array(), true);
JHtml::stylesheet(JURI::base() . 'components/com_miniorange_oauth/assets/css/bootstrap-tour-standalone.css', array(), true);
JHtml::script(JURI::base() . 'components/com_miniorange_oauth/assets/js/bootstrap-tour-standalone.min.js');
JHtml::script(JURI::base() . 'components/com_miniorange_oauth/assets/js/jeswanthscript.js');
JHtml::stylesheet('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), true);

if (MoOAuthUtility::is_curl_installed() == 0) { ?>
    <p style="color:red;">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP CURL
            extension</a> is not installed or disabled) Please go to Troubleshooting for steps to enable curl.</p>
    <?php
}

$active_tab = JFactory::getApplication()->input->get->getArray();
$oauth_active_tab = isset($active_tab['tab-panel']) && !empty($active_tab['tab-panel']) ? $active_tab['tab-panel'] : 'configuration';

$current_user = JFactory::getUser();
if (!JPluginHelper::isEnabled('system', 'miniorangeoauth')) {
    ?>

    <div id="system-message-container">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <div class="alert alert-error">
            <h4 class="alert-heading">Warning!</h4>
            <div class="alert-message">
                <h4>This component requires System Plugin to be activated. Please activate the following plugin
                    to proceed further: System - miniOrange OAuth Client</h4>
                <h4>Steps to activate the plugins:</h4>
                <ul>
                    <li>In the top menu, click on Extensions and select Plugins.</li>
                    <li>Search for miniOrange in the search box and press 'Search' to display the plugins.</li>
                    <li>Now enable the System plugin.</li>
                </ul>
            </div>
            </h4>
        </div>
    </div>
<?php } ?>


    <input type="button" onclick="window.open('https://faq.miniorange.com/kb/oauth-openid-connect/')" target="_blank"
           value="FAQ's" style=" float: right;  margin-right: 25px;" class="btn btn-medium btn-success"/>
    <input type="button" id="end_oa_tour_oauth" value="Start-Plugin-tour" onclick="restart_oatour_oauth();"
           style=" float: right; margin-right:10px" class="btn btn-medium btn-danger"/>


    <ul class="nav nav-tabs" id="myTabTabs">

        <li id="configtab" class="<?php echo $oauth_active_tab == 'configuration' ? 'active' : ''; ?>"><a
                    href="#configuration"
                    data-toggle="tab"><?php echo JText::_('COM_MINIORANGE_OAUTH_TAB2_CONFIGURE_OAUTH'); ?></a></li>
        <li class="<?php echo $oauth_active_tab == 'attrrolemapping' ? 'active' : ''; ?>"><a
                    href="#attrrolemapping"
                    data-toggle="tab"><?php echo "Attribute/Role Mapping"; ?></a></li>
        <li class="<?php echo $oauth_active_tab == 'loginlogoutsettings' ? 'active' : ''; ?>"><a
                    href="#loginlogoutsettings"
                    data-toggle="tab"><?php echo "Logout/Login Settings"; ?></a>
        </li>
        <li class="<?php echo $oauth_active_tab == 'useranalytics' ? 'active' : ''; ?>"><a href="#useranalytics"
                                                                                           data-toggle="tab"><?php echo "User Analysis"; ?></a>
        </li>
        <li id="licensetab" class="<?php echo $oauth_active_tab == 'license' ? 'active' : ''; ?>"><a
                    href="#licensing-plans"
                    data-toggle="tab"><?php echo JText::_('com_miniorange_oauth_TAB3_LICENSING_PLANS'); ?></a>
        </li>
        <li id="accounttab" class="<?php echo $oauth_active_tab == 'account' ? 'active' : ''; ?>"><a href="#description"
                                                                                                     data-toggle="tab"><?php echo JText::_('com_miniorange_oauth_TAB1_ACCOUNT_SETUP'); ?></a>
        </li>
        <li id="rfdtab" class="<?php echo $oauth_active_tab == 'rfd' ? 'active' : ''; ?>"><a href="#rfd"
                                                                                             data-toggle="tab"><?php echo JText::_('com_miniorange_oauth_TAB5_RFD'); ?></a>
        </li>
    </ul>

    <script>

        function restart_oatour_oauth() {
            jQuery('.nav-tabs a[href=#configuration]').tab('show');
            oatour.restart();
        }

        var base_url = '<?php echo JURI::root();?>';


        var oatour = new Tour({
            name: "oatour",
            steps: [
                {
                    element: "#configtab",
                    title: "Configuration Tab",
                    content: "Configure your server with client here to perform SSO.",
                    backdrop: 'body',
                    backdropPadding: '6',


                }, {
                    element: "#moJoom-OauthClient-supportButton-SideButton",
                    title: "Contact Us",
                    content: "Feel free to contact us for any queries or issues regarding plugin. We will help you with configuration also",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onNext: function () {
                        jQuery('.nav-tabs a[href=#licensing-plans]').tab('show');
                    }

                }, {
                    element: "#licensetab",
                    title: "Upgrade Plans",
                    content: "You can compare our licensed versions and their features.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('.nav-tabs a[href=#configuration]').tab('show');
                    },
                    onNext: function () {
                        jQuery('.nav-tabs a[href=#help]').tab('show');
                    }

                }, {
                    element: "#faqstab",
                    title: "Help",
                    content: "You could find solutions for most popular queries.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('.nav-tabs a[href=#licensing-plans]').tab('show');
                    },
                    onNext: function () {
                        jQuery('.nav-tabs a[href=#configuration]').tab('show');
                    }

                }, {
                    element: "#oacconf_end_tour",
                    title: "Tab Tour.",
                    content: " You could find the start tour button on each tab which will help you to configure the tab /get the inforamtion from that tab.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('.nav-tabs a[href=#help]').tab('show');
                    }

                }, {
                    element: "#end_oa_tour_oauth",
                    title: "Tab Tour.",
                    content: " By clicking on start-Plugin-tour button you will take to overal plugin tour and explain what each tab does.",
                    backdrop: 'body',
                    backdropPadding: '6',

                }
            ]
        });

        //tabtour.init();
        //tabtour.start();

    </script>
    <div class="tab-content" id="myTabContent">
        <div id="description" class="tab-pane <?php echo $oauth_active_tab == 'account' ? 'active' : ''; ?>">
            <div class="row-fluid">
                <?php
                $customer_details = MoOAuthUtility::getCustomerDetails();
                $registration_status = $customer_details['registration_status'];

                if ($customer_details['login_status']) {  //Show Login Page?>
                    <div class="mo_oauth_table_layout_1">
                    <div class="mo_oauth_table_layout mo_oauth_container">
                        <?php mo_oauth_login_page(); ?>
                    </div>
                    <div id="mojoom-ouath-client-contact-form" class="mo_oauth_table_layout_support_1">
                        <?php requestfordemo(); echo mo_oauth_support(); ?>
                    </div>
                    </div><?php
                } else {  // Show Registration Page
                    if ($registration_status == 'MO_OTP_DELIVERED_SUCCESS' || $registration_status == 'MO_OTP_VALIDATION_FAILURE' || $registration_status == 'MO_OTP_DELIVERED_FAILURE') {
                        ?>
                        <div class="mo_oauth_table_layout_1">
                            <div class="mo_oauth_table_layout mo_oauth_container">
                                <?php mo_otp_show_otp_verification(); ?>
                            </div>
                            <div id="mojoom-ouath-client-contact-form" class="mo_oauth_table_layout_support_1">
                                <?php requestfordemo(); echo mo_oauth_support(); ?>
                            </div>
                        </div>
                        <?php

                    } else if (!MoOAuthUtility::is_customer_registered()) {
                        ?>
                        <div class="mo_oauth_table_layout_1">
                            <div class="mo_oauth_table_layout mo_oauth_container">
                                <?php mo_oauth_registration_page(); ?>
                            </div>
                            <div id="mojoom-ouath-client-contact-form" class="mo_oauth_table_layout_support_1">
                                <?php requestfordemo(); echo mo_oauth_support(); ?>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="mo_oauth_table_layout_1">
                            <div class="mo_oauth_table_layout mo_oauth_container">
                                <?php mo_oauth_account_page(); ?>
                            </div>
                            <div id="mojoom-ouath-client-contact-form" class="mo_oauth_table_layout_support_1">
                                <?php requestfordemo(); echo mo_oauth_support(); ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                <!--<div id="mojoom-ouath-client-contact-form" class="span4" style="margin-left:50px;">

                    <?php /*echo mo_oauth_support(); */ ?>

                </div>-->
            </div>
        </div>
        <div id="configuration" class="tab-pane <?php echo $oauth_active_tab == 'configuration' ? 'active' : ''; ?>">
            <div class="row-fluid">
                <div class="mo_oauth_table_layout_1">
                    <div class="mo_oauth_table_layout mo_oauth_container">
                        <?php selectAppByIcon(); ?>
                    </div>
                    <div id="confsupport" class="mo_oauth_table_layout_support_1">
                        <?php configSidePage() ?>

                    </div>
                    <div id="confsupport" class="mo_oauth_table_layout_support_1">


                        <?php grant_type_settings();  mo_oauth_support() ?>

                    </div>
                </div>

            </div>
        </div>
        <div id="attrrolemapping"
             class="tab-pane <?php echo $oauth_active_tab == 'attrrolemapping' ? 'active' : ''; ?>">
            <div class="row-fluid">
                <div class="mo_oauth_table_layout_1">
                    <div class="mo_oauth_table_layout mo_oauth_large_container">
                        <?php attributerole(); ?>
                    </div>
                    <div id="confsupport" class="mo_oauth_table_layout_support_small">

                        <?php echo mo_oauth_2fa();  mo_oauth_support()?>

                    </div>
                </div>

            </div>
        </div>
        <div id="loginlogoutsettings"
             class="tab-pane <?php echo $oauth_active_tab == 'loginlogoutsettings' ? 'active' : ''; ?>">
            <div class="row-fluid">
                <div class="mo_oauth_table_layout_1">
                    <div class="mo_oauth_table_layout mo_oauth_large_container">
                        <?php loginlogoutsettings(); ?>
                    </div>
                    <div id="confsupport" class="mo_oauth_table_layout_support_small">

                        <?php echo mo_oauth_2fa();  mo_oauth_support() ?>

                    </div>
                </div>

            </div>
        </div>
        <div id="pagerestrictionsettings"
             class="tab-pane <?php echo $oauth_active_tab == 'pagerestrictionsettings' ? 'active' : ''; ?>">
            <div class="row-fluid">
                <div class="mo_oauth_table_layout_1">
                    <div class="mo_oauth_table_layout mo_oauth_large_container">
                        <?php pagerestrictionsettings(); ?>
                    </div>
                    <div id="confsupport" class="mo_oauth_table_layout_support_small">

                        <?php echo mo_oauth_2fa();  mo_oauth_support() ?>

                    </div>
                </div>

            </div>
        </div>
        <div id="useranalytics" class="tab-pane <?php echo $oauth_active_tab == 'useranalytics' ? 'active' : ''; ?>">
            <div class="row-fluid">
                <div class="mo_oauth_table_layout_1">
                    <div class="mo_oauth_table_layout mo_oauth_large_container">
                        <?php useranalytics(); ?>
                    </div>
                    <div id="confsupport" class="mo_oauth_table_layout_support_small">
                        <?php echo mo_oauth_2fa();  mo_oauth_support() ?>

                    </div>
                </div>

            </div>
        </div>
        <div id="rfd" class="tab-pane <?php echo $oauth_active_tab == 'rfd' ? 'active' : ''; ?>">
            <div class="row-fluid">
                <div class="mo_oauth_table_layout_1">
                    <div class="mo_oauth_table_layout mo_oauth_large_container">
                        <?php requestfordemo(); ?>
                    </div>
                    <div id="confsupport" class="mo_oauth_table_layout_support_small">

                        <?php echo mo_oauth_2fa();  mo_oauth_support() ?>
                    </div>
                </div>

            </div>
        </div>
        <div id="licensing-plans" class="tab-pane <?php echo $oauth_active_tab == 'license' ? 'active' : ''; ?>">
            <div class="row-fluid">
                <div class="span12">
                    <!--<div class="span10 offset1">-->
                    <?php
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query->select('email');
                    $query->from($db->quoteName('#__miniorange_oauth_customer'));
                    $query->where($db->quoteName('id') . " = 1");

                    $db->setQuery($query);
                    $result = $db->loadAssoc();

                    $email = $result['email'];
                    $hostName = 'https://www.miniorange.com';
                    $loginUrl = $hostName . '/contact';

                    echo $this->showLicensingPlanDetails();
                    echo mo_oauth_support();
                    ?>
                    <!--</div>-->
                </div>
            </div>
        </div>
        <div id="help" class="tab-pane <?php echo $oauth_active_tab == 'faqs' ? 'active' : ''; ?>">
            <div class="row-fluid">
                <div class="span12">
                    <!--<div class="span4" style="margin-left:50px;">   -->
                    <?php echo mo_oauth_help(); ?>
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>

    <!--
        *End Of Tabs for accountsetup view.
        *Below are the UI for various sections of Account Creation.
    -->

<?php
function mo_oauth_login_page()
{

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('email');
    $query->from($db->quoteName('#__miniorange_oauth_customer'));
    $query->where($db->quoteName('id') . " = 1");

    $db->setQuery($query);
    $admin_email = $db->loadResult();

    ?>

    <form name="f" method="post"
          action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.verifyCustomer'); ?>">

        <h3>Login with miniOrange</h3>
        <p>Please enter your miniOrange account credentials. If you forgot your password then enter your email and click
            on <b>Forgot your passowrd</b> link. If you are not registered with miniOrange then click on <b>Back To
                Registration</b> link. </p><br/>
        <table class="oauth-table">
            <tr>
                <td class="oauth-table-td"><b><font color="#FF0000">*</font>Email:</b></td>
                <td><input class="form-control oauth-textfield" type="email" name="email" id="email"
                           required placeholder="person@example.com"
                           value="<?php echo $admin_email; ?>"/></td>
            </tr>
            <tr>
                <td><b><font color="#FF0000">*</font>Password:</b></td>
                <td><input class="form-control oauth-textfield" required type="password"
                           name="password" placeholder="Enter your miniOrange password"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" class="btn btn-medium btn-success" value="Login"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="#mo_oauth_forgot_password_link">Forgot your password?</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
                            href="#oauth_cancel_link">Back To Registration</a>
                </td>
            </tr>
        </table>
    </form>

    <form id="oauth_forgot_password_form" method="post"
          action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.forgotpassword'); ?>">
        <input type="hidden" name="current_admin_email" id="current_admin_email" value=""/>
    </form>
    <form id="oauth_cancel_form" method="post"
          action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.cancelform'); ?>">
    </form>
    <script>

        jQuery('a[href=#oauth_cancel_link]').click(function () {
            jQuery('#oauth_cancel_form').submit();
        });

        jQuery('a[href=#mo_oauth_forgot_password_link]').click(function () {

            var email = jQuery('#email').val();
            jQuery('#current_admin_email').val(email);
            jQuery('#oauth_forgot_password_form').submit();
        });
    </script>
    <br><br><br><br><br><br>
    <?php
}

/* Show otp verification page*/
function mo_otp_show_otp_verification()
{
    ?>
    <form name="f" method="post" id="otp_form"
          action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.validateOtp'); ?>">
        <h3>Verify Your Email</h3><br/>
        <table class="oauth-table">
            <!-- Enter otp -->
            <tr>
                <td><b><font color="#FF0000">*</font>Enter OTP:</b></td>
                <td colspan="1"><input class="form-control" autofocus="true" type="text" name="otp_token" required
                                       placeholder="Enter OTP"/>
                    &nbsp;&nbsp;<a href="#mo_otp_resend_otp_email">Resend OTP over Email</a></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Validate OTP" class="btn btn-medium btn-success"/>&nbsp;&nbsp;&nbsp;
                    <input type="button" value="Back" id="back_btn" class="btn btn-medium btn-danger"/>
                </td>
            </tr>
        </table>
    </form>

    <form method="post"
          action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.cancelform'); ?>"
          id="mo_otp_cancel_form">
    </form>

    <form name="f" id="resend_otp_form" method="post"
          action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.resendOtp'); ?>">
    </form>

    <br>
    <hr>

    <h3>I did not receive any email with OTP . What should I do ?</h3>
    <form id="phone_verification" method="post"
          action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.phoneVerification'); ?>">
        If you can't see the email from miniOrange in your mails, please check your <b>SPAM Folder</b>. If you don't see
        an email even in SPAM folder, verify your identity with our alternate method.
        <br><br>
        <b>Enter your valid phone number here and verify your identity using one time passcode sent to your
            phone.</b><br><br>

        <input class="form-control" required="true" pattern="[\+]\d{1,3}\d{10}" autofocus="true" type="text"
               name="phone_number" id="phone_number" placeholder="Enter Phone Number with country code"
               style="width:40%;" title="Enter phone number without any space or dashes with country code."/>

        <br><input type="submit" value="Send OTP" class="btn btn-medium btn-success"/>

    </form>
    <hr>
    <p style="color:#b42f2f;">If you face any issues while registration then please contact us at <a href="mailto:joomlasupport@xecurify.com">joomlasupport@xecurify.com</a></p>
    <script>
        jQuery('#back_btn').click(function () {
            jQuery('#mo_otp_cancel_form').submit();
        });

        jQuery('a[href=#mo_otp_resend_otp_email]').click(function () {
            jQuery('#resend_otp_form').submit();
        });


    </script>
    <?php
}

/* Create Customer function */
function mo_oauth_registration_page()
{
    $current_user = JFactory::getUser();
    ?>


    <!--Register with miniOrange-->
    <form name="f" method="post"
          action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.registerCustomer'); ?>">
        <input type="button" id="oacrg_end_tour" value="Start-tour" onclick="restart_tourrg();" style=" float: right;"
               class="btn btn-medium btn-success"/>
        <h3>Register with miniOrange</h3>
        <hr>
        <p class='alert alert-info'>You should register so that in case you need help, we can help you with step by step
            instructions. We support all known Servers -Google,Facebook,Auth0,etc., <b>You will also need a miniOrange
                account to upgrade to the premium version of the plugins</b>. We do not store any information except the
            email that you will use to register with us.<br><br></p><i><p style="color: #b42f2f;">If you face any issues during registraion then you can <a href="https://www.miniorange.com/businessfreetrial" target="_blank"><b>click here</b></a> to quick register your account with miniOrange and use the same credentials to login into the plugin.</p></i>

        <table id="oacemail" class="oauth-table">
            <tr>
                <td class="oauth-table-td"><b><font color="#FF0000">*</font>Email:</b></td>
                <td>
                    <input class="form-control oauth-textfield" type="email" name="email"
                           style="border-radius:4px;resize: vertical;"
                           required placeholder="person@example.com"
                           value="<?php echo $current_user->email; ?>"/></td>
            </tr>

            <tr>
                <td><b>Phone number:</b></td>
                <td><br/><input class="form-control oauth-textfield" type="tel" id="phone"
                                style="border-radius:4px;resize: vertical;"
                                pattern="[\+]\d{11,14}|[\+]\d{1,4}([\s]{0,1})(\d{0}|\d{9,10})" name="phone"
                                title="Phone with country code eg. +1xxxxxxxxxx"
                                placeholder="Phone with country code eg. +1xxxxxxxxxx"
                    /><br/>
                    <i>We will call only if you call for support</i></td>
            </tr>
            <tr>
                <td><br/><b><font color="#FF0000">*</font>Password:</b></td>
                <td><br/><input class="form-control oauth-textfield" required type="password"
                                style="border-radius:4px;resize: vertical;"
                                name="password" placeholder="Choose your password (Min. length 6)"/>
                </td>
            </tr>
            <tr>
                <td><br/><b><font color="#FF0000">*</font>Confirm Password:</b></td>
                <td><br/><input class="form-control oauth-textfield" required type="password"
                                style="border-radius:4px;resize: vertical;"
                                name="confirmPassword" placeholder="Confirm your password"/>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" value="Register"
                           class="btn btn-medium btn-success"/>&nbsp;&nbsp;<a href="#oauth_account_exist" class="btn btn-medium btn-success">Already registered with miniOrange?</a></td>
            </tr>
        </table>


    </form>

    <form name="f" id="oauth_account_already_exist" method="post"
          action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.customerLoginForm'); ?> ">
    </form>
    <script>
        var base_url = '<?php echo JURI::root();?>';


        var tabtour = new Tour({
            name: "tabtour",
            steps: [
                {
                    element: "#configtab",
                    title: "Configuration Tab",
                    content: "Configure your server with client here to perform SSO.",
                    backdrop: 'body',
                    backdropPadding: '6',


                }, {
                    element: "#moJoom-OauthClient-supportButton-SideButton",
                    title: "Contact Us",
                    content: "Feel free to contact us for any queries or issues regarding plugin. We will help you with configuration also",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onNext: function () {
                        jQuery('.nav-tabs a[href=#licensing-plans]').tab('show');
                    }

                }, {
                    element: "#licensetab",
                    title: "License",
                    content: "You can find premium features and can upgrade to our premium plans.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('.nav-tabs a[href=#configuration]').tab('show');
                    },
                    onNext: function () {
                        jQuery('.nav-tabs a[href=#help]').tab('show');
                    }

                }, {
                    element: "#faqstab",
                    title: "Help",
                    content: "You could find solutions for most popular quries.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('.nav-tabs a[href=#licensing-plans]').tab('show');
                    },
                    onNext: function () {
                        jQuery('.nav-tabs a[href=#configuration]').tab('show');
                    }

                }, {
                    element: "#oacconf_end_tour",
                    title: "Tab Tour.",
                    content: " You could find the start tour button on each tab which will help you to configure the tab /get the inforamtion from that tab.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('.nav-tabs a[href=#help]').tab('show');
                    }

                }, {
                    element: "#moJoom-OauthClient-supportButton-SideButton",
                    title: "Contact Us",
                    content: "Feel free to contact us for any queries or issues regarding plugin. We will help you with configuration also",
                    backdrop: 'body',
                    backdropPadding: '6',


                }, {
                    element: "#end_oa_tour_oauth",
                    title: "Tab Tour.",
                    content: " By clicking on start-Plugin-tour button you will take to overal plugin tour and explain what each tab does.",
                    backdrop: 'body',
                    backdropPadding: '6',

                }


            ]
        });

        tabtour.init();
        tabtour.start();


    </script>
    <script>

        function restart_tourrg() {
            tourrg.restart();
        }

        var tourrg = new Tour({
            name: "tour",
            steps: [
                {
                    element: "#oacemail",
                    title: "Register with Us",
                    content: "Please register here to configure and get further help regarding plugin..",
                    backdrop: 'body',
                    backdropPadding: '6'
                },

                {
                    element: "#moJoom-OauthClient-supportButton-SideButton",
                    title: "Contact Us",
                    content: "Feel free to contact us for any queries or issues regarding plugin. We will help you with configuration too.",
                    backdrop: 'title',
                    backdropPadding: '6'
                },
                {
                    element: "#oacrg_end_tour",
                    title: "Tour ends",
                    content: "Click here to restart tour",
                    backdrop: 'body',
                    backdropPadding: '6'
                }

            ]
        });


        //  tourrg.init();
        //tourrg.start();
    </script>


    <script>
        jQuery('a[href=#oauth_account_exist]').click(function () {
            jQuery('#oauth_account_already_exist').submit();
        });
    </script>
    <?php
}

function mo_oauth_account_page()
{

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from($db->quoteName('#__miniorange_oauth_customer'));
    $query->where($db->quoteName('id') . " = 1");

    $db->setQuery($query);
    $result = $db->loadAssoc();
    $email = $result['email'];
    $customer_key = $result['customer_key'];
    $api_key = $result['api_key'];
    $customer_token = $result['customer_token'];

    if (!JPluginHelper::isEnabled('system', 'miniorangeoauth')) {

        ?>
        <div id="system-message-container">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <div class="alert alert-error">
                <h4 class="alert-heading">Warning!</h4>
                <div class="alert-message">
                    <h4>This component requires User and System Plugin to be activated. Please activate the following 2
                        plugins
                        to proceed further.</h4>
                    <li>System -miniOrange OTP Verification</li>
                    </ul>
                    <h4>Steps to activate the plugins.</h4>
                    <ul>
                        <li>In the top menu, click on Extensions and select Plugins.</li>
                        <li>Search for miniOrange in the search box and press 'Search' to display the plugins.</li>
                        <li>Now enable both User and System plugin.</li>
                    </ul>
                </div>
                </h4>
            </div>
        </div>
    <?php }
    ?>

    <p class="mo_oauth_welcome_message">Thank You for registering with miniOrange.<p><br>
    <h3>Your Profile</h3><br/>
    <table class="table table-striped table-hover table-bordered oauth-table">
        <tr>
            <td><b>Username/Email</b></td>
            <td><?php echo $email ?></td>
        </tr>
        <tr>
            <td><b>Customer ID</b></td>
            <td><?php echo $customer_key ?></td>
        </tr>
        <tr>
            <td><b>API Key</b></td>
            <td><?php echo $api_key ?></td>
        </tr>
        <tr>
            <td><b>Token Key</b></td>
            <td><?php echo $customer_token ?></td>
        </tr>
    </table>

    <form id="otp_forgot_password_form" method="post"
          action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.forgotpassword'); ?>">
        <input type="hidden" name="current_admin_email" id="current_admin_email" value=""/>
    </form>

    <?php
}

function getAppJason(){
    return '{
    "azure": {
        "label":"Azure AD", "type":"openidconnect", "image":"azure.png", "scope": "openid", "authorize": "https://login.microsoftonline.com/[tenant-id]/oauth2/authorize", "token": "https://login.microsoftonline.com/[tenant]/oauth2/token", "userinfo": "https://login.windows.net/common/openid/userinfo", "guide":"https://plugins.miniorange.com/configure-azure-ad-with-joomla", "logo_class":"fa fa-windowslive"
    },
    "cognito": {
        "label":"AWS Cognito", "type":"oauth", "image":"cognito.png", "scope": "openid", "authorize": "https://<cognito-app-domain>/oauth2/authorize", "token": "https://<cognito-app-domain>/oauth2/token", "userinfo": "https://<cognito-app-domain>/oauth2/userInfo", "guide":"https://plugins.miniorange.com/configure-aws-cognito-oauthopenid-connect-server-joomla    ", "logo_class":"fa fa-amazon"
    },
    "adfs": {
        "label":"ADFS", "type":"openidconnect", "image":"adfs.png", "scope": "openid", "authorize": "https://{yourADFSDomain}/adfs/oauth2/authorize/", "token": "https://{yourADFSDomain}/adfs/oauth2/token/", "userinfo": "", "guide":"", "logo_class":"fa fa-windowslive"
    },
    "whmcs": {
        "label":"WHMCS", "type":"oauth", "image":"whmcs.png", "scope": "openid profile email", "authorize": "https://{yourWHMCSdomain}/oauth/authorize.php", "token": "https://{yourWHMCSdomain}/oauth/token.php", "userinfo": "https://{yourWHMCSdomain}/oauth/userinfo.php?access_token=", "guide":"", "logo_class":"fa fa-lock"
    },
    "keycloak": {
        "label":"keycloak", "type":"openidconnect", "image":"keycloak.png", "scope": "openid", "authorize": "{your-domain}/auth/realms/{realm}/protocol/openid-connect/auth", "token": "{your-domain}/auth/realms/{realm}/protocol/openid-connect/token", "userinfo": "{your-domain}/auth/realms/{realm}/protocol/openid-connect/userinfo", "guide":"", "logo_class":"fa fa-lock"
    },
    "slack": {
        "label":"Slack", "type":"oauth", "image":"slack.png", "scope": "users.profile:read", "authorize": "https://slack.com/oauth/authorize", "token": "https://slack.com/api/oauth.access", "userinfo": "https://slack.com/api/users.profile.get", "guide":"", "logo_class":"fa fa-slack"
    },
    "discord": {
        "label":"Discord", "type":"oauth", "image":"discord.png", "scope": "identify email", "authorize": "https://discordapp.com/api/oauth2/authorize", "token": "https://discordapp.com/api/oauth2/token", "userinfo": "https://discordapp.com/api/users/@me", "guide":"", "logo_class":"fa fa-lock"
    },
    "invisioncommunity": {
        "label":"Invision Community", "type":"oauth", "image":"invis.png", "scope": "email", "authorize": "https://{invision-community-domain}/oauth/authorize/", "token": "https://{invision-community-domain}/oauth/token/", "userinfo": "https://{invision-community-domain}/oauth/me", "guide":"", "logo_class":"fa fa-lock"
    },
    "bitrix24": {
        "label":"Bitrix24", "type":"oauth", "image":"bitrix24.png", "scope": "user", "authorize": "https://{your-id}.bitrix24.com/oauth/authorize", "token": "https://{your-id}.bitrix24.com/oauth/token", "userinfo": "https://{your-id}.bitrix24.com/rest/user.current.json?auth=", "guide":"https://plugins.miniorange.com/configure-bitrix24-oauthopenid-connect-server-joomla", "logo_class":"fa fa-clock-o"
    },
    "wso2": {
        "label":"WSO2", "type":"oauth", "image":"wso2.png", "scope": "openid", "authorize": "https://<wso2-app-domain>/wso2/oauth2/authorize", "token": "https://<wso2-app-domain>/wso2/oauth2/token", "userinfo": "https://<wso2-app-domain>/wso2/oauth2/userinfo", "guide":"", "logo_class":"fa fa-lock"
    },
    "okta": {
        "label":"Okta", "type":"openidconnect", "image":"okta.png", "scope": "openid", "authorize": "https://{yourOktaDomain}.com/oauth2/default/v1/authorize", "token": "https://{yourOktaDomain}.com/oauth2/default/v1/token", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
    },
    "onelogin": {
        "label":"OneLogin", "type":"openidconnect", "image":"onelogin.png", "scope": "openid", "authorize": "https://<site-url>.onelogin.com/oidc/auth", "token": "https://<site-url>.onelogin.com/oidc/token", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
    },
    "gapps": {
        "label":"Google", "type":"oauth", "image":"google.png", "scope": "email", "authorize": "https://accounts.google.com/o/oauth2/auth", "token": "https://www.googleapis.com/oauth2/v4/token", "userinfo": "https://www.googleapis.com/oauth2/v1/userinfo", "guide":"https://plugins.miniorange.com/configure-google-apps-oauth-server-joomla", "logo_class":"fa fa-google-plus"
    },
    "fbapps": {
        "label":"Facebook", "type":"oauth", "image":"facebook.png", "scope": "public_profile email", "authorize": "https://www.facebook.com/dialog/oauth", "token": "https://graph.facebook.com/v2.8/oauth/access_token", "userinfo": "https://graph.facebook.com/me/?fields=id,name,email,age_range,first_name,gender,last_name,link", "guide":"https://plugins.miniorange.com/configure-facebook-oauth-server-joomla", "logo_class":"fa fa-facebook"
    },
    "gluu": {
        "label":"Gluu Server", "type":"oauth", "image":"gluu.png", "scope": "openid", "authorize": "http://<gluu-server-domain>/oxauth/restv1/authorize", "token": "http://<gluu-server-domain>/oxauth/restv1/token", "userinfo": "http:///<gluu-server-domain>/oxauth/restv1/userinfo", "guide":"", "logo_class":"fa fa-lock"
    },
    "linkedin": {
        "label":"LinkedIn", "type":"oauth", "image":"linkedin.png", "scope": "r_basicprofile", "authorize": "https://www.linkedin.com/oauth/v2/authorization", "token": "https://www.linkedin.com/oauth/v2/accessToken", "userinfo": "https://api.linkedin.com/v2/me", "guide":"https://plugins.miniorange.com/configure-linkedin-oauth-openid-connect-server-joomla-client", "logo_class":"fa fa-linkedin-square"
    },
    "strava": {
        "label":"Strava", "type":"oauth", "image":"strava.png", "scope": "public", "authorize": "https://www.strava.com/oauth/authorize", "token": "https://www.strava.com/oauth/token", "userinfo": "https://www.strava.com/api/v3/athlete", "guide":"", "logo_class":"fa fa-lock"
    },
    "fitbit": {
        "label":"FitBit", "type":"oauth", "image":"fitbit.png", "scope": "profile", "authorize": "https://www.fitbit.com/oauth2/authorize", "token": "https://api.fitbit.com/oauth2/token", "userinfo": "https://www.fitbit.com/1/user", "guide":"https://plugins.miniorange.com/configure-fitbit-oauth-server-joomla", "logo_class":"fa fa-lock"
    },
    "box": {
        "label":"Box", "type":"oauth", "image":"box.png", "scope": "root_readwrite", "authorize": "https://account.box.com/api/oauth2/authorize", "token": "https://api.box.com/oauth2/token", "userinfo": "https://api.box.com/2.0/users/me", "guide":"", "logo_class":"fa fa-lock"
    },
    "github": {
        "label":"GitHub", "type":"oauth", "image":"github.png", "scope": "user repo", "authorize": "https://github.com/login/oauth/authorize", "token": "https://github.com/login/oauth/access_token", "userinfo": "https://api.github.com/user", "guide":"", "logo_class":"fa fa-github"
    },
    "gitlab": {
        "label":"GitLab", "type":"oauth", "image":"gitlab.png", "scope": "read_user", "authorize": "https://gitlab.com/oauth/authorize", "token": "http://gitlab.com/oauth/token", "userinfo": "https://gitlab.com/api/v4/user", "guide":"", "logo_class":"fa fa-gitlab"
    },
    "clever": {
        "label":"Clever", "type":"oauth", "image":"clever.png", "scope": "read:students read:teachers read:user_id", "authorize": "https://clever.com/oauth/authorize", "token": "https://clever.com/oauth/tokens", "userinfo": "https://api.clever.com/v1.1/me", "guide":"https://plugins.miniorange.com/configure-clever-oauthopenid-connect-server-in-joomla", "logo_class":"fa fa-lock"
    },
    "salesforce": {
        "label":"Salesforce", "type":"oauth", "image":"salesforce.png", "scope": "email", "authorize": "https://login.salesforce.com/services/oauth2/authorize", "token": "https://login.salesforce.com/services/oauth2/token", "userinfo": "https://login.salesforce.com/services/oauth2/userinfo", "guide":"", "logo_class":"fa fa-lock"
    },
    "reddit": {
        "label":"Reddit", "type":"oauth", "image":"reddit.png", "scope": "identity", "authorize": "https://www.reddit.com/api/v1/authorize", "token": "https://www.reddit.com/api/v1/access_token", "userinfo": "https://www.reddit.com/api/v1/me", "guide":"https://plugins.miniorange.com/guide-to-configure-reddit-as-oauth-openid-connect-server-in-joomla", "logo_class":"fa fa-reddit"
    },
    "paypal": {
        "label":"PayPal", "type":"openidconnect", "image":"paypal.png", "scope": "openid", "authorize": "https://www.paypal.com/signin/authorize", "token": "https://api.paypal.com/v1/oauth2/token", "userinfo": "", "guide":"", "logo_class":"fa fa-paypal"
    },
    "swiss-rx-login": {
        "label":"Swiss RX Login", "type":"openidconnect", "image":"swiss-rx-login.png", "scope": "anonymous", "authorize": "https://www.swiss-rx-login.ch/oauth/authorize", "token": "https://swiss-rx-login.ch/oauth/token", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
    },
    "yahoo": {
        "label":"Yahoo", "type":"openidconnect", "image":"yahoo.png", "scope": "openid", "authorize": "https://api.login.yahoo.com/oauth2/request_auth", "token": "https://api.login.yahoo.com/oauth2/get_token", "userinfo": "", "guide":"", "logo_class":"fa fa-yahoo"
    },
    "spotify": {
        "label":"Spotify", "type":"oauth", "image":"spotify.png", "scope": "user-read-private user-read-email", "authorize": "https://accounts.spotify.com/authorize", "token": "https://accounts.spotify.com/api/token", "userinfo": "https://api.spotify.com/v1/me", "guide":"", "logo_class":"fa fa-spotify"
    },
    "eveonlinenew": {
        "label":"Eve Online", "type":"oauth", "image":"eveonline.png", "scope": "publicData", "authorize": "https://login.eveonline.com/oauth/authorize", "token": "https://login.eveonline.com/oauth/token", "userinfo": "https://esi.evetech.net/verify", "guide":"", "logo_class":"fa fa-lock"
    },
    "vkontakte": {
        "label":"VKontakte", "type":"oauth", "image":"vk.png", "scope": "openid", "authorize": "https://oauth.vk.com/authorize", "token": "https://oauth.vk.com/access_token", "userinfo": "https://api.vk.com/method/users.get?fields=id,name,email,age_range,first_name,gender,last_name,link&access_token=", "guide":"", "logo_class":"fa fa-vk"
    },
    "pinterest": {
        "label":"Pinterest", "type":"oauth", "image":"pinterest.png", "scope": "read_public", "authorize": "https://api.pinterest.com/oauth/", "token": "https://api.pinterest.com/v1/oauth/token", "userinfo": "https://api.pinterest.com/v1/me/", "guide":"", "logo_class":"fa fa-pinterest"
    },
    "vimeo": {
        "label":"Vimeo", "type":"oauth", "image":"vimeo.png", "scope": "public", "authorize": "https://api.vimeo.com/oauth/authorize", "token": "https://api.vimeo.com/oauth/access_token", "userinfo": "https://api.vimeo.com/me", "guide":"", "logo_class":"fa fa-vimeo"
    },
    "deviantart": {
        "label":"DeviantArt", "type":"oauth", "image":"devart.png", "scope": "browse", "authorize": "https://www.deviantart.com/oauth2/authorize", "token": "https://www.deviantart.com/oauth2/token", "userinfo": "https://www.deviantart.com/api/v1/oauth2/user/profile", "guide":"", "logo_class":"fa fa-deviantart"
    },
    "dailymotion": {
        "label":"Dailymotion", "type":"oauth", "image":"dailymotion.png", "scope": "email", "authorize": "https://www.dailymotion.com/oauth/authorize", "token": "https://api.dailymotion.com/oauth/token", "userinfo": "https://api.dailymotion.com/user/me?fields=id,username,email,first_name,last_name", "guide":"", "logo_class":"fa fa-lock"
    },
    "meetup": {
        "label":"Meetup", "type":"oauth", "image":"meetup.png", "scope": "basic", "authorize": "https://secure.meetup.com/oauth2/authorize", "token": "https://secure.meetup.com/oauth2/access", "userinfo": "https://api.meetup.com/members/self", "guide":"", "logo_class":"fa fa-lock"
    },
    "autodesk": {
        "label":"Autodesk", "type":"oauth", "image":"autodesk.png", "scope": "user:read user-profile:read", "authorize": "https://developer.api.autodesk.com/authentication/v1/authorize", "token": "https://developer.api.autodesk.com/authentication/v1/gettoken", "userinfo": "https://developer.api.autodesk.com/userprofile/v1/users/@me", "guide":"", "logo_class":"fa fa-lock"
    },
    "zendesk": {
        "label":"Zendesk", "type":"oauth", "image":"zendesk.png", "scope": "read write", "authorize": "https://{subdomain}.zendesk.com/oauth/authorizations/new", "token": "https://{subdomain}.zendesk.com/oauth/tokens", "userinfo": "https://{subdomain}.zendesk.com/api/v2/users", "guide":"", "logo_class":"fa fa-lock"
    },
    "laravel": {
        "label":"Laravel", "type":"oauth", "image":"laravel.png", "scope": "", "authorize": "http://your-laravel-site-url/oauth/authorize", "token": "http://your-laravel-site-url/oauth/token", "userinfo": "http://your-laravel-site-url/api/user/get", "guide":"", "logo_class":"fa fa-lock"
    },
    "identityserver": {
        "label":"Identity Server", "type":"oauth", "image":"identityserver.png", "scope": "openid", "authorize": "https://<your-identityserver-domain>/connect/authorize", "token": "https://<your-identityserver-domain>/connect/token", "userinfo": "https://your-domain/connect/introspect", "guide":"", "logo_class":"fa fa-lock"
    },
    "nextcloud": {
        "label":"Nextcloud", "type":"oauth", "image":"nextcloud.png", "scope": "", "authorize": "https://<your-nextcloud-domain>/apps/oauth2/authorize", "token": "https://<your-nextcloud-domain>/apps/oauth2/api/v1/token", "userinfo": "https://<your-nextcloud-domain>/ocs/v2.php/cloud/user?format=json", "guide":"", "logo_class":"fa fa-lock"
    },
    "twitch": {
        "label":"Twitch", "type":"oauth", "image":"twitch.png", "scope": "Analytics:read:extensions", "authorize": "https://id.twitch.tv/oauth2/authorize", "token": "https://id.twitch.tv/oauth2/token", "userinfo": "https://id.twitch.tv/oauth2/userinfo", "guide":"", "logo_class":"fa fa-lock"
    },
    "wildApricot": {
        "label":"Wild Apricot", "type":"oauth", "image":"wildApricot.png", "scope": "auto", "authorize": "https://<your_account_url>/sys/login/OAuthLogin", "token": "https://oauth.wildapricot.org/auth/token", "userinfo": "https://api.wildapricot.org/v2.1/accounts/<account_id>/contacts/me", "guide":"https://plugins.miniorange.com/guide-to-configure-wildapricot-as-an-oauth-openid-connect-server-with-joomla-as-client", "logo_class":"fa fa-lock"
    },
    "connect2id": {
        "label":"Connect2id", "type":"oauth", "image":"connect2id.png", "scope": "openid", "authorize": "https://c2id.com/login", "token": "https://<your-base-server-url>/token", "userinfo": "https://<your-base-server-url>/userinfo", "guide":"", "logo_class":"fa fa-lock"
    },
    "miniorange": {
        "label":"miniOrange", "type":"oauth", "image":"miniorange.png", "scope": "openid", "authorize": "https://<your-site-url>/wp-json/moserver/authorize", "token": "https://<your-site-url>/wp-json/moserver/token", "userinfo": "https://<your-site-url>/wp-json/moserver/resource", "guide":"", "logo_class":"fa fa-lock"
    },
    "orcid": {
        "label":"ORCID", "type":"openidconnect", "image":"orcid.png", "scope": "openid", "authorize": "https://orcid.org/oauth/authorize", "token": "https://orcid.org/oauth/token", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
    },
    "diaspora": {
        "label":"Diaspora", "type":"openidconnect", "image":"diaspora.png", "scope": "openid", "authorize": "https://<your-diaspora-domain>/api/openid_connect/authorizations/new", "token": "https://<your-diaspora-domain>/api/openid_connect/access_tokens", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
    },
    "timezynk": {
        "label":"Timezynk", "type":"oauth", "image":"timezynk.png", "scope": "read:user", "authorize": "https://api.timezynk.com/api/oauth2/v1/auth", "token": "https://api.timezynk.com/api/oauth2/v1/token", "userinfo": "https://api.timezynk.com/api/oauth2/v1/userinfo", "guide":"", "logo_class":"fa fa-lock"
    },
    "other": {
        "label":"Custom OAuth 2.0 App", "type":"oauth", "image":"customapp.png", "scope": "", "authorize": "", "token": "", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
    },
    "openidconnect": {
        "label":"Custom OpenID Connect App", "type":"openidconnect", "image":"customapp.png", "scope": "", "authorize": "", "token": "", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
    }
}';
}
function selectAppByIcon(){

    $appArray = json_decode(getAppJason(),TRUE);

    $app = JFactory::getApplication();
    $get = $app->input->get->getArray();
    $attribute = getAppDetails();
    $isAppConfigured = empty($attribute['client_secret']) || empty($attribute['client_id']) || empty($attribute['custom_app'])?FALSE:TRUE;


    if( isset($get['moAuthAddApp']) && !empty($get['moAuthAddApp']) ){
        configuration($appArray[$get['moAuthAddApp']],$get['moAuthAddApp']);
        return;
    }
    if($isAppConfigured){
        configuration($appArray[$attribute['appname']],$attribute['appname']);
        return;
    }

    $ImagePath=JURI::base().'components/com_miniorange_oauth/assets/images/';
    $imageTableHtml = "<table id='moAuthAppsTable'>";
    $i=1;
    $PreConfiguredApps = array_slice($appArray,0,count($appArray)-2);
    foreach ($PreConfiguredApps as $key => $value) {
        $img=$ImagePath.$value['image'];
        if($i%6==1){
            $imageTableHtml.='<tr>';
        }
        $imageTableHtml=$imageTableHtml."<td moAuthAppSelector='".$value['label']."'><a href='".JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp='.$key)."''><img style='max-height:60px;max-width:60px;' src='".$img."'><br><p>".$value['label']."</p></a></td>";
        if($i%6==0 || $i==count($appArray)){
            $imageTableHtml.='</tr>';
        }
        $i++;


    }
    $imageTableHtml.='</table>';
    ?>
    <div><input type="text" name="appsearch" id="moAuthAppsearchInput" value="" placeholder="Select Application"></div>

    <hr>
    <h6>Pre-Configured Applications<div class="moAuthtooltip">&ensp;<img src="<?php echo  $ImagePath.'icon3.png'; ?>" style="height:18px;">
            <span class="moAuthtooltiptext">By selecting pre-configured applications, the configuration would already be half-done!</span>
        </div></h6>

    <?php

    echo $imageTableHtml;
    ?>
    <hr>
    <h6>Custom Applications<div class="moAuthtooltip">&ensp;<img src="<?php echo  $ImagePath.'icon3.png'; ?>" style="height:18px;">
            <span class="moAuthtooltiptext">Your provider is not in the list? You can select the type of your provider and configure it yourself!</span>
        </div></h6>
    <table id="moAuthCustomAppsTable">
        <tr>


            <td moAuthAppSelector='moCustomOuth2App'><a href="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp=other');?>"><img style='max-height:60px;max-width:60px;' src="<?php echo  $ImagePath.$appArray['other']['image']; ?>"><br><p><?php echo $appArray['other']['label'];?></p></a></td>
            <td moAuthAppSelector='moCustomOpenIdConnectApp'><a href="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp=openidconnect');?>"><img style='max-height:60px;max-width:60px;' src="<?php echo  $ImagePath.$appArray['openidconnect']['image']; ?>"><br><p><?php echo $appArray['openidconnect']['label'];?></p></a></td>
        </tr>
    </table>
    <?php

}
function configSidePage(){
    $attribute = getAppDetails();
    $isAppConfigured = empty($attribute['client_secret']) || empty($attribute['client_id']) || empty($attribute['custom_app'])?FALSE:TRUE;
    $instruction='';
    $appJson=json_decode(getAppJason(),true);
    if($isAppConfigured){
        $instruction = mo_oauth_client_instructions($attribute['appname'],false,$appJson[$attribute['appname']]['guide']);
    }
    else{
        $app = JFactory::getApplication();
        $get = $app->input->get->getArray();
        if( isset($get['moAuthAddApp']) && !empty($get['moAuthAddApp']) ){
            $instruction = mo_oauth_client_instructions($get['moAuthAddApp'],false,$appJson[$get['moAuthAddApp']]['guide']);
        }

        else{
            echo "<h4 style='text-align:center;color:#7373ed !important;'><code style='font-size:16px;color:midnightblue !important;'>Select application to see steps of configurations</code></h4>";
        }
    }

    echo $instruction;
    return 0;

}


function getAppDetails(){
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from($db->quoteName('#__miniorange_oauth_config'));
    $query->where($db->quoteName('id') . " = 1");
    $db->setQuery($query);
    return $db->loadAssoc();
}


function configuration($OauthApp,$appLabel)
{


    $attribute = getAppDetails();

    $mo_oauth_app = $appLabel;
    $custom_app = "";
    $client_id = "";
    $client_secret = "";
    $redirecturi = JURI::root();
    $email_attr = "";
    $first_name_attr = "";
    $isAppConfigured = FALSE;
    $mo_oauth_in_header = "checked=true";
    $mo_oauth_in_body   = "";
    if( isset($attribute['in_header_or_body']) && $attribute['in_header_or_body']=='both' ){
        $mo_oauth_in_header = "checked=true";
        $mo_oauth_in_body   = "checked=true";
    }
    else if(isset($attribute['in_header_or_body']) && $attribute['in_header_or_body']=='inBody'){
        $mo_oauth_in_header = "";
        $mo_oauth_in_body   = "checked=true";
    }


    if (isset($attribute['client_id'])) {
        $mo_oauth_app = empty($attribute['appname'])?$appLabel:$attribute['appname'];
        $custom_app = $attribute['custom_app'];
        $client_id = $attribute['client_id'];
        $client_secret = $attribute['client_secret'];
        $isAppConfigured = empty($client_id) || empty($client_secret) || empty($custom_app)?FALSE:TRUE;
        $app_scope = empty($attribute['app_scope'])?$OauthApp['scope']:$attribute['app_scope'];
        $authorize_endpoint = empty($attribute['authorize_endpoint'])?$OauthApp['authorize']:$attribute['authorize_endpoint'];
        $access_token_endpoint = empty($attribute['access_token_endpoint'])?$OauthApp['token']:$attribute['access_token_endpoint'];
        $user_info_endpoint = empty($attribute['user_info_endpoint'])?$OauthApp['userinfo']:$attribute['user_info_endpoint'];
        $email_attr = $attribute['email_attr'];
        $first_name_attr = $attribute['first_name_attr'];
    }

    ?>

    <script>

        window.addEventListener('DOMContentLoaded', function(){
                selectapp();
            }

        );

        function selectapp() {

            document.getElementById("instructions").innerHTML = "";
            document.getElementById('mo_oauth_authorizeurl').value = "<?php echo $authorize_endpoint; ?>";
            document.getElementById('mo_oauth_accesstokenurl').value = "<?php echo $access_token_endpoint; ?>";
            document.getElementById('mo_oauth_resourceownerdetailsurl').value = "<?php echo $user_info_endpoint ;?>";
            document.getElementById('mo_oauth_scope').value = "<?php echo $app_scope; ?>";
            document.getElementById('loginUrl').value = "<?php echo JURI::root() . '?morequest=oauthredirect&app_name='.$mo_oauth_app;?>";

        }

        function testConfiguration() {
            var appname = "<?php echo $custom_app; ?>";
            var winl = ( screen.width - 600 ) / 2,
                wint = ( screen.height - 800 ) / 2,
                winprops = 'height=' + 600 +
                    ',width=' + 800 +
                    ',top=' + wint +
                    ',left=' + winl +
                    ',scrollbars=1'+
                    ',resizable';
            var myWindow = window.open('<?php echo JURI::root(); ?>' + '?morequest=testattrmappingconfig&app=' + appname, "Test Attribute Configuration", winprops);

        }
    </script>

    <input type="button" id="oacconf_end_tour" value="Start-tour" onclick="restart_tourconf();" style=" float: right;"
           class="btn btn-medium btn-success"/>

    <div id="toggle2" class="panel_toggle">
        <h3><?php echo JText::_('COM_MINIORANGE_OAUTH_APP_CONFIGURATION'); ?></h3>
        <hr>
        <br>
    </div>
    <form id="oauth_config_form" name="oauth_config_form" method="post"
          action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.saveConfig'); ?>">
        <table class="mo_settings_table">

            <tr>
                <td ><strong><font
                                color="#FF0000">*</font>Application</strong></td>
                <td><?php echo $OauthApp['label']."&emsp;".($isAppConfigured==FALSE?"<a align=\"left\"
                       href='index.php?option=com_miniorange_oauth&view=accountsetup'
                       class=\"btn\">Change Application</a>":"<a align=\"left\"
                       href='index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.clearConfig'
                       class=\"btn\">Delete Application</a>&emsp;<code id=\"moAuthBlinkingWarning\" style=\"color:#d92e2e;background-color:#fff;\">You can only ADD one APP in free version.</code>");?></td>

            </tr>
            <tr>
                <input type="hidden" name="mo_oauth_app_name" value="<?php echo $mo_oauth_app; ?>">
                <td style="width:20%;"><strong>Login URL:</strong></td>
                <td style="width:100%;"><input class="selected-text" id="loginUrl" type="text" style="width:80%;"
                                               readonly="true"
                                               value='<?php echo JURI::root() . '?morequest=oauthredirect&app_name=' . $mo_oauth_app; ?>'>
                    <i class="fa fa-fw fa-pull-right fa-lg fa-copy mo_copy copytooltip" ;
                       onclick="copyToClipboard('#loginUrl');" style="color:red" ;><span
                                class="copytooltiptext">Copied!</span> </i>

                </td>
            </tr>
            <tr>
                <td><strong><?php echo JText::_('COM_MINIORANGE_OAUTH_CALLBACK_URL'); ?></strong></td>
                <td style="width:100%;"><input class="mo_table_textbox" id="callbackurl" type="text" style="width: 80%;"
                                               readonly="true" value='<?php echo $redirecturi; ?>'>
                    <i class="fa fa-fw fa-pull-right fa-lg fa-copy mo_copy copytooltip" ;
                       onclick="copyToClipboard('#callbackurl');" style="color:red" ;><span class="copytooltiptext">Copied!</span>
                    </i>

                </td>
            </tr>


            <tr id="mo_oauth_custom_app_name_div">
                <td><strong><font
                                color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_CUSTOM_APP_NAME'); ?>
                    </strong></td>
                <td><input class="mo_table_textbox" type="text" style="width: 80%;" id="mo_oauth_custom_app_name"
                           name="mo_oauth_custom_app_name" value='<?php echo $custom_app; ?>' required></td>
            </tr>
            <tr>
                <td><strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_CLIENT_ID'); ?>
                    </strong></td>
                <td><input class="mo_table_textbox" required="" type="text" style="width: 80%;"
                           name="mo_oauth_client_id" id="mo_oauth_client_id" value='<?php echo $client_id; ?>'></td>
            </tr>
            <tr>
                <td><strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_CLIENT_SECRET'); ?>
                    </strong></td>
                <td><input class="mo_table_textbox" type="text" style="width: 80%;"
                           id="mo_oauth_client_secret" name="mo_oauth_client_secret"
                           value='<?php echo $client_secret; ?>'></td>
            </tr>
            <tr>
                <td><strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_APP_SCOPE'); ?>
                    </strong></td>
                <td><input class="mo_table_textbox" required type="text" style="width: 80%;" id="mo_oauth_scope"
                           name="mo_oauth_scope" value='<?php echo $app_scope; ?>'></td>
            </tr>

            <tr id="mo_oauth_authorizeurl_div">
                <td><strong><font
                                color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_AUTHORIZE_ENDPOINT'); ?>
                    </strong></td>
                <td><input class="mo_table_textbox" type="text" style="width: 80%;"
                           id="mo_oauth_authorizeurl" name="mo_oauth_authorizeurl"
                           value='<?php echo $authorize_endpoint; ?>' required>
                    <i class="fa fa-fw fa-pull-right fa-lg fa-copy mo_copy copytooltip" ;
                       onclick="copyToClipboard('#mo_oauth_authorizeurl');" style="color:red" ;><span
                                class="copytooltiptext">Copied!</span> </i>
                </td>
            </tr>
            <tr id="mo_oauth_accesstokenurl_div">
                <td><strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_TOKEN_ENDPOINT'); ?>
                    </strong></td>
                <td><input class="mo_table_textbox" type="text" style="width: 80%;"
                           id="mo_oauth_accesstokenurl" name="mo_oauth_accesstokenurl"
                           value='<?php echo $access_token_endpoint; ?>' required>
                    <i class="fa fa-fw fa-pull-right fa-lg fa-copy mo_copy copytooltip" ;
                       onclick="copyToClipboard('#mo_oauth_accesstokenurl');" style="color:red" ;><span
                                class="copytooltiptext">Copied!</span> </i>
                </td>
            </tr>


            <?php if(!isset($OauthApp['type']) || $OauthApp['type']=='oauth'){
                ?>
                <tr id="mo_oauth_resourceownerdetailsurl_div">
                    <td><strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_INFO_ENDPOINT'); ?>
                        </strong></td>
                    <td><input class="mo_table_textbox" type="text" style="width: 80%;"
                               id="mo_oauth_resourceownerdetailsurl" name="mo_oauth_resourceownerdetailsurl"
                               value='<?php echo $user_info_endpoint; ?>' required>
                        <i class="fa fa-fw fa-pull-right fa-lg fa-copy mo_copy copytooltip" ;
                           onclick="copyToClipboard('#mo_oauth_resourceownerdetailsurl');" style="color:red" ;><span
                                    class="copytooltiptext">Copied!</span> </i>


                    </td>
                </tr>

                <?php
            }
            ?>
            <tr id="mo_oauth_set_credential_in_body_or_header">
                <td></td>
                <td>
                    <div style="padding:5px;"></div>
                    <input type="checkbox" style='vertical-align: -2px;' name="mo_oauth_in_header" value="1" <?php echo " ".$mo_oauth_in_header; ?>>&nbsp;Set client credentials in Header
                    <span style="padding:0px 0px 0px 8px;"></span>
                    <input type="checkbox" style='vertical-align: -2px;' class="mo_table_textbox" name="mo_oauth_body" value="1" <?php echo " ".$mo_oauth_in_body; ?> >&nbsp; Set client credentials in Body<div style="padding:5px;">
                    </div>
                </td>
            </tr>
            <tr style="height: 30px !important; background-color: #FFFFFF;">
                <td colspan="3"></td>
            </tr>
            <tr>
                <td>
                    <input type="hidden" name="moOauthAppName" value="<?php echo $appLabel; ?>">
                    <input type="submit" name="send_query" id="send_query"
                           value='<?php echo JText::_('COM_MINIORANGE_OAUTH_SAVE_SETTINGS_BUTTON'); ?>'
                           style="margin-bottom:3%;" class="btn btn-medium btn-success"/></td>
                <td><input type="button" id="test_config_button"
                           title='<?php echo JText::_('COM_MINIORANGE_OAUTH_TEST_CONFIGURATION_MESSAGE'); ?>'
                           style=" margin-left:30px; margin-right:65px;" class="btn btn-primary"
                           value='<?php echo JText::_('COM_MINIORANGE_OAUTH_TEST_CONFIGURATION_BUTTON'); ?>'
                           onclick="testConfiguration()">
                    <a align="left"
                       href='index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.clearConfig'
                       id="clear_config_button"
                       class="btn btn-danger"><?php echo JText::_('COM_MINIORANGE_OAUTH_CLEAR_SETTINGS_BUTTON'); ?></td>
            </tr>
        </table>
    </form>

    <hr>
    <div id="mo_oauth_attr_mapping_div">
        <form id="oauth_mapping_form" name="oauth_config_form" method="post"
              action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.saveMapping'); ?>">
            <div id="toggle2" class="panel_toggle">
                <h3><?php echo JText::_('COM_MINIORANGE_OAUTH_ATTRIBUTE_MAPPING'); ?></h3>
                <h6><?php echo JText::_('COM_MINIORANGE_OAUTH_ATTRIBUTE_MAPPING_MESSAGE'); ?></h6><br>
            </div>
            <table class="mo_mapping_table">
                <tr id="mo_oauth_email_attr_div">
                    <td><strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_EMAIL_ATTR'); ?>
                        </strong></td>
                    <td><input class="mo_table_textbox" required="" type="text" style="width:150%;"
                               id="mo_oauth_email_attr" name="mo_oauth_email_attr" value='<?php echo $email_attr; ?>'>
                    </td>
                </tr>
                <tr id="mo_oauth_first_name_attr_div">
                    <td><strong><font
                                    color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_FIRST_NAME_ATTR'); ?>
                        </strong></td>
                    <td><input class="mo_table_textbox" required="" type="text" style="width: 150%;"
                               id="mo_oauth_first_name_attr" name="mo_oauth_first_name_attr"
                               value='<?php echo $first_name_attr; ?>'></td>
                </tr>
                <tr style="height: 30px !important; background-color: #FFFFFF;">
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td><input type="submit" name="send_query" id="send_query"
                               value='<?php echo JText::_('COM_MINIORANGE_OAUTH_SAVE_MAPPING_BUTTON'); ?>'
                               style="margin-bottom:3%;" class="btn btn-medium btn-success"/>
                </tr>
            </table>
        </form>
        <hr>
    </div>
    <div id="instructions">
    </div>

    <style>
        .selected-text, .selected-text > * {
            /*background: #red;*/
            color: #ffffff;
        }
    </style>

    <script>
        function copyToClipboard(element) {
            var temp = jQuery("<input>");
            jQuery("body").append(temp);
            temp.val(jQuery(element).val()).select();
            document.execCommand("copy");
            temp.remove();
        }

        function restart_tourconf() {
            tourconf.restart();
        }

        var tourconf = new Tour({
            name: "tour",
            steps: [
                {
                    element: "#mo_oauth_app",
                    title: "Select Application",
                    content: "Please select your server to configure. Select Other if your server not listed.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#callbackurl",
                    title: "Redirect / Callback URL",
                    content: "Use this URL to configure your server.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#mo_oauth_custom_app_name",
                    title: "Custom App Name",
                    content: "Give a name to identify your server.",
                    backdrop: 'body',
                    backdropPadding: '6'
                }, {
                    element: "#mo_oauth_client_id",
                    title: "Client ID",
                    content: "You can get the Client ID from your server application.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#mo_oauth_client_secret",
                    title: "Client Secret",
                    content: "You can get the Client Secret from your server application.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#mo_oauth_scope",
                    title: "Scope",
                    content: "Get the Scope from Server to get particular information",
                    backdrop: 'body',
                    backdropPadding: '6'
                }, {
                    element: "#mo_oauth_authorizeurl",
                    title: "Authorize Endpoint ",
                    content: "You can get the Authorize Endpoint from your server application.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#mo_oauth_accesstokenurl",
                    title: "Access Token Endpoint",
                    content: "You can get the Access Token Endpoint from your server application.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#mo_oauth_resourceownerdetailsurl",
                    title: "Get User Info Endpoint",
                    content: "You can get the User Info Endpoint from your server application.",
                    backdrop: 'body',
                    backdropPadding: '6'
                }, {
                    element: "#test_config_button",
                    title: "Test Configuration",
                    content: "Click here to know the attributes sent by the server to configure in attribute mapping.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#mo_oauth_email_attr",
                    title: "Email Attribute ",
                    content: "Please enter attribute name which holds email address here. You can find this in test Configuration",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#mo_oauth_first_name_attr",
                    title: "Username Attribute",
                    content: "Enter the Username Attribute which holds name. You can find this in test configuration.",
                    backdrop: 'body',
                    backdropPadding: '6'
                }, {
                    element: "#confsupport",
                    title: "Support",
                    content: "Unable to Configure/ any issue related to plugin feel free to reach us we will help you.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#oacconf_end_tour",
                    title: "Tour ends",
                    content: "Click here to restart tour",
                    backdrop: 'body',
                    backdropPadding: '6'
                },

            ]
        });

    </script>
    <?php
}

function attributerole()
{
    ?>

    <script>
        jQuery(document).ready(function () {

            jQuery('.premium').click(function () {
                jQuery('.nav-tabs a[href=#licensing-plans]').tab('show');
            });
        });
    </script>
    <div id="toggle2" class="panel_toggle">
        <hr>

        <h3><?php echo JText::_('COM_MINIORANGE_OAUTH_ATTRIBUTE_MAPPING1'); ?> <font size="2px" >[Available in the <b><a href='#' class='premium'><b>Standard</b></a>, <a href='#' class='premium'><b>Premium</b></a>,
                    <a href='#' class='premium'><b>Enterprise</b></a></b> version]</font></h3>



        <h6><?php echo JText::_('COM_MINIORANGE_OAUTH_ATTRIBUTE_MAPPING_MESSAGE1'); ?></h6><br>
    </div>

    <table class="mo_settings_table">
        <tr id="mo_oauth_uname_attr_div">
            <td style="width: 20%;"><strong><font color="#FF0000">*</font><?php echo "Username: "; ?>
                </strong></td>
            <td style="width: 50%;"><input class="mo_table_textbox" type="text" style="width: 62%;"
                                           id="mo_oauth_uname_attr"
                                           name="mo_oauth_uname_attr" value='' disabled required></td>
        </tr>
        <tr id="mo_oauth_email_attr_div">
            <td><strong><font color="#FF0000">*</font><?php echo "Email"; ?>
                </strong></td>
            <td><input class="mo_table_textbox" type="text" style="width: 62%;" id="mo_oauth_email_attr"
                       name="mo_oauth_email_attr" value='' disabled required></td>
        </tr>
        <tr id="mo_oauth_dname_attr_div">
            <td><strong><?php echo "Display Name:"; ?></strong></td>
            <td><input class="mo_table_textbox" type="text" style="width: 62%; border-bottom: 10px;"
                       id="mo_oauth_dname_attr"
                       name="mo_oauth_dname_attr" value='' disabled></td>
        </tr>
        <tr style="height: 30px !important;">
            <td colspan="3"></td>
        </tr>
        <tr>
            <td><input type="submit" name="send_query" id="send_query"
                       value='<?php echo "Save Attribute Mapping"; ?>' disabled
                       style="margin-bottom:3%;" class="btn btn-medium btn-success"/></td>
        </tr>
    </table>
    <h4> Add Joomla's User Profile Attributes (Optional) <a href='#' class='premium'><b>[Premium</b></a>, <a href='#' class='premium'><b>Enterprise]</b></a>
        <input type="button" class="btn btn-primary moOauthAttributeMappingButtons" disabled="true"  value="+" />
        <input type="button" class="btn btn-danger" disabled="true" value="-" />
    </h4>
    <p class="alert alert-info" style="color: #151515;">NOTE: During registration or login of the user, the value corresponding to 'Value from OAuth server' will be updated for the User Profile Attribute field in the User Profile table. Customized attribute mapping options shown above are configurable in the <a href='#' class='premium'><b>Premium </a> </b>and <a href='#' class='premium'> <b>Enterprise</b></a> versions of plugin.</p>

    <div class="container" style="margin-left:2%;">
        <div class="row">
            <div class="moOauthAtrributeMappingHeadings" style="margin-left: 33px;">
                <b>User Profile Attribute</b>
            </div>
            <div style="width:20%;display:inline-block; margin-left: 15px;"><b>OAuth Server Attribute</b></div>
        </div>
        <div class="moOauthAtrributeMappingHeadings">
            <input type="text" disabled="disabled"/>
        </div>
        <div class="moOauthAtrributeMappingHeadings">
            <input type="text" disabled="disabled"/>
        </div>
    </div><br><hr><br>

    <h4> Add Field Attributes (Optional) <a href='#' class='premium'><b>[Enterprise]</b></a>
        <input type="button" class="btn btn-primary moOauthAttributeMappingButtons"  value="+" disabled/>
        <input type="button" class="btn btn-danger" value="-" disabled/>
    </h4>
    <p class="alert alert-info">NOTE: During registration or login of the user, the value corresponding to User Profile Attributes Mapping Value from OAuth Server will be updated for the User Field Attributes field in User field table.</p>

    <div class="container" style="margin-left:2%;">
        <div class="row">
            <div class="moOauthAtrributeMappingHeadings" style="margin-left: 2%;"><b>User Field Attribute</b></div>
            <div style="width:20%;display:inline-block;"><b>OAuth Server Attribute</b></div>
        </div>
        <div class="row userAttr" style="padding-bottom:1%;">
            <div class="moOauthAtrributeMappingHeadings" style="margin-left: 2%;">
                <input type="text" disabled/>
            </div>
            <div class="moOauthAtrributeMappingHeadings">
                <input type="text" disabled/>
            </div>
        </div>
    </div>
    <br><br>
    <hr>
    <div id="toggle2" class="panel_toggle">

        <h3><?php echo "Role Mapping "; ?>&nbsp;&nbsp;<font size="2px" >[Available in <a href='#' class='premium'><b>Standard, Premium, Enterprise</b></a> version]</font></h3>
        <h6><?php echo "(Configure the 'Group Attribute Names' field in Attribute Mapping section above in order to configure Advanced Role Mapping.)"; ?></h6>
        <br>
    </div>

    <section style="display: table">
        <div style="display: table-row" id="mo_oauth_enable_role_div">
            <div style="display: table-cell"><input type="checkbox" name="enable_role_mapping" id="enable_role_mapping"
                                                    value="1" disabled
                                                    style="margin-right:25px;"/><strong><?php echo "Enable Role Mapping"; ?></strong>
            </div>
        </div>
        <div style="display: table-row; height: 10px !important;">
            <div style="display: table-cell"></div>
        </div>
        <div style="display: table-row">
            <div style="display: table-cell">Select default group for the new users:&nbsp;&nbsp;</div>
            <div style="display: table-cell"><?php
                $db = JFactory::getDbo();
                $db->setQuery($db->getQuery(true)
                    ->select('*')
                    ->from("#__usergroups")
                );
                $groups = $db->loadRowList();

                echo '<select name="mapping_value_default" id="default_group_mapping"  disabled>';

                foreach ($groups as $group) {
                    if ($group[4] != 'Super Users')
                        echo '<option selected="selected" value = "' . $group[0] . '">' . $group[4] . '</option>';
                } ?>
                </select>
            </div>
        </div>
        <div style="display: table-row" id="mo_oauth_gname_attr_div">
            <div style="display: table-cell"><strong><?php echo "Group Attribute Names:"; ?></strong></div>
            <div style="display: table-cell"><input class="mo_table_textbox" type="text" style="width: 350px;"
                                                    id="mo_oauth_gname_attr"
                                                    name="mo_oauth_gname_attr" value='' disabled></div>
        </div>
        <div style="display: table-row; height: 30px !important; visibility: hidden;">
            <div style="display: table-cell; width: 43%;">
            </div>
        </div>
    </section>
    <section style="background-color: lightgray; padding: 10px; ">
        <p style="text-align: center; margin-top: 10px;">[Available in the <b><a href='#'
                                                                                 class='premium'><b>Premium</b></a>,
                <a href='#' class='premium'><b>Enterprise</b></a></b> version]
        </p>
        <section style="display: table; width: 97.8%;">
            <div style="display: table-row; height: 10px !important; visibility: hidden;">
                <div style="display: table-cell;">
                </div>
            </div>
            <div style="display: table-row">
                <div style="display: table-cell; width:27%"><b>Group Name in Joomla</b></div>
                <div style="display: table-cell;"><b>Group/Role Name in the Configured App</b></div>
            </div><?php
            $user_role = array();

            if (empty($role_mapping_key_value)) {
                foreach ($groups as $group) {
                    if ($group[4] != 'Super Users') {
                        echo '<div style="display: table-row"><div style="display: table-cell"><b>' . $group[4] . '</b></div><div style="display: table-cell"><input  disabled type="text" id="oauth_group_attr_values' . $group[0] . '" name="oauth_group_attr_values' . $group[0] . '" value= "" placeholder="Semi-colon(;) separated Group/Role value for ' . $group[4] . '" style="width: 350px;"' . ' /></div></div><div><div style="display: table-cell"></div><div style="display: table-cell"></div></div>';
                    }
                }

            } else {
                foreach ($groups as $group) {
                    if ($group[4] != 'Super Users') {
                        $role_value = array_key_exists($group[0], $role_mapping_key_value) ? $role_mapping_key_value[$group[0]] : "";
                        echo '<div style="display: table-row"><div style="display: table-cell"><b>' . $group[4] . '</b></div><div style="display: table-cell"><input   disabled type="text" id="oauth_group_attr_values' . $group[0] . '" name="oauth_group_attr_values' . $group[0] . '" value= "' . $role_value . '" placeholder="Semi-colon(;) separated Group/Role value for ' . $group[4] . '" style="width: 350px;"' . ' /></div></div><div><div style="display: table-cell"></div><div style="display: table-cell"></div></div>';
                    }
                }
            } ?>
            <div style="display: table-row; height: 30px !important;">
                <div style="display: table-cell"></div>
            </div>
            <div style="display: table-row">
                <div style="display: table-cell"><input type="submit" name="send_query" id="send_query"
                                                        value='<?php echo "Save role Mapping"; ?>' disabled
                                                        style="margin-bottom:3%;" class="btn btn-medium btn-success"/>
                </div>
            </div>
        </section>
    </section>
    <?php
}
function grant_type_settings() {
    ?>

    <div class="mo_table_layout" id="mo_grant_settings" style="position: relative;">
        <table class="mo_settings_table">
            <tr>
                <td style="padding: 15px 0px 5px;"><h3 style="display: inline;">Grant Settings&emsp;<code><small><a href="index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=license"  rel="noopener noreferrer">[PREMIUM, ENTERPRISE]</a></small></code></h3><!--<span style="float: right;">[ <a href="https://developers.miniorange.com/docs/oauth/wordpress/client/multiple-grant-support" target="_blank">Click here</a> to know how this is useful. ]</span> --></td>
                <!-- <td align="right"><a href="#" target="_blank" id='mo_oauth_grant_guide' style="display:inline;background-color:#0085ba;color:#fff;padding:4px 8px;border-radius:4px;">What is this?</a></td> -->
            </tr>
        </table>
        <div class="grant_types">
            <h4>Select Grant Type:</h4>
            <input checked disabled type="checkbox">&emsp;<strong>Authorization Code Grant</strong>&emsp;<code><small>[DEFAULT]</small></code>
            <blockquote>
                The Authorization Code grant type is used by web and mobile apps.<br/>
                It requires the client to exchange authorization code with access token from the server.
                <br/><small>(If you have doubt on which settings to use, you can leave this checked and disable all others.)</small>
            </blockquote>
            <input disabled type="checkbox">&emsp;<strong>Implicit Grant</strong>
            <blockquote>
                The Implicit grant type is a simplified version of the Authorization Code Grant flow.<br/>
                OAuth providers directly offer access token when using this grant type.
            </blockquote>
            <input disabled type="checkbox">&emsp;<strong>Password Grant</strong>
            <blockquote>
                Password grant is used by application to exchange user's credentials for access token.<br/>
                This, generally, should be used by internal applications.
            </blockquote>
            <input disabled type="checkbox">&emsp;<strong>Refresh Token Grant</strong>
            <blockquote>
                The Refresh Token grant type is used by clients.<br/>
                This can help in keeping user session persistent.
            </blockquote>
        </div>
        <hr>
        <div style="padding:15px 0px 15px;"><h3 style="display: inline;">JWT Validation&emsp;<code><small><a href="index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=license"  rel="noopener noreferrer">[PREMIUM, ENTERPRISE]</a></small></code></h3><span style="float: right;">
        <!--<a href="https://developers.miniorange.com/docs/oauth/wordpress/client/json-web-token-support" target="_blank">Click here</a> to know how this is useful. ]</span> --></div>
        <div>
            <table class="mo_settings_table">
                <tr>
                    <td><strong>Enable JWT Verification:</strong></td>
                    <td><input type="checkbox" value="" disabled/></td>
                </tr>
                <tr>
                    <td><strong>JWT Signing Algorithm:</strong></td>
                    <td><select disabled>
                            <option>HSA</option>
                            <option>RSA</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <br><br>
        <div class="notes">
            <hr />
            Grant Type Settings and JWT Validation are configurable in <a href="index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=license" rel="noopener noreferrer">premium and enterprise</a> versions of the plugin.
        </div>
    </div>

    <?php
}

function loginlogoutsettings()
{

    ?>

    <div id="toggle2" class="panel_toggle">
        <hr>
        <h3><?php echo "Login/Logout Settings"; ?></h3><br>
    </div>

    <table class="mo_login_logout_settings_table" style="border-collapse: separate;">
        <tr id="mo_oauth_auto_redirect_div">
            <td colspan="3"><input type="checkbox" name="mo_oauth_auto_redirect" id="mo_oauth_auto_redirect"
                                   value="1" disabled
                                   style="margin-right:25px;"/><strong><?php echo "Restrict site to logged in users (Users will be auto redirected to OAuth login if not logged in) "; ?></strong>
                [<a href='#' class='premium'><b>Premium</b></a>,
                <a href='#' class='premium'><b>Enterprise</b></a>]</td>
            <td></td>
        </tr>
        <tr style="height: 20px !important; background-color: #FFFFFF;">
        </tr>
        <tr id="mo_oauth_dont_auto_register_div">
            <td colspan="3"><input type="checkbox" name="mo_oauth_dont_auto_register" id="mo_oauth_dont_auto_register"
                                   value="1" disabled
                                   style="margin-right:25px;"/><strong><?php echo "Do Not Auto Create Users (If checked, only existing users will be able to log-in) "; ?></strong>
                [<a href='#' class='premium'><b>Premium</b></a>,
                <a href='#' class='premium'><b>Enterprise</b></a>]</td>
            <td></td>
        </tr>
        <tr style="height: 20px !important; background-color: #FFFFFF;">
        </tr>
        <tr id="mo_oauth_enable_page_restriction_div">
            <td colspan="3"><input type="checkbox" name="mo_oauth_enable_page_restriction"
                                   id="mo_oauth_enable_page_restriction"
                                   value="1" Disabled
                                   style="margin-right:25px;"/><strong><?php echo "Enable Page Restriction"; ?></strong>
                [<a href='#' class='premium'><b>Enterprise</b></a>]</td>
        </tr>
        <tr style="height: 20px !important; background-color: #FFFFFF;">
        </tr>
        <tr id="mo_oauth_enable_analytics_div">
            <td colspan="3"><input type="checkbox" name="mo_oauth_enable_analytics" id="mo_oauth_enable_analytics"
                                   value="1" Disabled
                                   style="margin-right:25px;"/><strong><?php echo "Enable User Analytics"; ?></strong>
                [<a href='#' class='premium'><b>Enterprise</b></a>]</td>
        </tr>
        <tr style="height: 15px !important; background-color: #FFFFFF;">
            <td colspan="3"></td>
        </tr>
        <tr>
            <td style="width: 20%;"><strong><?php echo "Restricted Domains:"; ?></strong></td>
            <td style="width: 100%;"><input class="mo_table_textbox" type="text" style="width: 60%;"
                                            id="mo_oauth_restricted_domains" name="mo_oauth_restricted_domains"
                                            value='' disabled placeholder="domain1.com,domain2.com,...."><p style="display: inline-block; padding-left: 5px">[<a href='#' class='premium'><b>Premium</b></a>,
                    <a href='#' class='premium'><b>Enterprise</b></a>]</p></td>
        </tr>
        <tr>
            <td><strong><?php echo "Allowed Domains:"; ?></strong></td>
            <td style="width: 100%;"><input class="mo_table_textbox" type="text" style="width: 60%;" id="mo_oauth_allowed_domains"
                                            name="mo_oauth_allowed_domains" value='' disabled
                                            placeholder="domain1.com,domain2.com,...."><p style="display: inline-block; padding-left: 5px">[<a href='#' class='premium'><b>Premium</b></a>,
                    <a href='#' class='premium'><b>Enterprise</b></a>]</p></td>
        </tr>
        <tr>
            <td><strong><?php echo "Login Redirect URL:"; ?></strong></td>
            <td style="width: 100%;"><input class="mo_table_textbox" type="text" style="width: 60%;" id="mo_oauth_allowed_domains"
                                            name="mo_oauth_allowed_domains" value='' disabled
                                            placeholder="domain1.com,domain2.com,...."><p style="display: inline-block; padding-left: 5px">[<a href='#' class='premium'><b>Standard</b></a>, <a href='#' class='premium'><b>Premium</b></a>,
                    <a href='#' class='premium'><b>Enterprise</b></a>]</p></td>
        </tr>
        <tr>
            <td><strong><?php echo "Logout Redirect URL:"; ?></strong></td>
            <td style="width: 100%;"><input class="mo_table_textbox" type="text" style="width: 60%;" id="mo_oauth_allowed_domains"
                                            name="mo_oauth_allowed_domains" value='' disabled
                                            placeholder="domain1.com,domain2.com,...."><p style="display: inline-block; padding-left: 5px">[<a href='#' class='premium'><b>Standard</b></a>, <a href='#' class='premium'><b>Premium</b></a>,
                    <a href='#' class='premium'><b>Enterprise</b></a>]</p></td>
        </tr>
        <tr style="height: 20px !important; background-color: #FFFFFF;">
        </tr>
        <tr>
            <td><input type="submit" disabled name="send_query" id="send_query"
                       value='<?php echo JText::_('COM_MINIORANGE_OAUTH_SAVE_SETTINGS_BUTTON'); ?>'
                       style="margin-bottom:3%;" class="btn btn-medium btn-success"/>
        </tr>
    </table>
    <hr>
    <?php


}

function pagerestrictionsettings()
{

    ?>
    <p style="text-align: center;">[Available in <b>
            <a href='#' class='premium'><b>Enterprise</b></a></b> version]
    <hr>
    </p>

    <div><b>Enter the list of semicolon separated relative URLs of your pages in the textarea.</b></div>
    For example: <?php echo "2-uncategorised/1-my-page1;2-uncategorised/2-my-page2" ?><br/><br/>
    Base Url: <b><?php echo JURI::root(); ?></b> <input type="submit" disabled
                                                        style="float:right;margin-right:19%;"
                                                        class="btn btn-medium btn-success"
                                                        value="Save"/><br/><br/>
    <textarea rows="10" id="mo_oauth_page_restricted_urls" name="mo_oauth_page_restricted_urls"
              placeholder="Enter the semicolon(;) separated relative urls."
              style="width: 80%;" disabled><?php echo ""; ?></textarea>
    <?php


}

function useranalytics()
{

    ?>
    <p style="text-align: center;">[Available in <b>
            <a href='#' class='premium'><b>Enterprise</b></a></b> version]
    <hr>
    </p>
    <h3><?php echo "User Transaction Reports    "; ?></h3>
    <input disabled type="button" class="btn btn-danger btn-medium" id="cleartext"
           value="<?php echo "Clear Reports"; ?>"
           style="margin-bottom:3%; float:right"
    />
    <input disabled type="button" class="btn btn-primary btn-medium" id="refreshtext" value="Refresh"
           style="margin-bottom:3%; float:right"
    />
    <table class="table table-striped table-hover table-bordered">
        <thead>
        <tr>
            <th width="25%">Username</th>
            <th width="25%">Application</th>
            <th width="25%">Status</th>
            <th width="25%">Login Timestamp</th>
        </tr>
        </thead>
    </table>
    <?php


}


function mo_oauth_client_instructions($appname, $newdiv,$guideLink="https://plugins.miniorange.com/guide-to-enable-joomla-oauth-client")
{
    $guideOrGuides="Detailed Guide";
    $guideLink = empty($guideLink)?"https://plugins.miniorange.com/guide-to-enable-joomla-oauth-client":$guideLink;
    if($guideLink=="https://plugins.miniorange.com/guide-to-enable-joomla-oauth-client")
        $guideOrGuides.='s';
    $guide='<a href="'.$guideLink.'" class="btn btn-info" style="float:right;" target="_blank" rel="noopener noreferrer">'.$guideOrGuides.'</a>';
    $instructionsArray = array(
        'azure'   =>'<strong>Instructions to configure Azure-AD:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li> Go to <a target=\"blank\" href="https://portal.azure.com/">https://portal.azure.com/</a> and login with your azure account</li><li>Click on Azure Active Directory from Azure services.</li><li>In the left-hand navigation pane, click the App registrations service, and click New registration.</li><li>When the Create page appears, enter your application\'s registration information:<table style="width:80%;"><tr><td>Name</td><td>Your Choice</td></tr><tr><td>Redirect URI</td><td><code>'.JURI::root().'</code></td></tr></table></li><li>Click on register</li><li>Azure AD assigns a unique <b>Cliet ID</b> and <b>tenant ID</b> to your application.Enter the client ID in client ID field and replace [tenant-id] in Authorize Endpoint & Access Token Endpoint.</li><li>Go to <b>Certificates</b> and <b>Secrets</b> from the left navigaton pane and click on <b>New Client Secret</b>. Enter description and expiration time and click on <b>ADD</b>.</li><li>Azure will create a <b>secret key</b> for you. Enter the secret key in <b>secret key field</b> in plugin.</li><li>Click on the Save settings button.</li><li>Click on Test Configuration button to test the connection.</li><li>Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li></ol>',
        'cognito' =>'<strong>Instructions to configure AWS Cognito:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://console.aws.amazon.com/console/home" target="_blank"> https://console.aws.amazon.com/console/home/</a> and sign in with your AWS account. </li><li >Search for <b>Cognito</b> in the AWS Services search bar and click on <b>Manage User Pools</b>.</li><li >Create a new user pool.</li><li >Add a <b>Pool Name</b> and click on the <b>Review Defaults</b> button to continue.</li><li >Scroll down and click on the <b>Add App Client</b> option in front of App Clients.</li><li >Create <b>app client</b> and return to pool details.</li><li >Save your settings and create a user pool.</li><li >Go to <b>App Client setting -> App integration</b>. Select Cognito User Pool.</li><li >Enter <b>' . JURI::root() . '</b> under <b>Callback URL(s)</b> and select <b>Authorization code grant</b>. Also select openid and profile checkboxes. Save your settings.</li><li >Click on choose domains name option to set a <b>domain name</b> for your app.</li><li >Paste your <b>Client ID/Secret</b> provided into the fields above.</li><li >Click on the Save settings button. Click <b>Test Configuration</b> button. Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li><li > Add a button on your site login page with the following URL: <b>' . JURI::root() . '?morequest=oauthredirect&app_name=' . $appname . '</b></li><li >Now logout and go to your site. You will see a login link where you placed that button.</li></ol>',
        'adfs'=>'',
        'whmcs'=>'',
        'keycloak'=>'',
        'slack'   =>'<strong>Instructions to configure Slack:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://api.slack.com/apps" target="blank">https://api.slack.com/apps</a> and login to your Slack account.</li><li>Go to the <a href="https://api.slack.com/apps/new">Create App</a> page. Click on Create an App option.</li><li>Enter App name and Development Slack Workshop. App name can be anything and by default Slack Workshop is Group but you can sign in to another workspace.</li><li>Click On Create App.</li><li>Copy Client ID and Client Secret and enter them in respective fields in the plugin</li><li>Go to <b>OAuth & Permissions</b> , click on <b>Add new Redirect URL</b> and enter <code>'.JURI::root().'</code> under Redirect URLs text field and after that click on <b>ADD</b> and then click on <b>Save URLs</b>.</li><li>Scroll Down to Scopes section and add necessary scopes and then click on Save Changes. Most importantly, these scopes must be the same as given in Configure OAuth Application form (The form in your left).</li><li>Click on the Save settings button.</li><li>Click on Test Configuration button to test the connection.</li><li>Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li></ol>',
        'discord'=>'<strong>Instructions to configure Discord:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://discordapp.com/developers" target="_blank">https://discordapp.com/developers/applications</a> and sign in with your discordapp developer account.On the page, Click on the <strong>New Application</strong> button and enter a <strong>Name</strong> for your app. Click on Save.</li><li>Click on <strong>OAuth2</strong> form left section.</li><li>Click on <b>Add redirect</b> and Enter <b><code id="4">'.JURI::root().'</code></b> in that </li><li>Copy the Client Id and Client Secret from the <b>General information</b> and Paste them into the fields .</li><li>Click on the Save settings button.</li><li>Click on Test Configuration button to test the connection.</li><li>Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li></ol>',
        'invisioncommunity'=>'',
        'bitrix24'=>'<strong>Instructions to configure Bitrix:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://www.bitrix24.com/" target="_blank"> https://www.bitrix24.com/</a> and sign up/login to register a new app. </li><li >Under the <b>Add Your App</b>, select For <b>Developers</b> option to continue. Click on the <b>Start Free button</b>.</li><li >From the navigation menu on the left, go to <b>Application</b>. Click on <b>Add Application</b> to add an application.</li><li >Fill up the form according to your <b>use case</b> and click on the Save button to save your settings.</li><li >Paste your <b>Application ID/Secret</b> provided by Bitrix into the fields above.</li><li >Click on the Save settings button. Click <b>Test Configuration</b> button. Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li><li >Add a button on your site login page with the following URL: <b>' . JURI::root() . '?morequest=oauthredirect&app_name=bitrix24</b></li><li >Now logout and go to your site. You will see a login link where you placed that button.</li></ol>',
        'wso2'=>'',
        'okta'=>'',
        'onelogin'=>'<strong>Instructions to configure OneLogin:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://app.onelogin.com/login" target="_blank">https://app.onelogin.com/login</a>and log in to your Onelogin account.Go to Your <b>Admin Dashboard</b>.</li><li>In your Onelogin Admin dashboard go to <b>Applications --> Applications</b> and click on <b>Add App</b> on top right of the screen.</li><li>You will be shown a search list. Search for OIDC (OpenID Connect) and select <b>OpenId Connect (OIDC)</b> from the search result.</li><li>You will be shown a configuration screen. Fill the application name and other details as required then click on <b>Save</b>.</li><li>Now, go to the Configuration tab and enter <code>'.JURI::root().'</code> as Redirect URIs and click on <b>Save</b>.</li><li>Go to SSO tab. You will find Client ID over there. Click on Show client secret to get the Client Secret.Enter them in respective fields in Configure OAuth Application form (The form in your left).</li><li>Click on Test Configuration button to test the connection.</li><li>Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li></ol>',
        'gapps'=>'<strong>Instructions to configure Google :</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Visit the Google website for developers <a href="https://console.developers.google.com/project"target="_blank">console.developers.google.com</a>.</li><li >From the <b>project drop-down</b>, choose <b>Create a new project</b>, enter a name for the project, and optionally, edit the provided <b>Project ID</b>. Click Create.</li><li >Open the <b>Google API Console Credentials</b> page and go to <b>API Manager -> Credentials</b></li><li >On the <b>Credentials</b> page, select <b>Create credentials</b>, then select <b>OAuth client ID</b>.</li><li >You may be prompted to set a <b>product name</b> on the Consent screen. If so, click <b>Configure consent screen</b>, supply the requested information, and click Save to return to the Credentials screen.</li><li >Select <b>Web Application</b> for the Application Type. Follow the instructions to enter <b>JavaScript origins, redirect URIs, or both</b>. Provide <b>' . JURI::root() . '</b> for the <b>Redirect URI</b>.</li><li >Click Create.</li><li >On the page that appears, copy the <b>client ID and client secret</b> to your clipboard, as you will need them to configure above.</li><li >Enable the <b>Google+ API</b>.</li><li >Click on the Save settings button. Click <b>Test Configuration</b> button. Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li><li >Add a button on your site login page with the following URL: <b>' . JURI::root() . '?morequest=oauthredirect&app_name=gapps</b></li><li >Now logout and go to your site. You will see a login link where you placed that button.</li></ol>',
        'fbapps'=>'<strong>Instructions to configure Facebook : </strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to Facebook developers console <a href="https://developers.facebook.com/apps/" target="_blank">https://developers.facebook.com/apps/</a>.</li><li >Click on Create a New App/Add new App button. You will need to login to create an App(Create new account if you dont have an facebook account).</li><li >Enter <b>Display Name</b>. And choose category.</li><li >Click on <b>Create App ID</b>.</li><li>In Add Product select Facebook Login and click on Setup</li><li >Click on Web. Enter your Site URL. From the left panel, select Facebook Login -> Settings.<li >Under <b>Client OAuth Settings</b>, enter <b>' . JURI::root() . '</b> in Valid OAuth redirect URIs and click <b>Save Changes</b>.</li><li >Go to Settings -> Basic. Enter your domain name in App Domain(Ex:- In www.abc.com enter abc), In the same window bottom of page click <b> Add Platform </b> and select Website URL. Then add  <b>' . JURI::root() . '</b> in Website URL , your privacy policy in Privacy Policy URL and select Category of your website .</li><li >Go to App Review make your app public. After making app public you will see on right top corner your app status changes from In Development to Live.</li><li >Paste your App ID/Secret provided by Facebook into the fields above.</li><li >Click on the Save settings button. Click <b>Test Configuration</b> button. Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li><li >Add a button on your site login page with the following URL: <b>' . JURI::root() . '?morequest=oauthredirect&app_name=fbapps</b></li><li >Now logout and go to your site. You will see a login link where you placed that button.</li></ol>',
        'gluu'=>'',
        'linkedin'=>'<strong>Instructions to configure LinkedIn:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://www.linkedin.com/developers/login" target="_blank"> https://www.linkedin.com/developers/login</a> and sign up/login to your account. </li><li >Create a new application.</li><li >Enter the required details and submit. </li><li >Paste your <b>Client ID/Secret</b> provided by LinkedIn into the fields above.</li><li >Enter <b>' . JURI::root() . '</b> under <b>OAuth 2.0 -> Authorized Redirect URL(s)</b> textbox. Save the changes. </li><li >Click on the Save settings button. Click <b>Test Configuration</b> button. Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li><li >Add a button on your site login page with the following URL: <b>' . JURI::root() . '?morequest=oauthredirect&app_name=linkedin</b></li><li >Now logout and go to your site. You will see a login link where you placed that button.</li></ol>',
        'strava'=>'',
        'fitbit'=>'<strong>Instructions to configure Fitbit:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://dev.fitbit.com/" target="_blank">https://dev.fitbit.com/</a> to go to FitBit developers console.</li><li>Go to <b>Manage</b> --> <b>Register an APP</b> to create an APP.</li><li><b>Login/Signup</b> to your FitBit account in case you are not already logged in.</li><li>In the Callback URL field, enter the <code>'.JURI::root().'</code> . Fill rest of the fields and click on the <b>Register</b> button to save your configurations.</li><li>Copy your Client ID and Client Secret and  enter them in respective fields in Configure OAuth Application form (The form in your left).</li><li>Click on the Save settings button.</li><li>Click on Test Configuration button to test the connection.</li><li>Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li></ol>',
        'box'=>'',
        'github'=>'',
        'gitlab'=>'',
        'clever'=>'',
        'salesforce'=>'',
        'reddit'=>'<strong>Instructions to configure Reddit:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://ssl.reddit.com/prefs/apps/" target="_blank"> https://ssl.reddit.com/prefs/apps/</a> and sign up/login to your account. </li><li >Click on Are you a developer? create an app button to create a new App as shown below.</li><li >Enter the name, description and about url for your application. Enter <b><code>' . JURI::root() . '</code></b> under the <b>redirect uri</b> text field. Finally, click on the <b>create app button</b> to save your app.</li><li >Paste your <b>Client ID/Secret</b> provided by Reddit into the fields above.</li><li >Click on the Save settings button. Click <b>Test Configuration</b> button. Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li><li >Add a button on your site login page with the following URL: <b><code>' . JURI::root() . '?morequest=oauthredirect&app_name=reddit</code></b></li><li >Now logout and go to your site. You will see a login link where you placed that button.</li></ol>',
        'paypal'=>'',
        'swiss-rx-login'=>'',
        'yahoo'=>'<strong>Instructions to configure Yahoo:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://developer.yahoo.com" target="_blank">https://developer.yahoo.com</a> Sign in with your yahoo account and select Apps from the menubar.</li><li>On the page, Click on the <strong>Create an App</strong> button.</li><li>Enter <strong>Application Name</strong> and select <strong>Application Type</strong> as <strong>Web Application</strong>.</li><li>Enter <b><code id="13">' . JURI::root() . '</code></b> in the <strong>Home Page URL</strong>.</li><li>Enter <b><code id="14">' . JURI::root() . '</code></b></b> in <b>Callback Domain</b></li><li>Select all <b>API Permissions</b>.</li><li>Click on <strong>Create App</strong>.</li><li>Copy the <b>Client ID</b> and <b>Client Secret</b> from this page and Paste them into the fields above.</li><li>Keep <b>Scope</b> blank. </li><li>Click on the <b>Save settings</b> button.</li><li>Click on Test Configuration button to test the connection.</li><li>Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li></ol>',

        'spotify'=>'<strong>Instructions to configure Spotify:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to Spotify developers dashboard <a href="https://developer.spotify.com/dashboard/" target="_blank">https://developer.spotify.com/dashboard/</a>. Login with your Spotify developer account.</li><li>Click on Create A CLIENT ID.</li><li>Enter <b>Application name and other details about the application</b> and click on Next button.</li><li>Click on <b>Commercial/non-Commercial option </b> after that click on next </li><li>Click on <b>Edit Settings</b>. Enter <b><code id="24">'.JURI::root().'</code></b> into <b>Website</b> </li><li>under Client Redirect URI section, Enter <b><code id="25">'.JURI::root(). '</code></b> and click on <b>Add</b> button.</li><li>Paste your <b>Client ID</b> and <b>CLient Secret</b> provided by Spotify into the fields. </li><li>Click on the <b>Save settings</b> button.</li><li>Click on Test Configuration button to test the connection.</li><li>Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li></ol>',
        'eveonlinenew'=>'',
        'vkontakte'=>'<strong>Instructions to configure Vkontakte:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://new.vk.com/dev" target="_blank">https://new.vk.com/dev</a> and sign in with your vkontakte account.</li><li>Go to <strong>My Apps</strong> and click on <strong>Create an Application</strong>.</li><li>Provide a name for your app.</li><li>Select <strong>Website</strong> as the <strong>Category</strong>. Select <b><code id="35">' . JURI::root() . '</code></b> as <strong>Site address</strong></li><li>Enter the <b><code id="36">' .'Your site domain' . '</code></i></b> as Base domain.</li><li>Click on <strong>Connect Site</strong> to create the app.</li><li>You will be required to confirm your request with a code send via SMS.</li><li>Once the application is created, select <strong>Settings</strong> in the left nav.</li><li>Enter the <b><code id="37">' . JURI::root() . '</code></b> as <b>Authorized redirect URI.</b></li><li>Click on Save.</li><li>From the top of the same page, copy the <b>Application ID</b> (This is your <b>Client ID </b>) and <b>Secure Key</b> (This is your <b>Client Secret</b>). Paste them into the fields.</li><li>Click on the Save settings button.</li><li>Click on Test Configuration button to test the connection.</li><li>Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li></ol>',

        'pinterest'=>'<strong>Instructions to configure Pinterest:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://developers.pinterest.com/apps/" target="_blank">https://developers.pinterest.com/apps/</a>. Sign in with your Pinterest account.</li><li>Click on I agree to the Pinterest Developer Terms and the API Policy checkbox and then click on <strong>Create app</strong> button.</li><li>Enter <b>Name</b> and <b>Description</b> of your application.</li><li>Click on <b>Create</b> button.</li><li>Enter <b><code id="22">'.JURI::root().'</code></b> into <b>Site URL</b>. Enter <b><code id="23">'.JURI::root(). '</code></b> in the <strong>Redirect URIs</strong>.</li><li>Please click on <b>Submit for review</b> button. Enter the required information and click on <b>submit</b> to send your app for review.This custom app will not work until your app will not get verified from Pinterest</li><li>Copy the <b>Client ID</b> and <b>Client Secret</b> from top of the page and Paste them into the Client ID and Client Secret fields. </li><li>Click on the <b>Save settings</b> button.</li><li>Click on Test Configuration button to test the connection.</li><li>Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li></ol>',
        'vimeo'=>'<strong>Instructions to configure Pinterest:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://developer.vimeo.com" target="_blank">https://developer.vimeo.com</a>. Sign in with your Vimeo account.</li><li>Click on <b>Create App</b> button on top right corner.</li><li>Then enter name of your application in <strong>App name</strong> field and description in <strong>App description</strong> field.</li><li>Select your access priority and check <strong>Terms of Service</strong> field.</li><li>In the callback URLs section click on <strong>Add URL +</strong> and enter <b><code id="34">'.JURI::root(). '</code></b></li><li>Copy the <b>Client identifier</b> and <b>Client secrets</b> from this page and Paste them into the Client ID and Client Secret fields.</li><li>Click on the <b>Save settings</b> button.</li><li>Click on Test Configuration button to test the connection.</li><li>Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li></ol>',
        'deviantart'=>'',
        'dailymotion'=>'',
        'meetup'=>'<strong>Instructions to configure Meetup:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://secure.meetup.com/meetup_api/oauth_consumers" target="_blank">https://secure.meetup.com/meetup_api/oauth_consumers/</a> and sign in with your meetup developer account.</li><li>On the page, Click on the <strong>Create One New</strong> button and On Register Oauth Consumer form enter <strong>Name, Description, and Privacy URL</strong> for your app.</li><li>Enter <b><code id="21">'.JURI::root()."</code></b> in the <strong>Redirect URL</strong>.</li><li>Enter your website URL in <strong>Application Website</strong> field. Click on register consumer. </li><li>Copy the Client Id and Client Secret from the Oauth Consumers and Paste them into the respective field in this plugin.</li><li>Click on the Save settings button.</li><li>Click on Test Configuration button to test the connection.</li><li>Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li></ol> ",
        'autodesk'=>'',
        'zendesk'=>'<strong>Instructions to configure Zendesk:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://www.zendesk.com/login/" target="_blank">https://www.zendesk.com/login/</a> and sign up/login.</li><li>In Zendesk support click on <b>Admin</b> and select the <b>API</b> in the Channels category.</li><li>Click the <b>OAuth Clients</b> tab in the Channels/API page, and then click the plus icon (<b>+</b>) on the right side of the client list.</li><li>Enter a name for your application under the <b>Client Name</b> field and add <code>'.JURI::root().'</code> <b>Redirect URLs</b> field. The <b>Unique Identifier</b> field is auto-populated with a reformatted version of the name you entered for your app. You can change it if you want. Click on <b>Save</b> to create a new OAuth client.</li><li>After successful save, page refreshes with new pre-populated <b>Client Secret</b> field. Copy <b>Unique Identifier (Client ID)</b> and <b>Client Secret</b>, Enter them in <b>client ID</b> and <b>Client Secret</b> field of Configure OAuth Application form (The form in your left).</li><li>Click on the Save settings button.</li><li>Click on Test Configuration button to test the connection.</li><li>Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li></ol>',
        'laravel'=>'',
        'identityserver'=>'',
        'nextcloud'=>'',
        'twitch'=>'<strong>Instructions to configure Twitch:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://dev.twitch.tv/console/apps" target="_blank">https://dev.twitch.tv/console/apps</a>. Sign in with your Twitch account.</li><li>Click on <b>Register Your Application</b>.</li><li>Then enter name of your application in <strong>Name</strong> field. Enter <b><code id="31">'.JURI::root().'</code></b> in the <strong>OAuth Redirect URL</strong>. Then select your website <b>Category</b>.</li><li>Click on <strong>Create</strong> button.</li><li>After creating application click on <b>manage</b> button which in front of your created application.</li><li>Click on <b>New Secret</b> button. It will generate Client Secret key. Copy the <b>Client ID</b> and <b>Client Secret</b> from this page and Paste them into the Client ID and Client Secret fields. </li><li>Click on the <b>Save settings</b> button.</li><li>Click on Test Configuration button to test the connection.</li><li>Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li></ol>',
        'wildApricot'=>'<strong>Instructions to configure Wildapricot:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://www.wildapricot.com/" target="_blank"> https://www.wildapricot.com/</a> and sign up/login to register a new app. </li><li >Under the <b>Settings</b>, select <b>Integration</b> option to continue. Click on <b>Authorize</b> application</li><li>From the list available select <b>Server Application</b> and click on Continue. </li><li >Fill the details and pick <b>Client ID</b> and <b>Client Secret</b> and fill them in the above Client Id and Client Secret fields. Click on save.</li><li >Click on <b>Authorize users</b> checkbox. </b> Enter <b>' . JURI::root() . '</b> under Redirect URLs. </li><li >Click on Save button to save your configuration. </li><li >Click <b>Test Configuration</b> button. Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li><li >Add a button on your site login page with the following URL: <b>' . JURI::root() . '?morequest=oauthredirect&app_name=wildApricot</b></li><li >Now logout and go to your site. You will see a login link where you placed that button.</li></ol>',
        'connect2id'=>'',
        'miniorange'=>'',
        'orcid'=>'',
        'diaspora'=>'',
        'timezynk'=>'',
        'other'=>'<strong>Instructions to configure OAuth Server:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Provide <b>' . JURI::root() . '</b> for your OAuth server Redirect URI.</li><li >Enter your Client ID and Client Secret above.</li><li >Fill the Endpoints appropriately.</li><li >Click on the Save settings button.</li><li >Add a button on your site login page with the following URL: <b>' . JURI::root() . '?morequest=oauthredirect&app_name=other</b></li><li >Now logout and go to your site. You will see a login link where you placed that button.</li></ol>',
        'openidconnect'=>'',
        'windows'=>'<strong>Instructions to configure Windows:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://apps.dev.microsoft.com/" target="_blank"> https://apps.dev.microsoft.com/ </a> and sign in with your windows account.</li><li >Click on <b>Add an app</b>.</li><li >Name your new app and click on Create.</li><li >On the <b>Registration page</b>, copy the <b>Application Id</b>. This is your <b>Client ID</b>.</li><li >Click on <b>Generate New Password</b>. Copy your password. This is your <b>Client Secret</b>.</li><li >Enter your <b>Client ID and Client Secret</b> above.</li><li >Click on the Save settings button.</li><li >Provide <b>' . JURI::root() . '</b> for your OAuth server Redirect URI.</li><li >Click on the Save settings button. Click <b>Test Configuration</b> button. Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li><li >Add a button on your site login page with the following URL: <b>' . JURI::root() . '?morequest=oauthredirect&app_name=windows</b></li><li >Now logout and go to your site. You will see a login link where you placed that button.</li></ol>',
        'instagram'=>'<strong>Instructions to configure Instagram:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Go to <a href="https://www.instagram.com/developer" target="_blank"> https://www.instagram.com/developer</a> and sign up/login to register a new app. </li><li >Click on Register Your Application button.</li><li >Enter your <b>website URL and phone number</b> under the Your <b>Website and Phone Number</b> text fields respectively. Click on the sign-up button to continue.</li><li >Click on Register your Application button again and then go to <b>Manage Clients Register a New Client</b>.</li><li >Enter your <b>Application Name, Description, Company name, website URL, Contact Email etc.</b> Enter <b>' . JURI::root() . '</b> under Valid Redirect URLs. </li><li >Click on Register button to save your configuration. </li><li >Click on the Manage button to view your <b>Client ID and Client Secret</b>.</li><li >Paste your <b>Client ID/Secret</b> provided by LinkedIn into the fields above.</li><li >Go to <b>Security tab</b> and uncheck the Disable <b>Implicit OAuth</b> option. Save your changes.</li><li >Click on the Save settings button. Click <b>Test Configuration</b> button. Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li><li >Add a button on your site login page with the following URL: <b>' . JURI::root() . '?morequest=oauthredirect&app_name=instagram</b></li><li >Now logout and go to your site. You will see a login link where you placed that button.</li></ol>',
        'line'=>'<strong>Instructions to configure Line:</strong>'.$guide.'<br><br><ol class="make-bullets-happen"><li>Download the <b>Line Application(app)</b> and sign up/login into the app in the mobile.</li><li>Go to <a href="https://developers.line.biz/" target="_blank"> https://developers.line.biz/</a> and login using the same credentials. </li><li >From top menu click on <b>Products</b>.</li><li>From the options click on LINE Login. Click on <b>Start now</b></li><li >Please click on <b>Create new provider</b> and give suitable name and click on <b>Next page</b>. Fill all the <b>Required details</b>.</li><li >In Channel Settings, you will find your <b>Channel ID(Client ID)</b> and <b>Channel Secret(Client Secret)</b>. Please copy it and keep it handy.</li><li>Applying for email permission click on Submit next to "Email" in the OpenID Connect session and Agree to the application terms and upload a screen shot of the screen that explains to the user why you need to obtain their email address and what you will use it for. </li><li>In <b>App settings</b> click on edit and ADD <b>callback URL/Redirect URL</b>. </li><li>Fill the Client ID and Client secret you copied before in the above fields.</li><li >Click on Save button to save your configuration. </li><li >Click <b>Test Configuration</b> button. Save the <b>Attribute names</b> into <b>Attribute Mapping section</b> below <b>Test Configuration</b> button.</li><li >Add a button on your site login page with the following URL: <b>' . JURI::root() . '?morequest=oauthredirect&app_name=line</b></li><li >Now logout and go to your site. You will see a login link where you placed that button.</li></ol>'
    );

    if(empty($instructionsArray[$appname]))
        $instructionsArray[$appname]=$guide."<h4 style='text-align:center;color:#7373ed !important;'><code style='font-size:16px;color:midnightblue !important;'>Contact us for configuration queries</code></h4>";
    return $instructionsArray[$appname];
}
function mo_oauth_2fa()
{
    ?>


    <form name="f2">
        <h4 style="text-align: center;">Looking for a Joomla Two-Factor Authentication (2FA)?</h4>
        <table id="OAuth Server_support" class="OAuth Server-table">

            <tr>
                <th class="" style="border: none; padding-bottom: 4%;"><img
                            src="<?php echo JURI::root(); ?>administrator/components/com_miniorange_oauth/assets/images/2fa.png"
                            alt="miniOrange icon" height=100px width=80%>
                    <h3>
                        <img src="<?php echo JURI::root(); ?>administrator/components/com_miniorange_oauth/assets/images/miniorange.png"
                             alt="miniOrange icon" height=50px width=50px>&nbsp;&nbsp;&nbsp;Two-Factor Authentication
                        (2FA)</h3></th>
            </tr>
            <tr>
                <td style="text-align: center">
                    Two Factor Authentication (2FA) plugin adds a second layer of authentication at the time of login to
                    secure your Joomla accounts. It is a highly secure and easy to setup plugin which protects your site
                    from hacks and unauthorized login attempts.
                </td>
            </tr>

            <tr>
                <td style="padding-left: 25%"><br>
                    <a href="https://www.miniorange.com/downloads/joomla-2fa-plugin.zip" class="btn btn-primary"
                       style="padding: 4px 10px;">Download Plugin</a>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
                            href="https://plugins.miniorange.com/joomla-two-factor-authentication-2fa"
                            class="btn btn-success" style="padding: 4px 10px;" target="_blank">Know More</a>
                </td>
            </tr>

        </table>
    </form>

    <?php
}

function mo_oauth_help()
{
    ?>

    <object type="text/html" data="https://faq.miniorange.com/kb/oauth-openid-connect" width="100%" height="600px"
            border="2" style="border-radius:15px; border-color: white;"></object>

    <?php

}

function mo_oauth_support()
{

    $current_user = JFactory::getUser();
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from($db->quoteName('#__miniorange_oauth_customer'));
    $query->where($db->quoteName('id') . " = 1");

    $db->setQuery($query);
    $result = $db->loadAssoc();
    $admin_email = $result['email'];
    $admin_phone = $result['admin_phone'];

    if ($admin_email == '')
        $admin_email = $current_user->email;


    ?>
    <div id="moJoom-OauthClient-supportForm" class="moJoom-OauthClient-supportForm">
        <input style="font-size: 15px;cursor: pointer;text-align: center;width: 150px;height: 35px;
                background: rgba(43, 141, 65, 0.93);color: #ffffff;border-radius: 3px;transform: rotate(90deg);text-shadow: none;
                position: relative;margin-left: -92px;top: 145px;float:left"  type="submit" id="moJoom-OauthClient-supportButton-SideButton" class="moJoom-OauthClient-supportButton-SideButton" name="op" value="Support">

        <form name="f" method="post"
              action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.contactUs'); ?>">
            <h3>Support</h3>
            <p class="oauth-table">Need any help? Just send us a query and we will get back to you soon.</p>
            <table class="oauth-table">
                <tr>
                    <td>
                        <input type="email" class="form-control oauth-table"
                               style="border-radius:4px;resize: vertical;width:100%" id="query_email" name="query_email"
                               value="<?php echo $admin_email; ?>" placeholder="Enter your email" required/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="text" class="form-control oauth-table"
                               style="border-radius:4px;resize: vertical;width:100%" name="query_phone" id="query_phone"
                               value="<?php echo $admin_phone; ?>" placeholder="Enter your phone with country code"/>
                    </td>
                </tr>
                <tr>
                    <td>
                    <textarea id="query" name="query" class="form-control"
                              style="border-radius:4px;resize: vertical;width:100%" cols="52" rows="6"
                              onkeyup="mo_otp_valid(this)" onblur="mo_otp_valid(this)" onkeypress="mo_otp_valid(this)"
                              placeholder="Write your query here" required></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" name="send_query" id="send_query" value="Submit Query"
                               style="margin-bottom:1%;display: block;margin-left: auto;margin-right: auto;"
                               class="btn btn-medium btn-success"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div hidden id="mosaml-feedback-overlay"></div>

    <br/>
    <script>

        function mo_otp_valid(f) {
            !(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;
        }
    </script>
    <?php
}

?>
<?php
function requestfordemo()
{
    ?>
    <h3> Request For Demo </h3>
    <hr>
    <div style="background-color: #e2e6ea; padding: 10px;"><p>If you want to try the upgraded version of the plugin then
            we can setup a demo Joomla site for you on our cloud and provide you with its credentials. You can configure
            it with your Oauth server, test the SSO and play around with the premium features.
        </p><b>Note:</b> Please describe your use-case in the <b>Description</b> below.
    </div><br>
    <form id="demo_request" name="demo_request" method="post"
          action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.requestForDemoPlan'); ?>">
        <table cellpadding="4" cellspacing="4">
            <tr>
                <td style="width: 35%;"><strong>Email:<span style="color: red;">*</span></strong></td>
                <td><input required onblur="validateEmail(this)" type="email"
                           style="border-radius:4px;resize: vertical;width:350px" name="email"
                           placeholder="person@example.com"
                           value="<?php echo JFactory::getUser()->email; ?>"/>
                    <p style="display: none;color:red" id="email_error">Invalid Email</p></td>

            </tr>

            <tr>
                <td><strong>Request a demo for:<span style="color: red;">*</span></strong></td>
                <td>
                    <select required style="width: 364px; height:35px;" name="plan" id="rfd_id">
                        <option value="">--------------------------------- Select ---------------------------------
                        </option>
                        <option value="Joomla OAuth Client Standard Plugin">Joomla OAuth Client Standard Plugin
                        </option>
                        <option value="Joomla OAuth Client Premium Plugin">Joomla OAuth Client Premium Plugin
                        </option>
                        <option value="Joomla OAuth Client Enterprise Plugin">Joomla OAuth Client Enterprise
                            Plugin
                        </option>
                        <option value="Not Sure">Not Sure</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Description:<span style="color: #ff7316;">*</span></strong></td>
                <td>
                        <textarea required type="text" name="description"
                                  style="resize: vertical; width:350px; height:100px;"
                                  rows="4"
                                  placeholder="Need assistance? Write us about your requirement and we will suggest the relevant plan for you."
                                  value=""></textarea>
                </td>
            </tr>


            <tr>
                <td></td>
                <td><input type="submit" name="submit" value="Submit" class="btn btn-success btn-medium"/></td>
            </tr>

        </table>
    </form>
    <script type="text/javascript">
        function validateEmail(emailField) {
            var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

            if (reg.test(emailField.value) == false) {
                document.getElementById('email_error').style.display = "block";
                document.getElementById('submit_button').disabled = true;
            } else {
                document.getElementById('email_error').style.display = "none";
                document.getElementById('submit_button').disabled = false;
            }
        }
    </script>
    <br>
<?php }