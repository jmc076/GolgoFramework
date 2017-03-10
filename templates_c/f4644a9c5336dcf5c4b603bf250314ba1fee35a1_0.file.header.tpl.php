<?php
/* Smarty version 3.1.31, created on 2017-03-10 11:14:49
  from "C:\xampp\htdocs\GolgoFramework\Private\Modules\GFStarterKit\Views\tpls\public\commons\header.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_58c27c993ebaa3_38954234',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f4644a9c5336dcf5c4b603bf250314ba1fee35a1' => 
    array (
      0 => 'C:\\xampp\\htdocs\\GolgoFramework\\Private\\Modules\\GFStarterKit\\Views\\tpls\\public\\commons\\header.tpl',
      1 => 1489140880,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58c27c993ebaa3_38954234 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
    <!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>GolgoFramework StarterKit</title>
	
	
	
	<link rel="stylesheet" href="modules/GFStarterKit/components/sweetalert2/dist/sweetalert2.min.css">
 	<link rel="stylesheet" href="modules/GFStarterKit/components/material-design-lite/material.min.css">
    <link rel="stylesheet" href="modules/GFStarterKit/css/loading.css">
    <link rel="stylesheet" href="modules/GFStarterKit/css/loading-chunk.css">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    
    <link rel="stylesheet" href="modules/GFStarterKit/css/admin-mdl-template.css">
    <link rel="stylesheet" href="modules/GFStarterKit/css/login.css">

    </head>
    <body class="login-content">
    <?php echo $_smarty_tpl->tpl_vars['csrfdata']->value;?>

	    <div id="loading">
			<div class="loader">
		 		<svg class="circular">
		    		<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
	 			</svg>
			</div>
		</div> 
<?php }
}
