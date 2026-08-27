<?php

namespace FuelSdk\Test;

use FuelSdk\ET_Client;
use FuelSdk\ET_Subscriber;
use PHPUnit\Framework\TestCase;

/**
* @covers ET_Subscriber
*/
final class SubscriberTest extends TestCase
{
    private $client;


    protected function setUp(): void
    {
        requireMarketingCloudConfig($this);
        $this->client = new ET_Client(true);
    }

    public function testCanCreateSubscriber()
    {
        $result = $this->createSubscriber();
        $this->assertEquals($result->status, true);
        $this->assertEquals($result->results[0]->StatusMessage == 'Created Subscriber.', true);
        return $result->results[0];
    }

    /**
    * @depends testCanCreateSubscriber
    */
    public function testCanGetSubscriber($subscriber)
    {
        $getsubscriber = $this->getSubscriber($subscriber->NewID);
        //make sure the get was successful
        $this->assertEquals($getsubscriber->status, true);
        //compare the key of the subscriber
        $this->assertEquals($getsubscriber->results[0]->SubscriberKey == $subscriber->Object->SubscriberKey, true);
        return $getsubscriber->results[0];
    }

    /**
    * @depends testCanGetSubscriber
    */
    public function testCanUpdateSubscriber($subscriber)
    {
        $newEmail = 'updatedemail@salesforce.com';
        $updatedSubscriber = $this->updateSubscriber($subscriber, $newEmail);
        $this->assertEquals($updatedSubscriber->status, true);
        $this->assertEquals($updatedSubscriber->results[0]->StatusMessage == 'Updated Subscriber.', true);
        $getsubscriber = $this->getSubscriber($subscriber->ID);

        $this->assertEquals($getsubscriber->results[0]->EmailAddress == $newEmail, true);
        return $subscriber;
    }

    /**
    * @depends testCanUpdateSubscriber
    */
    public function testCanDeleteSubscriber($subscriber)
    {
        $result = $this->deleteSubscriber($subscriber);
        $this->assertEquals($result->status, true);
        $this->assertEquals($result->results[0]->StatusMessage == 'Subscriber deleted', true);

    }

    public function testCanUpsertSubscriber()
    {
        $listtest = new ListTest();
        $list = $listtest->createList();
        $listID = $list->results[0]->NewID;
        $subscriber = new ET_Subscriber();
        $subscriber->props = ['SubscriberKey' => 'PHPSDKSubscriber' . uniqid(),
                                    'EmailAddress' => uniqid() . '@salesforce.com',
                                    'Lists' => ['ID' => $listID],
                                    'Attributes' => ['Name' => 'First Name', 'Value' => 'FirstName' . uniqid()],
                                    'Attributes' => ['Name' => 'Last Name', 'Value' => 'LastName' . uniqid()],
                                    ];

        //call upsert to create a new subscriber.
        $result = $this->upsertSubscriber($subscriber);
        $this->assertEquals($result->status, true);
        $this->assertEquals($result->results[0]->StatusMessage == 'Updated Subscriber.', true);

        //try to get the subscriber we created above
        $getsubscriber = $this->getSubscriber($result->results[0]->Object->ID);
        //make sure the get was successful
        $this->assertEquals($getsubscriber->status, true);
        //call the upsert again ... but this time we are going to update the existing one by passing ID field populated
        $subscriber->props = ['ID' => $getsubscriber->results[0]->ID, 'EmailAddress' => 'updatedemail@salesforce.com'];
        $result = $this->upsertSubscriber($subscriber);
        $this->assertEquals($result->status, true);
        $this->assertEquals($result->results[0]->StatusMessage == 'Updated Subscriber.', true);

        //delete the subscriber
        $result = $this->deleteSubscriber($getsubscriber->results[0]);

        $this->assertEquals($result->status, true);
        $this->assertEquals($result->results[0]->StatusMessage == 'Subscriber deleted', true);

        $result = $listtest->deleteList($list->results[0]->Object);
        $this->assertEquals($result->status, true);

    }

    public function createSubscriber()
    {
        $list = new ListTest();
        $listID = $list->createList()->results[0]->NewID;

        $subscriber = new ET_Subscriber();
        $subscriber->authStub = $this->client;

        $subscriber->props = ['SubscriberKey' => 'PHPSDKSubscriber' . uniqid(),
                                    'EmailAddress' => uniqid() . '@salesforce.com',
                                    'Lists' => ['ID' => $listID],
                                    'Attributes' => ['Name' => 'First Name', 'Value' => 'FirstName' . uniqid()],
                                    'Attributes' => ['Name' => 'Last Name', 'Value' => 'LastName' . uniqid()],
                                    ];

        return $subscriber->post();

    }

    public function upsertSubscriber($subscriber)
    {
        $subscriber->authStub = $this->client;

        return $subscriber->put();

    }

    public function getSubscriber($subscriberId)
    {
        $subscriber = new ET_Subscriber();
        $subscriber->authStub = $this->client;
        $subscriber->filter = ['Property' => 'ID', 'SimpleOperator' => 'equals','Value' => $subscriberId];
        return $subscriber->get();
    }

    public function updateSubscriber($getsubscriber, $newEmail)
    {

        $subscriber = new ET_Subscriber();
        $subscriber->authStub = $this->client;
        $subscriber->props['ID'] = $getsubscriber->ID;
        $subscriber->props['EmailAddress'] = $newEmail;

        return $subscriber->patch();
    }

    public function deleteSubscriber($getsubscriber)
    {
        $subscriber = new ET_Subscriber();
        $subscriber->authStub = $this->client;
        $subscriber->props['ID'] = $getsubscriber->ID;

        return $subscriber->delete();
    }

}
