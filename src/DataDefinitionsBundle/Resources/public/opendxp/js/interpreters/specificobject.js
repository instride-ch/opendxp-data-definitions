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


opendxp.registerNS('opendxp.plugin.datadefinitions.interpreters.specific_object');

opendxp.plugin.datadefinitions.interpreters.specific_object = Class.create(opendxp.plugin.datadefinitions.interpreters.abstract, {

    getLayout: function (fromColumn, toColumn, record, config) {
        this.defaultObjectField = new Ext.form.TextField({
            name: "objectId",
            value: config.path,
            width: 500
        });

        return [{
            xtype: 'fieldcontainer',
            layout: 'hbox',
            fieldLabel: t("Predefined object"),
            items: [
                {
                    xtype: "button",
                    iconCls: "opendxp_icon_search",
                    handler: this.searchForObject.bind(this, 1)
                }, this.defaultObjectField
            ]
        }
        ];
    },

    searchForObject: function (objectIndex) {
        opendxp.helpers.itemselector(false, this.addDataFromSelector.bind(this, objectIndex), {
            type: ["object"]
        });
    },

    addDataFromSelector: function (objectIndex, item) {
        if (item) {
            this.defaultObjectField.setValue(item.id);
        }
    }
});
