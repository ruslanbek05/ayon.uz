<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Bp
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2017 ruslan qodirov
 * @license    paid
 */
// No direct access
defined('_JEXEC') or die;


$jinput = JFactory::getApplication()->input;

$user_name = $jinput->get('user_name', '', 'filter');
$user_pass = $jinput->get('password', '', 'filter');


jimport('joomla.user.authentication');
$auth = & JAuthentication::getInstance();
$credentials = array( 'username' => $user_name, 'password' => $user_pass );
$options = array();
$response = $auth->authenticate($credentials, $options);

if ($response->status != JAuthentication::STATUS_SUCCESS)
{
echo "Oh Snap! Failure!";
}
else
{
echo "W00t! Success";
}
//print_r($response);die;
exit;
?>