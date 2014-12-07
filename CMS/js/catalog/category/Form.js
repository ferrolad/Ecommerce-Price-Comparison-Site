/**
 * Extjs for Category Management
 * @author 	Tim Schelhas
 */
Ext.ns('Bob');

/*************************************************
 * Category Edit Panel
 * 
 */
Bob.CategoryEditPanel = Ext.extend(Ext.Panel, {
	
	id			: 'categoryEditForm',
	layout      : 'fit',
	
	initComponent	: function() {
		
		this.items = this.buildPanel();
		
		Bob.CategoryEditPanel.superclass.initComponent.apply(this, arguments);
		
		// Set Category Edit Form Handler
		Ext.getCmp('categoryedit_submitButton').setHandler(function() {
			this.updateCategory();
		}, this);
		
		// Set delete Handler
		Ext.getCmp('categoryedit_deleteButton').setHandler(function() {
			//this.deleteCategory();
			var id = this.id.value;
			var name = this.findById('name_en').value;
			toDelete = {
					action: 'button',
					id: id,
					name: name
			}
			onDelete(toDelete);
		}, this);
		
		// Set delete Handler
		Ext.getCmp('categoryedit_cancelButton').setHandler(function() {
			this.resetForm();
		}, this);
		
		// disable panel on load, because no category is selected
		Ext.getCmp('categoryTabs').disable();
		
	},
	
	resetForm: function(){
		
		//this.categoryEditForm.disable();
		this.categoryEditForm.getForm().reset();
		Ext.getCmp('categoryTabs').disable();
		//Ext.getCmp('categoryTreePanel').enable();
	
	},
	
	setEditFormData : function(node){
		
		//Ext.getCmp('categoryTreePanel').disable();
		this.categoryEditForm.enable();
		Ext.getCmp('categoryTabs').enable();
		this.categoryEditForm.setTitle('Edit Category ' + node.attributes.name);
		Ext.getCmp('id').setValue(node.id);
		Ext.getCmp('parentid').setValue(node.parentNode.id);
		Ext.getCmp('name').setValue(node.attributes.name);
		Ext.getCmp('url_key').setValue(node.attributes.url_key);
		Ext.getCmp('url_key_hidden').setValue(node.attributes.url_key);
		Ext.getCmp('name_en').setValue(node.attributes.name_en);
		Ext.getCmp('segments').setValue(node.attributes.segments.join(','));
		
		// add value deleted
		// create status 'deleted' if required
        if (node.attributes.status == 'deleted') {
            var record = new this.status.store.recordType({
            	name : 'Deleted',  
            	value: 'deleted'
            });
            this.status.store.add(record);
        }else{
        	  this.status.store.removeAt(2);
        };

		/*  deactivated meta text fields
		    may be used again later... ;-)
				Ext.getCmp('meta_title').setValue(node.attributes.meta_title);
				Ext.getCmp('meta_keywords').setValue(node.attributes.meta_keywords);
				Ext.getCmp('meta_description').setValue(node.attributes.meta_description);
		 */

		Ext.getCmp('status').setValue(node.attributes.status);
		Ext.getCmp('status_hidden').setValue(node.attributes.status);
    	Ext.getCmp('path').setValue(node.attributes.path);
    	Ext.getCmp('editmode').setValue('update');
    	Ext.getCmp('url_key_check').setValue(true);
    	// remove delete button if category has products or categories in it
    	if(node.attributes.product_count > 0 || node.attributes.subcategories_count > 0){
            Ext.getCmp('categoryedit_deleteButton').hide();
		}else{
            Ext.getCmp('categoryedit_deleteButton').show();
		}	
	},
	
	setNewFormData : function(parentnode){
		Ext.getCmp('categoryTabs').enable();
		this.categoryEditForm.getForm().reset();
		this.categoryEditForm.enable();
		this.categoryEditForm.setTitle('Create new Category');
        Ext.getCmp('categoryedit_deleteButton').hide();
		Ext.getCmp('parentid').setValue(parentnode.id);
		Ext.getCmp('path').setValue(parentnode.attributes.path);
		Ext.getCmp('editmode').setValue('add');
		Ext.getCmp('status').setValue('active');
        Ext.getCmp('url_key_check').setValue(true);
        
        if (parentnode.attributes.segments) {
            Ext.getCmp('segments').setValue(parentnode.attributes.segments.join(','));
        }
	},
	
	updateCategory	: function() {
		
		var formPanel = this.categoryEditForm;
		
		// check URL Key before Save
		if(Ext.getCmp('url_key_check').getValue()!='true') {
			Ext.Msg.show({
				title	: 'Error',
				msg		: 'The Url Key exists in the Database',
				icon	: Ext.MessageBox.ERROR,
				buttons : Ext.Msg.OK
			});
			
		}else{
		
			if (formPanel.getForm().isValid()) {
				
				var treePanel = Ext.getCmp('categoryTreePanel');
				
				// update or insert new category ? 
				var url;
				if(this.editmode.getValue() == 'update'){
					url	= '/catalog/category/update/id';
				}else{
					url	= '/catalog/category/add/id';
				}
				
		        Ext.get('categoryEditForm').mask('Please wait', 'x-mask-loading');
				
				formPanel.getForm().submit({
					
					url	: url,
		            success : function(form, action) {
		            	Ext.get('categoryEditForm').unmask();
		            	
						var responseJson = Ext.decode(action.response.responseText);
						if (responseJson.success === true) {
//							Ext.Msg.show({
//								title	: 'Info',
//								msg		: responseJson.message,
//								icon	: Ext.MessageBox.INFO
//							});
							

		                    (function(){ categorypanel.updateFrontendMessage.getEl().slideIn('r', { duration: 1.5, easing: 'elasticOut' }); }).defer(500);

					        // Reload Tree
							Ext.getCmp('categoryTreePanel').reloadTree();
							this.editmode.setValue('update');
							if(responseJson.id!=undefined){
								// set id for new created category
								this.id.setValue(responseJson.id);
							}	
						}else {
							Ext.Msg.show({
								title	: 'Error',
								msg		: responseJson.message,
								icon	: Ext.MessageBox.ERROR,
								buttons : Ext.Msg.OK
							});
						}
		            },
					failure	: function(form, action) {
						Ext.get('categoryEditForm').unmask();
					},
					scope	: this
				});
			} else {
				Ext.Msg.show({
					title	: 'Error',
					msg		: 'Please complete the required fields and check the input in the red marked fields',
					icon	: Ext.MessageBox.ERROR,
					buttons : Ext.Msg.OK
				});
			}
		}
	},
	
	
	buildPanel	: function() {
		
		// set URL Key if Empty
		this.setUrlKey = function(){
			urlkey = Ext.getCmp('url_key');
			if(urlkey.getValue()==''){
				// clean name
				newkey = Ext.getCmp('name').getValue().replace( /\s+/g, '-' );
				newkey = newkey.toLowerCase();
				newkey = newkey.replace( /ä/g, 'ae' );
				newkey = newkey.replace( /ü/g, 'ue' );
				newkey = newkey.replace( /ö/g, 'oe' );
				newkey = newkey.replace( /Ä/g, 'Ae' );
				newkey = newkey.replace( /Ü/g, 'Ue' );
				newkey = newkey.replace( /Ö/g, 'Oe' );
				newkey = newkey.replace( /é|è|ê/ig, 'e');
				newkey = newkey.replace( /â|á|� /ig, 'a');
				newkey = newkey.replace( /ô|ó|ò/ig, 'o');
				newkey = newkey.replace( /[ß]/g, 'ss' );
				newkey = newkey.replace( /[-]{2,}/g, '-');
				newkey = newkey.replace( /[^-a-z0-9_]/ig, '' )
				urlkey.setValue(newkey);
			};
			Ext.getCmp('categoryEditFormPanel').checkUrlKey();
		},	
		
		this.checkUrlKey = function(){
			
			if(Ext.getCmp('url_key').getValue()==''){
				Ext.getCmp('url_key_check').setValue('true');
				return true;
			}
			
			if(Ext.getCmp('url_key').getValue() == Ext.getCmp('url_key_hidden').getValue()){
				Ext.getCmp('url_key_check').setValue('true');
				return true;
			}	
			
			Ext.Ajax.request({
				url : '/catalog/category/check-url-key',
				params : {
					urlkey 			: Ext.getCmp('url_key').getValue(),
					parentid		: Ext.getCmp('parentid').getValue(),
					categoryid		: Ext.getCmp('id').getValue()
				},
				success : function (response, opts) {
					var responseJson = Ext.decode(response.responseText);
					if (responseJson.success != true) {
						Ext.Msg.show({
							title	: 'Error',
							msg		: responseJson.message,
							icon	: Ext.MessageBox.ERROR,
							buttons : Ext.Msg.OK
						});
						Ext.getCmp('url_key').markInvalid();
						Ext.getCmp('url_key_check').setValue('false');
						
					}else{
						Ext.getCmp('url_key_check').setValue('true');
					}
				},
				failure : function (response, opts) {
					Ext.Msg.show({
						title	: 'Error',
						msg		: 'An error occured with the server.',
						icon	: Ext.MessageBox.ERROR,
						buttons : Ext.Msg.OK
					});
					Ext.getCmp('url_key').markInvalid();
					Ext.getCmp('url_key_check').setValue('true');
				}
			})
			
		},
		
		this.buildSegments = function() {
		    var items = [];
		    this.segmentsStore.each(function(record) {
		        var segment = {
		                boxLabel   : record.data.name_en,
		                inputValue : record.id,
                        name       : 'segments[]'
		        };
		        items.push(segment);
		    });
		    
		    if (items.length == 0) {
		        items.push({});
		    }
		    
		    return items;
		};
		
        this.form = new Ext.FormPanel({

            id			: 'categoryEditForm',
            layout		: 'form',
            border		: false,
            frame		: true,
            padding		: 10,
            title		: 'Edit Category',
            ref			: 'categoryEditForm',
            autoScroll  : true,
            defaults	: {
                anchor	: '100%',
            },
            items		: [	
                {
                    xtype		: 'hidden',
                    fieldName	: 'editmode',
                    id			: 'editmode',
                    ref			: '../editmode',
                    isMandatory	: true,
                    readOnly    : true
                },
                {
                    xtype		: 'hidden',
                    fieldName	: 'parentid',
                    id			: 'parentid',
                    ref			: '../parentid',
                    isMandatory	: false,
                    readOnly    : true
                },
                {
                    xtype		: 'textfield',
                    style		: 'border: 0; background: none;',
                    fieldName	: 'id',
                    id			: 'id',
                    ref			: '../id',
                    fieldLabel	: 'Category Id',
                    isMandatory	: true,
                    readOnly    : true
                },
                {
                    xtype		: 'textfield',
                    fieldName	: 'name',
                    id			: 'name',
                    fieldLabel	: 'Name',
                    labelStyle : 'color: #CC3300',
                    isMandatory	: true,
                    allowBlank	: false,
                    emptyText : 'This field is required!',
                    listeners	: {
                        change: this.setUrlKey
                    }
                },
                {
                    xtype		: 'textfield',
                    fieldName	: 'name_en',
                    id			: 'name_en',
                    fieldLabel	: 'Name English',
                    allowBlank	: true,
                },
                {
                    xtype			: 'combo',
                    ref				: '../status',
                    mode			: 'local',
                    triggerAction	:  'all',
                    editable        : false,
                    fieldLabel		: 'Status',
                    name			: 'status',
                    hiddenName		: 'status',
                    labelStyle 		: 'color: #CC3300',
                    id				: 'status',
                    displayField	: 'name',
                    valueField		: 'value',
                    allowBlank		: false,
                    store:          new Ext.data.JsonStore({
                        fields : ['name', 'value'],
                        data   : [
                        {name : 'Active',   value: 'active'},
                        {name : 'Inactive',  value: 'inactive'},
                        /*{name : 'Deleted',  value: 'deleted'}*/
                        ]
                        }),
                        listeners       : {
                            select : function(el) {
                                if (el.value == 'inactive' && Ext.getCmp('status_hidden').getValue() != 'inactive' &&
                                        Ext.getCmp('status_hidden').getValue() != 'deleted' && Ext.getCmp('id').getValue() != '') {

                                    Ext.getBody().mask();
                                    Ext.Ajax.request({
                                        url: '/catalog/category/get-products-to-be-deactivated/',
                                        params : {
                                            id 			: Ext.getCmp('id').getValue()
                                        },
                                        success : function (response, opts) {
                                            Ext.getBody().unmask();
                                            var responseJson = Ext.decode(response.responseText);
                                            if (responseJson.success == true) {
                                                Ext.Msg.show({
                                                    title: 'WARNING',
                                                    msg: responseJson.message,
                                                    buttons: Ext.Msg.YESNO,
                                                    icon: Ext.MessageBox.QUESTION,
                                                    fn: function(buttonId) {
                                                        if (buttonId == 'yes') {
                                                            return true;
                                                        } else {
                                                            el.setValue('active');
                                                        }
                                                    }
                                                });
                                            }
                                        },
                                        failure : function (response, opts) {
                                            Ext.getBody().unmask();
                                            Ext.Msg.show({
                                                title	: 'Error',
                                                msg		: 'An error occured with the server.',
                                                icon	: Ext.MessageBox.ERROR,
                                                buttons : Ext.Msg.OK
                                            });
                                        }
                                    })
                                }
                            }
                        }
                    },
                    {
                        xtype		: 'hidden',
                        fieldName	: 'status_hidden',
                        id			: 'status_hidden',
                    },
                    {
                        xtype		: 'textfield',
                        fieldName	: 'url_key',
                        id			: 'url_key',
                        ref			: '../url_key',
                        fieldLabel	: 'URL Key',
                        allowBlank	: true,
                        regex		: /^[^ ?&/"\\°^'`´.;,:]*$/i,
                        regexText   : 'The Url Key contains special characters. Please check.',
                        listeners	: {
                            change: this.checkUrlKey
                        }
                    },
                    {
                        id          : 'segments',
                        ref         : '../segments',
                        xtype       : 'checkboxgroup',
                        fieldLabel  : 'Segments',
                        itemCls     : 'x-check-group-alt',
                        columns     : 1,
                        items       : this.buildSegments(),
                        hidden      : !this.segmentsStore.getCount()

                    },
                    {
                        xtype		: 'displayfield',
                        fieldLabel	: 'Position in tree',
                        id			: 'path',
                        style		: 'border: 0; background: none;',
                        readOnly    : true
                    },
                    {
                        xtype		: 'hidden',
                        fieldName	: 'url_key_hidden',
                        id			: 'url_key_hidden',
                    },
                    {
                        xtype		: 'hidden',
                        fieldName	: 'url_key_check',
                        id			: 'url_key_check',
                    }
                ],
                buttons : [
                    {
                        //iconCls : 'ok',
                        xtype	: 'button',
                        type	: 'submit',
                        width	: 95,
                        frame	: false,
                        text	: 'Save',
                        id		: 'categoryedit_submitButton',
                        ref		: 'submitButton'
                    },
                    {
                        xtype	: 'button',
                        style	: 'margin-left: 10px',
                        type	: 'cancel',
                        width	: 95,
                        text	: 'Cancel',
                        id		: 'categoryedit_cancelButton',
                        ref		: 'cancelButton'
                    },
                    {
                        xtype	: 'button',
                        style	: 'margin-left: 10px',
                        type	: 'submit',
                        width	: 95,
                        text	: 'Delete',
                        id		: 'categoryedit_deleteButton',
                        ref		: 'deleteButton'
                    }
                ]
            });
            return [
            this.form
            ]
			
	}	
});


Ext.reg('bob.categorypanel.categoryeditpanel', Bob.CategoryEditPanel);