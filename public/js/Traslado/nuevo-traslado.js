Vue.use(VueMask.VueMaskPlugin);
Vue.use(window.vuelidate.default);
const { required, minLength, minValue } = window.validators

const app = new Vue({
    el: '#app',
    components: {
  	  	autoComplete: autoComplete
  	},
    data: {        
        cod_traslado: in_cod_traslado,
        fecha_traslado: ((traslado.fecha_traslado != null) ? traslado.fecha_traslado : new Date().toISOString().split('T')[0]),
        punto_partida: ((traslado.punto_partida != null) ? traslado.punto_partida : ""),
        punto_llegada: ((traslado.punto_llegada != null) ? traslado.punto_llegada : ""),
        hora_llegada: ((traslado.hora_llegada != null) ? traslado.hora_llegada : ""),
        temperatura_llegada: ((traslado.temperatura_llegada != null) ? traslado.temperatura_llegada : ""),
        humedad_relativa_llegada: ((traslado.humedad_relativa_llegada != null) ? traslado.humedad_relativa_llegada : ""),
        hora_salida: ((traslado.hora_salida != null) ? traslado.hora_salida : ""),
        temperatura_salida: ((traslado.temperatura_salida != null) ? traslado.temperatura_salida : ""),
        humedad_relativa_salida: ((traslado.humedad_relativa_salida != null) ? traslado.humedad_relativa_salida : ""),
        total: ((traslado.total != null) ? traslado.total : 0),
        cod_cliente: ((traslado.Cliente.cod_cliente != null) ? parseInt(traslado.Cliente.cod_cliente) : 0),
        cliente_razon_social: ((traslado.Cliente.razon_social != null) ? traslado.Cliente.razon_social : ""),
        cod_almacen: 0,
        cod_conductor: ((traslado.Conductor.cod_conductor != null) ? parseInt(traslado.Conductor.cod_conductor) : 0),
        conductor_nombres: ((traslado.Conductor.nombres != null) ? traslado.Conductor.nombres+" "+traslado.Conductor.apellidos : ""),
        cod_vehiculo: ((traslado.Vehiculo.cod_vehiculo != null) ? parseInt(traslado.Vehiculo.cod_vehiculo) : 0),
        vehiculo_placa: ((traslado.Vehiculo.placa != null) ? traslado.Vehiculo.placa : ""),
        clientes: [],
        almacenes: [],
        conductores: [],
		vehiculos: [],
        textButton: "Guardar y continuar",
        submitStatus: "",
        successMessage : "",
        warningMessage : "",
        errorMessage : ""

    },
    created: function () {

    	fetch(base + "traslado/obtener-clientes-ajax")
			.then(data => data.json())
			.then(json => this.clientes = json);

    	fetch(base + "traslado/obtener-conductores-ajax")
    		.then(data => data.json())
    		.then(json => this.conductores = json);

    	fetch(base + "traslado/obtener-vehiculos-ajax")
        	.then(data => data.json())
        	.then(json => this.vehiculos = json);
    	
    	if(this.cod_cliente != 0){
    		this.obtenerAlmacenes();
    	}

    },
    computed: {

    },
    updated: function() {
    	//this.textButton = "Guardar";
    },
    validations: {
    	cod_cliente: {
    		required,
    		minValue: minValue(1)
    	},
    	cod_conductor: {
    		required,
    		minValue: minValue(1)
    	},
    	cod_vehiculo: {
    		required,
    		minValue: minValue(1)
    	},
    	fecha_traslado: {
    		required
    	},
    	punto_partida: {
    		required
    	},
    	punto_llegada: {
    		required
    	},
    	hora_llegada: {
    		required
    	},
    	temperatura_llegada: {
    		required
    	},
    	humedad_relativa_llegada: {
    		required
    	},
    	hora_salida: {
    		required
    	},
    	temperatura_salida: {
    		required
    	},
    	humedad_relativa_salida: {
    		required
    	}    	
    },
    methods: {
		guardarTraslado: function () {
			this.textButton = "Guardando...";
			this.$v.$touch();
			if (this.$v.$invalid) {
				this.warningMessage = "Revise que todos los campos estén completos antes de continuar.";
			    this.submitStatus = 'WARNING';
			    this.textButton = "Guardar y continuar";
			} else {				
				$.ajax({
	            	url: base + "traslado/guardar-traslado-ajax",
	            	type: "POST",
	                context: this,
	                data: {
	                	cod_traslado: this.cod_traslado,
	                	fecha_traslado: this.fecha_traslado,
	                	punto_partida: this.punto_partida,
	                	punto_llegada: this.punto_llegada,
	                    hora_llegada: this.hora_llegada,
	                    temperatura_llegada: this.temperatura_llegada,
	                    humedad_relativa_llegada: this.humedad_relativa_llegada,
	                    hora_salida: this.hora_salida,
	                    temperatura_salida: this.temperatura_salida,
	                    humedad_relativa_salida: this.humedad_relativa_salida,
	                    total: this.total,
	                	cod_cliente: this.cod_cliente,
	                	cod_vehiculo: this.cod_vehiculo,
	                	cod_conductor: this.cod_conductor
	                },                
	                dataType: "json",
	                cache: false,
	                success: function(data){
	                	console.log(data);
	                    if (data.response)
	                    {
	                    	this.textButton = "Guardado";
	                    	this.successMessage = data.successMessage;
	                    	this.submitStatus = 'SUCCESS';
	                    	this.cod_traslado = data.cod_traslado;
	                    	
	                    	final_url = url_agregar_destinatarios + "/" + this.cod_traslado; console.log(url_agregar_destinatarios);
	                    	
							var finalMessage = data.successMessage + "<br/> <br/><a class='uk-button uk-button-secondary' href='" + final_url + "'>Click aquí para agregar destinatarios.</a>";
							
	                    	UIkit.notification({
	    					    message: finalMessage,
	    					    status: 'success',
	    					    timeout: 20000
	    					});       	
	                    	
							/*setTimeout (()=>{
								window.location = url_agregar_destinatarios;
							}, 15000);*/
	                    }
	                    else
	                    {                  	
	                    	console.log(data.response);
	                        console.log(data.errorMessage);
	                        this.errorMessage = data.errorMessage;
	                        this.submitStatus = 'ERROR';
	                        UIkit.notification({
	    					    message: data.errorMessage,
	    					    status: 'danger',
	    					    timeout: 10000
	    					});
	                    }
	                },
	            });
			}
            
        },

		obtenerAlmacenes: function () {
            $.ajax({
            	url: base + "traslado/obtener-almacenes-ajax",
            	type: "POST",
                context: this,
                data: {
                	cod_cliente: this.cod_cliente
                },                
                dataType: "json",
                cache: false,
                success: function(data){                                                      	
                    if (data){
						this.almacenes = data;
                    }                    
                },
            });
        },
        
        handleDataCliente: function(e) {
        	var razon_social = "";
            [this.cod_cliente, razon_social] = e;
            //this.$v.cod_cliente.$touch();
            //console.log(e);
            this.obtenerAlmacenes();
        },
        
        handleDataAlmacen: function(e) {
            [this.cod_almacen, this.punto_llegada] = e;
            //console.log(e);
        },

        handleDataConductor: function(e) {
            [this.cod_conductor] = e;
            //[this.requerimientos[index].codCurso, this.requerimientos[index].nombreCurso] = e;
            //console.log(e);
        },

        handleDataVehiculo: function(e) {
            var placa = "";
            [this.cod_vehiculo, placa] = e;
            //console.log(e);
        }        
        
    }
});
