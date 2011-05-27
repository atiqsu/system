<?php
namespace aura\framework\web\not_found;
use aura\signal\Manager as SignalManager;
use aura\signal\HandlerFactory;
use aura\signal\ResultFactory;
use aura\signal\ResultCollection;
use aura\web\Context;
use aura\web\ResponseTransfer;

/**
 * Test class for Page.
 * Generated by PHPUnit on 2011-05-27 at 11:01:03.
 */
class PageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Page
     */
    protected $page;

    protected function newPage($params = array())
    {
        return new Page(
            new Context($GLOBALS),
            new SignalManager(new HandlerFactory, new ResultFactory, new ResultCollection),
            new ResponseTransfer,
            $params
        );
    }
    
    /**
     * @todo Implement testActionIndex().
     */
    public function testActionIndex()
    {
        $page = $this->newPage(array(
            'action' => 'index',
        ));
        $xfer = $page->exec();
        
        $html = '<html>
    <head>
        <title>Not Found</title>
    </head>
    <body>
        <h1>404 Not Found</h1>
        <p>No controller found for <code>NULL</code></p>
        <p>Please check that your config has:</p>
        <ol>
            <li>An <code>aura\\router\\Map</code> route for the path <code>\'/\'</code></li>
            <li>A <code>[\'values\'][\'controller\']</code> value for the mapped route</li>
            <li>A <code>$di->params[\'aura\\web\\ControllerFactory\'][\'map\']</code> entry for the controller value.</li>
        </ol>
    </body>
</html>';
        
        $this->assertType('aura\web\ResponseTransfer', $xfer);
        $this->assertSame(404, $xfer->getStatusCode());
        $this->assertSame($html, $xfer->getContent());
        $this->assertNull($xfer->getView());
    }
}