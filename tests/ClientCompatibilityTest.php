<?php

namespace FuelSdk\Test;

use FuelSdk\ET_Client;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

final class ClientCompatibilityTest extends TestCase
{
    public function testDoRequestSupportsPhp85Signature(): void
    {
        $parameters = (new ReflectionMethod(ET_Client::class, '__doRequest'))->getParameters();

        $this->assertCount(6, $parameters);
        $this->assertSame('action', $parameters[2]->getName());
        $this->assertSame('oneWay', $parameters[4]->getName());
        $this->assertSame('uriParserClass', $parameters[5]->getName());
        $this->assertTrue($parameters[5]->isOptional());
        $this->assertTrue($parameters[5]->allowsNull());
    }
}
