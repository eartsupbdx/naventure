<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title></title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Anton&display=swap" rel="stylesheet">
  <link href="<?php echo get_stylesheet_directory_uri(); ?>/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="<?php echo get_stylesheet_directory_uri(); ?>/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>
  <nav class=" naventurenav" role="navigation">
    <div class="nav-wrapper container">
      <a id="logo-container" href="/" class="brand-logo"><img style="margin-top: 8px;" src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo_256.png" height="50"></a>
      <ul class="right hide-on-med-and-down">
          <li><a href="/a-propos-de-naventure/">A propos</a></li>
          <li><a href="/collections/">Collections</a></li>
          <li><a href="#">Encyclopédie</a></li>
          <li><a target="_blank" href="https://github.com/eartsupbdx/naventure">DIY</a></li>
          <?php
          if( is_user_logged_in() ){
          ?>
          <li><a href="/mon-compte">Mon compte</a></li>
          <?php
            } else {
          ?>
          <li><a href="/wp-login.php">Connection</a></li>
          <li><a href="/wp-login.php?action=register">Créer un compte</a></li>
          <?php
          }
          ?>
          
      </ul>

      <ul id="nav-mobile" class="side-nav">
        <li><a href="#">Navbar Link</a></li>
      </ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
  </nav>