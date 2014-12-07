/**
 * Extjs for Category Management
 * @author 	Tim Schelhas
 */
Ext.ns('Bob');

/*************************************************
 * Build Main Panel
 * 
 */
Bob.CategoryPanel = Ext.extend(Ext.Panel, {

	id			: 'EditCategory_Panel',
	frame		: false,
	border		: false,
    layout      : 'fit',
    plugins     : ['fittoparent'],

	initComponent	: function() {
        this.segments = new Category.Segments.Store();
        this.segments.on('load',  this.buildUi, this);
	},
	
	buildUi : function () {
	    this.items = this.buildPanel();
        Bob.CategoryPanel.superclass.initComponent.apply(this, arguments);
        
        // Set Category Edit Form Handler
        this.updateFrontendButton.setHandler(function() {
            this.onUpdateFrontend();
        }, this);
            
        this.render('main');
	},
	
	onUpdateFrontend: function(){
		Ext.MessageBox.confirm(
				'Are you sure?',
				'All Changes will be commited to the frontend',
				this.onUpdateFrontendConfirm
			);
	},
	
	onUpdateFrontendConfirm: function(btn){
		if (btn == 'yes') {
			Ext.getCmp('EditCategory_Panel').el.mask('Processing...', 'x-mask-loading');
			Ext.Ajax.request({
				url : '/catalog/category/update-frontend',
				params : {
					id 			: ''
				},
				success : function (response, opts) {
					Ext.getCmp('EditCategory_Panel').el.unmask();
					var responseJson = Ext.decode(response.responseText);
					if (responseJson.success === true) {
						Ext.Msg.show({
							title	: 'Info',
							msg		: responseJson.message,
							icon	: Ext.MessageBox.INFO,
							buttons : Ext.Msg.OK
						});

						(function(){ categorypanel.updateFrontendMessage.getEl().fadeOut();}).defer(500);
						
					}else {
						Ext.Msg.show({
							title	: 'Error',
							msg		: responseJson.message,
							icon	: Ext.MessageBox.ERROR,
							buttons : Ext.Msg.OK
						});
					}
				},
				failure : function (response, opts) {
					Ext.getCmp('EditCategory_Panel').el.unmask();
					Ext.Msg.show({
						title	: 'Error',
						msg		: 'An error occured with the server.',
						icon	: Ext.MessageBox.ERROR,
						buttons : Ext.Msg.OK
					});
				}
			})
		}
	},

	buildPanel	: function() {
		return [
            {
                layout : 'vbox',
                border: false,
                height: 100,

                layoutConfig : {
                    pack  : 'start',
                    align : 'stretch'
                },
                items : [
                    {
                        layout	: 'hbox',
                        layoutConfig: {
                            pack  : 'start'
                        },
                        height	: 30,
                        border	: false,
                        padding	: 0,

                        items	: [{
                            flex	: 1,
                            border	: false,
                            xtype	: 'container',
                            ref		: '../../updateFrontendMessage',
                            style	: {
                                visibility: 'hidden'
                            },
                            items	: [{
                                xtype	: 'container',
                                cls		: 'infomessage',
                                html	: 'Changes have been exported to be displayed in the frontend'
                            }]
                        },{
                            xtype	: 'button',
                            type	: 'submit',
                            width	: 120,
                            text	: 'Update Frontend',
                            id		: 'updateFrontendButton',
                            ref		: '../../updateFrontendButton',
                            hidden	: false
                        }],
                        scope   : this
                    },
                    {
                        flex    : 1,
                        xtype	: 'container',
                        layout	: 'hbox',
                        layoutConfig : {
                            align : 'stretch'
                        },
                        border	: false,
                        frame	: false,
                        cls		: 'extras-fonted',
                        items	: [
                            {
                                xtype		: 'bob.categorypanel.treepanel',
                                width       : 300,
                                layout      : 'fit',
                                id			: 'categoryTreePanel',
                                border		: true,
                                frame		: false
                            },
                            new Ext.Spacer({width: 5}),
                            {
                                xtype			: 'tabpanel',
                                id				: 'categoryTabs',
                                flex            : 2,
                                activeTab		: 0,
                                tabPosition		: 'top',
                                border			: true,
                                items			: [
                                    {
                                        title		  : 'Edit',
                                        xtype		  : 'bob.categorypanel.categoryeditpanel',
                                        id			  : 'categoryEditFormPanel',
                                        itemid		  : 'categoryEditFormTab',
                                        bodyStyle	  : {"background-color":"#dfe8f6"},
                                        border		  : false,
                                        frame		  : false,
                                        segmentsStore : this.segments

                                    },
                                    {
                                        title		: 'Assigned Products',
                                        xtype		: 'bob.categorypanel.productgrid',
                                        id			: 'assignedProductsPanel',
                                        bodyStyle	:{"background-color":"#dfe8f6"},
                                        border		: false,
                                        frame		: false,
                                        tabTip		:		'Products have to be assigned to a category via PET -> Edited content '
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }

        ]
	}
});





Ext.reg('bob.categorypanel', Bob.CategoryPanel);

Ext.onReady(function(){	
	Ext.QuickTips.init();
	categorypanel = new Bob.CategoryPanel();
});