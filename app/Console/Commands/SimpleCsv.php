<?php

namespace App\Console\Commands;

use App\Application\RssDataFeedService;
use App\Validation\CsvCommandValidator;
use Illuminate\Console\Command;

class SimpleCsv extends Command
{
    protected $signature = 'csv:simple {url} {path}';
    protected $description = 'Export RSS feed data to CSV';

    private RssDataFeedService $rssDataFeedService;
    private CsvCommandValidator $validator;

    public function __construct(
        RssDataFeedService $rssDataFeedService,
        CsvCommandValidator $validator
    ) {
        parent::__construct();
        $this->rssDataFeedService = $rssDataFeedService;
        $this->validator = $validator;
    }

    public function handle(): void
    {
        $url = $this->argument('url');
        $path = $this->argument('path');

        if (!$this->validator->isUrlValid($url)) {
            $this->error('Incorrect feed url');
            return;
        }

        if (!$this->validator->isCsvFilePathValid($path)) {
            $this->error('Incorrect file path');
            return;
        }

        try {
            $this->rssDataFeedService->exportToCsv($url, $path);
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
            return;
        }

        $this->info('Data saved successfully!');
    }
}
