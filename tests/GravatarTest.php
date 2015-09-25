<?php namespace Gravatar\Tests;

use Gravatar\Gravatar;

class GravatarTest extends \PHPUnit_Framework_TestCase
{
    protected $gravatar     =   null;

    protected function setUp()
    {
        $email      =   'webloper@gmail.com';

        $this->gravatar =   new Gravatar( $email );
    }

    protected function tearDown()
    {
        $this->gravatar = null;
    }

    public function testGravatarURL() {

        $url        =   $this->gravatar->url();

        $this->assertNotNull( $url , 'message');
    }
}
