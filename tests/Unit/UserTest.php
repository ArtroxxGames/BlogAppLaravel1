<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    #[DataProvider('names')]
    public function test_initials(string $name, string $expected): void
    {
        $this->assertSame($expected, (new User(['name' => $name]))->initials());
    }

    public static function names(): array
    {
        return [
            ['Ana', 'A'],
            ['ana pérez', 'AP'],
            ['Juan Carlos  Gómez', 'JC'],
            ['Ángel Núñez', 'ÁN'],
        ];
    }
}
