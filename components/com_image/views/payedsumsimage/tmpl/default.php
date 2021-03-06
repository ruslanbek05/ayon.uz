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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_image');

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_image'))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_IMAGE_FORM_LBL_PAYEDSUMSIMAGE_MEDIAMANAGER'); ?></th>
			<td><?php echo $this->item->mediamanager; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_IMAGE_FORM_LBL_PAYEDSUMSIMAGE_IMAGELIST'); ?></th>
			<td><?php echo $this->item->imagelist; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_IMAGE_FORM_LBL_PAYEDSUMSIMAGE_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_image&task=payedsumsimage.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_IMAGE_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_image.payedsumsimage.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_IMAGE_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_IMAGE_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_IMAGE_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_image&task=payedsumsimage.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_IMAGE_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>