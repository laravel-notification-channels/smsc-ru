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
     * @var \DateTime
     */
    public $time;

    /**
     * Timezone of sending a message.
     *
     * @var \DateTimeZone
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
     *
     * @param  \DateTime|null  $time
     *
     * @return $this
     */
    public function timestamp(\DateTime $time = null)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Set the timezone the message should be sent.
     *
     * @param  \DateTimeZone  $tz
     *
     * @return $this
     */
    public function timezone(\DateTimeZone $tz = null)
    {
        $this->tz = $tz;

        return $this;
    }
}
