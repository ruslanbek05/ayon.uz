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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_image', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_image/js/form.js');

$user    = JFactory::getUser();
$canEdit = ImageHelpersImage::canUserEdit($this->item, $user);


?>

<div class="payedsumsimage-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(JText::_('COM_IMAGE_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo JText::sprintf('COM_IMAGE_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo JText::_('COM_IMAGE_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>

		<form id="form-payedsumsimage"
			  action="<?php echo JRoute::_('index.php?option=com_image&task=payedsumsimage.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
			
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[projectname]" value="<?php echo $this->item->projectname; ?>" />

	<input type="hidden" name="jform[summa]" value="<?php echo $this->item->summa; ?>" />

	<?php echo $this->form->renderField('mediamanager'); ?>

	<?php echo $this->form->renderField('imagelist'); ?>

	<?php echo $this->form->renderField('created_by'); ?>

			<div class="control-group">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php echo JText::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn"
					   href="<?php echo JRoute::_('index.php?option=com_image&task=payedsumsimageform.cancel'); ?>"
					   title="<?php echo JText::_('JCANCEL'); ?>">
						<?php echo JText::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_image"/>
			<input type="hidden" name="task"
				   value="payedsumsimageform.save"/>
			<?php echo JHtml::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
