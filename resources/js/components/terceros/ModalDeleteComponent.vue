<template>
	<div class="modal fade" id="deleteTercero" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Eliminar tercero</h5>
	        <button type="button" @click="reset()" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	       	<p>Desea eliminar el tercero: {{ nombre_tercero }}.</p>
			<div class="form-group" align="center">
				<button type="button"  @click="destroyTercero()" class="btn btn-danger">Eliminar</button> 
				<button type="button" @click="reset()" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
	 			nombre_tercero: null,
	 			slug : null
	 		}
	 	},
        mounted() {
        	//Recibe los datos y los carga en la modalDelete.
            EventBus.$on('tercero-delete', data => {
	 			this.slug = data.slug;
	 			this.nombre_tercero = data.nombre_tercero;
			});
        },
        methods: {
        	//Envia los datos al controlador para eliminar un registro.
        	destroyTercero: function(){
        		let metodo = this;
    			axios.delete('http://beta.test/terceros/'+this.slug).
    			then(function(response){
    				//Evento para hacer la actualizacion de registros al eliminar.
        			EventBus.$emit('tercero-destroy',response.data)
					metodo.reset();
    			}).catch(function(error){
    				console.log(error)
    			});
        	},
        	//Limpia la modal.
        	reset: function(){
        		this.nombre_tercero = "" ;
	 			this.slug = "";
	 			$('#deleteTercero').modal('hide');
    			$(document.body).removeClass('modal-open');
				$('.modal-backdrop').remove();
        	}
        }
    }
</script>