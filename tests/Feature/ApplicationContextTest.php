<?php

namespace TestSuite\Feature;

use App\ApplicationContext;
use App\Http\XHttpHeader;

class ApplicationContextTest extends \TestSuite\TestCase
{
    /** @var ApplicationContext */
    private $appContext;

    public function setUp()
    {
        parent::setUp();
        // Note. 통합 테스트는 프레임워크를 부트시키므로, 이미 ApplicationContext 인스턴스가 생성된 상태입니다.
        $this->appContext = $this->app->make(ApplicationContext::class);
    }

    public function testIsRunningInConsole()
    {
        $this->assertTrue($this->appContext->isRunningInConsole());
    }

    public function testAppEnv()
    {
        $this->assertEquals('testing', $this->appContext->getAppEnv());
    }

    public function testGetAppVersion()
    {
        $expected = \Config::get('app.version');
        $this->assertEquals($expected, $this->appContext->getAppVersion());
    }

    public function testGetTransactionId()
    {
        $this->assertNotEmpty($this->appContext->getTransactionId());
    }

    public function testClientSubmittedRequestIdIsPreferredForTransactionId()
    {
        $clientSubmittedRequestId = $this->appContext->getTransactionId();
        $response = $this->get('/api', [
            XHttpHeader::REQUEST_ID => $clientSubmittedRequestId,
        ]);
        $responseHeaderBag = $response->headers;
        $serverReturnedTransactionId = $responseHeaderBag->get(XHttpHeader::TRANSACTION_ID);
        $this->assertEquals($clientSubmittedRequestId, $serverReturnedTransactionId);
    }

    public function testGetTraceNumber()
    {
        $this->assertEquals(0, $this->appContext->getTraceNumber());
    }

    public function testIncreaseTraceNumber()
    {
        $this->appContext->increaseTraceNumber();
        $this->assertEquals(1, $this->appContext->getTraceNumber());
    }

    public function testSucceedPreviousContext()
    {
        $previousContext = $this->appContext;
        $currentTransactionId = \Ramsey\Uuid\Uuid::uuid4();
        $currentUser = \Myshop\Domain\Model\User::createDefaultUser([
            'name' => 'foo',
            'email' => 'foo@example.com',
        ]);
        $currentAppContext = new ApplicationContext([
            'transactionId' => $currentTransactionId,
            'traceNumber' => 0,
            'user' => $currentUser,
        ]);
        $currentAppContext->succeedPreviousContext($previousContext);
        $this->assertEquals($currentAppContext->getTransactionId(), $this->appContext->getTransactionId());
        $this->assertEquals($currentAppContext->getTraceNumber(), $this->appContext->getTraceNumber());
        $this->assertEquals($currentAppContext->getUser(), $this->appContext->getUser());
    }

    public function testGetUser()
    {
        $this->assertEquals('CLI', $this->appContext->getUser()->name);
    }
}
