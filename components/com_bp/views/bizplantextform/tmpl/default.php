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

?>
<style>

#jform_mundarija_text_content-lbl, #control-label {
	display:none;
}

.form-horizontal .controls {
	margin-left: 0px;
}

</style>
<?php

$turi = filter_var ( $_REQUEST['turi'], FILTER_SANITIZE_STRING);



JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_bp', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_bp/js/form.js');

$user    = JFactory::getUser();
$canEdit = BpHelpersBp::canUserEdit($this->item, $user);


?>

<div class="bizplantext-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(JText::_('COM_BP_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo JText::sprintf('COM_BP_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<?php if ($turi=='AddPreviousHeadlineMundarija') : ?>
				<h1><?php echo JText::_('COM_BP_BIZPLANTEXTS_ACTIONS_YANGI_OBZATS_QUSHISH_OLDINGI'); ?></h1>
			<?php elseif ($turi=='AddPreviousText') : ?>
				<h1><?php echo JText::_('COM_BP_BIZPLANTEXTS_ACTIONS_YANGI_TEXT_QUSHISH_OLDINGI'); ?></h1>
			<?php elseif ($turi=='AddAlternative') : ?>
				<h1><?php echo JText::_('COM_BP_BIZPLANTEXTS_ACTIONS_ALTERNATIVE'); ?></h1>
			<?php elseif ($turi=='AddNextHeadlineMundarija') : ?>
				<h1><?php echo JText::_('COM_BP_BIZPLANTEXTS_ACTIONS_YANGI_OBZATS_QUSHISH_KEYINGI'); ?></h1>
			<?php elseif ($turi=='AddNextText') : ?>
				<h1><?php echo JText::_('COM_BP_BIZPLANTEXTS_ACTIONS_YANGI_TEXT_QUSHISH_KEYINGI'); ?></h1>
			<?php endif; ?>
		<?php endif; ?>

		<form id="form-bizplantext"
			  action="<?php echo JRoute::_('index.php?option=com_bp&task=bizplantext.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
			
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>

	<?php //echo $this->form->renderField('id_bizplan'); ?>
	<input type="hidden" name="jform[id_bizplan]" value="<?php 
	$id_bizplan = JRequest::getVar('id_bizplan');
	echo $id_bizplan; ?>" />
	
	
	
	
<?php 
$turi = JRequest::getVar('turi');
//echo $turi;

if ($turi == '') { ?>
	
	<?php 
	
	echo $this->form->renderField('turi'); 
	
	} else { 
	?>
	
	<input type="hidden" name="jform[turi]" value="<?php 
	$turi = JRequest::getVar('turi');
	echo $turi; 
	?>" />
	<?php } ?>




	<?php //echo $this->form->renderField('abzats_tr'); ?>
	<input type="hidden" name="jform[abzats_tr]" value="<?php 
	$abzats_tr = JRequest::getVar('abzats_tr');
	echo $abzats_tr; ?>" />


	<?php //echo $this->form->renderField('text_tr'); ?>
	<input type="hidden" name="jform[text_tr]" value="<?php 
	$text_tr = JRequest::getVar('text_tr');
	echo $text_tr; ?>" />

	<input type="hidden" name="jform[id_current]" value="<?php 
	$id_current = JRequest::getVar('id_current');
	echo $id_current; ?>" />

	<input type="hidden" name="jform[turiAlternativa]" value="<?php 
	$turiAlternativa = JRequest::getVar('turiAlternativa');
	echo $turiAlternativa; ?>" />



	<?php echo $this->form->renderField('mundarija_text_content'); ?>



				<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','bp')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
				<?php if (!JFactory::getUser()->authorise('core.admin','bp')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-bizplantext").appendChild(input);
                    });
                </script>
             <?php endif; ?>
			<div class="control-group">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php echo JText::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn"
					   href="<?php echo JRoute::_('index.php?option=com_bp&task=bizplantextform.cancel'); ?>"
					   title="<?php echo JText::_('JCANCEL'); ?>">
						<?php echo JText::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_bp"/>
			<input type="hidden" name="task"
				   value="bizplantextform.save"/>
			<?php echo JHtml::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>

<?php
/*
if (($turi=='AddPreviousHeadlineMundarija') or ($turi=='AddNextHeadlineMundarija')){

	$lang = JFactory::getLanguage();
	if ($lang->getTag()=='uz-UZ'){
		echo "{article 22}[text]{/article}";
	}
	if ($lang->getTag()=='ru-RU'){
		echo "{article 23}[text]{/article}";
	}
	if ($lang->getTag()=='en-GB'){
		echo "{article 24}[text]{/article}";
	}	
}*/
?>

