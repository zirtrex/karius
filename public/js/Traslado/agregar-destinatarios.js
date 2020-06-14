Vue.use(VueMask.VueMaskPlugin);

const app = new Vue({
    el: '#app',
    components: {
    	
  	},
    data: {        
        cod_traslado: in_cod_traslado,
        fecha_traslado: traslado.fecha_traslado,
        cliente: traslado.Cliente.razon_social,
        conductor: traslado.Conductor.nombres + " " + traslado.Conductor.apellidos,
        vehiculo: traslado.Vehiculo.placa,
        hora_llegada: traslado.hora_llegada,
        hora_salida: traslado.hora_salida,
        destinatarios: [],
        distritos: [
        	{"id": 4, "nombre": "Breña"},
        	{"id": 5, "nombre": "Carabayllo"},
        	{"id": 6, "nombre": "Chaclacayo"},
        	{"id": 12, "nombre": "Jesús María"},
        	{"id": 13, "nombre": "La Molina"},        	
        	{"id": 16, "nombre": "Lince"},
        	{"id": 21, "nombre": "Miraflores"},
        	{"id": 30, "nombre": "San Borja"},
        ]
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
				if (this.destinatarios[index].cod_destinatario != 0) {
					
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
        
    	validate: function(destinatario){
    		let isValid = true;
    		
    		if (destinatario.nombre == "" || destinatario.nombre == null) {
    			isValid = false;
    		} else if (destinatario.distrito == "" || destinatario.distrito == null){
    			isValid = false;
    		}
    		
    		return isValid;
    	},
    	
    	validarHoras: function (h_llegada, h_entrega, h_salida) {
    		let isValid = true;
    		
    		let hora_llegada = moment(h_llegada, 'HH:mm:ss');
    		let hora_entrega = moment(h_entrega, 'HH:mm:ss');
    		let hora_salida = moment(h_salida, 'HH:mm:ss');
    		
    		if(!hora_llegada.isValid()){
    			isValid = false;
    		} else if (!hora_entrega.isValid()) {
    			isValid = false;
    		} else if (!hora_salida.isValid()) {
    			isValid = false;
    		}
    		
    		let minutos_s_e = moment.duration(hora_salida - hora_entrega)._milliseconds;
    		
    		let minutos_e_ll = moment.duration(hora_entrega - hora_llegada)._milliseconds;
    		
    		if(minutos_s_e <= 0 || minutos_e_ll <= 0){
    			isValid = false;
    		}
    		
    		return isValid;
    	},
        
        guardar: function (index) {
            try {
            	const destinatario = this.destinatarios[index]; console.log(destinatario)
            	
            	if (this.validate(destinatario)) {
            		if (this.validarHoras(destinatario.hora_llegada, destinatario.hora_entrega, destinatario.hora_salida)) {
            			destinatario.cod_traslado = this.cod_traslado;
        				
                		this.actualizarDestinatario(destinatario, index, "guardar");
            		} else {
            			UIkit.notification({
    					    message: "Horas no válidas, La hora de salida debe ser mayor a la hora de entrega y la hora de entrega mayor a la hora de llegada",
    					    status: 'danger',
    					    timeout: 5000
    					});
            		}
            		
            	} else {
            		UIkit.notification({
					    message: "Debe completar al menos el nombre y distrito",
					    status: 'danger',
					    pos: 'top-center',
					    timeout: 5000
					});
            	}
				
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
