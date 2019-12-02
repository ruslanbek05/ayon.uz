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

jimport('joomla.application.component.controllerform');

/**
 * Bizplantext controller class.
 *
 * @since  1.6
 */
class BpControllerBizplantext extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'bizplantexts';
		parent::__construct();
	}
}
