<?php

namespace App\Services;

use App\Models\Event;
use App\Reports\Contracts\ReportGeneratorInterface;

class ReportService
{
    /** @var array<string, ReportGeneratorInterface> */
    private array $generators = [];

    public function registerGenerator(ReportGeneratorInterface $generator): void
    {
        $this->generators[$generator->getName()] = $generator;
    }

    public function generate(string $reportName, Event $event, array $options = []): array
    {
        if (!isset($this->generators[$reportName])) {
            throw new \InvalidArgumentException("Unknown report type: {$reportName}");
        }

        return $this->generators[$reportName]->generate($event, $options);
    }

    public function getAvailableReports(): array
    {
        return array_keys($this->generators);
    }
}
