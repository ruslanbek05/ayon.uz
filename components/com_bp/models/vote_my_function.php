<?php
//defined('_JEXEC') or die;

function vote($id_bizplantext, $id_users, $id_bizplans,$vote) {


    define( '_JEXEC', 1 );
    define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../../../' ));  
    require_once ( JPATH_BASE .'/includes/defines.php' );
    require_once ( JPATH_BASE .'/includes/framework.php' );

    $mainframe = JFactory::getApplication('site');




$user  = JFactory::getUser();
$id_users=$user->id;

if($id_users == 0)
{
    // Redirect the user
echo 'You need to login to vote!';
die;    
}
else 
{
    // you are logged in
    

}

/*$vote[0]="not_voted";
$vote[1]="up";
$vote[2]="neutral";
$vote[3]="down";
$vote[4]="vandal";*/

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('vote');
$query->from($db->quoteName('#__bp_vote'));
$query->where($db->quoteName('id_bizplantext')." = ".$id_bizplantext);
$query->where($db->quoteName('id_users')." = ".$id_users);
$query->where($db->quoteName('id_bizplans')." = ".$id_bizplans);
 
// Reset the query using our newly populated query object.
$db->setQuery($query);
$count = $db->loadResult();

//echo $count;die;

if ($count==null)
{
//	echo 'count null';die;
	vote_insert($id_bizplantext, $id_users, $id_bizplans,$vote);
	$previous_vote=$count;
	change_counts($id_bizplantext, $id_users, $id_bizplans,$vote,$previous_vote);	

}
else
{
//	echo 'count ne null';die;
	vote_update($id_bizplantext, $id_users, $id_bizplans,$vote);
	$previous_vote=$count;
	change_counts($id_bizplantext, $id_users, $id_bizplans,$vote,$previous_vote);	

}

bizplan_vote_count($id_bizplans);
//bizplan_vote_count2($id_bizplans,$vote);

alternative_define_count_when_only_have($id_bizplantext);
}




function vote_insert($id_bizplantext, $id_users, $id_bizplans,$vote) {


	
// Get a db connection.
$db = JFactory::getDbo();
 
// Create a new query object.
$query = $db->getQuery(true);
 
// Insert columns.
$columns = array('id_bizplantext', 'id_users', 'id_bizplans','vote');
 
// Insert values.
//$values = array(1001, $db->quote('custom.message'), $db->quote('Inserting a record using insert()'), 1);
$values = array($id_bizplantext, $id_users, $id_bizplans,$vote);
 
// Prepare the insert query.
$query
    ->insert($db->quoteName('#__bp_vote'))
    ->columns($db->quoteName($columns))
    ->values(implode(',', $values));
 
// Set the query using our newly populated query object and execute it.
$db->setQuery($query);
$db->execute();	
	

//add to table bpp_bp_bizplantext counts
	
//END//add to table bpp_bp_bizplantext counts
	
}



function vote_delete($id_bizplantext, $id_users, $id_bizplans) {

$db = JFactory::getDbo();
 
$query = $db->getQuery(true);
 
// delete all custom keys for user 1001.
$conditions = array(
    $db->quoteName('user_id') . ' = 1001', 
    $db->quoteName('profile_key') . ' = ' . $db->quote('custom.%')
);
 
$query->delete($db->quoteName('#__user_profiles'));
$query->where($conditions);
 
$db->setQuery($query);
 
$result = $db->execute();

}





function vote_update($id_bizplantext, $id_users, $id_bizplans,$vote) {

$db = JFactory::getDbo();
 
$query = $db->getQuery(true);
 
// Fields to update.
$fields = array(
    $db->quoteName('vote') . ' = ' . $vote //,
    //$db->quoteName('ordering') . ' = 2'
);
 
// Conditions for which records should be updated.
$conditions = array(
    $db->quoteName('id_bizplantext') . ' = '. $id_bizplantext, 
    $db->quoteName('id_users') . ' = ' . $id_users,
    $db->quoteName('id_bizplans') . ' = ' . $id_bizplans,
    
);
 
$query->update($db->quoteName('#__bp_vote'))->set($fields)->where($conditions);
 
$db->setQuery($query);
 
$result = $db->execute();


//add to table bpp_bp_bizplantext counts
	//change_counts($id_bizplantext, $id_users, $id_bizplans,$vote,$previous_vote);
//END//add to table bpp_bp_bizplantext counts


	
}


