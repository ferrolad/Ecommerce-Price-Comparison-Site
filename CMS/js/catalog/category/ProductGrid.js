/**
 * Extjs for Categorygrid
 * @author 	Tim Schelhas
 */
Ext.ns('Bob');



/*************************************************
 * Category Assigne Products Grid
 * 
 */
Bob.CategoryProductGrid = Ext.extend(Ext.Panel, {
	
	id			: 'categoryProductGridPanel',
	layout      : 'fit',
	border      : true,
	
	initComponent	: function() {
		
		this.items = this.buildPanel();
		
		Bob.CategoryProductGrid.superclass.initComponent.apply(this, arguments);
		
	},
	
	resetStore :function(id){
		store = Ext.getCmp('categoryProductGridList').getStore();
		store.removeAll();
	},
		
	
	reloadStore : function(node){
		//store = Ext.getCmp('categoryProductGridList').getStore();
		store = this.grid.getStore();
		store.setBaseParam('categoryid',node.id);
		store.reload();
	},
	
	
	
	buildPanel : function(){
		
		this.store = new Ext.data.JsonStore({
            url			: '/catalog/category/get-products-by-category-id',
            autoLoad	: true,
            autoDestroy : true,
            totalProperty: 'totalCount',
            ref			: 'productStoreTest',
            storeId		: 'productStore',
            id 			: 'productStore',
            root		: 'records',
            remoteSort	: true,
            fields: [
                {name: 'id'},
                {name: 'name'},
                {name: 'sku'},
                {name: 'status'}
            ]
        }),
		
		this.grid = new Ext.grid.GridPanel({
		        
		        	id		: 'categoryProductGridList',
		        	store	: this.store,
		            columns: [
			            {
			                id       :'name',
			                header   : 'Name', 
			                width    : .4, 
			                sortable : true, 
			                dataIndex: 'name'
			            },
			            {
			                id       : 'sku',
			                header   : 'SKU', 
			                width    : .3, 
			                sortable : true, 
			                dataIndex: 'sku'
			            },
			            {
			                header   : 'Status', 
			                width    : .15, 
			                sortable : true,
			                dataIndex: 'status'
			            }
		            ],
            	Â Â Â Â viewConfig : {
                		forceFit : true
                	},
		            stripeRows: true,
		            border: false,
		            frame: false,
		            // paging bar on the bottom
		            bbar: new Ext.PagingToolbar({
		                pageSize: 24,
		                store: this.store,
		                displayInfo: true,
		                displayMsg: 'Displaying Products  {0} - {1} of {2}',
		                emptyMsg: "No Products to display",
		            }),
			        listeners : {
			        	rowdblclick : function(grid) {
			        		var selection = grid.getSelectionModel().getSelections(); 
			                var data = selection[0].data;
			        		window.open('../../pet/product/#' + data.id,'Pet');
			        	}
			        }
		        }
		        );
			return [
	            this.grid
	        ];
	}
});


Ext.reg('bob.categorypanel.productgrid', Bob.CategoryProductGrid);