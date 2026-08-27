<?php

namespace FuelSdk\Test;

use FuelSdk\ET_Client;
use FuelSdk\ET_Folder;
use PHPUnit\Framework\TestCase;

/**
* @covers ET_Folder
*/
final class FolderTest extends TestCase
{
    private $client;


    protected function setUp(): void
    {
        requireMarketingCloudConfig($this);
        $this->client = new ET_Client(true);


    }

    public function testCanCreateFolder()
    {
        $folderName = 'TestFolder' . uniqid();
        $result = $this->createFolder($folderName);
        $this->assertEquals($result->status, true);
        $this->assertEquals($result->results[0]->StatusMessage == 'Folder created successfully.', true);
        return ['result' => $result->results[0], 'folderName' => $folderName];

    }

    /**
    * @depends testCanCreateFolder
    */
    public function testCanGetFolder($input)
    {
        $folder = $input['result'];
        $getfolder = $this->getFolder($folder->NewID);
        //make sure the get was successful
        $this->assertEquals($getfolder->status, true);
        //compare the key of the folder
        $this->assertEquals($getfolder->results[0]->Name == $input['folderName'], true);
        return $getfolder->results[0];
    }

    /**
    * @depends testCanGetFolder
    */
    public function testCanUpdateFolder($folder)
    {
        $newName = 'Updated Folder Name';
        $updatedFolder = $this->updateFolder($folder, $newName);
        $this->assertEquals($updatedFolder->status, true);
        $this->assertEquals($updatedFolder->results[0]->StatusMessage == 'Folder updated successfully.', true);
        $getfolder = $this->getFolder($folder->ID);
        $this->assertEquals($getfolder->results[0]->Name == $newName, true);
        return $folder;
    }

    /**
    * @depends testCanUpdateFolder
    */
    public function testCanDeleteFolder($folder)
    {
        $result = $this->deleteFolder($folder);
        $this->assertEquals($result->status, true);
        $this->assertEquals($result->results[0]->StatusMessage == 'Folder deleted successfully.', true);

    }


    public function createFolder($folderName)
    {
        $folder = new ET_Folder();
        $folder->authStub = $this->client;
        $parent = $this->queryFolder('Name', 'My Emails');
        $folder->props = ['CustomerKey' => 'SDKExampleFolder' . uniqid(),
                                'Name' => $folderName,
                                'Description' => 'SDKExampleFolder',
                                'ContentType' => 'EMAIL',
                                'ParentFolder' => ['ID' => $parent->results[0]->ID],
                                'AllowChildren' => 'true',
                                'IsEditable' => 'true'];

        return $folder->post();

    }

    public function getFolder($folderId)
    {
        $folder = new ET_Folder();
        $folder->authStub = $this->client;
        $folder->filter = ['Property' => 'ID', 'SimpleOperator' => 'equals','Value' => $folderId];
        return $folder->get();
    }

    public function queryFolder($query, $value)
    {
        $folder = new ET_Folder();
        $folder->authStub = $this->client;
        $folder->filter = ['Property' => $query, 'SimpleOperator' => 'equals','Value' => $value];
        return $folder->get();
    }

    public function updateFolder($getfolder, $newName)
    {

        $folder = new ET_Folder();
        $folder->authStub = $this->client;
        $folder->props['ID'] = $getfolder->ID;
        $folder->props['Name'] = $newName;

        return $folder->patch();
    }

    public function deleteFolder($getfolder)
    {
        $folder = new ET_Folder();
        $folder->authStub = $this->client;
        $folder->props['ID'] = $getfolder->ID;

        return $folder->delete();
    }

}
