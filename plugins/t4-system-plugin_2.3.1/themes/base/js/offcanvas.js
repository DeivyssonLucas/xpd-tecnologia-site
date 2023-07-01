/* rechange name offcanvas before load bs5 offvancas */
(function( w, $ ){
	"use strict";

	var pluginName = "T4Offcanvas",
		initSelector = ".js-" + pluginName;

	$.fn[ pluginName ] = function(options){
		return this.each( function(){
			new w.componentNamespace.Offcanvas( this, options ).init();
		});
	};
	// auto-init on enhance (which is called on domready)
	$( w.document ).on( "enhance", function(e){
		$( $( e.target ).is( initSelector ) && e.target ).add( initSelector, e.target ).filter( initSelector )[ pluginName ]();
	});

})(this, jQuery);

jQuery(document).ready(function($){
	var $offcanvas = $('.t4-offcanvas');
	$('.t4-wrapper').addClass('c-offcanvas-content-wrap');
	if($('#triggerButton').length){
		$offcanvas.T4Offcanvas({
			triggerButton: '#triggerButton' ,
			onOpen: function () {
				$('#triggerButton').addClass('active');
				bodyScrollLock.disableBodyScroll ($('.t4-off-canvas-body').get(0));
			},
			onClose: function () {
				$('#triggerButton').removeClass('active');
				setTimeout(function(){
					bodyScrollLock.enableBodyScroll ($('.t4-off-canvas-body').get(0));
				},300);
			}
		});
		setTimeout(function () {
      $offcanvas.show();
    }, 300);
	}else{
		$offcanvas.hide();
	}
	
	var refixingHeight = function(elm,action = 'next'){
		//refixing height px on menu
		if($('.t4-off-canvas-body').data('effect') == 'drill'){
			let $that = $(elm);
			setTimeout(function(){
				let menuH = $that.next().outerHeight();
				if(menuH <= 200 ){
					menuH = '400';	
				}
				if(action == 'prev'){
					$offc_level = $that.closest('li.nav-item').data('level') || 0;
					$offc_level = ($offc_level != 0) ? $offc_level - 1 : 0;
					menuH = $that.closest("li[data-level="+$offc_level+"]").find('ul.dropdown-menu').not($that.parent('ul.dropdown-menu')).outerHeight();
					if(menuH == undefined) menuH = $('.navbar.drilldown-effect').find('ul.nav.navbar-nav').outerHeight();
					if(menuH <= 200 ){
						menuH = '400';	
					}
					$that.closest('opening').removeClass('opening');
				}
				menuH = menuH+"px";
				$('.t4-off-canvas-body nav.navbar').css({height:menuH});
			}, 201);
		}
	}
	if($('.t4-off-canvas-body').data('effect') == 'drill'){	
		$('.t4-off-canvas-body nav.navbar').addClass('drilldown-effect');
	}
	$( '.t4-off-canvas-body ul.dropdown-menu' ).each(function( index ) {
		var label = $(this).prev().text();

		$(this).prepend("<span class='sub-menu-back'><i class='fas fa-chevron-left'></i>"+label+"</span>");
	 	$( this ).before('<span class="sub-menu-toggle btn-toggle"></span>');
	}); 
	$('.sub-menu-toggle').on('click', function(e) {
		e.preventDefault();
		$offc_level = $(this).parent('li').data('level') || 0;
		$offcanvas.removeClass(function (index, css) {
			return (css.match (/\oc-level-\S+/g) || []).join(' '); // removes anything that starts with "page-"
		})
		$offcanvas.addClass('oc-level-'+$offc_level);
		if($offc_level == 1){
			$('ul.dropdown-menu').not($(this).next()).hide(200);
			$('.sub-menu-toggle').not($(this)).removeClass('is-active');
		}else if ($offc_level == 2) {
			$(this).parents('ul.dropdown-menu').find('ul.dropdown-menu').not($(this).next()).hide(200);
			$(this).parents('ul.dropdown-menu').find('.sub-menu-toggle').not($(this)).removeClass('is-active');
		}
		$(this).next().slideToggle(200);
		$(this).toggleClass('is-active');
		refixingHeight(this);
	});
	$('.sub-menu-back').on('click', function(e) {
		e.preventDefault();
		$offc_level = $(this).closest('li.nav-item').data('level') || 0;
		$offc_level = ($offc_level != 0) ? $offc_level - 1 : 0;
		$offcanvas.removeClass(function (index, css) {
			return (css.match (/\oc-level-\S+/g) || []).join(' '); // removes anything that starts with "oc-level-"
		})
		$offcanvas.addClass('oc-level-'+$offc_level);
		$(this).parent('ul.dropdown-menu').slideToggle(200);
		$('.sub-menu-toggle').toggleClass('is-active');
		refixingHeight(this,'prev');
	});
});