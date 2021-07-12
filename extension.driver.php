<?php

use ScssPhp\ScssPhp\Compiler;

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

        $compiler = new Compiler();
        $compiler->setImportPaths($import_dir);
        if (APP_MODE == 'administration') {
            $compiler->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::COMPRESSED);
        } else {
            $compiler->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::EXPANDED);
        }
        try {
            $output = $compiler->compileString($content)->getCss();
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
        return $output;
    }
}
