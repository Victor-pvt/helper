<?php

namespace HelperManager\Command;

use HelperManager\Helper\DateTimeHelper;
use HelperManager\Helper\LogHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class _ExtendedConsoleCommand extends ContainerAwareCommand
{
    const COMMAND_SUCCESS_CODE = 0; //https://en.wikipedia.org/wiki/Exit_status#Shell_and_scripts
    const COMMAND_FAILURE_CODE = 1; //https://en.wikipedia.org/wiki/Exit_status#Shell_and_scripts
	/** @var InputInterface $input */
	protected $input;
	/** @var OutputInterface $output */
	protected $output;
	/** @var SymfonyStyle $io */
	protected $io;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->input = $input;
		$this->output = $output;
		$this->io = new SymfonyStyle($input, $output);
		$id = getmypid();
        $isLogger = $this->getContainer()->getParameter('helper.logger');
        $ymTime = $this->startExecute($id,$isLogger);

		$this->runCommand();

        $this->finishExecute($ymTime,$id,$isLogger);

        return self::COMMAND_SUCCESS_CODE;
	}

	protected function runCommand()
	{

	}

    /**
     * $ymTime = $this->startExecute($output);
     * @return mixed
     */
    protected function startExecute($id,$isLogger)
    {
        $ymTime = microtime(true);
        $this->io->writeln('start process ' . DateTimeHelper::getDateString());
        $options = $this->input->getOptions();
        $args = '';
        if($options){
            $_args = [];
            foreach ($options as $key=>$option){
                if($option){
                    $_args[] =$key .':'. $option;
                }
            }
            $args = implode(',',$_args);
        }
        $message = " start process: {$id}, " . static::$defaultName . ', '.$args;
        $isLogger ? LogHelper::getInstance('console-command','m')->setStr($message) : null;

        return $ymTime;
    }

    /**
     * $this->finishExecute($output, $ymTime);
     * @param $ymTime
     * @param $id
     */
    protected function finishExecute($ymTime,$id,$isLogger)
    {
        $ymTime = (string)DateTimeHelper::s2t(microtime(true) - $ymTime);
        $this->io->writeln("Затрачено время " . $ymTime);
        $this->io->writeln('finish process ' . DateTimeHelper::getDateString());
        $message = "finish process: {$id}, " . static::$defaultName.', время работы ' . $ymTime;
        $isLogger ? LogHelper::getInstance('console-command','m')->setStr($message) : null;
    }
}
