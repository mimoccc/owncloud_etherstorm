<?php

/**
* 
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either 
* version 3 of the License, or any later version.
* 
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*  
* You should have received a copy of the GNU Affero General Public 
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
* 
*/

OCP\App::registerPersonal( 'owncloud_etherstorm', 'settings' );
OCP\App::registerAdmin( 'owncloud_etherstorm', 'settings2' );
OCP\App::addNavigationEntry( array( 
	'id' => 'owncloud_etherstorm',
	'order' => 78,
	'href' => OCP\Util::linkTo( 'owncloud_etherstorm', 'index.php' ),
	'icon' => OCP\Util::imagePath( 'owncloud_etherstorm', 'icon.png' ),
	'name' => 'Etherstorm'
));
