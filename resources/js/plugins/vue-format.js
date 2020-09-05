
export default {
    install(Vue, options) {
    	//Convierte a notacion de punto de mil.
    	Vue.prototype.$separador = function (numero) {
        	return new Intl.NumberFormat(["ban", "id"]).format(numero);
        }

        //Al escribir pone el punto de mil en los numeros.
        Vue.prototype.$escribir = function (event) {
        	//Se captura el evento y se usa el atributo value
        	//interno en la propiedad target.
        	var str = event.target.value.toString();
        	//Se verifica que no este vacio el input.
        	if (str == '') { 
        		return $('#'+event.target.id).val('');
        	}
        	//Al escribir se debe tratar el string como numero
        	//por lo cual se mantiene eliminando el punto de mil
        	str = str.replace(/\./g,"");
        	//Pasar el str a numero.
        	str = parseInt(str);
        	////Convierte a notacion de punto de mil.
        	str = new Intl.NumberFormat(["ban", "id"]).format(str);
        	//Se pasa a str nuevamente
        	str = str.toString();
        	//se pone el valor en el input detectado.
        	$('#'+event.target.id).val(str);        	 
        }
    }
}