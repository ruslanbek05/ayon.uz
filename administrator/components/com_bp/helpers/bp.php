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

/**
 * Bp helper.
 *
 * @since  1.6
 */
class BpHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_BP_TITLE_BIZPLANS'),
			'index.php?option=com_bp&view=bizplans',
			$vName == 'bizplans'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_BP_TITLE_BIZPLANTEXTS'),
			'index.php?option=com_bp&view=bizplantexts',
			$vName == 'bizplantexts'
		);

		JHtmlSidebar::addEntry(
			JText::_('JCATEGORIES') . ' (' . JText::_('COM_BP_TITLE_BIZPLANS') . ')',
			"index.php?option=com_categories&extension=com_bp",
			$vName == 'categories'
		);
		if ($vName=='categories') {
			JToolBarHelper::title('Bp: JCATEGORIES (COM_BP_TITLE_BIZPLANS)');
		}

	}

	/**
	 * Gets the files attached to an item
	 *
	 * @param   int     $pk     The item's id
	 *
	 * @param   string  $table  The table's name
	 *
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
	 */
	public static function getFiles($pk, $table, $field)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($field)
			->from($table)
			->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    JObject
	 *
	 * @since    1.6
	 */
	public static function getActions()
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		$assetName = 'com_bp';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}

