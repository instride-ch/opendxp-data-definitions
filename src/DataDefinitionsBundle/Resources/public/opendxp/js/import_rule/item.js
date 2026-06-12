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

opendxp.registerNS('opendxp.plugin.datadefinitions.import_rule.item');

opendxp.plugin.datadefinitions.import_rule.item = Class.create({

    iconCls: 'data_definitions_icon_import_rules',

    initialize: function (parentPanel, data, panelKey, type, record) {
        this.parentPanel = parentPanel;
        this.data = data;
        this.panelKey = panelKey;
        this.type = type;
        this.record = record;
    },

    getPanel: function () {
        var items = this.getItems();

        this.panel = new Ext.TabPanel({
            activeTab: 0,
            title: this.data.name,
            deferredRender: false,
            forceLayout: true,
            iconCls: this.iconCls,
            items: items
        });

        return this.panel;
    },

    getSettings: function () {
        var data = this.data;

        this.settingsForm = Ext.create('Ext.form.Panel', {
            iconCls: 'opendxp_icon_settings',
            title: t('settings'),
            bodyStyle: 'padding:10px;',
            autoScroll: true,
            border: false,
            items: [
                {
                    xtype: 'textfield',
                    name: 'name',
                    fieldLabel: t('name'),
                    width: 250,
                    value: data.name
                },
                {
                    xtype: 'checkbox',
                    name: 'active',
                    fieldLabel: t('active'),
                    checked: data.active
                }
            ]
        });

        return this.settingsForm;
    },

    save: function (callback) {

    },

    getSaveData: function () {
        var saveData = this.settingsForm.getForm().getValues();
        saveData.id = this.record.id;
        return saveData;
    },

    getActionContainerClass: function () {
        return opendxp.plugin.datadefinitions.import_rule.action;
    },

    getConditionContainerClass: function () {
        return opendxp.plugin.datadefinitions.import_rule.condition;
    }
});
