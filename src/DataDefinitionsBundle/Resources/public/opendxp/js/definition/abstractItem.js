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

opendxp.registerNS('opendxp.plugin.datadefinitions.definition.abstractItem');

opendxp.plugin.datadefinitions.definition.abstractItem = Class.create({
    saveDisabled: function() {
        return false;
    },

    save: function (callback) {
        var saveData = this.getSaveData ? this.getSaveData() : this.data;

        Ext.Ajax.request({
            url: this.url.save,
            jsonData: saveData,
            method: 'POST',
            success: function (response) {
                var result = Ext.decode(response.responseText);

                if (result.success) {
                    if (callback) {
                        callback(result);
                    }
                    if (this.postSave) {
                        this.postSave(result);
                    }
                } else {
                    Ext.Msg.alert(t('error'), result.message || t('save_failed'));
                }
            }.bind(this),
            failure: function () {
                Ext.Msg.alert(t('error'), t('save_failed'));
            }
        });
    },

    upload: function () {
        // Stub method - should be overridden in subclasses
    },

    automapExact: function (callback) {
        // Stub method - should be overridden in subclasses
    },

    automapFuzzy: function (callback) {
        // Stub method - should be overridden in subclasses
    },

    initialize: function (id) {
        if (!id) {
            return; // Don't load if no ID provided
        }
        this.id = id;
        this.data = {};
        this.panelKey = this.panelKey || '';
        this.load();
    },

    load: function () {
        Ext.Ajax.request({
            url: this.url.get,
            params: {
                id: this.id
            },
            method: 'GET',
            success: function (result) {
                console.log('Load response:', result.responseText);
                var response = Ext.decode(result.responseText);
                if (response.success) {
                    this.data = response.data;
                    if (this.onLoad) {
                        this.onLoad();
                    }
                }
            }.bind(this)
        });
    },

    getPanel: function () {
        var me = this,
            panel = new Ext.TabPanel({
                activeTab: 0,
                title: this.data.name + ' (' + this.data.id + ')',
                closable: true,
                deferredRender: false,
                forceLayout: true,
                iconCls: this.iconCls,
                buttons: [
                    {
                        text: t('data_definitions_automap'),
                        xtype: 'splitbutton',
                        iconCls: 'opendxp_icon_manyToOneRelation',
                        hidden: !me.panelKey.startsWith("importdefinitions_import_definition"),
                        menu: [
                            {
                                text: t('data_definitions_automap_exact'),
                                iconCls: "opendxp_icon_manyToOneRelation",
                                handler: me.automapExact.bind(me)
                            },
                            {
                                text: t('data_definitions_automap_fuzzy'),
                                iconCls: "opendxp_icon_manyToOneRelation",
                                handler: me.automapFuzzy.bind(me)
                            }
                        ]
                    },
                    {
                        text: t('data_definitions_import_definition'),
                        iconCls: 'opendxp_icon_import',
                        handler: me.upload.bind(me),
                        disabled: me.saveDisabled()
                    },
                    {
                        text: t('data_definitions_export_definition'),
                        iconCls: 'opendxp_icon_export',
                        handler: function () {
                            var id = me.data.id;
                            opendxp.helpers.download(me.url.export + '?id=' + id);
                        }
                    },
                    {
                        text: t('data_definitions_duplicate_definition'),
                        iconCls: 'opendxp_icon_copy',
                        disabled: me.saveDisabled(),
                        handler: function () {
                            var id = me.data.id;

                            Ext.MessageBox.prompt(t('add'), t('enter_the_name'), function (button, value) {
                                Ext.Ajax.request({
                                    url: me.url.duplicate,
                                    jsonData: {
                                        id: id,
                                        name: value
                                    },
                                    method: 'post',
                                    success: function (response) {
                                        var data = Ext.decode(response.responseText);

                                        me.parentPanel.grid.getStore().reload();
                                        me.parentPanel.refresh();

                                        if (!data || !data.success) {
                                            Ext.Msg.alert(t('add_target'), t('problem_creating_new_target'));
                                        } else {
                                            me.parentPanel.openItem(data.data);
                                        }
                                    }
                                });
                            }, null, null, '');
                        }
                    },
                    {
                        text: t('save'),
                        iconCls: 'opendxp_icon_apply',
                        handler: me.save.bind(me),
                        disabled: me.saveDisabled()
                    }],
                items: this.getItems()
            });

        return panel;
    },

    getItems: function () {
        return [
            this.getSettings(),
            this.getProviderSettings(),
            this.getMappingSettings()
        ];
    },

    getProviderSettings: function () {
        if (!this.providerSettings) {
            this.providerSettings = Ext.create({
                xtype: 'panel',
                layout: 'border',
                title: t('data_definitions_provider_settings'),
                iconCls: 'data_definitions_icon_provider',
                disabled: true
            });
        }

        if (this.data.provider) {
            this.reloadProviderSettings(this.data.provider);
        }

        return this.providerSettings;
    },

    upload: function (callback) {
        opendxp.helpers.uploadDialog(this.url.upload + '?id=' + this.data.id, 'Filedata', function () {
            this.panel.destroy();
            this.parentPanel.openItem(this.data);
        }.bind(this), function () {
            Ext.MessageBox.alert(t('error'), t('error'));
        });
    },

    getAutomapItems: function () {
        var grid = this.mappingSettings.down('grid');
        var mapping = grid.getStore().getRange();
        var fromColumnItems = [];
        grid.config.columns.items[1].editor.store.data.items.forEach(function (item) {
            fromColumnItems.push(item.data.identifier);
        });
        return {
            grid: grid,
            mapping: mapping,
            fromColumnItems: fromColumnItems,
        };
    },

    automapExact: function (callback) {
        var automap = this.getAutomapItems();
        automap.mapping.forEach(function (map) {
            if (automap.fromColumnItems.indexOf(map.data.toColumn) > -1) {
                map.data.fromColumn = map.data.toColumn;
            }
        });
        automap.grid.getView().refresh();
    },

    automapFuzzy: function (callback) {
        var automap = this.getAutomapItems();
        var options = {
            shouldSort: true,
            findAllMatches: true,
            includeScore: true,
            threshold: 0.7,
            location: 0,
            distance: 100,
            maxPatternLength: 32,
            minMatchCharLength: 1
        };
        var fuse = new Fuse(automap.fromColumnItems, options);
        automap.mapping.forEach(function (map) {
            result = fuse.search(map.data.toColumn)[0];
            if (result !== undefined) {
                if (!(['o_published', 'o_key', 'o_parentId', 'o_parent', 'o_type'].indexOf(map.data.toColumn) > -1 && result.score > 0.5)) {
                    map.data.fromColumn = automap.fromColumnItems[result.item];
                }
            }
        });
        automap.grid.getView().refresh();
    }
});