function change_counts($id_bizplantext, $id_users, $id_bizplans,$vote,$previous_vote) {
	if ($previous_vote<>NULL)
	{
//		echo 'previous null emas';die;
		//decrease
		change_counts_decrease($id_bizplantext, $id_users, $id_bizplans,$vote,$previous_vote);
		
	}
//	echo 'previous null';die;
	//increase
	change_counts_increase($id_bizplantext, $id_users, $id_bizplans,$vote,$previous_vote);
}

function change_counts_decrease($id_bizplantext, $id_users, $id_bizplans,$vote,$previous_vote) {

$count=get_count($id_bizplantext,$previous_vote);

	if ($previous_vote==0) {
		$field_name='neutral_count';
	}
	elseif ($previous_vote==1) {
		$field_name='up_count';
	}
	elseif ($previous_vote==2) {
		$field_name='neutral_count';
	}
	elseif ($previous_vote==3) {
		$field_name='down_count';
	}
	elseif ($previous_vote==4) {
		$field_name='vandal_count';
	}

$db = JFactory::getDbo();
$query = $db->getQuery(true);
// Fields to update.
$fields = array(
    $db->quoteName($field_name) . ' = ' . ($count - 1) //,
    //$db->quoteName('ordering') . ' = 2'
);
 
// Conditions for which records should be updated.
$conditions = array(
    $db->quoteName('id') . ' = '. $id_bizplantext, 
    
);
 
$query->update($db->quoteName('#__bp_bizplantext'))->set($fields)->where($conditions);
 
$db->setQuery($query);
 
$result = $db->execute();

}

function change_counts_increase($id_bizplantext, $id_users, $id_bizplans,$vote,$previous_vote) {


	$count=get_count($id_bizplantext,$vote);
//	$count=0;
//		echo 'previous null:'.$count;die;
	

	if ($vote==0) {
		$field_name='neutral_count';
	}
	elseif ($vote==1) {
		$field_name='up_count';
	}
	elseif ($vote==2) {
		$field_name='neutral_count';
	}
	elseif ($vote==3) {
		$field_name='down_count';
	}
	elseif ($vote==4) {
		$field_name='vandal_count';
	}

$db = JFactory::getDbo();
$query = $db->getQuery(true);
// Fields to update.
$fields = array(
    $db->quoteName($field_name) . ' = ' . ($count + 1) //,
    //$db->quoteName('ordering') . ' = 2'
);
 
// Conditions for which records should be updated.
$conditions = array(
    $db->quoteName('id') . ' = '. $id_bizplantext, 
    
);
 
$query->update($db->quoteName('#__bp_bizplantext'))->set($fields)->where($conditions);
 
$db->setQuery($query);
 
$result = $db->execute();


if (((($count + 1)>2) and $vote==4) or ((($count + 1)>4) and $vote==3)) {
	change_state_to_zero($id_bizplantext);
}

}

function get_count($id_bizplantext,$previous_vote) {


	if ($previous_vote==0) {
		$field_name='neutral_count';
	}
	elseif ($previous_vote==1) {
		$field_name='up_count';
	}
	elseif ($previous_vote==2) {
		$field_name='neutral_count';
	}
	elseif ($previous_vote==3) {
		$field_name='down_count';
	}
	elseif ($previous_vote==4) {
		$field_name='vandal_count';
	}


	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select($field_name);
	$query->from($db->quoteName('#__bp_bizplantext'));
	$query->where($db->quoteName('id')." = ".$id_bizplantext);
	 
	// Reset the query using our newly populated query object.
	$db->setQuery($query);
	$count = $db->loadResult();

	return $count;

}

function change_state_to_zero($id_bizplantext) {

$db = JFactory::getDbo();
$query = $db->getQuery(true);
// Fields to update.
$fields = array(
    $db->quoteName('state') . ' = 0' //,
    //$db->quoteName('ordering') . ' = 2'
);
 
// Conditions for which records should be updated.
$conditions = array(
    $db->quoteName('id') . ' = '. $id_bizplantext, 
    
);
 
$query->update($db->quoteName('#__bp_bizplantext'))->set($fields)->where($conditions);
 
$db->setQuery($query);
 
$result = $db->execute();

	
}


function get_total_count_after_vote($id_bizplantext) {


$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from($db->quoteName('#__bp_bizplantext'));
$query->where($db->quoteName('id')." = ".$db->quote($id_bizplantext));
 
$db->setQuery($query);
$row = $db->loadAssoc();


$vote_count[0]="";
$vote_count[1]=$row['up_count'];
$vote_count[2]=$row['neutral_count'];
$vote_count[3]=$row['down_count'];
$vote_count[4]=$row['vandal_count'];





return $vote_count;
	

}

