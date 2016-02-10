<?php
namespace Craft;

class DirectoryContentsService extends BaseApplicationComponent
{
	private $ignoreFiles = array('.DS_Store');
	private $path;

	public function read($options = false)
	{
		$this->_parseOptions($options);
		$pathExists = file_exists($this->path);
		if (!$pathExists or !$this->path) {
			throw new Exception(Craft::t('Unable to read path: “{path}”.', array('path' => $this->path)));
		} else {
			$directory = new \RecursiveDirectoryIterator($this->path, \RecursiveDirectoryIterator::SKIP_DOTS);

			$files = $this->loopFiles($directory);

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

				if (array_key_exists('ignoreFiles', $options)) {
					$this->ignoreFiles = $options['ignoreFiles'];
				}
			}
		} else {
			throw new Exception(Craft::t('Options must be specified'));
		}
	}

	private function loopFiles($directory)
	{
		$files = array();
		$iterator = new \RecursiveIteratorIterator($directory);
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
				$files[] = array(
					"name" => $name,
					"niceName" => ucwords(str_replace("-", " ", $name)),
					"fileName" => $info->getFileName(),
					"path" => $info->getPathname(),
					"parentFolder" => $parentFolder,
					"niceParentFolder" => ($parentFolder) ? ucwords(str_replace("-", " ", $parentFolder)) : $parentFolder,
					"extension" => $info->getExtension(),
					"size" => $info->getSize(),
					"created" => $info->getCtime(),
					"modified" => $info->getMtime(),
				);
			}
		}

		return $files;
	}
}
