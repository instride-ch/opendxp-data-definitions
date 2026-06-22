/*
 * OpenDXP Data Definitions.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh) in combination with instride AG (https://instride.ch)
 * @copyright  Modification Copyright (c) instride AG (https://instride.ch)
 * @license    https://github.com/instride-ch/opendxp-data-definitions/blob/main/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
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
