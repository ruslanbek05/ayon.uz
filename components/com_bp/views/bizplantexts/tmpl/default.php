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






//for submenu
$voted_index = 0;

$poll="poll";

$id_bizplantext=0;
$id_bizplans=0;

/*
$vote[0]="not_voted";
$vote[1]="up";
$vote[2]="neutral";
$vote[3]="down";
$vote[4]="act of vandalism";*/

$vote[0]="не голосовали за этот абзац";
$vote[1]="добавить свой голос за этот абзац";
$vote[2]="добавить нейтральный голос";
$vote[3]="голосовать против этого абзаца";
$vote[4]="это абзац добавили вандалы";

$image[0]="components/com_bp/views/bizplantexts/tmpl/exclamation.png";
$image[1]="components/com_bp/views/bizplantexts/tmpl/Thumbs-Up.png";
$image[2]="components/com_bp/views/bizplantexts/tmpl/exclamation.png";
$image[3]="components/com_bp/views/bizplantexts/tmpl/Thumbs-Down.png";
$image[4]="components/com_bp/views/bizplantexts/tmpl/stop.png";

?>
<script>
function getVote(int,nn,id_bizplantext,id_bizplans,poll,w,ac,abzats_tr,text_tr) {
	
	
  if (window.XMLHttpRequest) {
    
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
  	
    if (this.readyState==4 && this.status==200) {
      
      document.getElementById(nn).innerHTML=this.responseText;
    }
  }
  
  xmlhttp.open("GET","/components/com_bp/views/bizplantexts/tmpl/poll_vote.php?v="+int+"&id_bizplantext="+id_bizplantext+"&id_bizplans="+id_bizplans+"&poll="+poll+"&w="+w+"&ac="+ac+"&abzats_tr="+abzats_tr+"&text_tr="+text_tr,true);
  xmlhttp.send();
}
</script>
	
	<link rel="stylesheet" type="text/css" href="components/com_bp/views/bizplantexts/tmpl/mystyle.css">

<?php
//END//for submenu








JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_bp') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'bizplantextform.xml');
$canEdit    = $user->authorise('core.edit', 'com_bp') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'bizplantextform.xml');
$canCheckin = $user->authorise('core.manage', 'com_bp');
$canChange  = $user->authorise('core.edit.state', 'com_bp');
$canDelete  = $user->authorise('core.delete', 'com_bp');

//print_r($this->pagination->total);
//die();


$tovarfilteredbyuser = JRequest::getVar('tovarfilteredbyuser');



$id_bizplans = JRequest::getVar('id_bizplan');
$lang = JFactory::getLanguage();
/*
if ($tovarfilteredbyuser<>null) {
//	echo 'profile dan suralyapti!2';die;



//print_r($lang);die;
//echo $lang->getTag().'wwwwwww';
if ($lang->getTag()=='uz-UZ'){
	echo "{component index.php/bp?id_bizplansforbizplantexts=".$id_bizplans."&tovarfilteredbyuser=".$tovarfilteredbyuser."}";
}
if ($lang->getTag()=='ru-RU'){
	echo "{component index.php/ru/business-plans?id_bizplansforbizplantexts=".$id_bizplans."&tovarfilteredbyuser=".$tovarfilteredbyuser."}";
}
if ($lang->getTag()=='en-GB'){
	echo "{component index.php/en/business-plans?id_bizplansforbizplantexts=".$id_bizplans."&tovarfilteredbyuser=".$tovarfilteredbyuser."}";
}

	}
	else
	{

//echo 'bizplantexts dan suralyapti!2';die;

if ($lang->getTag()=='uz-UZ'){
	echo "{component index.php/bp?id_bizplansforbizplantexts=".$id_bizplans."}";
}
if ($lang->getTag()=='ru-RU'){
	echo "{component index.php/ru/biznes-plany?id_bizplansforbizplantexts=".$id_bizplans."}";
}
if ($lang->getTag()=='en-GB'){
	echo "{component index.php/en/business-plans?id_bizplansforbizplantexts=".$id_bizplans."}";
}


}
*/



?>
<?php
$project_id = JRequest::getVar('id_bizplan');

$db = JFactory::getDbo();

$query = $db->getQuery(true);

$query
    ->select(array('a.*'))
    ->from($db->quoteName('#__bp_bizplans', 'a'))
    ->where($db->quoteName('a.id') . ' = '.$project_id);

$db->setQuery($query);

$row = $db->loadAssoc();
//print_r($row);die;

if (($row['loyiha_qiymati'] == 0) or (is_null($row['loyiha_qiymati']))){
	$tuplangan_mablag_foizi = 0;
	$loyihaga_pul_toplash_ochilmagan = 0;
	
}
else{
	//print_r($item->loyiha_qiymati);die;
	$tuplangan_mablag_foizi = number_format(($row['tuplangan_mablag'] / $row['loyiha_qiymati']) * 100, 0);
	$loyihaga_pul_toplash_ochilmagan = 1;
}




