<?php 
OCP\Util::addScript( "owncloud_etherstorm", "jquery.datatables" );
OCP\Util::addScript( "owncloud_etherstorm", "jquery.ui.position" );
OCP\Util::addScript( "owncloud_etherstorm", "jquery.contextMenu" );
OCP\Util::addScript( "owncloud_etherstorm", "qtip2/jquery.qtip" );

OCP\Util::addStyle( "owncloud_etherstorm", "jquery.dataTables" );
OCP\Util::addStyle( "owncloud_etherstorm", "etherstorm-settings" );
OCP\Util::addStyle( "owncloud_etherstorm", "jquery.contextMenu" );
OCP\Util::addStyle( "owncloud_etherstorm", "qtip2/jquery.qtip" );
?>


<div style="position:relative;top:50px;padding:10px">
<table id="table_id">
    <thead>
        <tr>
            <th>Padname</th>      
            <th>Ersteller</th>
			<th>Erstellt</th>
			<th>Kommentar</th>
			<th>Priorität</th>
			<th>Letzte Bearbeitung</th>
			
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Row 1 Data 1</td>   
            <td>Row 1 Data 3</td>
			<td>Row 1 Data 4</td>
			<td>Row 1 Data 5</td>
			<td>Row 1 Data 6</td>
			<td>Row 1 Data 7</td>
			
	    </tr>
    </tbody>
