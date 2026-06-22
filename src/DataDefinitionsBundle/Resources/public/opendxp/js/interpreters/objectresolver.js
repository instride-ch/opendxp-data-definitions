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


opendxp.registerNS('opendxp.plugin.datadefinitions.interpreters.object_resolver');

opendxp.plugin.datadefinitions.interpreters.object_resolver = Class.create(opendxp.plugin.datadefinitions.interpreters.abstract, {
    getLayout: function (fromColumn, toColumn, record, config) {
        var classesStore = new Ext.data.JsonStore({
            autoDestroy: true,
            proxy: {
                type: 'ajax',
                url: '/admin/class/get-tree'
            },
            fields: ['text']
        });
        classesStore.load();

        return [{
            xtype: 'combo',
            fieldLabel: t('class'),
            name: 'class',
            displayField: 'text',
            valueField: 'text',
            store: classesStore,
            width: 500,
            value: config.class ? config.class : null
        },
            {
                xtype: 'textfield',
                fieldLabel: t('data_definitions_interpreter_object_resolver_field'),
                name: 'field',
                width: 500,
                value: config.field ? config.field : null
            },
            {
                xtype: 'checkbox',
                fieldLabel: t('data_definitions_interpreter_object_resolver_match_unpublished'),
                name: 'match_unpublished',
                value: Ext.isDefined(config.match_unpublished) ? config.match_unpublished : true,
                listeners: {
                    change: function (el, enabled) {
                        var matchUnpublishedDisabled = (enabled === false);
                        if (matchUnpublishedDisabled) {
                            createPublishedCheckbox.setValue(true);
                        }
                        createPublishedCheckbox.setDisabled(matchUnpublishedDisabled);
                    }
                }
            }
        ];
    }
});