$project_name=$row['name'];
$tuplangan_mablag=$row['tuplangan_mablag'];
$image_of_biznes_plan=$row['mediamanager'];

?>
<div class="media">
  <img src="http://www.ayon.uz/<?php echo $image_of_biznes_plan; ?>" class="mr-3" alt="..." height="auto" width="96px">
  <div class="media-body">
    <h5 class="mt-0"><?php echo $project_name; ?></h5>
<?php if($loyihaga_pul_toplash_ochilmagan==1){ ?><div class="progress">
  <div class="progress-bar" role="progressbar" style="width: <?php echo $tuplangan_mablag_foizi; ?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="50"><?php echo $tuplangan_mablag_foizi; ?>%</div>
</div>
<p class="card-text"><?php echo number_format($tuplangan_mablag,2,',',' '); ?><?php echo JText::_('COM_BP_HOW_MUCH_COLLECTED'); ?><span class="badge badge-secondary pointer" onClick="location.href='<?php echo JRoute::_('index.php?option=com_bp&view=payedsums&project_id='.(int) $project_id); ?>';"><?php echo JText::_('COM_BP_PAYERS'); ?></span> <button type="button" class="btn btn-sm btn-outline-secondary" onClick="location.href='<?php echo JRoute::_('index.php?option=com_payme&view=paymeform&product_ids='.(int) $project_id); ?>';">Поддержать проект</button></p><?php } ?>    
  </div>
</div>
<hr>


<form action="<?php echo JRoute::_('index.php?option=com_bp&view=bizplantexts'); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php 
	if ($tovarfilteredbyuser<>null) {
		}
	else
	{
		echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); 
	}
	?>
		<?php foreach ($this->items as $i => $item) : ?>

<?php //if (JFactory::getUser()->authorise( "core.view", "com_bp.bizplan." . $id_bizplans )): ?>



			<?php $canEdit = $user->authorise('core.edit', 'com_bp'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_bp')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>



				<?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					
<?php if ($item->turi=='mundarija') {
	//echo '</br>';
	} ?>

<span class='<?php echo $item->turi; ?>'>
<span align="right" class='poster'>
<?php 
$poll=$item->id;
$id_bizplans=$item->id_bizplan;

$voted_index=$voted_index;

$id_bizplantext=$item->id;
$vote_count[0]="";
$vote_count[1]=$item->up_count;
$vote_count[2]=$item->neutral_count;
$vote_count[3]=$item->down_count;
$vote_count[4]=$item->vandal_count;

$alternative_count=$item->alternative_count;

$abzats_tr=$item->abzats_tr;
$text_tr=$item->text_tr;

//print_r($item);
if ($item->user_vote==NULL){
	$voted_index=0;
	
}
else
{
	$voted_index = $item->user_vote;
}

?>
<img src="components/com_bp/views/bizplantexts/tmpl/keyboard-menu-bar-sign-label-16.png" alt="sub menu" height="13" width="10">
<?php

if ($item->turi=='mundarija') {
		echo $item->abzats_tr;
	}
	else{
		echo $item->abzats_tr.'.'.$item->text_tr;
	}
	 ?>


<div class="descr" align="left">
<strong>
<?php echo JText::_('COM_BP_BIZPLANTEXTS_VOTE_PLEASE'); ?>
</strong>
<div id=<?php echo $poll?>>
<?php for ($i = 0; $i <= 4; $i++) { ?>
<input type="checkbox" name="vote_n" value="<?php echo $i; ?>" onclick="getVote(this.value,'<?php echo $poll?>','<?php echo $id_bizplantext?>','<?php echo $id_bizplans?>','<?php echo $poll?>','<?php echo $userId?>','<?php echo $alternative_count?>')" <?php if ($i==$voted_index) {echo "checked";} else {echo "unchecked";} ;?>>
<img src="<?php echo $image[$i] ?>">
<?php echo $vote[$i]; ?>
<?php echo ' - всего '.$vote_count[$i].'</br>'; }?>
</div>

<strong>
<?php echo JText::_('COM_BP_BIZPLANTEXTS_ADD_PARAGRAPH'); ?>
</strong>
</br>



<?php if ($canCreate): ?>

	<?php if ($canCreate): ?>


<a href="<?php echo JRoute::_('index.php?option=com_bp&task=bizplantextform.edit&id=0&id_bizplan=' .  $item->id_bizplan . '&abzats_tr=' . $item->abzats_tr . '&turi=AddPreviousHeadlineMundarija' . '&text_tr=' . $item->text_tr . '&id_current=' . $item->id, false, 0); ?>"><?php echo JText::_('COM_BP_BIZPLANTEXTS_ACTIONS_YANGI_OBZATS_QUSHISH_OLDINGI'); ?></a>
</br>


<a href="<?php echo JRoute::_('index.php?option=com_bp&task=bizplantextform.edit&id=0&id_bizplan=' .  $item->id_bizplan . '&abzats_tr=' . $item->abzats_tr . '&turi=AddPreviousText' . '&text_tr=' . $item->text_tr . '&id_current=' . $item->id, false, 0); ?>"><?php echo JText::_('COM_BP_BIZPLANTEXTS_ACTIONS_YANGI_TEXT_QUSHISH_OLDINGI'); ?></a>
</br>
	<?php endif; ?>




	<?php if ($canCreate):
$abzats_tr = $item->abzats_tr;
$abzats_tr = $abzats_tr;
 ?>

<a href="<?php echo JRoute::_('index.php?option=com_bp&task=bizplantextform.edit&id=0&id_bizplan=' .  $item->id_bizplan . '&abzats_tr=' . $abzats_tr . '&turi=AddAlternative' . '&text_tr=' . $item->text_tr . '&id_current=' . $item->id . '&turiAlternativa=' . $item->turi, false, 0); ?>"><?php echo JText::_('COM_BP_BIZPLANTEXTS_ACTIONS_ALTERNATIVE'); ?></a>
</br>

<a href="<?php echo JRoute::_('index.php?option=com_bp&task=bizplantextform.edit&id=0&id_bizplan=' .  $item->id_bizplan . '&abzats_tr=' . $abzats_tr . '&turi=AddNextHeadlineMundarija' . '&text_tr=' . $item->text_tr . '&id_current=' . $item->id, false, 0); ?>"><?php echo JText::_('COM_BP_BIZPLANTEXTS_ACTIONS_YANGI_OBZATS_QUSHISH_KEYINGI'); ?></a>
</br>

<a href="<?php echo JRoute::_('index.php?option=com_bp&task=bizplantextform.edit&id=0&id_bizplan=' .  $item->id_bizplan . '&abzats_tr=' . $abzats_tr . '&turi=AddNextText' . '&text_tr=' . $item->text_tr . '&id_current=' . $item->id, false, 0); ?>"><?php echo JText::_('COM_BP_BIZPLANTEXTS_ACTIONS_YANGI_TEXT_QUSHISH_KEYINGI'); ?></a>
	<?php endif; ?>

<?php endif; ?>




</br>
<strong>
<?php echo JText::_('COM_BP_BIZPLANTEXTS_ALTERNATIVE_COUNT'); ?>
<a href="<?php echo JRoute::_('index.php?option=com_bp&view=bizplantexts&id_bizplan='.(int) $id_bizplans.'&abzats_tr='.(int) $abzats_tr.'&text_tr='.(int) $text_tr); ?>">
<?php echo $alternative_count; ?></a>
</strong>
</br>

id:
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'bizplantexts.', $canCheckin); ?>
				<?php endif; ?>
				<a href="<?php echo JRoute::_('index.php?option=com_bp&view=bizplantext&id='.(int) $item->id); ?>">
				<?php echo $this->escape($item->id); ?></a>