function alternative_define_count($id_bizplans,$abzats_tr,$text_tr){

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from($db->quoteName('#__bp_bizplantext'));
$query->where($db->quoteName('abzats_tr')." = ".$db->quote($abzats_tr));
$query->where($db->quoteName('text_tr')." = ".$db->quote($text_tr));
$query->where($db->quoteName('id_bizplan')." = ".$db->quote($id_bizplans));
$query->order('id ASC');
 
$db->setQuery($query);
$rows = $db->loadAssocList();
$count=count($rows)-1;
if ($count==NULL) {
	$count=0;
}

$state_1_selected_id=0;
$current_id_rating=0;
$highest_rating=0;

foreach ($rows as $row) {
	
	$current_id_rating = 1*$row['up_count'] - 1*$row['down_count'] - 2*$row['vandal_count'];


	
	if ($highest_rating<=$current_id_rating){
		$highest_rating=$current_id_rating;
		$state_1_selected_id=$row['id'];
	}
	
	
	
	//echo 'current_id_rating: '.$current_id_rating.' id: '.$row['id'].' state_1_selected_id: '.$state_1_selected_id.'</br>';
	
	$current_id_rating=0;
	alternative_change_count($row['id'],$count,0);
}
	alternative_change_count($state_1_selected_id,$count,1);

//echo 'current_id_rating: '.$current_id_rating.' id: '.$row['id'].' state_1_selected_id: '.$state_1_selected_id;
//die;

}

function alternative_change_count($id,$count,$state) {

$db = JFactory::getDbo();
$query = $db->getQuery(true);
 
// Fields to update.
$fields = array(
    $db->quoteName('alternative_count') . ' = ' . $count,
    $db->quoteName('state') . ' = ' . $state //,
    //$db->quoteName('ordering') . ' = 2'
);
 
// Conditions for which records should be updated.
$conditions = array(
    $db->quoteName('id') . ' = '. $id, 
);
 
$query->update($db->quoteName('#__bp_bizplantext'))->set($fields)->where($conditions);
 
$db->setQuery($query);
 
$result = $db->execute();

	
}


function tr_change_relative($id_bizplans,$abzats_tr_for_alt,$text_tr_for_alt,$tr_change_relative_type) {



$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from($db->quoteName('#__bp_bizplantext'));
$query->where($db->quoteName('id_bizplan')." = ".$db->quote($id_bizplans));
if (($tr_change_relative_type=='mundarija') or ($tr_change_relative_type=='mundarijadelete')){
	$query->where($db->quoteName('abzats_tr')." >= ".$db->quote($abzats_tr_for_alt));
	}
	else
	{
		$query->where($db->quoteName('abzats_tr')." = ".$db->quote($abzats_tr_for_alt));
		$query->where($db->quoteName('text_tr')." >= ".$db->quote($text_tr_for_alt));
		
	}

 
$db->setQuery($query);
$rows = $db->loadAssocList();
/*$count=count($rows)-1;
if ($count==NULL) {
	$count=0;
}*/

//print_r($rows);die;


foreach ($rows as $row) {
$abzats_tr=$row['abzats_tr'];
$text_tr=$row['text_tr'];
	tr_change_relative2($row['id'],$tr_change_relative_type,$abzats_tr,$text_tr);
}
	
}


function tr_change_relative2($id,$tr_change_relative_type,$abzats_tr,$text_tr) {

//echo 222;die;
$db = JFactory::getDbo();
$query = $db->getQuery(true);

if ($tr_change_relative_type=='mundarija') {
$abzats_tr=$abzats_tr+1;
// Fields to update.
$fields = array(
    $db->quoteName('abzats_tr') . ' = ' . $abzats_tr //,
);
}
elseif ($tr_change_relative_type=='text') 
{
$text_tr=$text_tr+1;
// Fields to update.
$fields = array(
    $db->quoteName('text_tr') . ' = ' . $text_tr //,
);	
}
elseif ($tr_change_relative_type=='mundarijadelete') {
//echo 111;die;
$abzats_tr=$abzats_tr-1;
// Fields to update.
$fields = array(
    $db->quoteName('abzats_tr') . ' = ' . $abzats_tr //,
);
}
elseif ($tr_change_relative_type=='textdelete') 
{
$text_tr=$text_tr-1;
// Fields to update.
$fields = array(
    $db->quoteName('text_tr') . ' = ' . $text_tr //,
);	
}
 
// Conditions for which records should be updated.
$conditions = array(
    $db->quoteName('id') . ' = '. $id, 
);
 
$query->update($db->quoteName('#__bp_bizplantext'))->set($fields)->where($conditions);
 
$db->setQuery($query);
 
$result = $db->execute();

	
}

