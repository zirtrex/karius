Vue.use(VueMask.VueMaskPlugin);

const app = new Vue({
    el: '#app',
    components: {
    	
  	},
    data: {        
        cod_traslado: in_cod_traslado,
        fecha_traslado: traslado.fecha_traslado,
        cod_cliente: traslado.Cliente.razon_social,
        cod_conductor: traslado.Conductor.nombres + " " + traslado.Conductor.apellidos,
        cod_vehiculo: traslado.Vehiculo.placa,
        destinatarios: []
    },
    created: function () {    	
    	this.obtener();
    },
    computed: {

    },
    validations: {
    	  	
    },
    methods: {    
    	obtener: function(){
    		var self = this;
    		$.ajax({
            	url: base + "traslado/destinatario/obtener-destinatarios-ajax",
            	type: "POST",
                context: this,
                data: {
                	cod_traslado: self.cod_traslado,
                	//cod_traslado: 0,
                },                
                dataType: "json",
                cache: false,
                success: function(data){                	
                    if (data.length > 0){
						self.destinatarios = data;
                    }else{
                    	self.destinatarios = [
                    		{
                    			id: 0, cod_destinatario: 0, nombre: "", distrito: "", numero_guia: 0,
                    			hora_llegada: "", temperatura_llegada: 0, humedad_relativa_llegada: 0,
                    			hora_entrega: "", temperatura_entrega: 0, humedad_relativa_entrega: 0,
                    			hora_salida: "", cod_traslado: 0
                    		},    		
                        ]
                    }                    
                },
            });
    	},
    
    	agregar: function (index) {
            try {
				if(this.destinatarios[index].cod_destinatario != 0){
					
					this.destinatarios.splice(this.destinatarios.length + 1, 0, {
		    			id: index + 1, cod_destinatario: 0, nombre: "", distrito: "", numero_guia: 0,
		    			hora_llegada: "", temperatura_llegada: 0, humedad_relativa_llegada: 0,
		    			hora_entrega: "", temperatura_entrega: 0, humedad_relativa_entrega: 0,
		    			hora_salida: "", cod_traslado: 0
		    		});									
					
				}else{
					UIkit.notification({
					    message: "Debe completar un destinatario antes",
					    status: 'warning',
					    pos: 'top-center',
					    timeout: 3000
					});
				}
            } catch(e){
                console.log(e);
            }
        },
        
        guardar: function (index) {
            try {
            	const destinatario = this.destinatarios[index];	
            	destinatario.cod_traslado = this.cod_traslado; console.log(destinatario);
				
                this.actualizarDestinatario(destinatario, index, "guardar");
				
            } catch(e){
                console.log(e);
            }
        },
        
        eliminar: function (index) {
        	try {	        	
        		const destinatario = this.destinatarios[index];
            	this.actualizarDestinatario(destinatario, index, "eliminar");	        	
        	} catch(e){
                console.log(e);
            }
        },        
        
        actualizarDestinatario: function (destinatario, index, opcion = "guardar") {

        	switch (opcion) {
    			case "guardar":
    				var url = "traslado/guardar-destinatario-ajax";
    				break;
    			default:
    				var url = "traslado/eliminar-destinatario-ajax";
    				break;
			}

        	var self = this;
        	
            $.ajax({
            	url: base + url,
            	type: "POST",
                context: this,
                data: {
                	destinatario,
                },                
                dataType: "json",
                cache: false,
                success: function(data){                	
                	console.log(data);
                	
                    if (data.response)
                    {	
						//self.destinatarios[index].cod_destinatario = data.cod_destinatario;
						self.obtener();
                    	
						if(opcion == "eliminar"){
                    		//self.destinatarios.splice(index, 1);
                        }
						
                    	UIkit.notification({
    					    message: data.successMessage,
    					    status: 'success',
    					    pos: 'top-center',
    					    timeout: 5000
    					});
                    }
                    else
                    {
                        UIkit.notification({
    					    message: data.errorMessage,
    					    status: 'danger',
    					    pos: 'top-center',
    					    timeout: 5000
    					});
                    }
                },
            });

        }           
        
    },
    watch: {
    	
   	}
});
