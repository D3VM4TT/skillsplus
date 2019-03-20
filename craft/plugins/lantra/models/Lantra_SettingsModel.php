<?php
namespace Craft;

class Lantra_SettingsModel extends BaseModel
{
	protected function defineAttributes()
	{
		return array(
			'lantraDisableLicences'     => AttributeType::Bool
		);
	}
}