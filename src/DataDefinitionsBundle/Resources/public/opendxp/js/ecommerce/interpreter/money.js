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

opendxp.registerNS('opendxp.plugin.datadefinitions.interpreters.opendxp_ecommerce_money');

opendxp.plugin.datadefinitions.interpreters.opendxp_ecommerce_money = Class.create(opendxp.plugin.datadefinitions.interpreters.abstract, {
    getLayout: function (fromColumn, toColumn, record, config) {
        return [
            {
                xtype: 'checkbox',
                fieldLabel: t('data_definitions_interpreter_opendxp_ecommerce_is_float'),
                name: 'isFloat',
                value: config.isFloat ? config.isFloat : false
            },
            {
                xtype: 'opendxp_ecommerce.currency',
                name: 'currency',
                multiSelect: false,
                typeAhead: false,
                value: config.currency
            }
        ];
    }
});
