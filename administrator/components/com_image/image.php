<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Image
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2019 ruslan qodirov
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_image'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Image', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('ImageHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'image.php');

$controller = JControllerLegacy::getInstance('Image');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
