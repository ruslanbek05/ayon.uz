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

jimport('joomla.application.component.controllerform');

/**
 * Payme controller class.
 *
 * @since  1.6
 */
class PaymeControllerPayme extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'paymes';
		parent::__construct();
	}
}
