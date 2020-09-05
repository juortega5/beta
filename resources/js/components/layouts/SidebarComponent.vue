<template>
	<div>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		  	<button 
			  class="navbar-toggler"  
			  data-toggle="collapse" 
			  data-target="#navbarSupportedContent" 
			  aria-controls="navbarSupportedContent" 
			  aria-expanded="false" 
			  aria-label="Toggle navigation"
		  	>
		    	<span class="navbar-toggler-icon"></span>
		  	</button>
		 	<div 
		 		lass="collapse navbar-collapse" 
		 		id="navbarSupportedContent"
		 	>
			    <ul class="navbar-nav mr-auto">
					<li class="nav-item active">
						<router-link 
							class="nav-link" 
							:to="{name: 'home'}"
						>
							Home
						</router-link>
					</li>
					<li class="nav-item">
						<router-link 
							class="nav-link" 
							:to="{name: 'productos'}"
						>
							Productos
						</router-link>
					</li>
					<li class="nav-item">
						<router-link 
							class="nav-link" 
							:to="{name: 'terceros'}"
						>
							Terceros
						</router-link>
					</li>
					<li class="nav-item">
						<router-link 
							class="nav-link" 
							:to="{name: 'compras'}"
						>
							Compras
						</router-link>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Ventas</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Reportes
						</a>
						<div 
							class="dropdown-menu" 
							aria-labelledby="navbarDropdown"
						>
							<a class="dropdown-item" href="#">Deudores</a>
							<a class="dropdown-item" href="#">Ganancias</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="#">Inventario</a>
						</div>
					</li>
			    </ul>
		  	</div>
		</nav>
		<hr>
	</div>
</template>