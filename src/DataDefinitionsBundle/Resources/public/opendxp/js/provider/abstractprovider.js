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

opendxp.registerNS('opendxp.plugin.datadefinitions.provider.abstractprovider');

opendxp.plugin.datadefinitions.provider.abstractprovider = Class.create({
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
                items: this.getItems(),
                buttons: [{
                    text: t('test'),
                    iconCls: 'opendxp_icon_apply',
                    handler: this.test.bind(this)
                }],
            });
        }

        return this.form;
    },

    getItems: function () {
        return [];
    },

    test: function () {
        this.parentItemPanel.save(function () {
            Ext.Ajax.request({
                url: this.parentItemPanel.url.test,
                method: 'get',
                params: {
                    id: this.parentItemPanel.data.id
                },
                success: function (response) {
                    try {
                        var res = Ext.decode(response.responseText);

                        if (res.success) {
                            opendxp.helpers.showNotification(t('success'), t('success'), 'success');

                            this.parentItemPanel.providerSettingsSuccess(this);
                        } else {
                            opendxp.helpers.showNotification(t('error'), res.message, 'error');
                        }
                    } catch (e) {
                        opendxp.helpers.showNotification(t('error'), t('error'), 'error');
                    }
                }.bind(this)
            });
        }.bind(this));
    }
});
