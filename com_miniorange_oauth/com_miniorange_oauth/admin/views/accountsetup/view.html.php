<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_miniorange_oauth
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Account Setup View
 *
 * @since  0.0.1
 */
class miniorangeoauthViewAccountSetup extends JViewLegacy
{
    function display($tpl = null)
    {
        // Get data from the model
        $this->lists        = $this->get('List');
        //$this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JFactory::getApplication()->enqueueMessage(500, implode('<br />', $errors));

            return false;
        }
        $this->setLayout('accountsetup');
        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function addToolBar()
    {
        JToolBarHelper::title(JText::_('com_miniorange_oauth_PLUGIN_TITLE'),'mo_oauth_logo mo_oauth_icon');
    }

    public function showLicensingPlanDetails() {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__miniorange_oauth_customer'));
        $query->where($db->quoteName('id')." = 1");

        $db->setQuery($query);
        $useremail = $db->loadAssoc();


        if(isset($useremail))
            $user_email =$useremail['email'];
        else
            $user_email="xyz";


        ?>
        <div class="tab-content" style= "background-color: #DBF3FA;">
            <div class="tab-pane active text-center" id="cloud">
                <div class="cd-pricing-container cd-has-margins"><br>

                    <ul class="cd-pricing-list cd-bounce-invert" >
                        <li class="cd-black">

                            <ul class="cd-pricing-wrapper"  style="height: 500px";>

                                <li id="singlesite_tab" data-type="singlesite" class="mosslp is-visible cd-singlesite" style="width: 100%">
                                    <header class="cd-pricing-header" style="height: 230px">
                                        <h2 style="margin-bottom: 10px" >Free<br/><br/><br></h2>
                                        <div class="cd-price" >
                                            <br><br>
                                            <b style="font-size: large">You are automatically on this plan</b>

                                        </div>

                                    </header> <!-- .cd-pricing-header -->
                                    </a>
                                    <footer class="cd-pricing-footer">
                                        <a class="cd-select" style="font-size: 85.5%;" >Current Active Plan</a>
                                    </footer><br>
                                    <!--                                <b style="color: coral;">See the Standard Plugin features list below</b>-->
                                    <div class="cd-pricing-body">

                                        <ul class="cd-pricing-features">
                                            <li style="font-size: medium"> Limited authentications</li>
                                            <li style="font-size: medium">Auto register users Upto 10</li>
                                            <li style="font-size: medium">Auto fill OAuth servers configuration</li>
                                            <li style="font-size: medium">Basic Attribute Mapping(Username , Email)</li>
                                            <li style="font-size: medium">Login using the link</li>
                                            <li style="font-size: medium"><br></li>
                                            <li style="font-size: medium"> <br></li>
                                            <li style="font-size: medium"><br></li>
                                            <li style="font-size: medium"><br><br></li>
                                            <li style="font-size: medium"> <br></li>
                                            <li style="font-size: medium"> <br><br></li>
                                            <li style="font-size: medium"> <br></li>
                                            <li style="font-size: medium"> <br></li>
                                            <li style="font-size: medium"><br></li>
                                            <li style="font-size: medium"><br><br><br><br><br><br></li>



                                        </ul>

                                    </div>
                                </li>

                            </ul> <!-- .cd-pricing-wrapper -->
                        </li>
                        <li class="cd-black">

                            <ul class="cd-pricing-wrapper"  style="height: 500px";>

                                <li id="singlesite_tab" data-type="singlesite" class="mosslp is-visible cd-singlesite" style="width: 100%">
                                    <header class="cd-pricing-header" style="height: 230px">
                                        <h2 style="margin-bottom: 10px" >Standard<br/></h2>(Unlimited Authentications, Unlimited user creations, Advance Attribute Mapping)<br>
                                        <div class="cd-price" ><br><br><br>
                                            <span id="plus_total_price" style="font-weight: bolder;font-size: xx-large">$249*</span>
                                            <br><br>
                                            <b style="font-size: large"></b>

                                        </div>

                                    </header> <!-- .cd-pricing-header -->
                                    </a>
                                    <footer class="cd-pricing-footer">

                                        <a class="cd-select" style="font-size: 85.5%; cursor: pointer;"  onclick= "<?php if(! MoOAuthUtility::is_customer_registered()){ echo " window.location.href='index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account' "; } else { echo " window.open('https://login.xecurify.com/moas/login?username=".$user_email."&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=joomla_oauth_client_standard_plan')"; } ?>" >Upgrade Now</a>
                                    </footer><br>
                                    <!--                                <b style="color: coral;">See the Standard Plugin features list below</b>-->
                                    <div class="cd-pricing-body">
                                        <ul class="cd-pricing-features">
                                            <li style="font-size: medium"> Unlimited authentications</li>
                                            <li style="font-size: medium">Auto register users Unlimited</li>
                                            <li style="font-size: medium">Auto fill OAuth servers configuration</li>
                                            <li style="font-size: medium">Attribute Mapping(Username , Email)</li>
                                            <li style="font-size: medium">Login using the link</li>
                                            <li style="font-size: medium">Custom Redirect URL after login and logout</li>
                                            <li style="font-size: medium">Basic Group Mapping</li>
                                            <li style="font-size: medium"> <br></li>
                                            <li style="font-size: medium"> <br><br></li>
                                            <li style="font-size: medium"><br></li>
                                            <li style="font-size: medium"><br><br></li>
                                            <li style="font-size: medium"><br></li>
                                            <li style="font-size: medium"><br></li>
                                            <li style="font-size: medium"><br></li>
                                            <li style="font-size: medium"> <b>Add-Ons **</b><br>Purchase Separately<br><a style="color:blue;" href="https://www.miniorange.com/contact" target='_blank'><br><b>Contact us</b></a><br><br><br></li>




                                        </ul>

                                    </div>
                                </li>

                            </ul> <!-- .cd-pricing-wrapper -->
                        </li>
                        <li class="cd-black">

                            <ul class="cd-pricing-wrapper">

                                <li id="singlesite_tab" data-type="singlesite" class="mosslp is-visible" style="height=600px; width: 100%; left: 30%; ">
                                    <header class="cd-pricing-header" style="height: 230px">
                                        <h2 style="margin-bottom: 10px">Premium<br/></h2>(Advanced Group Mapping, OpenId Connect)<br/>
                                        <div class="cd-price" ><br><br><br><br>
                                            <span id="plus_total_price" style="font-weight: bolder;font-size: xx-large">$399*</span> <br/></h3>
                                        </div>
                                    </header> <!-- .cd-pricing-header -->
                                    </a>
                                    <footer class="cd-pricing-footer">
                                        <a class="cd-select" style="font-size: 85.5%; cursor: pointer;"  onclick= "<?php if(! MoOAuthUtility::is_customer_registered()){ echo " window.location.href='index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account' "; } else { echo " window.open('https://login.xecurify.com/moas/login?username=".$user_email."&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=joomla_oauth_client_premium_plan')"; } ?>" >Upgrade Now</a>
                                    </footer><br>

                                    <div class="cd-pricing-body">
                                        <ul class="cd-pricing-features">
                                            <li style="font-size: medium"> Unlimited authentications</li>
                                            <li style="font-size: medium">Auto register users Unlimited</li>
                                            <li style="font-size: medium">Auto fill OAuth servers configuration</li>
                                            <li style="font-size: medium">Advanced Attribute Mapping</li>
                                            <li style="font-size: medium">Login using the link</li>
                                            <li style="font-size: medium">Custom Redirect URL after login and logout</li>
                                            <li style="font-size: medium">Advanced Group Mapping</li>
                                            <li style="font-size: medium">Force authentication/Protect complete site</li>
                                            <li style="font-size: medium">OpenId Connect Support (Login using OpenId Connect Server)</li>
                                            <li style="font-size: medium">Domain specific registration</li>
                                            <li style="font-size: medium">Grant Settings</li>
                                            <li style="font-size: medium">JWT Validation</li>
                                            <li style="font-size: medium"><br><br></li>
                                            <li style="font-size: medium"><br></li>
                                            <li style="font-size: medium"> <b>Add-Ons **</b><br>Purchase Separately<br><a style="color:blue;" href="https://www.miniorange.com/contact" target='_blank'><br><b>Contact us</b></a><br><br><br></li>



                                        </ul>
                                    </div> <!-- .cd-pricing-body -->
                                </li>

                            </ul> <!-- .cd-pricing-wrapper -->
                        </li>

                        <li class="cd-black">
                            <ul class="cd-pricing-wrapper">
                                <li id="singlesite_tab" data-type="singlesite" class="mosslp is-visible" style="width: 100%; left: 60%;">
                                    <header class="cd-pricing-header" style="height: 230px">
                                        <h2 style="margin-bottom:10px;">Enterprise<br/></h2>(Additional end point for getting user groups from your OAuth/Open ID provider, Login Reports/Analysis)<br/>
                                        <div class="cd-price" ><br><br><br>
                                            <span id="pro_total_price" style="font-weight: bolder;font-size: xx-large">$449*</span> <br/></h3>
                                        </div>
                                    </header> <!-- .cd-pricing-header -->
                                    <footer class="cd-pricing-footer">
                                        <a class="cd-select" style="font-size: 85.5%; cursor: pointer;"  onclick= "<?php if(! MoOAuthUtility::is_customer_registered()){ echo " window.location.href='index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account' "; } else { echo " window.open('https://login.xecurify.com/moas/login?username=".$user_email."&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=joomla_oauth_client_enterprise_plan')"; } ?>" >Upgrade Now</a>
                                    </footer><br>
                                    <!--                                <b style="color: coral;">See the Enterprise Plugin features list below</b>-->
                                    <div class="cd-pricing-body">
                                        <ul class="cd-pricing-features">
                                            <li style="font-size: medium"> Unlimited authentications</li>
                                            <li style="font-size: medium">Auto register users Unlimited</li>
                                            <li style="font-size: medium">Auto fill OAuth servers configuration</li>
                                            <li style="font-size: medium">Advanced Attribute Mapping</li>
                                            <li style="font-size: medium">Login using the link</li>
                                            <li style="font-size: medium">Custom Redirect URL after login and logout</li>
                                            <li style="font-size: medium">Advanced Group Mapping</li>
                                            <li style="font-size: medium">Force authentication/Protect complete site</li>
                                            <li style="font-size: medium">OpenId Connect Support (Login using OpenId Connect Server)</li>
                                            <li style="font-size: medium">Domain specific registration</li>
                                            <li style="font-size: medium">Grant Settings</li>
                                            <li style="font-size: medium">JWT Validation</li>
                                            <li style="font-size: medium">Additional end point for getting user groups from your OAuth/Open ID provider.</li>
                                            <li style="font-size: medium">Login Reports/Analytics</li>
                                            <li style="font-size: medium"> <b>Add-Ons **</b><br>Purchase Separately<br><a style="color:blue;" href="https://www.miniorange.com/contact" target='_blank'><br><b>Contact us</b></a><br><br><br></li>


                                        </ul>
                                    </div> <!-- .cd-pricing-body -->
                                    <!-- .cd-pricing-body -->
                                </li>


                            </ul> <!-- .cd-pricing-wrapper -->
                        </li>


                    </ul> <!-- .cd-pricing-list -->
                </div> <!-- .cd-pricing-container -->

            </div>

            <!-- Modal -->
            <br/><br/>


            <br/><div style="margin-left: 60px">
                <table>
                    <tr><td><h2><sup>*</sup></h2></td><td><h4 class="moOauthPointerCursor">This is the price for 1 instance. <i>Buying multiple licenses does not mean you have to pay the same amount for all the licenses.</i> We provide a deep discount from the second instance onwards. Check our <a onclick= "<?php if(! MoOAuthUtility::is_customer_registered()){ echo " window.location.href='index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account' "; } else { echo " window.open('https://login.xecurify.com/moas/login?username=".$user_email."&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=joomla_oauth_client_enterprise_plan')"; } ?>">pricing page</a> for full details.</h4></td></tr>
                </table>

                <div class="moOauthClientIndent">
                    <h4>10 Days Return Policy -</h4>
                    <p class="moOauthClientIndent">At miniOrange, we want to ensure you are 100% happy with your purchase. If the plugin you purchased is not working as advertised and you've attempted to resolve any issues with our support team, which couldn't get resolved, we will refund the whole amount given that you have a raised a request for refund within the first 10 days of the purchase. Please email us at <a href="mailto:joomlasupport@xecurify.com">joomlasupport@xecurify.com</a> for any queries regarding the return policy.</p>
                    <h4>Steps for Upgrade to licensed version of the Plugin -</h4>
                    <div class="moOauthClientIndent">
                        <p>1. You will be redirected to miniOrange Login Console. Enter your username and password with which you created an account with us. After that you will be redirected to payment page.</p>
                        <p>2. Enter your card details and complete the payment. On successful payment completion, you will see the link to download the premium plugin.</p>
                        <p>3. Once you download the premium plugin, first uninstall existing plugin ( free version ) then install the premium plugin. <br>
                    </div>
                </div>
                <h3 >** Add-Ons List</h3>
                <p class="moOauthClientIndent">Integration with Community Builder, SCIM (User Provisioning), Page Restriction</p>
                <br>
            </div><br>
        </div>


        <style>
            .cd-black :hover #singlesite_tab.is-visible{
                margin-right : 4px;
                transition : 0.4s;
                -moz-transition : 0.4s;
                -webkit-transition : 0.4s;
                border-radius: 8px;
                transform: scale(1.03);
                -ms-transform: scale(1.03); /* IE 9 */
                -webkit-transform: scale(1.03); /* Safari */

                box-shadow: 0 0 4px 1px rgba(255,165, 0, 0.8);
            }



            h1 {
                margin: .67em 0;
                font-size: 2em;
            }

            ul {
                list-style: none; /* Remove HTML bullets */
                padding: 0;
                margin: 0;
            }

            li {
                list-style: none; /* Remove HTML bullets */
                padding: 0;
                margin: 0;
            }
        </style>

        <?php


    }
}