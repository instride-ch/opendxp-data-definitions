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

opendxp.registerNS('opendxp.plugin.datadefinitions.provider.raw');

opendxp.plugin.datadefinitions.provider.raw = Class.create(opendxp.plugin.datadefinitions.provider.abstractprovider, {
    getItems: function () {
        return [{
            xtype: 'textarea',
            fieldLabel: t('data_definitions_data_object_headers'),
            name: 'headers',
            grow: true,
            anchor: '100%',
            minHeight: 300,
            value: this.data['headers'] ? this.data.headers : ''
        }];
    }
});
