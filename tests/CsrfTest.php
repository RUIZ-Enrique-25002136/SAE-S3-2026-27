<?php

use PHPUnit\Framework\TestCase;

final class CsrfTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
        $_POST = [];
    }

    public function testTokenIsCreatedOnceAndReused(): void
    {
        $token = csrfToken();
        $this->assertSame(64, strlen($token));
        $this->assertSame($token, csrfToken());
        $this->assertSame($token, $_SESSION['csrf']);
    }

    public function testRejectsWhenSessionHasNoToken(): void
    {
        $this->assertFalse(checkCsrf());
    }

    public function testRejectsEmptyTokenOnBothSides(): void
    {
        $_SESSION['csrf'] = '';
        $_POST['csrf'] = '';
        $this->assertFalse(checkCsrf());
    }

    public function testRejectsWrongToken(): void
    {
        csrfToken();
        $_POST['csrf'] = 'faux';
        $this->assertFalse(checkCsrf());
    }

    public function testAcceptsCorrectToken(): void
    {
        $_POST['csrf'] = csrfToken();
        $this->assertTrue(checkCsrf());
    }
}
