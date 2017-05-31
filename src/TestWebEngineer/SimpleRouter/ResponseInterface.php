<?php
namespace TestWebEngineer\SimpleRouter;

interface ResponseInterface
{

    /**
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     *
     * @return string
     */
    public function getContent(): string;

    /**
     *
     * @return string
     */
    public function getContentType(): string;


    /**
     * @param int $status
     * @return ResponseInterface
     */
    public function setStatus(int $status);

    /**
     * @param string $content
     * @return ResponseInterface
     */
    public function setContent(string $content);

    /**
     * @param string $contentType
     * @return ResponseInterface
     */
    public function setContentType(string $contentType);
}
