<template>
	<div 
		class="modal fade bg-dark" 
		id="clienteEdit" 
		tabindex="-1" 
		role="dialog" 
		aria-labelledby="exampleModalLabel" 
		aria-hidden="true"
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
						Añadir rol tercero
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
					<p>Desea agregar el rol proveedor a: {{ nombre_tercero }}.</p>
					<div 
						class="form-group" 
						align="center"
					>
						<button  
							@click="agregarRol()" 
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
			cliente: Object,
		},
	 	data() {
	 		return {
	 			nombre_tercero: null,
	 			slug : null
	 		}
	 	},
        mounted() {
	 		this.slug = this.cliente.slug;
	 		this.nombre_tercero = this.cliente.nombre_tercero;
        },
        methods: {
        	//Envia los datos al controlador para modificar el rol a un tercero.
        	agregarRol: function(){
        		let metodo = this;
    			axios
	    			.get('http://beta.test/terceros/'+this.slug+'/rol',{ params:{ rol: 2, } })
	                .then( response => { metodo.reset(); })
	    			.catch( error => { console.log(error); })
    			;
        	},
        	//Limpia la modal.
        	reset: function(){
        		this.nombre_tercero = "" ;
	 			this.slug = "";
	 			$('#clienteEdit').modal('hide');
    			$('body').removeClass('modal-open');
				EventBus.$emit('volver-factura',[false,0]);
        	}
        }
    }
</script>