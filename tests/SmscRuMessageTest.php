<?php

namespace NotificationChannels\SmscRu\Test;

use NotificationChannels\SmscRu\SmscRuMessage;
use PHPUnit\Framework\TestCase;

class SmscRuMessageTest extends TestCase
{
    public function test_it_can_accept_a_content_when_constructing_a_message(): void
    {
        $message = new SmscRuMessage('hello');

        $this->assertEquals('hello', $message->content);
    }

    public function test_it_can_accept_a_content_when_creating_a_message(): void
    {
        $message = SmscRuMessage::create('hello');

        $this->assertEquals('hello', $message->content);
    }

    public function test_it_can_set_the_content(): void
    {
        $message = (new SmscRuMessage())->content('hello');

        $this->assertEquals('hello', $message->content);
    }

    public function test_it_can_set_the_from(): void
    {
        $message = (new SmscRuMessage())->from('John_Doe');

        $this->assertEquals('John_Doe', $message->from);
    }

    public function test_it_can_set_the_send_at(): void
    {
        $message = (new SmscRuMessage())->sendAt($sendAt = \date_create());

        $this->assertEquals($sendAt, $message->sendAt);
    }
}
