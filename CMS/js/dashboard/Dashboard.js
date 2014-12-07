var Dashboard = 
{
	portlets : new Array(),
	
	responseHandler : {},
	
	addReloadBoxToPrtlets : function(){
		$('.dashboard-container').append('<div class="reload-icon"></div>');
	},
	
	getPortlets : function(){
		var self = this;
		$('.dashboard-container').each(function(i, value){
			var portlet = new Array();
			portlet.name = $(this).attr('id');
			self.portlets[i] = portlet;
		});
	},
	
	addReloadEvent : function(){
		var self = this;
		$('.dashboard-container .reload').click(function(){
			self.loadPortletData($(this).parents('.dashboard-container').attr('id'));
		});
	},
	
	loadDataForAllPortlets : function(){
		var self = this;
		$.ajax({
			url: '/index/load-data-for-all-portlets/portlets/' + self.getPortletsAsCommaSeperatedString(),
			success: function(response){
				self.handleResponse(response);
		  	}
		});
	},
	
	getPortletsAsCommaSeperatedString : function(){
		var portletsAsString = '';
		for (var i = 0; i < this.portlets.length; i++) {
			portletsAsString += this.portlets[i].name;
			if (i < this.portlets.length - 1) {
				portletsAsString += ',';
			}
		}
		return portletsAsString;
	},
	
	handleResponse : function(response){
		for (var i = 0; i < response.portletData.length; i++) {
			var functionName = 'handle' + response.portletData[i].portletName + 'Response';
			this.responseHandler[functionName](response.portletData[i].responseData);
		}
	},
	
	loadPortletData : function(portlet){
		$('#' + portlet).append('<div class="reload-icon"></div>');
		var self = this;
		$.ajax({
			url: '/index/load-dashboard-portlet/portlet/' + portlet + '/force-reload/true',
			success: function(response){
				self.handleResponseForOnePortlet(response, portlet);
		  	}
		});
	},
	
	handleResponseForOnePortlet : function(response, portlet){		
		var functionName = 'handle' + this.ucfirst(this.dashToCamelCase(portlet)) + 'Response';
		this.responseHandler[functionName](response.portletData);
	},
	
	dashToCamelCase : function(string){
		if (string.split('-')) {
			var parts = string.split('-');
			if (parts && parts.length > 1) {
				var camelCased = parts[0];
				for (var i = 1; i < parts.length; i++) {
					camelCased += parts[i].charAt(0).toUpperCase();
					camelCased += parts[i].substr(1);
				}
				return camelCased;
			}
		} 
		return string;
	},
	
	ucfirst: function(string){
		var ucfirst = string.charAt(0).toUpperCase();
		ucfirst += string.substr(1);
		return ucfirst;
	},
	init : function(){		
		this.addReloadBoxToPrtlets();
		this.getPortlets();
		this.addReloadEvent();
		this.loadDataForAllPortlets();
	}
}

Dashboard.responseHandler.handleThirdPartySystemsResponse = function(response){
	for (var i = 0; i < response.length; i++) {
		var statusClass = (response[i].status) ? 'active' : 'inactive';
		$('#' + response[i].name + ' .status').html('<span class="' + statusClass + '">&nbsp;</span>');
	}	 
	$('#third-party-systems .reload-icon').hide();
 }

Dashboard.responseHandler.handleShopKpisResponse = function(response){
	for (var i = 0; i < response.length; i++) {		
		$('#' + response[i].name).html(response[i].value);
	}
	$('#shop-kpis .reload-icon').hide();
}

Dashboard.responseHandler.handleOrderItemStatusResponse = function(response){
	for (var i = 0; i < response.length; i++) {
		$('#' + response[i].name).html(response[i].value);
	}
	$('#order-item-status .reload-icon').hide();
}

Dashboard.responseHandler.handleNotificationsResponse = function(response){
	var html = '';
	for (var i = 0; i < response.length; i++) {
		html += '<div class="notification"><table><tr>'
			 + '<td class="date">' + response[i].updated_at + '</td>'
			 + '<td class="title"><a href="/notification/index/search?q[query]=*&q[search]=search">' + response[i].title + '</a></td>'
			 + '</tr></table></div>';
	}
	$('#notifications-container').html(html);
	$('#notifications .reload-icon').hide();
}

Dashboard.responseHandler.handleErpCallsResponse = function(response){
	$('#erp-calls .error').remove();
	for (var i = 0; i < response.length; i++) {
		var statusClass = (response[i].status) ? 'active' : 'inactive';		
		if (response[i].status) {
			$('#' + response[i].name + ' .status').html('<span class="active">&nbsp;</span>');
		} else {
			$('#' + response[i].name + ' .status').html('<span class="inactive">&nbsp;</span>');
			if (response[i].error) {
				$('#' + response[i].name).append('<div class="error">' + response[i].error + '</div>');
			}
		}		
	}
	$('#erp-calls .reload-icon').remove();
 }

Dashboard.responseHandler.handleCatalogStatusResponse = function(response){
	for (var i = 0; i < response.length; i++) {
		$('#' + response[i].name).html(response[i].value);
	}
	$('#catalog-status .reload-icon').hide();
}

Dashboard.responseHandler.handleRatingsResponse = function(response){
	for (var i = 0; i < response.length; i++) {
		$('#' + response[i].name).html(response[i].value);
	}
	$('#ratings .reload-icon').hide();
}

Dashboard.responseHandler.handleOrderChartResponse = function(response) {	
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Hour');
    data.addColumn('number', 'Orders');
    
    data.addRows(response.length);
    for (var i = 0; i < response.length; i++) {
    	data.setCell(i, 0, response[i].time);
        data.setCell(i, 1, response[i].count);
    }
    var options = {
    	chartArea: {
    		left: 50,
    		width: '100%'
    	},
    	height: 300,
    	curveType: 'function',
    	legend: 'none',
    	pointSize: 5,
    	vAxis: {
    		title: 'Orders',
    		textPosition: 'out'
    	}
    }
    var chart = new google.visualization.LineChart(document.getElementById('order-chart-container'));
    chart.draw(data, options);
    $('#order-chart .reload-icon').hide();
}