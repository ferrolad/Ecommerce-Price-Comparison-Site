/*
 * Active Star
 * By CarlPham (http://www.carlpham.info)
*/

var point = 2;
var width = 0;
var stars = new Array("one-star","two-stars","three-stars","four-stars","five-stars");

//on page load 
$(document).ready(function(){   
//	active_star(1,3);
	hide_AD('priceComparsion');
	hide_AD('review');
	hideViewAll();
});

function click_star(catId, point){
	//alert(catId);
	var width = 0;
	stars = new Array("one-star","two-stars","three-stars","four-stars","five-stars");
	width = 0;
	for(var i = 0; i < point; i++){   
	  width = (i + 1) * 26;
	  $('#' + catId + ' .' + stars[i]).css('background-image', 'url(img/star_rating.png)');
	  $('#' + catId + ' .' + stars[i]).css('background-position', 'left bottom');
	  $('#' + catId + ' .' + stars[i]).css('left', '0px');
	  $('#' + catId + ' .' + stars[i]).css('width', width + 'px');
	}
}

function click_star_small(catId, point){
	var width = 0;
	stars = new Array("one-star","two-stars","three-stars","four-stars","five-stars");
	width = 0;
	for(var i = 0; i < point; i++){   
	  width = (i + 1) * 20;
	  $('#' + catId + ' .' + stars[i]).css('background-image', 'url(img/star_rating_small.png)');
	  $('#' + catId + ' .' + stars[i]).css('background-position', 'left bottom');
	  $('#' + catId + ' .' + stars[i]).css('left', '0px');
	  $('#' + catId + ' .' + stars[i]).css('width', width + 'px');
	}
}

/*function StarRatingSelectedDefaule(){
	catArrayStar = new Array(2, 2, 4, 3, 1, 2);
	for(var i = 0; i < catArrayStar.length; i++){
	  	click_star(i + 1, catArrayStar[i]);
	}
}*/

/*function show_menu_click(event)
{
	if($('#box_MenuLeft').css('display')=='none')
	{
		$('#box_MenuLeft').css({
			'display':'block',
			'background' : '#FFFFFF',
			'position' : 'absolute',
			'z-index' : '1'
		});
	}
	else
	{
		$('#box_MenuLeft').css('display','none');
	}
}*/
function show_menu(event){
	$('#box_MenuLeft').css({
		'display':'block',
		'background' : '#FFFFFF',
		'position' : 'absolute',
		'z-index' : '100'
	});		
}

function close_menu(event)
{
	$('#box_MenuLeft').css('display','none');
}

/*---Hide and show banner AD in right detail page---*/
function show_AD(e){
	if(e=='review'){
		hide_AD('priceComparsion');	
		$('#show_AD_'+e).show();
	}else if(e=='reviewViewAll'){
		$('#show_AD_'+e).show();
	}else{
		hide_AD('review');	
		$('#show_AD_'+e).show();
	}
	
}

function hide_AD(e){
	if(e=='priceComparsion'){
		$('#show_AD_review').hide();
		$('#show_AD_reviewViewAll').hide();
	}
	hideViewAll(e);
	$('#show_AD_'+e).hide();	
}

/*---Hide and show product when click View All in detail page--*/
var flagViewAllPriceComparsion = true;
var flagViewAllReview = true;
function showViewAll(e){
	if(e == 'priceComparsion'){
		if(flagViewAllPriceComparsion){
			show_AD(e);
			$('#viewAllPriceComparsion').hide();
			$('#viewLessPriceComparsion').show();
			flagViewAllPriceComparsion = false;
			$('.jsViewAll').show();	
		}else{
			hide_AD(e);
			$('#viewAllPriceComparsion').show();
			$('#viewLessPriceComparsion').hide();
			flagViewAllPriceComparsion = true;
			$('.jsViewAll').hide();	
		}
	}else{
		//show_AD('reviewViewAll');
		if(flagViewAllReview){
		//	show_AD(e);
			$('#viewAllReview').hide();
			$('#viewLessReview').show();
			flagViewAllReview = false;
			$('.jsViewAll').show();	
		}else{
		//	hide_AD(e);
			$('#viewAllReview').show();
			$('#viewLessReview').hide();
			flagViewAllReview = true;
			$('.jsViewAll').hide();
		}
	}
}

function hideViewAll(e){
	$('.jsViewAll').hide();	
}
