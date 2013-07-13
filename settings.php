<?php

OCP\User::checkLoggedIn();

OCP\Util::addScript( "owncloud_etherstorm", "user" );


$tmpl = new OCP\Template( 'owncloud_etherstorm', 'settings');

return $tmpl->fetchPage();
