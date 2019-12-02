<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Bp
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2017 ruslan qodirov
 * @license    paid
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Bp records.
 *
 * @since  1.6
 */
class BpModelBizplantexts extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'ordering', 'a.ordering',
				'state', 'a.state',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'id_bizplan', 'a.id_bizplan',
				'turi', 'a.turi',
				'abzats_tr', 'a.abzats_tr',
				'text_tr', 'a.text_tr',
				'mundarija_text_content', 'a.mundarija_text_content',
				'id_current', 'a.id_current',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app  = JFactory::getApplication();
		$list = $app->getUserState($this->context . '.list');

		$ordering  = isset($list['filter_order'])     ? $list['filter_order']     : null;
		$direction = isset($list['filter_order_Dir']) ? $list['filter_order_Dir'] : null;

		$list['limit']     = (int) JFactory::getConfig()->get('list_limit', 20);
		$list['start']     = $app->input->getInt('start', 0);
		$list['ordering']  = $ordering;
		$list['direction'] = $direction;

		$app->setUserState($this->context . '.list', $list);
		$app->input->set('list', null);

		// List state information.
		parent::populateState($ordering, $direction);

        $app = JFactory::getApplication();

        $ordering  = $app->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);
        $direction = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $ordering);

        $this->setState('list.ordering', $ordering);
        $this->setState('list.direction', $direction);

        $start = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0, 'int');
        $limit = $app->getUserStateFromRequest($this->context . '.limit', 'limit', 0, 'int');

        if ($limit == 0)
        {
            $limit = $app->get('list_limit', 0);
        }

        $this->setState('list.limit', $limit);
        $this->setState('list.start', $start);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query
			->select(
				$this->getState(
					'list.select', 'DISTINCT a.*'
				)
			);

		$query->from('`#__bp_bizplantext` AS a');
		
		// Join over the users for the checked out user.
		$query->select('uc.name AS uEditor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the created by field 'modified_by'
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');

		//
		$user = JFactory::getUser();
		$query->select('uv.vote AS user_vote');
		$query->join('LEFT', '#__bp_vote AS uv ON uv.id_bizplantext = a.id and uv.id_users = ' . (int) $user->id);

$id_bizplan = JRequest::getVar('id_bizplan');
//		$query->select('bp_bizplans.name AS bp_bizplans_name');
//		$query->join('LEFT', '#__bp_bizplans AS bp_bizplans ON bp_bizplans.id = a.id_bizplan');
		
		if (!JFactory::getUser()->authorise('core.edit', 'com_bp'))
		{
//			$query->where('a.state = 1');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');




$tovarfilteredbyuser = JRequest::getVar('tovarfilteredbyuser');
if ($tovarfilteredbyuser<>null) {
$query->where("a.created_by = '".$db->escape($tovarfilteredbyuser)."'");	

//$lang = JFactory::getLanguage();
//$languageTag = $lang->getTag();

//echo $languageTag;die;

//		$query->select('bpplang.lang AS bizplan_lang');
//		$query->join('LEFT', '#__bp_bizplans AS bpplang ON bpplang.id = a.id_bizplan');

//$query->where("a.bizplan_lang = '".$db->escape($languageTag)."'");

	}
	else
	{
		
		
		// print_r($id_bizplan);
		$query->where('a.id_bizplan = ' . (int) $id_bizplan);
}


		$abzats_tr = JRequest::getVar('abzats_tr');
		$text_tr = JRequest::getVar('text_tr');
		if (($abzats_tr<>null) and ($text_tr<>null)) {
			$query->where('a.abzats_tr = ' . (int) $abzats_tr);
			$query->where('a.text_tr = ' . (int) $text_tr);
		}
		else
		{
			$query->where('a.state = 1');
		}


		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.mundarija_text_content LIKE ' . $search . ' )');
			}
		}
		

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'ordering');
		$orderDirn = $this->state->get('list.direction', 'asc');

$query->order($db->escape('a.abzats_tr ASC'));
$query->order($db->escape('a.text_tr ASC'));

/*		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}
*/
		return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		
		foreach ($items as $item)
		{

					//$item->turi = JText::_('COM_BP_BIZPLANTEXTS_TURI_OPTION_' . strtoupper($item->turi));
		}

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_BP_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? JFactory::getDate($date)->format("Y-m-d") : null;
	}
}
