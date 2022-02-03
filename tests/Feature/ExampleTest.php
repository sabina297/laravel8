<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    
    public function testHomePAgeISWorkingCorrectly()
    {
         $response = $this->get('/');
         $response->assertSeeText('Hello world!!');
    }

    public function testContactPageIsWorkingCorrectly()
    {
        $response = $this->get('/contact');
        $response->assertSeeText('Contact');
    }
}
