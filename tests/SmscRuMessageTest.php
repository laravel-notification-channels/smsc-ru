<?php

namespace NotificationChannels\SmscRu\Test;

use NotificationChannels\SmscRu\SmscRuMessage;

class SmscRuMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_accept_a_content_when_constructing_a_message()
    {
        $message = new SmscRuMessage('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_accept_a_content_when_creating_a_message()
    {
        $message = SmscRuMessage::create('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_content()
    {
        $message = (new SmscRuMessage())->content('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_from()
    {
        $message = (new SmscRuMessage())->from('John_Doe');

        $this->assertEquals('John_Doe', $message->from);
    }

    /** @test */
    public function it_can_set_the_timestamp()
    {
        $timestamp = time();
        $message = (new SmscRuMessage())->timestamp($timestamp);

        $this->assertEquals($timestamp, $message->time);
    }

    /** @test */
    public function it_can_set_the_timezone()
    {
        $tz = -3;
        $message = (new SmscRuMessage())->timezone($tz);

        $this->assertEquals($tz, $message->tz);
    }
}
