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



$document = JFactory::getDocument();
$document->setTitle($this->item->name);

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_bp.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_bp' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>





<style>
#map {
  height: 300px;
  width: 300px;
  border: 1px solid #000;
}
</style>

<?php 
if ($this->item->lat==NULL){
	$lat="41.258742912828446";
}
else
{
	$lat=$this->item->lat;
}

if ($this->item->lng==NULL){
	$lng="69.30062055584847";
}
else
{
	$lng=$this->item->lng;
}





$user = JFactory::getUser();
$db = JFactory::getDbo();
$query = 'SELECT cb_lat, cb_lng FROM #__comprofiler WHERE user_id='.$user->id;
$db->setQuery($query);
$row = $db->loadAssoc();
$cb_lat=$row['cb_lat'];
$cb_lng=$row['cb_lng'];


//echo $cb_lng;die;


?>










<?php
//ikkinchi map
?>
    <style>
      #right-panel {
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }

      #right-panel select, #right-panel input {
        font-size: 15px;
      }

      #right-panel select {
        width: 100%;
      }

      #right-panel i {
        font-size: 12px;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 400px;
        width: 50%;
      }
      #right-panel {
        float: right;
        width: 48%;
        padding-left: 2%;
      }
      #output {
        font-size: 11px;
      }
    </style>
    
    <script>
      function initMap() {
        var bounds = new google.maps.LatLngBounds;
        var markersArray = [];

        var origin1 = {lat: <?php echo "$cb_lat"; ?>, lng: <?php echo "$cb_lng"; ?>};
        var destinationA = {lat: <?php echo "$lat"; ?>, lng: <?php echo "$lng"; ?>};
        

        var destinationIcon = 'https://chart.googleapis.com/chart?' +
            'chst=d_map_pin_letter&chld=D|FF0000|000000';
        var originIcon = 'https://chart.googleapis.com/chart?' +
            'chst=d_map_pin_letter&chld=O|FFFF00|000000';
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: <?php echo "$lat"; ?>, lng: <?php echo "$lng"; ?>},
          zoom: 10
        });
        var geocoder = new google.maps.Geocoder;

        var service = new google.maps.DistanceMatrixService;
        service.getDistanceMatrix({
          origins: [origin1],
          destinations: [destinationA],
          travelMode: 'DRIVING',
          unitSystem: google.maps.UnitSystem.METRIC,
          avoidHighways: false,
          avoidTolls: false
        }, function(response, status) {
          if (status !== 'OK') {
            alert('Error was: ' + status);
          } else {
            var originList = response.originAddresses;
            var destinationList = response.destinationAddresses;
            var outputDiv = document.getElementById('output');
            outputDiv.innerHTML = '';
            deleteMarkers(markersArray);

            var showGeocodedAddressOnMap = function(asDestination) {
              var icon = asDestination ? destinationIcon : originIcon;
              return function(results, status) {
                if (status === 'OK') {
                  map.fitBounds(bounds.extend(results[0].geometry.location));
                  markersArray.push(new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    icon: icon
                  }));
                } else {
                  alert('Geocode was not successful due to: ' + status);
                }
              };
            };

            for (var i = 0; i < originList.length; i++) {
              var results = response.rows[i].elements;
              geocoder.geocode({'address': originList[i]},
                  showGeocodedAddressOnMap(false));
              for (var j = 0; j < results.length; j++) {
                geocoder.geocode({'address': destinationList[j]},
                    showGeocodedAddressOnMap(true));
                outputDiv.innerHTML += originList[i] + ' to ' + destinationList[j] +
                    ': ' + results[j].distance.text + ' in ' +
                    results[j].duration.text + '<br>';
              }
            }
          }
        });
      }

      function deleteMarkers(markersArray) {
        for (var i = 0; i < markersArray.length; i++) {
          markersArray[i].setMap(null);
        }
        markersArray = [];
      }
    </script>    

<?php
//ikkinchi map
?>



<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLAN_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLAN_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLAN_NAME'); ?></th>
			<td><?php echo $this->item->name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLAN_CATEGORY'); ?></th>
			<td><?php echo $this->item->category_title; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLAN_DESCRIPTION'); ?></th>
			<td><?php echo nl2br($this->item->description); ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLAN_LANG'); ?></th>
			<td><?php echo $this->item->lang; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLAN_ADDRESS'); ?></th>
			<td><?php echo $this->item->address; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLAN_LAT'); ?></th>
			<td><?php echo $this->item->lat; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLAN_LNG'); ?></th>
			<td><?php echo $this->item->lng; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLAN_VOTES_COUNT'); ?></th>
			<td><?php echo $this->item->votes_count; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLAN_LOYIHA_QIYMATI'); ?></th>
			<td><?php echo $this->item->loyiha_qiymati; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLAN_TUPLANGAN_MABLAG'); ?></th>
			<td><?php echo $this->item->tuplangan_mablag; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_BP_FORM_LBL_BIZPLAN_MEDIAMANAGER'); ?></th>
			<td><?php echo $this->item->mediamanager; ?></td>
		</tr>

	</table>

    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB49803-BJxmX5xFcnSVPXeE-cDVbiQRO4&callback=initMap">
    </script>
<h3><?php echo JText::_("COM_BP_LOCATION_OF_BUSINESS_PLAN"); ?></h3>
      <div id="output"></div>
      <div id="map"></div>


</br>
<button type="button" class="btn btn-default btn-lg btn-block	" onClick="location.href='<?php echo JRoute::_('index.php?option=com_bp&view=bizplantexts&id_bizplan='.(int) $this->item->id); ?>';"><?php echo JText::_('COM_BP_BIZPLANS_GO_TO_THE_BUSINESS_PLAN_TEXT'); ?></button>
</br>



</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_bp&task=bizplan.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_BP_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_bp.bizplan.'.$this->item->id)) : ?>

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
			<a href="<?php echo JRoute::_('index.php?option=com_bp&task=bizplan.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_BP_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>


	
<?php endif; ?>


