<?php
namespace Craft;

class DirectoryContentsVariable
{
	public function read($path = '')
	{
		return Craft()->directoryContents->read($path);
	}
}
