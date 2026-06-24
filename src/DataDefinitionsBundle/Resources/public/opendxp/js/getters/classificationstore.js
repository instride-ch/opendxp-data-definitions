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

opendxp.registerNS('opendxp.plugin.datadefinitions.getters.classificationstore');

opendxp.plugin.datadefinitions.getters.classificationstore = Class.create(opendxp.plugin.datadefinitions.setters.abstract, {
    getLayout: function (fromColumn, toColumn, record, config) {
        this.fromColumn = fromColumn;

        return [];
    },

    getGetterData: function () {
        return {
            'keyConfig': this.fromColumn.config.keyId,
            'groupConfig': this.fromColumn.config.groupId,
            'field': this.fromColumn.config.field
        };
    }
});
