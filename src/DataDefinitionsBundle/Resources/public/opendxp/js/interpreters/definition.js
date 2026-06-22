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


opendxp.registerNS('opendxp.plugin.datadefinitions.interpreters.definition');

opendxp.plugin.datadefinitions.interpreters.definition = Class.create(opendxp.plugin.datadefinitions.interpreters.abstract, {
    getLayout: function (fromColumn, toColumn, record, config) {
        return [
            {
                xtype: 'data_definitions.import_definition',
                name: 'definition',
                value: Ext.isDefined(config.definition) ? config.definition : null
            }
        ];
    }
});
