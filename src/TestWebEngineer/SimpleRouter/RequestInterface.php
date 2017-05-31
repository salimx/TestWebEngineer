<?php
namespace TestWebEngineer\SimpleRouter;

interface RequestInterface
{

    /**
     *
     * @return array
     */
    public function getInputValues(): array;

    /**
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     *
     * @return array
     */
    public function getArguments(): array;
}
