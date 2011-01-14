jQuery(document).ready(function(){
	jQuery("#featured").after('<div id="slider-nav">').cycle({
		next: '#slider-next',
		prev: '#slider-prev',
		pager: '#slider-nav',
		timeout: 4000,
		delay: -4000,
		speed: 800,
	});
});