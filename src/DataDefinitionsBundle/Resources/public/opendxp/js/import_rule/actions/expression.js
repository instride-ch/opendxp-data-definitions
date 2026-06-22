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


opendxp.registerNS('opendxp.plugin.datadefinitions.import_rule.actions.expression');
opendxp.plugin.datadefinitions.import_rule.actions.expression = Class.create({

    type: 'expression',

    getForm: function () {
        this.expression = Ext.create({
            xtype: 'textfield',
            fieldLabel: t('data_definitions_interpreter_expression'),
            name: 'expression',
            width: 500,
            value: this.data ? this.data.expression : null
        });

        this.form = new Ext.form.Panel({
            items: [
                this.expression
            ]
        });

        return this.form;
    },

    getValues: function () {
        return {
            expression: this.expression.getValue()
        };
    }
});
