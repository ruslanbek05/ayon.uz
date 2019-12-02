<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Payme
 * @author     ruslanbek05 <ruslanbek05@yandex.ru>
 * @copyright  2019 ruslanbek05
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Payme controller class.
 *
 * @since  1.6
 */
class PaymeControllerPaymeForm extends JControllerForm
{
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	public function edit($key = NULL, $urlVar = NULL)
	{
		$app = JFactory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_payme.edit.payme.id');
		$editId     = $app->input->getInt('id', 0);

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_payme.edit.payme.id', $editId);

		// Get the model.
		$model = $this->getModel('PaymeForm', 'PaymeModel');

		// Check out the item
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_payme&view=paymeform&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return void
	 *
	 * @throws Exception
	 * @since  1.6
	 */
	public function save($key = NULL, $urlVar = NULL)
	{
		$jinput = JFactory::getApplication()->input;
		
		$summa = $jinput->getFloat('summa', '0');
		
		$product_ids = $jinput->getint('product_ids', '0');
		
		$user = JFactory::getUser();
		$user_id=$user->id;
		//$input = $app->input;
		//print_r($product_ids);die;
		
		//create record in order table
			$product_ids=$product_ids;
		
			// Create and populate an object.
			$profile = new stdClass();
			$profile->product_ids = $product_ids;
			$profile->amount=$summa;
			$profile->state=1;
			$profile->user_id=$user_id;

			// Insert the object into the user profile table.
			$result = JFactory::getDbo()->insertObject('orders', $profile);		
		
		//get id of last created order

			// Get a db connection.
			$db = JFactory::getDbo();
			

			// Create a new query object.
			$query = $db->getQuery(true);

			// Select all records from the user profile table where key begins with "custom.".
			// Order it by the ordering field.
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('orders'));
			$query->where($db->quoteName('user_id') . ' = '. $user_id . ' AND ' . $db->quoteName('amount') . ' = '. $summa . ' AND ' . $db->quoteName('product_ids') . ' = '. $product_ids);
			$query->order('id DESC');

			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			$id_of_last_order = $db->loadResult();
			//echo $id_of_last_order;die;
		
		$this->setRedirect(JRoute::_('index.php?option=com_payme&summa='.$summa.'&product_ids='.$product_ids.'&user_id='.$user_id.'&order='.$id_of_last_order, false));
		$this->redirect();
		
		
		
		
		
		
		
		
		echo "sss";die;
		
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app   = JFactory::getApplication();
		$model = $this->getModel('PaymeForm', 'PaymeModel');

		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			throw new Exception($model->getError(), 500);
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			$input = $app->input;
			$jform = $input->get('jform', array(), 'ARRAY');

			// Save the data in the session.
			$app->setUserState('com_payme.edit.payme.data', $jform);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_payme.edit.payme.id');
			$this->setRedirect(JRoute::_('index.php?option=com_payme&view=paymeform&layout=edit&id=' . $id, false));

			$this->redirect();
		}

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_payme.edit.payme.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_payme.edit.payme.id');
			$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_payme&view=paymeform&layout=edit&id=' . $id, false));
		}

		// Check in the profile.
		if ($return)
		{
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_payme.edit.payme.id', null);

		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_PAYME_ITEM_SAVED_SUCCESSFULLY'));
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_payme&view=paymes' : $item->link);
		$this->setRedirect(JRoute::_($url, false));

		// Flush the data from the session.
		$app->setUserState('com_payme.edit.payme.data', null);
	}

	/**
	 * Method to abort current operation
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function cancel($key = NULL)
	{
		$app = JFactory::getApplication();

		// Get the current edit id.
		$editId = (int) $app->getUserState('com_payme.edit.payme.id');

		// Get the model.
		$model = $this->getModel('PaymeForm', 'PaymeModel');

		// Check in the item
		if ($editId)
		{
			$model->checkin($editId);
		}

		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_payme&view=paymes' : $item->link);
		$this->setRedirect(JRoute::_($url, false));
	}

	/**
	 * Method to remove data
	 *
	 * @return void
	 *
	 * @throws Exception
     *
     * @since 1.6
	 */
	public function remove()
    {
        $app   = JFactory::getApplication();
        $model = $this->getModel('PaymeForm', 'PaymeModel');
        $pk    = $app->input->getInt('id');

        // Attempt to save the data
        try
        {
            $return = $model->delete($pk);

            // Check in the profile
            $model->checkin($return);

            // Clear the profile id from the session.
            $app->setUserState('com_payme.edit.payme.id', null);

            $menu = $app->getMenu();
            $item = $menu->getActive();
            $url = (empty($item->link) ? 'index.php?option=com_payme&view=paymes' : $item->link);

            // Redirect to the list screen
            $this->setMessage(JText::_('COM_EXAMPLE_ITEM_DELETED_SUCCESSFULLY'));
            $this->setRedirect(JRoute::_($url, false));

            // Flush the data from the session.
            $app->setUserState('com_payme.edit.payme.data', null);
        }
        catch (Exception $e)
        {
            $errorType = ($e->getCode() == '404') ? 'error' : 'warning';
            $this->setMessage($e->getMessage(), $errorType);
            $this->setRedirect('index.php?option=com_payme&view=paymes');
        }
    }
}
