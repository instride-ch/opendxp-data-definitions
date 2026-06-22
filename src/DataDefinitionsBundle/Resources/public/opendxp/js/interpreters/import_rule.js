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


opendxp.registerNS('opendxp.plugin.datadefinitions.interpreters.import_rule');

opendxp.plugin.datadefinitions.interpreters.import_rule = Class.create(opendxp.plugin.datadefinitions.interpreters.abstract, {
    rules: null,
    window: null,

    getLayout: function (fromColumn, toColumn, record, config) {
        var me = this;
        me.rules = config.rules;

        return Ext.Panel({
            autoScroll: true,
            forceLayout: true,
            items: [
                {
                    xtype: 'button',
                    text: t('edit'),
                    handler: function () {
                        me.panel = new opendxp.plugin.datadefinitions.import_rule.panel(me, me.rules, me.getActions(), me.getConditions());

                        me.window = new Ext.Window({
                            width: '80%',
                            height: '80%',
                            resizeable: true,
                            modal: false,
                            closeable: false,
                            title: t('data_definitions_import_rules'),
                            iconCls: 'data_definitions_icon_import_rules',
                            layout: 'fit',
                            items: me.panel.getLayout()
                        }).show();
                    }
                }
            ],
            border: false
        });
    },

    close: function (rules) {
        this.rules = rules;
        this.window.destroy();
    },

    getActions: function () {
        return opendxp.globalmanager.get('data_definitions_import_rule_actions');
    },

    getConditions: function () {
        return opendxp.globalmanager.get('data_definitions_import_rule_conditions');
    },

    getInterpreterData: function () {
        // get defined conditions
        return {
            rules: this.rules
        };
    }
});
