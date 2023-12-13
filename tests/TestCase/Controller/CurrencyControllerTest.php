<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\CurrencyController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\CurrencyController Test Case
 *
 * @uses \App\Controller\CurrencyController
 */
class CurrencyControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Currency',
    ];

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\CurrencyController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test convert method
     *
     * @return void
     * @uses \App\Controller\CurrencyController::convert()
     */
    public function testConvert(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
