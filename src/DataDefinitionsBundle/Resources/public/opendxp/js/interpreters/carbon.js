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

opendxp.registerNS('opendxp.plugin.datadefinitions.interpreters.carbon');

opendxp.plugin.datadefinitions.interpreters.carbon = Class.create(opendxp.plugin.datadefinitions.interpreters.abstract, {
    getLayout: function (fromColumn, toColumn, record, config) {
        return [{
            xtype: 'textfield',
            fieldLabel: t('date_format'),
            name: 'date_format',
            width: 500,
            value: config.date_format ? config.date_format : null
        }];
    }
});
