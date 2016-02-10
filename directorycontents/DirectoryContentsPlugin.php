<?php
namespace Craft;


class DirectoryContentsPlugin extends BasePlugin
{
	function getName()
	{
		return Craft::t('Directory Contents');
	}

	function getVersion()
	{
		return '0.0.1';
	}

	function getDeveloper()
	{
		return 'nfourtythree';
	}

	function getDeveloperUrl()
	{
		return 'http://n43.co.uk';
	}

	public function hasCpSection()
	{
		return false;
	}
}
