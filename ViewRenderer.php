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
use Handlebars;

/**
 *
 */
class ViewRenderer extends BaseViewRenderer
{

    public $options = [];

    public $lightncandy;

    public function init()
    {


        // $this->lightncandy = new \LightnCandy($options);
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


        $phpStr = LightnCandy::compile(pathinfo($file, PATHINFO_BASENAME));  // compiled PHP code in $phpStr
        $renderer = LightnCandy::prepare($phpStr);

        return $renderer($params);
    }
}
