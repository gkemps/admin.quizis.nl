<?php
namespace Task;

use Exception;
use Mage\Task\AbstractTask;
use Mage\Task\ErrorWithMessageException;
use Mage\Task\SkipException;

class Temp extends AbstractTask
{
    /**
     * Returns the Title of the Task
     * @return string
     */
    public function getName()
    {
        return 'Setting temp dir for Windows environment';
    }

    /**
     * Runs the task
     *
     * @return boolean
     * @throws Exception
     * @throws ErrorWithMessageException
     * @throws SkipException
     */
    public function run()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            echo 'This is a server using Windows!';
            putenv("TMP=");
        } else {
            echo 'This is a server not using Windows! Do nothing....';
        }

        return true;
    }
}
