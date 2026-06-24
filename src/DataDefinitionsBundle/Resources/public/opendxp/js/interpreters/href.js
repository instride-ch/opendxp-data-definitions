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

opendxp.registerNS('opendxp.plugin.datadefinitions.interpreters.href');

opendxp.plugin.datadefinitions.interpreters.href = Class.create(opendxp.plugin.datadefinitions.interpreters.abstract, {
    getLayout: function (fromColumn, toColumn, record, config) {
        var typeStore = new Ext.data.ArrayStore({
            fields: ['key', 'value'],
            data: [
                ['asset', t('asset')],
                ['object', t('object')],
                ['document', t('document')]
            ]
        });
        var classesStore = new Ext.data.JsonStore({
            autoDestroy: true,
            proxy: {
                type: 'ajax',
                url: '/admin/class/get-tree'
            },
            fields: ['text']
        });
        classesStore.load();

        return [
            {
                xtype: 'combo',
                fieldLabel: t('element'),
                name: 'type',
                displayField: 'value',
                valueField: 'key',
                store: typeStore,
                width: 500,
                value: config.type ? config.type : 'object'
            },
            {
                xtype: 'combo',
                fieldLabel: t('class'),
                name: 'class',
                displayField: 'text',
                valueField: 'text',
                store: classesStore,
                width: 500,
                value: config.class ? config.class : null
            }
        ];
    }
});
