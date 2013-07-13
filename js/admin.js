$(document).ready(function(){



	$('.etherstormbutton').click(function(event){
		event.preventDefault();
		var post = $( "#etherpadurl,#apikey" ).serialize();
		$.post( OC.filePath('owncloud_etherstorm', 'templates', 'getjson.php') , post, function(data){
			
			if (data == 'true') {
			$(".etherstormbutton").removeClass('red');
			$(".etherstormbutton").addClass('green');
			}
			else {
			$(".etherstormbutton").removeClass('green');
			$(".etherstormbutton").addClass('red');
			}
		});
	});



});
