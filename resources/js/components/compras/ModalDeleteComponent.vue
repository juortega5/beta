<template>
	<div 
		class="modal fade bg-dark" 
		id="deleteCompra" 
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
			    		Eliminar factura
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
			       	<p>Desea eliminar la factura de compra número: {{ numero }}.</p>
					<div 
						class="form-group" 
						align="center"
					>
						<button 
							@click="destroyFactura()" 
							class="btn btn-danger"
						>
							Eliminar
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
	 	data() {
	 		return {
	 			numero: null,
	 			id : null
	 		}
	 	},
        mounted() {
        	//Recibe los datos y los carga en la modalDelete.
            EventBus.$on('compra-delete', data => {
	 			this.numero = data.numero;
	 			this.id = data.id;
			});
        },
        beforeDestroy(){
    		EventBus.$off('compra-delete');
  		},
        methods: {
        	//Envia los datos al controlador para eliminar un registro.
        	destroyFactura: function(){
        		let metodo = this;
    			axios
	    			.delete('http://beta.test/compras/'+this.id)
	    			.then( response => {
	    				//Evento para hacer la actualizacion de registros al eliminar.
	        			EventBus.$emit('compra-destroy',response.data);
						metodo.reset();
	    			})
	    			.catch( error => { console.log(error); })
    			;
        	},
        	//Limpia la modal.
        	reset: function(){
        		this.numero = "" ;
	 			this.id = "";
	 			$('#deleteCompra').modal('hide');
    			$('body').removeClass('modal-open');
				$('.modal-backdrop').remove();
        	}
        }
    }
</script>