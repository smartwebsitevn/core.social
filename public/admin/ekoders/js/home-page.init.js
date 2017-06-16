$(document).ready(function() {

	//For users List demo at dashboard
	$(function() {    
        $('#input-quicklist').on('keyup', function() {
          var rex = new RegExp($(this).val(), 'i');
            $('.quick-list .items').hide();
            $('.quick-list .items').filter(function() {
                return rex.test($(this).text());
            }).show();
        });
    });
	
	// tasks-widget sortable.
	/*$('#todo-sortlist').sortable({
		opacity:0.8,
		revert:true,
		forceHelperSize:true,
		axis: 'y',
		placeholder: 'draggable-placeholder',
		forcePlaceholderSize:true,
		stop: function( event, ui ) {//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
			$(ui.item).css('z-index', 'auto');
			}
		}
	);*/
	
	//slimScroll Function for user's list
	//--------------------------------
	$(function () {
		$('.quick-list').slimScroll({
			height: '120px'
		});
	});
	
	//slimScroll Function for To do portlet 
	//--------------------------------
	$(function () {
		$('.task-widget').slimScroll({
			height: '195px',
			alwaysVisible: false,
			disableFadeOut: true,
			touchScrollStep: 50
		});
	});
	
	//slimScroll Function for Live chat
	//--------------------------------
	$(function () {
		$('.log-activities').slimScroll({
			height: '195px',
			touchScrollStep: 50
		});
	});
	
	//slimScroll Function for Internal chat 
	//--------------------------------
	$(function () {
		$('.in-chat').slimScroll({
			height: '195px',
			alwaysVisible: false,
			disableFadeOut: true,
			touchScrollStep: 50
		});
	});
	
	//gritter only for demo
    /*$(window).load(function () {
		$.gritter.add({
            title: "Welcome back, John!",
            text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
            image: "assets/images/user-profile-1.jpg",
			class_name: "bg-success",
            sticky: false
		})
	})*/

});