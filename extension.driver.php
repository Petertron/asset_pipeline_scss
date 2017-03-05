<?php

class extension_Asset_pipeline_scss extends Extension
{
    public $error;

    public function getOutputType()
    {
        return 'css';
    }

    public function getSubscribedDelegates()
    {
        return array(
            array(
                'page' => '/extension/asset_pipeline/',
                'delegate' => 'RegisterPreprocessors',
                'callback' => 'register'
            )
        );
    }

    public function register($context)
    {
        $context['preprocessors']['scss'] = $this;
    }

    public function convert($content, $import_dir = null)
    {
        require_once __DIR__ . '/lib/scssphp/scss.inc.php';

        $compiler = new Leafo\ScssPhp\Compiler();
        $compiler->setImportPaths($import_dir);
        if (APP_MODE == 'administration') {
            $compiler->setFormatter('Leafo\ScssPhp\Formatter\Crunched');
        } else {
            $compiler->setFormatter('Leafo\ScssPhp\Formatter\Expanded');
        }
        try {
            $output = $compiler->compile($content);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
        return $output;
    }
}
