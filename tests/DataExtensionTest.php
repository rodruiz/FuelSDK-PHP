<?php

namespace FuelSdk\Test;

use FuelSdk\ET_Client;
use FuelSdk\ET_DataExtension;
use PHPUnit\Framework\TestCase;

/**
* @covers ET_DataExtension
*/
final class DataExtensionTest extends TestCase
{
    private $client;


    protected function setUp(): void
    {
        requireMarketingCloudConfig($this);
        $this->client = new ET_Client(true);
    }

    public function testCanCreateDataExtension()
    {
        $result = $this->createDataExtension();
        $this->assertEquals($result->status, true);
        $this->assertEquals($result->results[0]->StatusMessage == 'Data Extension created.', true);
        return $result->results[0];
    }

    /**
    * @depends testCanCreateDataExtension
    */
    public function testCanGetDataExtension($dataextension)
    {
        $getDE = $this->getDataExtension($dataextension->Object->CustomerKey);
        //make sure the get was successful
        $this->assertEquals($getDE->status, true);
        //compare the content area name
        $this->assertEquals($getDE->results[0]->Name == $dataextension->Object->Name, true);
        return $getDE->results[0];
    }

    /**
    * @depends testCanGetDataExtension
    */
    public function testCanUpdateDataExtension($dataextension)
    {
        $newDEName = 'Updated DE Name';
        $updatedDE = $this->updateDataExtension($dataextension, $newDEName);
        $getDE = $this->getDataExtension($dataextension->CustomerKey);
        $this->assertEquals($getDE->results[0]->Name == $newDEName, true);
        return $dataextension;
    }

    /**
    * @depends testCanUpdateDataExtension
    */
    public function testCanDeleteDataExtension($dataextension)
    {
        $result = $this->deleteDataExtension($dataextension);
        $this->assertEquals($result->status, true);

    }

    public function createDataExtension()
    {
        $dataextension = new ET_DataExtension();
        $dataextension->authStub = $this->client;
        $dataextension->props = ['Name' => 'SDKDataExtension' . uniqid(), 'Description' => 'SDK Created Data Extension', 'CustomerKey' => 'CustKey' . uniqid()];
        $dataextension->columns = [];
        $dataextension->columns[] = ['Name' => 'Key', 'FieldType' => 'Text', 'IsPrimaryKey' => 'true','MaxLength' => '100', 'IsRequired' => 'true'];
        $dataextension->columns[] = ['Name' => 'Value', 'FieldType' => 'Text'];
        return $dataextension->post();

    }

    public function getDataExtension($customerkey)
    {
        $dataextension = new ET_DataExtension();
        $dataextension->authStub = $this->client;
        $dataextension->props = ['Name','Description','CustomerKey','ObjectID'];
        $dataextension->filter = ['Property' => 'CustomerKey', 'SimpleOperator' => 'equals','Value' => $customerkey];

        return $dataextension->get();
    }


    public function updateDataExtension($de, $updatedName)
    {
        $dataextension = new ET_DataExtension();
        $dataextension->authStub = $this->client;
        $dataextension->props = ['CustomerKey' => $de->CustomerKey, 'Name' => $updatedName];
        $dataextension->columns = [];
        return $dataextension->patch();
    }

    public function deleteDataExtension($de)
    {
        $dataextension = new ET_DataExtension();
        $dataextension->authStub = $this->client;
        $dataextension->props = ['ObjectID' => $de->ObjectID];

        return $dataextension->delete();
    }



}
