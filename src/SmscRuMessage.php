<?php

namespace NotificationChannels\SmscRu;

class SmscRuMessage
{
    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    public $from = '';

    /**
     * The message content.
     *
     * @var string
     */
    public $content = '';

    /**
     * Time of sending a message.
     *
     * @var int
     */
    public $time;

    /**
     * Timezone of sending a message.
     *
     * @var int
     */
    public $tz;

    /**
     * Create a new message instance.
     *
     * @param  string $content
     *
     * @return static
     */
    public static function create($content = '')
    {
        return new static($content);
    }

    /**
     * @param  string  $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the message content.
     *
     * @param  string  $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the phone number or sender name the message should be sent from.
     *
     * @param  string  $from
     *
     * @return $this
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set the time the message should be sent.
     * Accept unix timestamp, ex. 1495459379.
     *
     * @param  int  $time
     *
     * @return $this
     */
    public function timestamp($time)
    {
        if (is_int($time)) {
            $this->time = $time;
        }

        return $this;
    }

    /**
     * Set the timezone relatively to Europe/Moscow.
     * The value can be negative.
     *
     * @param  int  $tz
     *
     * @return $this
     */
    public function timezone($tz)
    {
        if (is_int($tz)) {
            $this->tz = $tz;
        }

        return $this;
    }
}
