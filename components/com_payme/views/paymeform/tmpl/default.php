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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_payme', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_payme/js/form.js');

$user    = JFactory::getUser();
$canEdit = PaymeHelpersPayme::canUserEdit($this->item, $user);


$jinput = JFactory::getApplication()->input;
$product_ids = $jinput->getint('product_ids', '0');

?>

<div class="payme-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(JText::_('COM_PAYME_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo JText::sprintf('COM_PAYME_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo JText::_('COM_PAYME_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>

		<form id="form-payme"
			  action="<?php echo JRoute::_('index.php?option=com_payme&task=payme.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
			
			<div class="control-group">
				<div class="controls">
					<?php echo "</br>"; ?>
					<input type="hidden" name="product_ids" value="<?php echo $product_ids; ?>"/>
					<input name="summa" value="0"/>
					</br>
					</br>

					<?php //if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php echo JText::_('COM_PAYME_CHOOSE_PAYMENT_METHOD'); ?>
						</button>
					<?php //endif; ?>
					<a class="btn"
					   href="<?php echo JRoute::_('index.php?option=com_payme&task=paymeform.cancel'); ?>"
					   title="<?php echo JText::_('JCANCEL'); ?>">
						<?php //echo JText::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_payme"/>
			<input type="hidden" name="task"
				   value="paymeform.save"/>
			<?php echo JHtml::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
