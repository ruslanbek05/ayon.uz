<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Image
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Image', JPATH_COMPONENT);
JLoader::register('ImageController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Image');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
