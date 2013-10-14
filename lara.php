<?php

class Lara {
	
	private $sTable;
	private $sField;
	private $sMigrationFields = '';
	
	public function generateMigrationsAndModels($aMWBTables)
	{
		foreach ($aMWBTables as $this->sTable => $aTable)
		{
			foreach ($aTable['fields'] as $this->sField => $aField)
			{
				$this->generateMigrationFields($aField);
			}
			
			$this->saveMigration();
			//strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1_$2", $string)); snake case
		}
	}
	
	private function generateMigrationFields($aField)
	{
		$this->sMigrationFields .= "";
		if ($aField['autoincrement'])
		{
			$this->sMigrationFields .= "\$table->increments('".$this->sField."')";
		}
		else
		{
			$this->sMigrationFields .= "\$table->".$aField['data_type']."('".$this->sField."'".
				($aField['length'] ? ', '.$aField['length'] : '').")";
		}
		if ($aField['flags'] == 'UNSIGNED') $this->sMigrationFields .= "->unsigned()";
		$this->sMigrationFields .= ";\n\t\t\t";
	}
	
	private function saveMigration()
	{
		error_log("<?php

use Illuminate\Database\Migrations\Migration;

class Create".preg_replace('/(?:^|_)(.?)/e',"strtoupper('$1')",$this->sTable)."Table extends Migration
{
    public function up()
    {
        Schema::create('".$this->sTable."', function(\$table)
        {
            \$table->engine='InnoDB';
            ".$this->sMigrationFields."
        });
    }

    public function down()
    {
        Schema::drop('".$this->sTable."');
    }
	
}", 3, '../tmp/'.date('Y_m_d_H_i_s').'_create_'.$this->sTable.'_table.php');
	}
	
	private function saveModel()
	{
		error_log("<?php

// Model:'User' - Database Table: 'users'

Class User extends Eloquent
{

    protected $table='users';

    public function addressuser()
    {
        return $this->belongsToMany('AddressUser');
    }
    public function phones()
    {
        return $this->hasMany('Phones');
    }

}", 3, '../tmp/'.ucfirst($this->sTable).'.php');
	}
}