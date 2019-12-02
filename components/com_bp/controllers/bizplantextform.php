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
 * Bizplantext controller class.
 *
 * @since  1.6
 */
class BpControllerBizplantextForm extends JControllerForm
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
		$previousId = (int) $app->getUserState('com_bp.edit.bizplantext.id');
		$editId     = $app->input->getInt('id', 0);

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_bp.edit.bizplantext.id', $editId);

		// Get the model.
		$model = $this->getModel('BizplantextForm', 'BpModel');

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

$id_bizplan = JRequest::getVar('id_bizplan');
$model->set_id_bizplan($id_bizplan);
$abzats_tr = JRequest::getVar('abzats_tr');

$text_tr = JRequest::getVar('text_tr');
$turi = JRequest::getVar('turi');
$id_current = JRequest::getVar('id_current');
$turiAlternativa = JRequest::getVar('turiAlternativa');


		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_bp&view=bizplantextform&layout=edit&id_bizplan=' . $id_bizplan . '&abzats_tr=' . $abzats_tr . '&turi=' . $turi . '&text_tr=' . $text_tr . '&id_current=' . $id_current . '&turiAlternativa=' . $turiAlternativa, false));
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
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app   = JFactory::getApplication();
		$model = $this->getModel('BizplantextForm', 'BpModel');


		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');

/*		print_r($data);
		die();*/


		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			throw new Exception($model->getError(), 500);
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

/*		print_r($data);
		die();
*/
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
			$app->setUserState('com_bp.edit.bizplantext.data', $jform);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_bp.edit.bizplantext.id');
			$this->setRedirect(JRoute::_('index.php?option=com_bp&view=bizplantextform&layout=edit&id=' . $id, false));

			$this->redirect();
		}


/*		print_r($data);
		die();*/


		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_bp.edit.bizplantext.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_bp.edit.bizplantext.id');
			$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_bp&view=bizplantextform&layout=edit&id=' . $id, false));
		}

		// Check in the profile.
		if ($return)
		{
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_bp.edit.bizplantext.id', null);

		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_BP_ITEM_SAVED_SUCCESSFULLY'));
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();


		
		//print_r($item->link);
		//die();
//		$id_bizplan = JRequest::getVar('id_bizplan');
/*		print_r($id_bizplan);
		die;*/
//		$id_bizplan = $model->get_id_bizplan();
/*		print_r($id_bizplan);
		die;*/
		$id_bizplan = $data['id_bizplan'];
		$url  = 'index.php?option=com_bp&view=bizplantexts&id_bizplan=' . $id_bizplan;
		//$url  = (empty($item->link) ? 'index.php?option=com_bp&view=bizplantexts' : $item->link);
		
		$this->setRedirect(JRoute::_($url, false));

		// Flush the data from the session.
		$app->setUserState('com_bp.edit.bizplantext.data', null);
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
		$editId = (int) $app->getUserState('com_bp.edit.bizplantext.id');

		// Get the model.
		$model = $this->getModel('BizplantextForm', 'BpModel');

		// Check in the item
		if ($editId)
		{
			$model->checkin($editId);
		}

		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_bp&view=bizplantexts' : $item->link);
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
        $model = $this->getModel('BizplantextForm', 'BpModel');
        $pk    = $app->input->getInt('id');
        $id_bizplan = $app->input->getInt('id_bizplan');
        
        /*print_r($id_bizplan);
        die;*/

        // Attempt to save the data
        try
        {
            $return = $model->delete($pk);

            // Check in the profile
            $model->checkin($return);

            // Clear the profile id from the session.
            $app->setUserState('com_bp.edit.bizplantext.id', null);

            $menu = $app->getMenu();
            $item = $menu->getActive();
            


            
            
            //$item->link='index.php?option=com_bp&view=bizplantexts&id_bizplan=' . $id_bizplan;
            $url = 'index.php?option=com_bp&view=bizplantexts&id_bizplan=' . $id_bizplan;
            //$url = (empty($item->link) ? 'index.php?option=com_bp&view=bizplantexts' : $item->link);

            // Redirect to the list screen
            $this->setMessage(JText::_('COM_EXAMPLE_ITEM_DELETED_SUCCESSFULLY'));
            $this->setRedirect(JRoute::_($url, false));

            // Flush the data from the session.
            $app->setUserState('com_bp.edit.bizplantext.data', null);
        }
        catch (Exception $e)
        {
            $errorType = ($e->getCode() == '404') ? 'error' : 'warning';
            $this->setMessage($e->getMessage(), $errorType);
            $this->setRedirect('index.php?option=com_bp&view=bizplantexts');
        }
    }
}
