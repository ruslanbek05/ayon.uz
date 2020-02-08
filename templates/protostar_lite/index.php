<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var JDocumentHtml $this */

$app  = JFactory::getApplication();
$user = JFactory::getUser();

// Output as HTML5
$this->setHtml5(true);

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');

if ($task === 'edit' || $layout === 'form')
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}













// Add template js
JHtml::_('script', 'template.js', array('version' => 'auto', 'relative' => true));

// Add html5 shiv
//JHtml::_('script', 'jui/html5.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

// Add Stylesheets
JHtml::_('stylesheet', 'template.css', array('version' => 'auto', 'relative' => true));
/*
// Use of Google Font
if ($this->params->get('googleFont'))
{
	JHtml::_('stylesheet', 'https://fonts.googleapis.com/css?family=' . $this->params->get('googleFontName'));
	$this->addStyleDeclaration("
	h1, h2, h3, h4, h5, h6, .site-title {
		font-family: '" . str_replace('+', ' ', $this->params->get('googleFontName')) . "', sans-serif;
	}");
}

// Template color
if ($this->params->get('templateColor'))
{
	$this->addStyleDeclaration('
	body.site {
		border-top: 3px solid ' . $this->params->get('templateColor') . ';
		background-color: ' . $this->params->get('templateBackgroundColor') . ';
	}
	a {
		color: ' . $this->params->get('templateColor') . ';
	}
	.nav-list > .active > a,
	.nav-list > .active > a:hover,
	.dropdown-menu li > a:hover,
	.dropdown-menu .active > a,
	.dropdown-menu .active > a:hover,
	.nav-pills > .active > a,
	.nav-pills > .active > a:hover,
	.btn-primary {
		background: ' . $this->params->get('templateColor') . ';
	}');
}

// Check for a custom CSS file
JHtml::_('stylesheet', 'user.css', array('version' => 'auto', 'relative' => true));

// Check for a custom js file
JHtml::_('script', 'user.js', array('version' => 'auto', 'relative' => true));

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);


// Adjusting content width
$position7ModuleCount = $this->countModules('position-7');
$position8ModuleCount = $this->countModules('position-8');

if ($position7ModuleCount && $position8ModuleCount)
{
	$span = 'span6';
}
elseif ($position7ModuleCount && !$position8ModuleCount)
{
	$span = 'span9';
}
elseif (!$position7ModuleCount && $position8ModuleCount)
{
	$span = 'span9';
}
else
{
	$span = 'span12';
}

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="' . JUri::root() . $this->params->get('logoFile') . '" alt="' . $sitename . '" />';
}
elseif ($this->params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . htmlspecialchars($this->params->get('sitetitle'), ENT_COMPAT, 'UTF-8') . '</span>';
}
else
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . $sitename . '</span>';
}
*/
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />



    	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	
	
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>	
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>	


	<?php //<jdoc:include type="head" />?>
	
	<jdoc:include type="head" />


	<title><?php echo $this->getTitle(); ?></title>
	

    
    
</head>
<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '')
	. ($this->direction === 'rtl' ? ' rtl' : '');
?>">
	<!-- Body -->




<?php
// Get default menu - JMenu object, look at JMenu api docs
$menu = JFactory::getApplication()->getMenu();


// Get menu items - array with menu items
$menu_items = $menu->getMenu();
//print_r($menu_items);die;
$menu_items_2 = $menu->getMenu();
$menu_items_3 = $menu->getMenu();

// Look through the menu structure, once you understand it
// do a loop and find the link that you need.
//var_dump($items);

$has_child_menu = 0;
$closing_end_li_is_open = 0;

