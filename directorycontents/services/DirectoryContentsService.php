<?php
namespace Craft;

class DirectoryContentsService extends BaseApplicationComponent
{
	private $ignoreFiles = array('.DS_Store');
	private $depth;
	private $path;

	public function read($options = false)
	{
		$this->_parseOptions($options);
		$pathExists = file_exists($this->path);
		if (!$pathExists or !$this->path) {
			throw new Exception(Craft::t('Unable to read path: “{path}”.', array('path' => $this->path)));
		} else {
			$directory = new \RecursiveDirectoryIterator($this->path, \RecursiveDirectoryIterator::SKIP_DOTS);

			$files = $this->_loopFiles($directory);

			return $files;
		}
	}

	private function _parseOptions($options)
	{
		if ($options) {
			if (is_string($options)) {
				$this->path = $options;
			} else {
				if (array_key_exists('path', $options)) {
					$this->path = $options['path'];
				} else {
					throw new Exception(Craft::t('A path must be specified'));
				}

				if (array_key_exists('root', $options)) {
					if ($options['root'] && strtolower($options['root']) !== 'webroot') {
						$rootName = ucfirst(strtolower($options['root']));
						$functionName = 'get'.$rootName.'Path';
						$rootPath = craft()->path->$functionName();
						$this->path = $rootPath.$this->path;
					}
				}

				if (array_key_exists('depth', $options)) {
					$this->depth = (int) $options['depth'];
				}
				else
				{
					$this->depth = -1;
				}

				if (array_key_exists('ignoreFiles', $options)) {
					$this->ignoreFiles = $options['ignoreFiles'];
				}
			}
		} else {
			throw new Exception(Craft::t('Options must be specified'));
		}
	}

	private function _loopFiles($directory)
	{
		$files = array();
		$iterator = new \RecursiveIteratorIterator($directory);

		$iterator->setMaxDepth($this->depth);

		foreach ($iterator as $info) {

			if (!in_array($info->getFilename(), $this->ignoreFiles)) {
				$fileNameLength = strlen($info->getFileName());
				$extLength = strlen($info->getExtension());
				$folders = explode("/", $info->getPathname());
				$parentFolder = false;
				if (count($folders) > 1) {
					$parentFolder = $folders[count($folders) - 2];
				}

				$name = substr($info->getFilename(), 0, $fileNameLength - $extLength - 1);

				$file = new DirectoryContents_FileModel();
				$file->name = $name;
				$file->niceName = $this->_generateNiceName($name);
				$file->fileName = $info->getFileName();
				$file->path = $info->getPathname();
				$file->parentFolder = $parentFolder;
				$file->niceParentFolder = ($parentFolder) ? $this->_generateNiceName($parentFolder) : $parentFolder;
				$file->extension = $info->getExtension();
				$file->size = $info->getSize();
				$file->created = $info->getCtime();
				$file->modified = $info->getMtime();

				$files[] = $file;
			}
		}

		return $files;
	}

	private function _generateNiceName($name = '')
	{
		$_replaceChars = array('-', '_');

		$name = str_replace($_replaceChars, " ", $name);
		$name = ucwords($name);

		return $name;
	}
}
