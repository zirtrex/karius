var autoComplete = Vue.component('auto-complete', {
	template:	
		`<div class="auto-complete">
    		<input type="hidden" v-model="codigo">
       		<input class="uk-input" type="text" v-model="input" @keydown.tab.prevent="complete()" @focus="focus(true)" @blur="focus(false)" v-bind="$attrs">
       		<ul v-if="focused" class="uk-list uk-list-divider uk-list-striped">
    	     	<li v-for="(data, i) in data" v-if="filter(data)" @mousedown="complete(i)">
          	      <p>{{ data[field2] }} {{ data[field3] || '' }}</p>
        	    </li>
        	 </ul>
    	</div>`,
    	
    props: {
	  	codigoin: { type: Number, required: true},
      	value: { type: String, required: false},
      	data:  { type: Array, required: true},
      	field1: { type: String, required: true},
      	field2: { type: String, required: true},
		field3: { type: String, required: false}
	},
	
	data() {
       return {
           codigo: 0,
           input: '',
           focused: false
       }
	},
		
	created() {
		this.codigo = this.codigoin || 0,
		//this.codigo =  this.field1 || 0,
		this.input = this.value || ''
	},

	methods: {
	   complete(i) {
		   if (i == undefined) {
	       	   for (let row of this.data) {
	        		if (this.filter(row)) {
	                	this.select(row)
	                	return
	              	}
	           }
	       }
	       this.select(this.data[i])
	   },
	
	   select(row) {       	  	
	      	this.codigo = row[this.field1];          	
	      	this.input = row[this.field2] + " " + (row[this.field3] || '');
	      	this.selected = true;
	      	this.$emit('newdata', [parseInt(this.codigo), row[this.field2], row[this.field3]]);
	   },
	
       filter(row) {
       		return row[this.field2].toLowerCase().indexOf(this.input.toLowerCase()) != -1
       },
	
       focus(f) {
       		this.focused = f
       }
	},	

	watch: {
		input: {
	    	handler: function() {
	        	if(this.input == 0) this.$emit('newdata', [0, '', '']);
	    			//this.$emit('newdata', [this.codigo, this.input, this.unidadMedida]);
	      	},
	    	deep: true
		}
	}
});