</table>
</div>
<script>
$.postJSON = function (url, data, callback) {
   $.post(url, data, callback, "json");
};
$(document).ready(function() {

json2 = {};
json2.aaData = [];
json2.aoColumns = [];
count = 0;
$('#table_id').dataTable({"iDisplayLength":100}).fnDeleteRow(0);

$.postJSON(OC.filePath('owncloud_etherstorm', 'templates', 'getjson.php'),'getallpads=', function(data2) {
$.each (data2.data.padIDs, function(index,val) {

$.postJSON(OC.filePath('owncloud_etherstorm', 'templates', 'getjson.php'),'padname='+val, function(data3) {
if (data3[0]["creator"] == null) {data3[0]["creator"] = 'Not available'}
if (data3[0]["created"] == null) {data3[0]["created"] = 'Not available'}
if (data3[0]["kommentaranzahl"] == null) {data3[0]["kommentaranzahl"] = 'Not available'}
if (data3[0]["priority"] == null) {data3[0]["priority"] = 'Not available'}
if (data3[0]["lastedited"] == null) {data3[0]["lastedited"] = 'Not available'}
json2.aaData.push([val,data3[0]["creator"],data3[0]["created"],data3[0]["kommentaranzahl"],data3[0]["priority"],data3[0]["lastedited"]]);


count++;
$('#table_id').dataTable().fnAddData([val,data3[0]["creator"],data3[0]["created"],data3[0]["kommentaranzahl"],data3[0]["priority"],data3[0]["lastedited"]]);
if (data2.data.padIDs.length == count) {
json2.aoColumns.push({"sTitle":"Padname"},{"sTitle":"Ersteller"},{"sTitle":"Erstellt"},{"sTitle":"Kommentar"},{"sTitle":"Priorität"},{"sTitle":"Letzte Bearbeitung"});

function clickable(serverurl) {
$('#table_id td.sorting_1').each(function(i) {$(this).html('<a href="'+serverurl+$(this).html() +'" target="_blank" >'+$(this).html() +'</a>')}); 
}

$.postJSON(OC.filePath('owncloud_etherstorm', 'templates', 'getjson.php'),'getpadserver=', function(data2) {
var a = document.createElement("a");
a.href = data2;
document.body.appendChild(a);
serverurl = a.href.replace(a.pathname,'/p/');
document.body.removeChild(a);
clickable(serverurl);
});



$('#table_id td.sorting_1 a').hover(
    function(){
	     $(this).css('color', 'blue'); //mouseover
    },
    function(){
         $(this).css('color', '#000'); // mouseout
    });
	

//werte aus datenbank

$('#table_id td:nth-child(4)').each(function(){
var padclick = ($(this).parent(1)[0].children[0].textContent);
//var padclick =($(this).parent(1)[0].children[0].children[0].innerHTML);
$(this).qtip(
		{
			content: { 
				// Set the text to an image HTML string with the correct src URL to the loading image you want to use
				text: '<img class="throbber" src="' + OC.filePath('owncloud_etherstorm', 'img', 'ajax-loader.gif') + '" alt="Loading..." />',
				ajax: {
					url: OC.filePath('owncloud_etherstorm', 'templates', 'getjson.php'), // Use the rel attribute of each element for the url to load
					type: 'POST', // POST or GET
					cache: false,
			data: {padname : padclick, getcomments: ""},
			dataType: 'json'
				},
				title: {
					text: padclick, // Give the tooltip a title using each elements text
					button: true
				}
			},
			position: {
				at: 'bottom center', // Position the tooltip above the link
				my: 'top center',
				adjust: {
            screen: true
        },
				viewport: $(window), // Keep the tooltip on-screen at all times
				effect: false // Disable positioning animation
			},
			show: {
				event: "mouseenter",
				solo: true,
				ready: false // Only show one tooltip at a time
			},
			hide: false,
           
			style: {
				classes: 'qtip-wiki qtip-light qtip-shadow'
			}
		})
});
function dialogue(content, title) {
		/* 
		 * Since the dialogue isn't really a tooltip as such, we'll use a dummy
		 * out-of-DOM element as our target instead of an actual element like document.body
		 */
		$('<div />').qtip(
		{
			content: {
				text: content,
				title: title
			},
			position: {
				my: 'center', at: 'center', // Center it...
				target: $(window) // ... in the window
			},
			show: {
				ready: true, // Show it straight away
				modal: {
					on: true, // Make it modal (darken the rest of the page)...
					blur: false // ... but don't close the tooltip when clicked
				}
			},
			hide: false, // We'll hide it maunally so disable hide events
			style: 'qtip-light qtip-rounded qtip-dialogue', // Add a few styles
			events: {
				// Hide the tooltip when any buttons in the dialogue are clicked
				render: function(event, api) {
					$('button', api.elements.content).click(api.hide);
				},
				// Destroy the tooltip once it's hidden as we no longer need it!
				hide: function(event, api) { api.destroy(); }
			}
		});
	}
function Prompt(question, initial, callback)
	{
		// Content will consist of a question elem and input, with ok/cancel buttons
		var message = $('<p />', { text: question }),
			input = $('<input />', { val: initial }),
			ok = $('<button />', { 
				text: 'Ok',
				click: function() { callback( input.val() ); }
			}),
			cancel = $('<button />', {
				text: 'Cancel',
				click: function() { callback(null); }
			});
 
		dialogue( message.add(input).add(ok).add(cancel), 'Attention!' );
	}
	
	function SelectPrompt(question, initial, callback)
	{
		// Content will consist of a question elem and input, with ok/cancel buttons
		var message = $('<p />', { text: question }),
			input = $('<Select><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option></Select>', { val: initial }),
			ok = $('<button />', { 
				text: 'Ok',
				click: function() { callback( input.val() ); }
			}),
			cancel = $('<button />', {
				text: 'Cancel',
				click: function() { callback(null); }
			});
 
		dialogue( message.add(input).add(ok).add(cancel), 'Attention!' );
	}
	
	function PadPrompt(question, initial, callback)
	{
		// Content will consist of a question elem and input, with ok/cancel buttons
		var message = $('<p />', { text: question }),
			input = $('<input/>', { val: initial }),
			
			ok = $('<button />', { 
				text: 'Ok',
				click: function() { callback( input.val() ); }
			}),
			cancel = $('<button />', {
				text: 'Cancel',
				click: function() { callback(null); }
			});
 
		dialogue( message.add(input).add(ok).add(cancel), 'Attention!' );
	}
 
	// Our Confirm method
	function Confirm(question, callback)
	{
		// Content will consist of the question and ok/cancel buttons
		var message = $('<p />', { text: question }),
			ok = $('<button />', { 
				text: 'Ok',
				click: function() { callback(true); }
			}),
			cancel = $('<button />', { 
				text: 'Cancel',
				click: function() { callback(false); }
			});
 
		dialogue( message.add(ok).add(cancel), 'Do you agree?' );
	}
	
	
function prioritycolor() {
$('#table_id td:nth-child(5)').each(function() {

if ($(this)[0].innerHTML == '1') {$(this).css('background-color', '#A9F5A9');}
if ($(this)[0].innerHTML == '2') {$(this).css('background-color', '#2EFE2E');}
if ($(this)[0].innerHTML == '3') {$(this).css('background-color', '#088A29');}
if ($(this)[0].innerHTML == '4') {$(this).css('background-color', '#F5F6CE');}
if ($(this)[0].innerHTML == '5') {$(this).css('background-color', '#F7FE2E');}
if ($(this)[0].innerHTML == '6') {$(this).css('background-color', '#AEB404');}
if ($(this)[0].innerHTML == '7') {$(this).css('background-color', '#F5A9A9');}
if ($(this)[0].innerHTML == '8') {$(this).css('background-color', '#FE2E2E');}
if ($(this)[0].innerHTML == '9') {$(this).css('background-color', '#B40404');}

});
}
prioritycolor();

function changepriority(priority,padnameclick,rowid) {
$.postJSON(OC.filePath('owncloud_etherstorm', 'templates', 'getjson.php'),'padname='+padnameclick+'&priority='+priority+'', function(data3) {if (data3 == "true") {}else {console.log(data3);}})
$('#table_id').children().children()[rowid].children[4].innerHTML = priority;
prioritycolor();
}

$.contextMenu({
    // define which elements trigger this menu
    selector: "#table_id td",
    // define the elements of the menu
	
    items: {
	
	
	    bar: {
		
                name: "Create Pad", icon : "add",
                callback: function() {
				
		PadPrompt('Padname', '', function(response) {
		if (response){
		
			$.postJSON(OC.filePath('owncloud_etherstorm', 'templates', 'getjson.php'),'padname=' + response + '&createpad=&priority=&comment=', function(data3) {if (data3 == "true"){
				$('#table_id').dataTable().fnAddData([response,'refresh','refresh',"refresh","refresh","refresh"]);
				} else {alert(data3);}})
				}
		});
	
				 }
               
			
		
		},
        foo: {
		"name":"Change Priority",icon:"edit",callback: function(key, opt){ 
		var removerow = ($(this).closest("tr")[0].rowIndex);
		var padnameclick = $(this).children("a").html();
		SelectPrompt('Priorität', 'Awesome!', function(response) {
		if (response){
			changepriority(response,padnameclick,removerow);
			}
		});
	}
		},
        bar2: {name: "Insert Comment",icon:"paste", 
                callback: function() {
				padnameclick = $(this).children("a").html();
				removerow = ($(this).closest("tr")[0].rowIndex);
		Prompt('Kommentar eingeben', '', function(response) {
		
		if (response){
			$.postJSON(OC.filePath('owncloud_etherstorm', 'templates', 'getjson.php'),'padname=' + padnameclick + '&comment='+response, function(data3) {
			if (data3 == "true"){
			$('#table_id').children().children()[removerow].children[3].innerHTML = parseInt($('#table_id').children().children()[removerow].children[3].innerHTML) +1;
			} 
			else {alert(data3);}}) 
			}
		});
	
				
               
			}},
		
		bar3: {name: "Pad löschen", icon:"delete", callback: function(key, opt){ 
		padnameclick = $(this).children("a").html();
		removerow = ($(this).closest("tr").get(0)); 
		
				
		Confirm('Wirklich Pad "' + padnameclick + '" löschen', function(response) {
		if (response){
		
				
			$.postJSON(OC.filePath('owncloud_etherstorm', 'templates', 'getjson.php'),'padname='+padnameclick+'&deletepad=', function(data3) {
			if (data3 === "true"){
			$('#table_id').dataTable().fnDeleteRow($('#table_id').dataTable().fnGetPosition(removerow));
			} 
			else {alert(data3);}})
			
			}
		});
	
		}}
		
    }
    
});	
}

});



});



});





	
	
} );
</script>