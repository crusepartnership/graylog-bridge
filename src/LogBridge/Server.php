<?php
namespace LogBridge;

class Server
{
    protected $log_server;
    protected $response;

    public function setLogServer($log_server)
    {
        $this->log_server = $log_server;
    }

    public function receive($post)
    {
        if (isset($_POST['messages'])) {
            $bridge = new Bridge();
            $bridge->setLogServer($this->log_server);
            $messages = json_decode($_POST['messages'], true);
            foreach ($messages as $message) {
                $bridge->forwardMessage($message);
            }
        } else {
            throw new \Exception("messages not set in POST");
        }
    }

    public function respond()
    {
    }
}

