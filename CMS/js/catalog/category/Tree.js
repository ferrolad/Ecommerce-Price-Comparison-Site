/**
 * Extjs Tree for Category Management
 * @author 	Tim Schelhas
 */
Ext.ns('Bob');

/************************************************************
 * Tree functions
 * 
 */

var onCtxEdit = function() {
	var selectedNode = Ext.getCmp('categoryTreePanel').getSelectedNode();
	onEdit(selectedNode);
}

var onEdit = function(selectedNode) {
	
	if ("undefined" == selectedNode){
		var selectedNode = Ext.getCmp('categoryTreePanel').getSelectedNode();
	}
	
	form = Ext.getCmp('categoryEditFormPanel');
	form.setEditFormData(selectedNode);
	
	// load assigned Products
	Ext.getCmp('assignedProductsPanel').reloadStore(selectedNode);
	
	// activate Tab
	Ext.getCmp('categoryTabs').setActiveTab(0);
	
	/*if(selectedNode.attributes.product_count > 0){
	}else{
		Ext.getCmp('assignedProductsPanel').resetStore();
	}*/
};


var onAdd = function() {
	
	var selectedNode = Ext.getCmp('categoryTreePanel').getSelectedNode();
	
	form = Ext.getCmp('categoryEditFormPanel');
	form.setNewFormData(selectedNode);
	
	// activate Tab
	Ext.getCmp('categoryTabs').setActiveTab(0);

};

var onDelete = function(toDelete) {
	
	if(typeof toDelete != "undefined" && toDelete.action !== 'button')
	{
		var selectedNode = Ext.getCmp('categoryTreePanel').getSelectedNode();
		var toDelete = {
			id: selectedNode.id,
			name: selectedNode.attributes.text
		};
	}
	
	if (toDelete) {
		deleteCategory = toDelete.id;
		Ext.MessageBox.confirm(
			'Are you sure?',
			'Please confirm the deletion of ' + toDelete.name,
			onConfirmDelete, toDelete
		)	
	}
};

var onConfirmDelete = function(btn) {
	if (btn == 'yes') {
		var treePanel = Ext.getCmp('categoryTreePanel');
		treePanel.el.mask('Deleting...', 'x-mask-loading');
		Ext.Ajax.request({
			url : '/catalog/category/delete/id',
			params : {
				id : this.id
			},
			success : function (response, opts) {
				treePanel.el.unmask();
				Ext.getCmp('categoryEditFormPanel').resetForm();
				var responseJson = Ext.decode(response.responseText);
				if (responseJson.success === true) {
					//selNode.remove();
					Ext.getCmp('categoryTreePanel').reloadTree();
					Ext.Msg.show({
						title	: 'Info',
						msg		: responseJson.message,
						icon	: Ext.MessageBox.INFO,
						buttons : Ext.Msg.OK
					});
				}
				else {
					Ext.Msg.show({
						title	: 'Error',
						msg		: responseJson.message,
						icon	: Ext.MessageBox.ERROR,
						buttons : Ext.Msg.OK
					});
				};
			
			},
			failure : function (response, opts) {
				treePanel.el.unmask();
				Ext.Msg.show({
					title	: 'Error',
					msg		: 'An error occured with the server.',
					icon	: Ext.MessageBox.ERROR,
					buttons : Ext.Msg.OK
				});
			}
		});
	}
};


