<?php
defined( '_JEXEC') or die('Restricted access'); 


?>
<h1><span class="badge badge-secondary"><?php echo JText::_('MOD_FEATURED_PROJECT_ON_MAIN_PAGE_MODULE_NAME'); ?></span></h1>
<?php

$user       = JFactory::getUser();
$userId     = $user->get('id');
$canCreate  = $user->authorise('core.create', 'com_bp') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'bizplanform.xml');
$canEdit    = $user->authorise('core.edit', 'com_bp') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'bizplanform.xml');
$canCheckin = $user->authorise('core.manage', 'com_bp');
$canChange  = $user->authorise('core.edit.state', 'com_bp');
$canDelete  = $user->authorise('core.delete', 'com_bp');
$canView  = $user->authorise('core.view', 'com_bp');





// Get a db connection.
$db = JFactory::getDbo();

// Create a new query object.
$query = $db->getQuery(true);

// Select all records from the user profile table where key begins with "custom.".
// Order it by the ordering field.
$query->select($db->quoteName(array('*')));
$query->from($db->quoteName('#__bp_bizplans'));
$query->where($db->quoteName('feautured_0_255') . ' > 0');
$query->limit('9');

// Reset the query using our newly populated query object.
$db->setQuery($query);

$rows = $db->loadAssocList();



?>
<div class="album py-5 bg-light">
    <div class="container">
      <div class="row">
<?php
foreach($rows as $row){
	if (($row['loyiha_qiymati'] == 0) or (is_null($row['loyiha_qiymati']))){
	$tuplangan_mablag_foizi = 0;
	$loyihaga_pul_toplash_ochilmagan = 0;
	
}
else{
	//print_r($item->loyiha_qiymati);die;
	$tuplangan_mablag_foizi = number_format(($row['tuplangan_mablag'] / $row['loyiha_qiymati']) * 100, 0);
	$loyihaga_pul_toplash_ochilmagan = 1;
}
?>	
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm" >
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title><?php echo $row['mediamanager']; ?></title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text><image xlink:href="http://www.ayon.uz/<?php echo $row['mediamanager']; ?>" x="0" y="0" height="100%" width="100%"/></svg>
            <div class="card-body">
              <p class="card-text"><?php echo $row['name']; ?></p>
              <?php if($loyihaga_pul_toplash_ochilmagan==1){ ?><div class="progress">
  <div class="progress-bar" role="progressbar" style="width: <?php echo $tuplangan_mablag_foizi; ?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="50"><?php echo $tuplangan_mablag_foizi; ?>%</div>
</div>
<p class="card-text"><?php echo number_format($row['tuplangan_mablag'],2,',',' '); ?><?php echo JText::_('MOD_FEATURED_PROJECT_ON_MAIN_PAGE_HOW_MUCH_COLLECTED'); ?><span class="badge badge-secondary pointer" onClick="location.href='<?php echo JRoute::_('index.php?option=com_bp&view=payedsums&project_id='.(int) $row['id']); ?>';"><?php echo JText::_('MOD_FEATURED_PROJECT_ON_MAIN_PAGE_PAYERS'); ?></span></p><?php } ?>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary" onClick="location.href='<?php echo JRoute::_('index.php?option=com_payme&view=paymeform&product_ids='.(int) $row['id']); ?>';"><?php echo JText::_('MOD_FEATURED_PROJECT_ON_MAIN_PAGE_PAY_TO_PROJECT'); ?></button>
                  <button type="button" class="btn btn-sm btn-outline-secondary" onClick="location.href='<?php echo JRoute::_('index.php?option=com_bp&view=bizplantexts&id_bizplan='.(int) $row['id']); ?>';"><?php echo JText::_('MOD_FEATURED_PROJECT_ON_MAIN_PAGE_DETAIL'); ?></button>
                  						<?php if ($canEdit): ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" onClick="location.href='<?php echo JRoute::_('index.php?option=com_bp&task=bizplanform.edit&id=' . $row['id'], false, 2); ?>';"><?php echo JText::_('MOD_FEATURED_PROJECT_ON_MAIN_PAGE_EDIT'); ?></button>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<button type="button" class="btn btn-sm btn-outline-secondary" onClick="location.href='<?php echo JRoute::_('index.php?option=com_bp&task=bizplanform.remove&id=' . $row['id'], false, 2); ?>';"><?php echo JText::_('MOD_FEATURED_PROJECT_ON_MAIN_PAGE_DELETE'); ?></button>
						<?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
<?php
}
?>
      </div>
    </div>
  </div>        
<a href="index.php?option=com_bp&view=bizplans" class="btn btn-info" role="button"><?php echo JText::_('MOD_FEATURED_PROJECT_ON_MAIN_PAGE_SEE_ALL_PROJECTS'); ?></a>