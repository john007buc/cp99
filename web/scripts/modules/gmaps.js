define(['gmap'],function($){
  return {
      initialize: function(){
          var input = document.getElementById('address');
          var options = {
              componentRestrictions: {country: 'ro'}
          };
          var autocomplete = new google.maps.places.Autocomplete(input,options);
      },
      load: function () {
          this.initialize();
          google.maps.event.addDomListener(window, 'load', this.initialize);
      }
  }

});