function bizplan_vote_count($id_bizplans) {




$vote_count = bizplan_vote_count_get_count($id_bizplans);
//echo "shu erga keldi!W".$vote_count."W";die;

	
$db = JFactory::getDbo();
 
$query = $db->getQuery(true);
 
// Fields to update.
$fields = array(
    $db->quoteName('votes_count') . ' = ' . $vote_count //,
    //$db->quoteName('ordering') . ' = 2'
);
 
// Conditions for which records should be updated.
$conditions = array(
    $db->quoteName('id') . ' = ' . $id_bizplans
);
 
$query->update($db->quoteName('#__bp_bizplans'))->set($fields)->where($conditions);
 
$db->setQuery($query);
 
$result = $db->execute();
	
}

function bizplan_vote_count_get_count($id_bizplans) {


	
$db = JFactory::getDbo();
$query = $db->getQuery(true);
/*$query->select('vote');
$query->from($db->quoteName('#__bp_bizplantext'));
$query->group($db->quoteName('id_bizplan'));
$query->where($db->quoteName('id_bizplantext')." = ".$id_bizplantext);
$query->where($db->quoteName('id_users')." = ".$id_users);
$query->where($db->quoteName('id_bizplans')." = ".$id_bizplans);
*/

$query="SELECT `id_bizplan`, Sum(`up_count`) AS `up`, Sum(`down_count`) AS `down`, Sum(`vandal_count`) AS `vandal` FROM #__bp_bizplantext WHERE (((id_bizplan)=".$id_bizplans.")) GROUP BY `id_bizplan`"; 

// Reset the query using our newly populated query object.
$db->setQuery($query);



//$count = $db->loadResult();	
$row = $db->loadAssoc();



//Array ( [id_bizplan] => 1 [up] => 20 [down] => 1 [vandal] => 0 )
//print_r($row);die;


//echo $row['total'];die;
//echo $row['up'] - $row['down'] - 2*$row['vandal'];die;

	return $row['up'] - $row['down'] - 2*$row['vandal'];
	
}

function bizplan_vote_count2($id_bizplans, $vote) {
	$vote_count = bizplan_vote_count_get_count2($id_bizplans);

	if ($vote==0) {
		$vote_count=$vote_count;
	}
	elseif ($vote==1) {
		$vote_count=$vote_count+1;
	}
	elseif ($vote==2) {
		$vote_count=$vote_count;
	}
	elseif ($vote==3) {
		$vote_count=$vote_count-1;
	}
	elseif ($vote==4) {
		$vote_count=$vote_count-2;
	}
	
	
	
$db = JFactory::getDbo();
 
$query = $db->getQuery(true);
 
// Fields to update.
$fields = array(
    $db->quoteName('votes_count') . ' = ' . $vote_count //,
    //$db->quoteName('ordering') . ' = 2'
);
 
// Conditions for which records should be updated.
$conditions = array(
    $db->quoteName('id') . ' = ' . $id_bizplans
);
 
$query->update($db->quoteName('#__bp_bizplans'))->set($fields)->where($conditions);
 
$db->setQuery($query);
 
$result = $db->execute();

}

function bizplan_vote_count_get_count2($id_bizplans) {
	
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select('votes_count');
	$query->from($db->quoteName('#__bp_bizplans'));
	$query->where($db->quoteName('id')." = ".$id_bizplans);
	 
	// Reset the query using our newly populated query object.
	$db->setQuery($query);
	$count = $db->loadResult();

	return $count;
	
}

function alternative_define_count_when_only_have($id_bizplantext){

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from($db->quoteName('#__bp_bizplantext'));
$query->where($db->quoteName('id')." = ".$db->quote($id_bizplantext));

 
$db->setQuery($query);
$rows = $db->loadAssocList();

foreach ($rows as $row) {
$abzats_tr=$row['abzats_tr'];
$text_tr=$row['text_tr'];
$id_bizplans=$row['id_bizplan'];
alternative_define_count($id_bizplans,$abzats_tr,$text_tr);	
}




}



?>