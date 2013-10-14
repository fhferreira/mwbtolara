<?php

class MWB {
	
	private $sMWBFile;
	private $oMWBXml;
	private $aMWBTables = array();
	
	public function __construct($sMWBFilename)
	{
		$this->sMWBFile = $sMWBFilename;
		$this->oMWBXml = new SimpleXMLElement($this->getMWBXml());
	}
	
	private function getMWBXml()
	{
		$zip = new ZipArchive;
		if ($zip->open($this->sMWBFile) === TRUE)
		{
			$zip->extractTo(dirname($this->sMWBFile));
			$zip->close();
			
			return file_get_contents(dirname($this->sMWBFile).'/document.mwb.xml');
		}
		else
		{
			die('Invalid mwb file!');
		}
	}
	
	public function getMWBTablesArray()
	{
		$this->setMWBTablesArray();
		return $this->aMWBTables;
	}
	
	private function setMWBTablesArray()
	{
		// Tables
		foreach ($this->getMWBTables()->value as $oMWBXmlTable)
		{
			// Fields
			foreach ($oMWBXmlTable->value as $oMWBXmlTableFields)
			{
				// Field properties
				foreach ($oMWBXmlTableFields->value as $oMWBXmlTableField)
				{
					// Is it a field?
					if ($oMWBXmlTableField->value[13])
					{
						$this->aMWBTables
							[(string) $oMWBXmlTable->value[41]]['fields']
							[(string) $oMWBXmlTableField->value[13]] = 
								$this->getMWBTableFieldArray($oMWBXmlTableField);
					}
				}
			}
		}
	}
	
	private function getMWBTableFieldArray($oMWBTableField)
	{
		// Table name / Field name
		return array(
			'data_type' => pathinfo((string) $oMWBTableField->link[0], PATHINFO_EXTENSION),
			'autoincrement' => (string) $oMWBTableField->value[0],
			'default_value' => (string) $oMWBTableField->value[5],
			'default_value_is_null' => (string) $oMWBTableField->value[6],
			'is_not_null' => (string) $oMWBTableField->value[8],
			'length' => (string) $oMWBTableField->value[9],
			'flags' => (string) $oMWBTableField->value[7]->value,
		);
	}
	
	private function getMWBTables()
	{
		return $this->oMWBXml->value->value[2]->value->value->value[1]->value->value[5];
	}
	
	public function __destruct()
	{
		//if (file_exists($this->sMWBFile)) unlink($this->sMWBFile);
		if (file_exists(dirname($this->sMWBFile).'/@db/data.db')) unlink(dirname($this->sMWBFile).'/@db/data.db');
		if (file_exists(dirname($this->sMWBFile).'/@db')) rmdir(dirname($this->sMWBFile).'/@db');
		if (file_exists(dirname($this->sMWBFile).'/document.mwb.xml')) unlink(dirname($this->sMWBFile).'/document.mwb.xml');
		if (file_exists(dirname($this->sMWBFile).'/lock')) unlink(dirname($this->sMWBFile).'/lock');
	}
}