<?php

namespace Isern\Test\Storage;

use Isern\Storage\Tag\TagEntity;

class TagEntityTest extends \PHPUnit_Framework_TestCase
{

    function testSetNameAttributeMethod()
    {
        $entity = new TagEntity;
        $entity->name = 'Isern Palaus';

        $this->assertEquals('isern-palaus', $entity->slug);
    }

}
