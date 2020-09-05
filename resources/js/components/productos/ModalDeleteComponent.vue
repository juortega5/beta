<template>
	<div 
		class="modal fade bg-dark" 
		id="deleteProducto" 
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
						Eliminar producto
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
					<p>Desea eliminar el producto: {{ nombre_producto }}.</p>
					<div class="form-group" align="center">
						<button 
							@click="destroyProducto()" 
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
	 			nombre_producto: null,
	 			slug : null
	 		}
	 	},
        mounted() {
        	//Recibe los datos y los carga en la modalDelete.
            EventBus.$on('producto-delete', data => {
	 			this.slug = data.slug;
	 			this.nombre_producto = data.nombre_producto;
			});
        },
        beforeDestroy(){
    		EventBus.$off('producto-delete');
  		},
        methods: {
        	//Envia los datos al controlador para eliminar un registro.
        	destroyProducto: function(){
        		let metodo = this;
    			axios
    				.delete('http://beta.test/productos/'+this.slug)
    				.then( response=> {
	    				//Evento para hacer la actualizacion de registros al eliminar.
	        			EventBus.$emit('producto-update',response.data);
						metodo.reset();
    				})
    				.catch( error=> { console.log(error); })
    			;
        	},
        	//Limpia la modal.
        	reset: function(){
        		this.nombre_producto = "" ;
	 			this.slug = "";
	 			$('#deleteProducto').modal('hide');
    			$('body').removeClass('modal-open');
				$('.modal-backdrop').remove();
        	}
        }
    }
</script>