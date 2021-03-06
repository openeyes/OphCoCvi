<?php

class m160816_132509_add_disorder_mapping extends CDbMigration
{
	public function up()
	{
        $this->addColumn('ophcocvi_clinicinfo_disorder', 'disorder_id','int(10) unsigned');
        $this->addForeignKey('ophcocvi_clinicinfo_disorder_disorder_fk',
            'ophcocvi_clinicinfo_disorder','disorder_id',
            'disorder', 'id');
        $this->addColumn('ophcocvi_clinicinfo_disorder_version', 'disorder_id','int(10) unsigned');
	}

	public function down()
	{
        $this->dropForeignKey('ophcocvi_clinicinfo_disorder_disorder_fk',
            'ophcocvi_clinicinfo_disorder');
        $this->dropColumn('ophcocvi_clinicinfo_disorder', 'disorder_id');
		$this->dropColumn('ophcocvi_clinicinfo_disorder_version', 'disorder_id');
	}

}