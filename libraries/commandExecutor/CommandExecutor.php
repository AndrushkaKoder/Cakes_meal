<?php

namespace libraries\commandExecutor;

use core\base\controller\BaseMethods;

class CommandExecutor
{

    use BaseMethods;

    private $scripts = [];

    private $lockedProcessDir = __DIR__ . '/lockedFlags';

    private $shellDirectory = __DIR__ . '/shell_executable';

    private static $runningProcesses = [];

    public function __construct($scripts = [])
    {

        $this->scripts = (array)$scripts;

        if(!$this->scripts){

            foreach (scandir($this->shellDirectory) as $file){

                if($file !== '.' && $file !== '..' && !is_dir($this->shellDirectory . '/' . $file) && is_readable($this->shellDirectory . '/' . $file)){

                    $this->scripts[] = $this->shellDirectory . '/' . $file;

                }

            }

        }

        if(!is_dir($this->lockedProcessDir)){

            mkdir($this->lockedProcessDir, 0777);

        }

        if(!preg_match('/\/$/', $this->lockedProcessDir)){

            $this->lockedProcessDir .= '/';

        }

    }

    public function execute($scriptName = ''){

        $scripts = $this->scripts;

        if($scriptName){

            $scriptName = str_replace('/', '\/', preg_quote($scriptName));

            $scripts = preg_grep('/' . $scriptName . '/', $scripts);

        }

        if($scripts){

            foreach ($scripts as $script){

                $scriptInfo = pathinfo($script);

                if(!is_readable($script) || !$scriptInfo){

                    return false;

                }

                if(!($fp = fopen($this->lockedProcessDir . $scriptInfo['filename'], 'w'))){

                    return false;

                }

                if(!$this->isLocked($fp) && !in_array($script, self::$runningProcesses)){

                    self::$runningProcesses[] = $script;

                    $this->launchProcess($script, $this->lockedProcessDir . $scriptInfo['filename'], $scriptInfo['filename']);

                    $this->writeLog('Start shell_script ' . $script, 'shell_executor.txt', 'Success');

                }

            }

            return true;

        }

        return false;

    }

    public function launchProcess($process, $lockFile, $scriptName){

        //$command = 'php ' . $process;

        $command = 'php ' . __DIR__ . '/exec.php';

        $arguments = '';

        $processArgs = preg_split('/_{2,}/', $scriptName, 0, PREG_SPLIT_NO_EMPTY);

        if(count($processArgs) > 1){

            unset($processArgs[0]);

            foreach ($processArgs as $item){

                $arguments && $arguments .= ' ';

                $arguments .= preg_replace('/_+/', ' ', $item);

            }

        }

        if(PHP_OS=='WINNT' || PHP_OS=='WIN32' || PHP_OS=='Windows'){
            // Windows
            $command = 'start "" '. $command . " $lockFile $process $arguments";

        } else {
            // Linux/UNIX
            $command = $command . " $lockFile $process $arguments" .' /dev/null &';

        }

        return pclose(popen($command, 'r'));

    }

    public function isLocked($process){

        if(!$process){

            throw new \Exception('Отсутствуют данные для проверки процесса');

        }

        if(is_string($process)){

            $scriptInfo = pathinfo($process);

            $process = fopen($this->lockedProcessDir . $scriptInfo['filename'], 'w');

        }

        if(!$process){

            throw new \Exception('Ошибка открытия файла контроля процесса');

        }

        $lock = flock($process, LOCK_EX | LOCK_NB);

        return !$lock;

    }

}