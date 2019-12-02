<?php
/*
//define( '_JEXEC', 1 );
define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../../../../..' ));

require_once ( JPATH_BASE . '/includes/defines.php' );
require_once ( JPATH_BASE . '/includes/framework.php' );

$app = JFactory::getApplication('site');
$app->initialise();

$lang = JFactory::getLanguage();
$tag  = $lang->getTag();

print_r($tag);*/
//echo "mmmmmmm";die;
$voted_index = filter_var ( $_REQUEST['v'], FILTER_SANITIZE_NUMBER_INT);
$id_bizplantext = filter_var ( $_REQUEST['id_bizplantext'], FILTER_SANITIZE_NUMBER_INT);
$id_bizplans = filter_var ( $_REQUEST['id_bizplans'], FILTER_SANITIZE_NUMBER_INT);
$userId = filter_var ( $_REQUEST['w'], FILTER_SANITIZE_NUMBER_INT);
$alternative_count = filter_var ( $_REQUEST['ac'], FILTER_SANITIZE_NUMBER_INT);
$abzats_tr = filter_var ( $_REQUEST['abzats_tr'], FILTER_SANITIZE_NUMBER_INT);
$text_tr = filter_var ( $_REQUEST['text_tr'], FILTER_SANITIZE_NUMBER_INT);

include_once('../../../models/vote_my_function.php');
vote($id_bizplantext,$userId,$id_bizplans,$voted_index);
$vote_count=get_total_count_after_vote($id_bizplantext);
//echo 'uuuuuraaaa';die;

$poll=filter_var ( $_REQUEST['poll'], FILTER_SANITIZE_NUMBER_INT);
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



<div id=<?php echo $poll?>>
<?php for ($i = 0; $i <= 4; $i++) { ?>
<input type="checkbox" name="vote_n" value="<?php echo $i; ?>" onclick="getVote(this.value,'<?php echo $poll?>','<?php echo $id_bizplantext?>','<?php echo $id_bizplans?>','<?php echo $poll?>','<?php echo $userId?>','<?php echo $alternative_count?>')" <?php if ($i==$voted_index) {echo "checked";} else {echo "unchecked";} ;?>>
<?php echo $vote[$i]; ?>
<?php echo ' - всего '.$vote_count[$i].'</br>'; }?>
</div>