user:
<a href="<?php echo JRoute::_('index.php?option=com_comprofiler&task=userprofile&user='.(int) JFactory::getUser($item->created_by)->id); ?>"><?php echo JFactory::getUser($item->created_by)->name; ?></a>


<?php if ($canEdit): ?>
<a href="<?php echo JRoute::_('index.php?option=com_bp&task=bizplantextform.edit&id=' . $item->id . '&id_bizplan=' . $item->id_bizplan . '&abzats_tr=' . $item->abzats_tr . '&text_tr=' . $item->text_tr, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i>Изменить</a>
<?php endif; ?>

<?php if ($canDelete): ?>
<a href="<?php echo JRoute::_('index.php?option=com_bp&task=bizplantextform.remove&id=' . $item->id . '&id_bizplan=' .  $item->id_bizplan . '&abzats_tr=' . $item->abzats_tr . '&text_tr=' . $item->text_tr . '&tr_change_relative_type=' . $item->turi . 'delete', false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i>Удалить</a>
<?php endif; ?>

<?php if ($canChange): ?>
state:
	<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_bp&task=bizplantext.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
	<?php if ($item->state == 1): ?>
		<i class="icon-publish"></i>
	<?php else: ?>
		<i class="icon-unpublish"></i>
	<?php endif; ?>
	</a>
<?php endif; ?>				


</div>
</span>




						

				<?php endif; ?>



				
<span>
					<?php echo $item->mundarija_text_content; ?>
</span>

</span>
				

					
					




<?php //endif; ?>

		<?php endforeach; ?>



			
				<?php echo $this->pagination->getListFooter(); ?>
			



	<?php 
	if ($this->pagination->total == 0) {
	$id_bizplan = JRequest::getVar('id_bizplan');
	if ($canCreate) : ?>
		<a href="<?php echo JRoute::_('index.php?option=com_bp&task=bizplantextform.edit&id=0&id_bizplan=' .  $id_bizplan . '&abzats_tr=0&turi=AddNextHeadlineMundarija&text_tr=1', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo JText::_('COM_BP_ADD_ITEM'); ?></a>
	<?php endif;
	}
	 ?>







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
<?php endif;

 ?>




