function soloNumeros(e)
{
  var key = window.Event ? e.which : e.keyCode
  return ((key >= 48 && key <= 57) || (key==8))
}


function soloLetrasNumeros(e)
{
	var regex = new RegExp("^[a-zA-Z0-9 ]+$");
	var key = String.fromCharCode(!e.charCode ? e.which : e.charCode);
	if (!regex.test(key)) 
	{
		e.preventDefault();
		return false;
	}
}
