<?php

namespace Tests\Firebase\V3;

use Firebase\Auth\Token\Handler;
use Firebase\Database;
use Firebase\ServiceAccount;
use Firebase\V3\Firebase;
use GuzzleHttp\Psr7\Uri;
use Tests\FirebaseTestCase;

class FirebaseTest extends FirebaseTestCase
{
    /**
     * @var ServiceAccount|\PHPUnit_Framework_MockObject_MockObject
     */
    private $serviceAccount;

    /**
     * @var Uri
     */
    private $databaseUri;

    /**
     * @var Firebase
     */
    private $firebase;

    protected function setUp()
    {
        $this->serviceAccount = $this->createServiceAccountMock();
        $this->databaseUri = new Uri('https://database-uri.tld');

        $this->firebase = new Firebase($this->serviceAccount, $this->databaseUri);
    }

    public function testCreateFromServiceAccount()
    {
        $this->assertInstanceOf(Firebase::class, Firebase::fromServiceAccount($this->serviceAccount));
        $this->assertInstanceOf(Firebase::class, Firebase::fromServiceAccount($this->serviceAccount, $this->databaseUri));
        $this->assertInstanceOf(Firebase::class, Firebase::fromServiceAccount($this->serviceAccount, (string) $this->databaseUri));
    }

    public function testWithDatabaseUri()
    {
        $firebase = $this->firebase->withDatabaseUri('https://some-other-uri.tld');

        $this->assertInstanceOf(Firebase::class, $firebase);
        $this->assertNotSame($this->firebase, $firebase);
    }

    public function testGetDatabase()
    {
        $db = $this->firebase->getDatabase();

        $this->assertInstanceOf(Database::class, $db);
    }

    public function testAsUserWithClaims()
    {
        $firebase = $this->firebase->asUserWithClaims('uid');
        $this->assertInstanceOf(Firebase::class, $firebase);
        $this->assertNotSame($this->firebase, $firebase);
    }

    public function testGetTokenHandler()
    {
        $this->assertInstanceOf(Handler::class, $this->firebase->getTokenHandler());
    }
}
