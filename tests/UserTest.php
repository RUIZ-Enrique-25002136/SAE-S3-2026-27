<?php

use App\Models\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    private function makeUser(string $password, bool $verified = true): User
    {
        return new User(1, 'test@example.com', password_hash($password, PASSWORD_DEFAULT), $verified);
    }

    public function testVerifyPasswordAcceptsCorrectPassword(): void
    {
        $this->assertTrue($this->makeUser('motdepasse')->verifyPassword('motdepasse'));
    }

    public function testVerifyPasswordRejectsWrongPassword(): void
    {
        $this->assertFalse($this->makeUser('motdepasse')->verifyPassword('mauvais'));
    }

    public function testPasswordIsStoredHashed(): void
    {
        $user = $this->makeUser('motdepasse');
        $this->assertNotSame('motdepasse', $user->passwordHash);
        $this->assertStringStartsWith('$2y$', $user->passwordHash);
    }

    public function testVerifiedFlagIsKept(): void
    {
        $this->assertFalse($this->makeUser('motdepasse', false)->verified);
        $this->assertTrue($this->makeUser('motdepasse', true)->verified);
    }
}