var onMove = function(treePanel,node){
	
	if (node) {
		Ext.MessageBox.confirm(
			'Are you sure?',
			'Please confirm the move of ' + node.attributes.text,
			 function(btn){ 
				if (btn == 'yes') {
					var texta = '';
					var textb = '';
					
					var parentNode = null;
					var previousSibling = null;
					
					// move behind existing Category
					if(node.parentNode != undefined){
						parentNode =  node.parentNode;
					}
					
					if(node.previousSibling != undefined){
						previousSibling = node.previousSibling;
					}
				
					Ext.Ajax.request({
						url : '/catalog/category/move/id',
						params : {
							id 			: node.id,
							parentid	: (parentNode)?parentNode.id : null,
							previousid	: (previousSibling)?previousSibling.id : null
						},
						success : function (response, opts) {
							Ext.getCmp('categoryTreePanel').reloadTree();
							var responseJson = Ext.decode(response.responseText);
							if (responseJson.success === true) {
								Ext.Msg.show({
									title	: 'Info',
									msg		: responseJson.message,
									icon	: Ext.MessageBox.INFO,
									buttons : Ext.Msg.OK
								});
							}
							else {
								Ext.Msg.show({
									title	: 'Error',
									msg		: responseJson.message,
									icon	: Ext.MessageBox.ERROR,
									buttons : Ext.Msg.OK
								});
							}
						},
						failure : function (response, opts) {
							Ext.getCmp('categoryTreePanel').reloadTree();
							Ext.Msg.show({
								title	: 'Error',
								msg		: 'An error occured with the server.',
								icon	: Ext.MessageBox.ERROR,
								buttons : Ext.Msg.OK
							});
						}
					});
					
				}else{
					Ext.getCmp('categoryTreePanel').reloadTree();
				}
			}	
	)}
};

/************************************************************
 * Build Category TreePanel
 * 
 */

