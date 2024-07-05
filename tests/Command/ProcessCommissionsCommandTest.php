<?php

namespace App\Tests\Command;

use App\Command\ProcessCommissionsCommand;
use App\Service\CommissionProcessor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ProcessCommissionsCommandTest extends KernelTestCase
{
    private CommissionProcessor $commissionProcessor;
    private CommandTester $commandTester;
    private string $file;

    protected function setUp(): void
    {
        $this->commissionProcessor = $this->createMock(CommissionProcessor::class);

        $application = new Application();
        $application->add(new ProcessCommissionsCommand($this->commissionProcessor));

        $command = $application->find('process:commissions');
        $this->commandTester = new CommandTester($command);

        $this->file = __DIR__ . '/test_input.txt';

        file_put_contents($this->file, json_encode([
                'bin' => '45717360',
                'amount' => "100",
                'currency' => 'EUR'
            ]) . "\n");

        unlink($this->file);
    }

    public function testExecute(): void
    {
        $this->commissionProcessor
            ->expects($this->once())
            ->method('processFile')
            ->with($this->file);

        $this->commandTester->execute([
            'file' => $this->file
        ]);

        $this->assertEquals(0, $this->commandTester->getStatusCode());
    }

    public function testExecuteWithInvalidFile(): void
    {
        $this->commissionProcessor
            ->expects($this->once())
            ->method('processFile')
            ->with($this->file)
            ->willThrowException(new \Exception('Error : Test error msg'));

        $this->commandTester->execute([
            'file' => $this->file,
        ]);

        $this->assertEquals(1, $this->commandTester->getStatusCode());

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Error : Test error msg', $output);
    }
}
