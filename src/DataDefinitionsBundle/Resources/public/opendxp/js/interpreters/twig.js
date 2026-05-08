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

opendxp.registerNS('opendxp.plugin.datadefinitions.interpreters.twig');

opendxp.plugin.datadefinitions.interpreters.twig = Class.create(opendxp.plugin.datadefinitions.interpreters.abstract, {
    getLayout: function (fromColumn, toColumn, record, config) {
        return [{
            xtype: 'textarea',
            fieldLabel: t('data_definitions_interpreter_template'),
            name: 'template',
            width: '100%',
            value: config.template ? config.template : null
        }];
    }
});
