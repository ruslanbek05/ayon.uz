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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_bp.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_bp' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLANTEXT_ID_BIZPLAN'); ?></th>
			<td><?php echo $this->item->id_bizplan; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLANTEXT_TURI'); ?></th>
			<td><?php echo $this->item->turi; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLANTEXT_ABZATS_TR'); ?></th>
			<td><?php echo $this->item->abzats_tr; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLANTEXT_TEXT_TR'); ?></th>
			<td><?php echo $this->item->text_tr; ?></td>
		</tr>
</table>
		
			<?php echo JText::_('COM_BP_FORM_LBL_BIZPLANTEXT_MUNDARIJA_TEXT_CONTENT'); ?>
			<?php echo $this->item->mundarija_text_content; ?>
		
		
	

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_bp&task=bizplantext.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_BP_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_bp.bizplantext.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_BP_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_BP_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_BP_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_bp&task=bizplantext.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_BP_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; 


$id=$this->item->id+1000000000;

$doc = JFactory::getDocument(); 
$title = $doc->getTitle();

  $comments = JPATH_SITE . DS .'components' . DS . 'com_jcomments' . DS . 'jcomments.php';
  if (file_exists($comments)) {
    require_once($comments);
    echo JComments::showComments($id, 'com_bp', $title);
  }
 
?>

