<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

class m160728_085205_add_new_columns_ophcocvi_clericinfo_employment_status extends CDbMigration
{
    public function up()
    {
        $this->addColumn('ophcocvi_clericinfo_employment_status', 'child_default', 'tinyint(1) unsigned NOT NULL DEFAULT 1 AFTER `name` ');
        $this->addColumn('ophcocvi_clericinfo_employment_status', 'social_history_occupation_id', 'int(12) NULL AFTER `child_default`  ');
        $this->addColumn('ophcocvi_clericinfo_employment_status', 'active', 'tinyint(1) unsigned not null default 1 AFTER `social_history_occupation_id` ');
        $this->addColumn('ophcocvi_clericinfo_employment_status_version', 'child_default', 'tinyint(1) unsigned NOT NULL DEFAULT 1 AFTER `name`');
        $this->addColumn('ophcocvi_clericinfo_employment_status_version', 'social_history_occupation_id', 'int(12) NULL AFTER `child_default`');
        $this->addColumn('ophcocvi_clericinfo_employment_status_version', 'active', 'tinyint(1) unsigned not null default 1 AFTER `social_history_occupation_id`');

    }

    public function down()
    {
        $this->dropColumn('ophcocvi_clericinfo_employment_status', 'active');
        $this->dropColumn('ophcocvi_clericinfo_employment_status', 'social_history_occupation_id');
        $this->dropColumn('ophcocvi_clericinfo_employment_status', 'child_default');
        $this->dropColumn('ophcocvi_clericinfo_employment_status_version', 'active');
        $this->dropColumn('ophcocvi_clericinfo_employment_status_version', 'social_history_occupation_id');
        $this->dropColumn('ophcocvi_clericinfo_employment_status_version', 'child_default');
    }
}