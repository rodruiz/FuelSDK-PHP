<?php
require __DIR__ . '/../vendor/autoload.php';
use FuelSdk\ET_Client;
use FuelSdk\ET_List;

try {

    $myclient = new ET_Client();
    $NewListName = 'PHPSDKList';
    $subs = [];
    $subs[] = ['EmailAddress' => 'SDKTest9090@bh.exacttarget.com', 'Attributes' => [['Name' => 'First Name', 'Value' => 'Mac'],['Name' => 'List Name', 'Value' => 'Testing']]];
    $subs[] = ['EmailAddress' => 'SDKTest9091@bh.exacttarget.com', 'Attributes' => [['Name' => 'First Name', 'Value' => 'Mac'],['Name' => 'List Name', 'Value' => 'Testing']]];

    // Example using AddSubscribersToLists() method
    // Typically this method will be used with a pre-existing list but for testing purposes one is being created.

    // Create List
    print "Create List \n";
    $postContent = new ET_List();
    $postContent->authStub = $myclient;
    $postContent->props = ['ListName' => $NewListName, 'Description' => 'This list was created with the PHPSDK', 'Type' => 'Private'];
    $postResponse = $postContent->post();
    print_r('Post Status: ' . ($postResponse->status ? 'true' : 'false') . "\n");
    print 'Code: ' . $postResponse->code . "\n";
    print 'Message: ' . $postResponse->message . "\n";
    print 'Results Length: ' . count($postResponse->results) . "\n";
    print 'Results: ' . "\n";
    print_r($postResponse->results);
    print "\n---------------\n";

    if ($postResponse->status) {
        $newListID = $postResponse->results[0]->NewID;
        // Adding Multiple Subscribers To a List in Bulk
        print "Adding Multiple Subscribers To a List in Bulk \n";
        $response = $myclient->AddSubscribersToLists($subs, [$newListID]);
        print_r('Response Status: ' . ($response->status ? 'true' : 'false') . "\n");
        print 'Code: ' . $response->code . "\n";
        print 'Message: ' . $response->message . "\n";
        print 'Results Length: ' . count($response->results) . "\n";
        print "Results: \n";
        print_r($response->results);
        print "\n---------------\n";

        // Delete List
        print "Delete List \n";
        $deleteList = new ET_List();
        $deleteList->authStub = $myclient;
        $deleteList->props = ['ID' => $newListID];
        $deleteResponse = $deleteList->delete();
        print_r('Delete Status: ' . ($deleteResponse->status ? 'true' : 'false') . "\n");
        print 'Code: ' . $deleteResponse->code . "\n";
        print 'Message: ' . $deleteResponse->message . "\n";
        print 'Results Length: ' . count($deleteResponse->results) . "\n";
        print 'Results: ' . "\n";
        print_r($deleteResponse->results);
        print "\n---------------\n";

    }
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

?>


