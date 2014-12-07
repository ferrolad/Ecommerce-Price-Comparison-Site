/**
 * Category -> Segments
 * @author Fabian Huhn <fabian.huhn@rocket-internet.de>
 */
Ext.ns('Category.Segments');


/**
 * @class Category.Store
 * @extends Ext.data.Store
 * Store for segments - handles reading category segmnets
 */
Category.Segments.Store = Ext.extend(Ext.data.Store, {

    record : Ext.data.Record.create([
        {
            name: 'id_catalog_segment',
            type: 'integer'
        }, {
            name: 'name',
            type: 'string'
        }, {
            name: 'name_en',
            type: 'string'
        }
    ]),

    constructor: function(config) {

        // set default config
        config = Ext.applyIf(config||{}, {
    
            storeId : 'segments',

            reader  : new Ext.data.JsonReader({
                root            : 'records',
                idProperty      : 'id_catalog_segment',
                totalProperty   : 'total',
                successProperty : 'success',
                messageProperty : 'message'
            }, this.record),
    
            url         : '/catalog/category/segments',
            autoLoad    : true
        }); 
    
        Category.Segments.Store.superclass.constructor.call(this, config);
    }

});

Ext.reg('segmentsstore', Category.Segments.Store);