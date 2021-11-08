var burger ={
    init: function(){
      $(document).ready(function () {
  
        $('.first-button').on('click', function () {
      
          $('.animated-icon1').toggleClass('open');
        });
  
  
  /*Remet le icon burger a ça possition intial*/ 
        $('.nav-link').on('click', function () {
      
          $('.animated-icon1').toggleClass('open');
        });
  
  /* ferme le menu au click*/
        $('.nav-link').on('click', function () {
      
          $('.navbar-collapse').toggleClass('show');
        });
  
  
  
        $('.second-button').on('click', function () {
      
          $('.animated-icon2').toggleClass('open');
        });
        $('.third-button').on('click', function () {
      
          $('.animated-icon3').toggleClass('open');
        });
      });
    }
  };
  
  $(burger.init);