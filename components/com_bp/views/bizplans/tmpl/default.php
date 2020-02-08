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
<link rel="stylesheet" type="text/css" href="components/com_bp/views/bizplans/tmpl/mystyle_for_bizplans.css">

<?php
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_bp') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'bizplanform.xml');
$canEdit    = $user->authorise('core.edit', 'com_bp') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'bizplanform.xml');
$canCheckin = $user->authorise('core.manage', 'com_bp');
$canChange  = $user->authorise('core.edit.state', 'com_bp');
$canDelete  = $user->authorise('core.delete', 'com_bp');
$canView  = $user->authorise('core.view', 'com_bp');


# groups to match
$super_users_group_id = 8;
$registered_group_id = 21;

//print_r($user);die;
/*# see if the user is in both
if( in_array($sales_group_id, $user->groups ) AND in_array($design_group_id, $user->groups ) ){ 
   echo "the user is in both sales and design"
}else{
    echo "Use is not";
}*/
/*if( in_array($super_users_group_id, $user->groups )){ ?>
   the user is super user
<?php }else{ ?>

<?php }*/

?>

<?php 
$id_bizplansforbizplantexts = JRequest::getVar('id_bizplansforbizplantexts');
$tovarfilteredbyuser = JRequest::getVar('tovarfilteredbyuser');

if ($id_bizplansforbizplantexts==null): ?>
<?php if ($tovarfilteredbyuser==null): ?>
<h1 class="page-header" align="center"><?php echo JText::_('COM_BP_HEAD_BIZPLANS'); ?></h1>
<?php endif; ?>
<?php endif; ?>

<form action="<?php echo JRoute::_('index.php?option=com_bp&view=bizplans'); ?>" method="post"
      name="adminForm" id="adminForm">
<?php 




if ($id_bizplansforbizplantexts==null) {
if ($tovarfilteredbyuser == null) {
echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); 
}
}

?>
<div class="album py-5 bg-light">
    <div class="container">
      <div class="row">
<?php foreach ($this->items as $i => $item) : 

//print_r($item);die;
				// Convert the JSON-encoded image info into an array


if (($item->loyiha_qiymati == 0) or (is_null($item->loyiha_qiymati))){
	$tuplangan_mablag_foizi = 0;
	$loyihaga_pul_toplash_ochilmagan = 0;
	
}
else{
	//print_r($item->loyiha_qiymati);die;
	$tuplangan_mablag_foizi = number_format(($item->tuplangan_mablag / $item->loyiha_qiymati) * 100, 0);
	$loyihaga_pul_toplash_ochilmagan = 1;
}

?>

        <div class="col-md-4">
          <div class="card mb-4 shadow-sm" >
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text><image xlink:href="https://www.ayon.uz/<?php echo $item->mediamanager; ?>" x="0" y="0" height="100%" width="100%"/></svg>
            <div class="card-body">
              <p class="card-text"><?php echo $this->escape($item->name); ?></p>
              <?php if($loyihaga_pul_toplash_ochilmagan==1){ ?><div class="progress">
  <div class="progress-bar" role="progressbar" style="width: <?php echo $tuplangan_mablag_foizi; ?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="50"><?php echo $tuplangan_mablag_foizi; ?>%</div>
</div>
<p class="card-text"><?php echo number_format($item->tuplangan_mablag,2,',',' '); ?><?php echo JText::_('COM_BP_HOW_MUCH_COLLECTED'); ?><span class="badge badge-secondary pointer" onClick="location.href='<?php echo JRoute::_('index.php?option=com_bp&view=payedsums&project_id='.(int) $item->id); ?>';"><?php echo JText::_('COM_BP_PAYERS'); ?></span></p><?php } ?>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary" onClick="location.href='<?php echo JRoute::_('index.php?option=com_payme&view=paymeform&product_ids='.(int) $item->id); ?>';">Поддержать проект</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary" onClick="location.href='<?php echo JRoute::_('index.php?option=com_bp&view=bizplantexts&id_bizplan='.(int) $item->id); ?>';"><?php echo JText::_('COM_BP_BIZPLANS_GO_TO_THE_BUSINESS_PLAN_TEXT'); ?></button>
                  						<?php if ($canEdit): ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" onClick="location.href='<?php echo JRoute::_('index.php?option=com_bp&task=bizplanform.edit&id=' . $item->id, false, 2); ?>';"><?php echo JText::_('COM_BP_BIZPLANTEXTS_EDIT'); ?></button>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" onClick="location.href='<?php echo JRoute::_('index.php?option=com_bp&task=bizplanform.remove&id=' . $item->id, false, 2); ?>';"><?php echo JText::_('COM_BP_BIZPLANTEXTS_DELETE'); ?></button>
						<?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        
      
        

<?php //endif; ?>
		<?php endforeach; ?>
      </div>
    </div>
  </div>
<?php echo $this->pagination->getListFooter(); ?>


<?php
$id_bizplansforbizplantexts = JRequest::getVar('id_bizplansforbizplantexts');
if ($id_bizplansforbizplantexts==null) : ?>
	<?php if ($canCreate) : ?>
		<a href="<?php echo JRoute::_('index.php?option=com_bp&task=bizplanform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo JText::_('COM_BP_ADD_ITEM'); ?></a>
	<?php endif; ?>
<?php endif; ?>



	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>

<?php if($canDelete) : ?>
<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {

		if (!confirm("<?php echo JText::_('COM_BP_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; ?>
