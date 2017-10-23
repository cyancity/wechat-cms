<?php

namespace App\Commands;


use Illuminate\Console\Command;

/**
* 
*/
class BlogInstall extends Command
{
	protected $signature = 'blog:install';

	protected $description = 'Install the blog';

	public function __construct()
	{
		parent::__construct();
	}
	
	public function handle()
	{
		$this->exceShellWithPrettyPrint('php artisan key:generate');
		$this->exceShellWithPrettyPrint('php artisan migrate --seed');
		$this->exceShellWithPrettyPrint('php artisan passport:install');
		$this->exceShellWithPrettyPrint('php artisan storage:link');
	}

	public function exceShellWithPrettyPrint($command)
	{
		$this->info('---');
		$this->info('$command');
		$this->info(shell_exec($command));
		$this->info('---');
	}
}