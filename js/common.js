$('form').on('submit', function(e){
  if( ! this.checkValidity()){
  		e.preventDefault();
    	$(this).addClass('invalid');
  }
});