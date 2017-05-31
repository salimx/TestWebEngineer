<?php
namespace TestWebEngineer\SimpleRouter;

class Response implements ResponseInterface
{

    private $status      = 200;
    private $contentType = 'html';
    private $content     = '';

    public function __construct()
    {
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see ResponseInterface::getContent()
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see ResponseInterface::getContent()
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see ResponseInterface::getContent()
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see ResponseInterface::setContent()
     */
    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see ResponseInterface::setContentType()
     */
    public function setContentType(string $contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see ResponseInterface::setStatus()
     */
    public function setStatus(int $status)
    {
        $this->status = $status;

        return $this;
    }
}
