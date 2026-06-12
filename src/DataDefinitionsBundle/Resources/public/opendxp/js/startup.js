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

opendxp.registerNS('opendxp.plugin.data_definitions');

opendxp.plugin.data_definitions = Class.create({
    getClassName: function () {
        return 'opendxp.plugin.data_definitions';
    },

    initialize: function () {
        document.addEventListener(opendxp.events.opendxpReady, (e) => {
            this.opendxpReady();
        });
    },

    opendxpReady: function () {

        var user = opendxp.globalmanager.get('user');

        if (user.isAllowed('plugins')) {

            var importMenu = new Ext.Action({
                text: t('data_definitions_import_definitions'),
                iconCls: 'data_definitions_nav_icon_import_definition',
                handler: this.openImportDefinitions
            });

            layoutToolbar.settingsMenu.add(importMenu);

            var exportMenu = new Ext.Action({
                text: t('data_definitions_export_definitions'),
                iconCls: 'data_definitions_nav_icon_export_definition',
                handler: this.openExportDefinitions
            });

            layoutToolbar.settingsMenu.add(exportMenu);
        }
    },

    openImportDefinitions: function () {
        try {
            opendxp.globalmanager.get('data_definitions_import_definition_panel').activate();
        } catch (e) {
            var panel = new opendxp.plugin.datadefinitions.import.panel();
            opendxp.globalmanager.add('data_definitions_import_definition_panel', panel);
        }
    },

    openExportDefinitions: function () {
        try {
            opendxp.globalmanager.get('data_definitions_export_definition_panel').activate();
        } catch (e) {
            var panel = new opendxp.plugin.datadefinitions.export.panel();
            opendxp.globalmanager.add('data_definitions_export_definition_panel', panel);
        }
    }
});

new opendxp.plugin.data_definitions();

