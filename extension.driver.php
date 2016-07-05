<?php

class extension_asset_pipeline_scss extends Extension
{
    public function getSubscribedDelegates()
    {
        return array(
            array(
                'page' => '/extension/asset_pipeline/',
                'delegate' => 'RegisterPlugins',
                'callback' => 'register'
            )
        );
    }

    function register($context)
    {
        $context['plugins']['scss'] = array('output_type' => 'css', 'driver' => $this);
    }

    public function compile($content, $import_dir = null)
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
            return array('error' => $e->getMessage());
        }
        return array('output' => $output);
    }
}