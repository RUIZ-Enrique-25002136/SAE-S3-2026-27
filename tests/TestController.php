<?php

use PHPUnit\Framework\TestCase;
use App\Controller\HomeController;
use App\Core\Request;

final class HomeControllerTest extends TestCase
{
    public function testIndexReturnsOkResponseWithWelcomeHtml(): void
    {
        $controller = new HomeController();
        $request = new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/']);

        $response = $controller->index($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('Bienvenue', $response->getContent());
    }

    public function testLegalNoticeReturnsOkResponse(): void
    {
        $controller = new HomeController();
        $request = new Request([], [], ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/legal-notice']);

        $response = $controller->legalNotice($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('Mentions légales', $response->getContent());
    }
}