<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Payme
 * @author     ruslanbek05 <ruslanbek05@yandex.ru>
 * @copyright  2019 ruslanbek05
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Paymes list controller class.
 *
 * @since  1.6
 */
class PaymeControllerPaymes extends PaymeController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional
	 * @param   array   $config  Configuration array for model. Optional
	 *
	 * @return object	The model
	 *
	 * @since	1.6
	 */
	public function &getModel($name = 'Paymes', $prefix = 'PaymeModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}
}
