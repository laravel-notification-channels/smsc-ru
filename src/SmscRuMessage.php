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
     * @var \DateTimeInterface
     */
    public $sendAt;

    /**
     * Sign of a voice message.
     * When forming a voice message, you can transfer both text and attach files.
     * Files added to the message must be transferred using the POST method in the body of the http request.
     * 0 (default) is a regular message.
     * 1 - voice message.
     *
     * @var bool
     */
    public $call;

    /**
     * Voice used to read text (for voice messages only).
     * m (default) - male voice.
     * m2 is a male alternative voice.
     * w is a female voice.
     * w2 is a female alternative voice.
     *
     * @var string
     */
    public $voice;

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
     * @param  \DateTimeInterface|null  $sendAt
     *
     * @return $this
     */
    public function sendAt(\DateTimeInterface $sendAt = null)
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    /**
     * Set the sign of a voice message.
     *
     * @param  bool|null  $call
     *
     * @return $this
     */
    public function call($call = null)
    {
        $this->call = filter_var($call, FILTER_VALIDATE_BOOLEAN);

        return $this;
    }

    /**
     * Set the voice used to read text (for voice messages only).
     *
     * @param  string  $call
     *
     * @return $this
     */
    public function voice($voice = null)
    {
        $this->voice = $voice;

        return $this;
    }
}