?>


	
<nav class="navbar navbar-expand-md navbar-dark bg-dark top">
 <a class="navbar-brand" href="https://www.ayon.uz">Ayon.uz</a>
  <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      <?php foreach ($menu_items as $i => $menu_item) { 
      if ($menu_item->level == 1){
      	$has_sub_menu = 0;
      	foreach ($menu_items_2 as $j => $menu_item_2){
      		if ($menu_item->id == $menu_item_2->parent_id){
				$has_sub_menu = 1;
				
				//echo "id:".$menu_item->alias." has child";die;
				break;
			}
			//print_r("eeeeeee");die;
		}
		if ($has_sub_menu == 0){
			
		
      	  ?>
	  
	  <li class="nav-item">
        <a class="nav-link" href="<?php echo $menu_item->link; ?>"><?php echo $menu_item->title; ?></a>
        </li>
	  <?php }
	  else{
	  	//скрываем меню ползователь
	  	if ($menu_item->id <> 102){
			
		
	  	?>
<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $menu_item->title; ?></a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
          <?php foreach ($menu_items_3 as $j => $menu_item_3){
          	if ($menu_item->id == $menu_item_3->parent_id) {
?>
<a class="dropdown-item" href="<?php echo $menu_item_3->link; ?>"><?php echo $menu_item_3->title; ?></a>
<?php
}
          } ?>
</div>
</li>	  	
	  	<?php
	  } }}?>
<?php }
//$user = JFactory::getUser();        // Get the user object
//$app  = JFactory::getApplication(); // Get the application
if ($user->id != 0)
{
    // you are logged in 
    //print_r($user);die;
?>
<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $user->name; ?></a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
          <a class="dropdown-item" href="index.php?option=com_users&view=profile&layout=edit"><?php echo JText::_('TPL_PROTOSTAR_CHANGE_PERSONAL_DATA'); ?></a>
          <a class="dropdown-item" href="index.php?option=com_users&view=profile"><?php echo JText::_('TPL_PROTOSTAR_PROFILE'); ?></a>
          <a class="dropdown-item" href="index.php?option=com_bp&view=payedsums"><?php echo JText::_('TPL_PROTOSTAR_PAYEDSUMS'); ?></a>
          <a class="dropdown-item" href="index.php?option=com_users&view=login&layout=logout&task=user.menulogout"><?php echo JText::_('TPL_PROTOSTAR_LOGOUT'); ?></a>
        </div>
</li>    
<?php }
else 
{
    // not logged in 
?>
<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo JText::_('TPL_PROTOSTAR_LOGIN_AUTHENTICATION'); ?></a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
          <a class="dropdown-item" href="index.php?option=com_users&view=login"><?php echo JText::_('TPL_PROTOSTAR_LOGIN'); ?></a>
          <a class="dropdown-item" href="index.php?option=com_users&view=registration"><?php echo JText::_('TPL_PROTOSTAR_REGISTRATION'); ?></a>
        </div>
</li>    
<?php }
?>
<li class="nav-item">
    <a href="https://t.me/ayon_uz"><img src="https://www.ayon.uz/images/t_logo40.png" alt="telegram" width="40" height="40"></a>
</li>
</ul>
</div>
</nav>
	
























<div class="container">
<jdoc:include type="message" />
<jdoc:include type="modules" name="debug" style="none" />
<jdoc:include type="modules" name="menu" style="none" />
<jdoc:include type="modules" name="banner" style="none" />
<jdoc:include type="modules" name="jumbotron" style="none" />
<jdoc:include type="modules" name="position-0" style="none" />
<jdoc:include type="modules" name="position-1" style="none" />
<jdoc:include type="modules" name="position-2" style="none" />
<jdoc:include type="modules" name="position-3" style="none" />
<jdoc:include type="component" />
<jdoc:include type="modules" name="position-4" style="none" />
<jdoc:include type="modules" name="position-5" style="none" />
<jdoc:include type="modules" name="position-6" style="none" />
<jdoc:include type="modules" name="position-7" style="none" />
<jdoc:include type="modules" name="position-8" style="none" />
<jdoc:include type="modules" name="position-9" style="none" />
<jdoc:include type="modules" name="position-10" style="none" />
<jdoc:include type="modules" name="position-11" style="none" />
<jdoc:include type="modules" name="position-12" style="none" />
<jdoc:include type="modules" name="position-13" style="none" />
<jdoc:include type="modules" name="position-14" style="none" />
<jdoc:include type="modules" name="footer" style="none" />
</div>

<footer class="text-muted">
  <div class="container">
</br>
</br>
</br>
</br>
</br>
</br>
</br>
<hr />
&copy; <?php echo date('Y'); ?> <?php echo $sitename; ?>
 | <a href="https://t.me/qodirov_ruslan">Контакт</a>
  </div>
</footer>

</body>
</html>

