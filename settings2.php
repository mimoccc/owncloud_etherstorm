<?php

OCP\User::checkAdminUser();

OCP\Util::addScript( "owncloud_etherstorm", "admin" );


$tmpl = new OCP\Template( 'owncloud_etherstorm', 'settings2');

return $tmpl->fetchPage();
