<?php

namespace OEngine\Platform\Commands;

use Illuminate\Console\Command;
use OEngine\Platform\Traits\WithGeneratorStub;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class PlatformMakeCommand extends Command
{
    use WithGeneratorStub;
    protected $name = 'platform:make';


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['type', 't', InputOption::VALUE_OPTIONAL, 'Make by type', 'module'],
            ['list', 'l', InputOption::VALUE_OPTIONAL, 'Show type list', '']
        ];
    }
    protected function getArguments()
    {
        return [
            ['name', InputArgument::IS_ARRAY, 'The names of modules will be created.'],
        ];
    }
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->option('type');
        $this->components->info('Platform make by ' . $type);
        $this->bootWithGeneratorStub();
        $names = $this->argument('name');
        $success = true;

        foreach ($names as $name) {
            $code = $this->generate($name);
            $this->components->info('module ' . $name . ' : ' .  $code);
            if ($code === E_ERROR) {
                $success = false;
            }
        }
        $this->info('done');

        return $success ? 0 : E_ERROR;
    }
}
