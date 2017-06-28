<!DOCTYPE html>
    <!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="description" content="GolgoFramework, a php framework for making web apps. GF Starter kit, a wordpress like php framework for easy web dev">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
	    <title>GolgoFramework Starter Kit</title>
	

	    <!-- Add to homescreen for Chrome on Android -->
	    <meta name="mobile-web-app-capable" content="yes">
	    <link rel="icon" sizes="192x192" href="modules/GFStarterKit/images/android-desktop.png">
	
	    <!-- Add to homescreen for Safari on iOS -->
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
	    <link rel="apple-touch-icon-precomposed" href="modules/GFStarterKit/images/ios-desktop.png">
	
	    <!-- Tile icon for Win8 (144x144 + tile color) -->
	    <meta name="msapplication-TileImage" content="modules/GFStarterKit/images/touch/ms-touch-icon-144x144-precomposed.png">
	    <meta name="msapplication-TileColor" content="#3372DF">
	
	    <link rel="shortcut icon" href="modules/GFStarterKit/images/favicon.png">
	
	    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
	    <!--
	    <link rel="canonical" href="http://www.example.com/">
	    -->
		
		
		<link rel="stylesheet" href="modules/GFStarterKit/components/sweetalert2/dist/sweetalert2.min.css">
	    <link rel="stylesheet" href="modules/GFStarterKit/css/loading.css">
	    <link rel="stylesheet" href="modules/GFStarterKit/css/loading-chunk.css">
	    
	    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
	    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	     <link rel="stylesheet" href="modules/GFStarterKit/css/material-cyan-blue-template.css">
	    <link rel="stylesheet" href="modules/GFStarterKit/css/admin-mdl-template.css">
	    <link rel="stylesheet" href="modules/GFStarterKit/css/styles.css">
	    <link rel="stylesheet" href="modules/GFStarterKit/css/data-tables.css">
  		<link rel="stylesheet" href="modules/GFStarterKit/css/getmdl-select.min.css" >
  		<link rel="stylesheet" href="modules/GFStarterKit/css/cards.css" >
    </head>
    <body>
    {$csrfdata}
	    <div id="loading">
			<div class="loader">
		 		<svg class="circular">
		    		<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
	 			</svg>
			</div>
		</div> 
		 <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
