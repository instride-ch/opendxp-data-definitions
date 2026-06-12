/*
 * This source file is available under two different licenses:
 *  - GNU General Public License version 3 (GPLv3)
 *  - Data Definitions Commercial License (DDCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh) in combination with instride AG (https://www.instride.ch)
 * @license    GPLv3 and DDCL
 */

opendxp.registerNS('opendxp.plugin.datadefinitions.import.panel');

opendxp.plugin.datadefinitions.import.panel = Class.create({
    layoutId: 'data_definitions_import_definition_panel',
    storeId: 'data_definitions_definitions',
    iconCls: 'data_definitions_icon_import_definition',
    type: 'definition',

    url: {
        add: '/admin/data_definitions/import_definitions/add',
        delete: '/admin/data_definitions/import_definitions/delete',
        get: '/admin/data_definitions/import_definitions/get'
    },

    routing: {
        add: null,
        delete: null,
        get: null
    },

    providers: [],
    cleaners: [],
    interpreters: [],
    setters: [],
    filters: [],
    runners: [],
    persisters: [],

    getTopBar: function () {
        return [
            {
                text: t('add'),
                iconCls: 'opendxp_icon_add',
                itemId: 'add-button',
                handler: this.addItem.bind(this),
                disabled: !opendxp.settings['data-definitions-import-definition-writeable']
            }
        ];
    },

    getGridDisplayColumnRenderer: function (value, metaData, record, rowIndex, colIndex, store) {
        return value;
    },

    getDefaultGridDisplayColumnName: function () {
        return 'name';
    },

    getTreeNodeListeners: function () {
        return {
            itemclick: this.onTreeNodeClick.bind(this)
        };
    },

    onTreeNodeClick: function (view, record, item, index, e) {
        console.log('Clicked record:', record);
        console.log('Record ID:', record.id);
        console.log('Record data:', record.data);
        this.openItem(record.id);
    },

    addItem: function () {
        Ext.Ajax.request({
            url: this.url.add,
            method: 'POST',
            success: function (response) {
                var result = Ext.decode(response.responseText);
                if (result.success) {
                    this.grid.getStore().load();
                }
            }.bind(this)
        });
    },

    openItem: function (id) {
        console.log('openItem called with ID:', id);
        var itemClass = this.getItemClass();
        var item = new itemClass();
        item.id = id;
        item.parentPanel = this;
        this.panels.push(item);

        // Set onLoad callback before initializing
        item.onLoad = function() {
            console.log('onLoad callback triggered');
            var tabPanel = Ext.getCmp("opendxp_panel_tabs");
            if (tabPanel) {
                console.log('Adding panel to tabPanel');
                var itemPanel = item.getPanel();
                tabPanel.add(itemPanel);
                tabPanel.setActiveItem(itemPanel);
            } else {
                console.log('tabPanel not found');
            }
        }.bind(this);

        // Initialize item to load data from server
        item.initialize(id);
    },

    activate: function () {
        if (!this.layout) {
            this.getLayout();
        }
        var tabPanel = Ext.getCmp("opendxp_panel_tabs");
        if (tabPanel) {
            tabPanel.add(this.layout);
            tabPanel.setActiveItem(this.layout);
        } else {
            // Create a tab panel in a window if tab panel is not available
            if (!this.tabPanelWindow) {
                this.tabPanelWindow = new Ext.Window({
                    title: 'Data Definitions',
                    width: 1000,
                    height: 700,
                    layout: 'fit',
                    maximizable: true,
                    closable: true,
                    autoShow: true,
                    items: [{
                        xtype: 'tabpanel',
                        items: [this.layout]
                    }]
                });
            } else {
                this.tabPanelWindow.show();
            }
        }
    },

    getDefaultGridConfiguration: function () {
        var store = opendxp.globalmanager.get(this.storeId);
        return {
            store: store,
            columns: [
                {
                    text: 'ID',
                    dataIndex: 'id',
                    flex: 1,
                    renderer: this.getGridDisplayColumnRenderer
                },
                {
                    text: 'Name',
                    dataIndex: this.getDefaultGridDisplayColumnName(),
                    flex: 4,
                    renderer: this.getGridDisplayColumnRenderer
                }
            ],
            listeners: this.getTreeNodeListeners(),
            useArrows: true,
            autoScroll: true,
            animate: true,
            containerScroll: true,
            width: 200,
            split: true,
            tbar: this.getTopBar(),
            bbar: {
                items: [{
                    xtype: 'label',
                    text: '',
                    itemId: 'totalLabel'
                }, '->', {
                    iconCls: 'opendxp_icon_reload',
                    scale: 'small',
                    handler: function () {
                        if (this.grid && this.grid.getStore()) {
                            this.grid.getStore().load();
                        }
                    }.bind(this)
                }]
            },
            hideHeaders: false
        };
    },

    getTitle: function () {
        return t('data_definitions_import_definitions');
    },

    getLayout: function () {
        if (!this.layout) {
            this.grid = new Ext.grid.Panel(this.getDefaultGridConfiguration());
            this.layout = new Ext.Panel({
                title: this.getTitle(),
                iconCls: this.iconCls,
                layout: 'border',
                items: [this.grid]
            });
            if (typeof layoutTabPanel !== 'undefined') {
                layoutTabPanel.add(this.layout);
            }
        }
        return this.layout;
    },

    initialize: function () {
        this.panels = [];

        // Create the main store for import definitions
        var store = new Ext.data.JsonStore({
            autoLoad: true,
            proxy: {
                type: 'ajax',
                url: '/admin/data_definitions/import_definitions/list',
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                }
            },
            fields: ['id', 'name']
        });

        opendxp.globalmanager.add(this.storeId, store);

        Ext.Ajax.request({
            url: '/admin/data_definitions/import_definitions/get-config',
            method: 'GET',
            success: function (result) {
                var config = Ext.decode(result.responseText);

                this.providers = [];
                this.loaders = [];
                this.filters = [];
                this.interpreters = [];
                this.setters = [];
                this.cleaners = [];
                this.runners = [];
                this.persisters = [];

                config.providers.forEach(function (provider) {
                    this.providers.push([provider]);
                }.bind(this));

                config.loaders.forEach(function (loader) {
                    this.loaders.push([loader]);
                }.bind(this));

                config.filters.forEach(function (filter) {
                    this.filters.push([filter]);
                }.bind(this));

                config.interpreter.forEach(function (interpreter) {
                    this.interpreters.push([interpreter]);
                }.bind(this));

                config.setter.forEach(function (setter) {
                    this.setters.push([setter]);
                }.bind(this));

                config.cleaner.forEach(function (cleaner) {
                    this.cleaners.push([cleaner]);
                }.bind(this));

                config.runner.forEach(function (runner) {
                    this.runners.push([runner]);
                }.bind(this));

                config.persister.forEach(function (persister) {
                    this.persisters.push([persister]);
                }.bind(this));

                var providerStore = new Ext.data.ArrayStore({
                    data: this.providers,
                    fields: ['provider'],
                    idProperty: 'provider'
                });

                opendxp.globalmanager.add('importdefinitions_providers', providerStore);
                opendxp.globalmanager.add('data_definitions_providers', providerStore);

                var loaderStore = new Ext.data.ArrayStore({
                    data: this.loaders,
                    fields: ['loader'],
                    idProperty: 'loader'
                });

                opendxp.globalmanager.add('importdefinitions_loaders', loaderStore);
                opendxp.globalmanager.add('data_definitions_loaders', loaderStore);

                var filterStore = new Ext.data.ArrayStore({
                    data: this.filters,
                    fields: ['filter'],
                    idProperty: 'filter'
                });

                opendxp.globalmanager.add('importdefinitions_filters', filterStore);
                opendxp.globalmanager.add('data_definitions_filters', filterStore);

                var cleanersStore = new Ext.data.ArrayStore({
                    data: this.cleaners,
                    fields: ['cleaner'],
                    idProperty: 'cleaner'
                });

                opendxp.globalmanager.add('importdefinitions_cleaners', cleanersStore);
                opendxp.globalmanager.add('data_definitions_cleaners', cleanersStore);

                var interpretersStore = new Ext.data.ArrayStore({
                    data: this.interpreters,
                    fields: ['interpreter'],
                    idProperty: 'interpreter'
                });

                opendxp.globalmanager.add('importdefinitions_interpreters', interpretersStore);
                opendxp.globalmanager.add('data_definitions_interpreters', interpretersStore);

                var settersStore = new Ext.data.ArrayStore({
                    data: this.setters,
                    fields: ['setter'],
                    idProperty: 'setter'
                });

                opendxp.globalmanager.add('importdefinitions_setters', settersStore);
                opendxp.globalmanager.add('data_definitions_setters', settersStore);

                var runnersStore = new Ext.data.ArrayStore({
                    data: this.runners,
                    fields: ['runner'],
                    idProperty: 'runner'
                });

                opendxp.globalmanager.add('importdefinitions_runners', runnersStore);
                opendxp.globalmanager.add('data_definitions_runners', runnersStore);

                var persistersStore = new Ext.data.ArrayStore({
                    data: this.persisters,
                    fields: ['persister'],
                    idProperty: 'persister'
                });

                opendxp.globalmanager.add('importdefinitions_persisters', persistersStore);
                opendxp.globalmanager.add('data_definitions_persisters', persistersStore);

                opendxp.globalmanager.add('data_definitions_import_rule_conditions', config.import_rules.conditions);
                opendxp.globalmanager.add('data_definitions_import_rule_actions', config.import_rules.actions);

                this.getLayout();
                this.activate();
            }.bind(this)
        });
    },

    getItemClass: function () {
        return opendxp.plugin.datadefinitions.import.item;
    }
});
