function setSpinner()
{
	var html = 
	'<div class="loader"><div class="sk-folding-cube"><div class="sk-cube1 sk-cube"></div><div class="sk-cube2 sk-cube"></div><div class="sk-cube4 sk-cube"></div><div class="sk-cube3 sk-cube"></div></div><h3>Ładuję wyniki</h3></div>';
	$('#error').fadeOut(400, function()
	{
		$('#error').html(html).fadeIn(500);
	});
}