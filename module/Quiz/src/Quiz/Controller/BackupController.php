<?php
namespace Quiz\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use pCloud;

class BackupController extends AbstractActionController
{
    /** @var array */
    protected $backupConfig;

    /**
     * BackupController constructor.
     * @param array $backupConfig
     */
    public function __construct(array $backupConfig)
    {
        $this->backupConfig = $backupConfig;
    }

    public function backupAction()
    {
        foreach ($this->backupConfig as $path => $folderId) {
            $folder = new pCloud\Folder();
            /** @var \stdClass[] $pcloudFiles */
            $pcloudFiles = $folder->getContent($folderId);

            $index = [];
            foreach ($pcloudFiles as $pcloudFile) {
                $index[$pcloudFile->name] = $pcloudFile->id;
            }

            $localFiles = scandir($path);

            foreach ($localFiles as $id => $fileName) {
                if (empty($fileName) || $fileName == "." || $fileName == ".." || substr($fileName, 0, 1) == ".") {
                    continue;
                }
                if (isset($index[$fileName])) {
                    continue;
                }

                $pcloudFile = new pCloud\File();
                try {
                    print "Uploading ".$path."/".$fileName." to folderId: ".$folderId."\r\n";
                    $fileMetadata = $pcloudFile->upload($path."/".$fileName, $folderId);
                    if (empty($fileMetadata->metadata->fileid)) {
                        throw new \Exception("missing metadata");
                    }
                } catch (\Exception $e) {
                    print "Error uploading to pCloud: " . $e->getMessage() . "\r\n";
                }
            }
        }
    }
}