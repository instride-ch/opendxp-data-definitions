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


opendxp.registerNS('opendxp.plugin.datadefinitions.export_provider.abstractprovider');

opendxp.plugin.datadefinitions.export_provider.abstractprovider = Class.create({
    data: {},
    parentItemPanel: null,

    initialize: function (data, parentItemPanel) {
        this.data = data;
        this.parentItemPanel = parentItemPanel;
    },

    getForm: function () {
        if (!this.form) {
            this.form = new Ext.form.Panel({
                bodyStyle: 'padding:10px;',
                region: 'center',
                autoScroll: true,
                defaults: {
                    labelWidth: 200
                },
                border: false,
                items: this.getItems()
            });
        }

        return this.form;
    },

    getItems: function () {
        return [];
    }
});
