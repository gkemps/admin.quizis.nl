<?php

namespace Task;

use Mage\Task\AbstractTask;

class Composer extends AbstractTask
{
    public function getName()
    {
        return 'Installing composer dependencies';
    }

    public function run()
    {
        $command = 'php composer.phar update';
        $result = $this->runCommandRemote($command);

        return $result;
    }
}
