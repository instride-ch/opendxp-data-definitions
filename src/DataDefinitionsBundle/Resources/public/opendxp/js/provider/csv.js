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


opendxp.registerNS('opendxp.plugin.datadefinitions.provider.csv');

opendxp.plugin.datadefinitions.provider.csv = Class.create(opendxp.plugin.datadefinitions.provider.abstractprovider, {
    getItems: function () {
        return [{
            xtype: 'textfield',
            name: 'delimiter',
            fieldLabel: t('data_definitions_csv_delimiter'),
            anchor: '100%',
            value: this.data['delimiter'] ? this.data.delimiter : ','
        }, {
            xtype: 'textfield',
            name: 'enclosure',
            fieldLabel: t('data_definitions_csv_enclosure'),
            anchor: '100%',
            value: this.data['enclosure'] ? this.data.enclosure : '"'
        }, {
            xtype: 'textarea',
            fieldLabel: t('data_definitions_csv_example'),
            name: 'csvExample',
            grow: true,
            anchor: '100%',
            minHeight: 300,
            value: this.data['csvExample'] ? this.data.csvExample : ''
        }, {
            xtype: 'container',
            html: '<strong>' + t('data_definitions_csv_headers_description') + '</strong>',
            padding: '0 0 0 200px'
        }, {
            xtype: 'textarea',
            fieldLabel: t('data_definitions_csv_headers'),
            name: 'csvHeaders',
            grow: true,
            anchor: '100%',
            minHeight: 300,
            value: this.data['csvHeaders'] ? this.data.csvHeaders : ''
        }];
    }
});
