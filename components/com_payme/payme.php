<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Payme
 * @author     ruslanbek05 <ruslanbek05@yandex.ru>
 * @copyright  2019 ruslanbek05
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Payme', JPATH_COMPONENT);
JLoader::register('PaymeController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Payme');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
