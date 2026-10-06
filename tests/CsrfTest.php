<?php

use PHPUnit\Framework\TestCase;
use app\core\Csrf;
final class CsrfTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
        $_POST = [];
    }

    public function testTokenIsCreatedOnceAndReused(): void
    {
        $token = Csrf::token();
        $this->assertSame(64, strlen($token));
        $this->assertSame($token, Csrf::token());
        $this->assertSame($token, $_SESSION['csrf']);
    }

    public function testRejectsWhenSessionHasNoToken(): void
    {
        $this->assertFalse(Csrf::check());
    }

    public function testRejectsEmptyTokenOnBothSides(): void
    {
        $_SESSION['csrf'] = '';
        $_POST['csrf'] = '';
        $this->assertFalse(Csrf::check());
    }

    public function testRejectsWrongToken(): void
    {
        csrfToken();
        $_POST['csrf'] = 'faux';
        $this->assertFalse(Csrf::check());
    }

    public function testAcceptsCorrectToken(): void
    {
        $_POST['csrf'] = Csrf::token();
        $this->assertTrue(Csrf::check());
    }
}
