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