Bob.CategoryTree = Ext.extend(Ext.Panel, {
	
	id: 'categorytreepanel',
	frame			: false,
	border			: false,
	layout          : 'fit',
	
	initComponent	: function() {
		
		this.items = this.buildPanel();
		
		Bob.CategoryTree.superclass.initComponent.apply(this, arguments);
		
	},
	
	showDeleted : function(value){
		var treePanel = this.tree;
		var treeLoader = treePanel.loader;
		treeLoader.baseParams.showdeleted=value;
		treeLoader.load(treePanel.getRootNode());
		treePanel.getRootNode().expand();
	},
	
	reloadTree : function(){
		this.tree.reload();
	},
	
	getSelectedNode : function(){
		return this.tree.getSelectionModel().getSelectedNode();
	},
	
	getTree : function(){
		return this.tree;
	},
	
	collapseAll : function(){
		var treePanel = this.tree;
		var treeLoader = treePanel.loader;
		treeLoader.baseParams.expandall=false;
		treeLoader.baseParams.collapseall=true;
		treeLoader.load(treePanel.getRootNode());
		treePanel.getRootNode().expand();
	},
	
	expandAll : function(){
		var treePanel = this.tree;
		var treeLoader = treePanel.loader;
		treeLoader.baseParams.expandall=true;
		treeLoader.load(treePanel.getRootNode());
		treePanel.getRootNode().expand();
	},
	
	onCtxMenu : function(node, evtObj) {
		
		evtObj.stopEvent();
		node.select();
		
		/*onAddNode = function() {
			
			alert('add');
			var selectedNode = Ext.getCmp('categoryTreePanel').getSelectedNode();
			
			form = Ext.getCmp('categoryEditFormPanel');
			form.setNewFormData(selectedNode);
			
			// activate Tab
			Ext.getCmp('categoryTabs').setActiveTab(0);

		};*/
		
		if (! this.ctxMenu) {
			this.ctxMenu = new Ext.menu.Menu({
						items: [
							{
								itemId : 'add',
								handler : onAdd,
								scope : onAdd
							},
							{
								itemId : 'edit',
								handler : onCtxEdit,
								scope : onCtxEdit
							},
							{
								itemId : 'delete',
								handler : onDelete,
								scope : onDelete
							}
						]
				});	
		};	
		var ctxMenu = this.ctxMenu;
		
		var addItem = ctxMenu.getComponent('add');
		var editItem = ctxMenu.getComponent('edit');
		var deleteItem = ctxMenu.getComponent('delete');
		
		addItem.setText('Add Subcategory');
		if (node.attributes.status == 'deleted'){
			addItem.disable();
		} else {
			addItem.enable();
		}	
		
		if (node.id =='root') {
			editItem.setText('Root Category can\'t be edited');
			editItem.disable();
			deleteItem.setText('Root Category can\'t be deleted');
			deleteItem.disable();
		} else {
			if(node.attributes.product_count > 0){
				deleteItem.setText('You can\'t delete a Category with products in it');
				deleteItem.disable();
			}else if(node.attributes.subcategories_count > 0){
				deleteItem.setText('You can\'t delete a Category with Subcategories');
				deleteItem.disable();
			}else {	
				deleteItem.setText('Delete Category');
				if(node.attributes.status == 'deleted'){
					deleteItem.disable();
				}else{
					deleteItem.enable();
				}
			}
			editItem.setText('Edit Category');
			editItem.enable();
		}; 
		ctxMenu.showAt(evtObj.getXY());
	},
	
	buildPanel : function(){
		this.tree = new Ext.tree.TreePanel({
						id				: 'categoryTree',
					    animate			: true,
					    enableDD		: true,
					    containerScroll	: true,
						margins			: 10,
						padding			: 10,
						frame			: false,
						border			: false,
						autoScroll		: true,
						height			: 397,
						title			: 'Category Tree',
						loader			: new Ext.tree.TreeLoader({
							dataUrl			: '/catalog/category/load-categories/id',
							nodeParameter	: 'id'
						}),
						root		: {
							text		: 'Root Category', 
							id			: 'root',
							expanded	: true, 
							draggable	: false
						},
						listeners : {
							contextmenu : this.onCtxMenu,
							
							render: function(tp){
								this.getRootNode().expand();
					        },
							click: function(selectedNode,evtObj){
								if(selectedNode.id != 'root'){
									this.resetCategory();
									onEdit(selectedNode);
								}
							},
					        dragdrop: function(tree,node){
					        	onMove(tree,node);
					        },
					        expandnode: function(selectedNode,evtObj){
					        	if(selectedNode.id != 'root'){
						        	Ext.Ajax.request({
						    			url : '/catalog/category/expand-node',
						    			params : {
						    				id : selectedNode.id
						    			}
						    		});
					        	}	
					        },
					        collapsenode: function(selectedNode,evtObj){
					        	if(selectedNode.id != 'root'){
						        	Ext.Ajax.request({
						    			url : '/catalog/category/collapse-node',
						    			params : {
						    				id : selectedNode.id
						    			}
						    		});
					        	}	
					        }
						},
						reload : function(expandall){
							this.loader.load(this.getRootNode());
							this.getRootNode().expand();
						},
						resetCategory : function(){
							Ext.getCmp('categoryEditFormPanel').resetForm();
							Ext.getCmp('assignedProductsPanel').resetStore();
						},
					    tbar    : [
									'->', // Fill
					      			{
					      				text    : 'Reload',
					      				handler : function() {
					      					Ext.getCmp('categoryTreePanel').reloadTree();
					      				}
					      			},
					      			'-',
					      			{
					      				text    : 'Collapse All',
					      				handler : function() {
					      					Ext.getCmp('categoryTreePanel').collapseAll();
					      				}
					      			},
					      			'-',
					      			{
					      				text    : 'Expand All',
					      				handler : function() {
					      					Ext.getCmp('categoryTreePanel').expandAll();
					      				}
					      			}
					      		],
					      bbar	: {
                              style : 'padding:4px;',
                              items : [
					          	  	'->', // Fill
					          	    {
                                        text    : 'Download as CSV',
                                        handler : function() {
    					          	  	    window.location = '/catalog/category/export';
                                        }
                                    },
                                    '-',
					          	  	{
                                        enableToggle    : true,
										text            : 'Show deleted',
										checked         : false,
										toggleHandler   : function (button, state) {
                                            Ext.getCmp('categoryTreePanel').showDeleted(state);
										}
									}
					          	  ]
                          }
					}
		);
					
					return [
				            this.tree
				   ];
		}
});

// override the processResponse method to tell it to look under 'records'
	Ext.override(Ext.tree.TreeLoader, {
	 processResponse : function(response, node, callback, scope){
	     var json = response.responseText;
	     try {
	         var o = response.responseData || Ext.decode(json);
	   o = o.records; // this is the important line
	         node.beginUpdate();
	         for(var i = 0, len = o.length; i < len; i++){
	             var n = this.createNode(o[i]);
	             if(n){
	                 node.appendChild((n));
	             }
	         }
	         node.endUpdate();
	         this.runCallback(callback, scope || node, [node]);
	     }catch(e){
	         this.handleFailure(response);
	     }
	 }
	});


Ext.reg('bob.categorypanel.treepanel', Bob.CategoryTree);