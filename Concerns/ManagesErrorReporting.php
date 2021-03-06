<?php
namespace Vanilla\Concerns;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Whoops\Handler\CallbackHandler;

trait ManagesErrorReporting {

    public $whoops;

    public function registerErrorHandling()
    {
        $whoops = new Run;
        if($this->debugMode()) {
            $whoops->pushHandler(new PrettyPageHandler);
        } else {
            $whoops->pushHandler(new CallbackHandler(function ($exception) {
                $template = $this->config('custom_error_template') ?: 'path: '.__DIR__ . '/../error.blade.php';
                die(view($template, ['exception' => $exception])->render());
            }));
        }
        $whoops->pushHandler(function ($e) {
            $this->log()->error($e);
        });

        $whoops->register();

        $this->whoops = $whoops;
    }
}