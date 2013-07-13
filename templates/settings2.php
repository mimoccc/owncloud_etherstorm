<?php 
//OCP\Util::addScript( "owncloud_etherstorm", "jquery1.9.1" );
//OCP\Util::addScript( "owncloud_etherstorm", "sjcl" );
//OCP\Util::addScript( "owncloud_etherstorm", "jquery.cookie" );
//OCP\Util::addScript( "owncloud_etherstorm", "opentooltip" );
//OCP\Util::addScript( "owncloud_etherstorm", "farbtastic" );


//OCP\Util::addScript( "owncloud_etherstorm", "jquery.noty" );
//OCP\Util::addScript( "owncloud_etherstorm", "layouts/bottom" );
//OCP\Util::addScript( "owncloud_etherstorm", "layouts/bottomCenter" );
//OCP\Util::addScript( "owncloud_etherstorm", "layouts/bottomLeft" );
//OCP\Util::addScript( "owncloud_etherstorm", "layouts/bottomRight" );
//OCP\Util::addScript( "owncloud_etherstorm", "layouts/center" );
//OCP\Util::addScript( "owncloud_etherstorm", "layouts/centerLeft" );
//OCP\Util::addScript( "owncloud_etherstorm", "layouts/centerRight" );
//OCP\Util::addScript( "owncloud_etherstorm", "layouts/inline" );
//OCP\Util::addScript( "owncloud_etherstorm", "layouts/top" );
//OCP\Util::addScript( "owncloud_etherstorm", "layouts/topCenter" );
//OCP\Util::addScript( "owncloud_etherstorm", "layouts/topLeft" );
//OCP\Util::addScript( "owncloud_etherstorm", "layouts/topRight" );
//OCP\Util::addScript( "owncloud_etherstorm", "jquery.noty.default" );

//OCP\Util::addScript( "owncloud_etherstorm", "validationengine/jquery.validationEngine" );
//OCP\Util::addScript( "owncloud_etherstorm", "validationengine/jquery.validationEngine-de" );

//OCP\Util::addScript( "owncloud_etherstorm", "admin" ); 

//OCP\Util::addStyle( "owncloud_etherstorm", "opentooltip" );
//OCP\Util::addStyle( "owncloud_etherstorm", "farbtastic" );

//OCP\Util::addStyle( "owncloud_etherstorm", "inputvalidation/customMessages" );
//OCP\Util::addStyle( "owncloud_etherstorm", "inputvalidation/template" );
//OCP\Util::addStyle( "owncloud_etherstorm", "inputvalidation/validationEngine.jquery" );
OCP\Util::addStyle( "owncloud_etherstorm", "etherstorm-settings" );
//sjcl.encrypt($.cookie("etherstorm-encryption"), "data");
//sjcl.decrypt($.cookie("etherstorm-encryption"), "encrypted-data");
 ?>

<div id="sizer">
    <form action="#" method="get" accept-charset="utf-8" id="options">
	<legend> Etherstorm Configuration </legend>
        <fieldset class="checkboxes">

        </fieldset>
        
		
		<fieldset class="inputs">
			
			
			<label for="etherpadurl" id="etherpadurl-tag" class="floatlabel" >Etherpad-Url*</label><input type="text" name="etherpadurl" id="etherpadurl" value="" />
            <label for="apikey" id="apikey-tag" class="floatlabel">API-Key*</label><input type="text" name="apikey" id="apikey" value="" />
		
		      
		</fieldset>
						
		 
		<fieldset class="inputs">
		  <div class="etherstormbutton-div"><a href="javascript:void(0)" class="etherstormbutton">Speichern</a></div>
		</fieldset>
		
		
</fieldset>
		
    </form>
    	
</div>

