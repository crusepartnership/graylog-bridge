<?php
namespace LogBridge;

class Bridge
{

    public function setLogServer($log_server)
    {
        $this->log_server = $log_server;
    }

    public function forwardMessage(array $message) {
        $transport = new \Gelf\Transport\UdpTransport($this->log_server, 12201, \Gelf\Transport\UdpTransport::CHUNK_SIZE_LAN);
        $publisher = new \Gelf\Publisher();
        $publisher->addTransport($transport);
        syslog(6,"Sending message " . $message['short_message']);
        $gelf_message = new \Gelf\Message();
        $gelf_message->setHost($message['host']);
        $gelf_message->setShortMessage($message['short_message']);
        $gelf_message->setFullMessage($message['full_message']);
        if (isset($message['timestamp'])) {
            $gelf_message->setTimestamp($message['timestamp']);
        }
        $gelf_message->setLevel($message['level']);
        $gelf_message->setLine($message['line']);
        $additional_keys = array_filter(array_keys($message), function ($k){ return strpos($k, '_')===0; });
        $additional_fields = array_intersect_key($message, array_flip($additional_keys));
        foreach ($additional_fields as $key => $value) {
            $gelf_message->setAdditional(substr($key, 1), $value);
        }
        // set the time that the log reached here, mostly so we can see the difference with logs from apps
        $gelf_message->setAdditional('logged_timestamp', date("Y-m-dTH:i:s.Z"));
        $publisher->publish($gelf_message);
    }

}
