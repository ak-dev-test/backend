<?php

namespace AkDevTodo\Backend\Tools;

class Response
{
    private bool $success = true;
    private string $message = '';
    private ?array $data = null;

    public function __construct()
    {
    }


    public function __toString(): string
    {
        $result = [
            'success' => $this->success,
            'data' => $this->data,
            'message' => $this->message,
        ];

        return json_encode($result);
    }


    /**
     * @param bool $success
     * @return Response
     */
    public function setSuccess(bool $success): Response
    {
        $this->success = $success;

        return $this;
    }


    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): Response
    {
        $this->message = $message;

        return $this;
    }


    /**
     * @param array|null $data
     * @return $this
     */
    public function setData(?array $data): Response
    {
        $this->data = $data;

        return $this;
    }
}