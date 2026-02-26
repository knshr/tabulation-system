<?php

namespace App\Reports\Contracts;

use App\Models\Event;

interface ReportGeneratorInterface
{
    public function generate(Event $event, array $options = []): array;

    public function getName(): string;
}
