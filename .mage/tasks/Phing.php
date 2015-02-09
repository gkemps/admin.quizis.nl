<?php

namespace Task;

use Mage\Task\AbstractTask;

class Phing extends AbstractTask
{
    public function getName()
    {
        return 'Running Phing deployment targets';
    }

    public function run()
    {
//        $command = 'vendor/bin/phing -f build.xml app:file-permissions';
//        $result = $this->runCommandRemote($command);
//        if (! $result) {
//            return false;
//        }
//
//        $command = 'vendor/bin/phing -f build.xml cache:clear';
//        $result = $this->runCommandRemote($command);
//        if (! $result) {
//            return false;
//        }

        $command = 'vendor/bin/phing -f build/build.xml db:migrate';
        $result = $this->runCommandRemote($command);
        if (! $result) {
            return false;
        }

//        $command = 'vendor/bin/doctrine-module orm:generate-proxies';
//        $result = $this->runCommandRemote($command);
//        if (! $result) {
//            return false;
//        }

        return true;
    }
}
