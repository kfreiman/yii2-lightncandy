<?php
/**
 * @link https://github.com/kfreiman/yii2-lightncandy
 * @copyright Copyright (c) Kirill Freiman
 * @license MIT <http://opensource.org/licenses/MIT>
 */

namespace kfreiman\lightncandy;

use Yii;
use yii\base\View;
use yii\base\ViewRenderer as BaseViewRenderer;
use LightnCandy;


class ViewRenderer extends BaseViewRenderer
{

    public $options = [];

    public $basedir; // only dirname($file) by default

    public $cache_preffix = 'LightnCandy_';

    public $extension = ['.handlebars', '.mustache'];

    public $flags =
        LightnCandy::FLAG_INSTANCE |
        LightnCandy::FLAG_NOESCAPE |
        LightnCandy::FLAG_SPVARS |
        LightnCandy::FLAG_RUNTIMEPARTIAL |
        LightnCandy::FLAG_HANDLEBARSJS
    ;

    public function init()
    {
        if (YII_ENV_DEV) {
            $this->flags = $this->flags |
                LightnCandy::FLAG_ERROR_EXCEPTION |
                LightnCandy::FLAG_RENDER_DEBUG
            ;
        }

        $this->flags = $this->flags | LightnCandy::FLAG_BARE;
    }

    /**
     * Renders a view file.
     *
     * This method is invoked by [[View]] whenever it tries to render a view.
     * Child classes must implement this method to render the given view file.
     *
     * @param View $view the view object used for rendering the file.
     * @param string $file the view file.
     * @param array $params the parameters to be passed to the view file.
     *
     * @return string the rendering result
     */
    public function render($view, $file, $params)
    {
        $this->basedir[] = dirname($file);

        $renderer = $this->getTemplateRenderer($file);

        return $renderer($params);
    }


    protected function getTemplateRenderer($file)
    {
        // include all {{> partials }}
        $content = $this->includePartial($file);

        $hash = md5($content);
        $key = $this->cache_preffix.$hash;

        $phpStr = Yii::$app->cache->get($key);

        if ($phpStr === false) {
            $phpStr = $this->getPhpStr($file);
            Yii::$app->cache->set($key, $phpStr);
        }

        return eval($phpStr.';');
    }


    protected function getPhpStr($file)
    {
        return LightnCandy::compile(
            '{{>'. pathinfo($file, PATHINFO_FILENAME) .'}}', // render as partial
            [
                'flags' => $this->flags,
                'basedir' => $this->basedir,
                'fileext' => $this->extension,
            ] + $this->options
        );
    }


    protected function includePartial($file)
    {
        $content = file_get_contents($file);
        return preg_replace_callback('/{{>(.+)}}/', [$this, "getPartial"], $content);
    }


    protected function getPartial($matchs)
    {
        $partName = trim($matchs[1]);
        foreach ($this->basedir as $dir) {
            foreach ($this->extension as $ext) {
                $fn = "$dir/$partName$ext";
                if (file_exists($fn)) {
                    return $this->includePartial($fn);
                }
            }
        }
    }
}
