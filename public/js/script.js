$(document).ready(function(){
	
	var scrollSideBar = function(){
		$('._side_bar').slimScroll({
			height: document.documentElement.clientHeight - $('.navbar').outerHeight()
		});
	};	
	
	scrollSideBar();
	
	$(window).resize(function(){
		scrollSideBar();
	});
	//side menu toggle
    
    $('._side_menu_bar').on('click', function(){
    
        $('._wrapper').toggleClass('active');
        
    });
});