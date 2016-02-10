<?php
namespace Craft;

class DirectoryContents_FileModel extends BaseComponentModel
{
	public function __toString()
	{
		return $this->path;
	}

	protected function defineAttributes()
	{
		return array(
			'name'             => AttributeType::String,
			'niceName'         => AttributeType::String,
			'fileName'         => AttributeType::String,
			'path'             => AttributeType::String,
			'parentFolder'     => AttributeType::String,
			'niceParentFolder' => AttributeType::String,
			'extension'        => AttributeType::String,
			'size'             => AttributeType::Number,
			'created'          => AttributeType::Number,
			'modified'         => AttributeType::Number,
		);
	}
}
