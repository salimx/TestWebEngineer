<?php
namespace TestWebEngineer\SimpleRouter;

class Request implements RequestInterface
{


    /**
     * The HTTP method eg: GET, POST, PUT or DELETE
     *
     * @var string
     */
    protected $method;

    /**
     * The input values
     *
     * @var array
     */
    protected $inputValues;

    /**
     * Arguments send for selectiong the resource
     *
     * @var array
     */
    protected $arguments;

    /**
     * Request constructor.
     * @param string $method
     * @param [] $arguments
     * @param [] $inputValues
     */
    public function __construct($method, $arguments, $inputValues)
    {
        $this->setMethod($method)
             ->setArguments($arguments)
             ->setInputValues($inputValues);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see RequestInterface::getArguments()
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see RequestInterface::getInputValues()
     */
    public function getInputValues(): array
    {
        return $this->inputValues;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see RequestInterface::getMethod()
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    protected function setInputValues(array $inputValues)
    {
        $this->inputValues = $inputValues;

        return $this;
    }

    protected function setMethod(string $method)
    {
        $this->method = $method;

        return $this;
    }

    protected function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }
}
