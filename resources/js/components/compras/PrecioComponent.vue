<template>
	<div 
		class="modal fade bg-dark" 
		id="agregarPrecio" 
		tabindex="-1" 
		role="dialog" 
		aria-labelledby="exampleModalLabel" 
		aria-hidden="true"
		align="left" 
	>
		<div 
			class="modal-dialog" 
			role="document"
		>
			<div class="modal-content">
				<div class="modal-header">
					<h5 
						class="modal-title" 
						id="exampleModalLabel"
					>
						Agregar precio de venta
					</h5>
					<button 
						type="button" 
						@click="reset()" 
						class="close" 
						data-dismiss="modal" 
						aria-label="Close"
					>
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
			      		<div class="col-md-12">
							<div class="form-group" >
								<label>Modalidad:&nbsp;</label>
								<div class="form-check form-check-inline">
								  	<input 
								  		id="btnPorcentaje" 
								  		class="form-check-input" 
								  		type="checkbox" 
								  		@change="configurar(false,'btnValor','porcentaje')"
								  	/>
								 	<label class="form-check-label">Porcentaje %</label>
								</div>
								<div class="form-check form-check-inline">
									<input 
										id="btnValor"
								  		class="form-check-input" 
								  		type="checkbox" 
								  		@change="configurar(true,'btnPorcentaje','precio_venta')"
								  	/>
								 	<label class="form-check-label">Valor $</label>
								</div>
								<small 
									id="rolHelp" 
									class="form-text text-muted"
								>
									Seleccione la modalidad para fijar el precio de venta.
								</small>
							</div>
						</div>
					</div>
					<div class="row">
			      		<div class="col-md-4">
							<div class="form-group">
								<label for="nit">Precio compra</label>
								<input  
									readonly="readonly"  
									type="text" 
									class="form-control" 
									id="precio_compra"
								/>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="nit">Precio venta</label>
								<input  
									disabled 
									v-on:keyup="formulavalor();$escribir($event) "
									type="text" 
									class="form-control" 
									id="precio_venta"
									onKeyPress="return soloNumeros(event)"
								/>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="nit">% Incremento</label>
								<input  
									disabled
									v-on:keyup="formulaporcentaje();$escribir($event)"
									type="text" 
									class="form-control" 
									id="porcentaje"
									onKeyPress="return soloNumeros(event)"
								/>
							</div>
						</div>
					</div>
					<div 
						class="form-group" 
						align="center"
					>
						<button  
							@click="agregarPrecio()" 
							disabled
							id="agregar" 
							class="btn btn-success"
						>
							Agregar
						</button> 
						<button  
							@click="reset()" 
							class="btn btn-secondary" 
							data-dismiss="modal"
						>
							Cancelar
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import EventBus from '../../event-bus';
	export default {
		props: {
			precioCompra: String,
		},
	 	data() {
	 		return {
	 			precio: null,
	 		}
	 	},
        mounted() {
        	$("#precio_compra").val(this.precioCompra);
	 		this.precio = parseInt(this.precioCompra.replace(/\./g,""));
        },
        methods: {
        	//Habilita las opciones dependiendo del boton seleccionado.
        	configurar: function(flag,btn,input){
        		//Desabilitar el boton de enviar.
        		$("#agregar").prop('disabled', true);
        		//Limpiar inputs
        		$("#porcentaje").val('');
        		$("#precio_venta").val('');
        		//Habilita inputs de acuerdo a la opcion marcada.
        		$("#porcentaje").prop('disabled', flag);
        		$("#precio_venta").prop('disabled', !flag);
        		//Deshabilita el checked que no esta en uso.
        		$("#"+btn).prop('checked',false);
        		setTimeout(function(){ $('#'+input).focus(); }, 1);
        	},
        	//Formula para trabajar valores.
        	formulavalor:function(){
        		//Limpiar inputs
        		$("#porcentaje").val('');
        		//Desabilitar el boton de enviar.
        		$("#agregar").prop('disabled', true);
        		//Revisar que no vengan valores vacios.
				var dato = $("#precio_venta").val().replace(/\./g,"").trim();
        		if (dato == '') {
        			return setTimeout(function(){ $('#precio_venta').focus(); }, 1);
        		}
        		//Coventir el valor a int y revisar que no sea menor al precio de compra.
        		var valor = parseInt(dato);
        		if (valor < this.precio) {
        			return setTimeout(function(){ $('#precio_venta').focus(); }, 1);
        		}
        		//Mostrar en el otro input el resultado de la formula.
        		$("#porcentaje").val(Math.round(((valor-this.precio)/this.precio)*100)+'%');
        		//Habilitar boton de envio,
        		$("#agregar").prop('disabled', false);
        	},
        	//Formula para trabajar porcentajes.
        	formulaporcentaje:function(){
        		//Limpiar inputs
        		$("#precio_venta").val('');
        		//Desabilitar el boton de enviar.
        		$("#agregar").prop('disabled', true);
        		//Revisar que no vengan valores vacios.
				var dato = $("#porcentaje").val().replace(/\./g,"").trim();
        		if (dato == '') {
        			return setTimeout(function(){ $('#porcentaje').focus(); }, 1);
        		}
        		//Coventir el valor a int
        		var valor = parseInt(dato);
        		//Mostrar en el otro input el resultado de la formula.
        		$("#precio_venta").val(Math.round(this.precio*(1+(valor/100))));
        		//Habilitar boton de envio,
        		$("#agregar").prop('disabled', false);
        	},
        	agregarPrecio: function(){
        		var dato = $("#precio_venta").val().replace(/\./g,"");
        		EventBus.$emit('volver-precio',[false,dato]);
        	},
        	//Limpia la modal.
        	reset: function(){
        		this.precio = "" ;
	 			$('#clienteEdit').modal('hide');
    			$('body').removeClass('modal-open');
				EventBus.$emit('volver-precio',[false,'nope']);
        	}
        }
    }
</script>