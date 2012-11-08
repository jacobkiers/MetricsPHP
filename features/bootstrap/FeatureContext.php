<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        foreach(glob(__DIR__.'/*Context.php') as $file) {
            $class_name = basename($file, '.php');
            if ($class_name == 'FeatureContext') {
                continue;
            }

            $name = substr($class_name, 0, strpos($class_name, 'Context'));

            $context = new $class_name();
            $this->useContext($name, $context);
        }
    }
}
