<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Bp
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2017 ruslan qodirov
 * @license    paid
 */

// No direct access.
defined('_JEXEC') or die;

include_once('vote_my_function.php');

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

use Joomla\Utilities\ArrayHelper;

/**
 * Bp model.
 *
 * @since  1.6
 */
class BpModelBizplantextForm extends JModelForm
{
	private $item = null;


protected $id_bizplan;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return void
	 *
	 * @since  1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('com_bp');

		// Load state from the request userState on edit or from the passed variable on default
		if (JFactory::getApplication()->input->get('layout') == 'edit')
		{
			$id = JFactory::getApplication()->getUserState('com_bp.edit.bizplantext.id');
		}
		else
		{
			$id = JFactory::getApplication()->input->get('id');
			JFactory::getApplication()->setUserState('com_bp.edit.bizplantext.id', $id);
		}

		$this->setState('bizplantext.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('bizplantext.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

	/**
	 * Method to get an ojbect.
	 *
	 * @param   integer $id The id of the object to get.
	 *
	 * @return Object|boolean Object on success, false on failure.
	 *
	 * @throws Exception
	 */
	public function &getData($id = null)
	{
		if ($this->item === null)
		{
			$this->item = false;

			if (empty($id))
			{
				$id = $this->getState('bizplantext.id');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table !== false && $table->load($id))
			{
				$user = JFactory::getUser();
				$id   = $table->id;
				
				if ($id)
				{
					$canEdit = $user->authorise('core.edit', 'com_bp.bizplantext.' . $id) || $user->authorise('core.create', 'com_bp.bizplantext.' . $id);
				}
				else
				{
					$canEdit = $user->authorise('core.edit', 'com_bp') || $user->authorise('core.create', 'com_bp');
				}

				if (!$canEdit && $user->authorise('core.edit.own', 'com_bp.bizplantext.' . $id))
				{
					$canEdit = $user->id == $table->created_by;
				}

				if (!$canEdit)
				{
					throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 500);
				}

				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if (isset($table->state) && $table->state != $published)
					{
						return $this->item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties = $table->getProperties(1);
				$this->item = ArrayHelper::toObject($properties, 'JObject');
			}
		}

		return $this->item;
	}

	/**
	 * Method to get the table
	 *
	 * @param   string $type   Name of the JTable class
	 * @param   string $prefix Optional prefix for the table class name
	 * @param   array  $config Optional configuration array for JTable object
	 *
	 * @return  JTable|boolean JTable if found, boolean false on failure
	 */
	public function getTable($type = 'Bizplantext', $prefix = 'BpTable', $config = array())
	{
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_bp/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Get an item by alias
	 *
	 * @param   string $alias Alias string
	 *
	 * @return int Element id
	 */
	public function getItemIdByAlias($alias)
	{
		$table      = $this->getTable();
		$properties = $table->getProperties();

		if (!in_array('alias', $properties))
		{
			return null;
		}

		$table->load(array('alias' => $alias));

		return $table->id;
	}

	/**
	 * Method to check in an item.
	 *
	 * @param   integer $id The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkin($id = null)
	{
//	echo("checkin qilindi!!!!!!");
		// Get the id.
		$id = (!empty($id)) ? $id : (int) $this->getState('bizplantext.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
			if (method_exists($table, 'checkin'))
			{
				if (!$table->checkin($id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param   integer $id The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkout($id = null)
	{
//		echo("checkout qilindi!!!!!!");
		// Get the user id.
		$id = (!empty($id)) ? $id : (int) $this->getState('bizplantext.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = JFactory::getUser();

			// Attempt to check the row out.
			if (method_exists($table, 'checkout'))
			{
				if (!$table->checkout($user->get('id'), $id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML
	 *
	 * @param   array   $data     An optional array of data for the form to interogate.
	 * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
	 *
	 * @return    JForm    A JForm object on success, false on failure
	 *
	 * @since    1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_bp.bizplantext', 'bizplantextform', array(
				'control'   => 'jform',
				'load_data' => $loadData
			)
		);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return    mixed    The data for the form.
	 *
	 * @since    1.6
	 */
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_bp.edit.bizplantext.data', array());

		if (empty($data))
		{
			$data = $this->getData();
		}

		

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array $data The form data
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 1.6
	 */
	public function save($data)
	{

/*echo "model saving!!!!";
die();*/		
		
//print_r($data);die;		


		
		$id    = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('bizplantext.id');
		$state = (!empty($data['state'])) ? 1 : 0;
		$user  = JFactory::getUser();

//print_r($user);die;

		if ($id)
		{
			// Check the user can edit this item
			$authorised = $user->authorise('core.edit', 'com_bp.bizplantext.' . $id) || $authorised = $user->authorise('core.edit.own', 'com_bp.bizplantext.' . $id);
		}
		else
		{
			// Check the user can create new items in this section
			$authorised = $user->authorise('core.create', 'com_bp');
		}

		if ($authorised !== true)
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
		}

		$table = $this->getTable();




//saving
		if ($data['turi']=='AddNextHeadlineMundarija') {
			$data['abzats_tr']=$data['abzats_tr']+1;
			$abzats_tr_for_alt=$data['abzats_tr'];
			$data['text_tr']=0;
			$text_tr_for_alt=$data['text_tr'];
			$data['turi']='mundarija';
			$tr_change_relative_type='mundarija';
			//print_r($data['abzats_tr']);
		}
		elseif ($data['turi']=='AddNextText') {
			//print_r($data['turi']);
			$data['turi']='text';
			$data['text_tr']=$data['text_tr']+1;
			$abzats_tr_for_alt=$data['abzats_tr'];
			$text_tr_for_alt=$data['text_tr'];
			$tr_change_relative_type='text';
		}
		elseif ($data['turi']=='AddPreviousHeadlineMundarija') {
			//print_r($data['turi']);
//			$data['abzats_tr']=$data['abzats_tr']-1;
			$abzats_tr_for_alt=$data['abzats_tr'];
			$data['text_tr']=0;
			$text_tr_for_alt=$data['text_tr'];
			$data['turi']='mundarija';
			$tr_change_relative_type='mundarija';
		}		
		elseif ($data['turi']=='AddPreviousText') {
			//print_r($data['turi']);
			$data['turi']='text';
//			$data['text_tr']=$data['text_tr']-1;
			$abzats_tr_for_alt=$data['abzats_tr'];
			$text_tr_for_alt=$data['text_tr'];
			$tr_change_relative_type='text';
		}		
		elseif ($data['turi']=='AddAlternative') {
			$data['turi']=$data['turiAlternativa'];
		}		



//		print_r($text_tr_for_alt);die();

$id_bizplans=$data['id_bizplan'];

//tr_change
	tr_change_relative($id_bizplans,$abzats_tr_for_alt,$text_tr_for_alt,$tr_change_relative_type);
//END//tr_change


		if ($table->save($data) === true)
		{

//edit vote table
$id_bizplantext=$table->id;
$id_users=$user->id;


vote($id_bizplantext,0, $id_bizplans,1);

//END//edit vote table


//get alternative_count 
$abzats_tr=$data['abzats_tr'];
$text_tr=$data['text_tr'];

	alternative_define_count($id_bizplans,$abzats_tr,$text_tr);
//END//get alternative_count 





			return $table->id;
			
		}
		else
		{
			return false;
		}
	}

	/**
	 * Method to delete data
	 *
	 * @param   int $pk Item primary key
	 *
	 * @return  int  The id of the deleted item
	 *
	 * @throws Exception
	 *
	 * @since 1.6
	 */
	public function delete($pk)
	{

$id_bizplan = $_REQUEST['id_bizplan'];
$abzats_tr = $_REQUEST['abzats_tr'];
$text_tr = $_REQUEST['text_tr'];
$tr_change_relative_type = $_REQUEST['tr_change_relative_type'];







		$user = JFactory::getUser();

		if (empty($pk))
		{
			$pk = (int) $this->getState('bizplantext.id');
		}

		if ($pk == 0 || $this->getData($pk) == null)
		{
			throw new Exception(JText::_('COM_BP_ITEM_DOESNT_EXIST'), 404);
		}

		if ($user->authorise('core.delete', 'com_bp.bizplantext.' . $id) !== true)
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
		}

		$table = $this->getTable();



//tr_change

	tr_change_relative($id_bizplan,$abzats_tr,$text_tr,$tr_change_relative_type);
//END//tr_change



		if ($table->delete($pk) !== true)
		{
			throw new Exception(JText::_('JERROR_FAILED'), 501);
		}

		return $pk;
	}

	/**
	 * Check if data can be saved
	 *
	 * @return bool
	 */
	public function getCanSave()
	{
		$table = $this->getTable();

		return $table !== false;
	}


    public function set_id_bizplan($id_bizplan) 
    {
        $this->id_bizplan = $id_bizplan;
         //return $id_bizplan;
/*        print_r($this->id_bizplan);
        die;*/
    }
    public function get_id_bizplan() 
    {
        //$this->id_bizplan = $id_bizplan;
        return $this->id_bizplan;

    }
    
    
	
}
