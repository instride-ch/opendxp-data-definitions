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

opendxp.registerNS('opendxp.plugin.datadefinitions.setters.opendxp_ecommerce_store_price');

opendxp.plugin.datadefinitions.setters.opendxp_ecommerce_store_price = Class.create(opendxp.plugin.datadefinitions.setters.abstract, {
    getLayout: function (fromColumn, toColumn, record, config) {
        return [{
            xtype: 'opendxp_ecommerce.store',
            name: 'stores',
            multiSelect: true,
            typeAhead: false,
            value: config ? config.stores : []
        }];
    }
});
